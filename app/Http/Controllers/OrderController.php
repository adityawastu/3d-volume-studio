<?php

namespace App\Http\Controllers;

use App\Models\Calculation;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Calculation::latest()->paginate(15);

        return view('orders.index', compact('orders'));
    }

    public function fix(Calculation $calculation)
    {
        $calculation->update([
            'status' => 'fixed',
            'fixed_at' => now(),
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Order berhasil dikonfirmasi.');
    }

    public function cancel(Calculation $calculation)
    {
        $calculation->update([
            'status' => 'cancelled',
            'fixed_at' => null,
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Order ditandai tidak jadi.');
    }
}
