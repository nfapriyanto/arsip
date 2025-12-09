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
      <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title">
                Nama Barang : <?php
                $kode = $this->db->query("SELECT kode FROM tb_barang WHERE id = '".$id."' ")->result();
                foreach ($kode as $kode_brg) {
                    echo $kode_brg->kode;
                }
                ?>
            </h3>
            <div class="pull-right btn-group">
                <!-- Tombol Kembali -->
                <a href="<?php echo base_url('user/barang') ?>" class="btn btn-warning btn-sm">
                    <div class="fa fa-arrow-left"></div> Kembali
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
                            <th>Jumlah</th>
                            <th>Waktu</th>
                            <th>Unit</th>
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
                                if($rwt->jenis == 'Masuk'){
                                    echo '<div class="label label-success">'.$rwt->jenis.'</div>';
                                } else {
                                    echo '<div class="label label-danger">'.$rwt->jenis.'</div>';
                                }
                                ?>
                            </td>
                            <td><?php echo $rwt->jumlah ?></td>
                            <td><?php echo date('d-M-Y H:i:s', strtotime($rwt->createDate)) ?></td>
                            <td><?php echo $rwt->unit ?></td>
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