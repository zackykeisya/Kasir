<?php
session_start();

if (!isset($_SESSION["data_barang"])) {
    $_SESSION["data_barang"] = [];
}

$barang_list = [
    "Mie Instan" => 3000,
    "Aqua" => 5000,
    "Kopi" => 7000
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['btn-submit'])) {
        $nama = $_POST["nama"];
        $harga = $barang_list[$nama];
        $jumlah = $_POST["jumlah"];
        $total = $harga * $jumlah;

        $_SESSION["data_barang"][] = [
            "nama" => $nama,
            "harga" => $harga,
            "jumlah" => $jumlah,
            "total" => $total
        ];
        $_SESSION['success_message'] = "Data berhasil ditambahkan";
        header("Location: index.php");
        exit();
    } elseif (isset($_POST['btn-delete'])) {
        $index = $_POST["delete-index"];
        unset($_SESSION['data_barang'][$index]);
        $_SESSION['data_barang'] = array_values($_SESSION['data_barang']);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

if (isset($_SESSION['payment_complete'])) {
    unset($_SESSION['data_barang']);
    unset($_SESSION['payment_complete']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container mt-4">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class='alert alert-success d-flex justify-content-between' role='alert'>
                <?= $_SESSION['success_message'] ?>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="card-title text-center">Masukan Data Barang</h3>
                <form method="post" class="d-flex flex-column gap-3">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Pilih Barang</label>
                        <select id="nama" class="form-select" name="nama" required>
                            <?php foreach ($barang_list as $nama => $harga): ?>
                                <option value="<?= $nama ?>"><?= $nama ?> - Rp <?= number_format($harga, 0, ',', '.') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input id="jumlah" class="form-control" type="number" name="jumlah" required>
                    </div>
                    <button class="btn btn-primary" type="submit" name="btn-submit"><i class="bi bi-plus-lg"></i> Tambah</button>
                    <a href="checkout.php" class="btn btn-success"><i class="bi bi-cart-fill"></i> CheckOut</a>
                </form>
            </div>
        </div>

        <hr>
        <p class="text-secondary">List Barang</p>
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($_SESSION["data_barang"])): ?>
                    <?php foreach ($_SESSION["data_barang"] as $index => $barang): ?>
                        <tr class="text-center">
                            <td><?= $index + 1 ?></td>
                            <td><?= $barang['nama'] ?></td>
                            <td>Rp <?= number_format($barang['harga'], 0, ',', '.') ?></td>
                            <td><?= $barang['jumlah'] ?></td>
                            <td>Rp <?= number_format($barang['total'], 0, ',', '.') ?></td>
                            <td>
                                <form method='post' class='d-inline-block'>
                                    <input type='hidden' name='delete-index' value='<?= $index ?>'>
                                    <button type='submit' class='btn btn-danger btn-sm' name='btn-delete'><i class='bi bi-trash3-fill'></i> Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                <tr class="text-center fw-bold">
                    <td colspan="5">Total Barang</td>
                    <td>
                        <?php
                        $totalBarang = array_sum(array_column($_SESSION["data_barang"], 'jumlah'));
                        echo $totalBarang;
                        ?>
                    </td>
                </tr>
                <tr class="text-center fw-bold">
                    <td colspan="5">Total Harga</td>
                    <td>
                        <?php
                        $totalHarga = array_sum(array_column($_SESSION["data_barang"], 'total'));
                        echo "Rp " . number_format($totalHarga, 0, ',', '.');
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    </body>
    </html>
