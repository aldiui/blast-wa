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
        Schema::create('tabungans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_siswa');
            $table->enum('jenis_tabungan',['tabungan_wajib','tabungan_reguler'])->default('');
            $table->BigInteger('saldo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabungans');
    }
};
