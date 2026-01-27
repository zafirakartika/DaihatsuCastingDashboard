/**
 * Daihatsu Production Dashboard - Main JavaScript
 */

// Update Time and Date
function updateDateTime() {
    const now = new Date();
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    const dateEl = document.getElementById('current-date');
    const timeEl = document.getElementById('current-time');

    if (dateEl) {
        const formattedDate = `${days[now.getDay()]}, ${String(now.getDate()).padStart(2, '0')}-${months[now.getMonth()]}-${now.getFullYear()}`;
        dateEl.textContent = formattedDate;
    }

    if (timeEl) {
        const formattedTime = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}:${String(now.getSeconds()).padStart(2, '0')}`;
        timeEl.textContent = formattedTime;
    }
}

// Toggle Sidebar with Hamburger Menu
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const hamburger = document.querySelector('.hamburger-menu');
    const body = document.body;

    if (sidebar) {
        // Check if we're on mobile (screen width <= 768px)
        const isMobile = window.innerWidth <= 768;

        if (isMobile) {
            // Mobile: Overlay behavior
            sidebar.classList.toggle('mobile-open');

            // Prevent body scroll when sidebar is open
            if (sidebar.classList.contains('mobile-open')) {
                body.style.overflow = 'hidden';
            } else {
                body.style.overflow = '';
            }
        } else {
            // Desktop: Hide/show behavior
            sidebar.classList.toggle('hidden');

            // Save state to localStorage
            const isHidden = sidebar.classList.contains('hidden');
            localStorage.setItem('sidebarHidden', isHidden);
        }

        // Toggle hamburger animation
        if (hamburger) {
            hamburger.classList.toggle('active');
        }
    }
}

// Close mobile sidebar when clicking outside
function closeMobileSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const body = document.body;

    if (sidebar && sidebar.classList.contains('mobile-open')) {
        sidebar.classList.remove('mobile-open');
        body.style.overflow = '';

        const hamburger = document.querySelector('.hamburger-menu');
        if (hamburger) {
            hamburger.classList.remove('active');
        }
    }
}

// Handle window resize for responsive behavior
function handleResize() {
    const sidebar = document.querySelector('.sidebar');
    const body = document.body;

    if (sidebar) {
        // If resizing to desktop while mobile sidebar is open, close it
        if (window.innerWidth > 768 && sidebar.classList.contains('mobile-open')) {
            sidebar.classList.remove('mobile-open');
            body.style.overflow = '';

            const hamburger = document.querySelector('.hamburger-menu');
            if (hamburger) {
                hamburger.classList.remove('active');
            }
        }

        // If resizing to mobile while desktop sidebar is hidden, show it
        if (window.innerWidth <= 768 && sidebar.classList.contains('hidden')) {
            sidebar.classList.remove('hidden');
        }
    }
}

// Toggle Submenu
function toggleSubmenu(id) {
    const submenu = document.getElementById('submenu-' + id);
    const icon = document.getElementById('expand-' + id);

    if (submenu && icon) {
        submenu.classList.toggle('expanded');
        icon.classList.toggle('expanded');
    }
}

// Part Data Configuration
const parts = [
    { id: 'kr', name: 'KR', color: '#3498DB', icon: '⚙️' },
    { id: 'tr', name: 'TR', color: '#3498DB', icon: '⚙️' },
    { id: '3sz', name: '3SZ', color: '#3498DB', icon: '⚙️' },
    { id: 'nr', name: 'NR', color: '#3498DB', icon: '⚙️' },
    { id: 'wa', name: 'WA', color: '#3498DB', icon: '⚙️' }
];

let selectedPart = null;

// Generate Parts Dynamically
function renderParts() {
    const container = document.getElementById('partContainer');
    if (!container) return;

    const partsHTML = parts.map((part, i) => {
        const partCard = `
            <div class="part-card part-${part.id}" onclick="selectPart('${part.id}')">
                <span class="part-icon">${part.icon}</span>
                <span class="part-name">${part.name}</span>
            </div>
        `;
        const connector = i < parts.length - 1 ? '<div class="connector"></div>' : '';
        return partCard + connector;
    }).join('');

    container.innerHTML = partsHTML;
}

// Instant Navigation - No Modal
function selectPart(id) {
    selectedPart = parts.find(p => p.id === id);
    if (!selectedPart) return;

    // Redirect instantly without showing modal
    const url = `pages/traceability-${selectedPart.id}.php?v=1`;
    window.location.href = url;
}

// Event Listeners Setup
function setupEventListeners() {
    // Filter buttons
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Chart tabs
    const chartTabs = document.querySelectorAll('.chart-tab');
    chartTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const parent = this.parentElement;
            const siblingTabs = parent.querySelectorAll('.chart-tab');
            siblingTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
}

// Restore Sidebar State
function restoreSidebarState() {
    const sidebar = document.querySelector('.sidebar');
    const hamburger = document.querySelector('.hamburger-menu');

    if (sidebar) {
        const isHidden = localStorage.getItem('sidebarHidden') === 'true';
        if (isHidden) {
            sidebar.classList.add('hidden');
            if (hamburger) {
                hamburger.classList.add('active');
            }
        }
    }
}

// Initialize on DOM Ready
function init() {
    updateDateTime();
    setInterval(updateDateTime, 1000);
    renderParts();
    setupEventListeners();
    restoreSidebarState();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}