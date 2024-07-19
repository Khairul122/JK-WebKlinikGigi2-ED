<div class="content-header row">

  <div class="content-header-right col-md-12">
    <a href="?page=layanan" class="btn btn-light float-right mb-2">Kembali</a>
  </div>
</div>
<section id="basic-horizontal-layouts">
  <div class="row match-height">
    <div class="col-md-12 col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Tambah Layanan</h4>
        </div>
        <div class="card-content">
          <div class="card-body">
            <form action="app/layanan/proses/create.php" method="post">
              <div class="form-body">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group row">
                      <div class="col-md-4">
                        <label>Nama Layanan</label>
                      </div>
                      <div class="col-md-8">

                        <input type="text" placeholder="Nama Layanan" class="form-control" name="nama_obat" required>
                      </div>
                    </div>
                  </div>

                  <!-- <div class="col-12">
                      <div class="form-group row">
                        <div class="col-md-4">
                          <label>Keterangan</label>
                        </div>
                        <div class="col-md-8">
                          <input type="text" placeholder="keterangan" class="form-control" name="keterangan" required>
                        </div>
                      </div>
                    </div> -->

                  <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary">Save</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>