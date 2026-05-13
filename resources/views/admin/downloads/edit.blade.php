@extends('admin.layouts.adminlte')

@section('title', 'Edit Download - Portal Kemenag')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-download mr-2"></i>Edit Download</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.downloads.index') }}">Download</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.downloads.update', $download->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="title">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title', $download->title) }}" required>
                        @error('title')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3">{{ old('description', $download->description) }}</textarea>
                        @error('description')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>File Saat Ini</label>
                        <div class="input-group">
                            <span class="form-control">{{ $download->file_path }}</span>
                            <span class="input-group-append">
                                <span class="input-group-text">{{ $download->file_size_formatted }}</span>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="file">Ganti File</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="file" id="file" class="custom-file-input @error('file') is-invalid @enderror">
                                <label class="custom-file-label" for="file">Pilih file baru...</label>
                            </div>
                        </div>
                        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengganti file. Maksimal ukuran file: 50MB</small>
                        @error('file')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="is_published" id="is_published" class="custom-control-input" 
                                   value="1" {{ old('is_published', $download->is_published) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_published">Publikasi</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.downloads.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection

@section('js')
<script>
    // Update file label on select
    bsCustomFileInput.init();
</script>
@endsection
