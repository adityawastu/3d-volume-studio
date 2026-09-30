@extends ('layouts.dashboard')

@section ('title', 'Orders')
@section ('page-title', 'Orders')

@section ('content')
  <div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Orders</h1>
        <p class="mt-1 text-sm text-slate-500">Kelola hasil perhitungan dan status order.</p>
      </div>

      {{-- button cari --}}
      <div class="flex flex-col gap-3 sm:flex-row">
        <form action="{{ route('orders.index') }}" method="GET" class="flex">
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
        @endif

        <a
          href="{{ route('calculator.index') }}"
          class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
        >
          Buat Perhitungan
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
              <th class="px-5 py-4 font-medium">Qty</th>
              <th class="px-5 py-4 font-medium">Harga</th>
              <th class="px-5 py-4 font-medium">Status</th>
              <th class="px-5 py-4 text-center font-medium">Aksi</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-100">
            @forelse ($orders as $order)
              <tr>
                <td class="px-5 py-4 font-medium text-slate-900">{{ $order->reference_no }}</td>

                <td class="px-5 py-4 text-slate-600">{{ number_format($order->weight, 0, ',', '.') }} gram</td>

                <td class="px-5 py-4 text-slate-600">{{ $order->hours }}j {{ $order->minutes }}m</td>

                <td class="px-5 py-4 text-slate-600">{{ $order->quantity }}</td>

                <td class="px-5 py-4 font-semibold text-slate-900">
                  Rp{{ number_format($order->total_price, 0, ',', '.') }}
                </td>

                <td class="px-5 py-4">
                  @if ($order->status === 'pending')
                    <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700">Pending</span>
                  @elseif ($order->status === 'fixed')
                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">Fixed</span>
                  @else
                    <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-medium text-red-700">Tidak Fix</span>
                  @endif
                </td>

                <td class="px-5 py-4">
                  @if ($order->status === 'pending')
                    <div class="flex items-center justify-center gap-2">
                      <form action="{{ route('orders.fix', $order) }}" method="POST">
                        @csrf
                        @method ('PATCH')

                        <button
                          type="submit"
                          title="Fix Order"
                          class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-600 font-bold text-white transition hover:bg-emerald-700"
                        >
                          ✓
                        </button>
                      </form>

                      <form action="{{ route('orders.cancel', $order) }}" method="POST">
                        @csrf
                        @method ('PATCH')

                        <button
                          type="submit"
                          title="Tidak Fix"
                          class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-600 font-bold text-white transition hover:bg-red-700"
                        >
                          ×
                        </button>
                      </form>
                    </div>

                  @else
                    <div class="text-center text-slate-400">-</div>

                  @endif
                </td>
              </tr>

            @empty
              <tr>
                <td colspan="7" class="px-5 py-12 text-center text-slate-500">Belum ada data perhitungan.</td>
              </tr>

            @endforelse
          </tbody>
        </table>
      </div>

      @if ($orders->hasPages())
        <div class="border-t border-slate-200 p-4">{{ $orders->links() }}</div>
      @endif
    </div>
  </div>

@endsection
