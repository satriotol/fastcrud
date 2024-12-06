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
@can('menu-index')
    <li class="menu-item {{ request()->routeIs('menu.*') ? 'active' : '' }}">
        <a href="{{ route('menu.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-menu"></i>
            <div>Menu</div>
        </a>
    </li>
@endcan
@can('media-index')
    <li class="menu-item {{ request()->routeIs('media.*') ? 'active' : '' }}">
        <a href="{{ route('media.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-photo"></i>
            <div>Media</div>
        </a>
    </li>
@endcan
@can('config-index')
    <li class="menu-item {{ request()->routeIs('config.*') ? 'active' : '' }}">
        <a href="{{ route('config.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-settings-automation"></i>
            <div>Config</div>
        </a>
    </li>
@endcan
@role('SUPERADMIN')
    <li class="menu-item {{ request()->routeIs('app-specs.index') ? 'active' : '' }}">
        <a href="{{ route('app-specs.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-section"></i>
            <div>Spesifikasi</div>
        </a>
    </li>
@endrole
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
@can('api_key-index')
    <li class="menu-item {{ request()->routeIs('api_key.*') ? 'active' : '' }}">
        <a href="{{ route('api_key.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-key"></i>
            <div>ApiKey</div>
        </a>
    </li>
@endcan
@can('role-index')
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
