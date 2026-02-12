<div class="sidebar">
    <div class="menu-section">
        <div class="menu-title">Main Menu</div>

        <div class="menu-item {{ $current_page == 'production-dashboard' ? 'active' : '' }}" 
             onclick="location.href='{{ url('/production-dashboard') }}'">
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
            <span class="expand-icon {{ in_array($current_page, ['lpc-counters', 'alpc-overview']) ? 'expanded' : '' }}" id="expand-alpc">▼</span>
        </div>
        
        <div class="submenu {{ in_array($current_page, ['lpc-counters', 'alpc-overview']) ? 'expanded' : '' }}" id="submenu-alpc">
            <div class="submenu-item {{ $current_page == 'lpc-counters' ? 'active' : '' }}" 
                 onclick="location.href='{{ url('/lpc-counters') }}'">
                <div class="menu-item-left">
                    <img src="{{ asset('assets/icons/chart.svg') }}" class="icon" alt="LPC Counters">
                    <span>LPC Counters</span>
                </div>
            </div>

            <div class="submenu-item {{ $current_page == 'alpc-overview' ? 'active' : '' }}" 
                 onclick="location.href='{{ url('/alpc-overview') }}'">
                Overview
            </div>
            
            </div>
    </div>

    <div class="menu-section">
        <div class="menu-title">Others</div>
        <div class="menu-item {{ $current_page == 'traceability' ? 'active' : '' }}" 
             onclick="location.href='{{ url('/traceability') }}'">
            <div class="menu-item-left">
                <img src="{{ asset('assets/icons/list.svg') }}" class="icon" alt="Traceability">
                <span>Traceability</span>
            </div>
        </div>
    </div>

    <div class="menu-section" style="margin-top: auto; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.1);">
        <div class="menu-item exit-button" onclick="location.href='{{ url('/') }}'" style="background: rgba(231, 76, 60, 0.1); color: #e74c3c; font-weight: 600;">
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