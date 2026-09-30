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

        <div class="py-2">
          <div class="border-t border-slate-800"></div>
        </div>

        <a
          href="{{ route('orders.index') }}"
          class="flex items-center rounded-xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('orders.*') || request()->routeIs('calculator.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
        >
          Orders
        </a>

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

          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white">VS</div>
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
