<!-- Sidebar -->
<div class="sidebar">
    <div class="menu-section">
        <div class="menu-title">Main Menu</div>

        <div class="menu-item <?php echo ($current_page == 'production-dashboard') ? 'active' : ''; ?>" onclick="location.href='<?php echo $base_url; ?>pages/production-dashboard.php'">
            <div class="menu-item-left">
                <img src="<?php echo $base_url; ?>assets/icons/dashboard.svg" class="icon" alt="Dashboard">
                <span>Dashboard</span>
            </div>
        </div>

        <!-- AHPC Menu -->
        <div class="menu-item" onclick="toggleSubmenu('ahpc')">
            <div class="menu-item-left">
                <img src="<?php echo $base_url; ?>assets/icons/factory.svg" class="icon" alt="AHPC">
                <span>AHPC</span>
            </div>
            <span class="expand-icon" id="expand-ahpc">▼</span>
        </div>
        <div class="submenu" id="submenu-ahpc">
            <div class="submenu-item">Casting Performance</div>
            <div class="submenu-item">Fin 1 Performance</div>
            <div class="submenu-item">Fin 2 Performance</div>
        </div>

        <!-- ALPC Menu -->
        <div class="menu-item" onclick="toggleSubmenu('alpc')">
            <div class="menu-item-left">
                <img src="<?php echo $base_url; ?>assets/icons/factory2.svg" class="icon" alt="ALPC">
                <span>ALPC</span>
            </div>
            <span class="expand-icon <?php echo in_array($current_page, ['lpc-counters', 'alpc-overview', 'general-alpc-wa', 'casting-performance-wa', 'finishing-wa', 'casting-performance-tr', 'general-alpc-tr', 'traceability-wa', 'traceability-tr']) ? 'expanded' : ''; ?>" id="expand-alpc">▼</span>
        </div>
        <div class="submenu <?php echo in_array($current_page, ['lpc-counters', 'alpc-overview', 'general-alpc-wa', 'casting-performance-wa', 'finishing-wa', 'casting-performance-tr', 'general-alpc-tr', 'traceability-wa', 'traceability-tr']) ? 'expanded' : ''; ?>" id="submenu-alpc">
            <!-- LPC Counters -->
            <div class="submenu-item <?php echo ($current_page == 'lpc-counters') ? 'active' : ''; ?>" onclick="location.href='<?php echo $base_url; ?>pages/lpc-counters.php'">
                <div class="menu-item-left">
                    <img src="<?php echo $base_url; ?>assets/icons/chart.svg" class="icon" alt="LPC Counters">
                    <span>LPC Counters</span>
                </div>
            </div>

            <!-- ALPC Overview -->
            <div class="submenu-item alpc-overview-item <?php echo ($current_page == 'alpc-overview') ? 'active' : ''; ?>" onclick="location.href='<?php echo $base_url; ?>pages/alpc-overview.php'">
                Overview
            </div>

            <!-- ALPC LINE 1 -->
            <div class="nested-submenu-item" onclick="event.stopPropagation(); toggleSubmenu('alpc-line1')">
                <span>ALPC LINE 1</span>
                <span class="expand-icon <?php echo in_array($current_page, ['casting-performance-tr', 'general-alpc-tr', 'traceability-tr']) ? 'expanded' : ''; ?>" id="expand-alpc-line1">▼</span>
            </div>
            <div class="nested-submenu <?php echo in_array($current_page, ['casting-performance-tr', 'general-alpc-tr', 'traceability-tr']) ? 'expanded' : ''; ?>" id="submenu-alpc-line1">
                <!-- ALPC TR dengan submenu -->
                <div class="nested-submenu-child clickable" onclick="event.stopPropagation(); toggleSubmenu('alpc-tr')">
                    <span>ALPC TR</span>
                    <span class="expand-icon <?php echo in_array($current_page, ['casting-performance-tr', 'general-alpc-tr', 'traceability-tr']) ? 'expanded' : ''; ?>" id="expand-alpc-tr">▼</span>
                </div>
                <div class="nested-submenu <?php echo in_array($current_page, ['casting-performance-tr', 'general-alpc-tr', 'traceability-tr']) ? 'expanded' : ''; ?>" id="submenu-alpc-tr">
                    <div class="nested-submenu-child <?php echo ($current_page == 'general-alpc-tr') ? 'active' : ''; ?>" onclick="location.href='<?php echo $base_url; ?>pages/general-alpc-tr.php'">General ALPC TR</div>
                    <div class="nested-submenu-child <?php echo ($current_page == 'casting-performance-tr') ? 'active' : ''; ?>" onclick="location.href='<?php echo $base_url; ?>pages/casting-performance-tr.php'">Casting Performance</div>
                    <div class="nested-submenu-child">Finishing 1 Performance</div>
                    <div class="nested-submenu-child">Finishing 2 Performance</div>
                </div>

                <!-- ALPC 3SZ dengan submenu -->
                <div class="nested-submenu-child clickable" onclick="event.stopPropagation(); toggleSubmenu('alpc-sz')">
                    <span>ALPC 3SZ</span>
                    <span class="expand-icon" id="expand-alpc-sz">▼</span>
                </div>
                <div class="nested-submenu" id="submenu-alpc-sz">
                    <div class="nested-submenu-child">General ALPC 3SZ</div>
                    <div class="nested-submenu-child">Casting Performance</div>
                    <div class="nested-submenu-child">Finishing 1 Performance</div>
                    <div class="nested-submenu-child">Finishing 2 Performance</div>
                </div>

                <!-- ALPC KR dengan submenu -->
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

            <!-- ALPC LINE 2 -->
            <div class="nested-submenu-item" onclick="event.stopPropagation(); toggleSubmenu('alpc-line2')">
                <span>ALPC LINE 2</span>
                <span class="expand-icon <?php echo in_array($current_page, ['general-alpc-wa', 'casting-performance-wa', 'finishing-wa', 'traceability-wa']) ? 'expanded' : ''; ?>" id="expand-alpc-line2">▼</span>
            </div>
            <div class="nested-submenu <?php echo in_array($current_page, ['general-alpc-wa', 'casting-performance-wa', 'finishing-wa', 'traceability-wa']) ? 'expanded' : ''; ?>" id="submenu-alpc-line2">
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
                <div class="nested-submenu-child clickable" onclick="event.stopPropagation(); toggleSubmenu('alpc-wa')">
                    <span>ALPC WA</span>
                    <span class="expand-icon <?php echo in_array($current_page, ['general-alpc-wa', 'casting-performance-wa', 'finishing-wa', 'traceability-wa']) ? 'expanded' : ''; ?>" id="expand-alpc-wa">▼</span>
                </div>
                <div class="nested-submenu <?php echo in_array($current_page, ['general-alpc-wa', 'casting-performance-wa', 'finishing-wa', 'traceability-wa']) ? 'expanded' : ''; ?>" id="submenu-alpc-wa">
                    <div class="nested-submenu-child <?php echo ($current_page == 'general-alpc-wa') ? 'active' : ''; ?>" onclick="location.href='<?php echo $base_url; ?>pages/general-alpc-wa.php'">General ALPC WA</div>
                    <div class="nested-submenu-child <?php echo ($current_page == 'casting-performance-wa') ? 'active' : ''; ?>" onclick="location.href='<?php echo $base_url; ?>pages/casting-performance-wa.php'">Casting Performance</div>
                    <div class="nested-submenu-child <?php echo ($current_page == 'finishing-wa') ? 'active' : ''; ?>" onclick="location.href='<?php echo $base_url; ?>pages/finishing-performance-wa.php'">Finishing 1 Performance</div>
                    <div class="nested-submenu-child">Finishing 2 Performance</div>
                </div>
            </div>
        </div>
    </div>

    <div class="menu-section">
        <div class="menu-title">Others</div>

        <div class="menu-item <?php echo (in_array($current_page, ['traceability', 'traceability-wa', 'traceability-tr', 'traceability-kr', 'traceability-nr', 'traceability-3sz'])) ? 'active' : ''; ?>" onclick="location.href='<?php echo $base_url; ?>pages/traceability.php'">
            <div class="menu-item-left">
                <img src="<?php echo $base_url; ?>assets/icons/list.svg" class="icon" alt="Traceability">
                <span>Traceability</span>
            </div>
        </div>

        <div class="menu-item <?php echo ($current_page == 'trials-dandori') ? 'active' : ''; ?>" onclick="location.href='<?php echo $base_url; ?>pages/trials-dandori.php'">
            <div class="menu-item-left">
                <img src="<?php echo $base_url; ?>assets/icons/thermometer.svg" class="icon" alt="Trials">
                <span>Trials-Dandori</span>
            </div>
        </div>

        <div class="menu-item">
            <div class="menu-item-left">
                <img src="<?php echo $base_url; ?>assets/icons/chart.svg" class="icon" alt="Analysis">
                <span>Changeover Analysis</span>
            </div>
        </div>
    </div>

    <!-- Exit/Back Button -->
    <div class="menu-section" style="margin-top: auto; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.1);">
        <div class="menu-item exit-button" onclick="location.href='<?php echo $base_url; ?>index.php'" style="background: rgba(231, 76, 60, 0.1); color: #e74c3c; font-weight: 600;">
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