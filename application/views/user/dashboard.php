  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Dashboard</small>
      </h1>
      <b><marquee style="font-size:25px;color:green;font-family:Times New Roman;padding-top:5px;"scrollamount="13">.: Selamat Datang di Halaman Sistem Informasi Manajamen STOK BARANG INVENTARISASI ASSET 2023 :.</marquee></b>
      <h4><p>Halo Sobat <b><?php echo $_SESSION['nama']; ?></b> Asset !!! Anda telah login sebagai <b><?php echo $_SESSION['level']; ?></b>.</p></h4>
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
        <div class="col-md-8">
          <div class="row">
            <div class="col-lg-6 col-xs-6">
              <div class="small-box bg-blue">
                <div class="inner">
                  <h3><?php echo $jumlahBarang; ?></h3>

                  <p>Total Barang</p>
                </div>
                <div class="icon">
                  <i class="fa fa-book"></i>
                </div>
                <a href="<?php echo base_url('user/barang') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-6 col-xs-6">
             <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo $transaksiBarang; ?></h3>

                  <p>Transaksi Barang</p>
                </div>
                <div class="icon">
                  <i class="fa fa-table"></i>
                </div>
                <a href="<?php echo base_url('user/riwayat') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-12 col-xs-12">
    <div class="box box-info">
      <div class="box-header with-border">
        <i class="fa fa-briefcase"></i>
        <h3 class="box-title">Statistik <small>Data Stok Barang</small></h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <canvas id="data-stokbarang" style="height:250px"></canvas>
      </div>
    </div>
  </div> 
          </div>
        </div> 
        <div class="col-md-4">
            <div class="box box-widget widget-user-2">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-yellow">
                <div class="widget-user-image">
                  <img class="img-circle" src="<?php echo base_url('assets') ?>/dist/img/avatar4.png" alt="User Avatar">
                </div>
                <!-- /.widget-user-image -->
                <h3 class="widget-user-username"><?php echo $this->session->userdata('nama'); ?></h3>
                <h5 class="widget-user-desc">Terdaftar Pada <?php echo date('d-M-Y H:i:s', strtotime($this->session->userdata('createDate'))); ?></h5>
              </div>
              <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                  <li><a>Nama Lengkap <span class="pull-right badge bg-yellow"><?php echo $this->session->userdata('nama'); ?></span></a></li>
                  <li><a>Username <span class="pull-right badge bg-yellow"><?php echo $this->session->userdata('username'); ?></span></a></li>
                  <li><a>Password <span class="pull-right badge bg-yellow">Disembunyikan</span></a></li>
                  <li><a>Level <span class="pull-right badge bg-yellow"><?php echo $this->session->userdata('level'); ?></span></a></li>
                  <li><a>Terdaftar Pada <span class="pull-right badge bg-yellow"><?php echo date('d-M-Y H:i:s', strtotime($this->session->userdata('createDate'))); ?></span></a></li>
                  <li><a>Alamat IP <span class="pull-right badge bg-red"><?php echo base_url() ?></span></a></li>
                </ul>
              </div>
            </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
<script src="<?php echo base_url(); ?>assets/plugins/chartjs/Chart.min.js"></script>
<script>
  //data transaksi
  var pieChartCanvas = $("#data-stokbarang").get(0).getContext("2d");
  var pieChart = new Chart(pieChartCanvas);
  var PieData = <?php echo $jumlahBarang; ?>;

  var pieOptions = {
    segmentShowStroke: true,
    segmentStrokeColor: "#fff",
    segmentStrokeWidth: 2,
    percentageInnerCutout: 50,
    animationSteps: 100,
    animationEasing: "easeOutBounce",
    animateRotate: true,
    animateScale: false,
    responsive: true,
    maintainAspectRatio: true,
    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
  };

  pieChart.Doughnut(PieData, pieOptions);
</script>
  <!-- /.content-wrapper -->