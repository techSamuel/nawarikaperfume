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
            $table->integer('sort_order')->default(0)->after('is_featured')->index();
            $table->integer('featured_order')->default(0)->after('sort_order')->index();
        });

        // Initialize with id for existing products
        \Illuminate\Support\Facades\DB::statement('UPDATE products SET sort_order = id, featured_order = id WHERE sort_order = 0');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['sort_order']);
            $table->dropIndex(['featured_order']);
            $table->dropColumn(['sort_order', 'featured_order']);
        });
    }
};
