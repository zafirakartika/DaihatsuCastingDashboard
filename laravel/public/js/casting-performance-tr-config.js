/**
 * Casting Performance Configuration for TR Part
 * Updated to match actual tr_loger_lpcX table schema (LPC 1, 4, 6 confirmed)
 */

const CastingPerformanceTR = CastingPerformanceCore({
    partName: 'TR',
    apiUrl: '/api/casting-data-tr',

    // LPC selection for TR Line 1
    lpcOptions: {
        default: 1,
        options: [
            { value: 1, label: 'LPC 1' },
            { value: 2, label: 'LPC 2' },
            { value: 3, label: 'LPC 3' },
            { value: 4, label: 'LPC 4' },
            { value: 6, label: 'LPC 6' }
        ]
    },

    showIdPart: false,
    trendDataLimit: 5000,

    simulationMode: { enabled: false },
    disableCycleFilter: true,

    chartConfig: {
        yMin: 420,
        yMax: 560,
        stepSize: 20,
        pointRadius: 3,
        lowerLimit: 470,
        upperLimit: 520
    },

    secondaryChart: {
        metricIndices: [9, 10],
        canvasId: 'pressureRoomChart',
        title: 'Pressure & Holding Room Temperature Trend',
        yMin: 670,
        yMax: 710,
        tickCount: 9,
        pointRadius: 3
    },

    // 11 temperature metrics — shown in trend chart
    metrics: [
        { key: 'r_lower_gate1_temp_1', elementId: 'r-gate1-temp',       label: 'R Lower Gate1 Temp' },
        { key: 'r_lower_gate2_temp_1', elementId: 'r-gate2-temp',       label: 'R Lower Gate2 Temp' },
        { key: 'r_lower_main1_temp_1', elementId: 'r-main1-temp',       label: 'R Lower Main1 Temp' },
        { key: 'r_lower_main2_temp_1', elementId: 'r-main2-temp',       label: 'R Lower Main2 Temp' },
        { key: 'l_upper_main_temp_1',  elementId: 'l-upper-main-temp',  label: 'L Upper Main Temp'  },
        { key: 'l_lower_gate1_temp_1', elementId: 'l-gate1-temp',       label: 'L Lower Gate1 Temp' },
        { key: 'l_lower_gate2_temp_1', elementId: 'l-gate2-temp',       label: 'L Lower Gate2 Temp' },
        { key: 'l_lower_main1_temp_1', elementId: 'l-main1-temp',       label: 'L Lower Main1 Temp' },
        { key: 'l_lower_main2_temp_1', elementId: 'l-main2-temp',       label: 'L Lower Main2 Temp' },
        { key: 'pressure_room_temp_1', elementId: 'pressure-room-temp', label: 'Pressure Room Temp' },
        { key: 'hoolding_room_temp_1', elementId: 'holding-room-temp',  label: 'Holding Room Temp'  }
    ],

    // 8 flow metrics — shown as cards only (not in temperature trend chart)
    additionalMetrics: [
        { key: 'r_upper_sp_flow_1',          elementId: 'r-upper-sp-flow',   label: 'R Upper SP Flow',        divisor: 10 },
        { key: 'r_upper_flow_1',             elementId: 'r-upper-flow',      label: 'R Upper Flow',           divisor: 10 },
        { key: 'l_upper_sp_flow_1',          elementId: 'l-upper-sp-flow',   label: 'L Upper SP Flow',        divisor: 10 },
        { key: 'l_upper_flow_1',             elementId: 'l-upper-flow',      label: 'L Upper Flow',           divisor: 10 },
        { key: 'r_lower_cooling_air1_flow_1',elementId: 'r-cool-air1-flow',  label: 'R Lower Cool Air1 Flow' },
        { key: 'l_lower_cooling_air1_flow_1',elementId: 'l-cool-air1-flow',  label: 'L Lower Cool Air1 Flow' },
        { key: 'r_lower_cooling_air2_flow_1',elementId: 'r-cool-air2-flow',  label: 'R Lower Cool Air2 Flow' },
        { key: 'l_lower_cooling_air2_flow_1',elementId: 'l-cool-air2-flow',  label: 'L Lower Cool Air2 Flow' }
    ],

    chartColors: {
        r_lower_gate1_temp_1: '#8E44AD',
        r_lower_gate2_temp_1: '#BB8FCE',
        r_lower_main1_temp_1: '#E67E22',
        r_lower_main2_temp_1: '#F39C12',
        l_upper_main_temp_1:  '#1ABC9C',
        l_lower_gate1_temp_1: '#3498DB',
        l_lower_gate2_temp_1: '#5DADE2',
        l_lower_main1_temp_1: '#2980B9',
        l_lower_main2_temp_1: '#85C1E2',
        pressure_room_temp_1: '#9B59B6',
        hoolding_room_temp_1: '#E67E22'
    },

    thresholds: {
        r_lower_gate1_temp_1: { min: 150, max: 400 },
        r_lower_gate2_temp_1: { min: 150, max: 400 },
        r_lower_main1_temp_1: { min: 150, max: 400 },
        r_lower_main2_temp_1: { min: 150, max: 400 },
        l_upper_main_temp_1:  { min: 150, max: 400 },
        l_lower_gate1_temp_1: { min: 150, max: 400 },
        l_lower_gate2_temp_1: { min: 150, max: 400 },
        l_lower_main1_temp_1: { min: 150, max: 400 },
        l_lower_main2_temp_1: { min: 150, max: 400 },
        pressure_room_temp_1: { min: 100, max: 300 },
        hoolding_room_temp_1: { min: 100, max: 300 }
    },

    tableColumns: [
        { key: 'r_lower_gate1_temp_1', label: 'R Gate1 Temp'    },
        { key: 'r_lower_gate2_temp_1', label: 'R Gate2 Temp'    },
        { key: 'r_lower_main1_temp_1', label: 'R Main1 Temp'    },
        { key: 'r_lower_main2_temp_1', label: 'R Main2 Temp'    },
        { key: 'l_upper_main_temp_1',  label: 'L Upper Main'    },
        { key: 'l_lower_gate1_temp_1', label: 'L Gate1 Temp'    },
        { key: 'l_lower_gate2_temp_1', label: 'L Gate2 Temp'    },
        { key: 'l_lower_main1_temp_1', label: 'L Main1 Temp'    },
        { key: 'l_lower_main2_temp_1', label: 'L Main2 Temp'    },
        { key: 'pressure_room_temp_1', label: 'Pres Room Temp'  },
        { key: 'hoolding_room_temp_1', label: 'Hold Room Temp'  }
    ],

    // Dataset indices matching metrics array above (0–10)
    chartFilters: {
        all:   [0,1,2,3,4,5,6,7,8,9,10],
        gate:  [0,1,5,6],          // r_gate1, r_gate2, l_gate1, l_gate2
        main:  [2,3,4,7,8],        // r_main1, r_main2, l_upper_main, l_main1, l_main2
        left:  [4,5,6,7,8],        // l_upper_main, l_gate1, l_gate2, l_main1, l_main2
        right: [0,1,2,3],          // r_gate1, r_gate2, r_main1, r_main2
        room:  [9,10]              // pressure_room, holding_room
    },

    // 3 flow charts — indices into additionalMetrics array (0-7)
    // additionalMetrics: [0] r_upper_sp, [1] r_upper, [2] l_upper_sp, [3] l_upper, [4] r_cool_air1, [5] l_cool_air1, [6] r_cool_air2, [7] l_cool_air2
    additionalCharts: [
        {
            canvasId: 'spFlowChart',
            title: 'Upper SP Flow Trend',
            metricIndices: [0, 2],
            yMin: 0, yMax: 5, stepSize: 0.5, unit: 'L/min', pointRadius: 2,
            colors: ['#27AE60', '#16A085']
        },
        {
            canvasId: 'upperFlowChart',
            title: 'Upper Flow Trend',
            metricIndices: [1, 3],
            yMin: 0, yMax: 5, stepSize: 0.5, unit: 'L/min', pointRadius: 2,
            colors: ['#2ECC71', '#1ABC9C']
        },
        {
            canvasId: 'airFlowChart',
            title: 'Lower Cool Air Flow Trend',
            metricIndices: [4, 5, 6, 7],
            yMin: 900, yMax: 1500, stepSize: 100, unit: 'L/min', pointRadius: 2,
            colors: ['#117A65', '#148F77', '#0E6655', '#17A589']
        }
    ]
});

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', CastingPerformanceTR.init);
} else {
    CastingPerformanceTR.init();
}
