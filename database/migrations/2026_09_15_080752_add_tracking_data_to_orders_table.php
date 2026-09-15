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
            $table->string('ip_address')->nullable()->after('notes');
            $table->text('user_agent')->nullable()->after('ip_address');
            $table->string('device_type')->nullable()->after('user_agent');
            $table->string('browser')->nullable()->after('device_type');
            $table->string('platform')->nullable()->after('browser');
            $table->string('timezone')->nullable()->after('platform');
            $table->string('country')->nullable()->after('timezone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'ip_address',
                'user_agent',
                'device_type',
                'browser',
                'platform',
                'timezone',
                'country'
            ]);
        });
    }
};
