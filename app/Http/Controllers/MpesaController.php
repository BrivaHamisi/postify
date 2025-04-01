<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Safaricom\Mpesa\Mpesa;
use App\Models\Donation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class MpesaController extends Controller
{
    protected $mpesa;

    public function __construct()
    {
        $this->mpesa = new Mpesa();
    }

    /**
     * Initiate an M-Pesa STK Push donation
     */
    public function getAccessToken()
    {
        $consumerKey = config('services.mpesa.consumer_key');
        $consumerSecret = config('services.mpesa.consumer_secret');
        $url = config('services.mpesa.env') === 'sandbox'
            ? 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'
            : 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $response = Http::withBasicAuth($consumerKey, $consumerSecret)->withOptions(['verify' => false])->get($url);
        $result = $response->json();
        Log::info('Manual Token Response:', ['result' => $result]);

        if (!$response->successful() || !isset($result['access_token'])) {
            throw new \Exception('Failed to obtain access token: ' . ($result['error'] ?? 'Unknown error'));
        }

        return $result['access_token'];
    }

    public function initiateDonation(Request $request)
    {
        Log::info('Initiate Donation Request:', $request->all());

        $request->validate([
            'phone' => 'required|regex:/^2547[0-9]{8}$/',
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
            // Generate token
            $token = $this->getAccessToken();

            // Prepare STK Push payload
            $timestamp = now()->format('YmdHis');
            $password = base64_encode($shortcode . $passkey . $timestamp);

            $payload = [
                'user_id' => $userId,
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

            // Send STK Push request
            $url = config('services.mpesa.env') === 'sandbox'
                ? 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest'
                : 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])->withOptions(['verify' => false])->post($url, $payload);

            $result = $response->json();
            Log::info('Manual STK Push Response:', ['result' => $result]);

            if ($response->successful() && isset($result['ResponseCode']) && $result['ResponseCode'] == '0') {
                Donation::create([
                    'user_id' => $userId,
                    'phone' => $phone,
                    'amount' => $amount,
                    'transaction_id' => $transactionId,
                    'status' => 'pending',
                    'checkout_request_id' => $result['CheckoutRequestID'],
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment request sent. Please check your phone to confirm.',
                    'checkout_request_id' => $result['CheckoutRequestID']
                ]);
            } else {
                Log::error('Manual STK Push Failed:', ['result' => $result]);
                return response()->json([
                    'success' => false,
                    'message' => $result['errorMessage'] ?? 'Failed to initiate payment',
                ]);
            }
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

    /**
     * Handle M-Pesa callback
     */

     public function callback(Request $request)
     {
         // Log the raw callback for debugging
         Log::info('M-Pesa Callback Received:', $request->all());
     
         // Check if the callback contains the expected data
         $callbackData = $request->input('Body.stkCallback');
         if (!$callbackData) {
             Log::error('Invalid callback data received', ['request' => $request->all()]);
             return response()->json(['status' => 'error']);
         }
     
         $resultCode = $callbackData['ResultCode'];
         $resultDesc = $callbackData['ResultDesc'];
         $checkoutRequestId = $callbackData['CheckoutRequestID'];
     
         // Find the donation
         $donation = Donation::where('checkout_request_id', $checkoutRequestId)->first();
     
         if (!$donation) {
             Log::error('Donation not found for CheckoutRequestID:', ['checkout_request_id' => $checkoutRequestId]);
             return response()->json(['status' => 'error']);
         }
     
         // Update status based on result code
         if ($resultCode == 0) {
             $donation->update([
                 'status' => 'completed',
             ]);
             Log::info('Payment completed for CheckoutRequestID:', ['checkout_request_id' => $checkoutRequestId]);
         } else {
             $donation->update([
                 'status' => 'failed',
                 'failure_reason' => $resultDesc,
             ]);
             Log::warning('Payment failed for CheckoutRequestID:', [
                 'checkout_request_id' => $checkoutRequestId,
                 'reason' => $resultDesc
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
