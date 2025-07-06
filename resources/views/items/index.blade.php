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
                <div class="container px-6 mx-auto grid">
                    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">Daftar Barang</h2>

                    @if(session('success'))
                    <div class="mb-4 text-green-600 dark:text-green-400">{{ session('success') }}</div>
                    @endif

                    <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                                <tr>
                                    <th class="px-6 py-3">Nama Barang</th>
                                    <th class="px-6 py-3">Tipe</th>
                                    <th class="px-6 py-3">Stok Barang</th>
                                    <th class="px-6 py-3">Harga Kulak</th>
                                    <!-- <th class="px-6 py-3">Harga Jual</th> -->
                                    <th class="px-6 py-3">Tanggal kulak</th>
                                    <th class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach ($items as $item)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-6 py-4">{{ $item->nama_barang }}</td>
                                    <td class="px-6 py-4">{{ $item->tipe_barang }}</td>
                                    <td class="px-6 py-4">
                                        <div
                                            x-data="{ 
                                            stok: {{ $item->stok }},
                                            updateStok(val) {
                                            this.stok += val;
                                            if (this.stok < 0) this.stok = 0;

                                            fetch('/items/{{ $item->id }}/update-stok', {
                                            method: 'PATCH',
                                            headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: JSON.stringify({ stok: this.stok })
                                            })
                                            .then(res => res.json())
                                            .then(data => {
                                            if (!data.success) {
                                            alert('Gagal update stok!');
                                            // rollback stok jika gagal update
                                            this.stok -= val;
                                            } else if (this.stok === 0) {
                                            $el.closest('tr').remove();
                                            }
                                            })
                                            .catch(() => {
                                            alert('Terjadi kesalahan saat mengupdate stok.');
                                            this.stok -= val; 
                                            });
                                            }
                                            }"
                                            class="flex items-center gap-2">
                                            <button
                                                @click="updateStok(-1)"
                                                class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-orange-600 mr-2">-</button>
                                            <input
                                                type="text"
                                                :value="stok"
                                                readonly
                                                class="w-12 py-1 text-center border rounded bg-gray-100 dark:bg-gray-700 dark:text-white" />
                                            <button
                                                @click="updateStok(1)"
                                                class="px-2 py-1 bg-blue-500 ml-2 text-white rounded hover:bg-blue-600">+</button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}
                                    </td>
                                    <!-- <td class="px-6 py-4">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}
                                    </td> -->
                                    <td class="px-6 py-4">{{$item->tanggal_order}}</td>

                                    <td class="px-2 py-4 flex gap-1">
                                        <form action="{{ route('items.destroy', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-500 hover:text-red-700 font-medium text-sm px-2 py-1 border border-red-500 rounded">Hapus</button>
                                        </form>
                                        <a href="{{ route('items.checkout', $item->id) }}" class="bg-blue-600 text-white px-2 py-1 ml-2 rounded hover:bg-green-700">Checkout</a>
                                        <!-- <form action="{{ route('cart.add', $item->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                                            <input type="number" name="jumlah" value="1" min="1">
                                            <button type="submit" class="btn btn-primary">+ Keranjang</button>
                                        </form> -->
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="w-32 mt-6 mb-5 ml-2">
                            <a href="{{ route('items.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow-md transition duration-200">
                                + Tambah Barang
                            </a>
                        </div>
                        <div class="mt-3">
                            {{ $items->links() }}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const tableBody = document.getElementById('table-body');

            searchInput.addEventListener('input', function() {
                const query = this.value.trim();

                fetch(`/items/search?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        tableBody.innerHTML = data.html;
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                    });
            });
        });
    </script>

</body>

</html>