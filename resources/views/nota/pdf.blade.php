<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Nota Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        .detail {
            margin-bottom: 15px;
        }

        .detail p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #444;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        .total {
            margin-top: 15px;
            text-align: right;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Nota Transaksi</h2>
        <p>{{ \Carbon\Carbon::parse($transaction->tanggal)->format('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $transaction->item->nama_barang }}</td>
                <td>{{ $transaction->jumlah }}</td>
                <td>Rp {{ number_format($transaction->item->harga_jual, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <p class="total">Total: Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</p>

    <div class="footer">
        <p>Terima kasih atas transaksi Anda!</p>
    </div>

</body>

</html>