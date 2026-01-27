<?php
$current_page = 'quality-dashboard';
$base_url = '../';
$page_title = 'Quality Dashboard';
$page_subtitle = 'Rejection Tracking & Quality Control';
$has_sidebar = false;  // No sidebar for standalone template page
$has_filters = true;
$has_export = true;
$has_back_button = true;  // Show back button in header
$back_button_color = 'green';  // Green theme for quality

// Start output buffering for main content
ob_start();
?>

<!-- Quality KPI Cards (Compact) -->
<div class="metric-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <?php
    // Total Production Today
    $metric_config = [
        'icon' => '',
        'title' => 'Total Production',
        'value' => '2,847',
        'unit' => ' parts',
        'trend' => 'up',
        'trend_value' => '+5%',
        'status' => 'normal',
        'element_id' => 'metric-total-production'
    ];
    include '../templates/components/metric-card.php';

    // Total Rejections
    $metric_config = [
        'icon' => '',
        'title' => 'Total Rejections',
        'value' => '47',
        'unit' => ' parts',
        'trend' => 'down',
        'trend_value' => '-8%',
        'status' => 'good',
        'element_id' => 'metric-total-rejections'
    ];
    include '../templates/components/metric-card.php';

    // Rejection Rate
    $metric_config = [
        'icon' => '',
        'title' => 'Rejection Rate',
        'value' => '1.65',
        'unit' => '%',
        'trend' => 'down',
        'trend_value' => '-0.3%',
        'status' => 'good',
        'element_id' => 'metric-rejection-rate'
    ];
    include '../templates/components/metric-card.php';

    // External Rejections
    $metric_config = [
        'icon' => '',
        'title' => 'External Rejection',
        'value' => '12',
        'unit' => ' parts',
        'trend' => 'down',
        'trend_value' => '-2',
        'status' => 'good',
        'element_id' => 'metric-external-rejection'
    ];
    include '../templates/components/metric-card.php';

    // Internal Rejections
    $metric_config = [
        'icon' => '',
        'title' => 'Internal Rejection',
        'value' => '35',
        'unit' => ' parts',
        'trend' => 'neutral',
        'trend_value' => '±0',
        'status' => 'normal',
        'element_id' => 'metric-internal-rejection'
    ];
    include '../templates/components/metric-card.php';

    // First Pass Yield
    $metric_config = [
        'icon' => '',
        'title' => 'First Pass Yield',
        'value' => '98.35',
        'unit' => '%',
        'trend' => 'up',
        'trend_value' => '+0.3%',
        'status' => 'good',
        'element_id' => 'metric-fpy'
    ];
    include '../templates/components/metric-card.php';
    ?>
</div>

<!-- Rejection Trends -->
<div class="section-header" style="margin-top: 20px;">
    <h2 class="section-title">Rejection Trends Analysis</h2>
</div>

<div class="comparison-grid" style="gap: 16px; margin-bottom: 20px;">
    <!-- Daily Rejection Trend -->
    <?php
    $chart_config = [
        'title' => 'Daily Rejection Trend',
        'subtitle' => 'Last 7 Days',
        'canvas_id' => 'rejectionTrendChart',
        'height' => '280px',
        'has_legend' => true,
        'has_filters' => false
    ];
    include '../templates/components/chart-container.php';
    ?>

    <!-- Hourly Rejection Pattern -->
    <?php
    $chart_config = [
        'title' => 'Hourly Pattern',
        'subtitle' => 'Today (24 hours)',
        'canvas_id' => 'hourlyRejectionChart',
        'height' => '280px',
        'has_legend' => false,
        'has_filters' => true,
        'filters' => ['All', 'External', 'Internal']
    ];
    include '../templates/components/chart-container.php';
    ?>
</div>

<!-- Rejection Analysis -->
<div class="section-header" style="margin-top: 20px;">
    <h2 class="section-title">Rejection Type & Part Analysis</h2>
</div>

<div class="comparison-grid" style="gap: 16px; margin-bottom: 20px;">
    <!-- Rejection by Type -->
    <?php
    $chart_config = [
        'title' => 'Rejection by Type',
        'subtitle' => 'Breakdown by Category',
        'canvas_id' => 'rejectionTypeChart',
        'height' => '250px',
        'has_legend' => false,
        'has_filters' => false
    ];
    include '../templates/components/chart-container.php';
    ?>

    <!-- Rejection by Part -->
    <?php
    $chart_config = [
        'title' => 'Rejection by Part',
        'subtitle' => 'WA vs TR Comparison',
        'canvas_id' => 'rejectionPartChart',
        'height' => '250px',
        'has_legend' => false,
        'has_filters' => false
    ];
    include '../templates/components/chart-container.php';
    ?>
</div>

<!-- Recent Rejections Table -->
<div class="section-header" style="margin-top: 20px;">
    <h2 class="section-title">Recent Rejection Records</h2>
</div>

<div class="data-table-wrapper">
    <table class="data-table" id="rejectionRecordsTable">
        <thead>
            <tr>
                <th>Time</th>
                <th>Part Type</th>
                <th>ID Part</th>
                <th>Rejection Type</th>
                <th>Category</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody id="rejectionTableBody">
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
/* Compact layout for quality dashboard */
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

// Custom JavaScript for quality dashboard
$custom_js = <<<EOT
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="../js/quality-dashboard.js?v={$_SERVER['REQUEST_TIME']}"></script>
EOT;

// Include the base template
include '../templates/dashboard-template.php';
?>
