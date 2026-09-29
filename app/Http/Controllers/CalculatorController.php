<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function index()
    {
        return view('calculator.index');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'weight' => ['required', 'numeric', 'min:0.01'],
            'hours' => ['required', 'integer', 'min:0'],
            'minutes' => ['required', 'integer', 'min:0', 'max:59'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $pricePerGram = 600;
        $pricePerHour = 25000;
        $feePercentage = 8.25;
        $fixedCost = 3750;
        $ratioLimit = 2.5;

        $weight = (float) $request->weight;
        $hours = (int) $request->hours;
        $minutes = (int) $request->minutes;
        $quantity = (int) $request->quantity;

        $totalMinutes = ($hours * 60) + $minutes;

        $ratio = $totalMinutes / $weight;

        if ($ratio < $ratioLimit) {
            $method = 'Harga per Gram';
            $basePrice = $weight * $pricePerGram;
        } else {
            $method = 'Harga Berdasarkan Waktu';
            $basePrice = ($totalMinutes / 60) * $pricePerHour;
        }

        $fee = $basePrice * ($feePercentage / 100);

        $pricePerItem = $basePrice + $fee + $fixedCost;

        $pricePerItem = round($pricePerItem);

        $totalPrice = $pricePerItem * $quantity;

        return view('calculator.index', compact(
            'weight',
            'hours',
            'minutes',
            'quantity',
            'totalMinutes',
            'ratio',
            'method',
            'basePrice',
            'fee',
            'fixedCost',
            'pricePerItem',
            'totalPrice'
        ));
    }
}
