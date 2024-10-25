<ul class="navbar-nav ml-auto">
    <!-- Navbar Notification -->
    {{-- @role('auditor')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('notifikasi') }}">
                <i class="far fa-bell"></i>
                @if ($notificationCount > 0)
                    <span class="badge badge-warning navbar-badge blink">{{ $notificationCount }}</span>
                @endif
            </a>
        </li>
    @endrole --}}

    @role(['pj_universitas', 'pj_fakultas', 'pj_prodi', 'gkm', 'gpm', 'auditor'])
        <li class="nav-item">
            <a class="nav-link" href="{{ route('notifikasi') }}">
                <i class="far fa-bell"></i>
                @if ($notificationCount > 0)
                    <span class="badge badge-warning navbar-badge blink">{{ $notificationCount }}</span>
                @endif
            </a>
        </li>
    @endrole

    {{-- User Profile --}}
    <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false" style="margin-top:-6px">
            <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
            <img class="img-profile rounded-circle" src="{{ asset('dist/img/logo_unsoed.png') }}"
                style="widht:30px; height:30px;">
        </a>
        <!-- Dropdown - User Information -->
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown"
            style="font-size: 14px">
            <a class="dropdown-item text-gray-400" href="{{ route('profile') }}">
                <i class="fas fa-user fa-sm fa-fw mr-2"></i>
                Profile
            </a>
            <div class="dropdown-divider"></div>
            <form action="{{ route('logout') }}" method="post" class="d-inline">
                @csrf
                <button type="submit" class="dropdown-item"><i
                        class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i>Logout</button>
            </form>
        </div>
    </li>
</ul>
