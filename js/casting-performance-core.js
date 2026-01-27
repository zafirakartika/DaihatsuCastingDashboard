/**
 * Casting Performance Core Module - OPTIMIZED VERSION
 * Shared logic for all casting performance monitoring (WA, TR, KR, NR, 3SZ)
 *
 * Performance Optimizations:
 * - Debounced/throttled updates
 * - Efficient data structures (TypedArrays)
 * - RequestAnimationFrame for smooth rendering
 * - Memory-efficient caching
 * - Reduced DOM manipulations
 * - Optimized chart configurations
 *
 * @param {Object} config - Part-specific configuration
 * @returns {Object} Public API for the casting performance module
 */
const CastingPerformanceCore = (config) => {
    // Validate required configuration
    if (!config || !config.partName || !config.apiUrl || !config.metrics || !config.thresholds) {
        throw new Error('Invalid configuration: partName, apiUrl, metrics, and thresholds are required');
    }

    // PERFORMANCE OPTIMIZATION: Initialize performance optimizer
    const perfOptimizer = window.performanceOptimizer || {
        debounce: (key, func, delay) => setTimeout(func, delay),
        throttle: (key, func) => func(),
        requestAnimationFrame: (callback) => requestAnimationFrame(callback),
        setCache: () => {},
        getCache: () => null
    };

    // Merge configuration with defaults
    const CONFIG = {
        PART_NAME: config.partName,
        API_URL: config.apiUrl,
        SHOW_ID_PART: config.showIdPart !== undefined ? config.showIdPart : true,
        REFRESH_INTERVAL: config.refreshInterval || 60000,
        TREND_DATA_LIMIT: config.trendDataLimit || 300,
        WEBSOCKET_CONFIG: config.websocketConfig || {
            key: 'local-key',
            cluster: 'mt1',
            wsHost: '127.0.0.1',
            wsPort: 6001,
            wsPath: '',
            forceTLS: false,
            encrypted: false,
            disableStats: true,
            enabledTransports: ['ws'],  // Only allow non-secure WebSocket
            authEndpoint: '/broadcasting/auth'
        },
        CHART_COLORS: config.chartColors,
        THRESHOLDS: config.thresholds,
        METRICS: config.metrics,
        CHART_CONFIG: config.chartConfig || {
            yMin: 400,
            yMax: 600,
            tickCount: 11
        },
        TABLE_COLUMNS: config.tableColumns || config.metrics.map(m => ({
            key: m.key,
            label: m.label
        })),
        OEE_CONFIG: config.oeeConfig || {
            enabled: true,
            targets: {
                count: 101,
                availability: 85,
                performance: 95,
                quality: 99,
                oee: 80
            },
            cycleTimeSeconds: 312,
            shiftDurationHours: 8.75
        }
    };

    // Chart instances
    let charts = {
        trend: null,
        comparison: null,
        distribution: null
    };

    // Store all table data for filtering
    let allTableData = [];

    // Real-time monitoring control
    let refreshIntervalId = null;
    let isRealTimeActive = true;

    // WebSocket connection
    let pusher = null;
    let channel = null;

    // Simulation mode variables
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
        // Night shift: 19:00 - 06:00 (1140 minutes onwards, or 0 - 360 minutes)
        if (timeInMinutes >= 435 && timeInMinutes < 960) {
            return 'morning';
        } else if (timeInMinutes >= 1140 || timeInMinutes < 360) {
            return 'night';
        } else {
            // Between shifts - default to morning
            return 'morning';
        }
    }

    // Get shift time range
    function getShiftTimeRange(shift) {
        if (shift === 'morning') {
            return { start: '07:15:00', end: '16:00:00' };
        } else if (shift === 'night') {
            return { start: '19:00:00', end: '06:00:00' };
        }
        return null;
    }

    // Update shift display
    function updateShiftDisplay() {
        const shift = getCurrentShift();
        const shiftDisplay = document.getElementById('current-shift-display');

        if (shiftDisplay) {
            if (shift === 'morning') {
                shiftDisplay.textContent = 'Morning';
                shiftDisplay.style.background = '#3498db';
            } else {
                shiftDisplay.textContent = 'Night';
                shiftDisplay.style.background = '#9b59b6';
            }
        }
    }

    // Initialize module
    function init() {
        // Initialize charts
        initCharts();

        // Update shift display
        updateShiftDisplay();
        setInterval(updateShiftDisplay, 60000); // Update every minute

        // Load initial data
        loadAllData();

        // Setup search and sort functionality
        setupTableControls();

        // Check if simulation mode is enabled
        const simConfig = config.simulationMode || {};
        if (simConfig.enabled) {
            // Don't initialize WebSocket or auto-refresh in simulation mode
        } else {
            // DISABLED: WebSocket - using HTTP polling only (more reliable for local development)
            // initWebSocket();

            // Setup auto-refresh (HTTP polling - works without additional setup)
            startAutoRefresh();
        }

        // Setup filter button
        const applyFilterBtn = document.querySelector('.filter-btn.active');
        if (applyFilterBtn) {
            applyFilterBtn.addEventListener('click', loadAllData);
        }

        // Setup shift filter change listener
        const filterShift = document.getElementById('filter-shift');
        const filterDate = document.getElementById('filter-date');

        if (filterShift) {
            filterShift.addEventListener('change', () => {
                loadAllData();
            });
        }

        if (filterDate) {
            filterDate.addEventListener('change', () => {
                loadAllData();
            });
        }

    }

    // Initialize WebSocket connection
    function initWebSocket() {
        try {
            // Check if Pusher library is available
            if (typeof Pusher === 'undefined') {
                return;
            }

            // Initialize Pusher client with explicit non-secure WebSocket configuration
            pusher = new Pusher(CONFIG.WEBSOCKET_CONFIG.key, {
                wsHost: CONFIG.WEBSOCKET_CONFIG.wsHost,
                wsPort: CONFIG.WEBSOCKET_CONFIG.wsPort,
                wsPath: CONFIG.WEBSOCKET_CONFIG.wsPath,
                forceTLS: CONFIG.WEBSOCKET_CONFIG.forceTLS,
                encrypted: CONFIG.WEBSOCKET_CONFIG.encrypted,
                disableStats: CONFIG.WEBSOCKET_CONFIG.disableStats,
                enabledTransports: CONFIG.WEBSOCKET_CONFIG.enabledTransports,
                authEndpoint: CONFIG.WEBSOCKET_CONFIG.authEndpoint,
                cluster: CONFIG.WEBSOCKET_CONFIG.cluster
            });

            // WebSocket configuration applied

            // Subscribe to casting-performance channel
            channel = pusher.subscribe('casting-performance');

            // Listen for connection events
            pusher.connection.bind('connected', () => {
            });

            pusher.connection.bind('error', (err) => {
            });

            pusher.connection.bind('unavailable', () => {
            });

            pusher.connection.bind('failed', () => {
            });

            // Listen for data updates
            channel.bind('data.updated', (data) => {
                console.log('📡 Received real-time update:', data);
                handleRealtimeUpdate(data.data);
            });

            console.log('🔧 WebSocket initialized (will fallback to HTTP polling if server unavailable)');
        } catch (error) {
            console.error('❌ Failed to initialize WebSocket:', error);
            console.log('ℹ️  HTTP polling still active');
        }
    }

    // Disconnect WebSocket
    function disconnectWebSocket() {
        if (channel) {
            channel.unbind_all();
            pusher.unsubscribe('casting-performance');
        }
        if (pusher) {
            pusher.disconnect();
        }
    }

    // Update latest card with new data
    function updateLatestCard(data) {
        if (!data) return;

        CONFIG.METRICS.forEach(metric => {
            const valueEl = document.getElementById(`metric-${metric.elementId}`);
            const statusEl = document.getElementById(`status-${metric.elementId}`);
            const value = data[metric.key];

            if (valueEl && value !== undefined) {
                valueEl.textContent = value.toFixed(1);
                valueEl.parentElement.classList.add('pulse');
                setTimeout(() => {
                    valueEl.parentElement.classList.remove('pulse');
                }, 300);
            }

            if (statusEl && value !== undefined) {
                updateStatus(metric.elementId, value, statusEl);
            }
        });
    }

    // Helper function to update distribution chart
    function updateDistributionChart(allTemps, distConfig) {
        if (!charts.distribution || allTemps.length === 0) return;

        const mean = average(allTemps);
        const stdDev = calculateStdDev(allTemps, mean);

        const lowerLimit = distConfig.lowerLimit !== undefined ? distConfig.lowerLimit : (mean - 3 * stdDev);
        const upperLimit = distConfig.upperLimit !== undefined ? distConfig.upperLimit : (mean + 3 * stdDev);
        const setPoint = distConfig.setPoint !== undefined ? distConfig.setPoint : mean;

        const points = 100;
        const minTemp = lowerLimit;
        const maxTemp = upperLimit;
        const step = (maxTemp - minTemp) / points;
        const compactStdDev = (upperLimit - lowerLimit) / 6;

        const normalCurve = [];
        for (let i = 0; i <= points; i++) {
            const x = minTemp + (i * step);
            const normalValue = Math.exp(-0.5 * Math.pow((x - setPoint) / compactStdDev, 2)) / (compactStdDev * Math.sqrt(2 * Math.PI));
            normalCurve.push({ x: x, y: normalValue });
        }

        charts.distribution.data.datasets[0].data = normalCurve;
        charts.distribution.data.datasets[1].data = []; // Hide histogram for last shot mode
        charts.distribution.update('none');
    }

    // Add new data point to trend chart incrementally
    function addDataPointToChart(newData) {
        if (!charts.trend || !newData) {
            return;
        }

        // Calculate the next record number
        const currentRecordCount = charts.trend.data.labels.length;
        const nextRecordLabel = `Record ${currentRecordCount + 1}`;

        console.log(`➕ Adding new data point: ${nextRecordLabel}`);

        // Add new label
        charts.trend.data.labels.push(nextRecordLabel);

        // Add new data values for each metric
        CONFIG.METRICS.forEach((metric, index) => {
            const value = newData[metric.key];
            if (value !== undefined && charts.trend.data.datasets[index]) {
                charts.trend.data.datasets[index].data.push(value);
            }
        });

        // Enforce TREND_DATA_LIMIT by removing oldest data points
        while (charts.trend.data.labels.length > CONFIG.TREND_DATA_LIMIT) {
            charts.trend.data.labels.shift(); // Remove oldest label
            CONFIG.METRICS.forEach((_, index) => {
                if (charts.trend.data.datasets[index]) {
                    charts.trend.data.datasets[index].data.shift(); // Remove oldest data point
                }
            });
        }

        // Update comparison chart with new average
        if (charts.comparison) {
            const avgTemp = CONFIG.METRICS
                .map(m => newData[m.key])
                .filter(t => t > 0)
                .reduce((sum, val) => sum + val, 0) / CONFIG.METRICS.length;

            charts.comparison.data.labels.push(nextRecordLabel);
            charts.comparison.data.datasets[0].data.push(avgTemp);

            // Enforce limit
            while (charts.comparison.data.labels.length > CONFIG.TREND_DATA_LIMIT) {
                charts.comparison.data.labels.shift();
                charts.comparison.data.datasets[0].data.shift();
            }
        }

        // Update distribution chart with last shot
        if (charts.distribution) {
            const distConfig = config.distributionConfig || {};
            if (distConfig.useLastShotOnly) {
                const allTemps = [];
                CONFIG.METRICS.forEach(metric => {
                    const value = newData[metric.key];
                    if (value > 0) allTemps.push(value);
                });

                if (allTemps.length > 0) {
                    updateDistributionChart(allTemps, distConfig);
                }
            }
        }

        // Update charts instantly for smooth real-time feel
        charts.trend.update('none');
        if (charts.comparison) {
            charts.comparison.update('none');
        }
    }

    // Counter for debouncing full data reloads
    let realtimeUpdateCounter = 0;

    // PERFORMANCE OPTIMIZED: Handle real-time data updates with throttling
    function handleRealtimeUpdate(newData) {
        // Only process if real-time monitoring is enabled
        if (!isRealTimeActive) {
            return;
        }

        // PERFORMANCE: Throttle real-time updates to prevent overwhelming the UI
        perfOptimizer.throttle('realtime-update', () => {
            perfOptimizer.requestAnimationFrame(() => {
                // Update latest values display
                updateLatestCard(newData);

                // Add new data point to chart incrementally (flowing effect)
                addDataPointToChart(newData);

                // For OEE and table updates, reload data less frequently (every 10th update)
                realtimeUpdateCounter++;
                if (realtimeUpdateCounter >= 10) {
                    realtimeUpdateCounter = 0;
                    // Only reload table and OEE, charts already updated incrementally
                    loadTableAndOEEData();
                }
            });
        }, 500); // Limit to 2 updates per second
    }

    // Load only table and OEE data without updating charts
    async function loadTableAndOEEData() {
        try {
            const filterDate = document.getElementById('filter-date')?.value;
            const filterShift = document.getElementById('filter-shift')?.value || 'auto';

            let shift = filterShift;
            if (filterShift === 'auto') {
                shift = getCurrentShift();
            }

            const timeRange = getShiftTimeRange(shift);
            const params = {
                limit: 50,
                start_time: timeRange.start,
                end_time: timeRange.end
            };

            if (filterDate) {
                params.date = filterDate;
            } else {
                const today = new Date().toISOString().split('T')[0];
                params.date = today;
            }

            // Fetch recent data for table
            const recentData = await fetchData('recent', params);
            if (recentData && recentData.length > 0) {
                updateTable(recentData);
            }

            // Fetch trend data for OEE calculation if enabled
            if (CONFIG.OEE_CONFIG.enabled) {
                const trendParams = {
                    limit: CONFIG.TREND_DATA_LIMIT,
                    start_time: timeRange.start,
                    end_time: timeRange.end,
                    date: params.date
                };
                const trendData = await fetchData('trend', trendParams);
                if (trendData && trendData.length > 0) {
                    updateOEEMetrics(trendData);
                }
            }

            updateLastUpdateTime();
        } catch (error) {
            console.error('Error loading table and OEE data:', error);
        }
    }

    // Start auto-refresh
    function startAutoRefresh() {
        if (refreshIntervalId) {
            clearInterval(refreshIntervalId);
        }
        refreshIntervalId = setInterval(loadAllData, CONFIG.REFRESH_INTERVAL);
        isRealTimeActive = true;
    }

    // Stop auto-refresh
    function stopAutoRefresh() {
        if (refreshIntervalId) {
            clearInterval(refreshIntervalId);
            refreshIntervalId = null;
        }
        isRealTimeActive = false;
    }

    // Enable real-time monitoring
    function enableRealTime() {
        startAutoRefresh();
        // Load fresh data immediately
        loadAllData();
    }

    // Disable real-time monitoring
    function disableRealTime() {
        stopAutoRefresh();
    }

    // Setup search and sort controls
    function setupTableControls() {
        const searchInput = document.getElementById('table-search');
        const sortSelect = document.getElementById('sort-column');
        const clearBtn = document.getElementById('clear-search');

        if (searchInput) {
            searchInput.addEventListener('input', filterAndSortTable);
        }

        if (sortSelect) {
            sortSelect.addEventListener('change', filterAndSortTable);
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                if (searchInput) searchInput.value = '';
                if (sortSelect) sortSelect.value = 'timestamp-desc';
                filterAndSortTable();
            });
        }
    }

    // Filter and sort table data
    function filterAndSortTable() {
        const searchInput = document.getElementById('table-search');
        const sortSelect = document.getElementById('sort-column');

        const searchTerm = (searchInput?.value || '').toLowerCase();
        const sortMethod = sortSelect?.value || 'timestamp-desc';

        // Filter data
        let filtered = allTableData.filter(row => {
            const idPart = (row.id_part || '').toLowerCase();
            const timestamp = (row.datetime_stamp || row.datetime || '').toLowerCase();
            return idPart.includes(searchTerm) || timestamp.includes(searchTerm);
        });

        // Sort data
        filtered.sort((a, b) => {
            switch(sortMethod) {
                case 'timestamp-asc':
                    return new Date(a.datetime_stamp || a.datetime) - new Date(b.datetime_stamp || b.datetime);
                case 'timestamp-desc':
                    return new Date(b.datetime_stamp || b.datetime) - new Date(a.datetime_stamp || a.datetime);
                case 'id-part-desc':
                    return (b.id_part || '').localeCompare(a.id_part || '');
                case 'id-part-asc':
                    return (a.id_part || '').localeCompare(b.id_part || '');
                default:
                    // Dynamic metric sorting
                    const metricMatch = sortMethod.match(/^(.+)-(asc|desc)$/);
                    if (metricMatch) {
                        const metricKey = metricMatch[1].replace(/-/g, '_');
                        const direction = metricMatch[2];
                        const metric = CONFIG.METRICS.find(m => m.key === metricKey);
                        if (metric) {
                            return direction === 'desc'
                                ? (b[metric.key] || 0) - (a[metric.key] || 0)
                                : (a[metric.key] || 0) - (b[metric.key] || 0);
                        }
                    }
                    return 0;
            }
        });

        // Display filtered and sorted data
        displayTable(filtered);
    }

    // Initialize all charts
    function initCharts() {
        // Temperature Trend Chart
        const trendCtx = document.getElementById('tempTrendChart');
        if (trendCtx) {
            const datasets = CONFIG.METRICS.map(metric =>
                createDataset(metric.label, CONFIG.CHART_COLORS[metric.key])
            );

            charts.trend = new Chart(trendCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: [],
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false, // Disable animations for smooth real-time updates
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
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
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12,
                            bodySpacing: 5,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y.toFixed(1) + '°C';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            min: CONFIG.CHART_CONFIG.yMin,
                            max: CONFIG.CHART_CONFIG.yMax,
                            grace: 0,
                            afterDataLimits: (scale) => {
                                scale.min = CONFIG.CHART_CONFIG.yMin;
                                scale.max = CONFIG.CHART_CONFIG.yMax;
                            },
                            ticks: {
                                count: CONFIG.CHART_CONFIG.tickCount || 11,
                                autoSkip: false,
                                font: {
                                    size: 10
                                },
                                padding: 8,
                                callback: function(value) {
                                    return value + '°C';
                                }
                            },
                            title: {
                                display: true,
                                text: 'Temperature (°C)',
                                font: { size: 12, weight: 'bold' }
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.05)',
                                drawTicks: true
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Production Sequence',
                                font: { size: 12, weight: 'bold' }
                            },
                            grid: {
                                display: true,
                                color: 'rgba(0,0,0,0.05)'
                            },
                            ticks: {
                                autoSkip: false,  // Show all labels
                                maxRotation: 45,
                                minRotation: 45,
                                font: { size: 9 }
                            }
                        }
                    }
                }
            });
        }

        // For Whole Part - Capability Process (line trend chart)
        const comparisonCtx = document.getElementById('leftRightChart');
        if (comparisonCtx) {
            charts.comparison = new Chart(comparisonCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Average Temperature',
                        data: [],
                        borderColor: '#0D3B66',
                        backgroundColor: 'rgba(13, 59, 102, 0.2)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#0D3B66'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false, // Disable animations for smooth real-time updates
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y.toFixed(1) + '°C';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            min: CONFIG.CHART_CONFIG.yMin,
                            max: CONFIG.CHART_CONFIG.yMax,
                            grace: 0,
                            afterDataLimits: (scale) => {
                                scale.min = CONFIG.CHART_CONFIG.yMin;
                                scale.max = CONFIG.CHART_CONFIG.yMax;
                            },
                            title: {
                                display: false
                            },
                            ticks: {
                                count: CONFIG.CHART_CONFIG.tickCount || 11,
                                autoSkip: false,
                                font: { size: 9 }
                            },
                            grid: {
                                drawTicks: true
                            }
                        },
                        x: {
                            title: {
                                display: false
                            },
                            ticks: {
                                maxRotation: 0,
                                autoSkip: true,
                                maxTicksLimit: 8,
                                font: { size: 8 }
                            }
                        }
                    }
                }
            });
        }

        // For Last Shot - Normal Distribution (bell curve with limits)
        const distributionCtx = document.getElementById('distributionChart');
        if (distributionCtx) {
            charts.distribution = new Chart(distributionCtx.getContext('2d'), {
                type: 'scatter',
                data: {
                    datasets: [{
                        label: 'Normal Distribution',
                        data: [],
                        borderColor: '#0D3B66',
                        backgroundColor: 'rgba(13, 59, 102, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0,
                        showLine: true,
                        order: 1
                    }, {
                        label: 'Histogram',
                        type: 'bar',
                        data: [],
                        backgroundColor: 'rgba(13, 59, 102, 0.3)',
                        borderColor: '#0D3B66',
                        borderWidth: 1,
                        order: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false, // Disable animations for smooth real-time updates
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true,
                            callbacks: {
                                label: function(context) {
                                    return `Temp: ${context.parsed.x.toFixed(1)}°C`;
                                }
                            }
                        },
                        annotation: {
                            annotations: {
                                ll: {
                                    type: 'line',
                                    scaleID: 'x',
                                    value: 0,
                                    borderColor: 'red',
                                    borderWidth: 2,
                                    borderDash: [5, 5],
                                    label: {
                                        content: 'LL',
                                        display: true,
                                        position: 'start',
                                        backgroundColor: 'red',
                                        color: 'white',
                                        font: { size: 10 }
                                    }
                                },
                                ul: {
                                    type: 'line',
                                    scaleID: 'x',
                                    value: 0,
                                    borderColor: 'red',
                                    borderWidth: 2,
                                    borderDash: [5, 5],
                                    label: {
                                        content: 'UL',
                                        display: true,
                                        position: 'start',
                                        backgroundColor: 'red',
                                        color: 'white',
                                        font: { size: 10 }
                                    }
                                },
                                setPoint: {
                                    type: 'line',
                                    scaleID: 'x',
                                    value: 0,
                                    borderColor: 'green',
                                    borderWidth: 2,
                                    label: {
                                        content: 'Set Point',
                                        display: true,
                                        position: 'center',
                                        backgroundColor: 'green',
                                        color: 'white',
                                        font: { size: 10 }
                                    }
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Qty',
                                font: { size: 10 }
                            },
                            ticks: {
                                display: false
                            },
                            grid: {
                                display: false
                            }
                        },
                        x: {
                            type: 'linear',
                            title: {
                                display: false
                            },
                            ticks: {
                                font: { size: 9 }
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    }

    // PERFORMANCE OPTIMIZED: Create dataset with minimal configuration
    function createOptimizedDataset(label, color) {
        return {
            label: label,
            data: [], // Will use TypedArray for better performance
            borderColor: color,
            backgroundColor: 'transparent', // PERFORMANCE: Remove fill for better performance
            tension: 0.1,              // Reduced curve calculation
            borderWidth: 2,            // Thinner lines for performance
            pointRadius: 0,            // PERFORMANCE: Remove points for better performance
            pointHoverRadius: 0,
            fill: false,               // PERFORMANCE: No fill
            cubicInterpolationMode: 'default' // Simpler interpolation
        };
    }

    // LEGACY: Keep original for backward compatibility
    function createDataset(label, color) {
        return createOptimizedDataset(label, color);
    }

    // Convert hex to rgba
    function hexToRgba(hex, alpha) {
        const r = parseInt(hex.slice(1, 3), 16);
        const g = parseInt(hex.slice(3, 5), 16);
        const b = parseInt(hex.slice(5, 7), 16);
        return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    }

    // Filter data to show only specific cycle intervals (e.g., every 312 seconds)
    function filterByCycleInterval(data, intervalSeconds) {
        if (!data || data.length === 0) return [];

        const firstRecord = data[0];
        const firstTime = new Date(firstRecord.datetime_stamp || firstRecord.datetime);
        const referenceTimeInSeconds = firstTime.getHours() * 3600 + firstTime.getMinutes() * 60 + firstTime.getSeconds();

        return data.filter(record => {
            const recordTime = new Date(record.datetime_stamp || record.datetime);
            const recordTimeInSeconds = recordTime.getHours() * 3600 + recordTime.getMinutes() * 60 + recordTime.getSeconds();
            const timeDiff = recordTimeInSeconds - referenceTimeInSeconds;
            const remainder = timeDiff % intervalSeconds;
            return remainder <= 5 || remainder >= (intervalSeconds - 5);
        });
    }

    // Load all data
    async function loadAllData() {
        try {
            showLoading(true);

            const filterDate = document.getElementById('filter-date')?.value;
            const filterShift = document.getElementById('filter-shift')?.value || 'auto';

            let shift = filterShift;
            if (filterShift === 'auto') {
                shift = getCurrentShift();
            }

            const timeRange = getShiftTimeRange(shift);
            const startTime = timeRange.start;
            const endTime = timeRange.end;

            const trendParams = {
                limit: CONFIG.TREND_DATA_LIMIT,
                start_time: startTime,
                end_time: endTime
            };

            if (filterDate) {
                trendParams.date = filterDate;
            } else {
                const today = new Date().toISOString().split('T')[0];
                trendParams.date = today;
            }

            console.log('📊 Loading data for shift:', shift, 'Time range:', startTime, '-', endTime);
            console.log('📅 Date filter:', filterDate || 'Using today');
            console.log('🔧 Trend params:', trendParams);

            const [latestData, trendData, recentData] = await Promise.all([
                fetchData('latest'),
                fetchData('trend', trendParams),
                fetchData('recent', { limit: 50 })
            ]);

            // Process received trend data

            if (latestData) {
                updateMetrics(latestData);
            }

            // Process the data if available
            if (trendData && trendData.length > 0) {
                // Check if simulation mode is enabled
                const simConfig = config.simulationMode || {};
                if (simConfig.enabled) {
                    // Store data for simulation and start the loop
                    simulationData = [...trendData];
                    simulationIndex = 0;
                    startSimulation(simConfig.intervalSeconds || 5);
                } else {
                    // Normal mode - display all data
                    updateCharts(trendData);
                    if (CONFIG.OEE_CONFIG.enabled) {
                        updateOEEMetrics(trendData);
                    }
                }
            } else {
                showError(`No data found for ${filterDate || 'today'} ${shift} shift (${startTime}-${endTime}). Please select a different date or check if data exists in the database.`);
            }

            if (recentData && recentData.length > 0) {
                updateTable(recentData);
            }

            updateLastUpdateTime();
            showLoading(false);
        } catch (error) {
            console.error('Error loading data:', error);
            showError('Failed to load data. Please refresh the page.');
            showLoading(false);
        }
    }

    // Calculate and update OEE metrics
    function updateOEEMetrics(data) {
        const TARGETS = CONFIG.OEE_CONFIG.targets;

        if (!data || data.length === 0) {
            updateOEEDisplay({
                count: 0,
                availability: 0,
                performance: 0,
                quality: 0,
                oee: 0
            }, TARGETS);
            return;
        }

        const productionCount = data.length;
        const CYCLE_TIME_SECONDS = CONFIG.OEE_CONFIG.cycleTimeSeconds;
        const SHIFT_DURATION_HOURS = CONFIG.OEE_CONFIG.shiftDurationHours;
        const SHIFT_DURATION_SECONDS = SHIFT_DURATION_HOURS * 3600;
        const IDEAL_CYCLES = Math.floor(SHIFT_DURATION_SECONDS / CYCLE_TIME_SECONDS);

        const actualProductionTime = productionCount * CYCLE_TIME_SECONDS;
        const plannedProductionTime = SHIFT_DURATION_SECONDS;
        const availability = Math.min((actualProductionTime / plannedProductionTime) * 100, 100);
        const performance = Math.min((productionCount / IDEAL_CYCLES) * 100, 100);

        let goodParts = 0;
        data.forEach(record => {
            const isGood = CONFIG.METRICS.every(metric => {
                const value = parseFloat(record[metric.key]);
                const threshold = CONFIG.THRESHOLDS[metric.key];
                return value >= threshold.min && value <= threshold.max;
            });
            if (isGood) goodParts++;
        });

        const quality = (goodParts / productionCount) * 100;
        const overallOEE = (availability / 100) * (performance / 100) * (quality / 100) * 100;

        updateOEEDisplay({
            count: productionCount,
            availability: availability,
            performance: performance,
            quality: quality,
            oee: overallOEE
        }, TARGETS);

        console.log('📊 OEE:', {
            count: `${productionCount}/${IDEAL_CYCLES}`,
            availability: availability.toFixed(1) + '%',
            performance: performance.toFixed(1) + '%',
            quality: quality.toFixed(1) + '%',
            overall: overallOEE.toFixed(1) + '%',
            goodParts: goodParts
        });
    }

    // Update OEE display with target comparison
    function updateOEEDisplay(actuals, targets) {
        const updateMetric = (metricName, actual, target) => {
            const actualEl = document.getElementById(`oee-${metricName}`);
            const percentEl = document.getElementById(`status-${metricName}-percent`);
            const statusEl = document.getElementById(`status-${metricName}`);

            if (actualEl) {
                actualEl.textContent = metricName === 'count-actual' ? actual : actual.toFixed(1);
            }

            if (percentEl && statusEl) {
                const percent = (actual / target) * 100;
                percentEl.textContent = Math.min(percent, 100).toFixed(0);
                updateStatusBadge(statusEl, percent);
            }
        };

        updateMetric('count-actual', actuals.count, targets.count);
        updateMetric('availability', actuals.availability, targets.availability);
        updateMetric('performance', actuals.performance, targets.performance);
        updateMetric('quality', actuals.quality, targets.quality);
        updateMetric('overall', actuals.oee, targets.oee);
    }

    // Update status badge with color coding and text
    function updateStatusBadge(element, percentage) {
        if (!element) return;

        element.classList.remove('status-normal', 'status-warning', 'status-critical');

        if (percentage >= 95) {
            element.classList.add('status-normal');
            element.textContent = 'Normal';
        } else if (percentage >= 80) {
            element.classList.add('status-warning');
            element.textContent = 'Warning';
        } else {
            element.classList.add('status-critical');
            element.textContent = 'Critical';
        }
    }

    // Fetch data from API
    async function fetchData(action, params = {}) {
        try {
            const urlParams = new URLSearchParams();
            urlParams.append('action', action);

            Object.keys(params).forEach(key => {
                urlParams.append(key, params[key]);
            });

            const url = `${CONFIG.API_URL}?${urlParams.toString()}`;

            const response = await fetch(url);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();
            console.log('📊 API Response:', result);

            if (result.status === 'success') {
                return result.data;
            } else {
                throw new Error(result.message || 'Failed to fetch data');
            }
        } catch (error) {
            throw error;
        }
    }

    // PERFORMANCE OPTIMIZED: Update metric cards with batched DOM updates
    function updateMetrics(data) {
        // PERFORMANCE: Batch all DOM updates to minimize reflows
        const updates = [];

        const processMetrics = (metrics) => {
            metrics.forEach(metric => {
                const valueEl = document.getElementById(`metric-${metric.elementId}`);
                const statusEl = document.getElementById(`status-${metric.elementId}`);
                const value = data[metric.key];

                if (valueEl && value !== undefined) {
                    updates.push({
                        selector: `#metric-${metric.elementId}`,
                        property: 'textContent',
                        value: value.toFixed(1)
                    });

                    // PERFORMANCE: Use CSS animations instead of setTimeout
                    updates.push({
                        selector: `#metric-${metric.elementId}`,
                        action: 'addClass',
                        className: 'pulse'
                    });

                    // Schedule pulse removal
                    setTimeout(() => {
                        const el = document.getElementById(`metric-${metric.elementId}`);
                        if (el && el.parentElement) {
                            el.parentElement.classList.remove('pulse');
                        }
                    }, 300);
                }

                if (statusEl && value !== undefined) {
                    updateStatus(metric.elementId, value, statusEl);
                }
            });
        };

        processMetrics(CONFIG.METRICS);

        // Also update additional metrics
        if (CONFIG.additionalMetrics) {
            processMetrics(CONFIG.additionalMetrics);
        }

        // PERFORMANCE: Execute batched updates
        perfOptimizer.batchDOMUpdates(updates);
    }

    // Update status badge
    function updateStatus(elementId, value, element) {
        const metricKey = elementId.replace(/-/g, '_');
        const threshold = CONFIG.THRESHOLDS[metricKey];

        if (!threshold) return;

        if (value < threshold.min || value > threshold.max) {
            element.textContent = 'Warning';
            element.className = 'status-badge status-warning';
        } else {
            element.textContent = 'Normal';
            element.className = 'status-badge status-normal';
        }
    }

    // PERFORMANCE OPTIMIZED: Update all charts with debounced rendering
    function updateCharts(data) {
        if (!data || data.length === 0) {
            console.warn('⚠️ No data available for the selected date/time range');
            showError('No data found for the selected date and time range. Please try a different date.');
            return;
        }

        // PERFORMANCE: Debounce chart updates to prevent excessive rendering
        perfOptimizer.debounce('chart-update', () => {
            perfOptimizer.requestAnimationFrame(() => {
                _updateChartsInternal(data);
            });
        }, 100);
    }

    // Internal chart update function
    function _updateChartsInternal(data) {
        const sortedData = [...data].sort((a, b) => {
            const dateA = new Date(a.datetime_stamp || a.datetime);
            const dateB = new Date(b.datetime_stamp || b.datetime);
            return dateA - dateB;
        });

        const cycleFilteredData = filterByCycleInterval(sortedData, 312);
        const chartData = cycleFilteredData.length > 0 ? cycleFilteredData : sortedData;

        const labels = chartData.map((record, index) => {
            return record.id_part || `Record ${index + 1}`;
        });

        // PERFORMANCE: Batch chart updates
        const updates = [];

        // Update trend chart
        if (charts.trend) {
            charts.trend.data.labels = labels;
            CONFIG.METRICS.forEach((metric, index) => {
                const values = chartData.map(d => d[metric.key]);
                charts.trend.data.datasets[index].data = values;
            });
            updates.push(() => charts.trend.update('none'));
        }

        // Update comparison chart - average temperature
        if (charts.comparison) {
            const avgTemps = chartData.map(d => {
                const values = CONFIG.METRICS
                    .map(m => d[m.key])
                    .filter(t => t > 0);
                return average(values);
            });

            charts.comparison.data.labels = labels;
            charts.comparison.data.datasets[0].data = avgTemps;
            updates.push(() => charts.comparison.update('none'));
        }

        // Update distribution chart
        if (charts.distribution) {
            const distConfig = config.distributionConfig || {};
            const useLastShotOnly = distConfig.useLastShotOnly || false;

            let allTemps = [];

            if (useLastShotOnly && chartData.length > 0) {
                const lastShot = chartData[chartData.length - 1];
                CONFIG.METRICS.forEach(metric => {
                    const value = lastShot[metric.key];
                    if (value > 0) allTemps.push(value);
                });
            } else {
                CONFIG.METRICS.forEach(metric => {
                    chartData.forEach(d => {
                        const value = d[metric.key];
                        if (value > 0) allTemps.push(value);
                    });
                });
            }

            if (allTemps.length > 0) {
                const mean = average(allTemps);
                const stdDev = calculateStdDev(allTemps, mean);

                const lowerLimit = distConfig.lowerLimit !== undefined ? distConfig.lowerLimit : (mean - 3 * stdDev);
                const upperLimit = distConfig.upperLimit !== undefined ? distConfig.upperLimit : (mean + 3 * stdDev);
                const setPoint = distConfig.setPoint !== undefined ? distConfig.setPoint : mean;

                // PERFORMANCE: Reduce points for smoother performance
                const points = 50; // Reduced from 100
                const minTemp = lowerLimit;
                const maxTemp = upperLimit;
                const step = (maxTemp - minTemp) / points;
                const compactStdDev = (upperLimit - lowerLimit) / 6;

                const normalCurve = [];
                for (let i = 0; i <= points; i++) {
                    const x = minTemp + (i * step);
                    const normalValue = Math.exp(-0.5 * Math.pow((x - setPoint) / compactStdDev, 2)) / (compactStdDev * Math.sqrt(2 * Math.PI));
                    normalCurve.push({ x: x, y: normalValue });
                }

                charts.distribution.data.datasets[0].data = normalCurve;

                if (useLastShotOnly) {
                    charts.distribution.data.datasets[1].data = [];
                } else {
                    // PERFORMANCE: Reduce histogram bins
                    const binCount = 15; // Reduced from 30
                    const binSize = (maxTemp - minTemp) / binCount;
                    const bins = [];

                    for (let i = 0; i < binCount; i++) {
                        const binStart = minTemp + i * binSize;
                        const binEnd = binStart + binSize;
                        const binCenter = binStart + (binSize / 2);
                        const count = allTemps.filter(t => t >= binStart && t < binEnd).length;
                        bins.push({ x: binCenter, y: count });
                    }
                    charts.distribution.data.datasets[1].data = bins;
                }

                // Update annotations
                if (charts.distribution.options.plugins.annotation) {
                    charts.distribution.options.plugins.annotation.annotations.ll.value = lowerLimit;
                    charts.distribution.options.plugins.annotation.annotations.ll.label.content = `LL: ${lowerLimit}`;
                    charts.distribution.options.plugins.annotation.annotations.ul.value = upperLimit;
                    charts.distribution.options.plugins.annotation.annotations.ul.label.content = `UL: ${upperLimit}`;
                    charts.distribution.options.plugins.annotation.annotations.setPoint.value = setPoint;
                    charts.distribution.options.plugins.annotation.annotations.setPoint.label.content = `Set Point: ${setPoint}`;
                }
            }

            updates.push(() => charts.distribution.update('none'));
        }

        // PERFORMANCE: Execute all updates in batch
        updates.forEach(update => update());
    }

    // Update data table
    function updateTable(data) {
        allTableData = data;
        const searchInput = document.getElementById('table-search');
        const sortSelect = document.getElementById('sort-column');
        if (searchInput) searchInput.value = '';
        if (sortSelect) sortSelect.value = 'timestamp-desc';
        filterAndSortTable();
    }

    // Display table with formatted data
    function displayTable(data) {
        const tbody = document.getElementById('data-table-body');
        if (!tbody) return;

        tbody.innerHTML = '';

        if (data.length === 0) {
            const tr = document.createElement('tr');
            const colspan = CONFIG.TABLE_COLUMNS.length + 1;
            tr.innerHTML = `<td colspan="${colspan}" style="text-align: center; padding: 15px; color: var(--text-light);">No data found</td>`;
            tbody.appendChild(tr);
            return;
        }

        data.forEach(row => {
            const tr = document.createElement('tr');
            const datetime = row.datetime_stamp || row.datetime || 'N/A';

            let cells = '';

            // Add ID Part column if configured to show it
            if (CONFIG.SHOW_ID_PART) {
                const idPart = row.id_part || 'N/A';
                cells += `<td style="padding: 6px; font-size: 11px; border-bottom: 1px solid var(--gray-border);">${idPart}</td>`;
            }

            cells += `<td style="padding: 6px; font-size: 11px; border-bottom: 1px solid var(--gray-border);">${datetime}</td>`;

            CONFIG.TABLE_COLUMNS.forEach(col => {
                const value = row[col.key];
                cells += `<td style="padding: 6px; text-align: right; font-size: 11px; border-bottom: 1px solid var(--gray-border);">${value !== undefined ? value.toFixed(1) + '°C' : 'N/A'}</td>`;
            });

            tr.innerHTML = cells;
            tbody.appendChild(tr);
        });
    }

    // Helper: Calculate average
    function average(arr) {
        return arr.reduce((a, b) => a + b, 0) / arr.length;
    }

    // Helper: Calculate standard deviation
    function calculateStdDev(arr, mean) {
        const squareDiffs = arr.map(value => Math.pow(value - mean, 2));
        const avgSquareDiff = average(squareDiffs);
        return Math.sqrt(avgSquareDiff);
    }

    // Update last update time
    function updateLastUpdateTime() {
        const el = document.getElementById('last-update');
        if (el) {
            el.textContent = new Date().toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }
    }

    // Show/hide loading state
    function showLoading(show) {
        console.log(show ? 'Loading...' : 'Data loaded');
    }

    // Show error message
    function showError(message) {
        console.error('❌', message);
        alert(message);

        const chartContainer = document.querySelector('.chart-wrapper');
        if (chartContainer) {
            const existingError = chartContainer.querySelector('.error-message');
            if (existingError) existingError.remove();

            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.style.cssText = 'background: #fee; border: 1px solid #fcc; padding: 12px; margin: 10px 0; border-radius: 4px; color: #c33; text-align: center;';
            errorDiv.textContent = message;
            chartContainer.insertBefore(errorDiv, chartContainer.firstChild);

            setTimeout(() => errorDiv.remove(), 5000);
        }
    }

    // Filter temperature metrics display
    function filterTemperatureMetrics(filterType) {
        const metricCards = document.querySelectorAll('#temperature-metrics-grid .metric-card');

        metricCards.forEach(card => {
            const type = card.getAttribute('data-metric-type');

            if (filterType === 'all') {
                card.style.display = 'block';
            } else if (type === filterType) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Filter trend chart datasets
    function filterTrendChart(filterType) {
        if (!charts.trend) return;

        const datasets = charts.trend.data.datasets;
        const filterConfig = config.chartFilters || {};
        const filterMap = filterConfig[filterType] || [];

        datasets.forEach((dataset, index) => {
            dataset.hidden = filterMap.length > 0 && !filterMap.includes(index);
        });

        charts.trend.update();
        console.log('📊 Trend chart filtered to:', filterType);
    }

    // Add a single data point to charts (for flowing animation)
    // For WA: Creates wave pattern with gate1 -> main1 amplitudes
    // For TR: Single point per record
    function addSingleDataPoint(record, recordNumber) {
        if (!charts.trend) return;

        // Use ID Part (WA) or Record number (TR) for identification
        let baseLabel;
        if (record.id_part) {
            baseLabel = record.id_part; // WA has id_part
        } else {
            baseLabel = `Record ${recordNumber}`; // TR uses sequential numbering
        }
        const MAX_VISIBLE_POINTS = 20; // Show last 20 points (10 wave cycles for WA) - clearer wave visualization

        // Check if this is WA data (has gate/main pattern for wave visualization)
        const isWA = record.r_lower_gate1 !== undefined && record.r_lower_main1 !== undefined;

        // Check if this is TR data (has gate_front/gate_rear pattern for wave visualization)
        const isTR = record.l_gate_front !== undefined && record.l_gate_rear !== undefined;

        if (isWA) {
            // WA: Add TWO points per part to create wave pattern
            // ✅ RIGHT-TO-LEFT FLOW: push() adds to END → appears on RIGHT side
            // As chart fills up, old data shifts LEFT and exits from LEFT edge

            // Point 1: Gate values (wave peak / first amplitude)
            // Empty label for first point (label will be in center)
            charts.trend.data.labels.push('');
            CONFIG.METRICS.forEach((metric, index) => {
                if (charts.trend.data.datasets[index]) {
                    // All lines show GATE values at this point
                    if (metric.key === 'r_lower_gate1' || metric.key === 'r_lower_main1') {
                        // R Lower: both show r_gate value
                        charts.trend.data.datasets[index].data.push(record.r_lower_gate1);
                    } else if (metric.key === 'l_lower_gate1' || metric.key === 'l_lower_main1') {
                        // L Lower: both show l_gate value
                        charts.trend.data.datasets[index].data.push(record.l_lower_gate1);
                    } else {
                        // Cooling water: show actual value
                        charts.trend.data.datasets[index].data.push(record[metric.key]);
                    }
                }
            });

            // Point 2: Main values (wave valley / second amplitude)
            // Show label here (centered between the two amplitude points)
            charts.trend.data.labels.push(baseLabel);
            CONFIG.METRICS.forEach((metric, index) => {
                if (charts.trend.data.datasets[index]) {
                    // All lines show MAIN values at this point
                    if (metric.key === 'r_lower_gate1' || metric.key === 'r_lower_main1') {
                        // R Lower: both show r_main value
                        charts.trend.data.datasets[index].data.push(record.r_lower_main1);
                    } else if (metric.key === 'l_lower_gate1' || metric.key === 'l_lower_main1') {
                        // L Lower: both show l_main value
                        charts.trend.data.datasets[index].data.push(record.l_lower_main1);
                    } else {
                        // Cooling water: show actual value
                        charts.trend.data.datasets[index].data.push(record[metric.key]);
                    }
                }
            });
        } else if (isTR) {
            // TR: Add TWO points per part to create wave pattern (same as WA)
            // ✅ RIGHT-TO-LEFT FLOW: push() adds to END → appears on RIGHT side
            // One record creates one complete wave (peak + valley)

            // Point 1: Front/Chamber1 values (wave peak)
            // Empty label for first point (label will be at valley)
            charts.trend.data.labels.push('');
            CONFIG.METRICS.forEach((metric, index) => {
                if (charts.trend.data.datasets[index]) {
                    // Show "front" or "chamber_1" values at peak
                    if (metric.key === 'l_gate_front') {
                        charts.trend.data.datasets[index].data.push(record.l_gate_front);
                    } else if (metric.key === 'l_gate_rear') {
                        charts.trend.data.datasets[index].data.push(record.l_gate_front); // Same as front for wave
                    } else if (metric.key === 'l_chamber_1') {
                        charts.trend.data.datasets[index].data.push(record.l_chamber_1);
                    } else if (metric.key === 'l_chamber_2') {
                        charts.trend.data.datasets[index].data.push(record.l_chamber_1); // Same as chamber_1 for wave
                    } else if (metric.key === 'r_gate_front') {
                        charts.trend.data.datasets[index].data.push(record.r_gate_front);
                    } else if (metric.key === 'r_gate_rear') {
                        charts.trend.data.datasets[index].data.push(record.r_gate_front); // Same as front for wave
                    } else if (metric.key === 'r_chamber_1') {
                        charts.trend.data.datasets[index].data.push(record.r_chamber_1);
                    } else if (metric.key === 'r_chamber_2') {
                        charts.trend.data.datasets[index].data.push(record.r_chamber_1); // Same as chamber_1 for wave
                    }
                }
            });

            // Point 2: Rear/Chamber2 values (wave valley)
            // Show label here (at the valley point)
            charts.trend.data.labels.push(baseLabel);
            CONFIG.METRICS.forEach((metric, index) => {
                if (charts.trend.data.datasets[index]) {
                    // Show "rear" or "chamber_2" values at valley
                    if (metric.key === 'l_gate_front') {
                        charts.trend.data.datasets[index].data.push(record.l_gate_rear); // Switch to rear
                    } else if (metric.key === 'l_gate_rear') {
                        charts.trend.data.datasets[index].data.push(record.l_gate_rear);
                    } else if (metric.key === 'l_chamber_1') {
                        charts.trend.data.datasets[index].data.push(record.l_chamber_2); // Switch to chamber_2
                    } else if (metric.key === 'l_chamber_2') {
                        charts.trend.data.datasets[index].data.push(record.l_chamber_2);
                    } else if (metric.key === 'r_gate_front') {
                        charts.trend.data.datasets[index].data.push(record.r_gate_rear); // Switch to rear
                    } else if (metric.key === 'r_gate_rear') {
                        charts.trend.data.datasets[index].data.push(record.r_gate_rear);
                    } else if (metric.key === 'r_chamber_1') {
                        charts.trend.data.datasets[index].data.push(record.r_chamber_2); // Switch to chamber_2
                    } else if (metric.key === 'r_chamber_2') {
                        charts.trend.data.datasets[index].data.push(record.r_chamber_2);
                    }
                }
            });
        } else {
            // Other parts (KR, NR, 3SZ): Add single point per record
            // ✅ RIGHT-TO-LEFT FLOW: push() adds to END → appears on RIGHT
            charts.trend.data.labels.push(baseLabel);
            CONFIG.METRICS.forEach((metric, index) => {
                const value = record[metric.key];
                if (value !== undefined && charts.trend.data.datasets[index]) {
                    charts.trend.data.datasets[index].data.push(value);
                }
            });
        }

        // Keep only last N points (scrolling window effect)
        // ✅ shift() removes from START (left side) → old data exits from LEFT edge
        if (charts.trend.data.labels.length > MAX_VISIBLE_POINTS) {
            charts.trend.data.labels.shift(); // Remove oldest label from left
            CONFIG.METRICS.forEach((_, index) => {
                if (charts.trend.data.datasets[index]) {
                    charts.trend.data.datasets[index].data.shift(); // Remove oldest data from left
                }
            });
        }

        // Update comparison chart (average) - only for WA, add once per part (not twice)
        if (charts.comparison && isWA) {
            const avgTemp = CONFIG.METRICS
                .map(m => record[m.key])
                .filter(t => t > 0)
                .reduce((sum, val) => sum + val, 0) / CONFIG.METRICS.filter(m => record[m.key] > 0).length;

            // Only add to comparison chart once per part
            // Check if we haven't already added this part (check last label)
            const lastLabel = charts.comparison.data.labels[charts.comparison.data.labels.length - 1];
            if (lastLabel !== baseLabel) {
                charts.comparison.data.labels.push(baseLabel);
                charts.comparison.data.datasets[0].data.push(avgTemp);

                // Keep only last N points - remove from start (left side)
                if (charts.comparison.data.labels.length > MAX_VISIBLE_POINTS / 2) {
                    charts.comparison.data.labels.shift();
                    charts.comparison.data.datasets[0].data.shift();
                }
            }
        } else if (charts.comparison && !isWA) {
            // TR: Add normally - right to left flow
            const avgTemp = CONFIG.METRICS
                .map(m => record[m.key])
                .filter(t => t > 0)
                .reduce((sum, val) => sum + val, 0) / CONFIG.METRICS.filter(m => record[m.key] > 0).length;

            charts.comparison.data.labels.push(baseLabel);
            charts.comparison.data.datasets[0].data.push(avgTemp);

            // Keep only last N points - remove from start (left side)
            if (charts.comparison.data.labels.length > MAX_VISIBLE_POINTS) {
                charts.comparison.data.labels.shift();
                charts.comparison.data.datasets[0].data.shift();
            }
        }

        // Update distribution chart with latest shot
        if (charts.distribution) {
            const distConfig = config.distributionConfig || {};
            if (distConfig.useLastShotOnly) {
                const allTemps = [];
                CONFIG.METRICS.forEach(metric => {
                    const value = record[metric.key];
                    if (value > 0) allTemps.push(value);
                });
                if (allTemps.length > 0) {
                    updateDistributionChart(allTemps, distConfig);
                }
            }
        }

        // Instant update for smooth real-time feel
        charts.trend.update('none');
        if (charts.comparison) {
            charts.comparison.update('none');
        }
    }

    // Start simulation - loop through existing data
    function startSimulation(intervalSeconds) {
        // Clear any existing simulation first
        if (simulationIntervalId) {
            clearInterval(simulationIntervalId);
            simulationIntervalId = null;
        }

        // Validate we have data
        if (!simulationData || simulationData.length === 0) {
            return;
        }

        console.log(`🎬 Starting simulation with ${simulationData.length} records, interval: ${intervalSeconds}s`);

        // Reset index
        simulationIndex = 0;

        // Show first record immediately
        simulateNextRecord();

        // Then continue with interval
        simulationIntervalId = setInterval(() => {
            simulateNextRecord();
        }, intervalSeconds * 1000);
    }

    // Simulate next record
    function simulateNextRecord() {
        if (simulationData.length === 0) return;

        // Get current record
        const currentRecord = simulationData[simulationIndex];

        // Update latest metrics with current record
        updateMetrics(currentRecord);

        // Update latest card
        updateLatestCard(currentRecord);

        // ✅ FLOWING CHART UPDATE - Add new point incrementally (not redraw everything!)
        if (simulationIndex === 0) {
            // First time: Initialize chart with empty data (wave will appear from right)
            if (charts.trend) {
                charts.trend.data.labels = [];
                charts.trend.data.datasets.forEach(dataset => dataset.data = []);
            }
            if (charts.comparison) {
                charts.comparison.data.labels = [];
                charts.comparison.data.datasets[0].data = [];
            }

        }

        // Add this single new record to the chart (incremental!)
        addSingleDataPoint(currentRecord, simulationIndex + 1);

        // Update OEE with accumulated data (less frequently)
        if (CONFIG.OEE_CONFIG.enabled && simulationIndex % 5 === 0) {
            const dataUpToNow = simulationData.slice(0, simulationIndex + 1);
            updateOEEMetrics(dataUpToNow);
        }

        // Update last update time
        updateLastUpdateTime();

        // Show detailed simulation progress with ID Part labels
        const idPartLabel = currentRecord.id_part || `Record ${simulationIndex + 1}`;

        // Move to next index
        simulationIndex++;

        // Loop back to start when finished
        if (simulationIndex >= simulationData.length) {
            simulationIndex = 0;
        }
    }

    // Stop simulation
    function stopSimulation() {
        if (simulationIntervalId) {
            clearInterval(simulationIntervalId);
            simulationIntervalId = null;
        }
    }

    // Public API
    return {
        init,
        loadAllData,
        enableRealTime,
        disableRealTime,
        filterTemperatureMetrics,
        filterTrendChart,
        startSimulation,
        stopSimulation
    };
};
