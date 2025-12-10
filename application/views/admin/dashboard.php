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
            <div class="box box-widget widget-user-2">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-green">
                <div class="widget-user-image">
                  <img class="img-circle" src="<?php echo base_url('assets') ?>/dist/img/avatar4.png" alt="User Avatar">
                </div>
                <!-- /.widget-user-image -->
                <h3 class="widget-user-username"><?php echo $this->session->userdata('nama'); ?></h3>
                <h5 class="widget-user-desc">Terdaftar Pada <?php echo date('d-M-Y H:i:s', strtotime($this->session->userdata('createDate'))); ?></h5>
              </div>
              <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                  <li><a>Aplikasi <span class="pull-right badge bg-red">Sistem Arsip Digital</span></a></li>
                  <li><a>Nama Lengkap <span class="pull-right badge bg-red"><?php echo $this->session->userdata('nama'); ?></span></a></li>
                  <li><a>Username <span class="pull-right badge bg-red"><?php echo $this->session->userdata('username'); ?></span></a></li>
                  <li><a>Password <span class="pull-right badge bg-red">Disembunyikan</span></a></li>
                  <li><a>Level <span class="pull-right badge bg-red"><?php echo $this->session->userdata('level'); ?></span></a></li>
                  <li><a>Terdaftar Pada <span class="pull-right badge bg-red"><?php echo date('d-M-Y H:i:s', strtotime($this->session->userdata('createDate'))); ?></span></a></li>
                  <li><a>Alamat IP <span class="pull-right badge bg-red"><?php echo base_url() ?></span></a></li>
                </ul>
              </div>
            </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->