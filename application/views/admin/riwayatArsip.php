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
                NO BERKAS : <?php echo isset($arsip->no_berkas) ? $arsip->no_berkas : 'ID-' . $arsip->id; ?>
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
                            <th width="200">NO BERKAS</th>
                            <td><?php echo isset($arsip->no_berkas) ? $arsip->no_berkas : '-'; ?></td>
                        </tr>
                        <tr>
                            <th>NO URUT</th>
                            <td><?php echo isset($arsip->no_urut) ? $arsip->no_urut : '-'; ?></td>
                        </tr>
                        <tr>
                            <th>KODE</th>
                            <td><?php echo isset($arsip->kode) ? $arsip->kode : '-'; ?></td>
                        </tr>
                        <tr>
                            <th>INDEKS/PEKERJAAN</th>
                            <td><?php echo isset($arsip->indeks_pekerjaan) ? $arsip->indeks_pekerjaan : '-'; ?></td>
                        </tr>
                        <tr>
                            <th>URAIAN MASALAH/KEGIATAN</th>
                            <td><?php echo isset($arsip->uraian_masalah_kegiatan) ? $arsip->uraian_masalah_kegiatan : '-'; ?></td>
                        </tr>
                        <tr>
                            <th>TAHUN</th>
                            <td><?php echo isset($arsip->tahun) ? $arsip->tahun : '-'; ?></td>
                        </tr>
                        <tr>
                            <th>JUMLAH BERKAS</th>
                            <td><?php echo isset($arsip->jumlah_berkas) ? $arsip->jumlah_berkas : 1; ?></td>
                        </tr>
                        <tr>
                            <th>ASLI/KOPI</th>
                            <td>
                                <?php if(isset($arsip->asli_kopi) && $arsip->asli_kopi): ?>
                                    <span class="label label-info"><?php echo $arsip->asli_kopi; ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>BOX</th>
                            <td>
                                <?php if(isset($arsip->box) && $arsip->box): ?>
                                    <span class="badge bg-green"><?php echo $arsip->box; ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>KLASIFIKASI KEAMANAN</th>
                            <td><?php echo isset($arsip->klasifikasi_keamanan) ? $arsip->klasifikasi_keamanan : '-'; ?></td>
                        </tr>
                        <tr>
                            <th>NAMA PENGISI</th>
                            <td><?php echo isset($arsip->nama_pengisi) && $arsip->nama_pengisi ? $arsip->nama_pengisi : '-'; ?></td>
                        </tr>
                        <tr>
                            <th>LINK DRIVE</th>
                            <td>
                                <?php if(isset($arsip->link_drive) && $arsip->link_drive): ?>
                                    <a href="<?php echo $arsip->link_drive; ?>" target="_blank"><?php echo $arsip->link_drive; ?></a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
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
                            <th>File/Link</th>
                            <td>
                                <?php if(!empty($arsip->nama_file) && !empty($arsip->path_file)): ?>
                                    <i class="fa fa-file"></i> <?php echo $arsip->nama_file; ?><br>
                                    <small><?php echo $this->m_model->formatBytes($arsip->ukuran_file); ?></small>
                                <?php elseif(!empty($arsip->link_drive)): ?>
                                    <i class="fa fa-cloud"></i> <a href="<?php echo $arsip->link_drive; ?>" target="_blank"><?php echo $arsip->link_drive; ?></a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
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




