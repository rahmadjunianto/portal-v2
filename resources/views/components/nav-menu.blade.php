{{-- resources/views/components/nav-menu.blade.php --}}
{{-- Usage: <x-nav-menu :items="$mainMenus" /> --}}

@props([
    'items' => collect(),
    'level' => 0,
    'currentPath' => '/' . request()->path()
])

@foreach($items as $item)
    @php
        $isActive = $item->url === $currentPath;
        $hasChildren = $item->children && $item->children->count() > 0;
    @endphp

    @if($hasChildren)
        {{-- Dropdown Parent --}}
        <li class="nav-item dropdown {{ $isActive ? 'active' : '' }}">
            <a class="nav-link dropdown-toggle" href="#"
               data-bs-toggle="dropdown" role="button">
                {{ $item->title }}
            </a>
            <ul class="dropdown-menu">
                {{-- Recursive children --}}
                @foreach($item->children as $child)
                    @php
                        $childActive = $child->url === $currentPath;
                        $childHasChildren = $child->children && $child->children->count() > 0;
                    @endphp

                    @if($childHasChildren)
                        {{-- Submenu --}}
                        <li class="dropend">
                            <a class="dropdown-item dropdown-toggle" href="{{ $child->url ?? '#' }}">
                                {{ $child->title }}
                            </a>
                            <ul class="dropdown-menu">
                                @foreach($child->children as $grandChild)
                                    <li>
                                        <a class="dropdown-item {{ $grandChild->url === $currentPath ? 'active' : '' }}"
                                           href="{{ $grandChild->url ?? '#' }}">
                                            {{ $grandChild->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li>
                            <a class="dropdown-item {{ $childActive ? 'active' : '' }}"
                               href="{{ $child->url ?? '#' }}">
                                {{ $child->title }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </li>
    @else
        {{-- Simple Link --}}
        <li class="nav-item {{ $isActive ? 'active' : '' }}">
            <a class="nav-link" href="{{ $item->url ?? '#' }}">
                {{ $item->title }}
            </a>
        </li>
    @endif
@endforeach
