<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traceability - Smart Factory</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <style>
        body {
            display: flex;
            background: #f0f2f5;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            margin-left: 260px;
        }

        .page-title {
            font-size: 26px;
            font-weight: 700;
            color: #0d3b66;
            border-left: 4px solid #4a6fa5;
            padding-left: 12px;
            margin-bottom: 30px;
        }

        .parts-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            margin-top: 20px;
        }

        .parts-row {
            display: flex;
            gap: 20px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
        }

        .part-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-decoration: none;
            color: #0d3b66;
            font-weight: 700;
            font-size: 18px;
            transition: all 0.2s;
            border: 2px solid transparent;
        }

        .part-box:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(74, 111, 165, 0.25);
            border-color: #4a6fa5;
            color: #4a6fa5;
        }

        .gear-icon {
            width: 40px;
            height: 40px;
            background: url('{{ asset("assets/icons/factory2.svg") }}') center/contain no-repeat;
            margin-bottom: 8px;
            opacity: 0.7;
        }

        .part-label {
            font-size: 16px;
            font-weight: 700;
        }

        .connector-line {
            width: 30px;
            height: 2px;
            background: #c0c8d4;
        }

        .instruction-bubble {
            margin-top: 30px;
            background: #e8f4f8;
            border: 1px solid #c5dde8;
            border-radius: 12px;
            padding: 14px 20px;
            font-size: 14px;
            color: #4a6fa5;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .hand-icon {
            font-size: 20px;
        }
    </style>
</head>
<body>
    @include('includes.sidebar')

    <div class="main-content">
        <div class="page-title">Traceability</div>

        <div class="parts-container">
            <div class="parts-row">
                <a href="{{ route('traceability-tr') }}" class="part-box">
                    <div class="gear-icon"></div>
                    <div class="part-label">TR</div>
                </a>

                <div class="connector-line"></div>

                <a href="{{ route('traceability-3sz') }}" class="part-box">
                    <div class="gear-icon"></div>
                    <div class="part-label">3SZ</div>
                </a>

                <div class="connector-line"></div>

                <a href="{{ route('traceability-nr') }}" class="part-box">
                    <div class="gear-icon"></div>
                    <div class="part-label">NR</div>
                </a>

                <div class="connector-line"></div>

                <a href="{{ route('traceability-wa') }}" class="part-box">
                    <div class="gear-icon"></div>
                    <div class="part-label">WA</div>
                </a>

                <div class="connector-line"></div>

                <a href="{{ route('traceability-kr') }}" class="part-box">
                    <div class="gear-icon"></div>
                    <div class="part-label">KR</div>
                </a>
            </div>

            <div class="instruction-bubble">
                <span class="hand-icon">👆</span>
                <span>Click on a part to view detailed traceability data</span>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        const originalToggleSidebar = window.toggleSidebar;
        window.toggleSidebar = function() {
            if (originalToggleSidebar) originalToggleSidebar();
            const sidebar = document.querySelector('.sidebar');
            if (sidebar && sidebar.classList.contains('hidden')) {
                document.body.classList.add('sidebar-collapsed');
            } else {
                document.body.classList.remove('sidebar-collapsed');
            }
        };

        window.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar && sidebar.classList.contains('hidden')) {
                document.body.classList.add('sidebar-collapsed');
            }
        });
    </script>
</body>
</html>