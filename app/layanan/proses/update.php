<?php
session_start();
require_once '../../functions/MY_model.php';

$id = $_POST['id'];
$nama_obat = $_POST['nama_obat'];
$updated_at = date('Y-m-d H:i:s');
$updated_by = $_SESSION['user']['id'];
$query = "UPDATE obat SET nama_obat = '$nama_obat', updated_at = '$updated_at', updated_by = '$updated_by' WHERE id = '$id'";
if (create($query) === 1) {
  echo '<script>document.location.href="../../../?page=layanan";</script>';
} else {
  echo mysqli_error($conn);
}
