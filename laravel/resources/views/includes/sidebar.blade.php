<div class="sidebar">
    <div class="menu-section">
        <div class="menu-title">Main Menu</div>

        <div class="menu-item {{ request()->routeIs('home') ? 'active' : '' }}" 
             onclick="location.href='{{ route('home') }}'">
            <div class="menu-item-left">
                <img src="{{ asset('assets/icons/dashboard.svg') }}" class="icon" alt="Dashboard">
                <span>Dashboard</span>
            </div>
        </div>

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

        <div class="menu-item" onclick="toggleSubmenu('alpc')">
            <div class="menu-item-left">
                <img src="{{ asset('assets/icons/factory2.svg') }}" class="icon" alt="ALPC">
                <span>ALPC</span>
            </div>
            <span class="expand-icon {{ request()->routeIs('casting-performance-*') || request()->routeIs('finishing-performance-*') || request()->routeIs('general-alpc-*') ? 'expanded' : '' }}" id="expand-alpc">▼</span>
        </div>
        
        <div class="submenu {{ request()->routeIs('casting-performance-*') || request()->routeIs('finishing-performance-*') || request()->routeIs('general-alpc-*') ? 'expanded' : '' }}" id="submenu-alpc">
            
            <div class="nested-submenu-item" onclick="event.stopPropagation(); toggleSubmenu('alpc-line1')">
                <span>ALPC LINE 1</span>
                <span class="expand-icon" id="expand-alpc-line1">▼</span>
            </div>
            <div class="nested-submenu" id="submenu-alpc-line1">
                <div class="nested-submenu-child clickable {{ request()->routeIs('general-alpc-tr') || request()->routeIs('casting-performance-tr') ? 'active-submenu' : '' }}" onclick="event.stopPropagation(); toggleSubmenu('alpc-tr')">
                    <span>ALPC TR</span>
                    <span class="expand-icon" id="expand-alpc-tr">▼</span>
                </div>
                <div class="nested-submenu {{ request()->routeIs('general-alpc-tr') || request()->routeIs('casting-performance-tr') ? 'expanded' : '' }}" id="submenu-alpc-tr">
                    <div class="nested-submenu-child {{ request()->routeIs('general-alpc-tr') ? 'active' : '' }}" onclick="location.href='{{ route('general-alpc-tr') }}'">General ALPC TR</div>
                    <div class="nested-submenu-child {{ request()->routeIs('casting-performance-tr') ? 'active' : '' }}" onclick="location.href='{{ route('casting-performance-tr') }}'">Casting Performance</div>
                </div>

                <div class="nested-submenu-child">ALPC 3SZ</div>
                
                <div class="nested-submenu-child clickable" onclick="event.stopPropagation(); toggleSubmenu('alpc-kr')">
                    <span>ALPC KR</span>
                    <span class="expand-icon" id="expand-alpc-kr">▼</span>
                </div>
                <div class="nested-submenu" id="submenu-alpc-kr">
                    <div class="nested-submenu-child">General ALPC KR</div>
                    <div class="nested-submenu-child">Casting Performance</div>
                    <div class="nested-submenu-child">Finishing 1 Performance</div>
                    <div class="nested-submenu-child">Finishing 2 Performance</div>
                </div>
            </div>

            <div class="nested-submenu-item" onclick="event.stopPropagation(); toggleSubmenu('alpc-line2')">
                <span>ALPC LINE 2</span>
                <span class="expand-icon {{ request()->routeIs('general-alpc-wa') || request()->routeIs('casting-performance-wa') || request()->routeIs('finishing-performance-wa') ? 'expanded' : '' }}" id="expand-alpc-line2">▼</span>
            </div>
            <div class="nested-submenu {{ request()->routeIs('general-alpc-wa') || request()->routeIs('casting-performance-wa') || request()->routeIs('finishing-performance-wa') ? 'expanded' : '' }}" id="submenu-alpc-line2">
                <div class="nested-submenu-child clickable" onclick="event.stopPropagation(); toggleSubmenu('alpc-nr')">
                    <span>ALPC NR</span>
                    <span class="expand-icon" id="expand-alpc-nr">▼</span>
                </div>
                <div class="nested-submenu" id="submenu-alpc-nr">
                    <div class="nested-submenu-child">General ALPC NR</div>
                    <div class="nested-submenu-child">Casting Performance</div>
                    <div class="nested-submenu-child">Finishing 1 Performance</div>
                    <div class="nested-submenu-child">Finishing 2 Performance</div>
                </div>

                <div class="nested-submenu-child clickable {{ request()->routeIs('general-alpc-wa') || request()->routeIs('casting-performance-wa') || request()->routeIs('finishing-performance-wa') ? 'active-submenu' : '' }}" onclick="event.stopPropagation(); toggleSubmenu('alpc-wa')">
                    <span>ALPC WA</span>
                    <span class="expand-icon {{ request()->routeIs('general-alpc-wa') || request()->routeIs('casting-performance-wa') || request()->routeIs('finishing-performance-wa') ? 'expanded' : '' }}" id="expand-alpc-wa">▼</span>
                </div>
                <div class="nested-submenu {{ request()->routeIs('general-alpc-wa') || request()->routeIs('casting-performance-wa') || request()->routeIs('finishing-performance-wa') ? 'expanded' : '' }}" id="submenu-alpc-wa">
                    <div class="nested-submenu-child {{ request()->routeIs('general-alpc-wa') ? 'active' : '' }}" onclick="location.href='{{ route('general-alpc-wa') }}'">General ALPC WA</div>
                    <div class="nested-submenu-child {{ request()->routeIs('casting-performance-wa') ? 'active' : '' }}" onclick="location.href='{{ route('casting-performance-wa') }}'">Casting Performance</div>
                    <div class="nested-submenu-child {{ request()->routeIs('finishing-performance-wa') ? 'active' : '' }}" onclick="location.href='{{ route('finishing-performance-wa') }}'">Finishing 1 Performance</div>
                    <div class="nested-submenu-child">Finishing 2 Performance</div>
                </div>
            </div>
        </div>
    </div>

    <div class="menu-section">
        <div class="menu-title">Others</div>
        <div class="menu-item {{ request()->routeIs('traceability') ? 'active' : '' }}" 
             onclick="location.href='{{ route('traceability') }}'">
            <div class="menu-item-left">
                <img src="{{ asset('assets/icons/list.svg') }}" class="icon" alt="Traceability">
                <span>Traceability</span>
            </div>
        </div>
    </div>

    <div class="menu-section" style="margin-top: auto; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.1);">
        <div class="menu-item exit-button" onclick="location.href='{{ route('home') }}'" style="background: rgba(231, 76, 60, 0.1); color: #e74c3c; font-weight: 600;">
            <div class="menu-item-left">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="filter: none; color: #e74c3c;">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <span>Exit</span>
            </div>
        </div>
    </div>
</div>