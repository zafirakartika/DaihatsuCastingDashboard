<?php
/**
 * Reusable Chart Container Component
 *
 * Usage Example:
 * $chart_config = [
 *     'title' => 'Production Trends',
 *     'subtitle' => 'Last 7 Days',
 *     'canvas_id' => 'productionChart',
 *     'height' => '400px',
 *     'has_legend' => true,
 *     'has_filters' => false,
 *     'filters' => ['all', 'wa', 'tr'] // Optional filter buttons
 * ];
 * include 'templates/components/chart-container.php';
 */

$title = $chart_config['title'] ?? 'Chart';
$subtitle = $chart_config['subtitle'] ?? '';
$canvas_id = $chart_config['canvas_id'] ?? uniqid('chart-');
$height = $chart_config['height'] ?? '400px';
$has_legend = $chart_config['has_legend'] ?? true;
$has_filters = $chart_config['has_filters'] ?? false;
$filters = $chart_config['filters'] ?? [];
?>

<div class="chart-container-wrapper">
    <div class="chart-header">
        <div class="chart-title-section">
            <h3 class="chart-title"><?php echo $title; ?></h3>
            <?php if ($subtitle): ?>
            <p class="chart-subtitle"><?php echo $subtitle; ?></p>
            <?php endif; ?>
        </div>

        <?php if ($has_filters && count($filters) > 0): ?>
        <div class="chart-filter-buttons" id="<?php echo $canvas_id; ?>-filters">
            <?php foreach ($filters as $index => $filter): ?>
            <button
                class="chart-filter-btn <?php echo $index === 0 ? 'active' : ''; ?>"
                data-filter="<?php echo $filter; ?>"
                onclick="filterChart('<?php echo $canvas_id; ?>', '<?php echo $filter; ?>')">
                <?php echo ucfirst($filter); ?>
            </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="chart-canvas-area" style="position: relative; height: <?php echo $height; ?>; width: 100%;">
        <canvas id="<?php echo $canvas_id; ?>"></canvas>
    </div>

    <?php if ($has_legend): ?>
    <div class="chart-legend" id="<?php echo $canvas_id; ?>-legend">
        <!-- Legend will be populated by JavaScript -->
    </div>
    <?php endif; ?>
</div>
