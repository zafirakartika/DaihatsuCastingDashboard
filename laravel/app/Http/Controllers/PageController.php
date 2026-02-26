<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    // ── Home ──────────────────────────────────────────────────────────────
    public function home()
    {
        return view('welcome');
    }

    // ── Production Dashboard ──────────────────────────────────────────────
    public function productionDashboard()
    {
        return view('production-dashboard');
    }

    // ── ALPC Overview ─────────────────────────────────────────────────────
    public function alpcOverview()
    {
        return view('alpc-overview');
    }

    // ── LPC Counters ──────────────────────────────────────────────────────
    public function lpcCounters()
    {
        return view('lpc-counters');
    }

    // ── ALPC TR ───────────────────────────────────────────────────────────
    public function generalAlpcTr()
    {
        return view('general-alpc-tr');
    }

    public function castingPerformanceTr()
    {
        return view('casting-performance-tr', ['current_page' => 'casting-performance-tr']);
    }

    public function finishingPerformanceTr()
    {
        return view('finishing-performance-tr', ['current_page' => 'finishing-performance-tr']);
    }

    // ── ALPC 3SZ ──────────────────────────────────────────────────────────
    public function generalAlpc3sz()
    {
        return view('general-alpc-3sz');
    }

    public function castingPerformance3sz()
    {
        return view('casting-performance-3sz', ['current_page' => 'casting-performance-3sz']);
    }

    public function finishingPerformance3sz()
    {
        return view('finishing-performance-3sz', ['current_page' => 'finishing-performance-3sz']);
    }

    // ── ALPC KR ───────────────────────────────────────────────────────────
    public function generalAlpcKr()
    {
        return view('general-alpc-kr');
    }

    public function castingPerformanceKr()
    {
        return view('casting-performance-kr', ['current_page' => 'casting-performance-kr']);
    }

    public function finishingPerformanceKr()
    {
        return view('finishing-performance-kr', ['current_page' => 'finishing-performance-kr']);
    }

    // ── ALPC NR ───────────────────────────────────────────────────────────
    public function generalAlpcNr()
    {
        return view('general-alpc-nr');
    }

    public function castingPerformanceNr()
    {
        return view('casting-performance-nr', ['current_page' => 'casting-performance-nr']);
    }

    public function finishingPerformanceNr()
    {
        return view('finishing-performance-nr', ['current_page' => 'finishing-performance-nr']);
    }

    // ── ALPC WA ───────────────────────────────────────────────────────────
    public function generalAlpcWa()
    {
        return view('general-alpc-wa');
    }

    public function castingPerformanceWa()
    {
        return view('casting-performance-wa', ['current_page' => 'casting-performance-wa']);
    }

    public function finishingPerformanceWa()
    {
        return view('finishing-performance-wa', ['current_page' => 'finishing-performance-wa']);
    }

    // ── Traceability ──────────────────────────────────────────────────────
    public function traceability()
    {
        return view('traceability');
    }

    public function traceabilityWa()
    {
        return view('traceability-wa');
    }

    public function traceabilityTr()
    {
        return view('traceability-tr');
    }

    public function traceabilityKr()
    {
        return view('traceability-kr');
    }

    public function traceabilityNr()
    {
        return view('traceability-nr');
    }

    public function traceability3sz()
    {
        return view('traceability-3sz');
    }

    // ── Management Dashboards ─────────────────────────────────────────────
    public function managementDashboard()
    {
        return view('management-dashboard');
    }

    public function qualityDashboard()
    {
        return view('quality-dashboard');
    }

    public function pclDashboard()
    {
        return view('pcl-dashboard');
    }

    public function maintenanceDashboard()
    {
        return view('maintenance-dashboard');
    }
}
