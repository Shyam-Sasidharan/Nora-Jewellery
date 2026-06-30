<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'compare_at_price')) {
                $table->decimal('compare_at_price', 12, 2)->nullable()->after('price');
            }

            if (! Schema::hasColumn('products', 'stock_quantity')) {
                $table->unsignedInteger('stock_quantity')->default(0)->after('price_on_request');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'stock_quantity')) {
                $table->dropColumn('stock_quantity');
            }

            if (Schema::hasColumn('products', 'compare_at_price')) {
                $table->dropColumn('compare_at_price');
            }
        });
    }
};
