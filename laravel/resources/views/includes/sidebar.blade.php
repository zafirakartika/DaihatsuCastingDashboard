<div class="sidebar">
    <div class="menu-section">
        <div class="menu-title">Main Menu</div>

        {{-- Dashboard --}}
        <div class="menu-item {{ request()->routeIs('production-dashboard') ? 'active' : '' }}"
             onclick="location.href='{{ route('production-dashboard') }}'">
            <div class="menu-item-left">
                <img src="{{ asset('assets/icons/dashboard.svg') }}" class="icon" alt="Dashboard">
                <span>Dashboard</span>
            </div>
        </div>

        {{-- AHPC --}}
        <div class="menu-item" onclick="toggleSubmenu('ahpc')">
            <div class="menu-item-left">
                <img src="{{ asset('assets/icons/factory.svg') }}" class="icon" alt="AHPC">
                <span>AHPC</span>
            </div>
            <span class="expand-icon" id="expand-ahpc">▼</span>
        </div>
        <div class="submenu" id="submenu-ahpc">
            <div class="submenu-item">Casting Performance</div>
            <div class="submenu-item">Fin 1 Performance</div>
            <div class="submenu-item">Fin 2 Performance</div>
        </div>

        {{-- ALPC --}}
        @php
            $alpcActive = request()->routeIs(
                'alpc-overview','lpc-counters',
                'general-alpc-tr','casting-performance-tr','finishing-performance-tr',
                'general-alpc-3sz','casting-performance-3sz',
                'general-alpc-kr','casting-performance-kr','finishing-performance-kr',
                'general-alpc-nr','casting-performance-nr','finishing-performance-nr',
                'general-alpc-wa','casting-performance-wa','finishing-performance-wa'
            );
            $line1Active = request()->routeIs(
                'general-alpc-tr','casting-performance-tr','finishing-performance-tr',
                'general-alpc-3sz','casting-performance-3sz',
                'general-alpc-kr','casting-performance-kr','finishing-performance-kr'
            );
            $trActive    = request()->routeIs('general-alpc-tr','casting-performance-tr','finishing-performance-tr');
            $szActive    = request()->routeIs('general-alpc-3sz','casting-performance-3sz');
            $krActive    = request()->routeIs('general-alpc-kr','casting-performance-kr','finishing-performance-kr');
            $line2Active = request()->routeIs(
                'general-alpc-nr','casting-performance-nr','finishing-performance-nr',
                'general-alpc-wa','casting-performance-wa','finishing-performance-wa'
            );
            $nrActive    = request()->routeIs('general-alpc-nr','casting-performance-nr','finishing-performance-nr');
            $waActive    = request()->routeIs('general-alpc-wa','casting-performance-wa','finishing-performance-wa');
        @endphp

        <div class="menu-item" onclick="toggleSubmenu('alpc')">
            <div class="menu-item-left">
                <img src="{{ asset('assets/icons/factory2.svg') }}" class="icon" alt="ALPC">
                <span>ALPC</span>
            </div>
            <span class="expand-icon {{ $alpcActive ? 'expanded' : '' }}" id="expand-alpc">▼</span>
        </div>
        <div class="submenu {{ $alpcActive ? 'expanded' : '' }}" id="submenu-alpc">

            {{-- Overview --}}
            <div class="submenu-item {{ request()->routeIs('alpc-overview') ? 'active' : '' }}"
                 onclick="location.href='{{ route('alpc-overview') }}'">Overview</div>

            {{-- LPC Counters --}}
            <div class="submenu-item {{ request()->routeIs('lpc-counters') ? 'active' : '' }}"
                 onclick="location.href='{{ route('lpc-counters') }}'">LPC Counters</div>

            {{-- LINE 1 --}}
            <div class="nested-submenu-item" onclick="event.stopPropagation(); toggleSubmenu('alpc-line1')">
                <span>ALPC LINE 1</span>
                <span class="expand-icon {{ $line1Active ? 'expanded' : '' }}" id="expand-alpc-line1">▼</span>
            </div>
            <div class="nested-submenu {{ $line1Active ? 'expanded' : '' }}" id="submenu-alpc-line1">

                {{-- TR --}}
                <div class="nested-submenu-child clickable {{ $trActive ? 'active' : '' }}"
                     onclick="event.stopPropagation(); toggleSubmenu('alpc-tr')">
                    <span>ALPC TR</span>
                    <span class="expand-icon {{ $trActive ? 'expanded' : '' }}" id="expand-alpc-tr">▼</span>
                </div>
                <div class="nested-submenu {{ $trActive ? 'expanded' : '' }}" id="submenu-alpc-tr">
                    <div class="nested-submenu-child {{ request()->routeIs('general-alpc-tr') ? 'active' : '' }}"
                         onclick="location.href='{{ route('general-alpc-tr') }}'">General ALPC TR</div>
                    <div class="nested-submenu-child {{ request()->routeIs('casting-performance-tr') ? 'active' : '' }}"
                         onclick="location.href='{{ route('casting-performance-tr') }}'">Casting Performance</div>
                    <div class="nested-submenu-child {{ request()->routeIs('finishing-performance-tr') ? 'active' : '' }}"
                         onclick="location.href='{{ route('finishing-performance-tr') }}'">Finishing Performance</div>
                </div>

                {{-- 3SZ --}}
                <div class="nested-submenu-child clickable {{ $szActive ? 'active' : '' }}"
                     onclick="event.stopPropagation(); toggleSubmenu('alpc-3sz')">
                    <span>ALPC 3SZ</span>
                    <span class="expand-icon {{ $szActive ? 'expanded' : '' }}" id="expand-alpc-3sz">▼</span>
                </div>
                <div class="nested-submenu {{ $szActive ? 'expanded' : '' }}" id="submenu-alpc-3sz">
                    <div class="nested-submenu-child {{ request()->routeIs('general-alpc-3sz') ? 'active' : '' }}"
                         onclick="location.href='{{ route('general-alpc-3sz') }}'">General ALPC 3SZ</div>
                    <div class="nested-submenu-child {{ request()->routeIs('casting-performance-3sz') ? 'active' : '' }}"
                         onclick="location.href='{{ route('casting-performance-3sz') }}'">Casting Performance</div>
                </div>

                {{-- KR --}}
                <div class="nested-submenu-child clickable {{ $krActive ? 'active' : '' }}"
                     onclick="event.stopPropagation(); toggleSubmenu('alpc-kr')">
                    <span>ALPC KR</span>
                    <span class="expand-icon {{ $krActive ? 'expanded' : '' }}" id="expand-alpc-kr">▼</span>
                </div>
                <div class="nested-submenu {{ $krActive ? 'expanded' : '' }}" id="submenu-alpc-kr">
                    <div class="nested-submenu-child {{ request()->routeIs('general-alpc-kr') ? 'active' : '' }}"
                         onclick="location.href='{{ route('general-alpc-kr') }}'">General ALPC KR</div>
                    <div class="nested-submenu-child {{ request()->routeIs('casting-performance-kr') ? 'active' : '' }}"
                         onclick="location.href='{{ route('casting-performance-kr') }}'">Casting Performance</div>
                    <div class="nested-submenu-child {{ request()->routeIs('finishing-performance-kr') ? 'active' : '' }}"
                         onclick="location.href='{{ route('finishing-performance-kr') }}'">Finishing Performance</div>
                </div>

            </div>{{-- end submenu-alpc-line1 --}}

            {{-- LINE 2 --}}
            <div class="nested-submenu-item" onclick="event.stopPropagation(); toggleSubmenu('alpc-line2')">
                <span>ALPC LINE 2</span>
                <span class="expand-icon {{ $line2Active ? 'expanded' : '' }}" id="expand-alpc-line2">▼</span>
            </div>
            <div class="nested-submenu {{ $line2Active ? 'expanded' : '' }}" id="submenu-alpc-line2">

                {{-- NR --}}
                <div class="nested-submenu-child clickable {{ $nrActive ? 'active' : '' }}"
                     onclick="event.stopPropagation(); toggleSubmenu('alpc-nr')">
                    <span>ALPC NR</span>
                    <span class="expand-icon {{ $nrActive ? 'expanded' : '' }}" id="expand-alpc-nr">▼</span>
                </div>
                <div class="nested-submenu {{ $nrActive ? 'expanded' : '' }}" id="submenu-alpc-nr">
                    <div class="nested-submenu-child {{ request()->routeIs('general-alpc-nr') ? 'active' : '' }}"
                         onclick="location.href='{{ route('general-alpc-nr') }}'">General ALPC NR</div>
                    <div class="nested-submenu-child {{ request()->routeIs('casting-performance-nr') ? 'active' : '' }}"
                         onclick="location.href='{{ route('casting-performance-nr') }}'">Casting Performance</div>
                    <div class="nested-submenu-child {{ request()->routeIs('finishing-performance-nr') ? 'active' : '' }}"
                         onclick="location.href='{{ route('finishing-performance-nr') }}'">Finishing Performance</div>
                </div>

                {{-- WA --}}
                <div class="nested-submenu-child clickable {{ $waActive ? 'active' : '' }}"
                     onclick="event.stopPropagation(); toggleSubmenu('alpc-wa')">
                    <span>ALPC WA</span>
                    <span class="expand-icon {{ $waActive ? 'expanded' : '' }}" id="expand-alpc-wa">▼</span>
                </div>
                <div class="nested-submenu {{ $waActive ? 'expanded' : '' }}" id="submenu-alpc-wa">
                    <div class="nested-submenu-child {{ request()->routeIs('general-alpc-wa') ? 'active' : '' }}"
                         onclick="location.href='{{ route('general-alpc-wa') }}'">General ALPC WA</div>
                    <div class="nested-submenu-child {{ request()->routeIs('casting-performance-wa') ? 'active' : '' }}"
                         onclick="location.href='{{ route('casting-performance-wa') }}'">Casting Performance</div>
                    <div class="nested-submenu-child {{ request()->routeIs('finishing-performance-wa') ? 'active' : '' }}"
                         onclick="location.href='{{ route('finishing-performance-wa') }}'">Finishing Performance</div>
                </div>

            </div>{{-- end submenu-alpc-line2 --}}

        </div>{{-- end submenu-alpc --}}

    </div>{{-- end Main Menu section --}}

    <div class="menu-section">
        <div class="menu-title">Others</div>

        {{-- Traceability --}}
        <div class="menu-item {{ request()->routeIs('traceability','traceability-wa','traceability-tr','traceability-kr','traceability-nr','traceability-3sz') ? 'active' : '' }}"
             onclick="location.href='{{ route('traceability') }}'">
            <div class="menu-item-left">
                <img src="{{ asset('assets/icons/list.svg') }}" class="icon" alt="Traceability">
                <span>Traceability</span>
            </div>
        </div>

    </div>{{-- end Others section --}}

    <div class="menu-section" style="margin-top: auto; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.1);">
        <div class="menu-item exit-button" onclick="location.href='{{ route('home') }}'"
             style="background: rgba(231, 76, 60, 0.1); color: #e74c3c; font-weight: 600;">
            <div class="menu-item-left">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     style="filter: none; color: #e74c3c;">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <span>Exit</span>
            </div>
        </div>
    </div>

</div>
