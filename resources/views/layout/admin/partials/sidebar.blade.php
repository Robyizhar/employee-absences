<nav class="sidebar">
    <div class="sidebar-header">
    <a href="#" class="sidebar-brand">
        Dapur<span>MBG</span>
    </a>
    <div class="sidebar-toggler not-active">
        <span></span>
        <span></span>
        <span></span>
    </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">Main</li>
            <li class="nav-item {{ Request::segment(1) == '' ? 'active' : '' }}">
                <a href="{{url('/')}}" class="nav-link">
                    <i class="link-icon" data-feather="box"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item nav-category">WEB APP</li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#absence" role="button" aria-expanded="false" aria-controls="absence">
                    <i class="link-icon" data-feather="mail"></i>
                        <span class="link-title">Absence</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="absence">
                    <ul class="nav sub-menu">
                        <li class="nav-item ">
                            <a href="{{ url('absence') }}" class="nav-link {{ Request::segment(1) == 'absence' && Request::segment(2) == '' ? 'active' : '' }}">Absence Log</a>
                        </li>
                        <li class="nav-item ">
                            <a href="{{ url('absence/recapitulation') }}" class="nav-link {{ Request::segment(1) == 'absence' && Request::segment(2) == 'recapitulation' ? 'active' : '' }}">Absence Recap</a>
                        </li>
                    </ul>
                </div>
            </li>
            @if (Auth::user()->getRoleNames()[0] == 'Maintener' || Auth::user()->hasAnyPermission(['master_employees']))
            <li class="nav-item {{ Request::segment(1) == 'employee' ? 'active' : '' }}">
                <a href="{{ url('employee') }}" class="nav-link">
                    <i class="link-icon" data-feather="users"></i>
                    <span class="link-title">Employees</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->getRoleNames()[0] == 'Maintener' || Auth::user()->hasAnyPermission(['master_companies']))
            <li class="nav-item {{ Request::segment(1) == 'company' ? 'active' : '' }}">
                <a href="{{ url('company') }}" class="nav-link ">
                    <i class="link-icon" data-feather="home"></i>
                    <span class="link-title">Companies</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->getRoleNames()[0] == 'Maintener' || Auth::user()->hasAnyPermission(['master_companies']))
            <li class="nav-item {{ Request::segment(1) == 'department' ? 'active' : '' }}">
                <a href="{{ url('department') }}" class="nav-link ">
                    <i class="link-icon" data-feather="hard-drive"></i>
                    <span class="link-title">Departement</span>
                </a>
            </li>
            @endif

            @if (Auth::user()->getRoleNames()[0] == 'Maintener' || Auth::user()->hasAnyPermission(['setting']))
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#settings" role="button" aria-expanded="false" aria-controls="settings">
                    <i class="link-icon" data-feather="settings"></i>
                        <span class="link-title">Setting</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="settings">
                    <ul class="nav sub-menu">
                        <li class="nav-item ">
                            <a href="{{ url('user') }}" class="nav-link {{ Request::segment(1) == 'user' ? 'active' : '' }}">User</a>
                        </li>
                        <li class="nav-item ">
                            <a href="{{ url('role') }}" class="nav-link {{ Request::segment(1) == 'role' ? 'active' : '' }}">Role</a>
                        </li>
                        <li class="nav-item ">
                            <a href="{{ url('permission') }}" class="nav-link {{ Request::segment(1) == 'permission' ? 'active' : '' }}">Permission</a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif
        </ul>
    </div>
</nav>
{{-- <nav class="settings-sidebar">
    <div class="sidebar-body">
        <a href="#" class="settings-sidebar-toggler">
            <i data-feather="settings"></i>
        </a>
        <h6 class="text-muted mb-2">Sidebar:</h6>
        <div class="mb-3 pb-3 border-bottom">
            <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input" name="sidebarThemeSettings" id="sidebarLight" value="sidebar-light" checked>
                <label class="form-check-label" for="sidebarLight">
                    Light
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input" name="sidebarThemeSettings" id="sidebarDark" value="sidebar-dark">
                <label class="form-check-label" for="sidebarDark">
                    Dark
                </label>
            </div>
        </div>
        <div class="theme-wrapper">
            <h6 class="text-muted mb-2">Light Theme:</h6>
            <a class="theme-item active" href="#">
                <img src="../assets/images/screenshots/light.jpg" alt="light theme">
            </a>
            <h6 class="text-muted mb-2">Dark Theme:</h6>
            <a class="theme-item" href="#">
                <img src="../assets/images/screenshots/dark.jpg" alt="light theme">
            </a>
        </div>
    </div>
</nav> --}}
