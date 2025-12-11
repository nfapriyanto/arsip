  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Dashboard</small>
      </h1>
      <div style="text-align:center;font-size:25px;color:green;font-family:Arial;padding-top:5px;font-weight:bold;">
        .: Selamat Datang di Halaman Sistem Penyimpanan Arsip Digital :.
      </div>
      <h4><p>Halo...<b><?php echo $_SESSION['nama']; ?></b> !!! Anda telah login sebagai <b><?php echo $_SESSION['level']; ?></b>.</p></h4>
      <b><div class="icon">
        <i class="fa fa-clock-o"></i>  
          <?php
                date_default_timezone_set('Asia/Jakarta');
                echo "<font size=3 color='blue' face='arial bold'>";
                echo date('d-M-Y H:i:s');
                echo " WIB";
                echo "</font>";
            ?>
      </div></b>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-lg-3 col-xs-6">
              <div class="small-box bg-blue">
                <div class="inner">
                  <h3><?php echo $jumlahArsip; ?></h3>

                  <p>Total Arsip</p>
                </div>
                <div class="icon">
                  <i class="fa fa-archive"></i>
                </div>
                <a href="<?php echo base_url('admin/arsip') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-3 col-xs-6">
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?php echo $jumlahKategori; ?></h3>

                  <p>Kategori Arsip</p>
                </div>
                <div class="icon">
                  <i class="fa fa-folder"></i>
                </div>
                <a href="<?php echo base_url('admin/arsip') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-3 col-xs-6">
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo $jumlahPengguna; ?></h3>

                  <p>Total Pengguna</p>
                </div>
                <div class="icon">
                  <i class="fa fa-group"></i>

                </div>
                <a href="<?php echo base_url('admin/manajemenUser') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-3 col-xs-6">
             <div class="small-box bg-red">
                <div class="inner">
                  <h3><?php echo $totalAkses; ?></h3>

                  <p>Total Akses</p>
                </div>
                <div class="icon">
                  <i class="fa fa-history"></i>
                </div>
                <a href="<?php echo base_url('admin/riwayat') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
        <div class="col-md-12">
            <div class="box box-widget">
              <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-bar-chart"></i> Statistik Kategori Arsip</h3>
                <div class="box-tools pull-right">
                  <div class="form-group" style="margin: 0; padding: 5px 0;">
                    <label style="margin-right: 10px; font-weight: normal;">Pilih Kategori Parent:</label>
                    <select id="selectParentKategori" class="form-control" style="display: inline-block; width: auto; min-width: 200px;">
                      <option value="all" <?php echo (empty($selectedParentId) || $selectedParentId == 'all') ? 'selected' : ''; ?>>Semua Kategori</option>
                      <?php if(!empty($listKategoriParent)): ?>
                        <?php foreach($listKategoriParent as $parent): ?>
                          <option value="<?php echo $parent->id; ?>" <?php echo ($selectedParentId == $parent->id) ? 'selected' : ''; ?>>
                            <?php echo $parent->nama; ?>
                          </option>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="box-body">
                <div id="statistikChart" style="height: 400px;"></div>
                <div id="chartEmpty" class="alert alert-info" style="display: none;">
                  <i class="fa fa-info-circle"></i> Belum ada sub-kategori arsip yang tersedia untuk kategori parent yang dipilih.
                </div>
              </div>
              <div class="box-footer">
                <a href="<?php echo base_url('admin/arsip') ?>" class="btn btn-primary btn-sm">
                  <i class="fa fa-folder"></i> Kelola Kategori Arsip
                </a>
              </div>
            </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<script type="text/javascript">
// Simpan data chart awal untuk digunakan di footer
window.statistikChartData = [
    <?php 
    if(!empty($statistikKategori)):
        $first = true;
        foreach($statistikKategori as $stat): 
            if(!$first) echo ',';
            $first = false;
        ?>
        {
            y: '<?php echo addslashes($stat->nama); ?>',
            a: <?php echo $stat->jumlah_arsip; ?>
        }
        <?php 
        endforeach;
    endif;
    ?>
];
</script>