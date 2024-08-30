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
            $table->unsignedBigInteger('id_tabungan');
            $table->enum('transaksi', ['Pemasukan', 'Pengeluaran']);
            $table->bigInteger('nominal');
            $table->timestamps();

            $table->foreign('id_tabungan')->references('id')->on('tabungans')->onDelete('cascade');
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
