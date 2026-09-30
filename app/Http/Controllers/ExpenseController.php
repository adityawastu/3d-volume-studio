<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $categories = [
            'Material',
            'Packaging',
            'Marketing',
            'Refund',
            'Peralatan',
            'Finishing',
            'Operasional',
            'Lainnya',
        ];

        $search = $request->input('q');
        $selectedCategory = $request->input('category');
        $selectedMonth = $request->input('month');

        $query = Expense::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('category', 'like', '%' . $search . '%')
                        ->orWhere('notes', 'like', '%' . $search . '%');
                });
            })
            ->when($selectedCategory, function ($query, $selectedCategory) {
                $query->where('category', $selectedCategory);
            })
            ->when($selectedMonth, function ($query, $selectedMonth) {
                if (preg_match('/^(\d{4})-(\d{2})$/', $selectedMonth, $matches)) {
                    $query->whereYear('expense_date', $matches[1])
                        ->whereMonth('expense_date', $matches[2]);
                }
            });

        $expenses = $query
            ->latest('expense_date')
            ->latest('id')
            ->paginate(5)
            ->withQueryString();

        $totalThisMonth = Expense::whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->sum('total_amount');

        $transactionsThisMonth = Expense::whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->count();

        return view('finance.expenses.index', compact(
            'expenses',
            'categories',
            'search',
            'selectedCategory',
            'selectedMonth',
            'totalThisMonth',
            'transactionsThisMonth'
        ));
    }

    public function store(Request $request)
    {
        $categories = [
            'Material',
            'Packaging',
            'Marketing',
            'Refund',
            'Peralatan',
            'Finishing',
            'Operasional',
            'Lainnya',
        ];

        $validated = $request->validate([
            'expense_date' => ['required', 'date'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in($categories)],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['total_amount'] =
            (float) $validated['quantity'] * (float) $validated['unit_price'];

        Expense::create($validated);

        return redirect()
            ->route('finance.expenses.index')
            ->with('success', 'Pengeluaran berhasil disimpan.');
    }

    public function update(Request $request, Expense $expense)
    {
        $categories = [
            'Material',
            'Packaging',
            'Marketing',
            'Refund',
            'Peralatan',
            'Finishing',
            'Operasional',
            'Lainnya',
        ];

        $validated = $request->validate([
            'expense_date' => ['required', 'date'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in($categories)],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['total_amount'] =
            (float) $validated['quantity'] * (float) $validated['unit_price'];

        $expense->update($validated);

        return redirect()
            ->route('finance.expenses.index')
            ->with('success', 'Pengeluaran berhasil diperbarui.');
    }


    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()
            ->route('finance.expenses.index')
            ->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
