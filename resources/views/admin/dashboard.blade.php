{{-- resources\views\admin\dashboard.blade.php --}}
{{-- Admin Dashboard with AdminLTE --}}

@extends('admin.layouts.adminlte')

@section('title', 'Dashboard')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Dashboard</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>
</div>
@endsection

@section('content')

{{-- Admin Dashboard Stats --}}
@if($isAdmin)
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $postCount }}</h3>
                <p>Total Posts</p>
            </div>
            <div class="icon">
                <i class="fas fa-newspaper"></i>
            </div>
            <a href="{{ route('admin.posts.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $categoryCount }}</h3>
                <p>Categories</p>
            </div>
            <div class="icon">
                <i class="fas fa-folder"></i>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $pageCount }}</h3>
                <p>Pages</p>
            </div>
            <div class="icon">
                <i class="fas fa-file"></i>
            </div>
            <a href="{{ route('admin.pages.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $agendaCount }}</h3>
                <p>Agendas</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar"></i>
            </div>
            <a href="{{ route('admin.agendas.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $downloadCount }}</h3>
                <p>Downloads</p>
            </div>
            <div class="icon">
                <i class="fas fa-download"></i>
            </div>
            <a href="{{ route('admin.downloads.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ $externalLinkCount }}</h3>
                <p>External Links</p>
            </div>
            <div class="icon">
                <i class="fas fa-link"></i>
            </div>
            <a href="{{ route('admin.external-links.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $publishedCount }}</h3>
                <p>Published Posts</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('admin.posts.index', ['status' => 'published']) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $draftCount }}</h3>
                <p>Draft Posts</p>
            </div>
            <div class="icon">
                <i class="fas fa-edit"></i>
            </div>
            <a href="{{ route('admin.posts.index', ['status' => 'draft']) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
@else
{{-- Non-Admin Dashboard Stats --}}
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $postCount }}</h3>
                <p>My Posts</p>
            </div>
            <div class="icon">
                <i class="fas fa-newspaper"></i>
            </div>
            <a href="{{ route('admin.posts.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $publishedCount }}</h3>
                <p>Published</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('admin.posts.index', ['status' => 'published']) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $draftCount }}</h3>
                <p>Drafts</p>
            </div>
            <div class="icon">
                <i class="fas fa-edit"></i>
            </div>
            <a href="{{ route('admin.posts.index', ['status' => 'draft']) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Posts</h3>
            </div>
            <div class="card-body table-responsive p-0">
                @if(isset($recentPosts) && $recentPosts->count() > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPosts as $post)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.posts.edit', $post->id) }}">{{ Str::limit($post->title, 40) }}</a>
                                    </td>
                                    <td>{{ $post->category?->name ?? '-' }}</td>
                                    <td>
                                        @if($post->status === 'published')
                                            <span class="badge badge-success">Published</span>
                                        @else
                                            <span class="badge badge-secondary">Draft</span>
                                        @endif
                                    </td>
                                    <td>{{ $post->created_at?->format('d M Y') ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted text-center p-3">No recent posts</p>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.posts.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-list mr-1"></i> View All Posts
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-block mb-2">
                    <i class="fas fa-plus mr-1"></i> Create New Post
                </a>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-info btn-block mb-2">
                    <i class="fas fa-list mr-1"></i> View All Posts
                </a>
                @if($isAdmin)
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-block mb-2">
                    <i class="fas fa-folder mr-1"></i> Manage Categories
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-block">
                    <i class="fas fa-users mr-1"></i> Manage Users
                </a>
                @endif
            </div>
        </div>
        
        @if($isAdmin && $recentAgendas->count() > 0)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Upcoming Agendas</h3>
            </div>
            <div class="card-body p-0">
                <ul class="todo-list" data-widget="todo-list">
                    @foreach($recentAgendas as $agenda)
                    <li>
                        <div class="icheck-primary d-inline ml-2">
                            <span>{{ $agenda->title }}</span>
                            <small class="badge badge-info"><i class="far fa-calendar-alt mr-1"></i>{{ $agenda->date?->format('d M Y') ?? 'TBA' }}</small>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.agendas.index') }}" class="btn btn-default btn-sm">
                    <i class="fas fa-calendar mr-1"></i> View All Agendas
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
