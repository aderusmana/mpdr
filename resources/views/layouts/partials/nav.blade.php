<nav class="semi-nav dark-sidebar selected">
    <div class="app-logo">
        <a class="logo d-inline-block" href="{{ route('dashboard') }}">
            {{-- Menggunakan logo dari desain lama --}}
            <img alt="logo" src="{{ asset('assets') }}/images/logo/logohitam.png" style="width: 150px;">
        </a>

        <span class="bg-light-primary toggle-semi-nav">
            <i class="ti ti-chevrons-right f-s-20"></i>
        </span>
    </div>
    <div class="app-nav" id="app-simple-bar">
        <ul class="main-nav p-0 mt-2">

            {{-- Menu: Home --}}
            <li class="menu-title">
                <span>Home</span>
            </li>
            <li class="no-sub">
                <a class="" href="{{ route('dashboard') }}">
                    <i class="ti ti-dashboard"></i> Dashboard
                </a>
            </li>

            {{-- Menu: Management (Hanya untuk Super Admin) --}}
            @if(auth()->user()->hasRole('super-admin'))
                <li class="menu-title"><span>Management</span></li>
                <li>
                    <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#user-management">
                        <i class="ti ti-chart-donut-3"></i> User Management
                    </a>
                    <ul class="collapse" id="user-management">
                        <li><a href="{{ route('users.index') }}">User</a></li>
                        <li><a href="{{ route('departments.index') }}">Department</a></li>
                        <li><a href="{{ route('positions.index') }}">Position</a></li>
                        <li><a href="{{ route('levels.index') }}">Level</a></li>
                        <li><a href="{{ route('roles.index') }}">Role</a></li>
                        <li><a href="{{ route('permissions.index') }}">Permission</a></li>
                    </ul>
                </li>
            @endif

            {{-- Menu: Pre MPDR --}}
            <li class="menu-title"><span>Pre MPDR</span></li>
            <li>
                <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#prempdr">
                    <i class="ti ti-file-text"></i> Pre MPDR
                </a>
                <ul class="collapse" id="prempdr">
                    <li><a href="{{ route('prempdr.index') }}">List Pre MPDR</a></li>
                    <li><a href="{{ route('prempdr.report') }}">Report Pre MPDR</a></li>
                    <li><a href="{{ route('prempdr.approval') }}">Approval Pre MPDR</a></li>
                    <li><a href="{{ route('prempdr.log') }}">Log Pre MPDR</a></li>
                    <li><a href="{{ route('prempdr.approver') }}">Approver Pre MPDR</a></li>
                </ul>
            </li>

            {{-- Menu: MPDR --}}
            <li class="menu-title"><span>MPDR</span></li>
            <li>
                <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#mpdr">
                    <i class="ti ti-file-invoice"></i> MPDR
                </a>
                <ul class="collapse" id="mpdr">
                    <li><a href="{{ route('mpdr.index') }}">List MPDR</a></li>
                    <li><a href="{{ route('mpdr.reports') }}">Report MPDR</a></li>
                    <li><a href="{{ route('mpdr.approval') }}">Approval MPDR</a></li>
                    <li><a href="{{ route('mpdr.log') }}">Log MPDR</a></li>
                    <li><a href="{{ route('mpdr.approver') }}">Approver MPDR</a></li>
                </ul>
            </li>

        </ul>
    </div>


    <div class="menu-navs">
        <span class="menu-previous"><i class="ti ti-chevron-left"></i></span>
        <span class="menu-next"><i class="ti ti-chevron-right"></i></span>
    </div>

</nav>
