@extends('admin.layouts.adminlte')

@section('title', 'Edit Layanan')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Edit Layanan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.services.index') }}">Layanan PTSP</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Informasi Layanan</h3>
    </div>
    
    <form action="{{ route('admin.services.update', $service->id) }}" method="POST">
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
            
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="name">Nama Layanan <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" 
                               value="{{ old('name', $service->name) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="service_category_id">Kategori</label>
                        <select name="service_category_id" id="service_category_id" class="form-control">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('service_category_id', $service->service_category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $service->description) }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="requirements">Persyaratan</label>
                        <textarea name="requirements" id="requirements" class="form-control" rows="4">{{ old('requirements', $service->requirements) }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="workflow">Alur/Langkah</label>
                        <textarea name="workflow" id="workflow" class="form-control" rows="4">{{ old('workflow', $service->workflow) }}</textarea>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="processing_time">Estimasi Waktu</label>
                        <input type="text" name="processing_time" id="processing_time" class="form-control" 
                               value="{{ old('processing_time', $service->processing_time) }}" placeholder="Contoh: 3-5 hari kerja">
                    </div>
                    
                    <div class="form-group">
                        <label for="cost">Biaya</label>
                        <input type="text" name="cost" id="cost" class="form-control" 
                               value="{{ old('cost', $service->cost) }}" placeholder="Contoh: Gratis atau Rp 50.000">
                    </div>
                    
                    <div class="form-group">
                        <label for="contact">Kontak PIC</label>
                        <input type="text" name="contact" id="contact" class="form-control" 
                               value="{{ old('contact', $service->contact) }}" placeholder="Contoh: 0812-3456-7890">
                    </div>
                    
                    <div class="form-group">
                        <label for="office">Lokasi Pelayanan</label>
                        <input type="text" name="office" id="office" class="form-control" 
                               value="{{ old('office', $service->office) }}" placeholder="Contoh: Ruang PTSP Lt. 1">
                    </div>
                    
                    <div class="form-group">
                        <label for="website">Website</label>
                        <input type="url" name="website" id="website" class="form-control" 
                               value="{{ old('website', $service->website) }}" placeholder="https://...">
                    </div>
                    
                    <div class="form-group">
                        <label for="download_link">Link Download Formulir</label>
                        <input type="url" name="download_link" id="download_link" class="form-control" 
                               value="{{ old('download_link', $service->download_link) }}" placeholder="https://...">
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" 
                                   {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Layanan Aktif</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Update
            </button>
            <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
