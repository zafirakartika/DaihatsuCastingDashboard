<?php
$current_page = 'pcl-dashboard';
$base_url = '../';
$page_title = 'PCL Dashboard';
$page_subtitle = 'Planning, Control & Logistics Management';
$has_sidebar = false;  // No sidebar for standalone template page
$has_filters = true;
$has_export = true;
$has_back_button = true;  // Show back button in header
$back_button_color = 'blue';  // Blue theme for PCL

// Start output buffering for main content
ob_start();
?>

<!-- Inventory KPI Cards (Compact) -->
<div class="metric-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <?php
    // Total Stock
    $metric_config = [
        'icon' => '',
        'title' => 'Total Stock',
        'value' => '8,542',
        'unit' => ' parts',
        'trend' => 'up',
        'trend_value' => '+12%',
        'status' => 'normal',
        'element_id' => 'metric-total-stock'
    ];
    include '../templates/components/metric-card.php';

    // WA Stock
    $metric_config = [
        'icon' => '',
        'title' => 'WA Stock',
        'value' => '4,820',
        'unit' => ' parts',
        'trend' => 'up',
        'trend_value' => '+8%',
        'status' => 'normal',
        'element_id' => 'metric-wa-stock'
    ];
    include '../templates/components/metric-card.php';

    // TR Stock
    $metric_config = [
        'icon' => '',
        'title' => 'TR Stock',
        'value' => '3,722',
        'unit' => ' parts',
        'trend' => 'up',
        'trend_value' => '+15%',
        'status' => 'normal',
        'element_id' => 'metric-tr-stock'
    ];
    include '../templates/components/metric-card.php';

    // Low Stock Items
    $metric_config = [
        'icon' => '',
        'title' => 'Low Stock Alert',
        'value' => '3',
        'unit' => ' items',
        'trend' => 'down',
        'trend_value' => '-2',
        'status' => 'warning',
        'element_id' => 'metric-low-stock'
    ];
    include '../templates/components/metric-card.php';

    // Shipments Today
    $metric_config = [
        'icon' => '',
        'title' => 'Shipments Today',
        'value' => '24',
        'unit' => ' batches',
        'trend' => 'up',
        'trend_value' => '+6',
        'status' => 'good',
        'element_id' => 'metric-shipments'
    ];
    include '../templates/components/metric-card.php';

    // Stock Turnover Rate
    $metric_config = [
        'icon' => '',
        'title' => 'Turnover Rate',
        'value' => '2.4',
        'unit' => ' days',
        'trend' => 'down',
        'trend_value' => '-0.3',
        'status' => 'good',
        'element_id' => 'metric-turnover'
    ];
    include '../templates/components/metric-card.php';
    ?>
</div>

<!-- Stock & Shipment Trends -->
<div class="section-header" style="margin-top: 20px;">
    <h2 class="section-title">Stock & Shipment Trends</h2>
</div>

<div class="comparison-grid" style="gap: 16px; margin-bottom: 20px;">
    <!-- Stock Level Trend -->
    <?php
    $chart_config = [
        'title' => 'Stock Level Trend',
        'subtitle' => 'Last 7 Days',
        'canvas_id' => 'stockTrendChart',
        'height' => '280px',
        'has_legend' => true,
        'has_filters' => false
    ];
    include '../templates/components/chart-container.php';
    ?>

    <!-- Daily Shipments -->
    <?php
    $chart_config = [
        'title' => 'Daily Shipments',
        'subtitle' => 'Last 7 Days',
        'canvas_id' => 'shipmentChart',
        'height' => '280px',
        'has_legend' => false,
        'has_filters' => true,
        'filters' => ['All', 'WA', 'TR']
    ];
    include '../templates/components/chart-container.php';
    ?>
</div>

<!-- Inventory Analysis -->
<div class="section-header" style="margin-top: 20px;">
    <h2 class="section-title">Inventory Analysis & Planning</h2>
</div>

<div class="comparison-grid" style="gap: 16px; margin-bottom: 20px;">
    <!-- Stock Distribution -->
    <?php
    $chart_config = [
        'title' => 'Stock Distribution',
        'subtitle' => 'By Part Type',
        'canvas_id' => 'stockDistributionChart',
        'height' => '250px',
        'has_legend' => false,
        'has_filters' => false
    ];
    include '../templates/components/chart-container.php';
    ?>

    <!-- Warehouse Utilization -->
    <?php
    $chart_config = [
        'title' => 'Warehouse Utilization',
        'subtitle' => 'By Location',
        'canvas_id' => 'warehouseChart',
        'height' => '250px',
        'has_legend' => false,
        'has_filters' => false
    ];
    include '../templates/components/chart-container.php';
    ?>
</div>

<!-- Recent Stock Movements Table -->
<div class="section-header" style="margin-top: 20px;">
    <h2 class="section-title">Recent Stock Movements</h2>
</div>

<div class="data-table-wrapper">
    <table class="data-table" id="stockMovementTable">
        <thead>
            <tr>
                <th>Time</th>
                <th>Part Type</th>
                <th>Batch ID</th>
                <th>Movement Type</th>
                <th>Quantity</th>
                <th>Location</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="stockTableBody">
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px;">
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
/* Compact layout for PCL dashboard */
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

// Custom JavaScript for PCL dashboard
$custom_js = <<<EOT
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="../js/pcl-dashboard.js?v={$_SERVER['REQUEST_TIME']}"></script>
EOT;

// Include the base template
include '../templates/dashboard-template.php';
?>
