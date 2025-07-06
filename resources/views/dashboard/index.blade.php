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
                <div class="container mx-auto px-6 py-8">

                    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200 mb-6"></h2>


                    <!-- Cards -->
                    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
                        <!-- Total Pendapatan -->

                        <div class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
                            <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13h2l1 5h12l1-5h2M5 6h14l1 5H4l1-5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Total Pendapatan</p>
                                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <!-- Total Barang -->
                        <div class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
                            <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13h2l1 5h12l1-5h2M5 6h14l1 5H4l1-5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Total Barang</p>
                                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $totalBarang }}</p>
                            </div>
                        </div>

                        <!-- TOTAL HARGA -->
                        <div class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
                            <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13h2l1 5h12l1-5h2M5 6h14l1 5H4l1-5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Total Harga</p>
                                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">Rp {{ number_format($totalHargaBarang, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <!-- Total Keuntungan -->
                        <div class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
                            <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13h2l1 5h12l1-5h2M5 6h14l1 5H4l1-5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Total Keuntungan</p>
                                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                                    Rp {{ number_format($totalKeuntungan, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Chart -->
                <div class="max-w-4xl mx-auto p-4">
                    <h2 class="text-xl font-bold mb-4 dark:text-gray-200">Grafik Keuntungan Mingguan Bulan Ini</h2>

                    <canvas id="lineChart" width="600" height="300"></canvas>
                </div>

        </div>
        </main>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('lineChart').getContext('2d');

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Keuntungan (Rp)',
                    data: @json($data),
                    borderColor: 'rgba(0, 128, 255, 1)',
                    backgroundColor: 'rgba(0, 128, 255, 0.2)',
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: 'rgba(0, 128, 255, 1)',
                    pointRadius: 5,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Minggu'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Keuntungan (Rp)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    },
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    </script>
</body>

</html>