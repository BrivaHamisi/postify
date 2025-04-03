<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->string('transaction_type')->nullable()->after('transaction_id');
            $table->string('business_shortcode')->nullable()->after('transaction_type');
            $table->string('callback_url')->nullable()->after('business_shortcode');
            $table->string('mpesa_receipt_number')->nullable()->after('checkout_request_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn(['transaction_type', 'business_shortcode', 'callback_url', 'mpesa_receipt_number']);
        });
    }
};
