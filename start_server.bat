@echo off
title Laravel Dashboard Server
echo Starting the server for local network access...

:: Change the directory to your project folder
cd /d "D:\new xampp\htdocs\daihatsu-dashboard\laravel"

:: Run the server accessible to the network
php artisan serve --host=0.0.0.0 --port=8000

pause