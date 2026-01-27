/**
 * Quality Dashboard JavaScript
 * Handles rejection tracking, quality metrics, and visualization
 */

const QualityDashboard = {
    charts: {
        rejectionTrend: null,
        hourlyRejection: null,
        rejectionType: null,
        rejectionPart: null
    },

    config: {
        refreshInterval: 30000, 
        colors: {
            external: '#ef4444',
            internal: '#f59e0b',
            total: '#3b82f6',
            wa: '#9b59b6',
            tr: '#f39c12'
        }
    },

    init() {
        console.log('Initializing Quality Dashboard...');

        // Initialize all charts
        this.initRejectionTrendChart();
        this.initHourlyRejectionChart();
        this.initRejectionTypeChart();
        this.initRejectionPartChart();

        // Load initial data
        this.loadAllData();

        // Setup auto-refresh
        setInterval(() => this.loadAllData(), this.config.refreshInterval);

        console.log('Quality Dashboard initialized successfully');
    },

    initRejectionTrendChart() {
        const ctx = document.getElementById('rejectionTrendChart');
        if (!ctx) return;

        // Generate last 7 days
        const last7Days = [];
        for (let i = 6; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            last7Days.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        }

        this.charts.rejectionTrend = new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: last7Days,
                datasets: [
                    {
                        label: 'External Rejection',
                        data: [15, 12, 18, 10, 14, 9, 12],
                        borderColor: this.config.colors.external,
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        borderWidth: 2,
                        fill: true
                    },
                    {
                        label: 'Internal Rejection',
                        data: [42, 38, 45, 35, 40, 33, 35],
                        borderColor: this.config.colors.internal,
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.4,
                        borderWidth: 2,
                        fill: true
                    },
                    {
                        label: 'Total Rejection',
                        data: [57, 50, 63, 45, 54, 42, 47],
                        borderColor: this.config.colors.total,
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        borderWidth: 3,
                        fill: false
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
                            padding: 15,
                            font: { size: 11 }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Rejections',
                            font: { size: 11 }
                        },
                        ticks: {
                            font: { size: 10 }
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 10 }
                        }
                    }
                }
            }
        });
    },

    initHourlyRejectionChart() {
        const ctx = document.getElementById('hourlyRejectionChart');
        if (!ctx) return;

        // Generate 24 hours
        const hours = [];
        for (let i = 0; i < 24; i++) {
            hours.push(`${String(i).padStart(2, '0')}:00`);
        }

        // Simulate hourly data (higher during working hours)
        const hourlyData = hours.map((_, i) => {
            if (i >= 7 && i < 16) return Math.floor(Math.random() * 5) + 2; // Morning shift
            if (i >= 19 || i < 6) return Math.floor(Math.random() * 4) + 1; // Night shift
            return 0; // Between shifts
        });

        this.charts.hourlyRejection = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: hours,
                datasets: [{
                    label: 'Rejections',
                    data: hourlyData,
                    backgroundColor: this.config.colors.total,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Count',
                            font: { size: 11 }
                        },
                        ticks: {
                            font: { size: 10 }
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 9 },
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
    },

    initRejectionTypeChart() {
        const ctx = document.getElementById('rejectionTypeChart');
        if (!ctx) return;

        this.charts.rejectionType = new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Porosity', 'Dimensional', 'Surface Defect', 'Crack', 'Others'],
                datasets: [{
                    data: [18, 12, 8, 5, 4],
                    backgroundColor: [
                        '#ef4444',
                        '#f59e0b',
                        '#3b82f6',
                        '#8b5cf6',
                        '#6b7280'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: { size: 10 },
                            padding: 10,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    },

    initRejectionPartChart() {
        const ctx = document.getElementById('rejectionPartChart');
        if (!ctx) return;

        this.charts.rejectionPart = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['WA', 'TR'],
                datasets: [
                    {
                        label: 'External',
                        data: [7, 5],
                        backgroundColor: this.config.colors.external
                    },
                    {
                        label: 'Internal',
                        data: [18, 17],
                        backgroundColor: this.config.colors.internal
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
                            font: { size: 10 },
                            usePointStyle: true
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        stacked: true,
                        title: {
                            display: true,
                            text: 'Rejections',
                            font: { size: 11 }
                        },
                        ticks: {
                            font: { size: 10 }
                        }
                    },
                    x: {
                        stacked: true,
                        ticks: {
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    },

    loadAllData() {
        // Simulate loading data and updating table
        this.updateRejectionTable();
    },

    updateRejectionTable() {
        const tbody = document.getElementById('rejectionTableBody');
        if (!tbody) return;

        // Simulated rejection data
        const rejections = [
            { time: '14:23:15', part: 'WA', id: 'WA-1023482', type: 'External', category: 'Porosity', remarks: 'Customer complaint' },
            { time: '13:45:30', part: 'TR', id: 'TR-2034521', type: 'Internal', category: 'Dimensional', remarks: 'Out of tolerance' },
            { time: '12:15:20', part: 'WA', id: 'WA-1023455', type: 'Internal', category: 'Surface Defect', remarks: 'Visual inspection' },
            { time: '11:30:10', part: 'TR', id: 'TR-2034498', type: 'External', category: 'Crack', remarks: 'Final inspection' },
            { time: '10:50:45', part: 'WA', id: 'WA-1023421', type: 'Internal', category: 'Porosity', remarks: 'X-ray detection' }
        ];

        tbody.innerHTML = '';
        rejections.forEach(rejection => {
            const tr = document.createElement('tr');
            const typeClass = rejection.type === 'External' ? 'status-critical' : 'status-warning';

            tr.innerHTML = `
                <td style="padding: 8px; font-size: 11px; border-bottom: 1px solid var(--gray-border);">${rejection.time}</td>
                <td style="padding: 8px; font-size: 11px; border-bottom: 1px solid var(--gray-border);">${rejection.part}</td>
                <td style="padding: 8px; font-size: 11px; border-bottom: 1px solid var(--gray-border);">${rejection.id}</td>
                <td style="padding: 8px; border-bottom: 1px solid var(--gray-border);">
                    <span class="status-badge ${typeClass}" style="font-size: 10px; padding: 3px 8px;">${rejection.type}</span>
                </td>
                <td style="padding: 8px; font-size: 11px; border-bottom: 1px solid var(--gray-border);">${rejection.category}</td>
                <td style="padding: 8px; font-size: 11px; border-bottom: 1px solid var(--gray-border);">${rejection.remarks}</td>
            `;
            tbody.appendChild(tr);
        });
    }
};

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => QualityDashboard.init());
} else {
    QualityDashboard.init();
}
