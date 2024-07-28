<?php
session_start();
require_once '../../functions/MY_model.php';

$nama_ruang = $_POST['nama_ruang'];
$keterangan = $_POST['keterangan'];
$created_at = date('Y-m-d H:i:s');
$created_by = $_SESSION['user']['id'];

$query = "INSERT INTO ruang (nama_ruang, keterangan, created_at, created_by, deleted_by, deleted_at) VALUES ('$nama_ruang', '$keterangan', '$created_at', '$created_by', NULL, NULL)";

if (create($query) === 1) {
  echo '<script>document.location.href="../../../?page=ruang";</script>';
} else {
  echo mysqli_error($conn);
}
