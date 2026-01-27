/**
 * Maintenance Dashboard - Energy Consumption & Resource Monitoring
 * Simulated data for electricity, gas, water, and compressed air
 */

const MaintenanceDashboard = {
    config: {
        refreshInterval: 30000 // 30 seconds
    },

    charts: {},

    init() {
        console.log('🔧 Maintenance Dashboard initializing...');

        // Initialize charts
        this.initEnergyTrendChart();
        this.initEnergyDistributionChart();
        this.initCostAnalysisChart();
        this.initHourlyPatternChart();

        // Update metrics with simulated data
        this.updateMetrics();

        // Update table
        this.updateResourceTable();

        // Start auto-refresh
        this.startAutoRefresh();

        console.log('✅ Maintenance Dashboard ready!');
    },

    initEnergyTrendChart() {
        const ctx = document.getElementById('energyTrendChart');
        if (!ctx) return;

        const last7Days = this.getLast7Days();

        this.charts.energyTrend = new Chart(ctx, {
            type: 'line',
            data: {
                labels: last7Days,
                datasets: [
                    {
                        label: 'Electricity (kWh)',
                        data: [3650, 3720, 3580, 3490, 3620, 3560, 3542],
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointRadius: 5,
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'Gas (m³)',
                        data: [890, 865, 872, 855, 848, 850, 847],
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointRadius: 5,
                        pointBackgroundColor: '#ef4444',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'Water (L)',
                        data: [1180, 1205, 1192, 1215, 1230, 1238, 1245],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointRadius: 5,
                        pointBackgroundColor: '#3b82f6',
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

    initEnergyDistributionChart() {
        const ctx = document.getElementById('energyDistributionChart');
        if (!ctx) return;

        this.charts.energyDistribution = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Electricity', 'Gas', 'Water', 'Compressed Air'],
                datasets: [{
                    data: [45, 20, 15, 20],
                    backgroundColor: ['#f59e0b', '#ef4444', '#3b82f6', '#8b5cf6'],
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
                                return `${label}: ${value}%`;
                            }
                        }
                    }
                }
            }
        });
    },

    initCostAnalysisChart() {
        const ctx = document.getElementById('costAnalysisChart');
        if (!ctx) return;

        this.charts.costAnalysis = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Monthly Cost (Million IDR)',
                    data: [245, 238, 242, 235, 230, 225],
                    backgroundColor: [
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(16, 185, 129, 0.8)'
                    ],
                    borderColor: [
                        '#ef4444',
                        '#ef4444',
                        '#ef4444',
                        '#10b981',
                        '#10b981',
                        '#10b981'
                    ],
                    borderWidth: 2,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                return `Cost: Rp ${context.parsed.y}M`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: {
                            font: { size: 11 },
                            callback: (value) => `Rp ${value}M`
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

    initHourlyPatternChart() {
        const ctx = document.getElementById('hourlyPatternChart');
        if (!ctx) return;

        const hours = Array.from({length: 24}, (_, i) => `${String(i).padStart(2, '0')}:00`);

        // Simulate hourly pattern (higher during work hours 7-17)
        const electricityData = hours.map((_, i) => {
            if (i >= 7 && i <= 17) return 150 + Math.random() * 50;
            return 50 + Math.random() * 30;
        });

        const gasData = hours.map((_, i) => {
            if (i >= 7 && i <= 17) return 35 + Math.random() * 10;
            return 10 + Math.random() * 5;
        });

        const waterData = hours.map((_, i) => {
            if (i >= 7 && i <= 17) return 50 + Math.random() * 15;
            return 15 + Math.random() * 8;
        });

        this.charts.hourlyPattern = new Chart(ctx, {
            type: 'line',
            data: {
                labels: hours,
                datasets: [
                    {
                        label: 'Electricity (kWh)',
                        data: electricityData,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 2
                    },
                    {
                        label: 'Gas (m³)',
                        data: gasData,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 2
                    },
                    {
                        label: 'Water (L)',
                        data: waterData,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 2
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
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: { font: { size: 11 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 10 },
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
    },

    updateMetrics() {
        // Metrics are already set in PHP, but can be updated here with real API data
        console.log('✓ Metrics displayed');
    },

    updateResourceTable() {
        const tbody = document.getElementById('resourceTableBody');
        if (!tbody) return;

        // Generate last 10 hourly readings
        const tableData = [];
        const now = new Date();

        for (let i = 9; i >= 0; i--) {
            const time = new Date(now.getTime() - i * 60 * 60 * 1000);
            const hour = time.getHours();
            const isWorkHour = hour >= 7 && hour <= 17;

            tableData.push({
                time: time.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }),
                electricity: isWorkHour ? (150 + Math.random() * 50).toFixed(1) : (50 + Math.random() * 30).toFixed(1),
                gas: isWorkHour ? (35 + Math.random() * 10).toFixed(1) : (10 + Math.random() * 5).toFixed(1),
                water: isWorkHour ? (50 + Math.random() * 15).toFixed(1) : (15 + Math.random() * 8).toFixed(1),
                compressedAir: isWorkHour ? (90 + Math.random() * 20).toFixed(1) : (30 + Math.random() * 10).toFixed(1),
                status: isWorkHour ? 'Normal' : 'Low Activity'
            });
        }

        tbody.innerHTML = tableData.map(row => {
            const statusColor = row.status === 'Normal' ? '#27ae60' : '#95a5a6';
            return `
                <tr>
                    <td><strong>${row.time}</strong></td>
                    <td>${row.electricity} kWh</td>
                    <td>${row.gas} m³</td>
                    <td>${row.water} L</td>
                    <td>${row.compressedAir} m³</td>
                    <td><span style="display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 11px; font-weight: 700; background: ${statusColor}15; color: ${statusColor};">${row.status}</span></td>
                </tr>
            `;
        }).join('');
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

    startAutoRefresh() {
        setInterval(() => {
            console.log('🔄 Auto-refreshing data...');
            this.updateResourceTable();
            // Could update charts here with new data
        }, this.config.refreshInterval);
    }
};

// Chart filter function (for hourly pattern)
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
    const chart = MaintenanceDashboard.charts.hourlyPattern;
    if (chart) {
        if (filter === 'All') {
            chart.data.datasets[0].hidden = false;
            chart.data.datasets[1].hidden = false;
            chart.data.datasets[2].hidden = false;
        } else if (filter === 'Electricity') {
            chart.data.datasets[0].hidden = false;
            chart.data.datasets[1].hidden = true;
            chart.data.datasets[2].hidden = true;
        } else if (filter === 'Gas') {
            chart.data.datasets[0].hidden = true;
            chart.data.datasets[1].hidden = false;
            chart.data.datasets[2].hidden = true;
        } else if (filter === 'Water') {
            chart.data.datasets[0].hidden = true;
            chart.data.datasets[1].hidden = true;
            chart.data.datasets[2].hidden = false;
        }
        chart.update();
    }
};

// Export dashboard function
window.exportDashboard = function() {
    alert('Export functionality coming soon!\n\nThis will export energy consumption data to Excel/PDF format.');
};

// Initialize dashboard when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => MaintenanceDashboard.init());
} else {
    MaintenanceDashboard.init();
}
