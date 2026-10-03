@extends ('layouts.dashboard')

@section ('title', 'Dashboard')

@section ('page-title', 'Dashboard')

@section ('content')
  <div class="space-y-8">
    <div>
      <h1 class="text-2xl font-bold tracking-tight">Dashboard</h1>

      <p class="mt-1 text-sm text-slate-500">Ringkasan aktivitas bisnis 3D printing.</p>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
      <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-sm text-slate-500">Total Order</p>

        <p class="mt-2 text-3xl font-bold">{{ $summary['orders'] }}</p>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-sm text-slate-500">Pendapatan</p>

        <p class="mt-2 text-3xl font-bold">Rp{{ number_format($summary['revenue'], 0, ',', '.') }}</p>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-sm text-slate-500">Print Aktif</p>

        <p class="mt-2 text-3xl font-bold">{{ $summary['active_prints'] }}</p>
      </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h3 class="font-semibold">Kalkulator Harga</h3>
          <p class="mt-1 text-sm text-slate-500">Hitung harga cetak berdasarkan berat dan waktu printing.</p>
        </div>

        <a
          href="{{ route('calculator.create') }}"
          class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
        >
          Buka Kalkulator
        </a>
      </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-200 px-6 py-4">
        <h3 class="font-semibold">Order Terbaru</h3>

        <p class="mt-1 text-sm text-slate-500">Daftar order terbaru akan tampil di sini.</p>
      </div>

      @if (count($latestOrders) > 0)
        <div class="p-6">Data order tersedia.</div>

      @else
        <div class="flex min-h-60 items-center justify-center p-6 text-center">
          <div>
            <p class="font-medium text-slate-700">Belum ada order</p>

            <p class="mt-1 text-sm text-slate-400">Order baru nantinya akan muncul di sini.</p>
          </div>
        </div>

      @endif
    </div>
  </div>

@endsection
