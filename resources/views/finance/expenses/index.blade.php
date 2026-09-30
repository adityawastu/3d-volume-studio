@extends ('layouts.dashboard')

@section ('title', 'Pengeluaran')
@section ('page-title', 'Keuangan')

@section ('content')
  <div class="space-y-6">
    <div>
      {{-- <h1 class="text-2xl font-bold text-slate-900">Pengeluaran</h1> --}}
      <p class="mt-1 text-sm text-slate-500">Catat dan kelola seluruh pengeluaran bisnis 3D printing.</p>
    </div>

    @if (session('success'))
      <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('success') }}
      </div>
    @endif

    <div class="grid items-start gap-6 xl:grid-cols-[380px_minmax(0,1fr)]">
      {{-- FORM PENGELUARAN --}}
      <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:sticky xl:top-6">
        <div class="mb-6">
          <h2 class="text-lg font-semibold text-slate-900">Tambah Pengeluaran</h2>
          {{-- <p class="mt-1 text-sm text-slate-500">Masukkan transaksi pengeluaran baru.</p> --}}
        </div>

        <form id="expense-form" action="{{ route('finance.expenses.store') }}" method="POST" class="space-y-5">
          @csrf

          <div>
            <label for="expense_date" class="mb-2 block text-sm font-medium text-slate-700"> Tanggal </label>

            <input
              type="date"
              id="expense_date"
              name="expense_date"
              value="{{ old('expense_date', now()->format('Y-m-d')) }}"
              class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 transition outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
              required
            />

            @error ('expense_date')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="name" class="mb-2 block text-sm font-medium text-slate-700"> Nama Pengeluaran </label>

            <input
              type="text"
              id="name"
              name="name"
              value="{{ old('name') }}"
              list="expense-name-list"
              placeholder="Contoh: Filament PLA"
              class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 transition outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
              required
            />

            <datalist id="expense-name-list">
              <option value="Plastik Kemasan Ukuran 15">
              <option value="Iklan Shopee">
              <option value="Kotak">
              <option value="Filament PLA">
              <option value="Bubble Wrap">
              <option value="Bubble Emailer">
              <option value="Refund Customer">
              <option value="Lakban Merah">
              <option value="Fillament Hitam dan Abu">
              <option value="Cutting Mate">
              <option value="Stiker">
              <option value="Kotak Packaging">
              <option value="Plastik Klip">
              <option value="Tank Potong">
              <option value="Pylox Hitam">
            </datalist>

            @error ('name')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="category" class="mb-2 block text-sm font-medium text-slate-700"> Kategori </label>

            <select
              id="category"
              name="category"
              class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 transition outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
              required
            >
              <option value="">Pilih kategori</option>

              @foreach ($categories as $category)
                <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>
                  {{ $category }}
                </option>
              @endforeach
            </select>

            @error ('category')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label for="quantity" class="mb-2 block text-sm font-medium text-slate-700"> Jumlah </label>

              <input
                type="number"
                id="quantity"
                name="quantity"
                min="0.01"
                step="0.01"
                value="{{ old('quantity', 1) }}"
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 transition outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                required
              />
            </div>

            <div>
              <label for="unit_price" class="mb-2 block text-sm font-medium text-slate-700"> Harga </label>

              <input
                type="number"
                id="unit_price"
                name="unit_price"
                min="0"
                step="1"
                value="{{ old('unit_price') }}"
                placeholder="0"
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 transition outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                required
              />
            </div>
          </div>

          @error ('quantity')
            <p class="text-sm text-red-600">{{ $message }}</p>
          @enderror

          @error ('unit_price')
            <p class="text-sm text-red-600">{{ $message }}</p>
          @enderror

          {{-- <div class="rounded-xl bg-slate-50 p-4">
            <div class="flex items-center justify-between gap-4">
              <span class="text-sm text-slate-500">Total Pengeluaran</span>

              <span id="expense-total-preview" class="text-lg font-bold text-slate-900"> Rp0 </span>
            </div>
          </div> --}}

          <div>
            <label for="notes" class="mb-2 block text-sm font-medium text-slate-700"> Catatan </label>

            <textarea
              id="notes"
              name="notes"
              rows="3"
              placeholder="Opsional..."
              class="w-full resize-none rounded-xl border border-slate-300 bg-white px-4 py-3 transition outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
              >{{ old('notes') }}</textarea
            >
          </div>

          <button
            type="submit"
            id="expense-submit"
            class="w-full rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-60"
          >
            Simpan Pengeluaran
          </button>
        </form>
      </section>

      {{-- BAGIAN KANAN --}}
      <div class="min-w-0 space-y-6">
        {{-- SUMMARY --}}
        <div class="grid gap-4 sm:grid-cols-2">
          <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Pengeluaran Bulan Ini</p>

            <p class="mt-2 text-2xl font-bold text-slate-900">Rp{{ number_format($totalThisMonth, 0, ',', '.') }}</p>
          </div>

          <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Transaksi Bulan Ini</p>

            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $transactionsThisMonth }}</p>
          </div>
        </div>

        {{-- TABEL --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
          {{-- FILTER --}}
          <div class="border-b border-slate-200 p-5">
            <form
              action="{{ route('finance.expenses.index') }}"
              method="GET"
              class="grid gap-3 md:grid-cols-[minmax(0,1fr)_170px_170px_auto]"
            >
              <input
                type="text"
                name="q"
                value="{{ $search }}"
                placeholder="Cari pengeluaran..."
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm transition outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
              />

              <select
                name="category"
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm transition outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
              >
                <option value="">Semua Kategori</option>

                @foreach ($categories as $category)
                  <option value="{{ $category }}" {{ $selectedCategory === $category ? 'selected' : '' }}>
                    {{ $category }}
                  </option>
                @endforeach
              </select>

              <input
                type="month"
                name="month"
                value="{{ $selectedMonth }}"
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm transition outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
              />

              <div class="flex gap-2">
                <button
                  type="submit"
                  class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800"
                >
                  Filter
                </button>

                @if ($search || $selectedCategory || $selectedMonth)
                  <a
                    href="{{ route('finance.expenses.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
                  >
                    Reset
                  </a>
                @endif
              </div>
            </form>
          </div>

          {{-- DATA --}}
          <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
              <thead class="border-b border-slate-200 bg-slate-50 text-slate-500">
                <tr>
                  <th class="px-5 py-4 font-medium">Tanggal</th>
                  <th class="px-5 py-4 font-medium">Pengeluaran</th>
                  <th class="px-5 py-4 font-medium">Kategori</th>
                  <th class="px-5 py-4 font-medium">Qty</th>
                  <th class="px-5 py-4 font-medium">Harga</th>
                  <th class="px-5 py-4 font-medium">Total</th>
                  <th class="px-5 py-4 text-center font-medium">Aksi</th>
                </tr>
              </thead>

              <tbody class="divide-y divide-slate-100">
                @forelse ($expenses as $expense)
                  <tr class="transition hover:bg-slate-50">
                    <td class="px-5 py-4 whitespace-nowrap text-slate-600">
                      {{ $expense->expense_date->format('d/m/Y') }}
                    </td>

                    <td class="px-5 py-4">
                      <p class="font-medium text-slate-900">{{ $expense->name }}</p>

                      @if ($expense->notes)
                        <p class="mt-1 max-w-xs truncate text-xs text-slate-400">{{ $expense->notes }}</p>
                      @endif
                    </td>

                    <td class="px-5 py-4">
                      <span
                        class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium whitespace-nowrap text-slate-600"
                      >
                        {{ $expense->category }}
                      </span>
                    </td>

                    <td class="px-5 py-4 text-slate-600">{{ number_format($expense->quantity, 0, ',', '.') }}</td>

                    <td class="px-5 py-4 whitespace-nowrap text-slate-600">
                      Rp{{ number_format($expense->unit_price, 0, ',', '.') }}
                    </td>

                    <td class="px-5 py-4 font-semibold whitespace-nowrap text-slate-900">
                      Rp{{ number_format($expense->total_amount, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-4">
                      <div class="flex items-center justify-center gap-2">
                        <button
                          type="button"
                          class="edit-expense-button rounded-lg bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-600 transition hover:bg-indigo-100"
                          data-update-url="{{ route('finance.expenses.update', $expense) }}"
                          data-date="{{ $expense->expense_date->format('Y-m-d') }}"
                          data-name="{{ $expense->name }}"
                          data-category="{{ $expense->category }}"
                          data-quantity="{{ $expense->quantity }}"
                          data-price="{{ $expense->unit_price }}"
                          data-notes="{{ $expense->notes }}"
                        >
                          Edit
                        </button>

                        <form action="{{ route('finance.expenses.destroy', $expense) }}" method="POST">
                          @csrf
                          @method ('DELETE')

                          <button
                            type="button"
                            class="delete-expense-button rounded-lg bg-red-50 px-3 py-2 text-xs font-medium text-red-600 transition hover:bg-red-100"
                            data-delete-url="{{ route('finance.expenses.destroy', $expense) }}"
                            data-name="{{ $expense->name }}"
                          >
                            Hapus
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>

                @empty
                  <tr>
                    <td colspan="7" class="px-5 py-12 text-center">
                      <p class="font-medium text-slate-600">Belum ada data pengeluaran</p>

                      <p class="mt-1 text-sm text-slate-400">Tambahkan pengeluaran melalui form di sebelah kiri.</p>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          {{-- PAGINATION --}}
          @if ($expenses->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">{{ $expenses->links('pagination.custom') }}</div>
          @endif
        </section>
      </div>
    </div>
  </div>

  </div>

  {{-- MODAL EDIT PENGELUARAN --}}
  <div id="edit-expense-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 p-4">
    <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">
      <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
        <div>
          <h2 class="text-lg font-semibold text-slate-900">Edit Pengeluaran</h2>
          <p class="mt-1 text-sm text-slate-500">Perbarui data transaksi pengeluaran.</p>
        </div>

        <button
          type="button"
          id="close-edit-expense"
          class="flex h-9 w-9 items-center justify-center rounded-lg text-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
        >
          ×
        </button>
      </div>

      <form id="edit-expense-form" method="POST" class="space-y-5 p-6">
        @csrf
        @method ('PATCH')

        <div>
          <label for="edit-expense-date" class="mb-2 block text-sm font-medium text-slate-700">Tanggal</label>
          <input
            type="date"
            id="edit-expense-date"
            name="expense_date"
            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 transition outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
            required
          />
        </div>

        <div>
          <label for="edit-expense-name" class="mb-2 block text-sm font-medium text-slate-700">Nama Pengeluaran</label>
          <input
            type="text"
            id="edit-expense-name"
            name="name"
            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 transition outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
            required
          />
        </div>

        <div>
          <label for="edit-expense-category" class="mb-2 block text-sm font-medium text-slate-700">Kategori</label>

          <select
            id="edit-expense-category"
            name="category"
            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 transition outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
            required
          >
            @foreach ($categories as $category)
              <option value="{{ $category }}">{{ $category }}</option>
            @endforeach
          </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label for="edit-expense-quantity" class="mb-2 block text-sm font-medium text-slate-700">Jumlah</label>
            <input
              type="number"
              id="edit-expense-quantity"
              name="quantity"
              min="0.01"
              step="0.01"
              class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 transition outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
              required
            />
          </div>

          <div>
            <label for="edit-expense-price" class="mb-2 block text-sm font-medium text-slate-700">Harga</label>
            <input
              type="number"
              id="edit-expense-price"
              name="unit_price"
              min="0"
              step="1"
              class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 transition outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
              required
            />
          </div>
        </div>

        <div class="rounded-xl bg-slate-50 p-4">
          <div class="flex items-center justify-between gap-4">
            <span class="text-sm text-slate-500">Total Pengeluaran</span>
            <span id="edit-expense-total" class="text-lg font-bold text-slate-900">Rp0</span>
          </div>
        </div>

        <div>
          <label for="edit-expense-notes" class="mb-2 block text-sm font-medium text-slate-700">Catatan</label>
          <textarea
            id="edit-expense-notes"
            name="notes"
            rows="3"
            class="w-full resize-none rounded-xl border border-slate-300 bg-white px-4 py-3 transition outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
          ></textarea>
        </div>

        <div class="flex justify-end gap-3 border-t border-slate-200 pt-5">
          <button
            type="button"
            id="cancel-edit-expense"
            class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
          >
            Batal
          </button>

          <button
            type="submit"
            id="edit-expense-submit"
            class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-60"
          >
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
  </div>

  {{-- MODAL HAPUS PENGELUARAN --}}
  <div id="delete-expense-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 p-4">
    <div class="w-full max-w-md rounded-2xl bg-white shadow-xl">
      <div class="p-6">
        <div class="flex items-start gap-4">
          <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-red-50 text-red-600">
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M8.75 1.75a1.75 1.75 0 0 0-1.75 1.75V4H4.5a.75.75 0 0 0 0 1.5h.55l.72 10.04A2.75 2.75 0 0 0 8.51 18h2.98a2.75 2.75 0 0 0 2.74-2.46l.72-10.04h.55a.75.75 0 0 0 0-1.5H13v-.5a1.75 1.75 0 0 0-1.75-1.75h-2.5ZM8.5 4v-.5a.25.25 0 0 1 .25-.25h2.5a.25.25 0 0 1 .25.25V4h-3ZM7.5 7.25a.75.75 0 0 1 .75.75v6a.75.75 0 0 1-1.5 0V8a.75.75 0 0 1 .75-.75Zm5 0a.75.75 0 0 1 .75.75v6a.75.75 0 0 1-1.5 0V8a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
            </svg>
          </div>

          <div>
            <h2 class="text-lg font-semibold text-slate-900">Hapus Pengeluaran?</h2>

            <p class="mt-2 text-sm leading-6 text-slate-500">Data <span id="delete-expense-name" class="font-semibold text-slate-700"></span> akan dihapus secara permanen.</p>
          </div>
        </div>

        <form id="delete-expense-form" method="POST" class="mt-6">
          @csrf
          @method ('DELETE')

          <div class="flex justify-end gap-3">
            <button
              type="button"
              id="cancel-delete-expense"
              class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
            >
              Batal
            </button>

            <button
              type="submit"
              id="confirm-delete-expense"
              class="rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-60"
            >
              Hapus
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection

@push ('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const rupiah = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
      });

      // FORM TAMBAH
      const expenseForm = document.getElementById('expense-form');
      const submitButton = document.getElementById('expense-submit');
      const quantityInput = document.getElementById('quantity');
      const unitPriceInput = document.getElementById('unit_price');
      const totalPreview = document.getElementById('expense-total-preview');

      function updateTotal() {
        if (!quantityInput || !unitPriceInput || !totalPreview) return;

        const quantity = parseFloat(quantityInput.value) || 0;
        const unitPrice = parseFloat(unitPriceInput.value) || 0;

        totalPreview.textContent = rupiah.format(quantity * unitPrice);
      }

      if (quantityInput && unitPriceInput) {
        quantityInput.addEventListener('input', updateTotal);
        unitPriceInput.addEventListener('input', updateTotal);
        updateTotal();
      }

      if (expenseForm && submitButton) {
        expenseForm.addEventListener('submit', function () {
          submitButton.disabled = true;
          submitButton.textContent = 'Menyimpan...';
        });
      }

      // MODAL EDIT
      const modal = document.getElementById('edit-expense-modal');
      const editForm = document.getElementById('edit-expense-form');
      const closeButton = document.getElementById('close-edit-expense');
      const cancelButton = document.getElementById('cancel-edit-expense');
      const editButtons = document.querySelectorAll('.edit-expense-button');

      const editDate = document.getElementById('edit-expense-date');
      const editName = document.getElementById('edit-expense-name');
      const editCategory = document.getElementById('edit-expense-category');
      const editQuantity = document.getElementById('edit-expense-quantity');
      const editPrice = document.getElementById('edit-expense-price');
      const editNotes = document.getElementById('edit-expense-notes');
      const editTotal = document.getElementById('edit-expense-total');
      const editSubmit = document.getElementById('edit-expense-submit');

      function updateEditTotal() {
        const quantity = parseFloat(editQuantity.value) || 0;
        const price = parseFloat(editPrice.value) || 0;

        editTotal.textContent = rupiah.format(quantity * price);
      }

      function openModal(button) {
        editForm.action = button.dataset.updateUrl;
        editDate.value = button.dataset.date;
        editName.value = button.dataset.name;
        editCategory.value = button.dataset.category;
        editQuantity.value = button.dataset.quantity;
        editPrice.value = button.dataset.price;
        editNotes.value = button.dataset.notes || '';

        updateEditTotal();

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.body.classList.add('overflow-hidden');
      }

      function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        document.body.classList.remove('overflow-hidden');
      }

      editButtons.forEach(function (button) {
        button.addEventListener('click', function () {
          openModal(button);
        });
      });

      closeButton.addEventListener('click', closeModal);
      cancelButton.addEventListener('click', closeModal);

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

      editQuantity.addEventListener('input', updateEditTotal);
      editPrice.addEventListener('input', updateEditTotal);

      editForm.addEventListener('submit', function () {
        editSubmit.disabled = true;
        editSubmit.textContent = 'Menyimpan...';
      });
    });

    // MODAL HAPUS
    const deleteModal = document.getElementById('delete-expense-modal');
    const deleteForm = document.getElementById('delete-expense-form');
    const deleteName = document.getElementById('delete-expense-name');
    const deleteButtons = document.querySelectorAll('.delete-expense-button');
    const cancelDeleteButton = document.getElementById('cancel-delete-expense');
    const confirmDeleteButton = document.getElementById('confirm-delete-expense');

    function openDeleteModal(button) {
      deleteForm.action = button.dataset.deleteUrl;
      deleteName.textContent = button.dataset.name;

      deleteModal.classList.remove('hidden');
      deleteModal.classList.add('flex');

      document.body.classList.add('overflow-hidden');
    }

    function closeDeleteModal() {
      deleteModal.classList.add('hidden');
      deleteModal.classList.remove('flex');

      document.body.classList.remove('overflow-hidden');
    }

    deleteButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        openDeleteModal(button);
      });
    });

    cancelDeleteButton.addEventListener('click', closeDeleteModal);

    deleteModal.addEventListener('click', function (event) {
      if (event.target === deleteModal) {
        closeDeleteModal();
      }
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && !deleteModal.classList.contains('hidden')) {
        closeDeleteModal();
      }
    });
  </script>
@endpush
