@extends('admin.layouts.adminlte')

@section('title', 'Edit Menu')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Menu "{{ $menuItem->title }}"</h1>
        <a href="{{ route('admin.menu-items.index') }}" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali
        </a>
    </div>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.menu-items.index') }}">Manajemen Menu</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Menu</li>
        </ol>
    </nav>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Menu</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.menu-items.update', $menuItem->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <!-- Title -->
                        <div class="form-group">
                            <label for="title">Judul Menu <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $menuItem->title) }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                         <!-- URL -->
                         <div class="form-group">
                             <label for="url">URL</label>
                             <input type="text" 
                                    class="form-control @error('url') is-invalid @enderror" 
                                    id="url" 
                                    name="url" 
                                    value="{{ old('url', $menuItem->url) }}"
                                    placeholder="/posts atau https://example.com">
                             @error('url')
                                 <div class="invalid-feedback">{{ $message }}</div>
                             @enderror
                         </div>

                         <!-- Open in New Tab -->
                         <div class="form-group">
                             <div class="custom-control custom-switch">
                                 <input type="checkbox" 
                                        class="custom-control-input" 
                                        id="open_in_new_tab" 
                                        name="open_in_new_tab" 
                                        value="1"
                                        {{ old('open_in_new_tab', $menuItem->open_in_new_tab) ? 'checked' : '' }}>
                                 <label class="custom-control-label" for="open_in_new_tab">Buka di Tab Baru</label>
                             </div>
                             <small class="form-text text-muted">Jika diaktifkan, link akan terbuka di tab/jendela baru.</small>
                         </div>

                         <!-- Parent -->
                        <div class="form-group">
                            <label for="parent_id">Parent Menu</label>
                            <select class="form-control @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                                <option value="">-- Menu Utama (Level 1) --</option>
                                @foreach($menuItems->whereNull('parent_id') as $item)
                                    <option value="{{ $item->id }}" {{ old('parent_id', $menuItem->parent_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->title }}
                                    </option>
                                    @foreach($item->children as $child)
                                        <option value="{{ $child->id }}" {{ old('parent_id', $menuItem->parent_id) == $child->id ? 'selected' : '' }}>
                                            &nbsp;&nbsp;↳ {{ $child->title }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                            @error('parent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                @if($menuItem->parent)
                                    Saat ini: <strong>{{ $menuItem->parent->title }}</strong> (Level {{ $menuItem->getDepth() }})
                                @else
                                    Saat ini: Menu Utama (Level 1)
                                @endif
                            </small>
                        </div>

                        <!-- Sort Order -->
                        <div class="form-group">
                            <label for="sort_order">Urutan</label>
                            <input type="number" 
                                   class="form-control @error('sort_order') is-invalid @enderror" 
                                   id="sort_order" 
                                   name="sort_order" 
                                   value="{{ old('sort_order', $menuItem->sort_order) }}"
                                   min="0">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Is Active -->
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $menuItem->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Menu Aktif</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Info Card -->
                        <div class="card bg-light mb-3">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-muted">Informasi Menu</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td>ID</td>
                                        <td><strong>#{{ $menuItem->id }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Level</td>
                                        <td><span class="badge badge-info">{{ $menuItem->getDepth() + 1 }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Submenu</td>
                                        <td><span class="badge badge-secondary">{{ $menuItem->children->count() }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>
                                            @if($menuItem->is_active)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-danger">Nonaktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>URL</td>
                                        <td><small>{{ $menuItem->url ?? '-' }}</small></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="card bg-warning-light">
                            <div class="card-body">
                                <h6 class="text-warning"><i class="fas fa-exclamation-triangle"></i> Perhatian</h6>
                                <ul class="mb-0 small">
                                    <li>Tidak dapat menjadikan menu ini sebagai parent dari dirinya sendiri.</li>
                                    <li>Mengubah parent akan memindahkan menu ke level yang berbeda.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="text-right">
                    <a href="{{ route('admin.menu-items.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
