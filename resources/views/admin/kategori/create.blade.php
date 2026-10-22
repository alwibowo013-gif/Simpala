@extends('layouts.admin')
@section('title', 'Tambah Kategori')

@section('content')
    <div class="page-title">
        <h1>Tambah Kategori Alat</h1>
        <p>Buat kelompok baru untuk klasifikasi alat.</p>
    </div>

    <div class="card-panel" style="max-width: 480px;">
        <form action="{{ route('admin.kategori.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control" value="{{ old('nama_kategori') }}" placeholder="Contoh: Elektronik">
                @error('nama_kategori') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn-simpala">Simpan</button>
            <a href="{{ route('admin.kategori.index') }}" class="btn-outline">Batal</a>
        </form>
    </div>
@endsection
