  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Data Kode Arsip
        <small>Manajemen Kode Arsip</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Data Kode Arsip</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Alert -->
        <?php 
        $flash_pesan = $this->session->flashdata('pesan');
        ?>
        <?php if(!empty($flash_pesan)): ?>
        <div class="flash-data" data-flashdata="<?php echo htmlspecialchars($flash_pesan, ENT_QUOTES, 'UTF-8') ?>"></div>
        <?php endif; ?>

        <!-- Tombol Tambah Data -->
        <div class="btn btn-danger" data-toggle="modal" data-target="#tambahData">
            <div class="fa fa-plus"></div> Tambah Kode
        </div>

        <!-- Tabel Kode -->
        <div class="box box-danger" style="margin-top: 15px">
            <div class="box-header">
                <h3 class="box-title">Daftar Kode Arsip</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="example1">
                        <thead>
                            <tr>
                                <th width="5px">No</th>
                                <th>Kode</th>
                                <th>Nama/Deskripsi</th>
                                <th>Jumlah Arsip</th>
                                <th>Tanggal Dibuat</th>
                                <th>Tanggal Diupdate</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                                foreach ($kode as $kod) {
                                    // Hitung jumlah arsip yang menggunakan kode ini
                                    $this->db->where('kode_id', $kod->id);
                                    $jumlah_arsip = $this->db->get('tb_arsip')->num_rows();
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><strong><?php echo $kod->kode; ?></strong></td>
                                    <td><?php echo $kod->nama; ?></td>
                                    <td>
                                        <span class="badge bg-blue"><?php echo $jumlah_arsip; ?></span>
                                    </td>
                                    <td><?php echo $kod->createDate ? date('d-m-Y H:i:s', strtotime($kod->createDate)) : '-'; ?></td>
                                    <td><?php echo $kod->updateDate ? date('d-m-Y H:i:s', strtotime($kod->updateDate)) : '-'; ?></td>
                                    <td>
                                        <!-- Tombol Edit -->
                                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editData<?php echo $kod->id; ?>">
                                            <div class="fa fa-edit"></div> Edit
                                        </button>
                                        <!-- Tombol Delete -->
                                        <a href="<?php echo base_url('admin/kode/delete/').$kod->id; ?>" class="btn btn-danger btn-sm tombol-yakin" data-isiData="Ingin menghapus kode ini?">
                                            <div class="fa fa-trash"></div> Delete
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
          <h4 class="modal-title" id="myModalLabel"><div class="fa fa-plus"></div> Tambah Kode Arsip</h4>
        </div>
        <form action="<?php echo base_url('admin/kode/insert') ?>" method="POST">
          <div class="modal-body">
            <div class="form-group">
                <label>Kode <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="kode" placeholder="Contoh: HK01, HK0101, dll" required>
                <small class="text-muted">Kode harus unik</small>
            </div>
            <div class="form-group">
                <label>Nama/Deskripsi <span class="text-danger">*</span></label>
                <textarea class="form-control" name="nama" placeholder="Contoh: HK01 – Produk Hukum" rows="3" required></textarea>
                <small class="text-muted">Deskripsi lengkap untuk kode ini</small>
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

  <!-- Modal Edit Data -->
  <?php foreach ($kode as $kod) { ?>
    <div class="modal fade" id="editData<?php echo $kod->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><div class="fa fa-edit"></div> Edit Kode Arsip</h4>
                </div>
                <form action="<?php echo base_url('admin/kode/update') ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode <span class="text-danger">*</span></label>
                        <input type="hidden" name="id" value="<?php echo $kod->id; ?>">
                        <input type="text" class="form-control" name="kode" placeholder="Contoh: HK01, HK0101, dll" value="<?php echo $kod->kode; ?>" required>
                        <small class="text-muted">Kode harus unik</small>
                    </div>
                    <div class="form-group">
                        <label>Nama/Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="nama" placeholder="Contoh: HK01 – Produk Hukum" rows="3" required><?php echo $kod->nama; ?></textarea>
                        <small class="text-muted">Deskripsi lengkap untuk kode ini</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-danger"><div class="fa fa-trash"></div> Reset</button>
                    <button type="submit" class="btn btn-primary"><div class="fa fa-save"></div> Update</button>
                </div>
                </form>
            </div>
        </div>
    </div>
  <?php } ?>

