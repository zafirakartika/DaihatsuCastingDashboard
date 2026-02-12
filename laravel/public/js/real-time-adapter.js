/**
 * Real-Time Data Adapter
 * Helper functions to switch from simulation to real MES data
 *
 * Usage: Simply call RealTimeAdapter.fetchData() instead of using simulation
 */

const RealTimeAdapter = {
    /**
     * Base API URL (change this to your production URL)
     */
    baseUrl: 'http://localhost/daihatsu-dashboard/api/data-connector.php',

    /**
     * Fetch casting performance data
     *
     * @param {string} part - Part type (WA, TR, KR, NR, 3SZ)
     * @param {number} limit - Number of records to fetch
     * @param {string} since - Fetch records since this timestamp (for incremental updates)
     * @returns {Promise<Array>}
     */
    async fetchCastingData(part, limit = 100, since = null) {
        try {
            let url = `${this.baseUrl}?endpoint=casting&part=${part}&limit=${limit}`;
            if (since) {
                url += `&since=${encodeURIComponent(since)}`;
            }

            const response = await fetch(url);
            const result = await response.json();

            if (!result.success) {
                throw new Error(result.error || 'Failed to fetch data');
            }

            return result.data;
        } catch (error) {
            console.error('❌ Error fetching casting data:', error);
            throw error;
        }
    },

    /**
     * Fetch finishing performance data
     *
     * @param {string} part - Part type (WA, TR)
     * @param {number} limit - Number of records
     * @returns {Promise<Array>}
     */
    async fetchFinishingData(part, limit = 100) {
        try {
            const url = `${this.baseUrl}?endpoint=finishing&part=${part}&limit=${limit}`;
            const response = await fetch(url);
            const result = await response.json();

            if (!result.success) {
                throw new Error(result.error || 'Failed to fetch data');
            }

            return result.data;
        } catch (error) {
            console.error('❌ Error fetching finishing data:', error);
            throw error;
        }
    },

    /**
     * Fetch general ALPC production summary
     *
     * @param {string} part - Part type (WA, TR)
     * @param {string} dateFrom - Start date (YYYY-MM-DD)
     * @param {string} dateTo - End date (YYYY-MM-DD)
     * @returns {Promise<Array>}
     */
    async fetchGeneralALPCData(part, dateFrom = null, dateTo = null) {
        try {
            let url = `${this.baseUrl}?endpoint=general-alpc&part=${part}`;
            if (dateFrom) url += `&date_from=${dateFrom}`;
            if (dateTo) url += `&date_to=${dateTo}`;

            const response = await fetch(url);
            const result = await response.json();

            if (!result.success) {
                throw new Error(result.error || 'Failed to fetch data');
            }

            return result.data;
        } catch (error) {
            console.error('❌ Error fetching general ALPC data:', error);
            throw error;
        }
    },

    /**
     * Search traceability records
     *
     * @param {string} searchTerm - Search keyword
     * @param {string} partType - Filter by part type
     * @returns {Promise<Array>}
     */
    async searchTraceability(searchTerm = '', partType = '') {
        try {
            let url = `${this.baseUrl}?endpoint=traceability`;
            if (searchTerm) url += `&search=${encodeURIComponent(searchTerm)}`;
            if (partType) url += `&part_type=${partType}`;

            const response = await fetch(url);
            const result = await response.json();

            if (!result.success) {
                throw new Error(result.error || 'Failed to fetch data');
            }

            return result.data;
        } catch (error) {
            console.error('❌ Error searching traceability:', error);
            throw error;
        }
    },

    /**
     * Test database connection
     *
     * @returns {Promise<Object>}
     */
    async testConnection() {
        try {
            const url = `${this.baseUrl}?endpoint=test-connection`;
            const response = await fetch(url);
            const result = await response.json();

            if (result.success) {
                console.log('✅ Database connection successful:', result.message);
                console.log('📊 Server info:', result.server_info);
            } else {
                console.error('❌ Database connection failed:', result.message);
            }

            return result;
        } catch (error) {
            console.error('❌ Connection test failed:', error);
            throw error;
        }
    },

    /**
     * Start real-time polling for new data
     *
     * @param {string} endpoint - Data endpoint (casting, finishing, etc.)
     * @param {string} part - Part type
     * @param {Function} callback - Function to call with new data
     * @param {number} interval - Polling interval in milliseconds
     * @returns {number} Interval ID (use clearInterval to stop)
     */
    startPolling(endpoint, part, callback, interval = 3000) {
        let lastTimestamp = null;

        const poll = async () => {
            try {
                let data = [];

                switch (endpoint) {
                    case 'casting':
                        data = await this.fetchCastingData(part, 1, lastTimestamp);
                        break;
                    case 'finishing':
                        data = await this.fetchFinishingData(part, 1);
                        break;
                    case 'general-alpc':
                        data = await this.fetchGeneralALPCData(part);
                        break;
                }

                if (data.length > 0) {
                    // Update last timestamp for incremental fetching
                    if (data[0].timestamp) {
                        lastTimestamp = data[0].timestamp;
                    }

                    // Call callback with new data
                    callback(data);
                    console.log(`📥 Fetched ${data.length} new record(s)`);
                }
            } catch (error) {
                console.error('❌ Polling error:', error);
            }
        };

        // Initial fetch
        poll();

        // Start polling
        const intervalId = setInterval(poll, interval);
        console.log(`🔄 Started polling ${endpoint} for ${part} every ${interval}ms`);

        return intervalId;
    },

    /**
     * Stop polling
     *
     * @param {number} intervalId - Interval ID from startPolling()
     */
    stopPolling(intervalId) {
        clearInterval(intervalId);
        console.log('⏹️ Stopped polling');
    }
};

// ============================================
// EXAMPLE USAGE
// ============================================

/**
 * Example 1: Test connection when page loads
 */
window.addEventListener('DOMContentLoaded', async () => {
    console.log('🚀 Testing MES database connection...');
    try {
        await RealTimeAdapter.testConnection();
    } catch (error) {
        console.warn('⚠️ Could not connect to MES database. Using simulation mode.');
    }
});

/**
 * Example 2: Fetch initial casting data (replace simulation)
 */
async function loadInitialCastingData(part) {
    try {
        const data = await RealTimeAdapter.fetchCastingData(part, 50);
        console.log(`✅ Loaded ${data.length} ${part} casting records`);
        return data;
    } catch (error) {
        console.error('❌ Failed to load casting data, falling back to simulation');
        return []; // Return empty array, simulation will take over
    }
}

/**
 * Example 3: Start real-time updates
 */
function startRealTimeUpdates(part) {
    const intervalId = RealTimeAdapter.startPolling(
        'casting',
        part,
        (newData) => {
            // This callback is called every time new data arrives
            console.log('📊 New data received:', newData);

            // Update your chart/dashboard here
            // Example: updateChart(newData[0]);
        },
        3000 // Poll every 3 seconds
    );

    // Store interval ID so you can stop it later
    window.realTimeIntervalId = intervalId;
}

/**
 * Example 4: Stop real-time updates (call when leaving page)
 */
function stopRealTimeUpdates() {
    if (window.realTimeIntervalId) {
        RealTimeAdapter.stopPolling(window.realTimeIntervalId);
    }
}

// ============================================
// HOW TO INTEGRATE WITH EXISTING DASHBOARDS
// ============================================

/*
// STEP 1: In your casting-performance-wa-config.js, change:

simulationMode: {
    enabled: false,  // Disable simulation
    intervalSeconds: 2
},

// STEP 2: In your init function, replace simulation data fetch with:

async init() {
    // Load initial data from MES database
    const initialData = await loadInitialCastingData('WA');

    if (initialData.length > 0) {
        // Process initial data
        this.processData(initialData);
    }

    // Start real-time polling
    startRealTimeUpdates('WA');
}

// STEP 3: Clean up when page unloads

window.addEventListener('beforeunload', () => {
    stopRealTimeUpdates();
});
*/
