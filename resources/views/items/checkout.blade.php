<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DashAdmin</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="./../../assets/css/tailwind.output.css" />
    <script
        src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"
        defer></script>
    <script src="./../../assets/js/init-alpine.js"></script>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css" />
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"
        defer></script>
    <script src="./../../assets/js/charts-lines.js" defer></script>
    <script src="./../../assets/js/charts-pie.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
</head>

<body>
    <div
        class="flex h-screen bg-gray-50 dark:bg-gray-900"
        :class="{ 'overflow-hidden': isSideMenuOpen }">
        <!-- sidebar -->
        @include('components.sidebar')
        <div class="flex flex-col flex-1 w-full">
            <!-- HEADER -->
            @include('components.header')
            <main class="h-full overflow-y-auto">
                <div class="container mx-auto p-6">
                    <h2 class="text-2xl font-semibold mb-6 dark:text-gray-200">Checkout Barang</h2>

                    <form action="{{ route('items.checkout.process', $item->id) }}" method="POST" class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                        @csrf

                        <div class="w-full mb-4">
                            <div class="w-full flex justify-between">
                                <p class="text-gray-600 dark:text-gray-400">Nama Barang : </p>
                                <p class="font-bold text-gray-900 dark:text-white">{{ $item->nama_barang }}</p>
                            </div>
                            <div class="w-full flex justify-between">
                                <p class="text-gray-600 dark:text-gray-400">Tipe : </p>
                                <p class="font-bold text-gray-900 dark:text-white">{{ $item->tipe_barang }}</p>
                            </div>
                            <div class="w-full flex justify-between">
                                <p class="text-gray-600 dark:text-gray-400">Stok : </p>
                                <p class="font-bold text-gray-900 dark:text-white">{{ $item->stok }}</p>
                            </div>
                        </div>

                        <label class="block text-sm text-gray-700 dark:text-gray-300 mb-4">
                            Harga Kulak
                            <input type="text"
                                value="Rp {{ number_format($item->harga_beli, 0, ',', '.') }}"
                                class="w-full mt-1 px-2 py-1 border rounded bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                disabled>
                        </label>

                        <label class="block text-sm text-gray-700 dark:text-gray-300 mb-4">
                            Total Harga:
                            <input type="text" name="total_harga" value="Rp {{ number_format($item->harga_jual, 0, ',', '.') }}" min="0" class="rupiah-input w-full mt-1 px-2 py-1 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" required>
                            @error('total_harga')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </label>

                        <label class="block text-sm text-gray-700 dark:text-gray-300 mb-4">
                            Jumlah Beli:
                            <div class="flex items-center mt-1">
                                <!-- Tombol minus -->
                                <button type="button"
                                    onclick="updateJumlah(-1)"
                                    class="px-3 py-1 text-white bg-blue-500 rounded-l focus:outline-none hover:bg-blue-600 dark:hover:bg-blue-700">
                                    -
                                </button>

                                <!-- Input jumlah -->
                                <input type="number" id="jumlah_beli" name="jumlah_beli"
                                    value="{{ old('jumlah_beli', 1) }}"
                                    min="1" max="{{ $item->stok }}"
                                    class="w-20 px-2 py-1 text-center border-t border-b border-gray-300 dark:border-gray-600 focus:outline-none dark:bg-gray-700 dark:text-gray-200"
                                    required>

                                <!-- Tombol plus -->
                                <button type="button"
                                    onclick="updateJumlah(1)"
                                    class="px-3 py-1 text-white bg-blue-500 rounded-r focus:outline-none hover:bg-blue-600 dark:hover:bg-blue-700">
                                    +
                                </button>
                            </div>

                            @error('jumlah_beli')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </label>

                        <label class="block text-sm text-gray-700 dark:text-gray-300 mb-4">
                            <span class="dark:text-gray-400">Tanggal Beli</span>
                            <input type="datetime-local" name="tanggal" class="block w-full mt-1 text-sm form-input dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" value="{{ old('tanggal') }}" required>
                        </label>

                        <div class="flex gap-2">
                            <a href="{{ route('items.index') }}" class="px-4 py-2 border rounded hover:bg-gray-100 dark:hover:bg-gray-700 dark:border-gray-600 dark:text-gray-300">Batal</a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">Simpan</button>
                        </div>
                    </form>

                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.rupiah-input').forEach(function(input) {
                input.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/[^,\d]/g, '').toString();
                    let split = value.split(',');
                    let sisa = split[0].length % 3;
                    let rupiah = split[0].substr(0, sisa);
                    let ribuan = split[0].substr(sisa).match(/\d{3}/g);

                    if (ribuan) {
                        let separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                    }

                    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                    e.target.value = 'Rp ' + rupiah;
                });
            });
        });

        function updateJumlah(delta) {
            const input = document.getElementById('jumlah_beli');
            const min = parseInt(input.min);
            const max = parseInt(input.max);
            let value = parseInt(input.value);

            value = isNaN(value) ? min : value + delta;
            if (value < min) value = min;
            if (value > max) value = max;

            input.value = value;
        }
    </script>
</body>

</html>