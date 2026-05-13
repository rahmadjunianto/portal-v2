{{-- resources\views\admin\external-links\index.blade.php --}}
{{-- External Links List with AdminLTE --}}

@extends('admin.layouts.adminlte')

@section('title', 'External Links')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">External Links</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">External Links</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All External Links</h3>
        <div class="card-tools">
            <a href="{{ route('admin.links.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Add New Link
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
        
        <table class="table table-bordered table-striped data-table">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Title</th>
                    <th>URL</th>
                    <th>Icon</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th width="150">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($links as $key => $link)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td><strong>{{ $link->title }}</strong></td>
                        <td><a href="{{ $link->url }}" target="_blank">{{ Str::limit($link->url, 40) }}</a></td>
                        <td>
                            @if($link->icon)
                                <i class="{{ $link->icon }}"></i>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $link->sort_order }}</td>
                        <td>
                            @if($link->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.links.edit', $link->id) }}" class="btn btn-info btn-xs">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.links.destroy', $link->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this link?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No external links found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
        <div class="card-footer">
            {{ $links->withQueryString()->links('pagination::bootstrap-4') }}
        </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    $('.data-table').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
});
</script>
@endpush
