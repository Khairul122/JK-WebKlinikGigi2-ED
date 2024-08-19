<?php
require_once 'app/functions/MY_model.php';
$rekam_medis = get("SELECT *, rm.id as rm_id FROM rekam_medis rm
                    INNER JOIN pasien ON rm.pasien_id = pasien.id 
                    INNER JOIN dokter ON rm.dokter_id = dokter.id 
                    INNER JOIN ruang ON rm.ruang_id = ruang.id");

$total_pembayaran = 0; // Inisialisasi total pembayaran

foreach ($rekam_medis as &$rm) {
  $obats = mysqli_query($conn, "SELECT nama_obat FROM rm_obat JOIN obat ON rm_obat.obat_id = obat.id WHERE rm_id = '{$rm['rm_id']}'") or die(mysqli_error($conn));
  $rm['nama_obat'] = [];
  while ($obat = mysqli_fetch_assoc($obats)) {
    $rm['nama_obat'][] = $obat['nama_obat'];
  }
  $rm['nama_obat'] = implode(", ", $rm['nama_obat']); // Menggabungkan nama obat menjadi satu string

  // Menambahkan nilai pembayaran ke total
  $total_pembayaran += $rm['pembayaran'];
}
?>


<!-- Form Laporan Harian-->
<section id="column-selectors">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h4 class="card-title">Laporan</h4>
          <div>
            <!-- Form untuk memilih tanggal awal dan akhir -->
            <div class="d-flex align-items-center">
              <form id="filterForm" class="form-inline">
                <div class="form-group mr-2">
                  <label for="tanggal_awal" class="mr-1">Tanggal Awal:</label>
                  <input type="date" id="tanggal_awal" name="tanggal_awal" class="form-control">
                </div>
                <div class="form-group mr-2">
                  <label for="tanggal_akhir" class="mr-1">Tanggal Akhir:</label>
                  <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control">
                </div>
                <input type="text" id="namaPimpinan" placeholder="Nama Pimpinan" class="form-control mr-2" />
                <button type="button" id="printBtn" class="btn btn-primary">Cetak</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Form Laporan Bulanan -->
<section id="monthly-report">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h4 class="card-title">Laporan Bulanan</h4>
          <div class="d-flex align-items-center">
            <!-- Form untuk memilih bulan dan tahun -->
            <form id="monthlyForm" class="form-inline">
              <div class="form-group mr-2">
                <label for="bulan" class="mr-1">Bulan:</label>
                <input type="month" id="bulan" name="bulan" class="form-control">
              </div>
              <!-- Input untuk Nama Pimpinan -->
              <div class="form-group mr-2">
                <input type="text" id="namaPimpinanBulanan" placeholder="Nama Pimpinan" class="form-control">
              </div>
              <!-- Tombol Cetak -->
              <button type="button" id="printMonthlyBtn" class="btn btn-primary">Cetak Laporan Bulanan</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- Form Laporan Tahunan -->
<section id="annual-report">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h4 class="card-title">Laporan Tahunan</h4>
          <div class="d-flex align-items-center">
            <form id="annualForm" class="form-inline">
              <div class="form-group mr-2">
                <label for="tahun" class="mr-1">Tahun:</label>
                <input type="number" id="tahun" name="tahun" class="form-control" min="2000" max="2099" step="1" value="<?php echo date('Y'); ?>">
              </div>
              <!-- Input untuk Nama Pimpinan -->
              <div class="form-group mr-2">
                <input type="text" id="namaPimpinanTahunan" placeholder="Nama Pimpinan" class="form-control">
              </div>
              <button type="button" id="printAnnualBtn" class="btn btn-primary">Cetak Laporan Tahunan</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

// Laporan Harian
<script>
  document.getElementById('printBtn').addEventListener('click', function() {
    const {
      jsPDF
    } = window.jspdf;
    const doc = new jsPDF('landscape');
    doc.setFont("helvetica");
    doc.setFontSize(12);

    function safeText(value) {
      return value !== null && value !== undefined ? String(value) : '';
    }

    const tanggalAwal = new Date(document.getElementById('tanggal_awal').value);
    const tanggalAkhir = new Date(document.getElementById('tanggal_akhir').value);
    const namaPimpinan = document.getElementById('namaPimpinan').value || "Pimpinan";

    const data = <?php echo json_encode($rekam_medis); ?>;
    let totalPembayaran = 0;

    const pageWidth = doc.internal.pageSize.getWidth();
    const margin = 10;

    doc.text("RUMAH KLINIK GIGI", pageWidth / 2, 10, null, null, "center");
    doc.setFontSize(10);
    doc.text("Jl. Perwira Ujung Belakang No.298, Belakang Balok, Kec. Aur Birugo Tigo Baleh, Kota Bukittinggi", pageWidth / 2, 17, null, null, "center");
    doc.line(margin, 23, pageWidth - margin, 23);

    doc.setFontSize(12);
    doc.text("Laporan Data Rekam Medis", pageWidth / 2, 35, null, null, "center");

    if (isNaN(tanggalAwal.getTime()) || isNaN(tanggalAkhir.getTime())) {
      doc.text("Periode: Semua Tanggal", pageWidth / 2, 45, null, null, "center");
    } else {
      doc.text(`Periode: ${tanggalAwal.toLocaleDateString()} - ${tanggalAkhir.toLocaleDateString()}`, pageWidth / 2, 45, null, null, "center");
    }

    let startY = 55;
    const headers = ["No", "Tanggal", "Pasien", "Keluhan", "Dokter", "Diagnosa", "Layanan", "Ruang", "Pembayaran"];
    const headerWidth = [10, 30, 30, 30, 30, 40, 50, 30, 35];
    const tableWidth = headerWidth.reduce((a, b) => a + b, 0);
    const startX = (pageWidth - tableWidth) / 2;
    const paddingX = 2;

    let x = startX;
    headers.forEach((header, index) => {
      doc.rect(x, startY, headerWidth[index], 10);
      doc.text(safeText(header), x + paddingX, startY + 7);
      x += headerWidth[index];
    });

    startY += 10;

    let nomorUrut = 1; // Inisialisasi nomor urut
    data.forEach((item) => {
      const itemDate = new Date(item.tanggal);

      if (
        (!isNaN(tanggalAwal.getTime()) && itemDate >= tanggalAwal) &&
        (!isNaN(tanggalAkhir.getTime()) && itemDate <= tanggalAkhir)
      ) {
        let x = startX;
        doc.rect(x, startY, headerWidth[0], 10);
        doc.text(safeText(nomorUrut++), x + paddingX, startY + 7); // No
        x += headerWidth[0];

        doc.rect(x, startY, headerWidth[1], 10);
        doc.text(safeText(item.tanggal), x + paddingX, startY + 7); // Tanggal
        x += headerWidth[1];

        doc.rect(x, startY, headerWidth[2], 10);
        doc.text(safeText(item.nama_pasien), x + paddingX, startY + 7); // Pasien
        x += headerWidth[2];

        doc.rect(x, startY, headerWidth[3], 10);
        doc.text(safeText(item.keluhan), x + paddingX, startY + 7); // Keluhan
        x += headerWidth[3];

        doc.rect(x, startY, headerWidth[4], 10);
        doc.text(safeText(item.nama_dokter), x + paddingX, startY + 7); // Dokter
        x += headerWidth[4];

        doc.rect(x, startY, headerWidth[5], 10);
        doc.text(safeText(item.diagnosa), x + paddingX, startY + 7); // Diagnosa
        x += headerWidth[5];

        doc.rect(x, startY, headerWidth[6], 10);
        doc.text(safeText(item.nama_obat), x + paddingX, startY + 7); // Obat
        x += headerWidth[6];

        doc.rect(x, startY, headerWidth[7], 10);
        doc.text(safeText(item.nama_ruang), x + paddingX, startY + 7); // Ruang
        x += headerWidth[7];

        doc.rect(x, startY, headerWidth[8], 10);
        const pembayaran = parseInt(item.pembayaran) || 0;
        totalPembayaran += pembayaran; // Tambahkan ke total pembayaran
        doc.text("Rp " + pembayaran.toLocaleString('id-ID'), x + paddingX, startY + 7); // Pembayaran
        x += headerWidth[8];

        startY += 10;
      }
    });

    // Tambahkan baris total pembayaran di bawah tabel
    x = startX;
    const totalCellWidth = headerWidth.slice(0, 8).reduce((a, b) => a + b, 0);
    doc.rect(x, startY, totalCellWidth, 10); // Gabungkan 8 kolom pertama
    doc.text("Total", x + totalCellWidth / 2, startY + 7, null, null, "center"); // Posisikan teks "Total" di tengah kolom yang digabungkan
    x += totalCellWidth;

    doc.rect(x, startY, headerWidth[8], 10);
    doc.text("Rp " + totalPembayaran.toLocaleString('id-ID'), x + 2, startY + 7);

    // Footer
    startY += 20;
    const tanggalCetak = new Date().toLocaleDateString('id-ID', {
      day: 'numeric',
      month: 'long',
      year: 'numeric'
    });
    doc.text(safeText("Bukittinggi, " + tanggalCetak), 230, startY);
    startY += 7;
    doc.text(safeText("Pimpinan"), 230, startY);
    startY += 20;
    doc.text(safeText(namaPimpinan), 230, startY);

    // Open the generated PDF in a new window
    window.open(doc.output('bloburl'));
  });
</script>


<!-- Laporan Bulanan -->
<script>
  document.getElementById('printMonthlyBtn').addEventListener('click', function() {
    const {
      jsPDF
    } = window.jspdf;
    const doc = new jsPDF('landscape');
    doc.setFont("helvetica");
    doc.setFontSize(12);

    function safeText(value) {
      return value !== null && value !== undefined ? String(value) : '';
    }

    const bulan = document.getElementById('bulan').value;
    if (!bulan) {
      alert("Pilih bulan terlebih dahulu.");
      return;
    }

    const [year, month] = bulan.split("-");

    const data = <?php echo json_encode($rekam_medis); ?>;
    let totalPembayaran = 0;

    const pageWidth = doc.internal.pageSize.getWidth();
    const margin = 10;

    const namaPimpinan = document.getElementById('namaPimpinanBulanan').value || "Pimpinan";

    doc.text("RUMAH KLINIK GIGI", pageWidth / 2, 10, null, null, "center");
    doc.setFontSize(10);
    doc.text("Jl. Perwira Ujung Belakang No.298, Belakang Balok, Kec. Aur Birugo Tigo Baleh, Kota Bukittinggi", pageWidth / 2, 17, null, null, "center");
    doc.line(margin, 23, pageWidth - margin, 23);

    doc.setFontSize(12);
    doc.text(`Laporan Rekam Medis Bulan ${month}/${year}`, pageWidth / 2, 35, null, null, "center");

    let startY = 55;
    const headers = ["No", "Tanggal", "Pasien", "Keluhan", "Dokter", "Diagnosa", "Layanan", "Ruang", "Pembayaran"];
    const headerWidth = [10, 30, 30, 30, 30, 40, 50, 30, 35];
    const tableWidth = headerWidth.reduce((a, b) => a + b, 0);
    const startX = (pageWidth - tableWidth) / 2;
    const paddingX = 2;

    let x = startX;
    headers.forEach((header, index) => {
      doc.rect(x, startY, headerWidth[index], 10);
      doc.text(safeText(header), x + paddingX, startY + 7);
      x += headerWidth[index];
    });

    startY += 10;

    let nomorUrut = 1; // Inisialisasi nomor urut
    data.forEach((item) => {
      const itemDate = new Date(item.tanggal);
      const itemMonth = (itemDate.getMonth() + 1).toString().padStart(2, '0');
      const itemYear = itemDate.getFullYear();

      if (itemMonth === month && itemYear.toString() === year) {
        let x = startX;
        doc.rect(x, startY, headerWidth[0], 10);
        doc.text(safeText(nomorUrut++), x + paddingX, startY + 7); // No
        x += headerWidth[0];

        doc.rect(x, startY, headerWidth[1], 10);
        doc.text(safeText(item.tanggal), x + paddingX, startY + 7);
        x += headerWidth[1];

        doc.rect(x, startY, headerWidth[2], 10);
        doc.text(safeText(item.nama_pasien), x + paddingX, startY + 7);
        x += headerWidth[2];

        doc.rect(x, startY, headerWidth[3], 10);
        doc.text(safeText(item.keluhan), x + paddingX, startY + 7);
        x += headerWidth[3];

        doc.rect(x, startY, headerWidth[4], 10);
        doc.text(safeText(item.nama_dokter), x + paddingX, startY + 7);
        x += headerWidth[4];

        doc.rect(x, startY, headerWidth[5], 10);
        doc.text(safeText(item.diagnosa), x + paddingX, startY + 7);
        x += headerWidth[5];

        doc.rect(x, startY, headerWidth[6], 10);
        doc.text(safeText(item.nama_obat), x + paddingX, startY + 7);
        x += headerWidth[6];

        doc.rect(x, startY, headerWidth[7], 10);
        doc.text(safeText(item.nama_ruang), x + paddingX, startY + 7);
        x += headerWidth[7];

        doc.rect(x, startY, headerWidth[8], 10);
        const pembayaran = parseInt(item.pembayaran) || 0;
        totalPembayaran += pembayaran;
        doc.text("Rp " + pembayaran.toLocaleString('id-ID'), x + paddingX, startY + 7);
        x += headerWidth[8];

        startY += 10;
      }
    });

    x = startX;
    const totalCellWidth = headerWidth.slice(0, 8).reduce((a, b) => a + b, 0);
    doc.rect(x, startY, totalCellWidth, 10);
    doc.text("Total", x + totalCellWidth / 2, startY + 7, null, null, "center");
    x += totalCellWidth;

    doc.rect(x, startY, headerWidth[8], 10);
    doc.text("Rp " + totalPembayaran.toLocaleString('id-ID'), x + 2, startY + 7);

    startY += 20;
    const tanggalCetak = new Date().toLocaleDateString('id-ID', {
      day: 'numeric',
      month: 'long',
      year: 'numeric'
    });
    doc.text(safeText("Bukittinggi, " + tanggalCetak), 230, startY);
    startY += 7;
    doc.text(safeText("Pimpinan"), 230, startY);
    startY += 20;
    doc.text(safeText(namaPimpinan), 230, startY);

    window.open(doc.output('bloburl'));
  });
</script>

<!-- Laporan Tahunan -->
<script>
document.getElementById('printAnnualBtn').addEventListener('click', function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('landscape');
    doc.setFont("helvetica");
    doc.setFontSize(12);

    function safeText(value) {
        return value !== null && value !== undefined ? String(value) : '';
    }

    const tahun = document.getElementById('tahun').value;
    if (!tahun) {
        alert("Pilih tahun terlebih dahulu.");
        return;
    }

    const data = <?php echo json_encode($rekam_medis); ?>;
    let totalPembayaran = 0;

    const pageWidth = doc.internal.pageSize.getWidth();
    const margin = 10;

    // Ambil nama pimpinan dari input form tahunan
    const namaPimpinan = document.getElementById('namaPimpinanTahunan').value || "Pimpinan";

    // Header
    doc.text("RUMAH KLINIK GIGI", pageWidth / 2, 10, null, null, "center");
    doc.setFontSize(10);
    doc.text("Jl. Perwira Ujung Belakang No.298, Belakang Balok, Kec. Aur Birugo Tigo Baleh, Kota Bukittinggi", pageWidth / 2, 17, null, null, "center");
    doc.line(margin, 23, pageWidth - margin, 23);

    doc.setFontSize(12);
    doc.text(`Laporan Rekam Medis Tahun ${tahun}`, pageWidth / 2, 35, null, null, "center");

    let startY = 55;
    const headers = ["No", "Bulan", "Jumlah", "Pembayaran"];
    const headerWidth = [10, 40, 30, 30];
    const tableWidth = headerWidth.reduce((a, b) => a + b, 0);
    const startX = (pageWidth - tableWidth) / 2;
    const paddingX = 2;

    // Header Tabel
    let x = startX;
    headers.forEach((header, index) => {
        doc.rect(x, startY, headerWidth[index], 10);
        doc.text(safeText(header), x + paddingX, startY + 7);
        x += headerWidth[index];
    });

    startY += 10;

    const months = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    // Menyiapkan data per bulan yang ada dalam database
    let nomorUrut = 1; // Inisialisasi nomor urut
    months.forEach((month, index) => {
        const monthlyData = data.filter(item => {
            const itemDate = new Date(item.tanggal);
            return itemDate.getFullYear() == tahun && itemDate.getMonth() == index;
        });

        if (monthlyData.length > 0) {
            const count = monthlyData.length;

            const pembayaranBulan = monthlyData.reduce((total, item) => {
                return total + parseInt(item.pembayaran || 0);
            }, 0);

            totalPembayaran += pembayaranBulan;

            // Baris data
            x = startX;
            doc.rect(x, startY, headerWidth[0], 10);
            doc.text(safeText(nomorUrut++), x + paddingX, startY + 7); // No
            x += headerWidth[0];

            doc.rect(x, startY, headerWidth[1], 10);
            doc.text(safeText(month), x + paddingX, startY + 7); // Bulan
            x += headerWidth[1];

            doc.rect(x, startY, headerWidth[2], 10);
            doc.text(safeText(count), x + paddingX, startY + 7); // Jumlah
            x += headerWidth[2];

            doc.rect(x, startY, headerWidth[3], 10);
            doc.text("Rp " + pembayaranBulan.toLocaleString('id-ID'), x + paddingX, startY + 7); // Pembayaran
            x += headerWidth[3];

            startY += 10;
        }
    });

    // Baris Total Pembayaran
    x = startX;
    const totalCellWidth = headerWidth.slice(0, 3).reduce((a, b) => a + b, 0);
    doc.rect(x, startY, totalCellWidth, 10);
    doc.text("Total", x + totalCellWidth / 2, startY + 7, null, null, "center");
    x += totalCellWidth;

    doc.rect(x, startY, headerWidth[3], 10);
    doc.text("Rp " + totalPembayaran.toLocaleString('id-ID'), x + 2, startY + 7);

    // Footer
    startY += 20;
    const tanggalCetak = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    doc.text(safeText("Bukittinggi, " + tanggalCetak), 230, startY);
    startY += 7;
    doc.text(safeText("Pimpinan"), 230, startY);
    startY += 20;
    doc.text(safeText(namaPimpinan), 230, startY);

    // Open the generated PDF in a new window
    window.open(doc.output('bloburl'));
});
</script>
