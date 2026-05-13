{{-- resources\views\admin\pages\create.blade.php --}}
{{-- Create Page with AdminLTE --}}

@extends('admin.layouts.adminlte')

@section('title', 'Create Page')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Create Page</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Page Content</h3>
            </div>
            
            <form action="{{ route('admin.pages.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="form-group">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required placeholder="Enter page title">
                    </div>
                    
                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}" placeholder="Auto-generated if empty">
                        <small class="text-muted">Leave empty to auto-generate from title</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="content">Content <span class="text-danger">*</span></label>
                        <textarea name="content" id="content" class="form-control" rows="10">{{ old('content') }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="excerpt">Excerpt</label>
                        <textarea name="excerpt" id="excerpt" class="form-control" rows="3" placeholder="Short summary of the page">{{ old('excerpt') }}</textarea>
                    </div>
                </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Settings</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="page_type">Page Type</label>
                    <select name="page_type" id="page_type" class="form-control">
                        <option value="">Select Type</option>
                        <option value="profil" {{ old('page_type') === 'profil' ? 'selected' : '' }}>Profil</option>
                        <option value="informasi" {{ old('page_type') === 'informasi' ? 'selected' : '' }}>Informasi</option>
                        <option value="kontak" {{ old('page_type') === 'kontak' ? 'selected' : '' }}>Kontak</option>
                        <option value="statis" {{ old('page_type') === 'statis' ? 'selected' : '' }}>Statis</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="published_at">Publish Date</label>
                    <input type="datetime-local" name="published_at" id="published_at" class="form-control" value="{{ old('published_at') }}">
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block">
                    <i class="fas fa-save mr-1"></i> Save Page
                </button>
                <a href="{{ route('admin.pages.index') }}" class="btn btn-default btn-block">
                    <i class="fas fa-arrow-left mr-1"></i> Cancel
                </a>
            </div>
        </div>
        
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">SEO Settings</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="meta_title">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ old('meta_title') }}" placeholder="SEO title">
                </div>
                
                <div class="form-group">
                    <label for="meta_description">Meta Description</label>
                    <textarea name="meta_description" id="meta_description" class="form-control" rows="2" placeholder="SEO description">{{ old('meta_description') }}</textarea>
                </div>
                
                <div class="form-group mb-0">
                    <label for="meta_keywords">Meta Keywords</label>
                    <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" value="{{ old('meta_keywords') }}" placeholder="keyword1, keyword2, keyword3">
                </div>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
$(function() {
    // Initialize Summernote WYSIWYG Editor
    $('#content').summernote({
        height: 350,
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
        ],
        callbacks: {
            onImageUpload: function(files) {
                for (let i = 0; i < files.length; i++) {
                    uploadImage(files[i]);
                }
            }
        }
    });
    
    function uploadImage(file) {
        let formData = new FormData();
        formData.append('image', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        $.ajax({
            url: '{{ route('admin.upload.image') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                $('#content').summernote('insertImage', data.url);
            },
            error: function(xhr, status, error) {
                alert('Failed to upload image: ' + error);
            }
        });
    }
    
    // Auto-generate slug from title
    var titleInput = document.getElementById('title');
    var slugInput = document.getElementById('slug');
    
    function generateSlug(text) {
        return text.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
    }
    
    titleInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.dataset.auto === 'true') {
            slugInput.value = generateSlug(titleInput.value);
            slugInput.dataset.auto = 'true';
        }
    });
    
    slugInput.addEventListener('input', function() {
        if (slugInput.value) {
            slugInput.dataset.auto = 'false';
        }
    });
});
</script>
@endpush
