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
        Schema::table('users', function (Blueprint $table) {
            $table->string('qris_path')->nullable()->after('password');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('payment_method')->default('cash')->after('status');
            $table->string('reference_number')->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('qris_path');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'reference_number']);
        });
    }
};
