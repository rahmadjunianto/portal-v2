@extends('admin.layouts.adminlte')

@section('title', 'Kategori Layanan')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Kategori Layanan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active">Kategori Layanan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Kategori</h3>
        <div class="card-tools">
            <a href="{{ route('admin.service-categories.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Kategori
            </a>
        </div>
    </div>
    
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('success') }}
            </div>
        @endif
        
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="60">Sort</th>
                    <th>Nama Kategori</th>
                    <th>Icon</th>
                    <th>Warna</th>
                    <th>Jumlah Layanan</th>
                    <th width="100">Status</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td class="text-center">{{ $category->sort_order }}</td>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td>
                            @if($category->icon)
                                <i class="{{ $category->icon }}"></i>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($category->color)
                                <span class="badge" style="background: {{ $category->color }}; color: white;">{{ $category->color }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-primary">{{ $category->services_count ?? 0 }} layanan</span>
                        </td>
                        <td>
                            @if($category->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.service-categories.edit', $category->id) }}" class="btn btn-info btn-xs">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.service-categories.destroy', $category->id) }}" 
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin hapus kategori ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs" 
                                        {{ ($category->services_count ?? 0) > 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data kategori</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
