@extends('admin.layouts.adminlte')

@section('title', 'Agendas - Agenda')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Agenda</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Agenda</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <form action="{{ route('admin.agendas.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Cari judul, deskripsi, lokasi..." 
                                   value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-3">
                    <form action="{{ route('admin.agendas.index') }}" method="GET">
                        <select name="year" class="form-control" onchange="this.form.submit()">
                            <option value="">Semua Tahun</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="col-md-3 text-right">
                    <a href="{{ route('admin.agendas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Agenda
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Gambar</th>
                            <th>Judul</th>
                            <th>Tanggal</th>
                            <th>Lokasi</th>
                            <th>Penulis</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agendas as $key => $agenda)
                            <tr>
                                <td>{{ $agendas->firstItem() + $key }}</td>
                                <td>
                                    @if($agenda->image)
                                        @php
                                            $imagePath = 'storage/' . $agenda->image;
                                            $publicPath = public_path($imagePath);
                                        @endphp
                                        @if(file_exists($publicPath))
                                            <img src="{{ asset($imagePath) }}" 
                                                 alt="{{ $agenda->title }}" 
                                                 style="max-width: 80px; max-height: 60px; object-fit: cover;">
                                        @else
                                            <span class="badge badge-warning" title="Gambar tidak ditemukan: {{ $agenda->image }}">No Image</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $agenda->title }}</td>
                                <td>
                                    <small>
                                        {{ $agenda->start_date->format('d/m/Y') }}
                                        @if($agenda->end_date && $agenda->end_date->format('d/m/Y') !== $agenda->start_date->format('d/m/Y'))
                                            - {{ $agenda->end_date->format('d/m/Y') }}
                                        @endif
                                        @if($agenda->event_time_text)
                                            <br><span class="text-muted">{{ $agenda->event_time_text }}</span>
                                        @endif
                                    </small>
                                </td>
                                <td>{{ $agenda->location ?? '-' }}</td>
                                <td>{{ $agenda->author->name ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.agendas.edit', $agenda->id) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.agendas.destroy', $agenda->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus agenda ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data agenda.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer">
            <div class="float-right">
                {{ $agendas->links() }}
            </div>
        </div>
    </div>
@endsection
