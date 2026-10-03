@extends ('layouts.dashboard')

@section ('title', 'Pemasukan')
@section ('page-title', 'Keuangan')

@section ('content')
  <div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Pemasukan</h1>
        <p class="mt-1 text-sm text-slate-500">Kelola pemasukan manual dan transaksi dari Shopee.</p>
      </div>

      <button
        type="button"
        id="open-import-income"
        class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
      >
        Import Excel Shopee
      </button>
    </div>

    @if (session('success'))
      <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('success') }}
      </div>
    @endif

    @if (session('error'))
      <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        {{ session('error') }}
      </div>
    @endif

    <div class="grid items-start gap-6 xl:grid-cols-[380px_minmax(0,1fr)]">
      {{-- INPUT MANUAL --}}
      <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:sticky xl:top-6">
        <div class="mb-6">
          <h2 class="text-lg font-semibold text-slate-900">Input Manual</h2>
          <p class="mt-1 text-sm text-slate-500">Untuk pesanan di luar Shopee.</p>
        </div>

        <form id="income-form" action="{{ route('finance.incomes.store') }}" method="POST" class="space-y-5">
          @csrf

          <div>
            <label for="income_date" class="mb-2 block text-sm font-medium text-slate-700">Tanggal Pemasukan</label>
            <input
              type="date"
              id="income_date"
              name="income_date"
              value="{{ old('income_date', now()->format('Y-m-d')) }}"
              class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
              required
            />
          </div>

          <div>
            <label for="customer_name" class="mb-2 block text-sm font-medium text-slate-700">Customer</label>
            <input
              type="text"
              id="customer_name"
              name="customer_name"
              value="{{ old('customer_name') }}"
              placeholder="Nama customer"
              class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
              required
            />
          </div>

          <div>
            <label for="description" class="mb-2 block text-sm font-medium text-slate-700">Keterangan</label>
            <input
              type="text"
              id="description"
              name="description"
              value="{{ old('description') }}"
              placeholder="Contoh: Cetak bracket 3D"
              class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
              required
            />
          </div>

          <div>
            <label for="amount" class="mb-2 block text-sm font-medium text-slate-700">Nominal</label>
            <input
              type="number"
              id="amount"
              name="amount"
              min="0"
              step="1"
              value="{{ old('amount') }}"
              placeholder="0"
              class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
              required
            />
          </div>

          <div>
            <label for="payment_method" class="mb-2 block text-sm font-medium text-slate-700">Metode Pembayaran</label>

            <select
              id="payment_method"
              name="payment_method"
              class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
              required
            >
              <option value="">Pilih metode</option>
              <option value="Transfer">Transfer</option>
              <option value="Cash">Cash</option>
              <option value="QRIS">QRIS</option>
              <option value="E-Wallet">E-Wallet</option>
              <option value="Lainnya">Lainnya</option>
            </select>
          </div>

          <div>
            <label for="notes" class="mb-2 block text-sm font-medium text-slate-700">Catatan</label>
            <textarea
              id="notes"
              name="notes"
              rows="3"
              placeholder="Opsional..."
              class="w-full resize-none rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
              >{{ old('notes') }}</textarea
            >
          </div>

          @if ($errors->any() && !$errors->import->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-4">
              <ul class="list-inside list-disc space-y-1 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <button
            type="submit"
            id="income-submit"
            class="w-full rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-60"
          >
            Simpan Pemasukan
          </button>
        </form>
      </section>

      {{-- BAGIAN KANAN --}}
      <div class="min-w-0 space-y-6">
        <div class="grid gap-4 md:grid-cols-3">
          <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Pemasukan Bulan Ini</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">Rp{{ number_format($totalThisMonth, 0, ',', '.') }}</p>
          </div>

          <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Shopee</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">Rp{{ number_format($shopeeThisMonth, 0, ',', '.') }}</p>
          </div>

          <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Manual</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">Rp{{ number_format($manualThisMonth, 0, ',', '.') }}</p>
          </div>
        </div>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
          <div class="border-b border-slate-200 p-5">
            <form
              action="{{ route('finance.incomes.index') }}"
              method="GET"
              class="grid gap-3 md:grid-cols-[minmax(0,1fr)_150px_170px_auto]"
            >
              <input
                type="text"
                name="q"
                value="{{ $search }}"
                placeholder="Cari pemasukan..."
                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
              />

              <select
                name="source"
                class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
              >
                <option value="">Semua Sumber</option>
                <option value="shopee" {{ $selectedSource === 'shopee' ? 'selected' : '' }}>Shopee</option>
                <option value="manual" {{ $selectedSource === 'manual' ? 'selected' : '' }}>Manual</option>
              </select>

              <input
                type="month"
                name="month"
                value="{{ $selectedMonth }}"
                class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
              />

              <div class="flex gap-2">
                <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-medium text-white">
                  Filter
                </button>

                @if ($search || $selectedSource || $selectedMonth)
                  <a
                    href="{{ route('finance.incomes.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-600"
                  >
                    Reset
                  </a>
                @endif
              </div>
            </form>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
              <thead class="border-b border-slate-200 bg-slate-50 text-slate-500">
                <tr>
                  <th class="px-5 py-4 font-medium">Tanggal</th>
                  <th class="px-5 py-4 font-medium">Referensi</th>
                  <th class="px-5 py-4 font-medium">Sumber</th>
                  <th class="px-5 py-4 font-medium">Customer</th>
                  <th class="px-5 py-4 font-medium">Pembayaran</th>
                  <th class="px-5 py-4 font-medium">Pemasukan</th>
                </tr>
              </thead>

              <tbody class="divide-y divide-slate-100">
                @forelse ($incomes as $income)
                  <tr class="transition hover:bg-slate-50">
                    <td class="px-5 py-4 whitespace-nowrap text-slate-600">
                      {{ $income->income_date->format('d/m/Y') }}
                    </td>

                    <td class="px-5 py-4">
                      <p class="font-medium text-slate-900">
                        {{ $income->external_order_no ?? $income->reference_no }}
                      </p>

                      @if ($income->description)
                        <p class="mt-1 text-xs text-slate-400">{{ $income->description }}</p>
                      @endif
                    </td>

                    <td class="px-5 py-4">
                      @if ($income->source === 'shopee')
                        <span class="rounded-full bg-orange-50 px-3 py-1 text-xs font-medium text-orange-700"
                          >Shopee</span
                        >
                      @else
                        <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700"
                          >Manual</span
                        >
                      @endif
                    </td>

                    <td class="px-5 py-4 text-slate-600">{{ $income->customer_name ?? '-' }}</td>

                    <td class="px-5 py-4 text-slate-600">{{ $income->payment_method ?? '-' }}</td>

                    <td class="px-5 py-4 font-semibold whitespace-nowrap text-slate-900">
                      Rp{{ number_format($income->amount, 0, ',', '.') }}
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-slate-500">Belum ada data pemasukan.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          @if ($incomes->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">{{ $incomes->links('pagination.custom') }}</div>
          @endif
        </section>
      </div>
    </div>
  </div>

  {{-- MODAL IMPORT SHOPEE --}}
  <div id="import-income-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 p-4">
    <div class="max-h-[90vh] w-full max-w-3xl overflow-y-auto rounded-2xl bg-white shadow-xl">
      <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
        <div>
          <h2 class="text-lg font-semibold text-slate-900">Import Pemasukan Shopee</h2>
          <p class="mt-1 text-sm text-slate-500">Upload laporan Income Shopee dalam format Excel.</p>
        </div>

        <button
          type="button"
          id="close-import-income"
          class="flex h-9 w-9 items-center justify-center rounded-lg text-xl text-slate-400 hover:bg-slate-100"
        >
          ×
        </button>
      </div>

      <div class="p-6">
        @isset ($importPreview)
          <div class="mb-6 grid gap-3 sm:grid-cols-4">
            <div class="rounded-xl bg-slate-50 p-4">
              <p class="text-xs text-slate-500">Ditemukan</p>
              <p class="mt-1 text-xl font-bold">{{ $importSummary['total'] }}</p>
            </div>

            <div class="rounded-xl bg-emerald-50 p-4">
              <p class="text-xs text-emerald-600">Data Baru</p>
              <p class="mt-1 text-xl font-bold text-emerald-700">{{ $importSummary['new'] }}</p>
            </div>

            <div class="rounded-xl bg-amber-50 p-4">
              <p class="text-xs text-amber-600">Duplikat</p>
              <p class="mt-1 text-xl font-bold text-amber-700">{{ $importSummary['duplicate'] }}</p>
            </div>

            <div class="rounded-xl bg-indigo-50 p-4">
              <p class="text-xs text-indigo-600">Pemasukan Baru</p>
              <p class="mt-1 text-lg font-bold text-indigo-700">Rp{{ number_format($importSummary['amount'], 0, ',', '.') }}</p>
            </div>
          </div>

          <div class="mb-6 overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full text-left text-sm">
              <thead class="bg-slate-50 text-slate-500">
                <tr>
                  <th class="px-4 py-3">Pesanan</th>
                  <th class="px-4 py-3">Dana Dilepas</th>
                  <th class="px-4 py-3">Customer</th>
                  <th class="px-4 py-3">Pemasukan</th>
                  <th class="px-4 py-3">Status</th>
                </tr>
              </thead>

              <tbody class="divide-y divide-slate-100">
                @foreach ($importPreview as $row)
                  <tr>
                    <td class="px-4 py-3 font-medium">{{ $row['external_order_no'] }}</td>
                    <td class="px-4 py-3">{{ $row['released_at'] ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $row['customer_name'] }}</td>
                    <td class="px-4 py-3 font-semibold">Rp{{ number_format($row['amount'], 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                      @if ($row['duplicate'])
                        <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700"
                          >Sudah Ada</span
                        >
                      @else
                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700"
                          >Baru</span
                        >
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <form action="{{ route('finance.incomes.import.confirm') }}" method="POST">
            @csrf

            <input type="hidden" name="import_token" value="{{ $importToken }}" />

            <div class="flex justify-end gap-3">
              <button
                type="button"
                class="cancel-import-income rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700"
              >
                Batal
              </button>

              <button
                type="submit"
                class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white disabled:opacity-50"
                {{ $importSummary['new'] === 0 ? 'disabled' : '' }}
              >
                Import {{ $importSummary['new'] }} Pemasukan
              </button>
            </div>
          </form>

        @else
          <form
            action="{{ route('finance.incomes.import.preview') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-5"
          >
            @csrf

            <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-6">
              <label for="income-file" class="mb-2 block text-sm font-medium text-slate-700"> File Excel Shopee </label>

              <input
                type="file"
                id="income-file"
                name="file"
                accept=".xlsx,.xls"
                class="block w-full text-sm text-slate-500"
                required
              />

              <p class="mt-2 text-xs text-slate-400">Gunakan laporan Income Shopee berformat XLSX atau XLS.</p>
            </div>

            @if ($errors->import->any())
              <div class="rounded-xl border border-red-200 bg-red-50 p-4">
                @foreach ($errors->import->all() as $error)
                  <p class="text-sm text-red-600">{{ $error }}</p>
                @endforeach
              </div>
            @endif

            <div class="flex justify-end gap-3">
              <button
                type="button"
                class="cancel-import-income rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700"
              >
                Batal
              </button>

              <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white">
                Preview Data
              </button>
            </div>
          </form>

        @endisset
      </div>
    </div>
  </div>

@endsection

@push ('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const modal = document.getElementById('import-income-modal');
      const openButton = document.getElementById('open-import-income');
      const closeButton = document.getElementById('close-import-income');
      const cancelButtons = document.querySelectorAll('.cancel-import-income');
      const incomeForm = document.getElementById('income-form');
      const incomeSubmit = document.getElementById('income-submit');

      function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
      }

      function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
      }

      openButton.addEventListener('click', openModal);
      closeButton.addEventListener('click', closeModal);

      cancelButtons.forEach(function (button) {
        button.addEventListener('click', closeModal);
      });

      modal.addEventListener('click', function (event) {
        if (event.target === modal) {
          closeModal();
        }
      });

      document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
          closeModal();
        }
      });

      if (incomeForm && incomeSubmit) {
        incomeForm.addEventListener('submit', function () {
          incomeSubmit.disabled = true;
          incomeSubmit.textContent = 'Menyimpan...';
        });
      }

      @if (isset($importPreview) || $errors->import->any())
      openModal();
      @endif
    });
  </script>
@endpush
