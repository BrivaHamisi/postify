<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'amount',
        'transaction_id',
        'checkout_request_id',
        'status',
        'failure_reason',
        'transaction_type',
        'business_shortcode',
        'callback_url',
        'mpesa_receipt_number'
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
