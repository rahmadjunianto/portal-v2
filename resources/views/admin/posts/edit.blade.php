{{-- resources\views\admin\posts\edit.blade.php --}}
{{-- Edit Post with AdminLTE --}}

@extends('admin.layouts.adminlte')

@section('title', 'Edit Post')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Edit Post</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Posts</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Post Content</h3>
            </div>
            
            <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
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
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $post->title) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $post->slug) }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="content">Content <span class="text-danger">*</span></label>
                        <textarea name="content" id="content" class="form-control" rows="10">{{ old('content', $post->content) }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="excerpt">Excerpt</label>
                        <textarea name="excerpt" id="excerpt" class="form-control" rows="3">{{ old('excerpt', $post->excerpt) }}</textarea>
                    </div>
                </div>
        </div>
        
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Thumbnail</h3>
            </div>
            <div class="card-body">
                @if($post->thumbnail)
                    <div class="mb-3">
                        <img src="{{ Storage::url($post->thumbnail) }}" alt="Current thumbnail" class="img-thumbnail" style="max-height: 150px;">
                        <div class="form-check mt-2">
                            <input type="checkbox" name="remove_thumbnail" id="remove_thumbnail" class="form-check-input" value="1">
                            <label class="form-check-label text-danger" for="remove_thumbnail">Remove thumbnail</label>
                        </div>
                    </div>
                @endif
                
                <div class="form-group">
                    <label for="thumbnail">{{ $post->thumbnail ? 'Replace Thumbnail' : 'Upload Thumbnail' }}</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="thumbnail" id="thumbnail" class="custom-file-input" accept="image/*">
                            <label class="custom-file-label" for="thumbnail">Choose file</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="image_caption">Image Caption</label>
                    <input type="text" name="image_caption" id="image_caption" class="form-control" value="{{ old('image_caption', $post->image_caption) }}">
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Publish Settings</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="type">Type <span class="text-danger">*</span></label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="berita" {{ old('type', $post->type) === 'berita' ? 'selected' : '' }}>Berita</option>
                        <option value="pengumuman" {{ old('type', $post->type) === 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                        <option value="lowongan" {{ old('type', $post->type) === 'lowongan' ? 'selected' : '' }}>Lowongan</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select name="category_id" id="category_id" class="form-control">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="published_at">Publish Date</label>
                    <input type="datetime-local" name="published_at" id="published_at" class="form-control" value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}">
                </div>
                
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" name="is_headline" id="is_headline" class="form-check-input" value="1" {{ old('is_headline', $post->is_headline) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_headline">Set as Headline</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input" value="1" {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">Featured Post</label>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block">
                    <i class="fas fa-save mr-1"></i> Save Changes
                </button>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-default btn-block">
                    <i class="fas fa-arrow-left mr-1"></i> Cancel
                </a>
            </div>
        </div>
        
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Post Info</h3>
            </div>
            <div class="card-body">
                <p><small class="text-muted">Author: {{ $post->author->name ?? 'Unknown' }}</small></p>
                <p><small class="text-muted">Created: {{ $post->created_at->format('d M Y H:i') }}</small></p>
                <p><small class="text-muted">Updated: {{ $post->updated_at->format('d M Y H:i') }}</small></p>
            </div>
        </div>
        
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Tags</h3>
            </div>
            <div class="card-body" style="max-height: 200px; overflow-y: auto;">
                @php
                    $selectedTags = old('tags', $post->tags->pluck('id')->toArray());
                @endphp
                @forelse($tags as $tag)
                    <div class="form-check">
                        <input type="checkbox" name="tags[]" id="tag_{{ $tag->id }}" class="form-check-input" value="{{ $tag->id }}" {{ in_array($tag->id, $selectedTags) ? 'checked' : '' }}>
                        <label class="form-check-label" for="tag_{{ $tag->id }}">{{ $tag->name }}</label>
                    </div>
                @empty
                    <p class="text-muted">No tags available</p>
                @endforelse
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
