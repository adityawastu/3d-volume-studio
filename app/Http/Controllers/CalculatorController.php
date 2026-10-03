<?php

namespace App\Http\Controllers;

use App\Models\Calculation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CalculatorController extends Controller
{
    public function index()
    {
        $calculations = Calculation::latest()->paginate(10);
        return view('calculator.index', compact('calculations'));
    }

    public function create (){
        $submissionToken = (string) Str::uuid();
        return view('calculator.create', compact('submissionToken'));
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'submission_token' => ['required', 'uuid'],
            'weight' => ['required', 'numeric', 'min:0.01'],
            'hours' => ['required', 'integer', 'min:0'],
            'minutes' => ['required', 'integer', 'min:0', 'max:59'],
            // 'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $pricePerGram = 600;
        $pricePerHour = 25000;
        $feePercentage = 8.25;
        $fixedCost = 3750;
        $ratioLimit = 2.5;

        $weight = (float) $request->weight;
        $hours = (int) $request->hours;
        $minutes = (int) $request->minutes;
        // $quantity = (int) $request->quantity;

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
        $pricePerItem = round($basePrice + $fee + $fixedCost);
        // $totalPrice = $pricePerItem * $quantity;
        $totalPrice = $pricePerItem;


        $data = [
            'weight' => $weight,
            'hours' => $hours,
            'minutes' => $minutes,
            'total_minutes' => $totalMinutes,
            'ratio' => $ratio,
            'method' => $method,
            'base_price' => $basePrice,
            'fee' => $fee,
            'fixed_cost' => $fixedCost,
            'price_per_item' => $pricePerItem,
            // 'quantity' => $quantity,
            'total_price' => $totalPrice,
        ];

        $calculation = Calculation::where('submission_token', $request->submission_token)->first();

        if ($calculation) {
            $calculation->update($data);
        } else {
            $calculation = Calculation::create(array_merge($data, [
                'submission_token' => $request->submission_token,
                'reference_no' => $this->generateReference(),
                'status' => 'pending',
            ]));
        }

        $submissionToken = $request->submission_token;

        return view('calculator.create', compact(
            'calculation',
            'submissionToken',
            'weight',
            'hours',
            'minutes',
            // 'quantity',
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

    private function generateReference(): string
    {
        $nextId = (Calculation::max('id') ?? 0) + 1;

        return 'ORD-' . now()->format('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }
}
