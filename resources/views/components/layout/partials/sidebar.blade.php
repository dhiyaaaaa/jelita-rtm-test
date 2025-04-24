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
            @forelse ($mainMenus as $mainMenu)
                @php
                    $isMainMenuActive = $mainMenu->menu->contains(function ($menu) {
                        return Request::is($menu->route . '*');
                    });
                @endphp
                
                <li class="nav-item has-treeview {{ $isMainMenuActive ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $isMainMenuActive ? 'active bg-info' : '' }}">
                        <i class="nav-icon fas fa-layer-group text-primary"></i>
                        <p class="font-weight-bold text-uppercase">
                            {{ $mainMenu->mainmenu }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
            
                    <ul class="nav nav-treeview">
                        @foreach ($mainMenu->menu as $menu)
                            @php
                                $menu_submenus = $menu->submenu;
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
                        @endforeach
                    </ul>
                </li>
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
            @endforelse
        @endif
    </ul>
</nav> 
