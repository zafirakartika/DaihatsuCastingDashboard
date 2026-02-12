/**
 * PCL Dashboard JavaScript
 * Handles stock tracking, shipment monitoring, and logistics visualization
 */

const PCLDashboard = {
    charts: {
        stockTrend: null,
        shipment: null,
        stockDistribution: null,
        warehouse: null
    },

    config: {
        refreshInterval: 30000, // 30 seconds
        colors: {
            total: '#3b82f6',
            wa: '#9b59b6',
            tr: '#f39c12',
            stock: '#10b981',
            shipment: '#06b6d4'
        }
    },

    init() {
        console.log('Initializing PCL Dashboard...');

        // Initialize all charts
        this.initStockTrendChart();
        this.initShipmentChart();
        this.initStockDistributionChart();
        this.initWarehouseChart();

        // Load initial data
        this.loadAllData();

        // Setup auto-refresh
        setInterval(() => this.loadAllData(), this.config.refreshInterval);

        console.log('PCL Dashboard initialized successfully');
    },

    initStockTrendChart() {
        const ctx = document.getElementById('stockTrendChart');
        if (!ctx) return;

        // Generate last 7 days
        const last7Days = [];
        for (let i = 6; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            last7Days.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        }

        this.charts.stockTrend = new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: last7Days,
                datasets: [
                    {
                        label: 'Total Stock',
                        data: [8120, 8245, 8380, 8290, 8450, 8510, 8542],
                        borderColor: this.config.colors.total,
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        borderWidth: 3,
                        fill: true
                    },
                    {
                        label: 'WA Stock',
                        data: [4520, 4580, 4640, 4610, 4720, 4780, 4820],
                        borderColor: this.config.colors.wa,
                        backgroundColor: 'rgba(155, 89, 182, 0.1)',
                        tension: 0.4,
                        borderWidth: 2,
                        fill: true
                    },
                    {
                        label: 'TR Stock',
                        data: [3600, 3665, 3740, 3680, 3730, 3730, 3722],
                        borderColor: this.config.colors.tr,
                        backgroundColor: 'rgba(243, 156, 18, 0.1)',
                        tension: 0.4,
                        borderWidth: 2,
                        fill: true
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
                            text: 'Stock Level (parts)',
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

    initShipmentChart() {
        const ctx = document.getElementById('shipmentChart');
        if (!ctx) return;

        // Generate last 7 days
        const last7Days = [];
        for (let i = 6; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            last7Days.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        }

        // Simulated shipment data
        const shipmentData = [18, 22, 20, 25, 19, 23, 24];

        this.charts.shipment = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: last7Days,
                datasets: [{
                    label: 'Shipments',
                    data: shipmentData,
                    backgroundColor: this.config.colors.shipment,
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
                            text: 'Batches',
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

    initStockDistributionChart() {
        const ctx = document.getElementById('stockDistributionChart');
        if (!ctx) return;

        this.charts.stockDistribution = new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['WA Parts', 'TR Parts', 'Raw Materials', 'Others'],
                datasets: [{
                    data: [4820, 3722, 520, 480],
                    backgroundColor: [
                        '#9b59b6',
                        '#f39c12',
                        '#10b981',
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

    initWarehouseChart() {
        const ctx = document.getElementById('warehouseChart');
        if (!ctx) return;

        this.charts.warehouse = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Zone A', 'Zone B', 'Zone C', 'Zone D'],
                datasets: [{
                    label: 'Utilization %',
                    data: [85, 72, 68, 90],
                    backgroundColor: [
                        '#10b981',
                        '#3b82f6',
                        '#f59e0b',
                        '#ef4444'
                    ],
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
                        max: 100,
                        title: {
                            display: true,
                            text: 'Utilization (%)',
                            font: { size: 11 }
                        },
                        ticks: {
                            font: { size: 10 },
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    },
                    x: {
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
        this.updateStockMovementTable();
    },

    updateStockMovementTable() {
        const tbody = document.getElementById('stockTableBody');
        if (!tbody) return;

        // Simulated stock movement data
        const movements = [
            { time: '14:45:20', part: 'WA', batch: 'WA-B1023482', type: 'Shipment Out', qty: '150', location: 'Zone A', status: 'Completed' },
            { time: '13:30:15', part: 'TR', batch: 'TR-B2034521', type: 'Receiving', qty: '200', location: 'Zone B', status: 'In Progress' },
            { time: '12:15:40', part: 'WA', batch: 'WA-B1023455', type: 'Transfer', qty: '75', location: 'Zone C', status: 'Completed' },
            { time: '11:20:30', part: 'TR', batch: 'TR-B2034498', type: 'Shipment Out', qty: '180', location: 'Zone A', status: 'Completed' },
            { time: '10:05:50', part: 'WA', batch: 'WA-B1023421', type: 'Receiving', qty: '250', location: 'Zone D', status: 'Completed' }
        ];

        tbody.innerHTML = '';
        movements.forEach(movement => {
            const tr = document.createElement('tr');
            let statusClass = 'status-normal';
            if (movement.status === 'Completed') statusClass = 'status-good';
            if (movement.status === 'In Progress') statusClass = 'status-warning';

            tr.innerHTML = `
                <td style="padding: 8px; font-size: 11px; border-bottom: 1px solid var(--gray-border);">${movement.time}</td>
                <td style="padding: 8px; font-size: 11px; border-bottom: 1px solid var(--gray-border);">${movement.part}</td>
                <td style="padding: 8px; font-size: 11px; border-bottom: 1px solid var(--gray-border);">${movement.batch}</td>
                <td style="padding: 8px; font-size: 11px; border-bottom: 1px solid var(--gray-border);">${movement.type}</td>
                <td style="padding: 8px; font-size: 11px; border-bottom: 1px solid var(--gray-border);">${movement.qty}</td>
                <td style="padding: 8px; font-size: 11px; border-bottom: 1px solid var(--gray-border);">${movement.location}</td>
                <td style="padding: 8px; border-bottom: 1px solid var(--gray-border);">
                    <span class="status-badge ${statusClass}" style="font-size: 10px; padding: 3px 8px;">${movement.status}</span>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }
};

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => PCLDashboard.init());
} else {
    PCLDashboard.init();
}
