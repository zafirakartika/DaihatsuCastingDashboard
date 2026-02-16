/**
 * Traceability WA Module
 * Handles Part ID traceability with breakdown analysis for WA parts
 * Version: 1.5.0 - Fixed API limit to show all 483 records
 * Uses shared utilities from traceability-shared.js
 */

// Configuration
const CONFIG = {
    API_URL: '/api/traceability?action=recent&line=wa'
};

// State management
let allData = [];
let filteredData = [];
let currentPage = 1;
let pageSize = 9999999; // Default to show all records
let currentSort = null; // Don't apply default sorting, use API order
let charts = {
    lpc: null,
    trend: null
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializePage();
    // Set initial page size based on dropdown value
    changePageSize();
    loadTraceabilityData();
    setupEventListeners();
});

/**
 * Initialize page elements
 */
function initializePage() {
    // Set today's date as default
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('filter-date-start').value = today;
    document.getElementById('filter-date-end').value = today;

    // Initialize charts
    initializeCharts();
}

/**
 * Setup event listeners
 */
function setupEventListeners() {
    // Search input
    const searchInput = document.getElementById('table-search');
    searchInput.addEventListener('input', debounce(handleSearch, 300));

    // Table header sorting
    document.querySelectorAll('.sortable').forEach(th => {
        th.addEventListener('click', () => handleSort(th.dataset.column));
    });
}

/**
 * Load traceability data from API
 */
async function loadTraceabilityData() {
    const tbody = document.getElementById('traceability-tbody');
    if (!tbody) return;

    tbody.innerHTML = '<tr><td colspan="9" class="loading-cell"><div class="loading-spinner"></div><div>Loading traceability data...</div></td></tr>';

    try {
        const dateStartElement = document.getElementById('filter-date-start');
        const limitElement = document.getElementById('filter-limit');
        const shiftElement = document.getElementById('filter-shift');
        const cavityElement = document.getElementById('filter-cavity');
        const pageSizeElement = document.getElementById('page-size');

        const dateStart = dateStartElement ? dateStartElement.value : '';
        const limitValue = limitElement ? limitElement.value : 'all';
        const shift = shiftElement ? shiftElement.value : 'all';
        const cavity = cavityElement ? cavityElement.value : 'all';
        const pageSizeValue = pageSizeElement ? pageSizeElement.value : 'all';

        // Convert "all" to a large number for the API
        const limit = limitValue === 'all' ? 999999 : limitValue;

        const url = new URL(CONFIG.API_URL, window.location.origin);
        url.searchParams.append('action', 'recent');
        url.searchParams.append('limit', limit);
        if (dateStart) {
            url.searchParams.append('date', dateStart);
        }

        const response = await fetch(url);
        const result = await response.json();

        if (result.status === 'success' && result.data && result.data.length > 0) {
            // Parse all records
            allData = result.data.map(record => {
                const parsed = parsePartId(record.id_part);
                return {
                    ...record,
                    ...parsed
                };
            });

            // Apply client-side filters (maintains API order)
            filteredData = applyFilters(allData, shift, cavity);

            // Update pageSize to show all records if "All" is selected
            const pageSizeValue = document.getElementById('page-size').value;
            if (pageSizeValue === 'all') {
                pageSize = filteredData.length;
            }

            renderTable();
            updateCharts();
            updateLastUpdateTime();
        } else {
            tbody.innerHTML = '<tr><td colspan="10" class="loading-cell">No data available for the selected filters</td></tr>';
            allData = [];
            filteredData = [];
        }
    } catch (error) {
        tbody.innerHTML = '<tr><td colspan="10" class="loading-cell">Error loading data. Please check your connection.</td></tr>';
    }
}

/**
 * Apply client-side filters
 */
function applyFilters(data, shift, cavity) {
    let filtered = [...data];

    // Filter by shift
    if (shift !== 'all') {
        // Shift filtering logic would go here based on shift code in part ID
        // For now, we'll pass through
    }

    // Filter by cavity
    if (cavity !== 'all') {
        filtered = filtered.filter(record => record.cavity === cavity);
    }

    return filtered;
}

/**
 * Render table with current filtered data
 */
function renderTable() {
    const tbody = document.getElementById('traceability-tbody');
    if (!tbody) return;

    const start = (currentPage - 1) * pageSize;
    const end = start + pageSize;
    const pageData = filteredData.slice(start, end);

    if (pageData.length === 0) {
        tbody.innerHTML = '<tr><td colspan="10" class="loading-cell">No records found</td></tr>';
        return;
    }

    tbody.innerHTML = '';

    pageData.forEach((record, index) => {
        const row = document.createElement('tr');

        // Generate timestamp if not available
        const timestamp = record.timestamp || '-';

        row.innerHTML = `
            <td style="text-align: center;">
                <button class="btn-view" onclick="showDetailModal(${start + index})" title="View Details">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </td>
            <td class="shot-cell">${record.shot || '-'}</td>
            <td class="id-part-cell" style="font-family: 'Courier New', monospace; font-weight: 600; color: #667eea;">${record.id_part || '-'}</td>
            <td class="segment-cell" style="text-align: center; font-family: 'Courier New', monospace; font-weight: 600;">${record.lpc || '-'}</td>
            <td class="segment-cell" style="text-align: center; font-family: 'Courier New', monospace;">${record.year || '-'}</td>
            <td class="segment-cell" style="text-align: center; font-family: 'Courier New', monospace;">${record.month || '-'}</td>
            <td class="segment-cell" style="text-align: center; font-family: 'Courier New', monospace;">${record.date || '-'}</td>
            <td class="segment-cell" style="text-align: center; font-family: 'Courier New', monospace;">${record.shift || '-'}</td>
            <td class="segment-cell" style="text-align: center; font-family: 'Courier New', monospace; font-weight: 600; font-size: 14px;">${record.cavity || '-'}</td>
            <td style="font-size: 11px; white-space: nowrap;">${timestamp}</td>
        `;

        tbody.appendChild(row);
    });

    updatePagination();
    updateRecordCount();
}

/**
 * Update pagination controls
 */
function updatePagination() {
    const totalPages = Math.ceil(filteredData.length / pageSize);
    updatePaginationControls(currentPage, totalPages);
}

/**
 * Update record count display
 */
function updateRecordCount() {
    updateRecordCountDisplay(currentPage, pageSize, filteredData.length);
}

/**
 * Update last update timestamp
 */
function updateLastUpdateTime() {
    updateLastUpdateTimeDisplay();
}

/**
 * Handle page change
 */
function changePage(action) {
    const totalPages = Math.ceil(filteredData.length / pageSize);

    switch(action) {
        case 'first':
            currentPage = 1;
            break;
        case 'prev':
            if (currentPage > 1) currentPage--;
            break;
        case 'next':
            if (currentPage < totalPages) currentPage++;
            break;
        case 'last':
            currentPage = totalPages;
            break;
    }

    renderTable();
}

/**
 * Change page size
 */
function changePageSize() {
    const pageSizeValue = document.getElementById('page-size').value;

    if (pageSizeValue === 'all') {
        // If data is already loaded, use its length; otherwise use a large number
        pageSize = filteredData.length > 0 ? filteredData.length : 9999999;
    } else {
        pageSize = parseInt(pageSizeValue);
    }

    currentPage = 1;

    // Only render if we have data
    if (filteredData.length > 0) {
        renderTable();
    }
}

/**
 * Handle search
 */
function handleSearch(event) {
    const searchTerm = event.target.value.toLowerCase();

    if (!searchTerm) {
        filteredData = [...allData];
    } else {
        filteredData = allData.filter(record => {
            return (
                (record.no_shot && record.no_shot.toString().includes(searchTerm)) ||
                (record.id_part && record.id_part.toLowerCase().includes(searchTerm)) ||
                (record.lpc && record.lpc.toLowerCase().includes(searchTerm)) ||
                (record.cavity && record.cavity.toLowerCase().includes(searchTerm)) ||
                (record.timestamp && record.timestamp.toLowerCase().includes(searchTerm))
            );
        });
    }

    currentPage = 1;
    renderTable();
    updateCharts();
}

/**
 * Handle column sorting
 */
function handleSort(column) {
    // Initialize currentSort if null
    if (!currentSort) {
        currentSort = { column: column, direction: 'desc' };
    } else if (currentSort.column === column) {
        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort.column = column;
        currentSort.direction = 'desc';
    }

    filteredData.sort((a, b) => {
        let aVal = a[column];
        let bVal = b[column];

        // Convert to numbers if numeric
        if (!isNaN(aVal) && !isNaN(bVal)) {
            aVal = parseFloat(aVal);
            bVal = parseFloat(bVal);
        }

        if (currentSort.direction === 'asc') {
            return aVal > bVal ? 1 : -1;
        } else {
            return aVal < bVal ? 1 : -1;
        }
    });

    renderTable();
}

/**
 * Reset filters
 */
function resetFilters() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('filter-date-start').value = today;
    document.getElementById('filter-date-end').value = today;
    document.getElementById('filter-shift').value = 'all';
    document.getElementById('filter-cavity').value = 'all';
    document.getElementById('filter-limit').value = 'all';
    document.getElementById('table-search').value = '';

    loadTraceabilityData();
}

/**
 * Initialize charts
 */
function initializeCharts() {
    // Check if Chart.js is available
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js is not loaded. Charts will not be displayed.');
        return;
    }

    // LPC Distribution Chart
    const lpcCtx = document.getElementById('lpcDistChart');
    if (lpcCtx) {
        charts.lpc = new Chart(lpcCtx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Parts Count',
                    data: [],
                    backgroundColor: [
                        '#667eea',
                        '#764ba2',
                        '#f093fb',
                        '#4facfe',
                        '#43e97b'
                    ],
                    borderWidth: 0,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Parts: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Production Trend Chart
    const trendCtx = document.getElementById('productionTrendChart');
    if (trendCtx) {
        charts.trend = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Parts Produced',
                    data: [],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: true }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
}

/**
 * Update charts with current data
 */
function updateCharts() {
    if (!filteredData.length) return;

    // Update LPC distribution (top 5 most produced LPCs)
    const lpcCounts = {};
    filteredData.forEach(r => {
        if (r.lpc) {
            lpcCounts[r.lpc] = (lpcCounts[r.lpc] || 0) + 1;
        }
    });

    // Sort by count and get top 5
    const sortedLPCs = Object.entries(lpcCounts)
        .sort((a, b) => b[1] - a[1])
        .slice(0, 5);

    const lpcLabels = sortedLPCs.map(([lpc]) => lpc);
    const lpcData = sortedLPCs.map(([, count]) => count);

    if (charts.lpc) {
        charts.lpc.data.labels = lpcLabels;
        charts.lpc.data.datasets[0].data = lpcData;
        charts.lpc.update();
    }

    // Update production trend (last 50 parts in batches of 10)
    const last50 = filteredData.slice(-50);
    const trendLabels = [];
    const trendData = [];

    for (let i = 0; i < last50.length; i += 10) {
        const batch = last50.slice(i, i + 10);
        trendLabels.push(`${i + 1}-${i + batch.length}`);
        trendData.push(batch.length);
    }

    if (charts.trend) {
        charts.trend.data.labels = trendLabels;
        charts.trend.data.datasets[0].data = trendData;
        charts.trend.update();
    }
}

/**
 * Export to CSV
 */
function exportToCSV() {
    const headers = ['Shot #', 'Part ID', 'LPC', 'Year', 'Month', 'Date', 'Shift', 'Cavity', 'Timestamp'];

    const rows = filteredData.map(record => [
        record.no_shot || '',
        record.id_part || '',
        record.lpc || '',
        record.year || '',
        record.month || '',
        record.date || '',
        record.shift || '',
        record.cavity || '',
        record.timestamp || ''
    ]);

    const csvContent = [headers, ...rows]
        .map(row => row.map(cell => `"${cell}"`).join(','))
        .join('\n');

    downloadFile(csvContent, 'traceability-wa.csv', 'text/csv');
}

/**
 * Export to Excel (CSV format compatible)
 */
function exportToExcel() {
    exportToCSV(); // For now, using CSV format
}

/**
 * Print report
 */
function printReport() {
    window.print();
}

/**
 * Download file helper
 */
function downloadFile(content, filename, mimeType) {
    const blob = new Blob([content], { type: mimeType });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = filename;
    link.click();
    URL.revokeObjectURL(url);
}

/**
 * Debounce utility
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Show detail modal
 */
function showDetailModal(index) {
    // Add array bounds checking
    if (index < 0 || index >= filteredData.length) {
        console.error('Index out of bounds:', index, 'Array length:', filteredData.length);
        return;
    }

    const record = filteredData[index];

    if (!record) {
        console.error('Record not found at index:', index);
        return;
    }

    // Update modal title with Part ID
    const modalTitle = document.getElementById('modal-title');
    if (modalTitle) {
        modalTitle.textContent = `154 ST-09 MAIN LINE POS 1 (WA LINE) - ${record.id_part || 'N/A'}`;
    }

    // Populate part details
    const detailIdPart = document.getElementById('detail-id-part');
    if (detailIdPart) detailIdPart.textContent = record.id_part || '-';

    const detailShot = document.getElementById('detail-shot');
    if (detailShot) detailShot.textContent = record.shot || '-';

    const detailLpc = document.getElementById('detail-lpc');
    if (detailLpc) detailLpc.textContent = record.lpc || '-';

    const detailShift = document.getElementById('detail-shift');
    if (detailShift) detailShift.textContent = record.shift || '-';

    // Populate new fields (show '-' if empty or null)
    const detailType = document.getElementById('detail-type');
    if (detailType) detailType.textContent = record.type || '-';

    const detailMc = document.getElementById('detail-mc');
    if (detailMc) detailMc.textContent = record.mc || '-';

    const detailCreateTimestamp = document.getElementById('detail-create-timestamp');
    if (detailCreateTimestamp) detailCreateTimestamp.textContent = record.create_timestamp || '-';

    // Set judge badge (for now, default to OK - this should come from API)
    const judgeElement = document.getElementById('detail-judge');
    if (judgeElement) {
        judgeElement.textContent = 'OK';
        judgeElement.className = 'judge-badge judge-ok';
    }

    // Handle part diagram image
    const partDiagram = document.getElementById('part-diagram');
    const imagePlaceholder = document.getElementById('image-placeholder');

    if (partDiagram && imagePlaceholder) {
        // Check if image exists, otherwise show placeholder
        partDiagram.onerror = function() {
            partDiagram.style.display = 'none';
            imagePlaceholder.classList.remove('hidden');
        };

        partDiagram.onload = function() {
            partDiagram.classList.add('loaded');
            imagePlaceholder.classList.add('hidden');
        };

        // Reset image state
        partDiagram.classList.remove('loaded');
        imagePlaceholder.classList.remove('hidden');
    }

    // Generate sample process data (in production, this should come from API)
    const processData = [
        { name: 'FINISHING 1', judge: 'OK' },
        { name: 'T6', judge: 'OK' },
        { name: 'FINISHING 2', judge: 'OK' }
    ];

    // Populate process table
    const processTableBody = document.getElementById('process-table-body');
    if (processTableBody) {
        processTableBody.innerHTML = '';

        processData.forEach(process => {
            const row = document.createElement('tr');
            const judgeClass = process.judge === 'OK' ? 'judge-ok' : 'judge-ng';

            row.innerHTML = `
                <td>${process.name}</td>
                <td><span class="judge-badge ${judgeClass}">${process.judge}</span></td>
            `;

            processTableBody.appendChild(row);
        });
    }

    // Show modal
    const detailModal = document.getElementById('detailModal');
    if (detailModal) {
        detailModal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }
}

/**
 * Close detail modal
 */
function closeDetailModal() {
    document.getElementById('detailModal').style.display = 'none';
    document.body.style.overflow = 'auto'; // Restore scrolling
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('detailModal');
    if (event.target === modal) {
        closeDetailModal();
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDetailModal();
    }
});
