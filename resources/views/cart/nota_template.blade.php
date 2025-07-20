@php
function pad($text, $length, $padType = STR_PAD_RIGHT) {
$text = substr($text, 0, $length);
return str_pad($text, $length, ' ', $padType);
}
@endphp

PUTRA ANUGERAH JAYA

KEPADA YTH:

{{ $nama_pembeli }}
{{ $alamat }}
TELP: {{ $no_hp }}
{{ pad('TANGGAL', 15) }}: {{ $tanggal }}

NOTA

--------------------------------------------------------------------------------
{{ pad('NO', 4) }}{{ pad('NAMA BARANG', 35) }}{{ pad('QTY', 5) }}{{ pad('HARGA', 15, STR_PAD_LEFT) }}{{ pad('TOTAL', 15, STR_PAD_LEFT) }}
--------------------------------------------------------------------------------
@foreach ($transactions as $i => $trx)
{{ pad($i + 1, 4) }}{{ pad(substr($trx->item->nama_barang, 0, 35), 35) }}{{ pad($trx->jumlah, 5) }}{{ pad(number_format($trx->harga_satuan, 0, ',', '.'), 15, STR_PAD_LEFT) }}{{ pad(number_format($trx->total_harga, 0, ',', '.'), 15, STR_PAD_LEFT) }}
@endforeach
--------------------------------------------------------------------------------
TOTAL {{ pad('', 60) }}Rp {{ number_format($total, 0, ',', '.') }}

BANK BCA: 4480 880 250 AN. BANK 2037186485
ATAS NAMA: PUTRA ANUGERAH JAYA CV

(TERIMA KASIH)