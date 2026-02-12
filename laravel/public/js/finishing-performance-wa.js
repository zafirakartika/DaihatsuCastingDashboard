/**
 * Finishing 1 Performance - WA Dashboard
 * Real-time monitoring of finishing machine parameters
 */

const FinishingPerformanceWA = {
    charts: {},
    config: {
        refreshInterval: 3000, // 3 seconds for real-time feel
        maxDataPoints: 30, // Show last 30 data points on temperature chart
        colors: {
            oven: '#e74c3c',
            cooling: '#3498db',
            ambient: '#95a5a6',
            pressure: '#9b59b6',
            speed: '#f39c12',
            good: '#27ae60',
            defect: '#e74c3c'
        }
    },
    simulationData: [],
    currentIndex: 0,

    init() {
        console.log('🚀 Initializing Finishing Performance WA Dashboard');

        this.initCharts();
        this.generateSimulationData();
        this.startSimulation();

        // Setup filter buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                e.target.classList.add('active');
                this.updateTemperatureChart(e.target.dataset.metric);
            });
        });
    },

    generateSimulationData() {
        // Generate 100 data points for simulation
        for (let i = 0; i < 100; i++) {
            const baseTime = new Date();
            baseTime.setSeconds(baseTime.getSeconds() - (100 - i) * 3);

            this.simulationData.push({
                timestamp: baseTime,
                partsProcessed: 245 + i,
                ovenTemp: 190 + Math.sin(i * 0.1) * 5 + (Math.random() - 0.5) * 2,
                coolingTemp: 30 + (Math.random() - 0.5) * 3,
                ambientTemp: 26 + (Math.random() - 0.5) * 1,
                pressure: 3.0 + (Math.random() - 0.5) * 0.3,
                speed: 1.35 + (Math.random() - 0.5) * 0.1,
                cycleTime: 42 + (Math.random() - 0.5) * 6,
                humidity: 50 + (Math.random() - 0.5) * 5,
                vibration: 2.5 + (Math.random() - 0.5) * 1,
                coatingThickness: 25 + (Math.random() - 0.5) * 3,
                surfaceQuality: 90 + Math.random() * 10,
                isGood: Math.random() > 0.08, // 92% good rate
                defectType: Math.random() > 0.92 ? this.getRandomDefect() : 'none'
            });
        }
    },

    getRandomDefect() {
        const defects = ['scratch', 'coating', 'contamination'];
        return defects[Math.floor(Math.random() * defects.length)];
    },

    startSimulation() {
        // Initial update
        this.updateDashboard();

        // Auto-update every 3 seconds
        setInterval(() => {
            this.currentIndex++;
            if (this.currentIndex >= this.simulationData.length) {
                this.currentIndex = 29; // Loop from point 30
            }
            this.updateDashboard();
        }, this.config.refreshInterval);
    },

    updateDashboard() {
        const dataSlice = this.simulationData.slice(0, this.currentIndex + 1);
        const latestData = this.simulationData[this.currentIndex];

        this.updateKPIs(dataSlice, latestData);
        this.updateStatusBar(latestData);
        this.updateCharts(dataSlice);
        this.updateProcessTable(latestData);
        this.updatePartsLog(dataSlice);
    },

    updateKPIs(dataSlice, latestData) {
        // Parts Processed
        document.getElementById('parts-processed').textContent = latestData.partsProcessed;

        // Cycle Time
        document.getElementById('cycle-time').textContent = latestData.cycleTime.toFixed(1);

        // First Pass Yield
        const goodParts = dataSlice.filter(d => d.isGood).length;
        const fpy = (goodParts / dataSlice.length * 100).toFixed(1);
        document.getElementById('fpy').textContent = fpy;

        // Machine Uptime (simulated as 95-99%)
        const uptime = (95 + Math.random() * 4).toFixed(1);
        document.getElementById('uptime').textContent = uptime;

        // Oven Temperature
        document.getElementById('oven-temp').textContent = latestData.ovenTemp.toFixed(1);

        // Defect Rate
        const defectRate = (100 - fpy).toFixed(1);
        document.getElementById('defect-rate').textContent = defectRate;
    },

    updateStatusBar(data) {
        const statusBar = document.getElementById('statusBar');
        const statusIndicator = document.getElementById('statusIndicator');
        const machineState = document.getElementById('machineState');
        const lastUpdate = document.getElementById('lastUpdate');

        // Update last update time
        lastUpdate.textContent = data.timestamp.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });

        // Update status based on parameters
        if (data.ovenTemp < 180 || data.ovenTemp > 200) {
            statusBar.className = 'status-bar warning';
            statusIndicator.className = 'status-indicator warning';
            machineState.textContent = 'Running - Temperature Warning';
        } else if (!data.isGood) {
            statusBar.className = 'status-bar warning';
            statusIndicator.className = 'status-indicator warning';
            machineState.textContent = 'Running - Quality Issue Detected';
        } else {
            statusBar.className = 'status-bar';
            statusIndicator.className = 'status-indicator';
            machineState.textContent = 'Running - Normal Operation';
        }
    },

    updateProcessTable(data) {
        document.getElementById('param-oven-temp').textContent = data.ovenTemp.toFixed(1);
        document.getElementById('param-cooling-temp').textContent = data.coolingTemp.toFixed(1);
        document.getElementById('param-pressure').textContent = data.pressure.toFixed(2);
        document.getElementById('param-speed').textContent = data.speed.toFixed(2);
        document.getElementById('param-humidity').textContent = data.humidity.toFixed(1);
        document.getElementById('param-vibration').textContent = data.vibration.toFixed(2);
    },

    updatePartsLog(dataSlice) {
        const tbody = document.getElementById('partsLogBody');
        const recentParts = dataSlice.slice(-10).reverse(); // Last 10 parts

        tbody.innerHTML = '';
        recentParts.forEach((part, index) => {
            const tr = document.createElement('tr');
            const batchId = `WA-F${String(245 + this.currentIndex - index).padStart(6, '0')}`;

            let defectBadge = '<span class="defect-badge none">None</span>';
            if (part.defectType === 'scratch') {
                defectBadge = '<span class="defect-badge scratch">Scratch</span>';
            } else if (part.defectType === 'coating') {
                defectBadge = '<span class="defect-badge coating">Coating Issue</span>';
            } else if (part.defectType === 'contamination') {
                defectBadge = '<span class="defect-badge contamination">Contamination</span>';
            }

            tr.innerHTML = `
                <td>${part.timestamp.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' })}</td>
                <td style="font-weight: 600;">${batchId}</td>
                <td>${part.cycleTime.toFixed(1)}s</td>
                <td>${part.coatingThickness.toFixed(1)} μm</td>
                <td>${part.surfaceQuality.toFixed(1)}</td>
                <td><span class="status-badge ${part.isGood ? 'status-normal' : 'status-warning'}">${part.isGood ? 'Pass' : 'Fail'}</span></td>
                <td>${defectBadge}</td>
            `;
            tbody.appendChild(tr);
        });
    },

    initCharts() {
        this.initTemperatureTrendChart();
        this.initPressureSpeedChart();
        this.initCycleTimeChart();
        this.initDefectDistributionChart();
        this.initHourlyPerformanceChart();
    },

    initTemperatureTrendChart() {
        const ctx = document.getElementById('temperatureTrendChart');
        if (!ctx) return;

        this.charts.temperature = new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Oven Temp',
                        data: [],
                        borderColor: this.config.colors.oven,
                        backgroundColor: 'rgba(231, 76, 60, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Cooling Temp',
                        data: [],
                        borderColor: this.config.colors.cooling,
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Ambient Temp',
                        data: [],
                        borderColor: this.config.colors.ambient,
                        backgroundColor: 'rgba(149, 165, 166, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: { size: 11 }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        title: {
                            display: true,
                            text: 'Temperature (°C)',
                            font: { size: 12 }
                        },
                        ticks: { font: { size: 10 } }
                    },
                    x: {
                        ticks: {
                            font: { size: 10 },
                            maxTicksLimit: 10
                        }
                    }
                }
            }
        });
    },

    initPressureSpeedChart() {
        const ctx = document.getElementById('pressureSpeedChart');
        if (!ctx) return;

        this.charts.pressureSpeed = new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Pressure (bar)',
                        data: [],
                        borderColor: this.config.colors.pressure,
                        backgroundColor: 'rgba(155, 89, 182, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        yAxisID: 'y',
                        fill: true
                    },
                    {
                        label: 'Speed (m/min)',
                        data: [],
                        borderColor: this.config.colors.speed,
                        backgroundColor: 'rgba(243, 156, 18, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        yAxisID: 'y1',
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            font: { size: 10 }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Pressure (bar)',
                            font: { size: 11 }
                        },
                        ticks: { font: { size: 10 } }
                    },
                    y1: {
                        type: 'linear',
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Speed (m/min)',
                            font: { size: 11 }
                        },
                        ticks: { font: { size: 10 } },
                        grid: {
                            drawOnChartArea: false
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 10 },
                            maxTicksLimit: 8
                        }
                    }
                }
            }
        });
    },

    initCycleTimeChart() {
        const ctx = document.getElementById('cycleTimeChart');
        if (!ctx) return;

        // Generate hourly data for last 8 hours
        const hours = [];
        const parts = [];
        const avgCycle = [];

        for (let i = 7; i >= 0; i--) {
            const hour = new Date();
            hour.setHours(hour.getHours() - i);
            hours.push(hour.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }));
            parts.push(Math.floor(25 + Math.random() * 10));
            avgCycle.push(42 + (Math.random() - 0.5) * 4);
        }

        this.charts.cycleTime = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: hours,
                datasets: [
                    {
                        label: 'Parts/Hour',
                        data: parts,
                        backgroundColor: 'rgba(52, 152, 219, 0.6)',
                        borderColor: this.config.colors.cooling,
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Avg Cycle Time (s)',
                        data: avgCycle,
                        type: 'line',
                        borderColor: this.config.colors.oven,
                        backgroundColor: 'rgba(231, 76, 60, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        yAxisID: 'y1',
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
                            font: { size: 10 }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Parts/Hour',
                            font: { size: 11 }
                        },
                        ticks: { font: { size: 10 } }
                    },
                    y1: {
                        type: 'linear',
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Cycle Time (s)',
                            font: { size: 11 }
                        },
                        ticks: { font: { size: 10 } },
                        grid: {
                            drawOnChartArea: false
                        }
                    },
                    x: {
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    },

    initDefectDistributionChart() {
        const ctx = document.getElementById('defectDistributionChart');
        if (!ctx) return;

        this.charts.defectDistribution = new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Good Parts', 'Scratches', 'Coating Issues', 'Contamination', 'Others'],
                datasets: [{
                    data: [92, 3, 2.5, 1.5, 1],
                    backgroundColor: [
                        '#27ae60',
                        '#e74c3c',
                        '#f39c12',
                        '#e67e22',
                        '#95a5a6'
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
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + '%';
                            }
                        }
                    }
                }
            }
        });
    },

    initHourlyPerformanceChart() {
        const ctx = document.getElementById('hourlyPerformanceChart');
        if (!ctx) return;

        // Generate 24 hour data
        const hours = [];
        const goodParts = [];
        const defectParts = [];

        for (let i = 0; i < 24; i++) {
            hours.push(`${String(i).padStart(2, '0')}:00`);
            const total = Math.floor(20 + Math.random() * 15);
            const good = Math.floor(total * (0.90 + Math.random() * 0.08));
            goodParts.push(good);
            defectParts.push(total - good);
        }

        this.charts.hourlyPerformance = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: hours,
                datasets: [
                    {
                        label: 'Good Parts',
                        data: goodParts,
                        backgroundColor: this.config.colors.good,
                        stack: 'stack0'
                    },
                    {
                        label: 'Defective Parts',
                        data: defectParts,
                        backgroundColor: this.config.colors.defect,
                        stack: 'stack0'
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
                            font: { size: 10 }
                        }
                    }
                },
                scales: {
                    y: {
                        stacked: true,
                        title: {
                            display: true,
                            text: 'Parts Count',
                            font: { size: 11 }
                        },
                        ticks: { font: { size: 10 } }
                    },
                    x: {
                        stacked: true,
                        ticks: {
                            font: { size: 9 },
                            maxTicksLimit: 12
                        }
                    }
                }
            }
        });
    },

    updateCharts(dataSlice) {
        // Get last 30 data points for real-time charts
        const recentData = dataSlice.slice(-this.config.maxDataPoints);

        // Update Temperature Trend Chart
        if (this.charts.temperature) {
            this.charts.temperature.data.labels = recentData.map(d =>
                d.timestamp.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' })
            );
            this.charts.temperature.data.datasets[0].data = recentData.map(d => d.ovenTemp);
            this.charts.temperature.data.datasets[1].data = recentData.map(d => d.coolingTemp);
            this.charts.temperature.data.datasets[2].data = recentData.map(d => d.ambientTemp);
            this.charts.temperature.update('none');
        }

        // Update Pressure & Speed Chart
        if (this.charts.pressureSpeed) {
            const last15 = recentData.slice(-15);
            this.charts.pressureSpeed.data.labels = last15.map(d =>
                d.timestamp.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })
            );
            this.charts.pressureSpeed.data.datasets[0].data = last15.map(d => d.pressure);
            this.charts.pressureSpeed.data.datasets[1].data = last15.map(d => d.speed);
            this.charts.pressureSpeed.update('none');
        }
    },

    updateTemperatureChart(metric) {
        if (!this.charts.temperature) return;

        // Show/hide datasets based on filter
        if (metric === 'all') {
            this.charts.temperature.data.datasets[0].hidden = false;
            this.charts.temperature.data.datasets[1].hidden = false;
            this.charts.temperature.data.datasets[2].hidden = false;
        } else if (metric === 'oven') {
            this.charts.temperature.data.datasets[0].hidden = false;
            this.charts.temperature.data.datasets[1].hidden = true;
            this.charts.temperature.data.datasets[2].hidden = true;
        } else if (metric === 'cooling') {
            this.charts.temperature.data.datasets[0].hidden = true;
            this.charts.temperature.data.datasets[1].hidden = false;
            this.charts.temperature.data.datasets[2].hidden = true;
        } else if (metric === 'ambient') {
            this.charts.temperature.data.datasets[0].hidden = true;
            this.charts.temperature.data.datasets[1].hidden = true;
            this.charts.temperature.data.datasets[2].hidden = false;
        }

        this.charts.temperature.update();
    }
};

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => FinishingPerformanceWA.init());
} else {
    FinishingPerformanceWA.init();
}
