@extends('admin.layouts.adminlte')

@section('title', 'Tambah Menu')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            @if($parent)
                Tambah Submenu untuk "{{ $parent->title }}"
            @else
                Tambah Menu Utama
            @endif
        </h1>
        <a href="{{ route('admin.menu-items.index') }}" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Menu</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.menu-items.store') }}" method="POST">
                @csrf
                
                <!-- Parent ID (hidden if creating submenu) -->
                @if($parent)
                    <input type="hidden" name="parent_id" value="{{ $parent->id }}">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Menu ini akan ditambahkan sebagai submenu dari "<strong>{{ $parent->title }}</strong>"
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-8">
                        <!-- Title -->
                        <div class="form-group">
                            <label for="title">Judul Menu <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Nama menu yang akan ditampilkan.</small>
                        </div>

                        <!-- URL -->
                        <div class="form-group">
                            <label for="url">URL</label>
                            <input type="text" 
                                   class="form-control @error('url') is-invalid @enderror" 
                                   id="url" 
                                   name="url" 
                                   value="{{ old('url') }}"
                                   placeholder="/posts atau https://example.com">
                            @error('url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Url untuk menu ini. Gunakan "/" untuk halaman internal atau lengkap untuk eksternal.</small>
                        </div>

                        <!-- Open in New Tab -->
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="open_in_new_tab" 
                                       name="open_in_new_tab" 
                                       value="1"
                                       {{ old('open_in_new_tab') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="open_in_new_tab">Buka di Tab Baru</label>
                            </div>
                            <small class="form-text text-muted">Jika diaktifkan, link akan terbuka di tab/jendela baru.</small>
                        </div>

                        <!-- Parent -->
                        @if(!$parent)
                        <div class="form-group">
                            <label for="parent_id">Parent Menu</label>
                            <select class="form-control @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                                <option value="">-- Menu Utama --</option>
                                @foreach($menuItems->whereNull('parent_id') as $item)
                                    <option value="{{ $item->id }}" {{ old('parent_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->title }}
                                    </option>
                                    @foreach($item->children as $child)
                                        <option value="{{ $child->id }}" {{ old('parent_id') == $child->id ? 'selected' : '' }}>
                                            &nbsp;&nbsp;↳ {{ $child->title }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                            @error('parent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Pilih menu utama jika ini adalah submenu.</small>
                        </div>
                        @endif

                        <!-- Sort Order -->
                        <div class="form-group">
                            <label for="sort_order">Urutan</label>
                            <input type="number" 
                                   class="form-control @error('sort_order') is-invalid @enderror" 
                                   id="sort_order" 
                                   name="sort_order" 
                                   value="{{ old('sort_order', 0) }}"
                                   min="0">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Angka kecil akan tampil lebih dulu.</small>
                        </div>

                        <!-- Is Active -->
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Menu Aktif</label>
                            </div>
                            <small class="form-text text-muted">Menu yang tidak aktif tidak akan ditampilkan di website.</small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-muted">Petunjuk</h6>
                            </div>
                            <div class="card-body">
                                <ul class="mb-0 small">
                                    <li class="mb-2">Menu Utama = Level 1</li>
                                    <li class="mb-2">Submenu = Level 2</li>
                                    <li>Submenu dari Submenu = Level 3</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="text-right">
                    <a href="{{ route('admin.menu-items.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
