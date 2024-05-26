<?php
session_start();

$totalHarga = 0;
if (isset($_SESSION['data_barang']) && !empty($_SESSION['data_barang'])) {
    foreach ($_SESSION['data_barang'] as $barang) {
        $totalHarga += $barang['total'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn-submit'])) {
    $nominal = $_POST["bayar"];
    if ($nominal < $totalHarga) {
        $totalHarga -= $nominal;
        // Update session data_barang with reduced total
        $remainingNominal = $nominal;
        foreach ($_SESSION['data_barang'] as &$barang) {
            if ($remainingNominal == 0) {
                break;
            }
            if ($remainingNominal >= $barang['total']) {
                $remainingNominal -= $barang['total'];
                $barang['total'] = 0;
            } else {
                $barang['total'] -= $remainingNominal;
                $remainingNominal = 0;
            }
        }
        // Remove items with zero total
        $_SESSION['data_barang'] = array_filter($_SESSION['data_barang'], function($item) {
            return $item['total'] > 0;
        });

        $_SESSION['totalHarga'] = $totalHarga;
        $message = "Nominal uang yang dimasukkan kurang, sisa yang harus dibayar: Rp " . number_format($totalHarga, 0, ',', '.');
        $alertClass = "alert-warning";
    } else {
        $_SESSION['nominal'] = $nominal;
        $_SESSION['totalHarga'] = $totalHarga;
        header("Location: rec.php?bayar=$nominal");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayar Sekarang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="card-title text-center">Bayar Sekarang</h3>
                <form action="" method="post" class="d-flex flex-column gap-3">
                    <div class="mb-3">
                        <label for="bayar" class="form-label">Masukan Nominal Uang</label>
                        <input id="bayar" class="form-control border-success" type="number" name="bayar" required>
                    </div>
                    <?php if (isset($message)): ?>
                        <div class="alert <?= $alertClass ?>"><?= $message ?></div>
                    <?php endif; ?>
                    <?php if ($totalHarga > 0): ?>
                        <div class="mb-2 fw-bold">Total Yang Harus Dibayar: Rp <?= number_format($totalHarga, 0, ',', '.') ?></div>
                        <button class="btn btn-primary" type="submit" name="btn-submit"><i class="bi bi-credit-card-2-back-fill"></i> Bayar</button>
                    <?php else: ?>
                        <p class="text-danger text-center fw-bold">Tidak ada barang yang harus dibayar</p>
                    <?php endif; ?>
                    <a class="btn btn-secondary" href="index.php"><i class="bi bi-arrow-left"></i> Kembali</a>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>

