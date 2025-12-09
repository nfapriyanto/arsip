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
      <!-- Alert -->
      <?php 
      $flash_pesan = $this->session->flashdata('pesan');
      // Flashdata otomatis dihapus setelah dibaca dengan flashdata()
      ?>
      <?php if(!empty($flash_pesan)): ?>
      <div class="flash-data" data-flashdata="<?php echo htmlspecialchars($flash_pesan, ENT_QUOTES, 'UTF-8') ?>"></div>
      <?php endif; ?>

      <!-- Tabel Data -->
      <div class="box box-warning" style="margin-top: 15px">
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