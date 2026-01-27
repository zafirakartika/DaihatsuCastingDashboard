<?php
$current_page = 'management-dashboard';
$base_url = '../';
$page_title = 'Management Dashboard';
$page_subtitle = 'Production Overview & Key Performance Indicators';
$has_sidebar = false;  // No sidebar for standalone template page
$has_filters = true;
$has_export = true;
$has_back_button = true;  // Show back button in header
$back_button_color = 'blue';  // Blue theme for management

// Start output buffering for main content
ob_start();
?>

<!-- KPI Metrics Grid (More Compact) -->
<div class="metric-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <?php
    // Today's Production
    $metric_config = [
        'icon' => '📦',
        'title' => 'Today Production',
        'value' => '2,847',
        'unit' => ' parts',
        'trend' => 'up',
        'trend_value' => '+12%',
        'status' => 'good',
        'element_id' => 'metric-total-production'
    ];
    include '../templates/components/metric-card.php';

    // Overall OEE
    $metric_config = [
        'icon' => '⚡',
        'title' => 'Overall OEE',
        'value' => '89.4',
        'unit' => '%',
        'trend' => 'up',
        'trend_value' => '+2.4%',
        'status' => 'good',
        'element_id' => 'metric-oee'
    ];
    include '../templates/components/metric-card.php';

    // Quality Rate
    $metric_config = [
        'icon' => '✓',
        'title' => 'Quality Rate',
        'value' => '96.3',
        'unit' => '%',
        'trend' => 'neutral',
        'trend_value' => '0%',
        'status' => 'good',
        'element_id' => 'metric-quality'
    ];
    include '../templates/components/metric-card.php';

    // Active Lines
    $metric_config = [
        'icon' => '🏭',
        'title' => 'Active Lines',
        'value' => '11',
        'unit' => '/11',
        'trend' => null,
        'status' => 'normal',
        'element_id' => 'metric-active-lines'
    ];
    include '../templates/components/metric-card.php';

    // Average Temperature (from casting data)
    $metric_config = [
        'icon' => '🌡️',
        'title' => 'Avg Temperature',
        'value' => '506',
        'unit' => '°C',
        'trend' => 'neutral',
        'trend_value' => '±1°C',
        'status' => 'normal',
        'element_id' => 'metric-avg-temp'
    ];
    include '../templates/components/metric-card.php';

    // Total Records
    $metric_config = [
        'icon' => '📊',
        'title' => 'Total Records',
        'value' => '0',
        'unit' => ' shots',
        'trend' => 'up',
        'trend_value' => 'Loading...',
        'status' => 'normal',
        'element_id' => 'metric-total-records'
    ];
    include '../templates/components/metric-card.php';
    ?>
</div>

<!-- Production Trend Chart (Compact) -->
<?php
$chart_config = [
    'title' => 'Production Trends',
    'subtitle' => 'WA vs TR - Last 7 Days',
    'canvas_id' => 'productionTrendChart',
    'height' => '300px',
    'has_legend' => true,
    'has_filters' => false
];
include '../templates/components/chart-container.php';
?>

<!-- WA vs TR Performance Comparison -->
<div class="section-header" style="margin-top: 20px;">
    <h2 class="section-title">Part Performance Comparison</h2>
</div>

<div class="comparison-grid" style="gap: 16px; margin-bottom: 20px;">
    <!-- WA Performance -->
    <?php
    $chart_config = [
        'title' => 'WA Performance Distribution',
        'subtitle' => 'Quality Status Breakdown',
        'canvas_id' => 'waPerformanceChart',
        'height' => '250px',
        'has_legend' => false,
        'has_filters' => false
    ];
    include '../templates/components/chart-container.php';
    ?>

    <!-- TR Performance -->
    <?php
    $chart_config = [
        'title' => 'TR Performance Distribution',
        'subtitle' => 'Quality Status Breakdown',
        'canvas_id' => 'trPerformanceChart',
        'height' => '250px',
        'has_legend' => false,
        'has_filters' => false
    ];
    include '../templates/components/chart-container.php';
    ?>
</div>

<!-- Temperature Comparison Chart (Compact) -->
<?php
$chart_config = [
    'title' => 'Temperature Trends Comparison',
    'subtitle' => 'Average temperatures across all sensors',
    'canvas_id' => 'temperatureComparisonChart',
    'height' => '280px',
    'has_legend' => true,
    'has_filters' => true,
    'filters' => ['All', 'WA', 'TR']
];
include '../templates/components/chart-container.php';
?>

<!-- Recent Production Records Table -->
<div class="section-header" style="margin-top: 20px;">
    <h2 class="section-title">Recent Production Records</h2>
</div>

<div class="data-table-wrapper">
    <table class="data-table" id="productionRecordsTable">
        <thead>
            <tr>
                <th>Shot No</th>
                <th>Part</th>
                <th>Timestamp</th>
                <th>Avg Temp (°C)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="productionTableBody">
            <tr>
                <td colspan="5" style="text-align: center; padding: 40px;">
                    <div style="color: #999; font-size: 14px;">Loading data...</div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?php
// Capture main content
$main_content = ob_get_clean();

// Custom CSS for compact layout
$custom_css = <<<EOT
<style>
/* Compact layout for management dashboard */
.main-content {
    padding: 20px !important;
}

.content-header {
    margin-bottom: 16px !important;
    padding-bottom: 12px !important;
}

.page-title {
    font-size: 24px !important;
}

.chart-container-wrapper {
    padding: 16px !important;
    margin-bottom: 16px !important;
}

.section-header {
    margin: 16px 0 12px 0 !important;
}

.section-title {
    font-size: 18px !important;
}

.data-table-wrapper {
    padding: 16px !important;
}

.chart-title {
    font-size: 16px !important;
}

.chart-subtitle {
    font-size: 12px !important;
}
</style>
EOT;

// Custom JavaScript for management dashboard
$custom_js = <<<EOT
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="../js/management-dashboard.js?v={$_SERVER['REQUEST_TIME']}"></script>
EOT;

// Include the base template
include '../templates/dashboard-template.php';
?>
