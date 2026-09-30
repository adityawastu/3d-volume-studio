@extends ('layouts.dashboard')

@section ('title', 'Kalkulator Harga')
@section ('page-title', 'Kalkulator Harga')

@section ('content')
  <div class="space-y-8">
    {{-- back to order --}}
    <div class="mb-6 flex items-center justify-between gap-4">
      <a
        href="{{ route('orders.index') }}"
        class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-slate-900"
      >
        <span aria-hidden="true">←</span>
        <span>Kembali ke Orders</span>
      </a>

      <a
        href="{{ route('calculator.index') }}"
        class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
      >
        <span aria-hidden="true">↻</span>
        <span>Order Baru</span>
      </a>
    </div>
    <div class="grid gap-6 xl:grid-cols-2">
      {{-- FORM INPUT --}}
      <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-6">
          <h2 class="text-lg font-semibold text-slate-900">Parameter Cetak</h2>
          <p class="mt-1 text-sm text-slate-500">Masukkan data hasil slicing model.</p>
        </div>

        <form id="calculator-form" action="{{ route('calculator.calculate') }}" method="POST">
          @csrf
          <input type="hidden" name="submission_token" value="{{ $submissionToken }}" />

          {{-- BERAT --}}
          <div>
            <label for="weight" class="mb-2 block text-sm font-medium text-slate-700">Berat Filament</label>
            <div class="flex">
              <input
                id="weight"
                name="weight"
                type="number"
                min="0.01"
                step="0.01"
                value="{{ old('weight', $weight ?? '') }}"
                placeholder="Masukkan Berat"
                class="min-w-0 flex-1 rounded-l-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 transition outline-none placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                required
              />
              <span
                class="flex items-center rounded-r-xl border border-l-0 border-slate-300 bg-slate-50 px-4 text-sm text-slate-500"
                >gram</span
              >
            </div>
          </div>

          {{-- WAKTU --}}
          <div>
            <label class="mb-2 block text-sm font-medium text-slate-700">Estimasi Waktu Print</label>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label for="hours" class="mb-2 block text-xs text-slate-500">Jam</label>
                <input
                  id="hours"
                  name="hours"
                  type="number"
                  min="0"
                  value="{{ old('hours', $hours ?? 0) }}"
                  class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 transition outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                  required
                />
              </div>

              <div>
                <label for="minutes" class="mb-2 block text-xs text-slate-500">Menit</label>
                <input
                  id="minutes"
                  name="minutes"
                  type="number"
                  min="0"
                  max="59"
                  value="{{ old('minutes', $minutes ?? 0) }}"
                  class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 transition outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                  required
                />
              </div>
            </div>
          </div>

          {{-- QUANTITY --}}
          <div>
            <label for="quantity" class="mb-2 block text-sm font-medium text-slate-700">Quantity</label>
            <input
              id="quantity"
              name="quantity"
              type="number"
              min="1"
              value="{{ old('quantity', $quantity ?? 1) }}"
              class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 transition outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
              required
            />
          </div>

          {{-- VALIDATION ERROR --}}
          @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-4">
              <p class="mb-2 text-sm font-semibold text-red-700">Periksa kembali input berikut:</p>
              <ul class="list-inside list-disc space-y-1 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="mt-5">
            <button
              type="submit"
              id="calculate-button"
              class="w-full rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-60"
            >
              Hitung Harga
            </button>
          </div>
        </form>
      </section>

      {{-- HASIL INTERNAL --}}
      <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-6">
          <h2 class="text-lg font-semibold text-slate-900">Hasil Perhitungan</h2>
          <p class="mt-1 text-sm text-slate-500">Informasi ini digunakan untuk kebutuhan internal.</p>
        </div>

        @isset ($pricePerItem)
          <div class="space-y-6">
            {{-- RINGKASAN --}}
            <div class="grid grid-cols-2 gap-3">
              <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-xs font-medium text-slate-500">Berat</p>
                <p class="mt-1 font-semibold text-slate-900">{{ number_format($weight, 0, ',', '.') }} gram</p>
              </div>

              <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-xs font-medium text-slate-500">Waktu</p>
                <p class="mt-1 font-semibold text-slate-900">{{ $hours }} jam {{ $minutes }} menit</p>
              </div>

              <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-xs font-medium text-slate-500">Total Menit</p>
                <p class="mt-1 font-semibold text-slate-900">{{ $totalMinutes }}</p>
              </div>

              <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-xs font-medium text-slate-500">Ratio</p>
                <p class="mt-1 font-semibold text-slate-900">{{ number_format($ratio, 2, ',', '.') }}</p>
              </div>
            </div>

            {{-- METODE --}}
            <div class="rounded-xl border border-indigo-100 bg-indigo-50 p-4">
              <p class="text-sm text-slate-500">Metode Perhitungan</p>
              <p class="mt-1 font-semibold text-indigo-700">{{ $method }}</p>
            </div>

            {{-- DETAIL BIAYA --}}
            {{-- <div class="overflow-hidden rounded-xl border border-slate-200">
              <div class="border-b border-slate-200 bg-slate-50 px-4 py-3">
                <p class="text-sm font-semibold text-slate-900">Detail Biaya</p>
              </div>

              <div class="divide-y divide-slate-100">
                <div class="flex items-center justify-between px-4 py-3">
                  <span class="text-sm text-slate-500">Harga Dasar</span>
                  <span class="font-medium text-slate-900">Rp{{ number_format($basePrice, 0, ',', '.') }}</span>
                </div>

                <div class="flex items-center justify-between px-4 py-3">
                  <span class="text-sm text-slate-500">Fee 8,25%</span>
                  <span class="font-medium text-slate-900">Rp{{ number_format($fee, 0, ',', '.') }}</span>
                </div>

                <div class="flex items-center justify-between px-4 py-3">
                  <span class="text-sm text-slate-500">Biaya Tetap</span>
                  <span class="font-medium text-slate-900">Rp{{ number_format($fixedCost, 0, ',', '.') }}</span>
                </div>
              </div>
            </div> --}}

            {{-- HARGA PCS --}}
            <div>
              <p class="text-sm text-slate-500">Harga per Pcs</p>
              <p class="mt-1 text-3xl font-bold text-slate-900">Rp{{ number_format($pricePerItem, 0, ',', '.') }}</p>
            </div>

            {{-- JUMLAH CO SHOPEE --}}
            <div>
              <p class="text-sm text-slate-500">Jumlah CO Shopee</p>
              <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($pricePerItem / 600, 0, ',', '.') }}</p>
            </div>

            {{-- TOTAL --}}
            <div class="rounded-2xl bg-slate-900 p-5 text-white">
              <div class="flex items-end justify-between gap-4">
                <div>
                  <p class="text-sm text-slate-300">Total Order</p>
                  <p class="mt-1 text-sm text-slate-400">{{ $quantity }} pcs</p>
                </div>

                <p class="text-2xl font-bold">Rp{{ number_format($totalPrice, 0, ',', '.') }}</p>
              </div>
            </div>

            {{-- DOWNLOAD QUOTATION --}}

            <button
              type="button"
              id="export-quotation"
              class="w-full rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
            >
              Download Quotation
            </button>
          </div>

        @else
          <div
            class="flex min-h-80 items-center justify-center rounded-xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center"
          >
            <div>
              <p class="font-medium text-slate-700">Belum ada perhitungan</p>
              <p class="mt-1 text-sm text-slate-400">Masukkan parameter cetak lalu tekan Hitung Harga.</p>
            </div>
          </div>

        @endisset
      </section>
    </div>

    {{-- QUOTATION CUSTOMER - TIDAK DITAMPILKAN --}}
    @isset ($pricePerItem)
      <div class="absolute top-0 left-[-9999px]">
        <div id="quotation-card" class="shrink-0 bg-white p-10 text-slate-900" style="width: 720px">
          {{-- BRAND --}}
          <div>
            <p class="text-xs font-medium tracking-wider text-slate-500 uppercase">3D Volume Studio</p>

            <h2 class="mt-1 text-2xl font-bold text-slate-900">Quotation</h2>

            @isset ($calculation)
              <p class="mt-1 text-sm font-medium text-slate-500">Ref: {{ $calculation->reference_no }}</p>
            @endisset
          </div>

          {{-- DETAIL --}}
          <div class="space-y-4 py-8">
            <div class="flex justify-between gap-8">
              <span class="text-slate-500">Berat</span>
              <span class="font-semibold">{{ number_format($weight, 0, ',', '.') }} gram</span>
            </div>

            <div class="flex justify-between gap-8">
              <span class="text-slate-500">Estimasi Waktu</span>
              <span class="font-semibold">{{ $hours }} jam {{ $minutes }} menit</span>
            </div>

            <div class="flex justify-between gap-8">
              <span class="text-slate-500">Rasio</span>
              <span class="font-semibold">{{ number_format($ratio, 2, ',', '.') }}</span>
            </div>

            <div class="flex justify-between gap-8">
              <span class="text-slate-500">Metode Perhitungan</span>
              <span class="font-semibold">{{ $method }}</span>
            </div>

            <div class="flex justify-between gap-8">
              <span class="text-slate-500">Harga</span>
              <span class="font-semibold">Rp{{ number_format($pricePerItem, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between gap-8">
              <span class="text-slate-500">Jumlah CO Shopee</span>
              <span class="font-semibold">{{ number_format($pricePerItem / 600, 0, ',', '.') }}</span>
            </div>
          </div>

          {{-- TOTAL --}}
          <div class="border-t border-slate-200 pt-6">
            <div class="flex items-end justify-between gap-8">
              <div>
                <p class="text-sm text-slate-500">Total Harga</p>
                <p class="mt-1 text-xs text-slate-400">{{ $quantity }} pcs</p>
              </div>

              <p class="text-3xl font-bold">Rp{{ number_format($totalPrice, 0, ',', '.') }}</p>
            </div>
          </div>

          {{-- FOOTER --}}
          <div class="mt-10 border-t border-slate-200 pt-5">
            <p class="text-xs leading-5 text-slate-400">Harga dapat berubah apabila terdapat perubahan desain, ukuran, material, atau parameter printing.</p>

            <p class="mt-2 text-xs leading-5 text-slate-400">Untuk harga yang kompetitif, jika rasio waktu cetak terhadap berat di bawah 2,5, perhitungan menggunakan harga per gram. Jika rasio berada di atas atau sama dengan 2,5 maka perhitungan menggunakan harga berdasarkan waktu cetak. Metode ini digunakan karena beberapa model dapat memiliki berat rendah tetapi membutuhkan waktu cetak yang lama.</p>
          </div>
        </div>
      </div>

    @endisset
  </div>

@endsection

@push ('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const calculatorForm = document.getElementById('calculator-form');
      const calculateButton = document.getElementById('calculate-button');

      if (calculatorForm && calculateButton) {
        calculatorForm.addEventListener('submit', function (event) {
          if (calculatorForm.dataset.submitting === 'true') {
            event.preventDefault();
            return;
          }

          calculatorForm.dataset.submitting = 'true';

          calculateButton.disabled = true;
          calculateButton.textContent = 'Menghitung...';
        });
      }

      const exportButton = document.getElementById('export-quotation');
      const quotationCard = document.getElementById('quotation-card');

      if (exportButton && quotationCard) {
        exportButton.addEventListener('click', async function () {
          try {
            exportButton.disabled = true;
            exportButton.textContent = 'Membuat gambar...';

            const dataUrl = await window.htmlToImage.toPng(quotationCard, {
              width: 720,
              height: quotationCard.scrollHeight,
              pixelRatio: 2,
              backgroundColor: '#ffffff',
              cacheBust: true,
              style: {
                margin: '0',
                transform: 'none',
              },
            });

            const referenceNo = @json ($calculation->reference_no ?? 'quotation');
            const fileName = `Quotation-${referenceNo}.png`;

            const link = document.createElement('a');

            link.download = fileName;
            link.href = dataUrl;

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
          } catch (error) {
            console.error('Gagal membuat quotation:', error);
            alert('Gagal membuat gambar quotation.');
          } finally {
            exportButton.disabled = false;
            exportButton.textContent = 'Download Quotation';
          }
        });
      }
    });
    console.log('Script kalkulator aktif');
  </script>
@endpush
