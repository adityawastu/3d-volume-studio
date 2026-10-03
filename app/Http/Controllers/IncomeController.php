<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        return view('finance.incomes.index', $this->pageData($request));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'income_date' => ['required', 'date'],
            'customer_name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        Income::create([
            'reference_no' => $this->generateReference(),
            'source' => 'manual',
            'external_order_no' => null,
            'income_date' => $validated['income_date'],
            'customer_name' => $validated['customer_name'],
            'description' => $validated['description'],
            'gross_amount' => $validated['amount'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('finance.incomes.index')
            ->with('success', 'Pemasukan berhasil disimpan.');
    }

    public function previewImport(Request $request)
    {
        $request->validateWithBag('import', [
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'],
        ]);

        try {
            $rows = $this->parseShopeeFile(
                $request->file('file')->getRealPath()
            );
        } catch (\Throwable $e) {
            return redirect()
                ->route('finance.incomes.index')
                ->with('error', 'File Excel tidak dapat dibaca.');
        }

        if (empty($rows)) {
            return redirect()
                ->route('finance.incomes.index')
                ->with('error', 'Tidak ditemukan data Order pada file Shopee.');
        }

        $orderNumbers = array_column($rows, 'external_order_no');

        $existingOrders = Income::where('source', 'shopee')
            ->whereIn('external_order_no', $orderNumbers)
            ->pluck('external_order_no')
            ->all();

        foreach ($rows as &$row) {
            $row['duplicate'] = in_array(
                $row['external_order_no'],
                $existingOrders,
                true
            );
        }

        unset($row);

        $newRows = array_filter(
            $rows,
            fn($row) => !$row['duplicate']
        );

        $token = (string) Str::uuid();

        Cache::put(
            'income_import_' . $token,
            $rows,
            now()->addMinutes(30)
        );

        $importSummary = [
            'total' => count($rows),
            'new' => count($newRows),
            'duplicate' => count($rows) - count($newRows),
            'amount' => array_sum(
                array_column($newRows, 'amount')
            ),
        ];

        return view(
            'finance.incomes.index',
            array_merge(
                $this->pageData($request),
                [
                    'importPreview' => $rows,
                    'importSummary' => $importSummary,
                    'importToken' => $token,
                ]
            )
        );
    }

    public function confirmImport(Request $request)
    {
        $request->validate([
            'import_token' => ['required', 'uuid'],
        ]);

        $cacheKey = 'income_import_' . $request->import_token;

        $rows = Cache::get($cacheKey);

        if (!$rows) {
            return redirect()
                ->route('finance.incomes.index')
                ->with('error', 'Data import sudah kedaluwarsa. Upload file kembali.');
        }

        $imported = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            $exists = Income::where('source', 'shopee')
                ->where('external_order_no', $row['external_order_no'])
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            Income::create([
                'reference_no' => $this->generateReference(),
                'source' => 'shopee',
                'external_order_no' => $row['external_order_no'],
                'income_date' => $row['income_date'],
                'order_date' => $row['order_date'],
                'released_at' => $row['released_at'],
                'customer_name' => $row['customer_name'],
                'description' => 'Pesanan Shopee',
                'gross_amount' => $row['gross_amount'],
                'amount' => $row['amount'],
                'payment_method' => $row['payment_method'],
                'shipping_service' => $row['shipping_service'],
                'courier' => $row['courier'],
            ]);

            $imported++;
        }

        Cache::forget($cacheKey);

        return redirect()
            ->route('finance.incomes.index')
            ->with(
                'success',
                "{$imported} pemasukan Shopee berhasil diimport. {$skipped} data dilewati karena sudah ada."
            );
    }

    private function pageData(Request $request): array
    {
        $search = $request->input('q');
        $selectedSource = $request->input('source');
        $selectedMonth = $request->input('month');

        $query = Income::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('reference_no', 'like', '%' . $search . '%')
                        ->orWhere('external_order_no', 'like', '%' . $search . '%')
                        ->orWhere('customer_name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->when($selectedSource, function ($query, $selectedSource) {
                $query->where('source', $selectedSource);
            })
            ->when($selectedMonth, function ($query, $selectedMonth) {
                if (preg_match('/^(\d{4})-(\d{2})$/', $selectedMonth, $matches)) {
                    $query->whereYear('income_date', $matches[1])
                        ->whereMonth('income_date', $matches[2]);
                }
            });

        $incomes = $query
            ->latest('income_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $totalThisMonth = Income::whereYear('income_date', now()->year)
            ->whereMonth('income_date', now()->month)
            ->sum('amount');

        $shopeeThisMonth = Income::where('source', 'shopee')
            ->whereYear('income_date', now()->year)
            ->whereMonth('income_date', now()->month)
            ->sum('amount');

        $manualThisMonth = Income::where('source', 'manual')
            ->whereYear('income_date', now()->year)
            ->whereMonth('income_date', now()->month)
            ->sum('amount');

        return compact(
            'incomes',
            'search',
            'selectedSource',
            'selectedMonth',
            'totalThisMonth',
            'shopeeThisMonth',
            'manualThisMonth'
        );
    }

    private function parseShopeeFile(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);

        $sheet = $spreadsheet->getSheetByName('Penghasilan')
            ?? $spreadsheet->getActiveSheet();

        $highestRow = $sheet->getHighestDataRow();

        $rows = [];
        $seen = [];

        for ($row = 4; $row <= $highestRow; $row++) {
            $viewType = trim(
                (string) $sheet->getCell("B{$row}")->getValue()
            );

            if (strtolower($viewType) !== 'order') {
                continue;
            }

            $orderNumber = trim(
                (string) $sheet->getCell("C{$row}")->getValue()
            );

            if ($orderNumber === '' || isset($seen[$orderNumber])) {
                continue;
            }

            $seen[$orderNumber] = true;

            $orderDate = $this->normalizeExcelDate(
                $sheet->getCell("G{$row}")->getValue()
            );

            $releasedAt = $this->normalizeExcelDate(
                $sheet->getCell("H{$row}")->getValue()
            );

            $rows[] = [
                'external_order_no' => $orderNumber,
                'order_date' => $orderDate,
                'released_at' => $releasedAt,
                'income_date' => $releasedAt ?? $orderDate,
                'gross_amount' => (float) $sheet->getCell("L{$row}")->getCalculatedValue(),
                'amount' => (float) $sheet->getCell("K{$row}")->getCalculatedValue(),
                'customer_name' => trim((string) $sheet->getCell("AL{$row}")->getValue()),
                'payment_method' => trim((string) $sheet->getCell("AN{$row}")->getValue()),
                'shipping_service' => trim((string) $sheet->getCell("AR{$row}")->getValue()),
                'courier' => trim((string) $sheet->getCell("AS{$row}")->getValue()),
            ];
        }

        return $rows;
    }

    private function normalizeExcelDate(mixed $value): ?string
    {
        if ($value === null || $value === '' || $value === '-') {
            return null;
        }

        if (is_numeric($value)) {
            return ExcelDate::excelToDateTimeObject($value)
                ->format('Y-m-d');
        }

        try {
            return Carbon::parse((string) $value)
                ->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    private function generateReference(): string
    {
        $nextId = (Income::max('id') ?? 0) + 1;

        return 'INC-' .
            now()->format('Ymd') .
            '-' .
            str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }
}
