<?php
require_once 'app/functions/MY_model.php';
$rekam_medis = get("SELECT *, rm.id as rm_id FROM rekam_medis rm
                    INNER JOIN pasien ON rm.pasien_id = pasien.id 
                    INNER JOIN dokter ON rm.dokter_id = dokter.id 
                    INNER JOIN ruang ON rm.ruang_id = ruang.id");

$no = 1;
?>

<!-- User Table -->
<section id="column-selectors">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h4 class="card-title">Rekam Medis</h4>
          <div>
            <a href="?page=tambah-rekam-medis" class="btn btn-primary round waves-effect waves-light">
              Tambah Rekam Medis
            </a>
            <button id="printBtn" class="btn btn-secondary round waves-effect waves-light ml-2">
              Cetak
            </button>
          </div>
        </div>
        <div class="card-content">
          <div class="card-body card-dashboard">
            <div class="table-responsive">
              <table id="rekamMedisTable" class="table table-striped">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Tanggal Periksa</th>
                    <th>Nama Pasien</th>
                    <th>Keluhan</th>
                    <th>Nama Dokter</th>
                    <th>Diagnosa</th>
                    <th>Nama Layanan</th>
                    <th>Ruang</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($rekam_medis as $rm) : ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= $rm['tanggal']; ?></td>
                      <td><?= $rm['nama_pasien']; ?></td>
                      <td><?= $rm['keluhan']; ?></td>
                      <td><?= $rm['nama_dokter']; ?></td>
                      <td><?= $rm['diagnosa']; ?></td>
                      <td>
                        <?php
                        $obats = mysqli_query($conn, "SELECT * FROM rm_obat JOIN obat ON rm_obat.obat_id = obat.id WHERE rm_id = '$rm[rm_id]'") or die(mysqli_error($conn));
                        while ($obat = mysqli_fetch_assoc($obats)) {
                          echo $obat['nama_obat'] . '<br>';
                        }
                        ?>
                      </td>
                      <td><?= $rm['nama_ruang']; ?></td>
                      <td>
                        <a href="?page=hapus-rekam-medis&id=<?= $rm['rm_id']; ?>" class="btn-hapus"><i class="feather icon-trash"></i></a>
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
    const doc = new jsPDF('landscape');
    doc.setFont("helvetica");
    doc.setFontSize(12);

    // Lebar halaman dan margin
    const pageWidth = doc.internal.pageSize.getWidth();
    const margin = 10;

    // Header
    doc.text("RUMAH KLINIK GIGI", pageWidth / 2, 10, null, null, "center");
    doc.setFontSize(10);
    doc.text("Jl. Perwira Ujung Belakang No.298, Belakang Balok, Kec. Aur Birugo Tigo Baleh, Kota Bukittinggi", pageWidth / 2, 17, null, null, "center");
    doc.line(margin, 23, pageWidth - margin, 23);  // Garis di bawah alamat dengan jarak 20px

    doc.setFontSize(12);
    doc.text("Laporan Data Rekam Medis", pageWidth / 2, 35, null, null, "center");

    // Tabel
    let startY = 40;

    // Header Row
    doc.setFontSize(10);
    const headers = ["No", "Tanggal", "Pasien", "Keluhan", "Dokter", "Diagnosa", "Layanan", "Ruang"];
    const headerWidth = [10, 25, 30, 30, 30, 40, 40, 40];

    // Hitung lebar total tabel
    const tableWidth = headerWidth.reduce((a, b) => a + b, 0);
    const startX = (pageWidth - tableWidth) / 2; // Hitung posisi X untuk menengahkan tabel

    // Gambar header tabel
    let x = startX;
    headers.forEach((header, index) => {
      doc.rect(x, startY, headerWidth[index], 10);
      doc.text(header, x + 2, startY + 7);
      x += headerWidth[index];
    });

    // Baris data
    const table = document.getElementById('rekamMedisTable').getElementsByTagName('tbody')[0];
    const rows = table.getElementsByTagName('tr');

    let y = startY + 10;
    for (let i = 0; i < rows.length; i++) {
      const cells = rows[i].getElementsByTagName('td');
      x = startX;
      for (let j = 0; j < cells.length - 1; j++) {
        doc.rect(x, y, headerWidth[j], 10);
        doc.text(cells[j].innerText, x + 2, y + 7);
        x += headerWidth[j];
      }
      y += 10;
    }

    // Footer
    y += 20;
    const tanggal = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    doc.text("Bukittinggi, " + tanggal, 230, y);
    y += 10;
    doc.text("Pimpinan", 230, y);
    y += 20;
    doc.text("Pimpinan", 230, y);

    // Open the generated PDF in a new window
    window.open(doc.output('bloburl'));
  });
</script>

