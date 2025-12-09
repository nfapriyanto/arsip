  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Stok Barang
        <small>Stok Barang</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Stok Barang</li>
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

        <!-- Tombol Tambah Data -->
        <div class="btn btn-danger" data-toggle="modal" data-target="#tambahData">
            <div class="fa fa-plus"></div> Tambah Data
        </div>

        <!-- Tabel Data -->
        <div class="box box-warning" style="margin-top: 15px">
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="example1">
                        <thead>
                            <tr>
                                <th width="5px">No</th>
                                <th>Nama Barang</th>
                                <th>Sisa Stok</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                                foreach ($barang as $brg) {
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $brg->kode; ?></td>
                                    <td><?php echo $brg->stok; ?></td>
                                    <td>
                                        <!-- Tombol Kelola -->
                                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#kelola<?= $brg->id ?>">
                                            <div class="fa fa-plus-square"></div> Kelola
                                        </button>
                                        <!-- Tombol History -->
                                        <a href="<?php echo base_url('user/barang/riwayat/').$brg->id ; ?>" class="btn btn-primary btn-sm">
                                            <div class="fa fa-history"></div> History
                                        </a>
                                    </td>
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

  <!-- Modal Tambah Data -->
  <div class="modal fade" id="tambahData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><div class="fa fa-plus"></div> Tambah Data</h4>
        </div>
        <form action="<?php echo base_url('user/barang/insert') ?>" method="POST">
          <div class="modal-body">
            <div class="form-group">
                <label>Nama Barang</label>
                <input type="datetime" class="form-control" name="kode" placeholder="Nama Barang" required>
            </div>
            <div class="form-group">
                <label>Sisa Stok</label>
                <input type="number" class="form-control" name="stok" placeholder="Sisa Stok" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="reset" class="btn btn-danger"><div class="fa fa-trash"></div> Reset</button>
            <button type="submit" class="btn btn-primary"><div class="fa fa-save"></div> Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Kelola Data -->
  <?php foreach ($barang as $brg) { ?>
    <div class="modal fade" id="kelola<?= $brg->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><div class="fa fa-plus-square"></div> Kelola Data</h4>
                </div>
                <form action="<?php echo base_url('user/barang/insert_kelola') ?>" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Barang</label>
                                <input type="hidden" name="id" value="<?php echo $brg->id; ?>">
                                <input type="text" class="form-control" name="kode" value="<?= $brg->kode ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sisa Stok</label>
                                <input type="text" class="form-control" name="stok" value="<?= $brg->stok ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Jenis</label>
                        <input type="hidden" name="id" value="<?php echo $brg->id; ?>">
                        <select class="form-control" name="jenis" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Masuk">Masuk</option>
                            <option value="Keluar">Keluar</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Label/Kode Identifikasi</label>
                        <input type="text" class="form-control" name="label" placeholder="Label/Kode Identifikasi (contoh: A, B, 001)" required>
                    </div>
                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="number" class="form-control" name="jumlah" placeholder="Jumlah" required>
                    </div>
                    <div class="form-group">
                        <label>Unit</label>
                        <input type="text" class="form-control" name="unit" placeholder="Unit" required>
                    </div>
                    <div class="form-group">
                        <label>No Telp</label>
                        <input type="text" class="form-control" name="noTlp" placeholder="No Telp" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-danger"><div class="fa fa-trash"></div> Reset</button>
                    <button type="submit" class="btn btn-primary"><div class="fa fa-save"></div> simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
  <?php } ?>