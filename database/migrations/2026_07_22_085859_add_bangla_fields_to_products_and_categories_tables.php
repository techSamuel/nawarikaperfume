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
        Schema::table('products', function (Blueprint $table) {
            $table->string('name_bn')->nullable()->after('name');
            $table->text('description_bn')->nullable()->after('description');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_bn')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['name_bn', 'description_bn']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('name_bn');
        });
    }
};
