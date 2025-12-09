  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Riwayat Arsip
        <small>Riwayat Akses Arsip</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Riwayat Arsip</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title">
                Judul Arsip : <?php echo $arsip->judul; ?>
            </h3>
            <div class="pull-right btn-group">
                <a href="<?php echo base_url('admin/arsip/kategori/').$arsip->kategori_id ?>" class="btn btn-success btn-sm">
                    <div class="fa fa-arrow-left"></div> Kembali
                </a>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="150">Nomor Arsip</th>
                            <td><?php echo $arsip->nomor_arsip; ?></td>
                        </tr>
                        <tr>
                            <th>Judul</th>
                            <td><?php echo $arsip->judul; ?></td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>
                                <?php 
                                $kategori = $this->m_model->get_where(array('id' => $arsip->kategori_id), 'tb_kategori_arsip')->row();
                                echo $kategori ? $kategori->nama : '-';
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>File</th>
                            <td><?php echo $arsip->nama_file; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <hr>
            <h4>Riwayat Akses</h4>
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped" id="example1">
                    <thead>
                        <tr>
                            <th width="5px">No</th>
                            <th>User</th>
                            <th>Aksi</th>
                            <th>Keterangan</th>
                            <th>IP Address</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
                            $no = 1;
                            if(!empty($riwayat)) {
                                foreach ($riwayat as $rwt) {
                                    $user = $this->m_model->get_where(array('id' => $rwt->user_id), 'tb_user')->row();
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $user ? $user->nama : '-'; ?></td>
                            <td>
                                <?php
                                $badge_class = 'label-default';
                                if($rwt->aksi == 'Upload') $badge_class = 'label-success';
                                elseif($rwt->aksi == 'Download') $badge_class = 'label-primary';
                                elseif($rwt->aksi == 'View') $badge_class = 'label-info';
                                elseif($rwt->aksi == 'Update') $badge_class = 'label-warning';
                                elseif($rwt->aksi == 'Delete') $badge_class = 'label-danger';
                                echo '<span class="label '.$badge_class.'">'.$rwt->aksi.'</span>';
                                ?>
                            </td>
                            <td><?php echo $rwt->keterangan ? $rwt->keterangan : '-'; ?></td>
                            <td><?php echo $rwt->ip_address ? $rwt->ip_address : '-'; ?></td>
                            <td><?php echo date('d-M-Y H:i:s', strtotime($rwt->createDate)); ?></td>
                        </tr>
                        <?php 
                                }
                            } else {
                        ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada riwayat akses</td>
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

