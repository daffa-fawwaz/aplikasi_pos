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
                <div class="container mx-auto px-6 py-6">
                    <h2 class="text-2xl font-semibold mb-4">Keranjang Belanja</h2>

                    @if(session('success'))
                    <div class="text-green-600 mb-4">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                    <div class="text-red-600 mb-4">{{ $errors->first() }}</div>
                    @endif

                    @if($cartItems->isEmpty())
                    <p>Keranjang kosong.</p>
                    @else
                    <table class="w-full text-left border">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="px-4 py-2">Nama Barang</th>
                                <th class="px-4 py-2">Harga</th>
                                <th class="px-4 py-2">Jumlah</th>
                                <th class="px-4 py-2">Subtotal</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $total = 0;
                            @endphp
                            @foreach($cartItems as $cartItem)
                            @php
                            $subtotal = $cartItem->quantity * $cartItem->item->harga_jual;
                            $total += $subtotal;
                            @endphp
                            <tr>
                                <td class="border px-4 py-2">{{ $cartItem->item->nama_barang }}</td>
                                <td class="border px-4 py-2">Rp {{ number_format($cartItem->item->harga_jual, 0, ',', '.') }}</td>
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
                            <tr class="font-semibold">
                                <td colspan="3" class="border px-4 py-2 text-right">Total</td>
                                <td class="border px-4 py-2">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                <td class="border px-4 py-2"></td>
                            </tr>
                        </tbody>
                    </table>

                    <form action="{{ route('cart.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Checkout</button>
                    </form>
                    @endif
                </div>
            </main>
        </div>
    </div>

</body>

</html>