<?php
require_once 'app/functions/MY_model.php';
$obats = get("SELECT * FROM obat");

$no = 1;

?>

<!-- User Table -->
<section id="column-selectors">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Layanan</h4>
          <a href="?page=tambah-layanan" class="btn btn-primary round waves-effect waves-light">
            Tambah Layanan
          </a>
        </div>
        <div class="card-content">
          <div class="card-body card-dashboard">
            <div class="table-responsive">
              <table class="table table-striped dataex-html5-selectors">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($obats as $obat) : ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= $obat['nama_obat']; ?></td>
                      <td>
                        <a href="?page=edit-layanan&id=<?= $obat['id']; ?>"><i class="m-1 feather icon-edit-2"></i></a>
                        <a href="?page=hapus-layanan&id=<?= $obat['id']; ?>" class="btn-hapus"><i class="feather icon-trash"></i></a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- User Table -->
<?php $title = 'layanan'; ?>