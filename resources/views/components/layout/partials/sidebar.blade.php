<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        @if (Auth::user()->jabatan->isNotEmpty() && Auth::user()->jabatan->first()->slug === 'rektor')
            <li class="nav-item">
                <a href="{{ url('dashboard') }}"
                    class="nav-link {{ Request::is('dashboard') || Request::is('dashboard') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-circle"></i>
                    <p>
                        Dashboard
                    </p>
                </a>
            </li>
        @else
            @php
                $hasGpmRole = Auth::user()->roles->contains('name', 'gpm');
                $totalRoles = Auth::user()->roles->count();

                if (Auth::user()->jabatan->isNotEmpty() && Auth::user()->jabatan->first()->slug === 'dekan') {
                    $menus[] = (object) [
                        'id' => 1000,
                        'menu' => 'Hasil Audit Prodi',
                        'status' => true,
                        'route' => 'hasil-audit-prodi',
                    ];
                } elseif ($hasGpmRole && $totalRoles !== 1) {
                    $menus[] = (object) [
                        'id' => 999,
                        'menu' => 'GPM',
                        'status' => true,
                        'route' => 'gpm',
                    ];

                    $menus[] = (object) [
                        'id' => 1000,
                        'menu' => 'Hasil Audit Prodi',
                        'status' => true,
                        'route' => 'hasil-audit-prodi',
                    ];

                    $submenus[] = (object) [
                        'menu_id' => 999,
                        'submenu' => 'Auditor',
                        'status' => true,
                        'route' => 'auditor',
                    ];

                    // $submenus[] = (object) [
                    //     'menu_id' => 999,
                    //     'submenu' => 'Hasil Audit Prodi',
                    //     'status' => true,
                    //     'route' => 'hasil-audit-prodi',
                    // ];
                }

            @endphp
            @forelse ($menus as $menu)
                @php
                    $menu_submenus = $submenus->filter(function ($submenu) use ($menu) {
                        return $submenu->menu_id == $menu->id;
                    });
                    $has_submenu = $menu_submenus->isNotEmpty();
                @endphp
                @if ($has_submenu)
                    <li class="nav-item {{ Request::is($menu->route . '/*') ? 'menu-open' : '' }}">
                        <a href="{{ url($menu->route) }}"
                            class="nav-link {{ Request::is($menu->route . '/*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-circle"></i>
                            <p>
                                {{ $menu->menu }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @foreach ($menu_submenus as $submenu)
                                <li class="nav-item">
                                    <a href="{{ url($menu->route . '/' . $submenu->route) }}"
                                        class="nav-link {{ Request::is($menu->route . '/' . $submenu->route) || Request::is($menu->route . '/' . $submenu->route . '/*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ $submenu->submenu }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ url($menu->route) }}"
                            class="nav-link {{ Request::is($menu->route . '/*') || Request::is($menu->route) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-circle"></i>
                            <p>
                                {{ $menu->menu }}
                            </p>
                        </a>
                    </li>
                @endif

            @empty
                <li class="nav-item">
                    <a href="{{ url('dashboard') }}"
                        class="nav-link {{ Request::is('dashboard') || Request::is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-circle"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                @if ($hasGpmRole && $totalRoles === 1)
                    <li class="nav-item {{ Request::is('gpm' . '/*') ? 'menu-open' : '' }}">
                        <a href="{{ url('gpm') }}"
                            class="nav-link {{ Request::is('gpm' . '/*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-circle"></i>
                            <p>
                                GPM
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('gpm' . '/' . 'auditor') }}"
                                    class="nav-link {{ Request::is('gpm' . '/' . 'auditor') || Request::is('gpm' . '/' . 'auditor' . '/*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Auditor</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('hasil-audit-prodi') }}"
                            class="nav-link {{ Request::is('hasil-audit-prodi') || Request::is('hasil-audit-prodi') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-circle"></i>
                            <p>
                                Hasil Audit Prodi
                            </p>
                        </a>
                    </li>
                @endif
            @endforelse
        @endif
    </ul>
</nav>
