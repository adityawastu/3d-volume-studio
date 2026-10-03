<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>@yield ('title', '3D Printing Dashboard')</title>

  @vite (['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 text-slate-900">
  <div class="min-h-screen lg:flex">
    <aside class="w-full border-r border-slate-800 bg-slate-950 text-white lg:min-h-screen lg:w-64">
      <div class="border-b border-slate-800 px-6 py-6">
        <h1 class="text-lg font-bold">3D Printing</h1>
        <p class="mt-1 text-xs text-slate-400">Management Dashboard</p>
      </div>

      <nav class="space-y-1 p-4">
        <a
          href="{{ route('dashboard') }}"
          class="flex items-center rounded-xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
        >
          Dashboard
        </a>

        <a
          href="{{ route('calculator.index') }}"
          class="flex items-center rounded-xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('calculator.*') || request()->routeIs('calculator.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
        >
          Hitung Harga
        </a>

        <a
          href="{{ route('orders.index') }}"
          class="flex items-center rounded-xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('orders.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
        >
          Orders
        </a>

        <details class="group" {{ request()->routeIs('finance.*') ? 'open' : '' }}>
          <summary
            class="flex cursor-pointer list-none items-center justify-between rounded-xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('finance.*') ? 'bg-slate-900 text-white' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
          >
            <span>Keuangan</span>

            <svg class="h-4 w-4 transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
            </svg>
          </summary>

          <div class="mt-1 ml-4 space-y-1 pl-3">
            <a
              href="{{ route('finance.incomes.index') }}"
              class="flex items-center rounded-xl px-4 py-2.5 text-sm font-medium transition {{ request()->routeIs('finance.incomes.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
            >
              Pemasukan
            </a>

            <a
              href="{{ route('finance.expenses.index') }}"
              class="flex items-center rounded-xl px-4 py-2.5 text-sm font-medium transition {{ request()->routeIs('finance.expenses.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
            >
              Pengeluaran
            </a>
          </div>
        </details>

        <div class="flex items-center justify-between rounded-xl px-4 py-3 text-sm text-slate-500">
          <span>Customers</span>
          <span class="text-[10px] uppercase">Soon</span>
        </div>

        <div class="flex items-center justify-between rounded-xl px-4 py-3 text-sm text-slate-500">
          <span>Printer</span>
          <span class="text-[10px] uppercase">Soon</span>
        </div>

        <div class="flex items-center justify-between rounded-xl px-4 py-3 text-sm text-slate-500">
          <span>File STL</span>
          <span class="text-[10px] uppercase">Soon</span>
        </div>

        <div class="flex items-center justify-between rounded-xl px-4 py-3 text-sm text-slate-500">
          <span>Settings</span>
          <span class="text-[10px] uppercase">Soon</span>
        </div>
      </nav>
    </aside>

    <div class="min-w-0 flex-1">
      <header class="border-b border-slate-200 bg-white px-6 py-4">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-semibold">@yield ('page-title', 'Dashboard')</h2>

          <div
            class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white"
          >
            VS
          </div>
        </div>
      </header>

      <main class="p-6 lg:p-8">
        @yield ('content')
      </main>
    </div>
  </div>

  @stack ('scripts')
</body>
</html>
