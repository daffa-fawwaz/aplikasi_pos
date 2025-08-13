<!DOCTYPE html>
<html lang="en" x-data="data()" :class="{ 'theme-dark': dark }">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Scan Barcode - Toko Elektronik</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="./../assets/css/tailwind.output.css" />
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="./../assets/js/init-alpine.js"></script>

    <style>
        #reader {
            width: 100%;
            max-width: 640px;
            margin: auto;
            border-radius: 8px;
            overflow: hidden;
            display: none;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .scanning-option {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .scanning-option:hover {
            transform: translateY(-2px);
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

                    <!-- Alert Messages -->
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded animate-fade-in">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <!-- Opsi 1: Scanner Wireless -->
                        <div class="scanning-option p-6 dark:bg-gray-800">
                            <div class="flex items-center mb-4">
                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Scanner Wireless</h3>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                    Gunakan scanner wireless untuk memindai barcode barang.
                                </p>
                                <div class="flex">
                                    <input type="text" id="manual-scan" placeholder="Arahkan scanner ke barcode"
                                        class="flex-1 border rounded-l px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                                        autofocus>
                                    <button id="manual-search"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-r hover:bg-blue-700 transition-colors">
                                        Cari
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Opsi 2: Kamera Smartphone -->
                        <div class="scanning-option p-6 dark:bg-gray-800">
                            <div class="flex items-center mb-4">
                                <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Kamera Smartphone</h3>
                            </div>

                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                Gunakan kamera smartphone Anda untuk memindai barcode.
                            </p>

                            <div class="flex gap-2 mb-4">
                                <button id="start-scan"
                                    class="flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    Mulai Scan
                                </button>
                                <button id="stop-scan"
                                    class="hidden flex items-center px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                                    </svg>
                                    Stop Scan
                                </button>
                            </div>
                            <div id="reader" class="mt-4"></div>
                        </div>
                    </div>

                    <!-- Modal Backdrop -->
                    <div id="modal-backdrop"
                        class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden transition-opacity duration-300">
                    </div>

                    <!-- Modal Detail -->
                    <div id="item-detail" class="fixed inset-0 items-center justify-center hidden z-50">
                        <div class="container mx-auto px-4 h-full flex items-center">
                            <div class="bg-white dark:bg-gray-800 w-full max-w-md mx-auto rounded-lg shadow-lg"
                                style="margin-top: 5rem;">
                                <!-- Header -->
                                <div class="border-b dark:border-gray-700">
                                    <div class="flex items-center justify-between p-4">
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Detail Barang
                                        </h3>
                                        <button onclick="closeDetail()"
                                            class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-4 overflow-y-auto" id="item-content"
                                    style="max-height: calc(100vh - 400px)">
                                    <!-- Content will be injected by JavaScript -->
                                </div>

                                <!-- Bottom Button -->
                                <div class="p-4 border-t dark:border-gray-700">
                                    <button onclick="addToCart()"
                                        class="w-full py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                        Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
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
