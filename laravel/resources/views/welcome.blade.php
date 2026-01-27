<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/daihatsu-logo.png') }}">
    <title>Monitoring Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <!-- Top Header -->
    <div class="top-header">
        <div class="logo-section">
            <img src="{{ asset('assets/images/daihatsu-logo.png') }}" alt="Daihatsu Logo" class="company-logo">
        </div>
        <div class="header-center">
            <div class="monitoring-title">
                <span class="monitoring-text">Monitoring Dashboard</span>
                <div class="monitoring-subtitle">Casting SMART Factory System</div>
            </div>
        </div>
        <div class="header-right">
            <div class="header-logos">
                <img src="{{ asset('assets/images/icare.png') }}" alt="I CARE" class="company-logo">
                <img src="{{ asset('assets/images/adm-unity.png') }}" alt="ADM Unity" class="company-logo">
            </div>
            <div class="datetime-display">
                <div class="date-text" id="current-date"></div>
                <div class="time-text" id="current-time"></div>
            </div>
        </div>
    </div>

    <!-- Dashboard Container -->
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="menu-section">
                <div class="menu-title">Main Menu</div>
                
                <div class="menu-item active">
                    <div class="menu-item-left">
                        <span>Dashboard</span>
                    </div>
                </div>

                <!-- AHPC Menu -->
                <div class="menu-item" onclick="toggleSubmenu('ahpc')">
                    <div class="menu-item-left">
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
                        <span>ALPC</span>
                    </div>
                    <span class="expand-icon" id="expand-alpc">▼</span>
                </div>
                <div class="submenu" id="submenu-alpc">
                    <!-- ALPC LINE 1 -->
                    <div class="nested-submenu-item" onclick="event.stopPropagation(); toggleSubmenu('alpc-line1')">
                        <span>ALPC LINE 1</span>
                        <span class="expand-icon" id="expand-alpc-line1">▼</span>
                    </div>
                    <div class="nested-submenu" id="submenu-alpc-line1">
                        <div class="nested-submenu-child">ALPC TR</div>
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
                    
                    <!-- ALPC LINE 2 -->
                    <div class="nested-submenu-item" onclick="event.stopPropagation(); toggleSubmenu('alpc-line2')">
                        <span>ALPC LINE 2</span>
                        <span class="expand-icon" id="expand-alpc-line2">▼</span>
                    </div>
                    <div class="nested-submenu" id="submenu-alpc-line2">
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
                            <span class="expand-icon" id="expand-alpc-wa">▼</span>
                        </div>
                        <div class="nested-submenu" id="submenu-alpc-wa">
                            <div class="nested-submenu-child">General ALPC WA</div>
                            <div class="nested-submenu-child" onclick="location.href='{{ route('casting-performance') }}'">Casting Performance</div>
                            <div class="nested-submenu-child">Finishing 1 Performance</div>
                            <div class="nested-submenu-child">Finishing 2 Performance</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="menu-section">
                <div class="menu-title">Others</div>
                
                <div class="menu-item" onclick="location.href='{{ route('traceability') }}'">
                    <div class="menu-item-left">
                        <span class="icon icon-trace"></span>
                        <span>Traceability</span>
                    </div>
                </div>

                <div class="menu-item">
                    <div class="menu-item-left">
                        <span class="icon icon-pe"></span>
                        <span>Trials & Dandori</span>
                    </div>
                </div>

                <div class="menu-item">
                    <div class="menu-item-left">
                        <span class="icon">📄</span>
                        <span>Changeover Analysis</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="content-header">
                <div class="page-title">Production Control System</div>
                <div class="filter-controls">
                    <button class="filter-btn active">Today</button>
                    <button class="filter-btn">This Week</button>
                    <button class="filter-btn">This Month</button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="cards-grid">
                <div class="stat-card">
                    <div class="stat-label">Overall OEE</div>
                    <div class="stat-value">84.2%</div>
                    <div class="stat-change positive">↑ 2.3% vs last week</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Availability</div>
                    <div class="stat-value">89.5%</div>
                    <div class="stat-change positive">↑ 1.8%</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Performance</div>
                    <div class="stat-value">91.2%</div>
                    <div class="stat-change negative">↓ 0.5%</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Quality</div>
                    <div class="stat-value">95.8%</div>
                    <div class="stat-change positive">↑ 1.2%</div>
                </div>
            </div>

            <!-- OEE Graph -->
            <div class="chart-container">
                <div class="chart-header">
                    <div class="chart-title">OEE Graph</div>
                    <div class="chart-tabs">
                        <div class="chart-tab active">Last 3 Month</div>
                        <div class="chart-tab">This Month</div>
                    </div>
                </div>
                <div class="chart-placeholder">
                    <div class="oee-chart-lines">
                        <div class="chart-line line-last-month"></div>
                        <div class="chart-line line-this-month"></div>
                    </div>
                    <span>OEE Trend Visualization</span>
                </div>
            </div>

            <!-- Additional Charts -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;">
                <div class="chart-container">
                    <div class="chart-header">
                        <div class="chart-title">Downtime Analysis</div>
                    </div>
                    <div class="chart-placeholder" style="height: 250px;">
                        <span>Downtime by Type</span>
                    </div>
                </div>

                <div class="chart-container">
                    <div class="chart-header">
                        <div class="chart-title">Defect Analysis</div>
                    </div>
                    <div class="chart-placeholder" style="height: 250px;">
                        <span>Quality Metrics</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        function toggleSubmenu(id) {
            const submenu = document.getElementById('submenu-' + id);
            const expandIcon = document.getElementById('expand-' + id);
            
            if (submenu.classList.contains('expanded')) {
                submenu.classList.remove('expanded');
                if (expandIcon) expandIcon.classList.remove('expanded');
            } else {
                submenu.classList.add('expanded');
                if (expandIcon) expandIcon.classList.add('expanded');
            }
        }
        
        function updateTime() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const dayName = days[now.getDay()];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const monthName = months[now.getMonth()];
            const day = String(now.getDate()).padStart(2, '0');
            const year = now.getFullYear();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            const dateDisplay = document.getElementById('current-date');
            const timeDisplay = document.getElementById('current-time');
            
            if (dateDisplay) dateDisplay.textContent = `${dayName}, ${day}-${monthName}-${year}`;
            if (timeDisplay) timeDisplay.textContent = `${hours}:${minutes}:${seconds}`;
        }
        
        updateTime();
        setInterval(updateTime, 1000);
    </script>
</body>
</html>
