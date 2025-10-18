<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBrandAndCategoryToProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'brand')) {
                $table->string('brand')->nullable()->after('name');
            }
            if (!Schema::hasColumn('products', 'category_id')) {
                $table->foreignId('category_id')->nullable()->after('condition')->constrained()->nullOnDelete();
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'category_id')) {
                $table->dropConstrainedForeignId('category_id');
            }
            if (Schema::hasColumn('products', 'brand')) {
                $table->dropColumn('brand');
            }
        });
    }
}
