<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DashAdmin</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="./../assets/css/tailwind.output.css" />
    <script
        src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"
        defer></script>
    <script src="./../assets/js/init-alpine.js"></script>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css" />
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"
        defer></script>
    <script src="./../assets/js/charts-lines.js" defer></script>
    <script src="./../assets/js/charts-pie.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
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
                <div class="container mx-auto p-4 max-w-lg">
                    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Input Barang</h1>

                    @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('items.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <label class="block text-sm">
                            <span class="text-gray-700 dark:text-gray-400">Nama Barang</span>
                            <input type="text" name="nama_barang" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" value="{{ old('destination') }}" placeholder="Masukkan nama barang" required>
                        </label>

                        <label class="block text-sm">
                            <span class="text-gray-700 dark:text-gray-400">Tipe</span>
                            <input type="text" name="tipe_barang" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" value="{{ old('destination') }}" placeholder="Masukkan tipe barang" required>
                        </label>

                        <label class="block text-sm">
                            <span class="text-gray-700 dark:text-gray-400">Harga Kulak</span>
                            <input type="text" name="harga_beli" class="rupiah-input block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" value="{{ old('destination') }}" placeholder="Masukkan harga kulak" required>
                        </label>

                        <!-- <label class="block text-sm">
                            <span class="text-gray-700 dark:text-gray-400">Harga Jual</span>
                            <input type="text" name="harga_jual" id="harga_jual" class="rupiah-input block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Masukkan harga jual" required>
                        </label> -->

                        <label class="block text-sm">
                            <span class="text-gray-700 dark:text-gray-400">Tanggal Kulak</span>
                            <input type="datetime-local" name="tanggal_order" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" value="{{ old('end_time') }}" required>
                        </label>

                        <label class="block text-sm">
                            <span class="text-gray-700 dark:text-gray-400">Jumlah Stok</span>
                            <div class="flex items-center mt-1">
                                <button type="button" onclick="decreaseStok()" class="px-3 py-1 bg-gray-300 dark:bg-gray-600 text-black dark:text-white rounded-l hover:bg-gray-400 dark:hover:bg-gray-500">−</button>

                                <input type="number" id="stokInput" name="stok" min="0"
                                    class="w-16 text-center border-t border-b border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500"
                                    value="{{ old('stok', 0) }}" required>

                                <button type="button" onclick="increaseStok()" class="px-3 py-1 bg-gray-300 dark:bg-gray-600 text-black dark:text-white rounded-r hover:bg-gray-400 dark:hover:bg-gray-500">+</button>
                            </div>
                        </label>


                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Tambah Barang
                        </button>
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
    </script>

</body>

</html>