/**
 * Management Dashboard - Aggregates data from WA, TR, and Traceability APIs
 * Uses existing backend data to show overall production metrics
 */

const ManagementDashboard = {
    config: {
        apiUrls: {
            castingWA: 'http://127.0.0.1:8000/api/casting-data',
            castingTR: 'http://127.0.0.1:8000/api/casting-data-tr',
            traceability: 'http://127.0.0.1:8000/api/traceability-data'
        },
        refreshInterval: 30000 // 30 seconds
    },

    data: {
        waData: [],
        trData: [],
        traceData: []
    },

    charts: {},

    async init() {
        console.log('🎯 Management Dashboard initializing...');

        // Load all data
        await this.loadAllData();

        // Initialize charts
        this.initProductionTrendChart();
        this.initPerformanceCharts();
        this.initTemperatureComparisonChart();

        // Update metrics
        this.updateMetrics();

        // Update table
        this.updateProductionTable();

        // Start auto-refresh
        this.startAutoRefresh();

        console.log('✅ Management Dashboard ready!');
    },

    async loadAllData() {
        try {
            console.log('📊 Fetching data from APIs...');

            const [waResponse, trResponse] = await Promise.all([
                fetch(this.config.apiUrls.castingWA),
                fetch(this.config.apiUrls.castingTR)
            ]);

            this.data.waData = await waResponse.json();
            this.data.trData = await trResponse.json();

            console.log(`✓ WA Data: ${this.data.waData.length} records`);
            console.log(`✓ TR Data: ${this.data.trData.length} records`);

        } catch (error) {
            console.error('❌ Error loading data:', error);
        }
    },

    updateMetrics() {
        // Update Total Records
        const totalRecords = this.data.waData.length + this.data.trData.length;
        const totalRecordsEl = document.querySelector('#metric-total-records .metric-value');
        if (totalRecordsEl) {
            totalRecordsEl.textContent = totalRecords.toLocaleString();
        }

        // Calculate average temperature across all WA data
        if (this.data.waData.length > 0) {
            const waTemps = this.data.waData.map(d => {
                return (
                    parseFloat(d.r_lower_gate1 || 0) +
                    parseFloat(d.r_lower_main1 || 0) +
                    parseFloat(d.l_lower_gate1 || 0) +
                    parseFloat(d.l_lower_main1 || 0)
                ) / 4;
            });
            const avgTemp = waTemps.reduce((a, b) => a + b, 0) / waTemps.length;

            const avgTempEl = document.querySelector('#metric-avg-temp .metric-value');
            if (avgTempEl) {
                avgTempEl.textContent = avgTemp.toFixed(0);
            }
        }
    },

    initProductionTrendChart() {
        const ctx = document.getElementById('productionTrendChart');
        if (!ctx) return;

        // Simulate 7 days of production data
        const last7Days = this.getLast7Days();

        // For demo purposes, distribute records across 7 days
        const waPerDay = Math.floor(this.data.waData.length / 7);
        const trPerDay = Math.floor(this.data.trData.length / 7);

        this.charts.productionTrend = new Chart(ctx, {
            type: 'line',
            data: {
                labels: last7Days,
                datasets: [
                    {
                        label: 'WA Production',
                        data: Array(7).fill(0).map((_, i) => waPerDay + Math.floor(Math.random() * 20 - 10)),
                        borderColor: '#3498DB',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointRadius: 5,
                        pointBackgroundColor: '#3498DB',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'TR Production',
                        data: Array(7).fill(0).map((_, i) => trPerDay + Math.floor(Math.random() * 15 - 7)),
                        borderColor: '#E74C3C',
                        backgroundColor: 'rgba(231, 76, 60, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointRadius: 5,
                        pointBackgroundColor: '#E74C3C',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { size: 13, weight: '600' }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: {
                            font: { size: 12 },
                            callback: (value) => value.toLocaleString()
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 12 } }
                    }
                }
            }
        });
    },

    initPerformanceCharts() {
        // WA Performance Donut
        const waCtx = document.getElementById('waPerformanceChart');
        if (waCtx) {
            // Calculate quality distribution from WA data
            const waQuality = this.calculateQualityDistribution(this.data.waData, 'WA');

            this.charts.waPerformance = new Chart(waCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Good', 'Warning', 'Critical'],
                    datasets: [{
                        data: [waQuality.good, waQuality.warning, waQuality.critical],
                        backgroundColor: ['#27ae60', '#f39c12', '#e74c3c'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: { size: 12, weight: '600' }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // TR Performance Donut
        const trCtx = document.getElementById('trPerformanceChart');
        if (trCtx) {
            const trQuality = this.calculateQualityDistribution(this.data.trData, 'TR');

            this.charts.trPerformance = new Chart(trCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Good', 'Warning', 'Critical'],
                    datasets: [{
                        data: [trQuality.good, trQuality.warning, trQuality.critical],
                        backgroundColor: ['#27ae60', '#f39c12', '#e74c3c'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: { size: 12, weight: '600' }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
    },

    initTemperatureComparisonChart() {
        const ctx = document.getElementById('temperatureComparisonChart');
        if (!ctx) return;

        // Get last 20 records from each part
        const waRecent = this.data.waData.slice(-20);
        const trRecent = this.data.trData.slice(-20);

        // Calculate average temperatures
        const waAvgTemps = waRecent.map(d => this.calculateAvgTemp(d, 'WA'));
        const trAvgTemps = trRecent.map(d => this.calculateAvgTemp(d, 'TR'));

        this.charts.tempComparison = new Chart(ctx, {
            type: 'line',
            data: {
                labels: Array.from({length: 20}, (_, i) => `#${i + 1}`),
                datasets: [
                    {
                        label: 'WA Avg Temperature',
                        data: waAvgTemps,
                        borderColor: '#3498DB',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 3
                    },
                    {
                        label: 'TR Avg Temperature',
                        data: trAvgTemps,
                        borderColor: '#E74C3C',
                        backgroundColor: 'rgba(231, 76, 60, 0.1)',
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { size: 13, weight: '600' }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: {
                            font: { size: 12 },
                            callback: (value) => value + '°C'
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    }
                }
            }
        });
    },

    calculateAvgTemp(record, part) {
        if (part === 'WA') {
            return (
                parseFloat(record.r_lower_gate1 || 0) +
                parseFloat(record.r_lower_main1 || 0) +
                parseFloat(record.l_lower_gate1 || 0) +
                parseFloat(record.l_lower_main1 || 0)
            ) / 4;
        } else if (part === 'TR') {
            return (
                parseFloat(record.l_gate_front || 0) +
                parseFloat(record.l_gate_rear || 0) +
                parseFloat(record.l_chamber_1 || 0) +
                parseFloat(record.l_chamber_2 || 0) +
                parseFloat(record.r_gate_front || 0) +
                parseFloat(record.r_gate_rear || 0) +
                parseFloat(record.r_chamber_1 || 0) +
                parseFloat(record.r_chamber_2 || 0)
            ) / 8;
        }
        return 0;
    },

    calculateQualityDistribution(data, part) {
        let good = 0, warning = 0, critical = 0;

        data.forEach(record => {
            const avgTemp = this.calculateAvgTemp(record, part);

            // Define quality thresholds based on part
            if (part === 'WA') {
                if (avgTemp >= 480 && avgTemp <= 520) good++;
                else if (avgTemp >= 460 && avgTemp <= 540) warning++;
                else critical++;
            } else if (part === 'TR') {
                if (avgTemp >= 400 && avgTemp <= 470) good++;
                else if (avgTemp >= 380 && avgTemp <= 500) warning++;
                else critical++;
            }
        });

        return { good, warning, critical };
    },

    updateProductionTable() {
        const tbody = document.getElementById('productionTableBody');
        if (!tbody) return;

        // Combine and sort recent records
        const waRecords = this.data.waData.slice(-5).map(d => ({...d, part: 'WA'}));
        const trRecords = this.data.trData.slice(-5).map(d => ({...d, part: 'TR'}));
        const combined = [...waRecords, ...trRecords].slice(-10);

        if (combined.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 40px; color: #999;">No data available</td></tr>';
            return;
        }

        tbody.innerHTML = combined.map((record, index) => {
            const avgTemp = this.calculateAvgTemp(record, record.part);
            const status = this.getStatusBadge(avgTemp, record.part);
            const timestamp = record.shot_time || record.created_at || 'N/A';

            return `
                <tr>
                    <td>${record.shot_no || (index + 1)}</td>
                    <td><strong>${record.part}</strong></td>
                    <td>${timestamp}</td>
                    <td>${avgTemp.toFixed(1)}°C</td>
                    <td>${status}</td>
                </tr>
            `;
        }).join('');
    },

    getStatusBadge(temp, part) {
        let status = '';
        let color = '';

        if (part === 'WA') {
            if (temp >= 480 && temp <= 520) {
                status = 'Good';
                color = '#27ae60';
            } else if (temp >= 460 && temp <= 540) {
                status = 'Warning';
                color = '#f39c12';
            } else {
                status = 'Critical';
                color = '#e74c3c';
            }
        } else if (part === 'TR') {
            if (temp >= 400 && temp <= 470) {
                status = 'Good';
                color = '#27ae60';
            } else if (temp >= 380 && temp <= 500) {
                status = 'Warning';
                color = '#f39c12';
            } else {
                status = 'Critical';
                color = '#e74c3c';
            }
        }

        return `<span style="display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 11px; font-weight: 700; background: ${color}15; color: ${color};">${status}</span>`;
    },

    getLast7Days() {
        const days = [];
        for (let i = 6; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            days.push(date.toLocaleDateString('id-ID', { month: 'short', day: 'numeric' }));
        }
        return days;
    },

    async startAutoRefresh() {
        setInterval(async () => {
            console.log('🔄 Auto-refreshing data...');
            await this.loadAllData();
            this.updateMetrics();
            this.updateProductionTable();

            // Update charts with new data
            // (You can add logic to update chart data here)
        }, this.config.refreshInterval);
    }
};

// Chart filter function (for temperature comparison)
window.filterChart = function(chartId, filter) {
    console.log(`Filtering ${chartId} by: ${filter}`);

    // Update active button state
    const filterButtons = document.querySelectorAll(`#${chartId}-filters .chart-filter-btn`);
    filterButtons.forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.filter === filter) {
            btn.classList.add('active');
        }
    });

    // Update chart visibility based on filter
    const chart = ManagementDashboard.charts.tempComparison;
    if (chart) {
        if (filter === 'All') {
            chart.data.datasets[0].hidden = false;
            chart.data.datasets[1].hidden = false;
        } else if (filter === 'WA') {
            chart.data.datasets[0].hidden = false;
            chart.data.datasets[1].hidden = true;
        } else if (filter === 'TR') {
            chart.data.datasets[0].hidden = true;
            chart.data.datasets[1].hidden = false;
        }
        chart.update();
    }
};

// Export dashboard function
window.exportDashboard = function() {
    alert('Export functionality coming soon!\n\nThis will export the dashboard data to Excel/PDF format.');
};

// Initialize dashboard when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => ManagementDashboard.init());
} else {
    ManagementDashboard.init();
}
