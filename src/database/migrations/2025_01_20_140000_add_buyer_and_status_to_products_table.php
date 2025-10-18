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
            $table->foreignId('buyer_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->boolean('is_sold')->default(false)->after('buyer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['buyer_id']);
            $table->dropColumn(['buyer_id', 'is_sold']);
        });
    }
};
