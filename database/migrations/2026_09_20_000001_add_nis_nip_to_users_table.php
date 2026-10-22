<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // nullable dulu supaya tidak error kalau tabel users sudah ada isinya (data lama tidak wajib langsung punya nis_nip)
            // unique supaya 1 NIS/NIP cuma bisa dipakai 1 akun
            $table->string('nis_nip', 20)->unique()->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nis_nip');
        });
    }
};
