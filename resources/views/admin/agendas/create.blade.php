@extends('admin.layouts.adminlte')

@section('title', 'Create Agenda - Tambah Agenda')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Tambah Agenda</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.agendas.index') }}">Agenda</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Tambah Agenda</h3>
        </div>
        
        <form action="{{ route('admin.agendas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="form-group">
                    <label for="title">Judul Agenda <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('title') is-invalid @enderror" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}" 
                           required>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ old('start_date') }}" 
                                   required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date">Tanggal Selesai</label>
                            <input type="date" 
                                   class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" 
                                   name="end_date" 
                                   value="{{ old('end_date') }}">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="event_time_text">Waktu Pelaksanaan</label>
                    <input type="text" 
                           class="form-control @error('event_time_text') is-invalid @enderror" 
                           id="event_time_text" 
                           name="event_time_text" 
                           value="{{ old('event_time_text') }}"
                           placeholder="Contoh: 09:00 s/d Selesai">
                </div>
                
                <div class="form-group">
                    <label for="location">Lokasi</label>
                    <input type="text" 
                           class="form-control @error('location') is-invalid @enderror" 
                           id="location" 
                           name="location" 
                           value="{{ old('location') }}">
                </div>
                
                <div class="form-group">
                    <label for="sender_name">Pengirim</label>
                    <input type="text" 
                           class="form-control @error('sender_name') is-invalid @enderror" 
                           id="sender_name" 
                           name="sender_name" 
                           value="{{ old('sender_name') }}">
                </div>
                
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea class="form-control summernote @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="5">{{ old('description') }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="image">Gambar</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" 
                                   class="custom-file-input @error('image') is-invalid @enderror" 
                                   id="image" 
                                   name="image"
                                   accept="image/*"
                                   onchange="previewImage(this);">
                            <label class="custom-file-label" for="image">Pilih file...</label>
                        </div>
                    </div>
                    <small class="form-text text-muted">Format: JPG, PNG, GIF. Maks: 2MB</small>
                </div>
                
                <div class="form-group" id="image-preview" style="display: none;">
                    <label>Preview:</label>
                    <div>
                        <img id="preview-img" src="#" alt="Preview" style="max-width: 300px; max-height: 200px;">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="published_at">Tanggal Publish</label>
                    <input type="datetime-local" 
                           class="form-control @error('published_at') is-invalid @enderror" 
                           id="published_at" 
                           name="published_at" 
                           value="{{ old('published_at') }}">
                </div>
            </div>
            
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="{{ route('admin.agendas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#preview-img').attr('src', e.target.result);
            $('#image-preview').show();
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
