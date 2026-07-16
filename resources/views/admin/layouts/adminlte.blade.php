{{-- resources\views\admin\layouts\adminlte.blade.php --}}
{{-- Main Admin LTE Template for Portal Kemenag Nganjuk --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Portal Kemenag Nganjuk') | {{ config('adminlte.title', 'Admin Panel') }}</title>

    {{-- Google Fonts --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    {{-- AdminLTE CSS from CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    {{-- Summernote WYSIWYG Editor --}}
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

    {{-- bs-custom-file-input for file inputs --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.css">

    @yield('plugins_head')

    {{-- Stack for page-specific styles --}}
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini @yield('body_class', '')">

<div class="wrapper">

    {{-- Navbar --}}
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i> {{ auth()->user()->name ?? 'Admin' }}
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">
                        <i class="fas fa-user mr-2"></i> Profil
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    {{-- Main Sidebar Container --}}
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ url('admin/dashboard') }}" class="brand-link">
            <span class="brand-text font-weight-light">{{ config('adminlte.title', 'Admin Panel') }}</span>
        </a>

        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    {{-- Dashboard - visible to all --}}
                    <li class="nav-item">
                        <a href="{{ url('admin/dashboard') }}" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    
                    {{-- Posts - visible to all roles --}}
                    <li class="nav-item">
                        <a href="{{ route('admin.posts.index') }}" class="nav-link {{ request()->is('admin/posts*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-newspaper"></i>
                            <p>Posts</p>
                        </a>
                    </li>
                    
                    {{-- Admin-only menus --}}
                    @if(auth()->user() && auth()->user()->role_name === 'admin')
                        <li class="nav-header text-muted">MANAJEMEN KONTEN</li>
                        
                        <li class="nav-item">
                            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-folder"></i>
                                <p>Categories</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.pages.index') }}" class="nav-link {{ request()->is('admin/pages*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file"></i>
                                <p>Pages</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.agendas.index') }}" class="nav-link {{ request()->is('admin/agendas*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-calendar"></i>
                                <p>Agendas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.downloads.index') }}" class="nav-link {{ request()->is('admin/downloads*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-download"></i>
                                <p>Downloads</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.external-links.index') }}" class="nav-link {{ request()->is('admin/external-links*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-link"></i>
                                <p>External Links</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.menu-items.index') }}" class="nav-link {{ request()->is('admin/menu-items*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-bars"></i>
                                <p>Menu</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.chat-conversations.index') }}" class="nav-link {{ request()->is('admin/chat-conversations*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-comments"></i>
                                <p>Chat Conversations</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('admin.whatsapp-conversations.index') }}" class="nav-link {{ request()->is('admin/whatsapp-conversations*') ? 'active' : '' }}">
                                <i class="nav-icon fab fa-whatsapp" style="color: #25d366;"></i>
                                <p>WhatsApp</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.knowledge-bank.index') }}" class="nav-link {{ request()->is('admin/knowledge-bank*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-database text-info"></i>
                                <p>Bank Data AI</p>
                            </a>
                        </li>
                        
                        <li class="nav-header text-muted">PENGATURAN</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>Settings</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Users</p>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </aside>

    {{-- Content Wrapper. Contains page content --}}
    <div class="content-wrapper">
        
        {{-- Content Header --}}
        <div class="content-header">
            <div class="container-fluid">
                @hasSection('content_header')
                    @yield('content_header')
                @else
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('title', 'Dashboard')</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                                @hasSection('breadcrumb')
                                    @yield('breadcrumb')
                                @endif
                            </ol>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Main Content --}}
        <div class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

    </div>
    {{-- /.content-wrapper --}}

    {{-- Footer --}}
    <footer class="main-footer">
        <div class="float-right d-none d-sm-inline">
            Portal Kemenag Nganjuk
        </div>
        <strong>&copy; {{ date('Y') }}</strong>
    </footer>

</div>
{{-- /.wrapper --}}

{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- Bootstrap 4 --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- AdminLTE JS from CDN --}}
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>

{{-- Summernote WYSIWYG Editor --}}
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/lang/summernote-id-ID.min.js"></script>

{{-- bs-custom-file-input for file inputs --}}
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize bs-custom-file-input
    bsCustomFileInput.init();
});
</script>

@yield('plugins_footer')

{{-- Stack for page-specific scripts --}}
@stack('scripts')

</body>
</html>
