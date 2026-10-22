<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    use HasFactory;

    protected $table = 'alat';

    protected $fillable = [
    'kategori_id',
    'jurusan',
    'kode_alat',
    'nama_alat',
    'stok_total',
    'stok_tersedia',
    'kondisi',
    'gambar',
];

    // Relasi: alat ini milik 1 kategori
    public function kategori()
    {
        return $this->belongsTo(KategoriAlat::class, 'kategori_id');
    }

    // Relasi: 1 alat bisa muncul di banyak detail peminjaman
    public function peminjamanDetail()
    {
        return $this->hasMany(PeminjamanDetail::class, 'alat_id');
    }
}
