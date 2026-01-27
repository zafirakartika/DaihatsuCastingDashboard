<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/daihatsu-logo.png') }}">
    <title>Traceability - Casting SMART Factory</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <!-- Top Header  -->
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
                
                <div class="menu-item" onclick="location.href='{{ route('home') }}'">
                    <div class="menu-item-left"><span>Dashboard</span></div>
                </div>

                <div class="menu-item" onclick="toggleSubmenu('ahpc')">
                    <div class="menu-item-left"><span>AHPC</span></div>
                    <span class="expand-icon" id="expand-ahpc">▼</span>
                </div>
                <div class="submenu" id="submenu-ahpc">
                    <div class="submenu-item">Casting Performance</div>
                    <div class="submenu-item">Fin 1 Performance</div>
                    <div class="submenu-item">Fin 2 Performance</div>
                </div>

                <div class="menu-item" onclick="toggleSubmenu('alpc')">
                    <div class="menu-item-left"><span>ALPC</span></div>
                    <span class="expand-icon" id="expand-alpc">▼</span>
                </div>
                <div class="submenu" id="submenu-alpc">
                    <div class="nested-submenu-item" onclick="event.stopPropagation(); toggleSubmenu('alpc-line1')">
                        <span>ALPC LINE 1</span>
                        <span class="expand-icon" id="expand-alpc-line1">▼</span>
                    </div>
                    <div class="nested-submenu" id="submenu-alpc-line1">
                        <div class="nested-submenu-child">ALPC TR</div>
                        <div class="nested-submenu-child">ALPC 3SZ</div>
                        <div class="nested-submenu-child">ALPC KR</div>
                    </div>
                    <div class="nested-submenu-item" onclick="event.stopPropagation(); toggleSubmenu('alpc-line2')">
                        <span>ALPC LINE 2</span>
                        <span class="expand-icon" id="expand-alpc-line2">▼</span>
                    </div>
                    <div class="nested-submenu" id="submenu-alpc-line2">
                        <div class="nested-submenu-child">ALPC NR</div>
                        <div class="nested-submenu-child">ALPC WA</div>
                    </div>
                </div>
            </div>

            <div class="menu-section">
                <div class="menu-title">Others</div>
                <div class="menu-item active">
                    <div class="menu-item-left"><span>🔍</span><span>Traceability</span></div>
                </div>
                <div class="menu-item">
                    <div class="menu-item-left"><span>⚙️</span><span>Trials & Dandori</span></div>
                </div>
                <div class="menu-item">
                    <div class="menu-item-left"><span>🔄</span><span>Changeover Analysis</span></div>
                </div>
            </div>
        </div>

        <!-- Main Content - Traceability -->
        <div class="trace-content">
            <div class="page-title">
                <h1>Traceability System</h1>
            </div>

            <div class="flow-container" id="partContainer"></div>

            <div class="instruction">
                <span class="instruction-icon">👆</span>
                <span class="instruction-text">Click to view detailed traceability data</span>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal-overlay" id="modal">
        <div class="modal">
            <div class="modal-icon" id="modalIcon">⚙️</div>
            <h2 id="modalTitle">Part KR</h2>
            <p id="modalDesc">View traceability data for KR process.</p>
            <div class="modal-actions">
                <button class="modal-btn secondary" onclick="closeModal()">Cancel</button>
                <button class="modal-btn primary" onclick="navigateToPart()">View Traceability →</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        // Fungsi toggle submenu
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
        
        // Update waktu dan tanggal
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
