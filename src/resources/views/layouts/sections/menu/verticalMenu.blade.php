@php
    $configData = Helper::appClasses();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <!-- ! Hide app brand if navbar-full -->
    @if (!isset($navbarFull))
        <div class="app-brand demo">
            <a href="{{ url('/') }}" class="app-brand-link">
                <span class="app-brand-logo demo">
                    @include('_partials.macros', ['height' => 20])
                </span>
                <span class="app-brand-text demo menu-text fw-bold">{{ config('variables.templateName') }}</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
                <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
            </a>
        </div>
    @endif


    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Dashboard</span>
        </li>
        <li class="menu-item {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
            <a href="{{ route('dashboard.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-dashboard"></i>
                <div>Dashboard</div>
            </a>
        </li>
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Data</span>
        </li>
        {{-- CRUD-GENERATOR-SIDEBAR --}}
        @can('media-index')
            <li class="menu-item {{ request()->routeIs('media.*') ? 'active' : '' }}">
                <a href="{{ route('media.index') }}" class="menu-link">
                    <i class="menu-icon far fa-images"></i>
                    <div>Media</div>
                </a>
            </li>
        @endcan
        @can('crud-index')
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Generator</span>
            </li>
            <li class="menu-item {{ request()->routeIs('crud.*') ? 'active' : '' }}">
                <a href="{{ route('crud.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-user"></i>
                    <div>CRUD Generator</div>
                </a>
            </li>
        @endcan
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Pengguna</span>
        </li>
        @can('user-index')
            <li class="menu-item {{ request()->routeIs('user.*') ? 'active' : '' }}">
                <a href="{{ route('user.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-user"></i>
                    <div>User</div>
                </a>
            </li>
        @endcan
        <li class="menu-item {{ request()->routeIs(['role.*', 'permission.*']) ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-settings"></i>
                <div>Roles &amp; Permissions</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('role.*') ? 'active' : '' }}">
                    <a href="{{ route('role.index') }}" class="menu-link">
                        <div>Roles</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('permission.*') ? 'active' : '' }}">
                    <a href="{{ route('permission.index') }}" class="menu-link">
                        <div>Permission</div>
                    </a>
                </li>
            </ul>
        </li>
        @can('role-index')
            <li class="menu-item {{ request()->routeIs('audit.*') ? 'active' : '' }}">
                <a href="{{ route('audit.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-line-dashed"></i>
                    <div>Audit</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="https://fontawesome.com/v5/search?o=r&m=free&s=regular" target="_blank" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-upload"></i>
                    <div>Font Awesome</div>
                </a>
            </li>
        @endcan
    </ul>

</aside>
