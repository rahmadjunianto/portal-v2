@extends('admin.layouts.adminlte')

@section('title', 'Edit Agenda - Edit Agenda')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Edit Agenda</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.agendas.index') }}">Agenda</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Edit Agenda</h3>
        </div>
        
        <form action="{{ route('admin.agendas.update', $agenda->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
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
                           value="{{ old('title', $agenda->title) }}" 
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
                                   value="{{ old('start_date', $agenda->start_date->format('Y-m-d')) }}" 
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
                                   value="{{ old('end_date', $agenda->end_date ? $agenda->end_date->format('Y-m-d') : '') }}">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="event_time_text">Waktu Pelaksanaan</label>
                    <input type="text" 
                           class="form-control @error('event_time_text') is-invalid @enderror" 
                           id="event_time_text" 
                           name="event_time_text" 
                           value="{{ old('event_time_text', $agenda->event_time_text) }}"
                           placeholder="Contoh: 09:00 s/d Selesai">
                </div>
                
                <div class="form-group">
                    <label for="location">Lokasi</label>
                    <input type="text" 
                           class="form-control @error('location') is-invalid @enderror" 
                           id="location" 
                           name="location" 
                           value="{{ old('location', $agenda->location) }}">
                </div>
                
                <div class="form-group">
                    <label for="sender_name">Pengirim</label>
                    <input type="text" 
                           class="form-control @error('sender_name') is-invalid @enderror" 
                           id="sender_name" 
                           name="sender_name" 
                           value="{{ old('sender_name', $agenda->sender_name) }}">
                </div>
                
                <div class="form-group">
                    <label for="content">Deskripsi</label>
                    <textarea class="form-control summernote @error('content') is-invalid @enderror" 
                              id="content" 
                              name="content" 
                              rows="5">{{ old('content', $agenda->description) }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="image">Gambar</label>
                    @if($agenda->image)
                        @php
                            $imagePath = 'storage/' . $agenda->image;
                            $publicPath = public_path($imagePath);
                        @endphp
                        @if(file_exists($publicPath))
                            <div class="mb-2">
                                <img src="{{ asset($imagePath) }}" 
                                     alt="{{ $agenda->title }}" 
                                     style="max-width: 200px; max-height: 150px; object-fit: cover;">
                                <small class="d-block text-muted">Gambar saat ini</small>
                            </div>
                        @endif
                    @endif
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
                    <small class="form-text text-muted">Format: JPG, PNG, GIF. Maks: 2MB. Kosongkan jika tidak ingin mengubah gambar.</small>
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
                           value="{{ old('published_at', $agenda->published_at ? $agenda->published_at->format('Y-m-d\TH:i') : '') }}">
                </div>
            </div>
            
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.agendas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/lang/summernote-id-ID.min.js"></script>
<script>
$(function() {
    // Initialize Summernote WYSIWYG Editor
    $('#content').summernote({
        height: 300,
        lang: 'id-ID',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph', 'height']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video', 'hr']],
            ['view', ['fullscreen', 'codeview', 'help']],
            ['misc', ['undo', 'redo']]
        ]
    });
    
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
});
</script>
@endpush
