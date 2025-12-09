  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Riwayat Barang
        <small>Riwayat Barang</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Riwayat Barang</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title">
                Nama Barang : <?php echo $kode; ?>
            </h3>
            <div class="pull-right btn-group">
                <a href="<?php echo base_url('admin/barang') ?>" class="btn btn-success btn-sm">
                    <div class="fa fa-arrow-left"></div> Kembali
                </a>
                <a href="<?php echo base_url('admin/barang/printRiwayatBarang/').$id ?>" class="btn btn-primary btn-sm">
                    <div class="fa fa-print"></div> Cetak Data
                </a>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped" id="example1">
                    <thead>
                        <tr>
                            <th width="5px">No</th>
                            <th>Nama Barang</th>
                            <th>Jenis</th>
                            <th>Waktu</th>
                            <th>Peminjam</th>
                            <th>No Telp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
                            $no =1;
                            foreach ($riwayat as $rwt) {
                        ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td><?php echo $rwt->kode ?></td>
                            <td>
                                <?php
                                if($rwt->jenis == 'Pengembalian'){
                                    echo '<div class="label label-success">'.$rwt->jenis.'</div>';
                                } else {
                                    echo '<div class="label label-danger">'.$rwt->jenis.'</div>';
                                }
                                ?>
                            </td>
                            <td><?php echo date('d-M-Y H:i:s', strtotime($rwt->createDate)) ?></td>
                            <td><?php echo isset($rwt->peminjam) ? $rwt->peminjam : (isset($rwt->unit) ? $rwt->unit : '-'); ?></td>
                            <td><?php echo $rwt->noTlp ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->