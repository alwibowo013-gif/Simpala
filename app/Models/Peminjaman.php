<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'user_id',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'keperluan',
        'status',
        'catatan_admin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detail()
    {
        return $this->hasMany(PeminjamanDetail::class, 'peminjaman_id');
    }

    // Helper: karena form pengajuan kita cuma 1 alat per transaksi,
    // ini mempermudah ambil detail utamanya tanpa nulis ->detail->first() berulang
    public function detailUtama()
    {
        return $this->detail->first();
    }

    // Helper untuk menampilkan kode transaksi seperti di desain Figma: #PJM-260914-018
    public function kodeTransaksi(): string
    {
        return '#PJM-' . $this->created_at->format('ymd') . '-' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }
}
