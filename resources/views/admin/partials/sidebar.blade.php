<nav class="menu text-sm">
    <!-- Dashboard -->
    <div class="menu-item">
        <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt me-3 text-base"></i>
            <span>Dashboard</span>
        </a>
    </div>

    <!-- Content Management -->
    <div class="menu-header mt-4 px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
        Manajemen Konten
    </div>

    <div class="menu-item">
        <a href="{{ route('admin.posts.index') }}" class="menu-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
            <i class="fas fa-newspaper me-3 text-base"></i>
            <span>Berita & Posts</span>
        </a>
    </div>

    <div class="menu-item">
        <a href="{{ route('admin.categories.index') }}" class="menu-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="fas fa-folder me-3 text-base"></i>
            <span>Kategori</span>
        </a>
    </div>

    <div class="menu-item">
        <a href="{{ route('admin.pages.index') }}" class="menu-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
            <i class="fas fa-file me-3 text-base"></i>
            <span>Halaman</span>
        </a>
    </div>

    <div class="menu-item">
        <a href="{{ route('admin.agendas.index') }}" class="menu-link {{ request()->routeIs('admin.agendas.*') ? 'active' : '' }}">
            <i class="fas fa-calendar me-3 text-base"></i>
            <span>Agenda</span>
        </a>
    </div>

    <div class="menu-item">
        <a href="{{ route('admin.downloads.index') }}" class="menu-link {{ request()->routeIs('admin.downloads.*') ? 'active' : '' }}">
            <i class="fas fa-download me-3 text-base"></i>
            <span>Download</span>
        </a>
    </div>

    <!-- Website Settings -->
    <div class="menu-header mt-4 px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
        Pengaturan Website
    </div>

    <div class="menu-item">
        <a href="{{ route('admin.settings.index') }}" class="menu-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="fas fa-cog me-3 text-base"></i>
            <span>Pengaturan Umum</span>
        </a>
    </div>

    <div class="menu-item">
        <a href="{{ route('admin.external-links.index') }}" class="menu-link {{ request()->routeIs('admin.external-links.*') ? 'active' : '' }}">
            <i class="fas fa-link me-3 text-base"></i>
            <span>Link Terkait</span>
        </a>
    </div>

    <div class="menu-item">
        <a href="{{ route('admin.menu-items.index') }}" class="menu-link {{ request()->routeIs('admin.menu-items.*') ? 'active' : '' }}">
            <i class="fas fa-bars me-3 text-base"></i>
            <span>Menu</span>
        </a>
    </div>

    <!-- User Management (Admin only) -->
    @if(auth()->check() && (auth()->user()->role_name === 'admin' || auth()->user()->role === 'admin'))
    <div class="menu-header mt-4 px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
        Sistem
    </div>

    <div class="menu-item">
        <a href="{{ route('admin.users.index') }}" class="menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fas fa-users me-3 text-base"></i>
            <span>Pengguna</span>
        </a>
    </div>
    @endif

    <!-- View Website -->
    <div class="menu-header mt-4 px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
        Lainnya
    </div>

    <div class="menu-item">
        <a href="{{ url('/') }}" target="_blank" class="menu-link">
            <i class="fas fa-external-link-alt me-3 text-base"></i>
            <span>Lihat Website</span>
        </a>
    </div>
</nav>

<style>
    .menu {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .menu-item {
        margin: 2px 8px;
    }

    .menu-link {
        display: flex;
        align-items: center;
        padding: 10px 12px;
        border-radius: 8px;
        color: #4b5563;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .menu-link:hover {
        background-color: #f3f4f6;
        color: #2563eb;
    }

    .menu-link.active {
        background-color: #eff6ff;
        color: #2563eb;
        font-weight: 500;
    }

    .menu-link i {
        width: 20px;
        text-align: center;
    }

    .menu-header {
        margin-top: 8px;
    }
</style>
