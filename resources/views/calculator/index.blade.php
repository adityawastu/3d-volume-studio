@extends ('layouts.dashboard')

@section ('title', 'Hitung Harga')
@section ('page-title', 'Hitung Harga')

@section ('content')
  <div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Hitung Harga Model 3D</h1>
        <p class="mt-1 text-sm text-slate-500">Riwayat perhitungan</p>
      </div>

      {{-- button cari --}}
      <div class="flex flex-col gap-3 sm:flex-row">
        {{-- <form action="{{ route('orders.index') }}" method="GET" class="flex">
          <input
            type="text"
            name="q"
            value="{{ $search ?? '' }}"
            placeholder="Cari nomor order..."
            class="w-full rounded-l-xl border border-slate-300 bg-white px-4 py-2.5 text-sm transition outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 sm:w-64"
          />

          <button
            type="submit"
            class="rounded-r-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700"
          >
            Cari
          </button>
        </form>

        @if (request('q'))
          <a
            href="{{ route('orders.index') }}"
            class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
          >
            Reset
          </a>
        @endif --}}

        <a
          href="{{ route('calculator.create') }}"
          class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
        >
          Hitung Harga
        </a>
      </div>
    </div>

    @if (session('success'))
      <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('success') }}
      </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="border-b border-slate-200 bg-slate-50">
            <tr class="text-slate-500">
              <th class="px-5 py-4 font-medium">Referensi</th>
              <th class="px-5 py-4 font-medium">Berat</th>
              <th class="px-5 py-4 font-medium">Waktu</th>
              <th class="px-5 py-4 font-medium">Ratio</th>
              <th class="px-5 py-4 font-medium">Jumlah CO Shopee</th>
              <th class="px-5 py-4 font-medium">Harga</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-100">
            @forelse ($calculations as $calculation)
              <tr>
                <td class="px-5 py-4 text-slate-600">{{ $calculation->reference_no }}</td>
                <td class="px-5 py-4 text-slate-600">{{ number_format($calculation->weight, 0, ',', '.') }} gram</td>
                <td class="px-5 py-4 text-slate-600">{{ $calculation->hours }}j {{ $calculation->minutes }}m</td>
                <td class="px-5 py-4 text-slate-600">{{ number_format($calculation->ratio, 2, ',', '.') }}</td>
                <td class="px-5 py-4 text-slate-600">{{ number_format($calculation->total_price / 600) }}</td>
                <td class="px-5 py-4 text-slate-600">Rp{{ number_format($calculation->total_price, 0, ',', '.') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-5 py-8 text-center text-slate-500">Belum ada riwayat perhitungan.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if ($calculations->hasPages())
        <div class="border-t border-slate-200 px-5 py-4">{{ $calculations->links('pagination.custom') }}</div>
      @endif
    </div>
  </div>

@endsection
