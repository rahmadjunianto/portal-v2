{{-- resources\views\admin\posts\index.blade.php --}}
{{-- Posts List with AdminLTE --}}

@extends('admin.layouts.adminlte')

@section('title', 'Posts')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Posts</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Posts</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
{{-- Filter Form --}}
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-filter mr-1"></i> Filter Posts
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.posts.index') }}" class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" class="form-control">
                        <option value="">All Types</option>
                        <option value="berita" {{ request('type') == 'berita' ? 'selected' : '' }}>Berita</option>
                        <option value="pengumuman" {{ request('type') == 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                        <option value="lowongan" {{ request('type') == 'lowongan' ? 'selected' : '' }}>Lowongan</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" class="form-control">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            @if(auth()->user()->role_name === 'admin')
            <div class="col-md-2">
                <div class="form-group">
                    <label>Author</label>
                    <select name="author" class="form-control">
                        <option value="">All Authors</option>
                        @foreach(\App\Models\User::orderBy('name')->get() as $user)
                            <option value="{{ $user->id }}" {{ request('author') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif
            
            <div class="col-md-2">
                <div class="form-group">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search title..." value="{{ request('search') }}">
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-default">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Posts</h3>
        <div class="card-tools">
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Add New Post
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
        
        @if(request()->hasAny(['status', 'type', 'category', 'author', 'search']))
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-1"></i>
                Showing filtered results. 
                <a href="{{ route('admin.posts.index') }}" class="alert-link">Clear filters</a>
            </div>
        @endif
        
        <table class="table table-bordered table-striped data-table">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th width="150">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $key => $post)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            <strong>{{ Str::limit($post->title, 50) }}</strong>
                            @if($post->is_headline)
                                <span class="badge badge-danger">Headline</span>
                            @endif
                            @if($post->is_featured)
                                <span class="badge badge-warning">Featured</span>
                            @endif
                        </td>
                        <td>{{ $post->category?->name ?? '-' }}</td>
                        <td>
                            @if($post->type === 'berita')
                                <span class="badge badge-primary">Berita</span>
                            @elseif($post->type === 'pengumuman')
                                <span class="badge badge-info">Pengumuman</span>
                            @elseif($post->type === 'lowongan')
                                <span class="badge badge-success">Lowongan</span>
                            @endif
                        </td>
                        <td>
                            @if($post->status === 'published')
                                <span class="badge badge-success">Published</span>
                            @else
                                <span class="badge badge-secondary">Draft</span>
                            @endif
                        </td>
                        <td>{{ $post->author?->name ?? 'Unknown' }}</td>
                        <td>{{ $post->created_at?->format('d M Y') ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-info btn-xs">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            @if(auth()->user()->role_name === 'admin' || $post->author_id === auth()->id())
                            <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No posts found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
        <div class="card-footer">
            {{ $posts->withQueryString()->links('pagination::bootstrap-4') }}
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
