<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
  <div class="navbar-header">
    <ul class="nav navbar-nav flex-row">
      <li class="nav-item mr-auto">
        <a class="navbar-brand" href="html/ltr/vertical-menu-template-semi-dark/index.html">
          <div class="brand-logo"></div>
          <h2 class="brand-text">Klinik Gigi</h2>
        </a>
      </li>
      <li class="nav-item nav-toggle">
        <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
          <i class="feather icon-x d-block d-xl-none font-medium-4 primary toggle-icon"></i>
          <i class="toggle-icon feather icon-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary" data-ticon="icon-disc"></i>
        </a>
      </li>
    </ul>
  </div>
  <div class="shadow-bottom"></div>
  <div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

      <?php if ($_SESSION['user']['level'] === 'pemilik'): ?>
        <li class="nav-item <?php echo is_active(''); ?>">
          <a href="index.php">
            <i class="feather icon-home"></i>
            <span class="menu-title" data-i18n="Dashboard">Dashboard</span>
          </a>
        </li>
        <li class="nav-item <?php echo is_active('laporan-pemilik'); ?>">
          <a href="?page=laporan-pemilik">
            <i class="feather icon-file"></i>
            <span class="menu-title" data-i18n="Laporan Pemilik">Laporan Pemilik</span>
          </a>
        </li>
      <?php endif; ?>

      <?php if ($_SESSION['user']['level'] === 'admin'): ?>
        <li class="nav-item <?php echo is_active(''); ?>">
          <a href="index.php">
            <i class="feather icon-home"></i>
            <span class="menu-title" data-i18n="Dashboard">Dashboard</span>
          </a>
        </li>

        <li class="nav-item <?php echo is_active('dokter'); ?>">
          <a href="?page=dokter">
            <i class="feather icon-user"></i>
            <span class="menu-title" data-i18n="Dokter">Dokter</span>
          </a>
        </li>

        <li class="nav-item <?php echo is_active('pasien'); ?>">
          <a href="?page=pasien">
            <i class="feather icon-user"></i>
            <span class="menu-title" data-i18n="Pasien">Pasien</span>
          </a>
        </li>

        <li class="nav-item <?php echo is_active('ruang'); ?>">
          <a href="?page=ruang">
            <i class="feather icon-check-square"></i>
            <span class="menu-title" data-i18n="Ruang">Ruang</span>
          </a>
        </li>

        <li class="nav-item <?php echo is_active('layanan'); ?>">
          <a href="?page=layanan">
            <i class="feather icon-calendar"></i>
            <span class="menu-title" data-i18n="Layanan">Layanan</span>
          </a>
        </li>

        <li class="nav-item <?php echo is_active('rekam-medis'); ?>">
          <a href="?page=rekam-medis">
            <i class="feather icon-bar-chart"></i>
            <span class="menu-title" data-i18n="Rekam Medis">Rekam Medis</span>
          </a>
        </li>
      <?php endif; ?>

    </ul>
  </div>
</div>
<!-- END: Main Menu-->
