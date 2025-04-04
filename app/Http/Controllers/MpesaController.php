<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Donation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MpesaController extends Controller
{
    protected function getAccessToken()
    {
        $consumerKey = config('services.mpesa.consumer_key');
        $consumerSecret = config('services.mpesa.consumer_secret');
        $url = config('services.mpesa.env') === 'sandbox'
            ? 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'
            : 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $response = Http::withBasicAuth($consumerKey, $consumerSecret)
            ->withOptions(['verify' => false])
            ->get($url);

        $result = $response->json();
        Log::info('Manual Token Response:', ['result' => $result]);

        if (!$response->successful() || !isset($result['access_token'])) {
            throw new \Exception('Failed to obtain access token: ' . ($result['error'] ?? 'Unknown error'));
        }

        return $result['access_token'];
    }

    protected function getTimestamp()
    {
        return now()->format('YmdHis'); // YYYYMMDDHHMMSS
    }

    protected function generatePassword($shortcode, $passkey, $timestamp)
    {
        return base64_encode($shortcode . $passkey . $timestamp);
    }

    public function initiateDonation(Request $request)
    {
        Log::info('Initiate Donation Request:', $request->all());

        $request->validate([
            'phone' => 'required|regex:/^254[71][0-9]{8}$/',
            'amount' => 'required|numeric|min:1',
        ]);

        $phone = $request->phone;
        $amount = (int) $request->amount;
        $shortcode = config('services.mpesa.shortcode');
        $passkey = config('services.mpesa.passkey');
        $callbackUrl = config('services.mpesa.callback_url');
        $transactionId = 'DONATION_' . time();
        $userId = Auth::id();

        try {
            $token = $this->getAccessToken();
            $timestamp = $this->getTimestamp();
            $password = $this->generatePassword($shortcode, $passkey, $timestamp);

            $payload = [
                'BusinessShortCode' => $shortcode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => $amount,
                'PartyA' => $phone,
                'PartyB' => $shortcode,
                'PhoneNumber' => $phone,
                'CallBackURL' => $callbackUrl,
                'AccountReference' => $transactionId,
                'TransactionDesc' => 'Donation to Platform'
            ];

            $url = config('services.mpesa.env') === 'sandbox'
                ? 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest'
                : 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])->withOptions(['verify' => false])->post($url, $payload);

            $result = $response->json();
            Log::info('Manual STK Push Response:', ['result' => $result]);

            if (!$response->successful() || !isset($result['ResponseCode']) || $result['ResponseCode'] != '0') {
                Log::error('Manual STK Push Failed:', ['result' => $result]);
                return response()->json([
                    'success' => false,
                    'message' => $result['errorMessage'] ?? 'Failed to initiate payment',
                ]);
            }

            $checkoutRequestId = $result['CheckoutRequestID'];
            $donation = Donation::create([
                'user_id' => $userId,
                'phone' => $phone,
                'amount' => $amount,
                'transaction_id' => $transactionId,
                'status' => 'pending',
                'checkout_request_id' => $checkoutRequestId,
                'transaction_type' => 'CustomerPayBillOnline',
                'business_shortcode' => $shortcode,
                'callback_url' => $callbackUrl,
            ]);

            // Query transaction status and update
            $transactionStatus = $this->queryTransactionStatus($checkoutRequestId);
            $this->updatePaymentStatus($donation, $transactionStatus);

            return response()->json([
                'success' => $transactionStatus['ResultCode'] == '0',
                'message' => $transactionStatus['ResultCode'] == '0'
                    ? 'Thank you for your donation!'
                    : 'Payment failed: ' . $transactionStatus['ResultDesc'],
                'checkout_request_id' => $checkoutRequestId,
            ]);
        } catch (\Exception $e) {
            Log::error('M-Pesa Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error processing payment: ' . $e->getMessage(),
            ]);
        }
    }

    protected function queryTransactionStatus($checkoutRequestId)
    {
        $token = $this->getAccessToken();
        $shortcode = config('services.mpesa.shortcode');
        $passkey = config('services.mpesa.passkey');
        $timestamp = $this->getTimestamp();
        $password = $this->generatePassword($shortcode, $passkey, $timestamp);

        $url = config('services.mpesa.env') === 'sandbox'
            ? 'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query'
            : 'https://api.safaricom.co.ke/mpesa/stkpushquery/v1/query';

        $payload = [
            'BusinessShortCode' => $shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'CheckoutRequestID' => $checkoutRequestId,
        ];

        $maxAttempts = 10;
        $attempts = 0;
        $delay = 3; // seconds

        while ($attempts < $maxAttempts) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ])->withOptions(['verify' => false])->post($url, $payload);

                $result = $response->json();
                Log::info('STK Push Query Response:', ['result' => $result]);

                if ($response->successful() && isset($result['ResultCode'])) {
                    return [
                        'ResultCode' => $result['ResultCode'],
                        'ResultDesc' => $result['ResultDesc'],
                        'TransactionStatus' => $result['ResultCode'] == '0' ? 'Success' : 'Failed',
                    ];
                }

                Log::warning('STK Push Query Attempt Failed:', [ // Changed from warn to warning
                    'attempt' => $attempts + 1,
                    'result' => $result
                ]);
            } catch (\Exception $e) {
                Log::warning('STK Push Query Attempt Exception:', [ // Changed from warn to warning
                    'attempt' => $attempts + 1,
                    'error' => $e->getMessage()
                ]);
            }

            $attempts++;
            if ($attempts >= $maxAttempts) {
                throw new \Exception('Max attempts reached while querying transaction status');
            }
            sleep($delay);
        }

        throw new \Exception('Failed to query transaction status');
    }

    protected function updatePaymentStatus($donation, $status)
    {
        if ($donation) {
            $donation->update([
                'status' => $status['TransactionStatus'] == 'Success' ? 'completed' : 'failed',
                'failure_reason' => $status['ResultDesc'],
            ]);
            Log::info('Payment Status Updated:', [
                'checkout_request_id' => $donation->checkout_request_id,
                'status' => $status['TransactionStatus']
            ]);
        }
    }

    public function callback(Request $request)
    {
        Log::info('M-Pesa Callback Received:', $request->all());
        $callbackData = $request->input('Body.stkCallback');

        if (!$callbackData) {
            Log::error('Invalid callback data received:', ['request' => $request->all()]);
            return response()->json(['status' => 'error']);
        }

        $resultCode = $callbackData['ResultCode'] ?? null;
        $resultDesc = $callbackData['ResultDesc'] ?? 'No description';
        $checkoutRequestId = $callbackData['CheckoutRequestID'] ?? null;

        if ($checkoutRequestId) {
            $donation = Donation::where('checkout_request_id', $checkoutRequestId)->first();
            $this->updatePaymentStatus($donation, [
                'ResultCode' => $resultCode,
                'ResultDesc' => $resultDesc,
                'TransactionStatus' => $resultCode == '0' ? 'Success' : 'Failed',
            ]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function checkStatus($checkoutRequestId)
    {
        $donation = Donation::where('checkout_request_id', $checkoutRequestId)->first();
        if (!$donation) {
            return response()->json(['status' => 'not_found']);
        }
        return response()->json(['status' => $donation->status]);
    }
}