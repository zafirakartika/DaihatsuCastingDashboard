/**
 * Trials & Dandori Monitoring System
 * Handles setup time efficiency, quality monitoring, and OEE tracking
 */

// Configuration
const CONFIG = {
    API_URL: '/daihatsu-dashboard/laravel/public/api/trials-dandori-data.php',
    REFRESH_INTERVAL: 60000, // 60 seconds
    CHARTS: {}
};

// State management
let currentData = [];
let currentLine = 'all';

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    loadTrialsData();
    setupEventListeners();

    // Auto-refresh data
    setInterval(loadTrialsData, CONFIG.REFRESH_INTERVAL);
});

/**
 * Setup event listeners
 */
function setupEventListeners() {
    // Line filter
    const lineFilter = document.getElementById('line-filter');
    if (lineFilter) {
        lineFilter.addEventListener('change', (e) => {
            currentLine = e.target.value;
            loadTrialsData();
        });
    }

    // Report period
    const reportPeriod = document.getElementById('report-period');
    if (reportPeriod) {
        reportPeriod.addEventListener('change', updateReportSummary);
    }

    // Table search
    const tableSearch = document.getElementById('table-search');
    if (tableSearch) {
        tableSearch.addEventListener('input', handleTableSearch);
    }
}

/**
 * Load trials data from API
 */
async function loadTrialsData() {
    try {
        const url = new URL(CONFIG.API_URL, window.location.origin);
        url.searchParams.append('line', currentLine);

        const response = await fetch(url);
        const result = await response.json();

        if (result.status === 'success' && result.data) {
            currentData = result.data;
            updateDashboard(result.data);
            updateKPIs(result.data);
            updateReportSummary();
        } else {
            showError('No data available');
        }
    } catch (error) {
        console.error('Error loading data:', error);
        showError('Error loading data');
    }
}

/**
 * Update all dashboard elements
 */
function updateDashboard(data) {
    updateSetupTimeChart(data);
    updateDowntimeChart(data);
    updateDefectRateChart(data);
    updateDefectTypeChart(data);
    updateTrialUnitsChart(data);
    updateOEEMetrics(data);
    updateQAStatus(data);
    updateTrialsTable(data);
}

/**
 * Update KPI cards
 */
function updateKPIs(data) {
    // Setup Time Efficiency
    const avgSetupTime = calculateAverage(data.map(d => d.setup_duration || 0));
    const targetSetupTime = 45; // minutes
    const efficiency = ((targetSetupTime / avgSetupTime) * 100).toFixed(1);
    document.getElementById('kpi-efficiency').textContent = efficiency + '%';

    // First Time Quality
    const qualityPass = data.filter(d => d.defect_rate === 0).length;
    const ftq = ((qualityPass / data.length) * 100).toFixed(1);
    document.getElementById('kpi-quality').textContent = ftq + '%';

    // Total Trial Units
    const totalUnits = data.reduce((sum, d) => sum + (d.trial_units || 0), 0);
    document.getElementById('kpi-units').textContent = totalUnits;

    // OEE
    const avgOEE = calculateAverage(data.map(d => d.oee || 0)).toFixed(1);
    document.getElementById('kpi-oee').textContent = avgOEE + '%';
}

/**
 * Initialize all charts
 */
function initializeCharts() {
    // Setup Time Chart (Gantt/Waterfall style)
    const setupTimeCtx = document.getElementById('setupTimeChart');
    if (setupTimeCtx) {
        CONFIG.CHARTS.setupTime = new Chart(setupTimeCtx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Waktu Set-up Aktual (min)',
                    data: [],
                    backgroundColor: '#667eea',
                }, {
                    label: 'Target Set-up (min)',
                    data: [],
                    backgroundColor: '#95a5a6',
                    type: 'line',
                    borderColor: '#e74c3c',
                    borderDash: [5, 5]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Duration (minutes)'
                        }
                    }
                },
                plugins: {
                    legend: { display: true },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.x + ' min';
                            }
                        }
                    }
                }
            }
        });
    }

    // Downtime Trend Chart
    const downtimeCtx = document.getElementById('downtimeChart');
    if (downtimeCtx) {
        CONFIG.CHARTS.downtime = new Chart(downtimeCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Durasi Downtime (min)',
                    data: [],
                    borderColor: '#e74c3c',
                    backgroundColor: 'rgba(231, 76, 60, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Minutes'
                        }
                    }
                }
            }
        });
    }

    // Defect Rate Control Chart
    const defectRateCtx = document.getElementById('defectRateChart');
    if (defectRateCtx) {
        CONFIG.CHARTS.defectRate = new Chart(defectRateCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Tingkat Cacat (%)',
                    data: [],
                    borderColor: '#f39c12',
                    backgroundColor: 'rgba(243, 156, 18, 0.1)',
                    pointBackgroundColor: '#f39c12',
                    pointRadius: 5,
                    tension: 0.1
                }, {
                    label: 'Upper Control Limit',
                    data: [],
                    borderColor: '#e74c3c',
                    borderDash: [10, 5],
                    pointRadius: 0,
                    fill: false
                }, {
                    label: 'Lower Control Limit',
                    data: [],
                    borderColor: '#27ae60',
                    borderDash: [10, 5],
                    pointRadius: 0,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Defect Rate (%)'
                        }
                    }
                }
            }
        });
    }

    // Defect Type Distribution (Pie Chart)
    const defectTypeCtx = document.getElementById('defectTypeChart');
    if (defectTypeCtx) {
        CONFIG.CHARTS.defectType = new Chart(defectTypeCtx, {
            type: 'pie',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: [
                        '#e74c3c',
                        '#f39c12',
                        '#3498db',
                        '#9b59b6',
                        '#1abc9c',
                        '#34495e'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // Trial Units Bar Chart
    const trialUnitsCtx = document.getElementById('trialUnitsChart');
    if (trialUnitsCtx) {
        CONFIG.CHARTS.trialUnits = new Chart(trialUnitsCtx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Unit Uji Coba',
                    data: [],
                    backgroundColor: '#667eea',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Units'
                        }
                    }
                }
            }
        });
    }
}

/**
 * Update Setup Time Chart
 */
function updateSetupTimeChart(data) {
    if (!CONFIG.CHARTS.setupTime) return;

    const latest = data.slice(-10); // Last 10 records
    const labels = latest.map(d => d.product_order || 'N/A');
    const actual = latest.map(d => d.setup_duration || 0);
    const target = latest.map(() => 45); // 45 min target

    CONFIG.CHARTS.setupTime.data.labels = labels;
    CONFIG.CHARTS.setupTime.data.datasets[0].data = actual;
    CONFIG.CHARTS.setupTime.data.datasets[1].data = target;
    CONFIG.CHARTS.setupTime.update();
}

/**
 * Update Downtime Trend Chart
 */
function updateDowntimeChart(data) {
    if (!CONFIG.CHARTS.downtime) return;

    const latest = data.slice(-20);
    const labels = latest.map((d, i) => `Record ${i + 1}`);
    const downtime = latest.map(d => d.downtime_duration || 0);

    CONFIG.CHARTS.downtime.data.labels = labels;
    CONFIG.CHARTS.downtime.data.datasets[0].data = downtime;
    CONFIG.CHARTS.downtime.update();
}

/**
 * Update Defect Rate Control Chart
 */
function updateDefectRateChart(data) {
    if (!CONFIG.CHARTS.defectRate) return;

    const latest = data.slice(-30);
    const labels = latest.map((d, i) => `Trial ${i + 1}`);
    const defectRates = latest.map(d => d.defect_rate || 0);

    // Calculate control limits
    const avgDefectRate = calculateAverage(defectRates);
    const ucl = Math.min(avgDefectRate + 10, 100);
    const lcl = Math.max(avgDefectRate - 10, 0);

    CONFIG.CHARTS.defectRate.data.labels = labels;
    CONFIG.CHARTS.defectRate.data.datasets[0].data = defectRates;
    CONFIG.CHARTS.defectRate.data.datasets[1].data = latest.map(() => ucl);
    CONFIG.CHARTS.defectRate.data.datasets[2].data = latest.map(() => lcl);
    CONFIG.CHARTS.defectRate.update();
}

/**
 * Update Defect Type Distribution Chart
 */
function updateDefectTypeChart(data) {
    if (!CONFIG.CHARTS.defectType) return;

    // Sample defect types distribution
    const defectTypes = {
        'Goresan': 0,
        'Dimensi Tidak Sesuai': 0,
        'Finishing': 0,
        'Berpori': 0,
        'Retak': 0,
        'Lainnya': 0
    };

    // Count defects by type (simulated)
    data.forEach(d => {
        if (d.defect_type) {
            defectTypes[d.defect_type] = (defectTypes[d.defect_type] || 0) + 1;
        }
    });

    const labels = Object.keys(defectTypes).filter(k => defectTypes[k] > 0);
    const values = labels.map(k => defectTypes[k]);

    CONFIG.CHARTS.defectType.data.labels = labels;
    CONFIG.CHARTS.defectType.data.datasets[0].data = values;
    CONFIG.CHARTS.defectType.update();
}

/**
 * Update Trial Units Bar Chart
 */
function updateTrialUnitsChart(data) {
    if (!CONFIG.CHARTS.trialUnits) return;

    const latest = data.slice(-10);
    const labels = latest.map(d => d.product_order || 'N/A');
    const units = latest.map(d => d.trial_units || 0);

    CONFIG.CHARTS.trialUnits.data.labels = labels;
    CONFIG.CHARTS.trialUnits.data.datasets[0].data = units;
    CONFIG.CHARTS.trialUnits.update();
}

/**
 * Update OEE Metrics
 */
function updateOEEMetrics(data) {
    if (!data.length) return;

    const latest = data[data.length - 1];
    const availability = latest.oee_availability || 85;
    const performance = latest.oee_performance || 92;
    const quality = latest.oee_quality || 95;
    const totalOEE = ((availability * performance * quality) / 10000).toFixed(1);

    document.getElementById('oee-availability').textContent = availability + '%';
    document.getElementById('oee-availability-bar').style.width = availability + '%';

    document.getElementById('oee-performance').textContent = performance + '%';
    document.getElementById('oee-performance-bar').style.width = performance + '%';

    document.getElementById('oee-quality').textContent = quality + '%';
    document.getElementById('oee-quality-bar').style.width = quality + '%';

    document.getElementById('oee-total').textContent = totalOEE + '%';
}

/**
 * Update QA Status
 */
function updateQAStatus(data) {
    const approved = data.filter(d => d.qa_status === 'Approved').length;
    const rejected = data.filter(d => d.qa_status === 'Rejected').length;
    const pending = data.filter(d => d.qa_status === 'Pending').length;

    document.getElementById('qa-approved').textContent = approved;
    document.getElementById('qa-rejected').textContent = rejected;
    document.getElementById('qa-pending').textContent = pending;

    // Update current status
    const latest = data[data.length - 1];
    const statusIndicator = document.getElementById('qa-status-indicator');
    const statusText = document.getElementById('qa-status-text');

    if (latest && latest.qa_status) {
        statusText.textContent = latest.qa_status;
        statusIndicator.className = 'status-indicator';

        if (latest.qa_status === 'Approved') {
            statusIndicator.classList.add('status-approved');
        } else if (latest.qa_status === 'Rejected') {
            statusIndicator.classList.add('status-rejected');
        } else {
            statusIndicator.classList.add('status-pending');
        }
    }
}

/**
 * Update Trials Table
 */
function updateTrialsTable(data) {
    const tbody = document.getElementById('trials-table-body');
    if (!tbody) return;

    if (!data.length) {
        tbody.innerHTML = '<tr><td colspan="8" class="loading-cell">No data available</td></tr>';
        return;
    }

    tbody.innerHTML = '';
    const latest = data.slice(-20).reverse(); // Last 20 records, newest first

    latest.forEach(record => {
        const row = document.createElement('tr');

        const qaStatusClass =
            record.qa_status === 'Approved' ? 'status-approved' :
            record.qa_status === 'Rejected' ? 'status-rejected' : 'status-pending';

        row.innerHTML = `
            <td>${record.timestamp || new Date().toLocaleString()}</td>
            <td>${record.machine_line || 'N/A'}</td>
            <td>${record.product_order || 'N/A'}</td>
            <td>${record.setup_duration || 0} min</td>
            <td>${record.trial_units || 0}</td>
            <td>${(record.defect_rate || 0).toFixed(1)}%</td>
            <td><span class="status-badge ${qaStatusClass}">${record.qa_status || 'Pending'}</span></td>
            <td><button class="action-btn" onclick="viewDetails(${record.id})">Details</button></td>
        `;

        tbody.appendChild(row);
    });
}

/**
 * Update Report Summary
 */
function updateReportSummary() {
    const period = document.getElementById('report-period')?.value || 'month';
    const filteredData = filterDataByPeriod(currentData, period);

    const totalTrials = filteredData.length;
    const avgSetup = calculateAverage(filteredData.map(d => d.setup_duration || 0)).toFixed(1);
    const successRate = ((filteredData.filter(d => d.qa_status === 'Approved').length / totalTrials) * 100).toFixed(1);

    document.getElementById('report-total-trials').textContent = totalTrials;
    document.getElementById('report-avg-setup').textContent = avgSetup + ' min';
    document.getElementById('report-success-rate').textContent = successRate + '%';
}

/**
 * Filter data by period
 */
function filterDataByPeriod(data, period) {
    const now = new Date();
    const filtered = data.filter(d => {
        if (!d.timestamp) return false;
        const date = new Date(d.timestamp);

        switch(period) {
            case 'today':
                return date.toDateString() === now.toDateString();
            case 'week':
                const weekAgo = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
                return date >= weekAgo;
            case 'month':
                return date.getMonth() === now.getMonth() && date.getFullYear() === now.getFullYear();
            case 'quarter':
                const quarter = Math.floor(now.getMonth() / 3);
                const dataQuarter = Math.floor(date.getMonth() / 3);
                return quarter === dataQuarter && date.getFullYear() === now.getFullYear();
            case 'year':
                return date.getFullYear() === now.getFullYear();
            default:
                return true;
        }
    });

    return filtered;
}

/**
 * Handle table search
 */
function handleTableSearch(event) {
    const searchTerm = event.target.value.toLowerCase();
    const rows = document.querySelectorAll('#trials-table-body tr');

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
}

/**
 * Calculate average
 */
function calculateAverage(arr) {
    if (!arr.length) return 0;
    return arr.reduce((a, b) => a + b, 0) / arr.length;
}

/**
 * Show error message
 */
function showError(message) {
    const tbody = document.getElementById('trials-table-body');
    if (tbody) {
        tbody.innerHTML = `<tr><td colspan="8" class="loading-cell">${message}</td></tr>`;
    }
}

/**
 * Generate Report
 */
function generateReport() {
    alert('Report generation feature - To be implemented');
    // TODO: Implement report generation
}

/**
 * Export Data
 */
function exportData() {
    alert('Data export feature - To be implemented');
    // TODO: Implement CSV/Excel export
}

/**
 * View Details
 */
function viewDetails(id) {
    alert(`View details for record ID: ${id} - To be implemented`);
    // TODO: Implement detail modal
}
