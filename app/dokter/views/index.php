<?php
require_once 'app/functions/MY_model.php';
$dokters = get("SELECT * FROM dokter");

$no = 1;
?>

<!-- User Table -->
<section id="column-selectors">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h4 class="card-title">Dokter</h4>
          <div>
            <a href="?page=tambah-dokter" class="btn btn-primary round waves-effect waves-light">
              Tambah Dokter
            </a>
            <button id="printBtn" class="btn btn-secondary round waves-effect waves-light ml-2">
              Cetak
            </button>
          </div>
        </div>
        <div class="card-content">
          <div class="card-body card-dashboard">
            <div class="table-responsive">
              <table id="dokterTable" class="table table-striped">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Spesialis</th>
                    <th>Alamat</th>
                    <th>Nomor Telepon</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($dokters as $dokter) : ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= $dokter['nama_dokter']; ?></td>
                      <td><?= $dokter['spesialis']; ?></td>
                      <td><?= $dokter['alamat']; ?></td>
                      <td><?= $dokter['telephone']; ?></td>
                      <td>
                        <a href="?page=edit-dokter&id=<?= $dokter['id']; ?>"><i class="m-1 feather icon-edit-2"></i></a>
                        <a href="?page=hapus-dokter&id=<?= $dokter['id']; ?>" class="btn-hapus">
                          <i class="feather icon-trash"></i>
                        </a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
  document.getElementById('printBtn').addEventListener('click', function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.setFont("helvetica");
    doc.setFontSize(12);

    // Header
    doc.text("RUMAH KLINIK GIGI", 105, 10, null, null, "center");
    doc.setFontSize(10);
    doc.text("Jl. Perwira Ujung Belakang No.298, Belakang Balok, Kec. Aur Birugo Tigo Baleh, Kota Bukittinggi", 105, 17, null, null, "center");
    doc.line(10, 23, 200, 23);  // Garis di bawah alamat dengan jarak 20px

    doc.setFontSize(12);
    doc.text("Laporan Data Dokter", 105, 35, null, null, "center");

    // Table
    let startY = 40;

    // Header Row
    doc.setFontSize(10);
    doc.rect(10, startY, 190, 10); // Garis luar header
    doc.text("No", 12, startY + 7);
    doc.text("Nama", 30, startY + 7);
    doc.text("Spesialis", 70, startY + 7);
    doc.text("Alamat", 110, startY + 7);
    doc.text("Nomor Telepon", 150, startY + 7);

    // Garis vertikal dalam header
    doc.line(28, startY, 28, startY + 10);
    doc.line(68, startY, 68, startY + 10);
    doc.line(108, startY, 108, startY + 10);
    doc.line(148, startY, 148, startY + 10);

    // Data rows
    const table = document.getElementById('dokterTable').getElementsByTagName('tbody')[0];
    const rows = table.getElementsByTagName('tr');

    let y = startY + 10;
    for (let i = 0; i < rows.length; i++) {
      const cells = rows[i].getElementsByTagName('td');
      doc.rect(10, y, 190, 10); // Garis luar data row
      doc.text(cells[0].innerText, 12, y + 7);
      doc.text(cells[1].innerText, 30, y + 7);
      doc.text(cells[2].innerText, 70, y + 7);
      doc.text(cells[3].innerText, 110, y + 7);
      doc.text(cells[4].innerText, 150, y + 7);

      // Garis vertikal dalam data row
      doc.line(28, y, 28, y + 10);
      doc.line(68, y, 68, y + 10);
      doc.line(108, y, 108, y + 10);
      doc.line(148, y, 148, y + 10);

      y += 10;
    }

    // Footer
    y += 20;
    const tanggal = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    doc.text("Bukittinggi, " + tanggal, 140, y);
    y += 10;
    doc.text("Pimpinan", 140, y);
    y += 20;
    y += 5;
    doc.text("Pimpinan", 140, y);

    // Open the generated PDF in a new window
    window.open(doc.output('bloburl'));
  });
</script>
