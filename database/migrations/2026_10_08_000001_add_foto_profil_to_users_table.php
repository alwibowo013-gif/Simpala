<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Yang disimpan di sini cuma PATH relatif file-nya (misal "profil/abc123.jpg"),
            // bukan file gambarnya. Filenya sendiri ada di storage/app/public/profil.
            // nullable = user yang belum upload foto tetap valid (tampil inisial nama).
            $table->string('foto_profil')->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('foto_profil');
        });
    }
};
