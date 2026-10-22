<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'nis_nip',
        'password',
        'role',
        'foto_profil', // <-- ditambahkan
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    // URL lengkap foto profil, atau null kalau user belum upload.
    // Dipakai di layout: kalau null -> tampilkan inisial nama sebagai gantinya.
    public function fotoUrl(): ?string
    {
        return $this->foto_profil ? asset('storage/' . $this->foto_profil) : null;
    }

    // Huruf pertama nama (kapital), untuk avatar default
    public function inisial(): string
    {
        return strtoupper(mb_substr($this->name ?? '?', 0, 1));
    }
}
