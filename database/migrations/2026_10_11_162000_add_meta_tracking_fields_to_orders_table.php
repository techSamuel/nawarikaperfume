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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('fbp')->nullable()->after('country');
            $table->string('fbc')->nullable()->after('fbp');
            $table->timestamp('capi_purchase_sent_at')->nullable()->after('fbc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['fbp', 'fbc', 'capi_purchase_sent_at']);
        });
    }
};
