<?php
// Landing Page - Central Hub
$current_page = 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="assets/images/daihatsu-logo.png">
    <title>Daihatsu Casting SMART Factory</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
            background-size: 200% 200%;
            animation: gradientFlow 15s ease infinite;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        /* ADM Background Image with Opacity */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('assets/images/ADM.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.25;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes gradientFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Animated Particles/Lights */
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            animation: floatParticle linear infinite;
            pointer-events: none;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.7), 0 0 5px rgba(255, 255, 255, 0.9);
            z-index: 1;
        }

        @keyframes floatParticle {
            0% {
                transform: translateY(100vh) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) translateX(100px);
                opacity: 0;
            }
        }

        /* Top Header */
        .top-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 12px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            position: relative;
            z-index: 10;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .company-logo {
            height: 45px;
        }

        .header-title {
            display: flex;
            flex-direction: column;
        }

        .header-title h1 {
            font-size: 18px;
            font-weight: 700;
            color: #1e40af;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }

        .header-title p {
            font-size: 11px;
            color: #64748b;
            font-weight: 500;
            margin-top: 2px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .header-logos {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .header-logos img {
            height: 32px;
        }

        .datetime-box {
            background: #3b82f6;
            color: white;
            padding: 8px 20px;
            border-radius: 8px;
            text-align: right;
            min-width: 140px;
        }

        .datetime-box .time {
            font-size: 18px;
            font-weight: 700;
            font-family: 'Inter', monospace;
            letter-spacing: 1px;
        }

        .datetime-box .date {
            font-size: 10px;
            opacity: 0.9;
            margin-top: 2px;
            font-weight: 500;
        }

        /* Main Container */
        .central-hub {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            position: relative;
            z-index: 5;
        }

        .welcome-section {
            text-align: center;
            margin-bottom: 50px;
        }

        .welcome-section h2 {
            font-size: 32px;
            font-weight: 300;
            color: white;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .welcome-section p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 400;
        }

        /* Module Cards Grid */
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            max-width: 900px;
            width: 100%;
        }

        .module-card {
            background: white;
            border-radius: 16px;
            padding: 32px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .module-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--accent-color);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .module-card:hover::before {
            transform: scaleX(1);
        }

        .module-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
        }

        .module-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 56px;
            transition: transform 0.3s ease;
        }

        .module-card:hover .module-icon {
            transform: scale(1.15);
        }

        .module-name {
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Module Color Accents */
        .module-card.production { --accent-color: #6366f1; }
        .module-card.maintenance { --accent-color: #ec4899; }
        .module-card.quality { --accent-color: #10b981; }
        .module-card.pcl { --accent-color: #f59e0b; }

        /* Footer */
        .footer {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 16px 32px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 10;
        }

        .footer p {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .modules-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .top-header {
                flex-direction: column;
                gap: 12px;
                padding: 16px;
            }

            .header-right {
                flex-direction: column;
                gap: 12px;
                width: 100%;
            }

            .datetime-box {
                width: 100%;
            }

            .modules-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }

            .welcome-section h2 {
                font-size: 24px;
            }

            .module-card {
                padding: 24px 16px;
            }

            .module-icon {
                width: 60px;
                height: 60px;
            }
        }

        @media (max-width: 480px) {
            .modules-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Top Header -->
    <div class="top-header">
        <div class="header-left">
            <img src="assets/images/daihatsu-logo.png" alt="Daihatsu" class="company-logo">
            <div class="header-title">
                <h1>SMART FACTORY</h1>
                <p>Casting Dashboard System</p>
            </div>
        </div>
        <div class="header-right">
            <div class="header-logos">
                <img src="assets/images/icare.png" alt="I CARE">
                <img src="assets/images/adm-unity.png" alt="ADM Unity">
            </div>
            <div class="datetime-box">
                <div class="time" id="live-time">00:00:00</div>
                <div class="date" id="live-date">Loading...</div>
            </div>
        </div>
    </div>

    <!-- Central Hub -->
    <div class="central-hub">
        <div class="welcome-section">
            <h2>Welcome to Dashboard</h2>
            <p>Choose your workspace to get started</p>
        </div>

        <div class="modules-grid">
            <!-- Production Module -->
            <div class="module-card production" onclick="location.href='pages/production-dashboard.php'">
                <div class="module-icon">🏭</div>
                <div class="module-name">Production</div>
            </div>

            <!-- Maintenance Module -->
            <div class="module-card maintenance" onclick="location.href='pages/maintenance-dashboard.php'">
                <div class="module-icon">🔧</div>
                <div class="module-name">Maintenance</div>
            </div>

            <!-- Quality Module -->
            <div class="module-card quality" onclick="location.href='pages/quality-dashboard.php'">
                <div class="module-icon">✅</div>
                <div class="module-name">Quality</div>
            </div>

            <!-- PCL Module -->
            <div class="module-card pcl" onclick="location.href='pages/pcl-dashboard.php'">
                <div class="module-icon">📦</div>
                <div class="module-name">PCL</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2025 Astra Daihatsu Motor - All Rights Reserved</p>
    </div>

    <script>
        // Live time and date update
        function updateDateTime() {
            const now = new Date();

            // Time
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('live-time').textContent = `${hours}:${minutes}:${seconds}`;

            // Date
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

            const dayName = days[now.getDay()];
            const day = String(now.getDate()).padStart(2, '0');
            const month = months[now.getMonth()];
            const year = now.getFullYear();

            document.getElementById('live-date').textContent = `${dayName}, ${day}-${month}-${year}`;
        }

        setInterval(updateDateTime, 1000);
        updateDateTime();

        // Create animated particle lights
        function createParticles() {
            const particleCount = 25;
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.animationDuration = (15 + Math.random() * 10) + 's';
                document.body.appendChild(particle);
            }
        }

        createParticles();
    </script>
</body>
</html>
