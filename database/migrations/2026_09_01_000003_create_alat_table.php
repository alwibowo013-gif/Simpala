<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_alat')->onDelete('cascade');
            $table->string('kode_alat', 20)->unique();
            $table->string('nama_alat', 100);
            $table->integer('stok_total')->default(0);
            $table->integer('stok_tersedia')->default(0);
            $table->enum('kondisi', ['baik', 'rusak'])->default('baik');
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alat');
    }
};
