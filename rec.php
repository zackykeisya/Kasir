<?php
session_start();

$nominal = $_SESSION['nominal'] ?? 0;
$totalHarga = $_SESSION['totalHarga'] ?? 0;

$kembalian = $nominal - $totalHarga;

// Jika pembayaran telah selesai, set flag pembayaran
if (!isset($_SESSION['payment_complete']) && $nominal >= $totalHarga) {
    $_SESSION['payment_complete'] = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            max-width: 300px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
        }
        .receipt {
            width: 100%;
        }
        .receipt-header,
        .receipt-footer {
            text-align: center;
            margin-bottom: 20px;
        }
        .receipt-header p,
        .receipt-footer p {
            margin: 0;
        }
        .receipt-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .receipt-table th, 
        .receipt-table td {
            padding: 5px;
            text-align: left;
        }
        .receipt-table th {
            border-bottom: 1px dashed #000;
        }
        .receipt-table td {
            border-bottom: 1px solid #ccc;
        }
        .total, .paid, .change {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        @media print {
            body {
                margin: 0;
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="receipt-header">
            <h2>Bukti Pembayaran</h2>
            <p>No. Transaksi: #<?php echo rand(10, 1000000000); ?></p>
            <p>Tanggal: <?php echo date("Y-m-d H:i:s"); ?></p>
        </div>
        <table class="receipt-table">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($_SESSION["data_barang"])): ?>
                    <?php foreach ($_SESSION["data_barang"] as $barang): ?>
                        <tr>
                            <td><?php echo $barang['nama']; ?></td>
                            <td>Rp <?php echo number_format($barang['harga'], 2, ',', '.'); ?></td>
                            <td><?php echo $barang['jumlah']; ?></td>
                            <td>Rp <?php echo number_format($barang['total'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="total">
            <span>Total</span>
            <span>Rp <?php echo number_format($totalHarga, 2, ',', '.'); ?></span>
        </div>
        <div class="paid">
            <span>Uang Yang Dibayarkan</span>
            <span>Rp <?php echo number_format($nominal, 2, ',', '.'); ?></span>
        </div>
        <?php if ($kembalian > 0): ?>
            <div class="change">
                <span>Kembalian</span>
                <span>Rp <?php echo number_format($kembalian, 2, ',', '.'); ?></span>
            </div>
        <?php endif; ?>
        <div class="receipt-footer">
            <p>Terima kasih atas pembelian Anda!</p>
            <p>Semoga hari Anda menyenangkan.</p>
        </div>
        <div class="text-center">
            <button class="btn btn-warning mb-2" id="printBtn"><i class="bi bi-printer"></i> Print</button>
            <a href="clear_session.php" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Kembali ke Kasir</a>
        </div>
    </div>
    <script>
        document.getElementById('printBtn').addEventListener('click', function() {
            window.print();
        });
    </script>
</body>
</html>
