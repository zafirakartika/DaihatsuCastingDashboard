<?php
/**
 * Data Mapping Configuration
 * Map your MES database tables/columns to dashboard data structure
 *
 * This file maps your real database structure to what the dashboard expects
 */

/**
 * CASTING PERFORMANCE DATA MAPPING
 * Configure how to fetch casting data from your MES database
 */
$CASTING_DATA_CONFIG = [
    // WA Part Configuration
    'WA' => [
        'table' => 'casting_data_wa',              // Your MES table name
        'columns' => [
            'id' => 'id',                          // Primary key column
            'id_part' => 'part_number',            // Part ID/Number column
            'timestamp' => 'created_at',           // Timestamp column
            'r_lower_gate1' => 'temp_r_gate',      // Map: dashboard field => database column
            'r_lower_main1' => 'temp_r_main',
            'l_lower_gate1' => 'temp_l_gate',
            'l_lower_main1' => 'temp_l_main',
            'cooling_water' => 'temp_cooling',
            'quality' => 'quality_status'          // OK, NG
        ],
        'order_by' => 'created_at DESC',           // How to sort results
        'limit' => 100                             // Maximum records to fetch
    ],

    // TR Part Configuration
    'TR' => [
        'table' => 'casting_data_tr',
        'columns' => [
            'id' => 'id',
            'timestamp' => 'created_at',
            'l_gate_front' => 'temp_l_gate_front',
            'l_gate_rear' => 'temp_l_gate_rear',
            'l_chamber_1' => 'temp_l_chamber_1',
            'l_chamber_2' => 'temp_l_chamber_2',
            'r_gate_front' => 'temp_r_gate_front',
            'r_gate_rear' => 'temp_r_gate_rear',
            'r_chamber_1' => 'temp_r_chamber_1',
            'r_chamber_2' => 'temp_r_chamber_2',
            'quality' => 'quality_status'
        ],
        'order_by' => 'created_at DESC',
        'limit' => 100
    ],

    // KR Part Configuration
    'KR' => [
        'table' => 'casting_data_kr',
        'columns' => [
            'id' => 'id',
            'id_part' => 'part_number',
            'timestamp' => 'created_at',
            // Add your KR sensor mappings here
        ],
        'order_by' => 'created_at DESC',
        'limit' => 100
    ],

    // NR Part Configuration
    'NR' => [
        'table' => 'casting_data_nr',
        'columns' => [
            'id' => 'id',
            'id_part' => 'part_number',
            'timestamp' => 'created_at',
            // Add your NR sensor mappings here
        ],
        'order_by' => 'created_at DESC',
        'limit' => 100
    ],

    // 3SZ Part Configuration
    '3SZ' => [
        'table' => 'casting_data_3sz',
        'columns' => [
            'id' => 'id',
            'id_part' => 'part_number',
            'timestamp' => 'created_at',
            // Add your 3SZ sensor mappings here
        ],
        'order_by' => 'created_at DESC',
        'limit' => 100
    ]
];

/**
 * FINISHING PERFORMANCE DATA MAPPING
 */
$FINISHING_DATA_CONFIG = [
    'WA' => [
        'table' => 'finishing_data_wa',
        'columns' => [
            'id' => 'id',
            'timestamp' => 'created_at',
            'part_id' => 'part_number',
            'oven_temp' => 'temperature_oven',
            'cooling_temp' => 'temperature_cooling',
            'pressure' => 'pressure_value',
            'speed' => 'conveyor_speed',
            'cycle_time' => 'cycle_time_seconds',
            'defect_type' => 'defect_classification',
            'machine_status' => 'status'           // Running, Idle, Down
        ],
        'order_by' => 'created_at DESC',
        'limit' => 100
    ],

    'TR' => [
        'table' => 'finishing_data_tr',
        'columns' => [
            'id' => 'id',
            'timestamp' => 'created_at',
            // Add TR finishing sensor mappings
        ],
        'order_by' => 'created_at DESC',
        'limit' => 100
    ]
];

/**
 * GENERAL ALPC DATA MAPPING (Production Overview)
 */
$GENERAL_ALPC_CONFIG = [
    'WA' => [
        'production_table' => 'production_summary_wa',
        'columns' => [
            'shot_number' => 'shot_count',
            'good_count' => 'good_parts',
            'reject_count' => 'rejected_parts',
            'timestamp' => 'production_date'
        ]
    ],

    'TR' => [
        'production_table' => 'production_summary_tr',
        'columns' => [
            'shot_number' => 'shot_count',
            'good_count' => 'good_parts',
            'reject_count' => 'rejected_parts',
            'timestamp' => 'production_date'
        ]
    ]
];

/**
 * TRACEABILITY DATA MAPPING
 */
$TRACEABILITY_CONFIG = [
    'table' => 'traceability_records',
    'columns' => [
        'part_id' => 'part_number',
        'part_type' => 'part_type',              // WA, TR, KR, NR, 3SZ
        'production_date' => 'production_date',
        'lot_number' => 'lot_number',
        'operator' => 'operator_name',
        'machine_id' => 'machine_number',
        'casting_temp' => 'casting_temperature',
        'finishing_temp' => 'finishing_temperature',
        'quality_result' => 'quality_check',
        'remarks' => 'notes'
    ],
    'search_fields' => ['part_number', 'lot_number', 'operator_name']
];

/**
 * COUNTER DATA MAPPING (Production Counters per LPC)
 */
$COUNTER_CONFIG = [
    'TR' => [
        'table' => 'production_summary_tr',
        'columns' => [
            'LPC1' => 'good_parts',
            'LPC2' => 'good_parts',
            'LPC3' => 'good_parts',
            'LPC4' => 'good_parts',
            'LPC6' => 'good_parts'
        ],
        'order_by' => 'created_at DESC',
        'limit' => 1
    ],
    '3SZ-KR' => [
        'table' => 'production_summary_tr', // Assuming same table, different LPC
        'columns' => [
            'LPC9' => 'good_parts'
        ],
        'order_by' => 'created_at DESC',
        'limit' => 1
    ],
    'NR' => [
        'table' => 'production_summary_tr', // Assuming same table
        'columns' => [
            'LPC12' => 'good_parts',
            'LPC13' => 'good_parts',
            'LPC14' => 'good_parts'
        ],
        'order_by' => 'created_at DESC',
        'limit' => 1
    ],
    'WA' => [
        'table' => 'production_summary_wa',
        'columns' => [
            'LPC11' => 'good_parts'
        ],
        'order_by' => 'created_at DESC',
        'limit' => 1
    ]
];

/**
 * DATA REFRESH SETTINGS
 */
$REFRESH_CONFIG = [
    'casting_performance' => [
        'interval_ms' => 3000,                   // Refresh every 3 seconds
        'batch_size' => 1                        // Fetch 1 new record per refresh
    ],
    'finishing_performance' => [
        'interval_ms' => 3000,
        'batch_size' => 1
    ],
    'general_alpc' => [
        'interval_ms' => 5000,                   // Refresh every 5 seconds
        'batch_size' => 10                       // Fetch latest 10 records
    ],
    'traceability' => [
        'interval_ms' => 10000,                  // Refresh every 10 seconds
        'batch_size' => 50
    ]
];

/**
 * QUALITY THRESHOLDS (from MES standards)
 */
$QUALITY_THRESHOLDS = [
    'WA' => [
        'r_lower_gate1' => ['min' => 480, 'max' => 520],
        'r_lower_main1' => ['min' => 510, 'max' => 525],
        'l_lower_gate1' => ['min' => 480, 'max' => 520],
        'l_lower_main1' => ['min' => 500, 'max' => 515],
        'cooling_water' => ['min' => 30, 'max' => 40]
    ],
    'TR' => [
        'l_gate_front' => ['min' => 200, 'max' => 400],
        'l_gate_rear' => ['min' => 200, 'max' => 400],
        'l_chamber_1' => ['min' => 200, 'max' => 400],
        'l_chamber_2' => ['min' => 200, 'max' => 400],
        'r_gate_front' => ['min' => 200, 'max' => 400],
        'r_gate_rear' => ['min' => 200, 'max' => 400],
        'r_chamber_1' => ['min' => 200, 'max' => 400],
        'r_chamber_2' => ['min' => 200, 'max' => 400]
    ]
    // Add thresholds for KR, NR, 3SZ
];
