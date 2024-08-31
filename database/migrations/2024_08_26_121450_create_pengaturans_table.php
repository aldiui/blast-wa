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
        Schema::create('pengaturans', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nama');
            $table->string('logo');
            $table->string('alamat');
            $table->string('email');
            $table->string('no_telepon');
            $table->unsignedBigInteger('syahriyah')->default('250000');
            $table->unsignedBigInteger('uang_makan')->default('50000');
            $table->unsignedBigInteger('field_trip')->default('25000');
            $table->unsignedBigInteger('daftar_ulang')->default('1454000');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturans');
    }
};
