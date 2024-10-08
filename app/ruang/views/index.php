<?php
require_once 'app/functions/MY_model.php';
$ruangs = get("SELECT * FROM ruang");

$no = 1;
?>

<!-- User Table -->
<section id="column-selectors">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h4 class="card-title">Ruang</h4>
          <div class="d-flex align-items-center">
            <a href="?page=tambah-ruang" class="btn btn-primary round waves-effect waves-light">
              Tambah Ruang
            </a>
            <input type="text" id="namaPimpinan" placeholder="Nama Pimpinan" class="form-control ml-2" style="width: 200px;" />
            <button id="printBtn" class="btn btn-secondary round waves-effect waves-light ml-2">
              Cetak
            </button>
          </div>
        </div>
        <div class="card-content">
          <div class="card-body card-dashboard">
            <div class="table-responsive">
              <table id="ruangTable" class="table table-striped">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($ruangs as $ruang) : ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= $ruang['nama_ruang']; ?></td>
                      <td><?= $ruang['keterangan']; ?></td>
                      <td>
                        <a href="?page=edit-ruang&id=<?= $ruang['id']; ?>"><i class="m-1 feather icon-edit-2"></i></a>
                        <a href="?page=hapus-ruang&id=<?= $ruang['id']; ?>" class="btn-hapus"><i class="feather icon-trash"></i></a>
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

<!-- jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
  document.getElementById('printBtn').addEventListener('click', function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.setFont("helvetica");
    doc.setFontSize(12);

    // Ambil nama pimpinan dari input
    const namaPimpinan = document.getElementById('namaPimpinan').value || "Pimpinan";

    // Header
    doc.text("RUMAH KLINIK GIGI", 105, 10, null, null, "center");
    doc.setFontSize(10);
    doc.text("Jl. Perwira Ujung Belakang No.298, Belakang Balok, Kec. Aur Birugo Tigo Baleh, Kota Bukittinggi", 105, 17, null, null, "center");
    doc.line(10, 23, 200, 23);  // Garis di bawah alamat dengan jarak 20px

    doc.setFontSize(12);
    doc.text("Laporan Data Ruang", 105, 35, null, null, "center");

    // Table
    let startY = 40;

    // Header Row
    doc.setFontSize(10);
    doc.rect(10, startY, 190, 10); // Garis luar header
    doc.text("No", 12, startY + 7);
    doc.text("Nama", 30, startY + 7);
    doc.text("Keterangan", 70, startY + 7);

    // Garis vertikal dalam header
    doc.line(28, startY, 28, startY + 10);
    doc.line(68, startY, 68, startY + 10);

    // Data rows
    const table = document.getElementById('ruangTable').getElementsByTagName('tbody')[0];
    const rows = table.getElementsByTagName('tr');

    let y = startY + 10;
    for (let i = 0; i < rows.length; i++) {
      const cells = rows[i].getElementsByTagName('td');
      doc.rect(10, y, 190, 10); // Garis luar data row
      doc.text(cells[0].innerText, 12, y + 7);
      doc.text(cells[1].innerText, 30, y + 7);
      doc.text(cells[2].innerText, 70, y + 7);

      // Garis vertikal dalam data row
      doc.line(28, y, 28, y + 10);
      doc.line(68, y, 68, y + 10);

      y += 10;
    }

    // Footer
    y += 20;
    const tanggal = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    doc.text("Bukittinggi, " + tanggal, 140, y);
    y += 7;
    doc.text("Pimpinan", 140, y);
    y += 20;
    doc.text(namaPimpinan, 140, y);

    // Open the generated PDF in a new window
    window.open(doc.output('bloburl'));
  });
</script>
