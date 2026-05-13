@extends('admin.layouts.adminlte')

@section('title', 'Manajemen Menu')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Menu</h1>
        <a href="{{ route('admin.menu-items.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus fa-sm"></i> Tambah Menu
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Menu Tree -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Struktur Menu</h6>
        </div>
        <div class="card-body">
            @if($menuItems->isEmpty())
                <div class="text-center py-5">
                    <p class="text-muted">Belum ada menu. Klik tombol "Tambah Menu" untuk membuat menu pertama.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" id="menuTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="40%">Judul</th>
                                <th width="30%">URL</th>
                                <th width="10%">Urutan</th>
                                <th width="10%">Status</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $menuItems = $menuItems->sortBy('sort_order');
                            @endphp
                            
                            @foreach($menuItems->whereNull('parent_id') as $level1)
                                <!-- Level 1 -->
                                <tr class="table-primary">
                                    <td>
                                        <strong><i class="fas fa-folder-open text-primary mr-1"></i> {{ $level1->title }}</strong>
                                    </td>
                                    <td>{{ $level1->url ?? '-' }}</td>
                                    <td>{{ $level1->sort_order }}</td>
                                    <td>
                                        <span class="badge badge-{{ $level1->is_active ? 'success' : 'secondary' }}">
                                            {{ $level1->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.menu-items.create', ['parent_id' => $level1->id]) }}" 
                                               class="btn btn-info btn-sm" title="Tambah Submenu">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                            <a href="{{ route('admin.menu-items.edit', $level1->id) }}" 
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.menu-items.toggle-active', $level1->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-{{ $level1->is_active ? 'secondary' : 'success' }} btn-sm" title="{{ $level1->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="fas fa-{{ $level1->is_active ? 'ban' : 'check' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.menu-items.destroy', $level1->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus menu ini? Submenu akan dipindahkan ke level atas.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Level 2 -->
                                @foreach($level1->children->sortBy('sort_order') as $level2)
                                    <tr class="table-secondary">
                                        <td style="padding-left: 40px;">
                                            <i class="fas fa-folder text-secondary mr-1"></i> {{ $level2->title }}
                                        </td>
                                        <td>{{ $level2->url ?? '-' }}</td>
                                        <td>{{ $level2->sort_order }}</td>
                                        <td>
                                            <span class="badge badge-{{ $level2->is_active ? 'success' : 'secondary' }}">
                                                {{ $level2->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.menu-items.create', ['parent_id' => $level2->id]) }}" 
                                                   class="btn btn-info btn-sm" title="Tambah Submenu Level 3">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                                <a href="{{ route('admin.menu-items.edit', $level2->id) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.menu-items.toggle-active', $level2->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-{{ $level2->is_active ? 'secondary' : 'success' }} btn-sm" title="{{ $level2->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                        <i class="fas fa-{{ $level2->is_active ? 'ban' : 'check' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.menu-items.destroy', $level2->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus menu ini? Submenu akan dipindahkan ke level 2.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <!-- Level 3 -->
                                    @foreach($level2->children->sortBy('sort_order') as $level3)
                                        <tr>
                                            <td style="padding-left: 80px;">
                                                <i class="fas fa-file-alt text-muted mr-1"></i> {{ $level3->title }}
                                            </td>
                                            <td>{{ $level3->url ?? '-' }}</td>
                                            <td>{{ $level3->sort_order }}</td>
                                            <td>
                                                <span class="badge badge-{{ $level3->is_active ? 'success' : 'secondary' }}">
                                                    {{ $level3->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.menu-items.edit', $level3->id) }}" 
                                                       class="btn btn-warning btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.menu-items.toggle-active', $level3->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-{{ $level3->is_active ? 'secondary' : 'success' }} btn-sm" title="{{ $level3->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                            <i class="fas fa-{{ $level3->is_active ? 'ban' : 'check' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.menu-items.destroy', $level3->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table td {
        vertical-align: middle;
    }
</style>
@endpush
