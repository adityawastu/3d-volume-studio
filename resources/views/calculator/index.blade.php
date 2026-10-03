@extends ('layouts.dashboard')

@section ('title', 'Kalkulator Harga')
@section ('page-title', 'Kalkulator Harga')

@section ('content')
  <div class="space-y-8">
    {{-- BACK TO ORDER --}}
    <div class="mb-6 flex items-center justify-between gap-4">
      <a
        id="back-to-order"
        href="{{ route('orders.index') }}"
        class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-slate-900"
      >
        <span aria-hidden="true">←</span>
        <span>Kembali ke Orders</span>
      </a>

      <a
        id="new-order-link"
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
          <div class="flex items-start justify-between gap-4">
            <div>
              <h2 class="text-lg font-semibold text-slate-900">Parameter Cetak</h2>
              <p class="mt-1 text-sm text-slate-500">Masukkan data hasil slicing atau upload file G-code.</p>
            </div>

            <div>
              <input id="gcode-file" type="file" accept=".gcode.3mf" class="hidden" />

              <label
                for="gcode-file"
                class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2.5 text-sm font-semibold text-indigo-700 transition hover:bg-indigo-100"
              >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16V4m0 0L8 8m4-4 4 4M4 15v4a1 1 0 001 1h14a1 1 0 001-1v-4" />
                </svg>

                Upload G-code
              </label>
            </div>
          </div>

          {{-- INFO G-CODE --}}
          <div id="gcode-info" class="mt-4 hidden rounded-xl border border-slate-200 bg-slate-50 p-4">
            <div class="flex items-start justify-between gap-4">
              <div class="min-w-0 flex-1">
                <p id="gcode-file-name" class="truncate text-sm font-semibold text-slate-700"></p>
                <p id="gcode-status" class="mt-1 text-xs text-slate-500"></p>

                <div id="gcode-detail" class="mt-3 hidden grid-cols-2 gap-3">
                  <div class="rounded-lg bg-white p-3">
                    <p class="text-xs text-slate-400">Total Berat</p>
                    <p id="gcode-weight" class="mt-1 text-sm font-semibold text-slate-700"></p>
                  </div>

                  <div class="rounded-lg bg-white p-3">
                    <p class="text-xs text-slate-400">Estimasi Waktu</p>
                    <p id="gcode-time" class="mt-1 text-sm font-semibold text-slate-700"></p>
                  </div>
                </div>

                <div id="gcode-preview-wrapper" class="mt-4 hidden">
                  <p class="mb-2 text-xs font-medium text-slate-500">Preview Model</p>
                  <div id="gcode-preview-list" class="grid gap-3 sm:grid-cols-2"></div>
                </div>
              </div>

              <button
                id="remove-gcode"
                type="button"
                class="shrink-0 text-xs font-medium text-red-500 transition hover:text-red-700"
              >
                Hapus
              </button>
            </div>
          </div>
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
              >
                gram
              </span>
            </div>
          </div>

          {{-- WAKTU --}}
          <div class="mt-5">
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
          <div class="mt-5">
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
            <div class="mt-5 rounded-xl border border-red-200 bg-red-50 p-4">
              <p class="mb-2 text-sm font-semibold text-red-700">Periksa kembali input berikut:</p>

              <ul class="list-inside list-disc space-y-1 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          {{-- BUTTON --}}
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
                <p class="mt-1 font-semibold text-slate-900">{{ number_format($weight, 2, ',', '.') }} gram</p>
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

          {{-- PREVIEW MODEL --}}
          <div id="quotation-gcode-section" class="mt-8 hidden">
            <div class="mb-4">
              <p class="text-xs font-medium tracking-wider text-slate-400 uppercase">Model yang akan dicetak</p>
              <p id="quotation-gcode-file" class="mt-1 text-sm font-semibold text-slate-700"></p>
            </div>

            <div id="quotation-plate-list" class="grid grid-cols-3 gap-3"></div>

            <div id="quotation-more-plates" class="mt-3 hidden text-center">
              <p class="text-xs font-medium text-slate-500"></p>
            </div>
          </div>

          {{-- DETAIL CUSTOMER --}}
          <div class="space-y-4 py-8">
            {{-- BERAT ASLI --}}
            <div class="flex justify-between gap-8">
              <span class="text-slate-500">Berat Asli</span>
              <span class="font-semibold">{{ number_format($weight, 2, ',', '.') }} gram</span>
            </div>

            {{-- ESTIMASI WAKTU --}}
            <div class="flex justify-between gap-8">
              <span class="text-slate-500">Estimasi Waktu</span>
              <span class="font-semibold">{{ $hours }} jam {{ $minutes }} menit</span>
            </div>

            {{-- RASIO --}}
            <div class="flex justify-between gap-8">
              <span class="text-slate-500">Rasio</span>
              <span class="font-semibold">{{ number_format($ratio, 2, ',', '.') }}</span>
            </div>

            {{-- METODE PERHITUNGAN HARGA --}}
            <div class="flex justify-between gap-8">
              <span class="text-slate-500">Metode Perhitungan Harga</span>
              <span class="font-semibold">{{ $method }}</span>
            </div>

            {{-- JUMLAH CO SHOPEE --}}
          </div>

          {{-- TOTAL --}}
          <div class="border-t border-slate-200 pt-6">
            <div class="flex items-end justify-between gap-8">
              <span class="text-sm text-slate-500">Jumlah CO Shopee</span>
              <span class="text-xl font-bold">{{ number_format($pricePerItem / 600, 0, ',', '.') }}</span>
            </div>

            <div class="mt-2 flex items-end justify-between gap-8">
              <div>
                <p class="text-sm text-slate-500">Total Harga</p>
                {{-- <p class="mt-1 text-xs text-slate-400">{{ $quantity }} pcs</p> --}}
              </div>

              <p class="text-2xl font-bold">Rp{{ number_format($totalPrice, 0, ',', '.') }}</p>
            </div>
          </div>

          {{-- KETERANGAN --}}
          <div class="mt-10 border-t border-slate-200 pt-5">
            <p class="text-xs font-semibold text-slate-600">Keterangan Perhitungan Harga</p>

            <div class="mt-3 space-y-3 text-xs leading-5 text-slate-400">
              <p>Untuk perhitungan biaya cetak, kami menggunakan dua metode tergantung kompleksitas modelnya. Jika rasio waktu cetak terhadap berat di bawah 2,5, maka perhitungan menggunakan harga per gram. Namun, jika rasionya di atas atau sama dengan 2,5, maka perhitungan menggunakan harga per jam cetak. Hal ini karena ada beberapa model yang ringan tetapi membutuhkan waktu cetak yang lama, misalnya karena memiliki banyak detail atau support. Dalam kondisi tersebut, biaya lebih dipengaruhi oleh durasi mesin bekerja dibandingkan berat filamennya.</p>
            </div>
          </div>
        </div>
      </div>
    @endisset
  </div>
@endsection

@push ('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // =========================================================
      // [1] ELEMEN G-CODE
      // =========================================================
      const gcodeFile = document.getElementById('gcode-file');
      const gcodeInfo = document.getElementById('gcode-info');
      const gcodeFileName = document.getElementById('gcode-file-name');
      const gcodeStatus = document.getElementById('gcode-status');
      const gcodeDetail = document.getElementById('gcode-detail');
      const gcodeWeight = document.getElementById('gcode-weight');
      const gcodeTime = document.getElementById('gcode-time');
      const gcodePreviewWrapper = document.getElementById('gcode-preview-wrapper');
      const gcodePreviewList = document.getElementById('gcode-preview-list');
      const removeGcode = document.getElementById('remove-gcode');

      // =========================================================
      // [2] INPUT KALKULATOR
      // =========================================================
      const weightInput = document.getElementById('weight');
      const hoursInput = document.getElementById('hours');
      const minutesInput = document.getElementById('minutes');

      // =========================================================
      // [3] QUOTATION
      // =========================================================
      const quotationGcodeSection = document.getElementById('quotation-gcode-section');
      const quotationGcodeFile = document.getElementById('quotation-gcode-file');
      const quotationPlateList = document.getElementById('quotation-plate-list');
      const quotationMorePlates = document.getElementById('quotation-more-plates');

      // =========================================================
      // [4] STORAGE
      // Preview tetap ada setelah Hitung Harga / reload.
      // =========================================================
      const GCODE_STORAGE_KEY = '3d-volume-studio-gcode';

      let plateData = [];

      // =========================================================
      // [5] FORMAT WAKTU
      // =========================================================
      function formatPlateTime(seconds) {
        const totalMinutes = Math.ceil(seconds / 60);
        const hours = Math.floor(totalMinutes / 60);
        const minutes = totalMinutes % 60;

        if (hours > 0 && minutes > 0) {
          return `${hours} jam ${minutes} menit`;
        }

        if (hours > 0) {
          return `${hours} jam`;
        }

        return `${minutes} menit`;
      }

      // =========================================================
      // [6] SIMPAN G-CODE
      // =========================================================
      function saveGcodeData(data) {
        try {
          sessionStorage.setItem(GCODE_STORAGE_KEY, JSON.stringify(data));
        } catch (error) {
          console.error('Gagal menyimpan data G-code:', error);
        }
      }

      // =========================================================
      // [7] AMBIL DATA G-CODE TERSIMPAN
      // =========================================================
      function getSavedGcodeData() {
        try {
          const savedData = sessionStorage.getItem(GCODE_STORAGE_KEY);

          if (!savedData) {
            return null;
          }

          return JSON.parse(savedData);
        } catch (error) {
          console.error('Gagal membaca data G-code:', error);
          return null;
        }
      }

      // =========================================================
      // [8] HAPUS STORAGE
      // =========================================================
      function clearSavedGcodeData() {
        sessionStorage.removeItem(GCODE_STORAGE_KEY);
      }

      // =========================================================
      // [9] RESET PREVIEW ADMIN
      // =========================================================
      function clearAdminPreview() {
        if (gcodePreviewList) {
          gcodePreviewList.innerHTML = '';
        }

        if (gcodePreviewWrapper) {
          gcodePreviewWrapper.classList.add('hidden');
        }
      }

      // =========================================================
      // [10] RESET QUOTATION PREVIEW
      // =========================================================
      function clearQuotationPreview() {
        if (quotationPlateList) {
          quotationPlateList.innerHTML = '';
        }

        if (quotationGcodeSection) {
          quotationGcodeSection.classList.add('hidden');
        }

        if (quotationGcodeFile) {
          quotationGcodeFile.textContent = '';
        }

        if (quotationMorePlates) {
          quotationMorePlates.classList.add('hidden');

          const text = quotationMorePlates.querySelector('p');

          if (text) {
            text.textContent = '';
          }
        }
      }

      // =========================================================
      // [11] PREVIEW ADMIN
      // =========================================================
      function renderAdminPreviews(plates) {
        if (!gcodePreviewList || !gcodePreviewWrapper) {
          return;
        }

        clearAdminPreview();

        const platesWithPreview = plates.filter(function (plate) {
          return plate.preview;
        });

        platesWithPreview.forEach(function (plate) {
          const previewCard = document.createElement('div');

          previewCard.className = 'overflow-hidden rounded-xl border border-slate-200 bg-white';

          previewCard.innerHTML = `
                    <div class="flex aspect-square items-center justify-center bg-slate-100 p-2">
                        <img src="${plate.preview}" alt="Preview Plate ${plate.plate}" class="h-full w-full object-contain">
                    </div>

                    <div class="border-t border-slate-100 px-3 py-2">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-xs font-semibold text-slate-700">Plate ${plate.plate}</p>
                            <p class="text-xs text-slate-400">${plate.weight.toFixed(2)} g</p>
                        </div>

                        <p class="mt-1 text-xs text-slate-400">${formatPlateTime(plate.seconds)}</p>
                    </div>
                `;

          gcodePreviewList.appendChild(previewCard);
        });

        if (platesWithPreview.length > 0) {
          gcodePreviewWrapper.classList.remove('hidden');
        }
      }

      // =========================================================
      // [12] PREVIEW QUOTATION
      // Maksimal 9 plate.
      // =========================================================
      function renderQuotationPreviews(fileName, plates) {
        if (!quotationGcodeSection || !quotationPlateList || !quotationGcodeFile) {
          return;
        }

        clearQuotationPreview();

        quotationGcodeFile.textContent = fileName;

        const maximumPreview = 9;

        const platesWithPreview = plates.filter(function (plate) {
          return plate.preview;
        });

        const previewPlates = platesWithPreview.slice(0, maximumPreview);

        previewPlates.forEach(function (plate) {
          const plateCard = document.createElement('div');

          plateCard.className = 'overflow-hidden rounded-xl border border-slate-200 bg-white';

          plateCard.innerHTML = `
                    <div class="flex h-32 items-center justify-center bg-slate-50 p-2">
                        <img src="${plate.preview}" alt="Preview Plate ${plate.plate}" class="h-full w-full object-contain">
                    </div>

                    <div class="border-t border-slate-200 p-3">
                        <p class="text-sm font-semibold text-slate-900">Plate ${plate.plate}</p>

                        <div class="mt-2 space-y-1">
                            <div class="flex justify-between gap-2 text-xs">
                                <span class="text-slate-400">Berat</span>
                                <span class="font-medium text-slate-700">${plate.weight.toFixed(2)} g</span>
                            </div>

                            <div class="flex justify-between gap-2 text-xs">
                                <span class="text-slate-400">Waktu</span>
                                <span class="font-medium text-slate-700">${formatPlateTime(plate.seconds)}</span>
                            </div>
                        </div>
                    </div>
                `;

          quotationPlateList.appendChild(plateCard);
        });

        if (plates.length > maximumPreview && quotationMorePlates) {
          const remainingPlates = plates.length - maximumPreview;
          const text = quotationMorePlates.querySelector('p');

          if (text) {
            text.textContent = `+ ${remainingPlates} plate lainnya`;
          }

          quotationMorePlates.classList.remove('hidden');
        }

        if (previewPlates.length > 0) {
          quotationGcodeSection.classList.remove('hidden');
        }
      }

      // =========================================================
      // [13] RENDER SELURUH DATA
      // =========================================================
      function renderGcodeData(data) {
        if (!data) {
          return;
        }

        plateData = data.plates || [];

        if (gcodeInfo) {
          gcodeInfo.classList.remove('hidden');
        }

        if (gcodeFileName) {
          gcodeFileName.textContent = data.fileName;
        }

        if (gcodeStatus) {
          gcodeStatus.textContent = `${plateData.length} plate berhasil dibaca. Data kalkulator telah diisi otomatis.`;
        }

        if (gcodeWeight) {
          gcodeWeight.textContent = `${Number(data.totalWeight).toFixed(2)} gram`;
        }

        if (gcodeTime) {
          gcodeTime.textContent = `${data.hours} jam ${data.minutes} menit`;
        }

        if (gcodeDetail) {
          gcodeDetail.classList.remove('hidden');
          gcodeDetail.classList.add('grid');
        }

        renderAdminPreviews(plateData);
        renderQuotationPreviews(data.fileName, plateData);
      }

      // =========================================================
      // [14] UPLOAD G-CODE
      // =========================================================
      if (gcodeFile) {
        gcodeFile.addEventListener('change', async function (event) {
          const file = event.target.files[0];

          if (!file) {
            return;
          }

          if (!file.name.toLowerCase().endsWith('.gcode.3mf')) {
            alert('File harus berformat .gcode.3mf.');
            gcodeFile.value = '';
            return;
          }

          if (gcodeInfo) {
            gcodeInfo.classList.remove('hidden');
          }

          if (gcodeDetail) {
            gcodeDetail.classList.add('hidden');
            gcodeDetail.classList.remove('grid');
          }

          clearAdminPreview();
          clearQuotationPreview();

          gcodeFileName.textContent = file.name;
          gcodeStatus.textContent = 'Membaca file G-code...';

          try {
            // =================================================
            // [15] BUKA ARCHIVE
            // =================================================
            const zip = await JSZip.loadAsync(file);

            // =================================================
            // [16] AMBIL METADATA
            // =================================================
            const metadataFile = zip.file('Metadata/slice_info.config');

            if (!metadataFile) {
              throw new Error('Metadata/slice_info.config tidak ditemukan.');
            }

            // =================================================
            // [17] PARSE XML
            // =================================================
            const metadataContent = await metadataFile.async('text');
            const parser = new DOMParser();
            const xml = parser.parseFromString(metadataContent, 'application/xml');

            if (xml.querySelector('parsererror')) {
              throw new Error('Metadata G-code tidak dapat dibaca.');
            }

            const plates = xml.querySelectorAll('plate');

            let totalWeight = 0;
            let totalSeconds = 0;
            let validPlate = 0;

            plateData = [];

            // =================================================
            // [18] BACA SETIAP PLATE
            // =================================================
            plates.forEach(function (plate, index) {
              const weightMetadata = plate.querySelector('metadata[key="weight"]');
              const predictionMetadata = plate.querySelector('metadata[key="prediction"]');

              if (!weightMetadata || !predictionMetadata) {
                return;
              }

              const weight = parseFloat(weightMetadata.getAttribute('value'));
              const prediction = parseInt(predictionMetadata.getAttribute('value'), 10);

              if (!Number.isNaN(weight) && !Number.isNaN(prediction)) {
                totalWeight += weight;
                totalSeconds += prediction;
                validPlate++;

                plateData.push({
                  plate: index + 1,
                  weight: weight,
                  seconds: prediction,
                  preview: null,
                });
              }
            });

            if (validPlate === 0) {
              throw new Error('Data berat dan estimasi waktu tidak ditemukan.');
            }

            // =================================================
            // [19] BACA PREVIEW
            // Prioritas small image agar sessionStorage ringan.
            // =================================================
            for (const plate of plateData) {
              const smallPreviewName = `Metadata/plate_${plate.plate}_small.png`;
              const fullPreviewName = `Metadata/plate_${plate.plate}.png`;

              const previewFile = zip.file(smallPreviewName) || zip.file(fullPreviewName);

              if (!previewFile) {
                continue;
              }

              const previewBase64 = await previewFile.async('base64');

              plate.preview = `data:image/png;base64,${previewBase64}`;
            }

            // =================================================
            // [20] KONVERSI WAKTU
            // =================================================
            const totalMinutes = Math.ceil(totalSeconds / 60);
            const hours = Math.floor(totalMinutes / 60);
            const minutes = totalMinutes % 60;

            // =================================================
            // [21] ISI FORM
            // =================================================
            weightInput.value = totalWeight.toFixed(2);
            hoursInput.value = hours;
            minutesInput.value = minutes;

            // =================================================
            // [22] SUSUN DATA
            // =================================================
            const gcodeData = {
              fileName: file.name,
              totalWeight: totalWeight,
              totalSeconds: totalSeconds,
              hours: hours,
              minutes: minutes,
              plates: plateData,
            };

            // =================================================
            // [23] SIMPAN AGAR TIDAK HILANG SETELAH HITUNG
            // =================================================
            saveGcodeData(gcodeData);

            // =================================================
            // [24] RENDER
            // =================================================
            renderGcodeData(gcodeData);
          } catch (error) {
            console.error('Gagal membaca G-code:', error);

            gcodeStatus.textContent = error.message || 'Gagal membaca file G-code.';

            if (gcodeDetail) {
              gcodeDetail.classList.add('hidden');
              gcodeDetail.classList.remove('grid');
            }

            clearAdminPreview();
            clearQuotationPreview();
          }
        });
      }

      // =========================================================
      // [25] RESTORE SETELAH HITUNG HARGA
      // =========================================================
      const savedGcodeData = getSavedGcodeData();

      if (savedGcodeData) {
        renderGcodeData(savedGcodeData);

        if (!weightInput.value) {
          weightInput.value = Number(savedGcodeData.totalWeight).toFixed(2);
        }

        if (Number(hoursInput.value) === 0 && Number(minutesInput.value) === 0) {
          hoursInput.value = savedGcodeData.hours;
          minutesInput.value = savedGcodeData.minutes;
        }
      }

      // =========================================================
      // [26] HAPUS G-CODE
      // =========================================================
      if (removeGcode) {
        removeGcode.addEventListener('click', function () {
          if (gcodeFile) {
            gcodeFile.value = '';
          }

          clearSavedGcodeData();
          clearAdminPreview();
          clearQuotationPreview();

          plateData = [];

          if (gcodeInfo) {
            gcodeInfo.classList.add('hidden');
          }

          if (gcodeDetail) {
            gcodeDetail.classList.add('hidden');
            gcodeDetail.classList.remove('grid');
          }

          gcodeFileName.textContent = '';
          gcodeStatus.textContent = '';
          gcodeWeight.textContent = '';
          gcodeTime.textContent = '';

          weightInput.value = '';
          hoursInput.value = 0;
          minutesInput.value = 0;
        });
      }

      // =========================================================
      // [27] ORDER BARU and Back To Order
      // =========================================================
      const newOrderLink = document.getElementById('new-order-link');

      if (newOrderLink) {
        newOrderLink.addEventListener('click', function () {
          clearSavedGcodeData();
        });
      }

      const backToOrder = document.getElementById('back-to-order');

      if (backToOrder) {
        backToOrder.addEventListener('click', function () {
          clearSavedGcodeData();
        });
      }
      // =========================================================
      // [28] ANTI DOUBLE SUBMIT
      // G-code tidak dihapus di sini.
      // =========================================================
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

      // =========================================================
      // [29] QUOTATION
      // =========================================================
      const exportButton = document.getElementById('export-quotation');
      const quotationCard = document.getElementById('quotation-card');

      // =========================================================
      // [30] TUNGGU SEMUA GAMBAR
      // =========================================================
      async function waitForImages(container) {
        const images = Array.from(container.querySelectorAll('img'));

        await Promise.all(
          images.map(function (image) {
            if (image.complete) {
              return Promise.resolve();
            }

            return new Promise(function (resolve) {
              image.onload = resolve;
              image.onerror = resolve;
            });
          }),
        );
      }

      // =========================================================
      // [31] DOWNLOAD QUOTATION
      // =========================================================
      if (exportButton && quotationCard) {
        exportButton.addEventListener('click', async function () {
          try {
            exportButton.disabled = true;
            exportButton.textContent = 'Membuat gambar...';

            await waitForImages(quotationCard);

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
