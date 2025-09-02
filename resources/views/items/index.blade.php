<!DOCTYPE html>
<html x-data="data()" :class="{ 'theme-dark': dark }" lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DashAdmin - Daftar Barang</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/tailwind.output.css') }}">

    <!-- CSS & JS via Vite --> 
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- <script src="{{ asset('assets/js/dashboard.js') }}"></script>  --}}
    
    <!-- Alpine.js -->
    <script src="{{ asset('assets/js/init-alpine.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script> 
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
</head>

<body>
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        <!-- sidebar -->
        @include('components.sidebar')

        <div class="flex flex-col flex-1 w-full">
            <!-- HEADER -->
            @include('components.header')

            <main class="h-full overflow-y-auto">
                <div class="container px-6 mx-auto grid">
                    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                        Daftar Barang
                    </h2>

                    @if (session('success'))
                        <div class="mb-4 text-green-600 dark:text-green-400">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- TABEL -->
                    <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                                <tr>
                                    <th class="px-6 py-3">Nama Barang</th>
                                    <th class="px-6 py-3">Tipe</th>
                                    <th class="px-6 py-3">Stok Barang</th>
                                    <th class="px-6 py-3">Harga Kulak</th>
                                    <th class="px-6 py-3">Harga Jual</th>
                                    <th class="px-6 py-3">Tanggal Kulak</th>
                                    <th class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach ($items as $item)
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="px-6 py-4">{{ $item->nama_barang }}</td>
                                        <td class="px-6 py-4">{{ $item->tipe_barang }}</td>
                                        <td class="px-6 py-4">
                                            <div x-data="stokHandler({{ $item->id }}, {{ $item->stok }})" class="flex items-center gap-2">
                                                <button @click="updateStok(-1)"
                                                    class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-orange-600">-</button>
                                                <input type="text" x-model="stok" readonly
                                                    class="w-12 py-1 text-center border rounded bg-gray-100 dark:bg-gray-700 dark:text-white" />
                                                <button @click="updateStok(1)"
                                                    class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">+</button>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4">{{ $item->tanggal_order }}</td>
                                        <td class="px-2 py-4 flex gap-3">
                                            <form action="{{ route('items.destroy', $item) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-700 font-medium text-sm px-2 py-1 border border-red-500 rounded">
                                                    Hapus
                                                </button>
                                            </form>
                                            <a href="{{ route('items.checkout', $item->id) }}"
                                                class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-green-700">Checkout</a>
                                            <form action="{{ route('cart.add', $item->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit"
                                                    class="border px-4 py-1 text-black rounded">+</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Aksi bawah -->
                    <div class="flex items-center justify-between mt-6">
                        <div class="flex gap-2">
                            <a href="{{ route('items.create') }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow-md">
                                + Tambah Barang
                            </a>
                            <a href="{{ route('cart.index') }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow-md">
                                + Cetak Nota
                            </a>
                            <a href="{{ route('barcode.index') }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow-md">
                                Scan Barcode
                            </a>
                        </div>
                        <div>
                            {{ $items->links() }}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Script tambahan -->
    <script>
        function stokHandler(itemId, initialStok) {
            return {
                stok: initialStok,
                async updateStok(val) {
                    this.stok += val;
                    if (this.stok < 0) this.stok = 0;

                    try {
                        let res = await fetch(`/items/${itemId}/update-stok`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                stok: this.stok
                            })
                        });
                        let data = await res.json();
                        if (!data.success) {
                            alert('Gagal update stok!');
                            this.stok -= val; // rollback
                        }
                    } catch (e) {
                        alert('Terjadi kesalahan saat mengupdate stok.');
                        this.stok -= val; // rollback
                    }
                }
            }
        }

        // Live search
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const tableBody = document.getElementById('table-body');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.trim();
                    fetch(`/items/search?query=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(data => tableBody.innerHTML = data.html)
                        .catch(err => console.error('Search error:', err));
                });
            }
        });
    </script>
</body>

</html>
