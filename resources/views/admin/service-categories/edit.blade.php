@extends('admin.layouts.adminlte')

@section('title', 'Edit Kategori')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Edit Kategori</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.service-categories.index') }}">Kategori Layanan</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Informasi Kategori</h3>
    </div>
    
    <form action="{{ route('admin.service-categories.update', $serviceCategory->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="form-group">
                <label for="name">Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" 
                       value="{{ old('name', $serviceCategory->name) }}" required>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="icon">Icon (FontAwesome)</label>
                        <input type="text" name="icon" id="icon" class="form-control" 
                               value="{{ old('icon', $serviceCategory->icon) }}" placeholder="Contoh: fas fa-briefcase">
                        <small class="text-muted">Gunakan class FontAwesome</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="color">Warna</label>
                        <div class="input-group">
                            <input type="color" name="color" class="form-control form-control-color" 
                                   value="{{ old('color', $serviceCategory->color ?? '#6c757d') }}" style="width: 60px;">
                            <input type="text" class="form-control" value="{{ old('color', $serviceCategory->color ?? '#6c757d') }}" 
                                   id="colorText" readonly>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sort_order">Urutan</label>
                        <input type="number" name="sort_order" id="sort_order" class="form-control" 
                               value="{{ old('sort_order', $serviceCategory->sort_order) }}" min="0">
                        <small class="text-muted">Angka kecil akan tampil lebih dulu</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Status</label>
                        <div class="form-check mt-2">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" 
                                   {{ old('is_active', $serviceCategory->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktif</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Update
            </button>
            <a href="{{ route('admin.service-categories.index') }}" class="btn btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.querySelector('input[name="color"]').addEventListener('input', function(e) {
    document.getElementById('colorText').value = e.target.value;
});
</script>
@endpush
