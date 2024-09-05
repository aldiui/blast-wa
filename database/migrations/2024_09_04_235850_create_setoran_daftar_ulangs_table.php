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
        Schema::create('setoran_daftar_ulangs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('daftar_ulang_id');
            $table->bigInteger('nominal');
            $table->date('tanggal');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('daftar_ulang_id')->references('id')->on('daftar_ulangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setoran_daftar_ulangs');
    }
};
