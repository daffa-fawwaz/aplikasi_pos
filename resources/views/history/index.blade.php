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
        class="flex h-fit bg-gray-50 dark:bg-gray-900"
        :class="{ 'overflow-hidden': isSideMenuOpen }">
        <!-- sidebar -->
        @include('components.sidebar')
        <div class="flex flex-col flex-1 w-full">
            <!-- HEADER -->
            @include('components.header')
            <div class="container mx-auto px-6 py-8">

                <div class="container mx-auto px-6 py-8">
                    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200 mb-6">Riwayat Transaksi</h2>

                    <!-- Filter Bulan -->
                    <form method="GET" action="{{ route('history.index') }}" class="mb-6 flex items-center space-x-4">
                        <label for="month" class="text-gray-700 dark:text-gray-300">Pilih Bulan:</label>
                        <select name="month" id="month" class="p-2 border rounded dark:bg-gray-700 dark:text-white">
                            <option value="">Semua</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                </option>
                                @endfor
                        </select>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Tampilkan</button>
                    </form>

                    <!-- Tabel Transaksi -->
                    <div class="overflow-x-auto bg-white rounded-lg shadow dark:bg-gray-800">
                        <table class="w-full table-auto text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm uppercase">
                                <tr>
                                    <th class="px-6 py-3">No</th>
                                    <th class="px-6 py-3">Nama Pembeli</th>
                                    <th class="px-6 py-3">Nama Barang</th>
                                    <th class="px-6 py-3">Jumlah</th>
                                    <th class="px-6 py-3">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($historyTransaction as $index => $transaction)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">{{ $transaction->nama_pembeli ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $transaction->item->nama_barang ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $transaction->jumlah }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($transaction->tanggal)->translatedFormat('d F Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Tidak ada transaksi pada bulan ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>