@extends('admin.layouts.adminlte')

@section('title', 'Tambah Bank Data - Chatbot AI')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fas fa-plus text-primary"></i> Tambah Data</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.knowledge-bank.index') }}">Bank Data</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.knowledge-bank.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="question">Pertanyaan <span class="text-danger">*</span></label>
                <input type="text" name="question" id="question" class="form-control @error('question') is-invalid @enderror" 
                    value="{{ old('question') }}" placeholder="Contoh: Cara daftar nikah?" required>
                @error('question')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="answer">Jawaban <span class="text-danger">*</span></label>
                <textarea name="answer" id="answer" class="form-control @error('answer') is-invalid @enderror" 
                    rows="5" placeholder="Jawaban yang akan diberikan bot..." required>{{ old('answer') }}</textarea>
                @error('answer')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="category">Kategori</label>
                        <select name="category" id="category" class="form-control">
                            <option value="">- Pilih Kategori -</option>
                            @foreach($categories as $key => $name)
                                <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="priority">Prioritas (0-100)</label>
                        <input type="number" name="priority" id="priority" class="form-control" 
                            value="{{ old('priority', 0) }}" min="0" max="100">
                        <small class="text-muted">Prioritas lebih tinggi = dicek lebih dulu</small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="tags">Tags</label>
                <input type="text" name="tags" id="tags" class="form-control" 
                    value="{{ old('tags') }}" placeholder="nikah, daftar, izin (pisahkan dengan koma)">
                <small class="text-muted">Kata kunci pencarian, pisahkan dengan koma</small>
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="is_active" id="is_active" class="custom-control-input" value="1" checked>
                    <label for="is_active" class="custom-control-label">Aktif</label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="{{ route('admin.knowledge-bank.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
