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
    <td class="px-6 py-4">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
    <td class="px-6 py-4">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
    <td class="px-6 py-4 flex">
        <form action="{{ route('items.destroy', $item) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="text-red-500 hover:text-red-700 font-medium text-sm px-2 py-1 border border-red-500 rounded">Hapus</button>
        </form>
        <a href="{{ route('items.checkout', $item->id) }}" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-green-700">Checkout</a>
    </td>
</tr>
@endforeach