<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DashAdmin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="./../../assets/css/tailwind.output.css" />
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="./../../assets/js/init-alpine.js"></script>
</head>

<body>
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        @include('components.sidebar')

        <div class="flex flex-col flex-1 w-full">
            @include('components.header')

            <main class="h-full overflow-y-auto">
                <div class="container mx-auto px-6 py-6">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-white">Keranjang Belanja</h2>

                    {{-- Alert Messages --}}
                    @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                    @endif

                    @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ $errors->first() }}
                    </div>
                    @endif

                    @if ($cartItems->isEmpty())
                    <p class="text-gray-700 dark:text-white">Keranjang kosong.</p>
                    @else
                    <table class="w-full text-left border">
                        <thead>
                            <tr class="bg-gray-200 text-gray-700">
                                <th class="px-4 py-2">Nama Barang</th>
                                <th class="px-4 py-2">Harga</th>
                                <th class="px-4 py-2">Jumlah</th>
                                <th class="px-4 py-2">Subtotal</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach ($cartItems as $cartItem)
                            @php
                            $hargaManual = $cartItem->harga_manual ?? 0;
                            $subtotal = $hargaManual; // Sudah dianggap total harga
                            $total += $subtotal;
                            @endphp
                            <tr>
                                <td class="border px-4 py-2">{{ $cartItem->item->nama_barang }}</td>
                                <td class="border px-4 py-2">
                                    <form action="{{ route('cart.updateHarga', $cartItem->id) }}" method="POST" class="flex items-center">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="harga_manual" value="{{ number_format($cartItem->harga_manual ?? 0, 0, ',', '.') }}" required
                                            class="w-24 px-2 py-1 border rounded text-sm harga-input" placeholder="Harga" />

                                        <button type="submit" class="ml-2 text-blue-600 hover:underline text-sm">Simpan</button>
                                    </form>
                                </td>
                                <td class="border px-4 py-2">{{ $cartItem->quantity }}</td>
                                <td class="border px-4 py-2">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                <td class="border px-4 py-2">
                                    <form action="{{ route('cart.remove', $cartItem->id) }}" method="POST" onsubmit="return confirm('Hapus barang dari keranjang?')">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            <tr class="font-semibold bg-gray-100">
                                <td colspan="3" class="border px-4 py-2 text-right">Total</td>
                                <td class="border px-4 py-2">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                <td class="border px-4 py-2"></td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Form Checkout --}}
                    <form id="checkout-form" action="{{ route('cart.checkout') }}" method="POST" class="mt-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block mb-1">Nama Pembeli</label>
                                <input type="text" name="nama_pembeli" required class="w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block mb-1">No HP</label>
                                <input type="text" name="no_hp" required class="w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block mb-1">Alamat</label>
                                <input type="text" name="alamat" required class="w-full border rounded px-3 py-2">
                            </div>
                        </div>
                        <button type="submit" class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Checkout</button>
                    </form>
                    @endif
                </div>
            </main>
        </div>
    </div>

    <script>
        document.querySelectorAll('.harga-input').forEach(input => {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^\d]/g, ''); // Hanya angka
                value = new Intl.NumberFormat('id-ID').format(value); // Format ke Rp
                e.target.value = value;
            });

            input.closest('form').addEventListener('submit', function(e) {
                // Ubah format "1.000.000" jadi "1000000" sebelum submit
                const rawValue = input.value.replace(/[^\d]/g, '');
                input.value = rawValue;
            });
        });
    </script>

</body>

</html>