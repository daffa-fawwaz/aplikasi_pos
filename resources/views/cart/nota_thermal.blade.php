@php
function pad($text, $length, $padType = STR_PAD_RIGHT) {
return str_pad($text, $length, ' ', $padType);
}
@endphp

SONI ELEKTRONIK
Jl. Demuk No. 123, Tulungagung
-------------------------------
Tanggal : {{ $tanggal }}
Pembeli : {{ $nama_pembeli }}
No HP : {{ $no_hp }}
Alamat : {{ $alamat }}
-------------------------------
Barang Jml Harga
@foreach ($transactions as $trx)
{{ pad(substr($trx->item->nama_barang, 0, 12), 12) }}
{{ pad($trx->jumlah, 4) }}
{{ pad(number_format($trx->total_harga), 12) }}
@endforeach
-------------------------------
TOTAL : Rp {{ number_format($total) }}
Terima kasih :)