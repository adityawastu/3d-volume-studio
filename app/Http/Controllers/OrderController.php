<?php

namespace App\Http\Controllers;

use App\Models\Calculation;

class OrderController extends Controller
{
    public function index()
    {
        $search = request('q');

        $orders = Calculation::query()
            ->when($search, function ($query, $search) {
                $query->where('reference_no', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('orders.index', compact('orders', 'search'));
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
