<?php
require_once 'app/functions/MY_model.php';

// Koneksi ke database
$conn = mysqli_connect('localhost', 'root', '', 'klinik');

// Query untuk mengambil semua data rekam medis
$rekam_medis_query = "
    SELECT rm.*, pasien.nama_pasien, dokter.nama_dokter, ruang.nama_ruang, GROUP_CONCAT(obat.nama_obat SEPARATOR ', ') AS nama_obat
    FROM rekam_medis rm
    INNER JOIN pasien ON rm.pasien_id = pasien.id 
    INNER JOIN dokter ON rm.dokter_id = dokter.id 
    INNER JOIN ruang ON rm.ruang_id = ruang.id 
    LEFT JOIN rm_obat ON rm.id = rm_obat.rm_id
    LEFT JOIN obat ON rm_obat.obat_id = obat.id
    GROUP BY rm.id
";
$rekam_medis = mysqli_query($conn, $rekam_medis_query);

if (!$rekam_medis) {
  die('Query Error: ' . mysqli_error($conn));
}

// Menghitung total pembayaran
$total_pembayaran = 0;
?>

<!-- User Table -->
<section id="column-selectors">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <div class="d-flex align-items-center">
            <a href="?page=tambah-rekam-medis" class="btn btn-primary round waves-effect waves-light mr-3">
              Tambah Rekam Medis
            </a>
            <input type="text" id="namaPimpinan" placeholder="Nama Pimpinan" class="form-control mr-1" style="max-width: 200px;" />
            <button id="printBtn" class="btn btn-secondary round waves-effect waves-light" style="width: 60%;">
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
                    <th>Nama Obat</th>
                    <th>Ruang</th>
                    <th>Pembayaran</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  while ($rm = mysqli_fetch_assoc($rekam_medis)) :
                    $total_pembayaran += $rm['pembayaran'];
                  ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= htmlspecialchars($rm['tanggal']); ?></td>
                      <td><?= htmlspecialchars($rm['nama_pasien']); ?></td>
                      <td><?= htmlspecialchars($rm['keluhan']); ?></td>
                      <td><?= htmlspecialchars($rm['nama_dokter']); ?></td>
                      <td><?= htmlspecialchars($rm['diagnosa']); ?></td>
                      <td><?= htmlspecialchars($rm['nama_obat']); ?></td>
                      <td><?= htmlspecialchars($rm['nama_ruang']); ?></td>
                      <td>Rp <?= htmlspecialchars(number_format($rm['pembayaran'], 0, ',', '.')); ?></td>
                      <td>
                        <a href="?page=hapus-rekam-medis&id=<?= htmlspecialchars($rm['id']); ?>" class="btn-hapus"><i class="feather icon-trash"></i></a>
                      </td>
                    </tr>
                  <?php endwhile; ?>
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
  document.getElementById('printBtn').addEventListener('click', function() {
    const {
      jsPDF
    } = window.jspdf;
    const doc = new jsPDF('landscape');
    doc.setFont("helvetica");
    doc.setFontSize(12);

    // Lebar halaman dan margin
    const pageWidth = doc.internal.pageSize.getWidth();
    const margin = 10;

    // Ambil nama pimpinan dari input
    const namaPimpinan = document.getElementById('namaPimpinan').value || "Pimpinan";

    // Header
    doc.text("RUMAH KLINIK GIGI", pageWidth / 2, 10, null, null, "center");
    doc.setFontSize(10);
    doc.text("Jl. Perwira Ujung Belakang No.298, Belakang Balok, Kec. Aur Birugo Tigo Baleh, Kota Bukittinggi", pageWidth / 2, 17, null, null, "center");
    doc.line(margin, 23, pageWidth - margin, 23); // Garis di bawah alamat dengan jarak 20px

    doc.setFontSize(12);
    doc.text("Laporan Data Rekam Medis", pageWidth / 2, 35, null, null, "center");

    // Tabel
    let startY = 40;

    // Header Row
    doc.setFontSize(10);
    const headers = ["No", "Tanggal", "Pasien", "Keluhan", "Dokter", "Diagnosa", "Layanan", "Ruang", "Pembayaran"];
    const headerWidth = [10, 25, 30, 30, 30, 40, 40, 40, 30];

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
      for (let j = 0; j < headerWidth.length; j++) { // Loop through all cells including the new 'pembayaran' cell
        doc.rect(x, y, headerWidth[j], 10);
        doc.text(cells[j].innerText || '', x + 2, y + 7);
        x += headerWidth[j];
      }
      y += 10;
    }

    // Tambahkan baris total di tabel
    x = startX;
    const totalCellWidth = headerWidth.slice(0, 8).reduce((a, b) => a + b, 0);
    doc.rect(x, y, totalCellWidth, 10); // Gabungkan 8 kolom pertama
    doc.text("Total", x + totalCellWidth / 2, y + 7, null, null, "center"); // Posisikan teks "Total" di tengah kolom yang digabungkan
    x += totalCellWidth;

    // Kolom terakhir untuk total pembayaran
    doc.rect(x, y, headerWidth[8], 10);
    doc.text("Rp " + <?= number_format($total_pembayaran, 0, ',', '.'); ?>, x + 2, y + 7);

    // Footer
    y += 20;
    const tanggal = new Date().toLocaleDateString('id-ID', {
      day: 'numeric',
      month: 'long',
      year: 'numeric'
    });
    doc.text("Bukittinggi, " + tanggal, 230, y);
    y += 10;
    doc.text(namaPimpinan, 230, y);
    y += 20;
    doc.text(namaPimpinan, 230, y);

    // Open the generated PDF in a new window
    window.open(doc.output('bloburl'));
  });
</script>