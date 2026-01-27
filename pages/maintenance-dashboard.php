<?php
$current_page = 'maintenance-dashboard';
$base_url = '../';
$page_title = 'Maintenance Dashboard';
$page_subtitle = 'Energy Consumption & Resource Monitoring';
$has_sidebar = false;  
$has_filters = true;
$has_export = true;
$has_back_button = true;  
$back_button_color = 'pink';  

// Start output buffering for main content
ob_start();
?>

<!-- Energy Consumption KPI Cards (More Compact) -->
<div class="metric-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <?php
    // Total Energy Consumption Today
    $metric_config = [
        'icon' => '⚡',
        'title' => 'Total Energy Today',
        'value' => '4,258',
        'unit' => ' kWh',
        'trend' => 'down',
        'trend_value' => '-8%',
        'status' => 'good',
        'element_id' => 'metric-total-energy'
    ];
    include '../templates/components/metric-card.php';

    // Electricity Consumption
    $metric_config = [
        'icon' => '💡',
        'title' => 'Electricity',
        'value' => '3,542',
        'unit' => ' kWh',
        'trend' => 'down',
        'trend_value' => '-5%',
        'status' => 'good',
        'element_id' => 'metric-electricity'
    ];
    include '../templates/components/metric-card.php';

    // Gas Consumption
    $metric_config = [
        'icon' => '🔥',
        'title' => 'Gas Consumption',
        'value' => '847',
        'unit' => ' m³',
        'trend' => 'neutral',
        'trend_value' => '±0%',
        'status' => 'normal',
        'element_id' => 'metric-gas'
    ];
    include '../templates/components/metric-card.php';

    // Water Usage
    $metric_config = [
        'icon' => '💧',
        'title' => 'Water Usage',
        'value' => '1,245',
        'unit' => ' L',
        'trend' => 'up',
        'trend_value' => '+3%',
        'status' => 'warning',
        'element_id' => 'metric-water'
    ];
    include '../templates/components/metric-card.php';

    // Compressed Air
    $metric_config = [
        'icon' => '💨',
        'title' => 'Compressed Air',
        'value' => '2,156',
        'unit' => ' m³',
        'trend' => 'neutral',
        'trend_value' => '±1%',
        'status' => 'normal',
        'element_id' => 'metric-compressed-air'
    ];
    include '../templates/components/metric-card.php';

    // Cost Savings
    $metric_config = [
        'icon' => '💰',
        'title' => 'Cost Savings',
        'value' => '12.4',
        'unit' => '%',
        'trend' => 'up',
        'trend_value' => '+2.4%',
        'status' => 'good',
        'element_id' => 'metric-cost-savings'
    ];
    include '../templates/components/metric-card.php';
    ?>
</div>

<!-- Consumption Trends: Daily & Hourly -->
<div class="section-header" style="margin-top: 20px;">
    <h2 class="section-title">Consumption Trends Analysis</h2>
</div>

<div class="comparison-grid" style="gap: 16px; margin-bottom: 20px;">
    <!-- Energy Consumption Trend Chart -->
    <?php
    $chart_config = [
        'title' => 'Daily Consumption',
        'subtitle' => 'Last 7 Days',
        'canvas_id' => 'energyTrendChart',
        'height' => '280px',
        'has_legend' => true,
        'has_filters' => false
    ];
    include '../templates/components/chart-container.php';
    ?>

    <!-- Hourly Consumption Pattern -->
    <?php
    $chart_config = [
        'title' => 'Hourly Pattern',
        'subtitle' => 'Today (24 hours)',
        'canvas_id' => 'hourlyPatternChart',
        'height' => '280px',
        'has_legend' => false,
        'has_filters' => true,
        'filters' => ['All', 'Electricity', 'Gas', 'Water']
    ];
    include '../templates/components/chart-container.php';
    ?>
</div>

<!-- Energy Distribution & Cost Analysis -->
<div class="section-header" style="margin-top: 20px;">
    <h2 class="section-title">Energy Distribution & Cost Analysis</h2>
</div>

<div class="comparison-grid" style="gap: 16px; margin-bottom: 20px;">
    <!-- Energy Distribution Pie Chart -->
    <?php
    $chart_config = [
        'title' => 'Energy Distribution',
        'subtitle' => 'Breakdown by Resource Type',
        'canvas_id' => 'energyDistributionChart',
        'height' => '250px',
        'has_legend' => false,
        'has_filters' => false
    ];
    include '../templates/components/chart-container.php';
    ?>

    <!-- Cost Analysis Chart -->
    <?php
    $chart_config = [
        'title' => 'Cost Analysis',
        'subtitle' => 'Monthly Comparison (IDR)',
        'canvas_id' => 'costAnalysisChart',
        'height' => '250px',
        'has_legend' => false,
        'has_filters' => false
    ];
    include '../templates/components/chart-container.php';
    ?>
</div>

<!-- Resource Consumption Table -->
<div class="section-header" style="margin-top: 20px;">
    <h2 class="section-title">Real-time Resource Consumption</h2>
</div>

<div class="data-table-wrapper">
    <table class="data-table" id="resourceConsumptionTable">
        <thead>
            <tr>
                <th>Time</th>
                <th>Electricity (kWh)</th>
                <th>Gas (m³)</th>
                <th>Water (L)</th>
                <th>Compressed Air (m³)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="resourceTableBody">
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px;">
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
/* Compact layout for maintenance dashboard */
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

// Custom JavaScript for maintenance dashboard
$custom_js = <<<EOT
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="../js/maintenance-dashboard.js?v={$_SERVER['REQUEST_TIME']}"></script>
EOT;

// Include the base template
include '../templates/dashboard-template.php';
?>
