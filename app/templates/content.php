<?php
if (isset($_GET['page'])) {

	switch ($_GET['page']) {
		case 'dokter':
			require_once 'app/dokter/views/index.php';
			break;
		case 'tambah-dokter':
			require_once 'app/dokter/views/create.php';
			break;
		case 'edit-dokter':
			require_once 'app/dokter/views/edit.php';
			break;
		case 'hapus-dokter':
			require_once 'app/dokter/proses/delete.php';
			break;
		case 'pasien':
			require_once 'app/pasien/views/index.php';
			break;
		case 'tambah-pasien':
			require_once 'app/pasien/views/create.php';
			break;
		case 'edit-pasien':
			require_once 'app/pasien/views/edit.php';
			break;
		case 'hapus-pasien':
			require_once 'app/pasien/proses/delete.php';
			break;
		case 'layanan':
			require_once 'app/layanan/views/index.php';
			break;
		case 'tambah-layanan':
			require_once 'app/layanan/views/create.php';
			break;
		case 'edit-layanan':
			require_once 'app/layanan/views/edit.php';
			break;
		case 'hapus-layanan':
			require_once 'app/layanan/proses/delete.php';
			break;
		case 'ruang':
			require_once 'app/ruang/views/index.php';
			break;
		case 'tambah-ruang':
			require_once 'app/ruang/views/create.php';
			break;
		case 'edit-ruang':
			require_once 'app/ruang/views/edit.php';
			break;
		case 'hapus-ruang':
			require_once 'app/ruang/proses/delete.php';
			break;
		case 'rekam-medis':
			require_once 'app/rekam-medis/views/index.php';
			break;
		case 'tambah-rekam-medis':
			require_once 'app/rekam-medis/views/create.php';
			break;
		case 'edit-rekam-medis':
			require_once 'app/rekam-medis/views/edit.php';
			break;
		case 'hapus-rekam-medis':
			require_once 'app/rekam-medis/proses/delete.php';
			break;
		case 'lap-rekam-medis':
			require_once 'app/laporan/views/rekam-medis.php';
			break;
		case 'laporan-pemilik':
			require_once 'app/laporan-pemilik/views/index.php';
			break;
	}
} else {
	require_once 'app/dashboard/views/index.php';
}
