<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $summary = [
            'orders' => 0,
            'revenue' => 0,
            'active_prints' => 0,
        ];

        $latestOrders = [];

        return view('dashboard.index', compact(
            'summary',
            'latestOrders'
        ));
    }
}
