<!DOCTYPE html>
<html lang="en" x-data="data()" :class="{ 'theme-dark': dark }">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Scan Barcode - Toko Elektronik</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="./../assets/css/tailwind.output.css" />

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="./../assets/js/init-alpine.js"></script>

    <style>
        #reader {
            width: 300px;
            margin: auto;
            display: none;
        }

        #item-detail {
            display: none;
        }
    </style>
</head>

<body>
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        @include('components.sidebar')
        <div class="flex flex-col flex-1 w-full">
            @include('components.header')

            <main class="h-full overflow-y-auto">
                <div class="container mx-auto px-6 py-6">
                    <h2 class="text-2xl font-semibold mb-6 text-gray-800 dark:text-white">Scan Barcode</h2>

                    <!-- ALERT -->
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Input Manual (Scanner Fisik) -->
                    <div class="mb-6">
                        <label for="manual-scan"
                            class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Scan dengan Alat Scanner:
                        </label>
                        <div class="flex">
                            <input type="text" id="manual-scan" placeholder="Arahkan scanner ke sini" autofocus
                                class="border rounded px-3 py-2 w-full focus:outline-none focus:ring focus:border-purple-500" />
                            <button id="manual-search"
                                class="ml-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Cari
                            </button>
                        </div>
                    </div>

                    <!-- Kamera Smartphone -->
                    <div class="mb-6">
                        <p class="text-gray-700 dark:text-gray-300 mb-2">Scan menggunakan Kamera:</p>
                        <div class="flex gap-2 mb-4">
                            <button id="start-scan"
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                Mulai Scan
                            </button>
                            <button id="stop-scan"
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 hidden">
                                Stop Scan
                            </button>
                        </div>
                        <div id="reader"></div>
                    </div>

                    <!-- Hasil Scan -->
                    <div id="item-detail" class="mt-6 p-4 bg-white dark:bg-gray-800 shadow rounded">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">Detail Barang</h3>
                        <div id="item-content" class="text-gray-700 dark:text-gray-200">
                            <!-- Detail barang dari AJAX -->
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <link rel="stylesheet" href="{{ secure_asset('build/assets/app-Duf7wj54.css') }}">
    <script type="module" src="{{ secure_asset('build/assets/app-BJokM-Mc.js') }}"></script>

</body>

</html>
