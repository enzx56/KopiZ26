<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Struk Pesanan {{ $order->order_code }} — KopiZ</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 20px;
        }
        .receipt-container {
            max-width: 380px;
            margin: 0 auto;
            border: 1px dashed #666;
            padding: 20px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .divider { border-bottom: 1px dashed #000; margin: 12px 0; }
        .flex { display: flex; justify-content: space-between; }
        .mb-1 { margin-bottom: 4px; }
        .mt-2 { margin-top: 8px; }
        .print-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background: #3A2418;
            color: #fff;
            border: none;
            cursor: pointer;
            font-weight: bold;
            margin-bottom: 15px;
            border-radius: 6px;
        }
        @media print {
            .print-btn { display: none; }
            .receipt-container { border: none; padding: 0; }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <button class="print-btn" onclick="window.print()">Cetak Struk Ini</button>

        <div class="text-center">
            <h2 style="margin: 0;">KOPIZ</h2>
            <div style="font-size: 11px;">Kopinya Gen-Z</div>
            <div style="font-size: 10px;">Kopi Berkualitas, Harga Pas</div>
        </div>

        <div class="divider"></div>

        <div class="flex mb-1">
            <span>Kode Order:</span>
            <span class="bold">{{ $order->order_code }}</span>
        </div>
        <div class="flex mb-1">
            <span>Tanggal:</span>
            <span>{{ $order->order_date ? $order->order_date->format('d/m/Y H:i') : '-' }}</span>
        </div>
        <div class="flex mb-1">
            <span>Pelanggan:</span>
            <span class="bold">{{ $order->customer->name }}</span>
        </div>
        <div class="flex mb-1">
            <span>No. WA:</span>
            <span>{{ $order->customer->phone }}</span>
        </div>
        <div class="flex mb-1">
            <span>Jenis:</span>
            <span>{{ $order->order_type }}</span>
        </div>
        <div class="flex mb-1">
            <span>Pickup:</span>
            <span>{{ $order->pickup_at ?: 'Di Tempat' }}</span>
        </div>

        <div class="divider"></div>

        <div class="bold mb-1">RINCIAN PESANAN:</div>
        @foreach($order->items as $item)
            <div class="flex">
                <span>{{ $item->product_name_snapshot }} x {{ $item->quantity }}</span>
                <span>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
            </div>
            <div style="font-size: 11px; color: #555; margin-bottom: 4px;">
                @ Rp{{ number_format($item->unit_price, 0, ',', '.') }}
            </div>
        @endforeach

        <div class="divider"></div>

        <div class="flex bold" style="font-size: 14px;">
            <span>TOTAL:</span>
            <span>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>
        <div class="flex mb-1 mt-2">
            <span>Status:</span>
            <span class="bold">{{ $order->status }}</span>
        </div>

        @if($order->customer_notes)
            <div class="mt-2" style="font-size: 11px;">
                <span class="bold">Catatan:</span> {{ $order->customer_notes }}
            </div>
        @endif

        <div class="divider"></div>

        <div class="text-center" style="font-size: 11px;">
            Terima kasih telah memesan di KopiZ!<br>
            Nikmati kopimu dan selamat beraktivitas ☕
        </div>
    </div>
</body>
</html>
