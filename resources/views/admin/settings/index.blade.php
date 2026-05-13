{{-- resources\views\admin\settings\index.blade.php --}}
{{-- Settings Page with AdminLTE --}}

@extends('admin.layouts.adminlte')

@section('title', 'Pengaturan')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Pengaturan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Pengaturan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
{{-- Success Alert --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
        {{ session('success') }}
    </div>
@endif

{{-- General Settings Card --}}
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-cog mr-1"></i> Informasi Situs
        </h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="site_name">Nama Situs <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('site_name') is-invalid @enderror"
                               id="site_name" name="site_name"
                               value="{{ old('site_name', $setting->site_name ?? '') }}"
                               placeholder="Masukkan nama situs" required>
                        @error('site_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="site_url">URL Situs</label>
                        <input type="url" class="form-control @error('site_url') is-invalid @enderror"
                               id="site_url" name="site_url"
                               value="{{ old('site_url', $setting->site_url ?? '') }}"
                               placeholder="https://example.com">
                        @error('site_url')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email"
                               value="{{ old('email', $setting->email ?? '') }}"
                               placeholder="email@example.com">
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Telepon</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                               id="phone" name="phone"
                               value="{{ old('phone', $setting->phone ?? '') }}"
                               placeholder="+62xxxxxxxxxx">
                        @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="logo">Logo URL</label>
                        <input type="text" class="form-control @error('logo') is-invalid @enderror"
                               id="logo" name="logo"
                               value="{{ old('logo', $setting->logo ?? '') }}"
                               placeholder="URL logo">
                        @error('logo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="favicon">Favicon URL</label>
                        <input type="text" class="form-control @error('favicon') is-invalid @enderror"
                               id="favicon" name="favicon"
                               value="{{ old('favicon', $setting->favicon ?? '') }}"
                               placeholder="URL favicon">
                        @error('favicon')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
    </div>
</div>

{{-- Social Media Card --}}
<div class="card card-info card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-share-alt mr-1"></i> Media Sosial
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="facebook_url">
                        <i class="fab fa-facebook text-primary mr-1"></i> Facebook URL
                    </label>
                    <input type="url" class="form-control @error('facebook_url') is-invalid @enderror"
                           id="facebook_url" name="facebook_url"
                           value="{{ old('facebook_url', $setting->facebook_url ?? '') }}"
                           placeholder="https://facebook.com/...">
                    @error('facebook_url')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="instagram_url">
                        <i class="fab fa-instagram text-pink mr-1"></i> Instagram URL
                    </label>
                    <input type="url" class="form-control @error('instagram_url') is-invalid @enderror"
                           id="instagram_url" name="instagram_url"
                           value="{{ old('instagram_url', $setting->instagram_url ?? '') }}"
                           placeholder="https://instagram.com/...">
                    @error('instagram_url')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="youtube_url">
                        <i class="fab fa-youtube text-danger mr-1"></i> YouTube URL
                    </label>
                    <input type="url" class="form-control @error('youtube_url') is-invalid @enderror"
                           id="youtube_url" name="youtube_url"
                           value="{{ old('youtube_url', $setting->youtube_url ?? '') }}"
                           placeholder="https://youtube.com/...">
                    @error('youtube_url')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="twitter_url">
                        <i class="fab fa-twitter text-info mr-1"></i> Twitter URL
                    </label>
                    <input type="url" class="form-control @error('twitter_url') is-invalid @enderror"
                           id="twitter_url" name="twitter_url"
                           value="{{ old('twitter_url', $setting->twitter_url ?? '') }}"
                           placeholder="https://twitter.com/...">
                    @error('twitter_url')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SEO Card --}}
<div class="card card-secondary card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-search mr-1"></i> SEO
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="meta_description">Meta Description</label>
                    <textarea class="form-control @error('meta_description') is-invalid @enderror"
                              id="meta_description" name="meta_description" rows="4"
                              placeholder="Deskripsi meta untuk SEO">{{ old('meta_description', $setting->meta_description ?? '') }}</textarea>
                    @error('meta_description')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="meta_keywords">Meta Keywords</label>
                    <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror"
                           id="meta_keywords" name="meta_keywords"
                           value="{{ old('meta_keywords', $setting->meta_keywords ?? '') }}"
                           placeholder="keyword1, keyword2, keyword3">
                    @error('meta_keywords')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Location & Footer Card --}}
<div class="card card-success card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-map-marker-alt mr-1"></i> Lokasi & Footer
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="maps_embed">Maps Embed</label>
                    <textarea class="form-control @error('maps_embed') is-invalid @enderror"
                              id="maps_embed" name="maps_embed" rows="5"
                              placeholder="Kode embed Google Maps">{{ old('maps_embed', $setting->maps_embed ?? '') }}</textarea>
                    @error('maps_embed')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="footer_description">Footer Description</label>
                    <textarea class="form-control @error('footer_description') is-invalid @enderror"
                              id="footer_description" name="footer_description" rows="5"
                              placeholder="Deskripsi untuk footer">{{ old('footer_description', $setting->footer_description ?? '') }}</textarea>
                    @error('footer_description')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-1"></i> Simpan Pengaturan
        </button>
    </div>
</div>
</form>
@endsection
