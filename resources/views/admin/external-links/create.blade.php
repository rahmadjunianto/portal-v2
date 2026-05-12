{{-- resources\views\admin\external-links\create.blade.php --}}
{{-- Create External Link with AdminLTE --}}

@extends('admin.layouts.adminlte')

@section('title', 'Add External Link')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Add External Link</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.links.index') }}">External Links</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">New External Link</h3>
    </div>
    
    <form action="{{ route('admin.links.store') }}" method="POST">
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
                <label for="title">Title *</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
            </div>
            
            <div class="form-group">
                <label for="url">URL *</label>
                <input type="url" name="url" id="url" class="form-control" value="{{ old('url') }}" placeholder="https://example.com" required>
            </div>
            
            <div class="form-group">
                <label for="icon">Icon (FontAwesome class)</label>
                <input type="text" name="icon" id="icon" class="form-control" value="{{ old('icon') }}" placeholder="fas fa-globe">
                <small class="text-muted">Example: fas fa-globe, fab fa-facebook, fas fa-newspaper</small>
            </div>
            
            <div class="form-group">
                <label for="sort_order">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', 0) }}">
            </div>
            
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
            </div>
            
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" name="open_in_new_tab" id="open_in_new_tab" class="form-check-input" value="1" {{ old('open_in_new_tab', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="open_in_new_tab">Open in New Tab</label>
                </div>
            </div>
        </div>
        
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Save Link
            </button>
            <a href="{{ route('admin.links.index') }}" class="btn btn-secondary float-right">
                <i class="fas fa-arrow-left mr-1"></i> Back
            </a>
        </div>
    </form>
</div>
@endsection
