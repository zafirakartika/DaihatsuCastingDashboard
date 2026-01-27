<?php
/**
 * Reusable Dashboard Template
 * Base layout for all dashboard sections (Production, Management, Maintenance, PCL, PE)
 *
 * Required variables to pass before including this template:
 * - $current_page: Current page identifier (e.g., 'management-dashboard')
 * - $base_url: Base URL for assets (e.g., '../')
 * - $page_title: Title of the page (e.g., 'Management Dashboard')
 * - $page_subtitle: Subtitle/description (optional)
 * - $main_content: Main content HTML (use output buffering)
 *
 * Optional variables:
 * - $has_sidebar: Show sidebar navigation (default: true)
 * - $has_filters: Enable filter controls (default: false)
 * - $has_export: Enable export button (default: false)
 * - $has_back_button: Show back to dashboard button (default: false)
 * - $back_button_color: Color for back button gradient (default: 'blue')
 * - $refresh_interval: Auto-refresh interval in ms (default: 30000)
 * - $custom_css: Additional CSS includes
 * - $custom_js: Additional JS includes
 */

$has_sidebar = $has_sidebar ?? true;
$has_filters = $has_filters ?? false;
$has_export = $has_export ?? false;
$has_back_button = $has_back_button ?? false;
$back_button_color = $back_button_color ?? 'blue';
$refresh_interval = $refresh_interval ?? 30000;
$page_subtitle = $page_subtitle ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?php echo $base_url; ?>assets/images/daihatsu-logo.png">
    <title><?php echo $page_title; ?> - SMART Factory</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/styles.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/dashboard-components.css?v=<?php echo time(); ?>">
    <?php if (isset($custom_css)) echo $custom_css; ?>
</head>

<body>
    <!-- Top Header -->
    <div class="top-header">
        <div class="logo-section">
            <?php if ($has_sidebar): ?>
            <!-- Hamburger Menu -->
            <div class="hamburger-menu" onclick="toggleSidebar()">
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
            </div>
            <?php endif; ?>
            <img src="<?php echo $base_url; ?>assets/images/daihatsu-logo.png" alt="Daihatsu Logo" class="company-logo">
        </div>
        <div class="header-center">
            <div class="monitoring-title">
                <span class="monitoring-text"><?php echo $page_title; ?></span>
                <?php if ($page_subtitle): ?>
                <div class="monitoring-subtitle"><?php echo $page_subtitle; ?></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="header-right">
            <div class="header-logos">
                <img src="<?php echo $base_url; ?>assets/images/icare.png" alt="I CARE" class="company-logo">
                <img src="<?php echo $base_url; ?>assets/images/adm-unity.png" alt="ADM Unity" class="company-logo">
            </div>
            <div class="datetime-display">
                <div class="date-text" id="current-date"></div>
                <div class="time-text" id="current-time"></div>
            </div>
        </div>
    </div>

    <!-- Dashboard Container -->
    <div class="dashboard-container">
        <?php if ($has_sidebar): ?>
        <?php include $base_url . 'includes/sidebar.php'; ?>
        <?php endif; ?>

        <!-- Main Content -->
        <div class="main-content" <?php if (!$has_sidebar): ?>style="margin-left: 0; width: 100%;"<?php endif; ?>>
            <!-- Page Header with Optional Filters/Controls -->
            <div class="content-header">
                <div class="page-title-wrapper">
                    <div class="page-title"><?php echo $page_title; ?></div>
                </div>

                <?php if ($has_filters || $has_export || $has_back_button): ?>
                <div class="page-controls">
                    <?php if ($has_back_button): ?>
                    <?php
                    // Define color schemes for back button
                    $color_schemes = [
                        'blue' => ['start' => '#4a6fa5', 'end' => '#0d3b66', 'hover_shadow' => 'rgba(74, 111, 165, 0.3)', 'shadow' => 'rgba(74, 111, 165, 0.2)'],
                        'pink' => ['start' => '#ec4899', 'end' => '#f43f5e', 'hover_shadow' => 'rgba(236, 72, 153, 0.3)', 'shadow' => 'rgba(236, 72, 153, 0.2)'],
                        'green' => ['start' => '#10b981', 'end' => '#14b8a6', 'hover_shadow' => 'rgba(16, 185, 129, 0.3)', 'shadow' => 'rgba(16, 185, 129, 0.2)']
                    ];
                    $colors = $color_schemes[$back_button_color] ?? $color_schemes['blue'];
                    ?>
                    <button onclick="window.location.href='<?php echo $base_url; ?>index.php'"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px <?php echo $colors['hover_shadow']; ?>'"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px <?php echo $colors['shadow']; ?>'"
                            style="display: flex; align-items: center; gap: 8px; padding: 8px 16px; background: linear-gradient(135deg, <?php echo $colors['start']; ?>, <?php echo $colors['end']; ?>); color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 2px 8px <?php echo $colors['shadow']; ?>;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 12H5M12 19l-7-7 7-7"/>
                        </svg>
                        Back
                    </button>
                    <?php endif; ?>

                    <?php if ($has_filters): ?>
                    <div class="filter-group">
                        <select id="dateRange" class="filter-select">
                            <option value="today">Today</option>
                            <option value="week" selected>Last 7 Days</option>
                            <option value="month">Last 30 Days</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                    <?php endif; ?>

                    <?php if ($has_export): ?>
                    <button class="export-btn" onclick="exportDashboard()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Export
                    </button>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Main Dashboard Content Area -->
            <div class="dashboard-content-area">
                <?php echo $main_content; ?>
            </div>
        </div>
    </div>

    <!-- Core Scripts -->
    <script src="<?php echo $base_url; ?>js/main.js?v=<?php echo time(); ?>"></script>
    <?php if (isset($custom_js)) echo $custom_js; ?>
</body>
</html>
