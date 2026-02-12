/**
 * General ALPC WA Dashboard
 * Aggregated metrics and analytics for WA part casting performance
 */

const GeneralALPCWA = (function() {
    'use strict';

    // Configuration
    const CONFIG = {
        API_URL: 'http://127.0.0.1:8000/api/casting-data',
        SIMULATION_ENABLED: false, // Disabled for real-time updates
        SIMULATION_INTERVAL: 3, // seconds
        REAL_TIME_ENABLED: true,
        REAL_TIME_INTERVAL: 10000, // 10 seconds
        THRESHOLDS: {
            min: 490,
            max: 520
        },
        SENSORS: [
            'r_lower_gate1',
            'r_lower_main1',
            'l_lower_gate1',
            'l_lower_main1',
            'cooling_water'
        ],
        SENSOR_LABELS: {
            r_lower_gate1: 'R Lower Gate 1',
            r_lower_main1: 'R Lower Main 1',
            l_lower_gate1: 'L Lower Gate 1',
            l_lower_main1: 'L Lower Main 1',
            cooling_water: 'Cooling Water'
        }
    };

    // State
    let charts = {};
    let simulationData = [];
    let simulationIndex = 0;
    let simulationIntervalId = null;

    // Get current shift
    function getCurrentShift() {
        const now = new Date();
        const hours = now.getHours();
        const minutes = now.getMinutes();
        const timeInMinutes = hours * 60 + minutes;

        // Morning shift: 07:15 - 16:00 (435 - 960 minutes)
        if (timeInMinutes >= 435 && timeInMinutes < 960) {
            return { shift: 'morning', display: 'Morning', startTime: '07:15:00', endTime: '16:00:00' };
        }
        // Night shift: 19:00 - 06:00
        else if (timeInMinutes >= 1140 || timeInMinutes < 360) {
            return { shift: 'night', display: 'Night', startTime: '19:00:00', endTime: '06:00:00' };
        }
        // Between shifts
        else {
            return { shift: 'morning', display: 'Morning (Next)', startTime: '07:15:00', endTime: '16:00:00' };
        }
    }

    // Update shift display
    function updateShiftDisplay() {
        const currentShift = getCurrentShift();
        const shiftDisplay = document.getElementById('current-shift-display');
        if (shiftDisplay) {
            shiftDisplay.textContent = currentShift.display;
            shiftDisplay.style.background = currentShift.shift === 'morning' ? '#3498db' : '#9b59b6';
        }
    }

    // Initialize charts
    function initCharts() {
        // Production Trend Chart
        const productionCtx = document.getElementById('productionTrendChart');
        if (productionCtx) {
            charts.production = new Chart(productionCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Shots per Hour',
                        data: [],
                        borderColor: '#3498db',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        borderWidth: 2,
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
                            ticks: { font: { size: 10 } }
                        },
                        x: {
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });
        }

        // Quality Pie Chart
        const qualityCtx = document.getElementById('qualityPieChart');
        if (qualityCtx) {
            charts.quality = new Chart(qualityCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Good', 'Rejected'],
                    datasets: [{
                        data: [0, 0],
                        backgroundColor: ['#27ae60', '#e74c3c'],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { font: { size: 10 } }
                        }
                    }
                }
            });
        }

        // Zone Comparison Chart (WA version - 3 zones)
        const zoneCtx = document.getElementById('zoneComparisonChart');
        if (zoneCtx) {
            charts.zone = new Chart(zoneCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Left Side', 'Right Side', 'Cooling Water'],
                    datasets: [{
                        label: 'Average Temperature (°C)',
                        data: [0, 0, 0],
                        backgroundColor: ['#3498db', '#e74c3c', '#1abc9c'],
                        borderWidth: 0
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
                            beginAtZero: false,
                            min: 50,
                            max: 90,
                            ticks: { font: { size: 10 } }
                        },
                        x: {
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });
        }

        // Average Trend Chart
        const avgTrendCtx = document.getElementById('avgTrendChart');
        if (avgTrendCtx) {
            charts.avgTrend = new Chart(avgTrendCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Overall Average',
                        data: [],
                        borderColor: '#0D3B66',
                        backgroundColor: 'rgba(13, 59, 102, 0.1)',
                        borderWidth: 2,
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
                            beginAtZero: false,
                            min: 50,
                            max: 90,
                            ticks: { font: { size: 10 } }
                        },
                        x: {
                            ticks: {
                                font: { size: 10 },
                                maxTicksLimit: 15
                            }
                        }
                    }
                }
            });
        }
    }

    // Calculate statistics for a sensor
    function calculateSensorStats(data, sensorKey) {
        const values = data.map(d => d[sensorKey]).filter(v => v > 0);
        if (values.length === 0) return null;

        const sum = values.reduce((a, b) => a + b, 0);
        const avg = sum / values.length;
        const min = Math.min(...values);
        const max = Math.max(...values);

        // Calculate standard deviation
        const squareDiffs = values.map(value => Math.pow(value - avg, 2));
        const avgSquareDiff = squareDiffs.reduce((a, b) => a + b, 0) / values.length;
        const stdDev = Math.sqrt(avgSquareDiff);

        const current = values[values.length - 1];
        const status = (current >= CONFIG.THRESHOLDS.min && current <= CONFIG.THRESHOLDS.max) ? 'Normal' : 'Out of Spec';

        return { current, avg, min, max, stdDev, status };
    }

    // Calculate zone averages (WA has 5 sensors: 2 gates, 2 mains, 1 cooling)
    function calculateZoneAverages(record) {
        const leftValues = [
            record.l_lower_gate1,
            record.l_lower_main1
        ].filter(v => v > 0);

        const rightValues = [
            record.r_lower_gate1,
            record.r_lower_main1
        ].filter(v => v > 0);

        const gateValues = [
            record.l_lower_gate1,
            record.r_lower_gate1
        ].filter(v => v > 0);

        const mainValues = [
            record.l_lower_main1,
            record.r_lower_main1
        ].filter(v => v > 0);

        const coolingValues = [
            record.cooling_water
        ].filter(v => v > 0);

        const avg = (arr) => arr.length > 0 ? arr.reduce((a, b) => a + b, 0) / arr.length : 0;

        return {
            left: avg(leftValues),
            right: avg(rightValues),
            gate: avg(gateValues),
            main: avg(mainValues),
            cooling: avg(coolingValues)
        };
    }

    // Calculate overall average
    function calculateOverallAverage(record) {
        const allValues = CONFIG.SENSORS.map(s => record[s]).filter(v => v > 0);
        return allValues.length > 0 ? allValues.reduce((a, b) => a + b, 0) / allValues.length : 0;
    }

    // Check if record is within spec
    let qualityCheckCounter = 0;
    function isWithinSpec(record, showDetails = false) {
        let outOfSpecCount = 0;
        let validSensorCount = 0;
        const outOfSpecSensors = [];
        const allSensorValues = [];

        for (let sensor of CONFIG.SENSORS) {
            const value = record[sensor];
            if (value > 0) {
                validSensorCount++;
                allSensorValues.push(`${CONFIG.SENSOR_LABELS[sensor]}: ${value}°C`);

                if (value < CONFIG.THRESHOLDS.min || value > CONFIG.THRESHOLDS.max) {
                    outOfSpecCount++;
                    outOfSpecSensors.push(`${CONFIG.SENSOR_LABELS[sensor]}: ${value}°C (OUT OF RANGE)`);
                }
            }
        }

        // Show detailed logging when requested OR for first 3 checks
        qualityCheckCounter++;
        // Quality check completed

        // Shot is good if ALL sensors are within spec
        return outOfSpecCount === 0 && validSensorCount > 0;
    }

    // Update KPI metrics
    function updateKPIs(data) {
        const totalShots = data.length;
        const goodShots = data.filter(isWithinSpec).length;
        const rejectedShots = totalShots - goodShots;
        const goodRate = totalShots > 0 ? (goodShots / totalShots * 100).toFixed(1) : 0;
        const rejectRate = totalShots > 0 ? (rejectedShots / totalShots * 100).toFixed(1) : 0;

        // Log quality summary for the latest record
        if (data.length > 0) {
            const latestRecord = data[data.length - 1];
            const isGood = isWithinSpec(latestRecord, true); // Show detailed logging
        }

        // Calculate production rate (shots per hour)
        if (data.length >= 2) {
            const firstTime = new Date(data[0].datetime_stamp || data[0].datetime);
            const lastTime = new Date(data[data.length - 1].datetime_stamp || data[data.length - 1].datetime);
            const hoursDiff = (lastTime - firstTime) / (1000 * 60 * 60);
            const productionRate = hoursDiff > 0 ? Math.round(totalShots / hoursDiff) : 0;
            document.getElementById('production-rate').textContent = productionRate;
        }

        document.getElementById('total-shots').textContent = totalShots;
        document.getElementById('good-shots').textContent = goodShots;
        document.getElementById('rejected-shots').textContent = rejectedShots;
        document.getElementById('good-rate').textContent = `${goodRate} %`;
        document.getElementById('reject-rate').textContent = `${rejectRate} %`;

        // Update quality pie chart
        if (charts.quality) {
            charts.quality.data.datasets[0].data = [goodShots, rejectedShots];
            charts.quality.update('none');
        }
    }

    // Update zone averages (WA version with cooling water)
    function updateZoneMetrics(record) {
        const zones = calculateZoneAverages(record);

        // Update display
        document.getElementById('left-avg').textContent = zones.left.toFixed(1);
        document.getElementById('right-avg').textContent = zones.right.toFixed(1);
        document.getElementById('cooling-avg').textContent = zones.cooling.toFixed(1);

        // Update status badges
        const updateStatus = (id, value) => {
            const element = document.getElementById(id);
            if (!element) return;
            if (value >= CONFIG.THRESHOLDS.min && value <= CONFIG.THRESHOLDS.max) {
                element.textContent = 'Normal';
                element.className = 'status-badge status-normal';
            } else {
                element.textContent = 'Out of Spec';
                element.className = 'status-badge status-warning';
            }
        };

        updateStatus('status-left', zones.left);
        updateStatus('status-right', zones.right);
        updateStatus('status-cooling', zones.cooling);

        // Update zone comparison chart
        if (charts.zone) {
            charts.zone.data.datasets[0].data = [zones.left, zones.right, zones.cooling];
            charts.zone.data.labels = ['Left Side', 'Right Side', 'Cooling Water'];
            charts.zone.update('none');
        }
    }

    // Update uniformity metrics
    function updateUniformityMetrics(record) {
        const allValues = CONFIG.SENSORS.map(s => record[s]).filter(v => v > 0);

        if (allValues.length > 0) {
            const min = Math.min(...allValues);
            const max = Math.max(...allValues);
            const range = max - min;

            const avg = allValues.reduce((a, b) => a + b, 0) / allValues.length;
            const squareDiffs = allValues.map(v => Math.pow(v - avg, 2));
            const variance = squareDiffs.reduce((a, b) => a + b, 0) / allValues.length;
            const stdDev = Math.sqrt(variance);

            const zones = calculateZoneAverages(record);
            const lrDiff = Math.abs(zones.left - zones.right);

            document.getElementById('temp-range').textContent = range.toFixed(1);
            document.getElementById('std-dev').textContent = stdDev.toFixed(2);
            document.getElementById('lr-diff').textContent = lrDiff.toFixed(1);
        }
    }

    // Update statistics table
    function updateStatsTable(data) {
        const tbody = document.getElementById('stats-table-body');
        tbody.innerHTML = '';

        CONFIG.SENSORS.forEach(sensor => {
            const stats = calculateSensorStats(data, sensor);
            if (!stats) return;

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td style="padding: 8px; border: 1px solid #ddd;">${CONFIG.SENSOR_LABELS[sensor]}</td>
                <td style="padding: 8px; text-align: right; border: 1px solid #ddd; font-weight: 600;">${stats.current.toFixed(1)}°C</td>
                <td style="padding: 8px; text-align: right; border: 1px solid #ddd;">${stats.avg.toFixed(1)}°C</td>
                <td style="padding: 8px; text-align: right; border: 1px solid #ddd;">${stats.min.toFixed(1)}°C</td>
                <td style="padding: 8px; text-align: right; border: 1px solid #ddd;">${stats.max.toFixed(1)}°C</td>
                <td style="padding: 8px; text-align: right; border: 1px solid #ddd;">${stats.stdDev.toFixed(2)}°C</td>
                <td style="padding: 8px; text-align: center; border: 1px solid #ddd;">
                    <span class="status-badge ${stats.status === 'Normal' ? 'status-normal' : 'status-warning'}" style="font-size: 10px; padding: 3px 8px;">
                        ${stats.status}
                    </span>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Update production trend chart
    function updateProductionTrend(data) {
        if (!charts.production || data.length === 0) return;

        // Group by hour
        const hourlyData = {};
        data.forEach(record => {
            const datetime = new Date(record.datetime_stamp || record.datetime);
            const hour = datetime.getHours();
            const hourKey = `${hour.toString().padStart(2, '0')}:00`;

            if (!hourlyData[hourKey]) {
                hourlyData[hourKey] = 0;
            }
            hourlyData[hourKey]++;
        });

        const labels = Object.keys(hourlyData).sort();
        const values = labels.map(label => hourlyData[label]);

        charts.production.data.labels = labels;
        charts.production.data.datasets[0].data = values;
        charts.production.update('none');
    }

    // Update average trend chart
    function updateAvgTrendChart(data) {
        if (!charts.avgTrend || data.length === 0) return;

        const labels = [];
        const avgValues = [];

        data.forEach((record, index) => {
            if (index % 5 === 0 || index === data.length - 1) { // Sample every 5th point
                const datetime = new Date(record.datetime_stamp || record.datetime);
                const timeLabel = datetime.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                labels.push(timeLabel);

                const overallAvg = calculateOverallAverage(record);
                avgValues.push(overallAvg);
            }
        });

        charts.avgTrend.data.labels = labels;
        charts.avgTrend.data.datasets[0].data = avgValues;
        charts.avgTrend.update('none');
    }

    // Update last update time
    function updateLastUpdateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        document.getElementById('last-update').textContent = timeString;
    }

    // Load data from API
    async function loadData() {
        try {
            const filterDate = document.getElementById('filter-date').value;
            const filterShift = document.getElementById('filter-shift').value;
            const filterLPC = document.getElementById('filter-lpc').value;

            // Check if LPC is selected - only LPC 2 has data
            if (filterLPC !== '2' && filterLPC !== 'all') {
                alert(`No data available for LPC ${filterLPC}.\n\nThe current data source is from LPC 2 only.\nPlease select LPC 2 to view the dashboard.`);
                // Clear all displays
                clearAllDisplays();
                return;
            }

            let shift, startTime, endTime;
            if (filterShift === 'auto') {
                const currentShift = getCurrentShift();
                shift = currentShift.shift;
                startTime = currentShift.startTime;
                endTime = currentShift.endTime;
            } else {
                shift = filterShift;
                if (filterShift === 'morning') {
                    startTime = '07:15:00';
                    endTime = '16:00:00';
                } else {
                    startTime = '19:00:00';
                    endTime = '06:00:00';
                }
            }

            const url = `${CONFIG.API_URL}?action=trend&date=${filterDate}&shift=${shift}&start_time=${startTime}&end_time=${endTime}`;

            const response = await fetch(url);
            const result = await response.json();

            if (result.data && result.data.length > 0) {
                if (CONFIG.SIMULATION_ENABLED) {
                    simulationData = [...result.data];
                    simulationIndex = 0;
                    startSimulation(CONFIG.SIMULATION_INTERVAL);
                } else {
                    processData(result.data);
                }
            }

        } catch (error) {
            console.error('❌ Error loading data:', error);
        }
    }

    // Process data and update all displays
    function processData(data) {
        if (data.length === 0) return;

        const latestRecord = data[data.length - 1];

        updateKPIs(data);
        updateZoneMetrics(latestRecord);
        updateUniformityMetrics(latestRecord);
        updateStatsTable(data);
        updateProductionTrend(data);
        updateAvgTrendChart(data);
        updateLastUpdateTime();
    }

    // Simulation functions
    function startSimulation(intervalSeconds) {
        if (simulationIntervalId) {
            clearInterval(simulationIntervalId);
        }

        simulateNextRecord();

        simulationIntervalId = setInterval(() => {
            simulateNextRecord();
        }, intervalSeconds * 1000);
    }

    function simulateNextRecord() {
        if (simulationData.length === 0) return;

        const dataUpToNow = simulationData.slice(0, simulationIndex + 1);
        processData(dataUpToNow);

        console.log(`🎬 Simulation: Record ${simulationIndex + 1}/${simulationData.length}`);

        simulationIndex++;
        if (simulationIndex >= simulationData.length) {
            simulationIndex = 0;
            console.log('🔄 Simulation loop completed - restarting');
        }
    }

    function stopSimulation() {
        if (simulationIntervalId) {
            clearInterval(simulationIntervalId);
            simulationIntervalId = null;
            console.log('⏹️ Simulation stopped');
        }
    }

    // Clear all displays when no data available
    function clearAllDisplays() {
        // Clear OEE metrics
        document.getElementById('oee-count-actual').textContent = '0';
        document.getElementById('oee-availability').textContent = '0.0';
        document.getElementById('oee-performance').textContent = '0.0';
        document.getElementById('oee-quality').textContent = '0.0';
        document.getElementById('oee-overall').textContent = '0.0';

        // Clear charts
        if (charts.trend) {
            charts.trend.data.labels = [];
            charts.trend.data.datasets.forEach(dataset => {
                dataset.data = [];
            });
            charts.trend.update();
        }

        if (charts.comparison) {
            charts.comparison.data.labels = [];
            charts.comparison.data.datasets.forEach(dataset => {
                dataset.data = [];
            });
            charts.comparison.update();
        }

        if (charts.distribution) {
            charts.distribution.data.labels = [];
            charts.distribution.data.datasets.forEach(dataset => {
                dataset.data = [];
            });
            charts.distribution.update();
        }

        console.log('🧹 All displays cleared');
    }

    // Initialize
    function init() {
        console.log('🚀 Initializing General ALPC WA Dashboard');

        updateShiftDisplay();
        setInterval(updateShiftDisplay, 60000);

        initCharts();
        loadData();

        // Start real-time updates
        if (CONFIG.REAL_TIME_ENABLED) {
            startRealTimeUpdates();
        }
    }

    // Start real-time updates
    function startRealTimeUpdates() {
        if (realTimeIntervalId) {
            clearInterval(realTimeIntervalId);
        }

        realTimeIntervalId = setInterval(() => {
            loadData();
        }, CONFIG.REAL_TIME_INTERVAL);

        console.log(`🔄 Real-time updates enabled - refreshing every ${CONFIG.REAL_TIME_INTERVAL / 1000} seconds`);
    }

    // Stop real-time updates
    function stopRealTimeUpdates() {
        if (realTimeIntervalId) {
            clearInterval(realTimeIntervalId);
            realTimeIntervalId = null;
            console.log('⏹️ Real-time updates stopped');
        }
    }

    // Public API
    return {
        init,
        loadData,
        startSimulation,
        stopSimulation,
        startRealTimeUpdates,
        stopRealTimeUpdates
    };
})();

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', GeneralALPCTR.init);
} else {
    GeneralALPCTR.init();
}
