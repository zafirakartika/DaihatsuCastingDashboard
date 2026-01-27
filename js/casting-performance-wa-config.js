/**
 * Casting Performance Configuration for WA Part
 * Contains part-specific settings for metrics, thresholds, and chart colors
 */

const CastingPerformance = CastingPerformanceCore({
    partName: 'WA',
    apiUrl: 'http://127.0.0.1:8000/api/casting-data',

    // Maximum data points to show (10 waves = 20 points: 10 peaks + 10 valleys)
    trendDataLimit: 20,

    // Real-time simulation mode (loops through existing data)
    simulationMode: {
        enabled: true,          // ✅ ENABLED - Data flows gradually with wave pattern!
        intervalSeconds: 2      // New part (2 amplitude points) every 2 seconds
    },

    // Distribution chart configuration for WA (Last Shot - single part)
    distributionConfig: {
        useLastShotOnly: true,
        lowerLimit: 490,
        setPoint: 500,
        upperLimit: 510
    },

    // Chart Y-axis range configuration (tighter range for better visibility)
    chartConfig: {
        yMin: 460,
        yMax: 540,
        tickCount: 9  // Every 10°C: 460, 470, 480, 490, 500, 510, 520, 530, 540
    },

    // Metric definitions (cooling_water excluded from trend chart, shown in metrics only)
    metrics: [
        { key: 'r_lower_gate1', elementId: 'r-gate', label: 'R Lower Gate 1' },
        { key: 'r_lower_main1', elementId: 'r-main', label: 'R Lower Main 1' },
        { key: 'l_lower_gate1', elementId: 'l-gate', label: 'L Lower Gate 1' },
        { key: 'l_lower_main1', elementId: 'l-main', label: 'L Lower Main 1' }
    ],

    // Additional metrics (shown in metric cards but not in trend chart)
    additionalMetrics: [
        { key: 'cooling_water', elementId: 'cooling', label: 'Cooling Water' }
    ],

    // Chart colors for each metric
    chartColors: {
        r_lower_gate1: '#F39C12',
        r_lower_main1: '#E74C3C',
        l_lower_gate1: '#3498DB',
        l_lower_main1: '#9B59B6',
        cooling_water: '#1ABC9C'
    },

    // Temperature thresholds for quality assessment
    thresholds: {
        r_lower_gate1: { min: 480, max: 520 },
        r_lower_main1: { min: 510, max: 525 },
        l_lower_gate1: { min: 480, max: 520 },
        l_lower_main1: { min: 500, max: 515 },
        cooling_water: { min: 30, max: 40 }
    },

    // Table column configuration
    tableColumns: [
        { key: 'r_lower_gate1', label: 'R Lower Gate 1' },
        { key: 'r_lower_main1', label: 'R Lower Main 1' },
        { key: 'l_lower_gate1', label: 'L Lower Gate 1' },
        { key: 'l_lower_main1', label: 'L Lower Main 1' },
        { key: 'cooling_water', label: 'Cooling Water' }
    ],

    // Chart filter mappings (dataset indices - cooling_water removed)
    chartFilters: {
        all: [0, 1, 2, 3],
        gate: [0, 2],           // R Gate, L Gate
        main: [1, 3],           // R Main, L Main
        right: [0, 1],          // R Gate, R Main
        left: [2, 3]            // L Gate, L Main
    }
});

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', CastingPerformance.init);
} else {
    CastingPerformance.init();
}
