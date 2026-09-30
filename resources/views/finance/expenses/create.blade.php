@extends ('layouts.dashboard')

@section ('title', 'Tambah Pengeluaran')
@section ('page-title', 'Keuangan')

@section ('content')
  <div class="mx-auto max-w-3xl space-y-6">
    <div>
      <a
        href="{{ route('finance.expenses.index') }}"
        class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-slate-900"
      >
        <span>←</span>
        <span>Kembali ke Pengeluaran</span>
      </a>
    </div>

    <div>
      <h1 class="text-2xl font-bold text-slate-900">Tambah Pengeluaran</h1>
      <p class="mt-1 text-sm text-slate-500">Masukkan pengeluaran bisnis yang baru.</p>
    </div>

    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <form action="{{ route('finance.expenses.store') }}" method="POST" class="space-y-5">
        @csrf

        <div>
          <label for="expense_date" class="mb-2 block text-sm font-medium text-slate-700">Tanggal</label>

          <input
            type="date"
            id="expense_date"
            name="expense_date"
            value="{{ old('expense_date', now()->format('Y-m-d')) }}"
            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
            required
          />
        </div>

        <div>
          <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Nama Pengeluaran</label>

          <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name') }}"
            placeholder="Contoh: Filament PLA"
            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
            required
          />
        </div>

        <div>
          <label for="category" class="mb-2 block text-sm font-medium text-slate-700">Kategori</label>

          <select
            id="category"
            name="category"
            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
            required
          >
            <option value="">Pilih kategori</option>
            <option value="Material">Material</option>
            <option value="Packaging">Packaging</option>
            <option value="Marketing">Marketing</option>
            <option value="Refund">Refund</option>
            <option value="Peralatan">Peralatan</option>
            <option value="Finishing">Finishing</option>
            <option value="Operasional">Operasional</option>
            <option value="Lainnya">Lainnya</option>
          </select>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label for="quantity" class="mb-2 block text-sm font-medium text-slate-700">Jumlah</label>

            <input
              type="number"
              id="quantity"
              name="quantity"
              min="0.01"
              step="0.01"
              value="{{ old('quantity', 1) }}"
              class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
              required
            />
          </div>

          <div>
            <label for="unit_price" class="mb-2 block text-sm font-medium text-slate-700">Harga Satuan</label>

            <input
              type="number"
              id="unit_price"
              name="unit_price"
              min="0"
              value="{{ old('unit_price') }}"
              placeholder="0"
              class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
              required
            />
          </div>
        </div>

        <div>
          <label for="notes" class="mb-2 block text-sm font-medium text-slate-700">Catatan</label>

          <textarea
            id="notes"
            name="notes"
            rows="3"
            placeholder="Opsional..."
            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
            >{{ old('notes') }}</textarea
          >
        </div>

        <button
          type="submit"
          class="w-full rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
        >
          Simpan Pengeluaran
        </button>
      </form>
    </section>
  </div>

@endsection
