@extends('layouts.admin')
@section('title', 'Edit Kategori')

@section('content')
    <div class="page-title">
        <h1>Edit Kategori Alat</h1>
        <p>Perbarui nama kategori {{ $kategori->nama_kategori }}.</p>
    </div>

    <div class="card-panel" style="max-width: 480px;">
        <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control" value="{{ old('nama_kategori', $kategori->nama_kategori) }}">
                @error('nama_kategori') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn-simpala">Update</button>
            <a href="{{ route('admin.kategori.index') }}" class="btn-outline">Batal</a>
        </form>
    </div>
@endsection
