<?php
session_start();

// Hapus data barang dan flag pembayaran
unset($_SESSION['data_barang']);
unset($_SESSION['nominal']);
unset($_SESSION['totalHarga']);
unset($_SESSION['payment_complete']);

// Redirect kembali ke halaman kasir
header("Location: index.php");
exit();
?>
