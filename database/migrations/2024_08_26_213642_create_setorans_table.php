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
        Schema::create('setorans', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('tabungan_id');
            $table->enum('transaksi', ['Pemasukan', 'Pengeluaran']);
            $table->bigInteger('nominal');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('tabungan_id')->references('id')->on('tabungans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setorans');
    }
};
