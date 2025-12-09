  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Laporan Barang
        <small>Laporan Barang</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Laporan Barang</li>
      </ol>
    </section>
  <!-- /.content-wrapper -->
  <!-- /tambah data -->
      <div class="box box-danger" style="margin-top: 15px">
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped" id="example1">
              <tbody>
                <center>
  <button class="btn btn-danger" data-toggle="modal" data-target="#cetakData">
        <div class="fa fa-print"></div> Cetak Data
      </button>
      <button class="btn btn-success" data-toggle="modal" data-target="#exportExcel">
        <i class="fa fa-download"></i> Export Excel
      </button>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  <!-- Modal Cetak Data -->
  <div class="modal fade" id="cetakData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><div class="fa fa-print"></div> Cetak Data</h4>
        </div>
        <form action="<?php echo base_url('admin/laporan/cetak') ?>" method="POST">
          <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tanggal awal</label>
                        <input type="date" class="form-control" name="tgl_awal" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tanggal akhir</label>
                        <input type="date" class="form-control" name="tgl_akhir" required>
                    </div>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="reset" class="btn btn-danger"><div class="fa fa-trash"></div> Reset</button>
            <button type="submit" class="btn btn-primary"><div class="fa fa-print"></div> Print</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Export Excel -->
  <div class="modal fade" id="exportExcel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><div class="fa fa-download"></div> Export Excel</h4>
        </div>
        <form action="<?php echo base_url('admin/laporan/exportExcel') ?>" method="POST">
          <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tanggal awal</label>
                        <input type="date" class="form-control" name="tgl_awal" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tanggal akhir</label>
                        <input type="date" class="form-control" name="tgl_akhir" required>
                    </div>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="reset" class="btn btn-danger"><div class="fa fa-trash"></div> Reset</button>
            <button type="submit" class="btn btn-primary"><div class="fa fa-download"></div> Export</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- jQuery 2.2.3 -->
<script src="<?php echo base_url('assets') ?>/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url('assets') ?>/bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="<?php echo base_url('assets') ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url('assets') ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url('assets') ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url('assets') ?>/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('assets') ?>/dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url('assets') ?>/dist/js/demo.js"></script>
<!-- PACE -->
<script src="<?php echo base_url('assets') ?>/plugins/pace/pace.min.js"></script>
<!-- page script -->
<script type="text/javascript">
  // To make Pace works on Ajax calls
  $(document).ajaxStart(function() { Pace.restart(); });
    $('.ajax').click(function(){
        $.ajax({url: '#', success: function(result){
            $('.ajax-content').html('<hr>Ajax Request Completed !');
        }});
    });
</script>

<!-- Sweet Alert -->
<script src="<?php echo base_url('assets/sweetalert') ?>/sweetalert.min.js"></script>
<script>
    //Notifikasi
    const flashData = $('.flash-data').data('flashdata');
    if (flashData){
      swal({
        title: "Success!",
        text: flashData,
        icon: "success",
        button: "Ok!",
      });
    }

    //Konfirmasi
    $('.tombol-yakin').on('click', function (e) {
      e.preventDefault();
      const href = $(this).attr('href');
      const isiData = $(this).data('isidata');
      swal({
        title: 'Apakah anda yakin?',
        text: isiData,
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      }).then((willDelete) => {
          if (willDelete) {
            document.location.href = href;
          }
        });
    });
</script>

<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>
