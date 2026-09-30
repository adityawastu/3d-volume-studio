<?php

namespace App\Http\Controllers;

use App\Models\Calculation;

class DashboardController extends Controller
{
    public function index()
    {
        $summary = [
            'orders' => Calculation::where('status', 'fixed')->count(),
            'revenue' => Calculation::where('status', 'fixed')->sum('total_price'),
            'active_prints' => 0,
        ];

        $latestOrders = Calculation::where('status', 'fixed')->latest('fixed_at')->take(5)->get();

        return view('dashboard.index', compact('summary', 'latestOrders'));
    }
}
