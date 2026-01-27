/**
 * Casting Performance Configuration for TR Part
 * Contains part-specific settings for metrics, thresholds, and chart colors
 */

const CastingPerformanceTR = CastingPerformanceCore({
    partName: 'TR',
    apiUrl: 'http://127.0.0.1:8000/api/casting-data-tr',

    // TR doesn't have ID Part column
    showIdPart: false,

    // Maximum data points to show (10 waves = 20 points: 10 peaks + 10 valleys)
    trendDataLimit: 20,

    // Real-time simulation mode (loops through existing data)
    simulationMode: {
        enabled: true,          // ✅ ENABLED - Data flows gradually with wave pattern!
        intervalSeconds: 2      // New part (2 amplitude points) every 2 seconds
    },

    // Distribution chart configuration for TR (Last Shot - single part)
    distributionConfig: {
        useLastShotOnly: true,
        lowerLimit: 490,
        setPoint: 520,
        upperLimit: 540
    },

    // Chart Y-axis range configuration (optimized range for TR data 200-400°C)
    chartConfig: {
        yMin: 200,
        yMax: 400,
        tickCount: 11  // Every 20°C: 200, 220, 240, 260, 280, 300, 320, 340, 360, 380, 400
    },

    // Metric definitions
    metrics: [
        { key: 'l_gate_front', elementId: 'l-gate-front', label: 'L Gate Front' },
        { key: 'l_gate_rear', elementId: 'l-gate-rear', label: 'L Gate Rear' },
        { key: 'l_chamber_1', elementId: 'l-chamber-1', label: 'L Chamber 1' },
        { key: 'l_chamber_2', elementId: 'l-chamber-2', label: 'L Chamber 2' },
        { key: 'r_gate_front', elementId: 'r-gate-front', label: 'R Gate Front' },
        { key: 'r_gate_rear', elementId: 'r-gate-rear', label: 'R Gate Rear' },
        { key: 'r_chamber_1', elementId: 'r-chamber-1', label: 'R Chamber 1' },
        { key: 'r_chamber_2', elementId: 'r-chamber-2', label: 'R Chamber 2' }
    ],

    // Chart colors for each metric
    chartColors: {
        l_gate_front: '#3498DB',
        l_gate_rear: '#5DADE2',
        l_chamber_1: '#85C1E2',
        l_chamber_2: '#AED6F1',
        r_gate_front: '#E74C3C',
        r_gate_rear: '#EC7063',
        r_chamber_1: '#F1948A',
        r_chamber_2: '#F5B7B1'
    },

    // Temperature thresholds for quality assessment
    thresholds: {
        l_gate_front: { min: 60, max: 80 },
        l_gate_rear: { min: 60, max: 80 },
        l_chamber_1: { min: 60, max: 80 },
        l_chamber_2: { min: 60, max: 80 },
        r_gate_front: { min: 60, max: 80 },
        r_gate_rear: { min: 60, max: 80 },
        r_chamber_1: { min: 60, max: 80 },
        r_chamber_2: { min: 60, max: 80 }
    },

    // Table column configuration
    tableColumns: [
        { key: 'l_gate_front', label: 'L Gate Front' },
        { key: 'l_gate_rear', label: 'L Gate Rear' },
        { key: 'l_chamber_1', label: 'L Chamber 1' },
        { key: 'l_chamber_2', label: 'L Chamber 2' },
        { key: 'r_gate_front', label: 'R Gate Front' },
        { key: 'r_gate_rear', label: 'R Gate Rear' },
        { key: 'r_chamber_1', label: 'R Chamber 1' },
        { key: 'r_chamber_2', label: 'R Chamber 2' }
    ],

    // Chart filter mappings (dataset indices)
    chartFilters: {
        all: [0, 1, 2, 3, 4, 5, 6, 7],
        gate: [0, 1, 4, 5],      
        chamber: [2, 3, 6, 7],   
        left: [0, 1, 2, 3],      
        right: [4, 5, 6, 7]     
    }
});

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', CastingPerformanceTR.init);
} else {
    CastingPerformanceTR.init();
}
