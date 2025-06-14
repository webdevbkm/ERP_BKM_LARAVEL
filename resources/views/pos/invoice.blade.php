<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $transaction->invoice_number }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 80mm; /* Lebar kertas thermal printer */
            margin: 0;
            padding: 5px;
            font-size: 10pt;
        }
        .header { text-align: center; margin-bottom: 10px; }
        .header h3 { margin: 0; }
        .header p { margin: 0; font-size: 8pt; }
        .info { border-top: 1px dashed; border-bottom: 1px dashed; padding: 5px 0; margin-bottom: 10px; }
        .info table { width: 100%; }
        .items table { width: 100%; border-collapse: collapse; }
        .items th, .items td { padding: 3px 0; }
        .items .price { text-align: right; }
        .summary { border-top: 1px dashed; padding-top: 5px; margin-top: 10px; }
        .summary table { width: 100%; }
        .footer { text-align: center; margin-top: 20px; font-size: 8pt; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="text-align: center; margin-bottom: 10px;">
        <a href="{{ route('pos.index') }}">Kembali ke POS</a> | 
        <button onclick="window.print()">Cetak Ulang</button>
    </div>

    <div class="receipt">
        <div class="header">
            <h3>BOKOR MAS GOLD</h3>
            <p>ARAYA</p>
            <p>Telp: 0812-3456-7890</p>
        </div>

        <div class="info">
            <table>
                <tr>
                    <td>No:</td>
                    <td>{{ $transaction->invoice_number }}</td>
                </tr>
                 <tr>
                    <td>Tanggal:</td>
                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td>Kasir:</td>
                    <td>{{ $transaction->user->name }}</td>
                </tr>
                <tr>
                    <td>Pelanggan:</td>
                    <td>{{ $transaction->customer->nama }}</td>
                </tr>
            </table>
        </div>

        <div class="items">
            <table>
                @foreach($transaction->details as $item)
                <tr>
                    <td colspan="2">{{ $item->product->nama }}</td>
                </tr>
                <tr>
                    <td>1 x {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="price">{{ number_format($item->price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </table>
        </div>

        <div class="summary">
            <table>
                <tr>
                    <td>Total</td>
                    <td class="price">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                </tr>
                 <tr>
                    <td>Bayar ({{ $transaction->payment_method }})</td>
                    <td class="price">Rp {{ number_format($transaction->amount_paid, 0, ',', '.') }}</td>
                </tr>
                 <tr>
                    <td>Kembali</td>
                    <td class="price">Rp {{ number_format($transaction->change, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Terima kasih telah berbelanja!</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
        </div>
    </div>
</body>
</html>
