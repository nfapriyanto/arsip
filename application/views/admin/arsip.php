  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Data Arsip
        <small>Data Arsip Digital</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Data Arsip</li>
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

        <?php if(isset($kategori) && !isset($arsip)): ?>
            <!-- TAMPILAN KATEGORI -->
            <!-- Tombol Tambah Data -->
            <div class="btn btn-danger" data-toggle="modal" data-target="#tambahData">
                <div class="fa fa-plus"></div> Tambah Kategori
            </div>

            <!-- Tabel Kategori -->
            <div class="box box-danger" style="margin-top: 15px">
                <div class="box-header">
                    <h3 class="box-title">Daftar Kategori Arsip</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped" id="example1">
                            <thead>
                                <tr>
                                    <th width="5px">No</th>
                                    <th>Nama Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Total Arsip</th>
                                    <th>Sub-Kategori</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1;
                                    foreach ($kategori as $kat) {
                                ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><strong><?php echo $kat->nama; ?></strong></td>
                                        <td><?php echo $kat->deskripsi ? $kat->deskripsi : '-'; ?></td>
                                        <td><span class="badge bg-blue"><?php echo $kat->total_arsip; ?></span></td>
                                        <td><span class="badge bg-green"><?php echo isset($kat->total_sub) ? $kat->total_sub : 0; ?></span></td>
                                        <td>
                                            <!-- Tombol Edit Kategori -->
                                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editKategori<?php echo $kat->id; ?>">
                                                <div class="fa fa-edit"></div> Edit
                                            </button>
                                            <!-- Tombol Lihat Daftar Arsip -->
                                            <a href="<?php echo base_url('admin/arsip/kategori/').$kat->id; ?>" class="btn btn-info btn-sm">
                                                <div class="fa fa-eye"></div> Lihat Arsip
                                            </a>
                                            <!-- Tombol Delete Kategori -->
                                            <a href="<?php echo base_url('admin/arsip/delete_kategori/').$kat->id; ?>" class="btn btn-danger btn-sm tombol-yakin" data-isiData="Ingin menghapus kategori ini? Semua arsip dan sub-kategori di dalamnya juga akan terpengaruh!">
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

        <?php elseif(isset($arsip) && isset($kategori_id)): ?>
            <!-- TAMPILAN DAFTAR ARSIP PER KATEGORI -->
            <!-- Tombol Kembali -->
            <a href="<?php echo base_url('admin/arsip') ?>" class="btn btn-default">
                <div class="fa fa-arrow-left"></div> Kembali ke Kategori
            </a>

            <!-- Tombol Tambah Kategori -->
            <div class="btn btn-success" data-toggle="modal" data-target="#tambahKategori">
                <div class="fa fa-folder"></div> Tambah Kategori
            </div>

            <!-- Tombol Tambah Arsip -->
            <div class="btn btn-danger" data-toggle="modal" data-target="#tambahData">
                <div class="fa fa-plus"></div> Tambah Arsip
            </div>

            <!-- Tabel Daftar Sub-Kategori -->
            <?php if(isset($sub_kategori) && !empty($sub_kategori)): ?>
            <div class="box box-info" style="margin-top: 15px">
                <div class="box-header">
                    <h3 class="box-title">Daftar Sub-Kategori Arsip</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped" id="example2">
                            <thead>
                                <tr>
                                    <th width="5px">No</th>
                                    <th>Nama Sub-Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Total Arsip</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no_sub = 1;
                                    foreach ($sub_kategori as $sub) {
                                ?>
                                    <tr>
                                        <td><?php echo $no_sub++; ?></td>
                                        <td><strong><?php echo $sub->nama; ?></strong></td>
                                        <td><?php echo $sub->deskripsi ? $sub->deskripsi : '-'; ?></td>
                                        <td><span class="badge bg-blue"><?php echo $sub->total_arsip; ?></span></td>
                                        <td>
                                            <!-- Tombol Lihat Daftar Arsip -->
                                            <a href="<?php echo base_url('admin/arsip/kategori/').$sub->id; ?>" class="btn btn-info btn-sm">
                                                <div class="fa fa-eye"></div> Lihat Arsip
                                            </a>
                                            <!-- Tombol Edit Sub-Kategori -->
                                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editSubKategori<?php echo $sub->id; ?>">
                                                <div class="fa fa-edit"></div> Edit
                                            </button>
                                            <!-- Tombol Delete Sub-Kategori -->
                                            <a href="<?php echo base_url('admin/arsip/delete_kategori/').$sub->id; ?>" class="btn btn-danger btn-sm tombol-yakin" data-isiData="Ingin menghapus sub-kategori ini? Semua arsip di dalamnya juga akan terpengaruh!">
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
            <?php endif; ?>

            <!-- Tabel Daftar Arsip -->
            <div class="box box-danger" style="margin-top: 15px">
                <div class="box-header">
                    <h3 class="box-title">Daftar Arsip: <?php echo isset($kategori_nama) ? $kategori_nama : ''; ?></h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped" id="example1">
                            <thead>
                                <tr>
                                    <th width="5px">No</th>
                                    <th>Nomor Arsip</th>
                                    <th>Judul</th>
                                    <th>Tahun Dokumen</th>
                                    <th>File</th>
                                    <th>Ukuran</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1;
                                    foreach ($arsip as $ars) {
                                ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><strong><?php echo $ars->nomor_arsip; ?></strong></td>
                                        <td><?php echo $ars->judul; ?></td>
                                        <td><?php echo $ars->tahun_dokumen ? $ars->tahun_dokumen : '-'; ?></td>
                                        <td>
                                            <i class="fa fa-file"></i> <?php echo $ars->nama_file; ?>
                                        </td>
                                        <td><?php echo $this->m_model->formatBytes($ars->ukuran_file); ?></td>
                                        <td>
                                            <?php if($ars->status == 'Aktif'): ?>
                                                <span class="label label-success">Aktif</span>
                                            <?php elseif($ars->status == 'Tidak Aktif'): ?>
                                                <span class="label label-warning">Tidak Aktif</span>
                                            <?php else: ?>
                                                <span class="label label-default">Arsip</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <!-- Tombol View -->
                                            <a href="<?php echo base_url('admin/arsip/view/').$ars->id; ?>" target="_blank" class="btn btn-primary btn-sm">
                                                <div class="fa fa-eye"></div> View
                                            </a>
                                            <!-- Tombol Download -->
                                            <a href="<?php echo base_url('admin/arsip/download/').$ars->id; ?>" class="btn btn-success btn-sm">
                                                <div class="fa fa-download"></div> Download
                                            </a>
                                            <!-- Tombol History -->
                                            <a href="<?php echo base_url('admin/arsip/riwayat/').$ars->id; ?>" class="btn btn-info btn-sm">
                                                <div class="fa fa-history"></div> History
                                            </a>
                                            <!-- Tombol Edit -->
                                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editData<?php echo $ars->id; ?>">
                                                <div class="fa fa-edit"></div> Edit
                                            </button>
                                            <!-- Tombol Delete -->
                                            <a href="<?php echo base_url('admin/arsip/delete/').$ars->id; ?>" class="btn btn-danger btn-sm tombol-yakin" data-isiData="Ingin menghapus arsip ini!">
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
        <?php endif; ?>
      
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Modal Tambah Kategori (untuk halaman kategori) -->
  <?php if(isset($kategori) && !isset($arsip)): ?>
  <div class="modal fade" id="tambahData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><div class="fa fa-plus"></div> Tambah Kategori</h4>
        </div>
        <form action="<?php echo base_url('admin/arsip/insert_kategori') ?>" method="POST">
          <div class="modal-body">
            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text" class="form-control" name="nama" placeholder="Nama Kategori" required>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea class="form-control" name="deskripsi" placeholder="Deskripsi Kategori" rows="3"></textarea>
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
  <?php endif; ?>

  <!-- Modal Tambah Kategori (untuk halaman daftar arsip - Sub-Kategori) -->
  <?php if(isset($arsip) && isset($kategori_id)): ?>
  <div class="modal fade" id="tambahKategori" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><div class="fa fa-folder"></div> Tambah Sub-Kategori</h4>
        </div>
        <form action="<?php echo base_url('admin/arsip/insert_kategori') ?>" method="POST">
          <div class="modal-body">
            <input type="hidden" name="parent_id" value="<?php echo $kategori_id; ?>">
            <div class="alert alert-info">
              <i class="fa fa-info-circle"></i> Sub-kategori akan ditambahkan di bawah kategori: <strong><?php echo $kategori_nama; ?></strong>
            </div>
            <div class="form-group">
                <label>Nama Sub-Kategori</label>
                <input type="text" class="form-control" name="nama" placeholder="Nama Sub-Kategori" required>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea class="form-control" name="deskripsi" placeholder="Deskripsi Sub-Kategori" rows="3"></textarea>
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
  <?php endif; ?>

  <!-- Modal Tambah Arsip (untuk halaman daftar arsip) -->
  <?php if(isset($arsip) && isset($kategori_id)): ?>
  <div class="modal fade" id="tambahData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><div class="fa fa-plus"></div> Tambah Arsip</h4>
        </div>
        <form action="<?php echo base_url('admin/arsip/insert') ?>" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="form-group">
                <label>Kategori <span class="text-danger">*</span></label>
                <select class="form-control" name="kategori_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php if(isset($list_kategori)): ?>
                        <?php 
                        // Tampilkan kategori parent dan sub-kategori dengan indentasi
                        foreach($list_kategori as $kat): 
                            $prefix = '';
                            if($kat->parent_id) {
                                // Cari nama parent untuk indentasi
                                $parent = $this->m_model->get_where(array('id' => $kat->parent_id), 'tb_kategori_arsip')->row();
                                $prefix = ($parent ? $parent->nama . ' > ' : '');
                            }
                        ?>
                            <option value="<?php echo $kat->id; ?>" <?php echo (isset($kategori_id) && $kategori_id == $kat->id) ? 'selected' : ''; ?>>
                                <?php echo $prefix . $kat->nama; ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Nomor Arsip</label>
                <input type="text" class="form-control" name="nomor_arsip" placeholder="Kosongkan untuk generate otomatis">
                <small class="text-muted">Jika dikosongkan, nomor arsip akan digenerate otomatis</small>
            </div>
            <div class="form-group">
                <label>Judul <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="judul" placeholder="Judul Arsip" required>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea class="form-control" name="deskripsi" placeholder="Deskripsi Arsip" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Tahun Dokumen</label>
                <input type="number" class="form-control" name="tahun_dokumen" placeholder="Contoh: 2024" min="1900" max="<?php echo date('Y') + 1; ?>" value="<?php echo date('Y'); ?>">
            </div>
            <div class="form-group">
                <label>Pembuat</label>
                <input type="text" class="form-control" name="pembuat" placeholder="Nama Pembuat Dokumen">
            </div>
            <div class="form-group">
                <label>File Arsip <span class="text-danger">*</span></label>
                <input type="file" class="form-control" name="file_arsip" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.zip,.rar" required>
                <small class="text-muted">Format: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, GIF, ZIP, RAR (Max: 10MB)</small>
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
  <?php endif; ?>

  <!-- Modal Edit Kategori (untuk halaman kategori) -->
  <?php if(isset($kategori) && !isset($arsip)): ?>
  <?php foreach ($kategori as $kat) { ?>
    <div class="modal fade" id="editKategori<?php echo $kat->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><div class="fa fa-edit"></div> Edit Kategori</h4>
                </div>
                <form action="<?php echo base_url('admin/arsip/update_kategori') ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input type="hidden" name="id" value="<?php echo $kat->id; ?>">
                        <input type="text" class="form-control" name="nama" placeholder="Nama Kategori" value="<?php echo $kat->nama; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" placeholder="Deskripsi Kategori" rows="3"><?php echo $kat->deskripsi; ?></textarea>
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
  <?php endif; ?>

  <!-- Modal Edit Sub-Kategori (untuk halaman daftar arsip) -->
  <?php if(isset($sub_kategori) && isset($kategori_id) && !empty($sub_kategori)): ?>
  <?php foreach ($sub_kategori as $sub) { ?>
    <div class="modal fade" id="editSubKategori<?php echo $sub->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><div class="fa fa-edit"></div> Edit Sub-Kategori</h4>
                </div>
                <form action="<?php echo base_url('admin/arsip/update_kategori') ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Sub-Kategori</label>
                        <input type="hidden" name="id" value="<?php echo $sub->id; ?>">
                        <input type="text" class="form-control" name="nama" placeholder="Nama Sub-Kategori" value="<?php echo $sub->nama; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" placeholder="Deskripsi Sub-Kategori" rows="3"><?php echo $sub->deskripsi; ?></textarea>
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
  <?php endif; ?>

  <!-- Modal Edit Data -->
  <?php if(isset($arsip)): ?>
  <?php foreach ($arsip as $ars) { ?>
    <div class="modal fade" id="editData<?php echo $ars->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><div class="fa fa-edit"></div> Edit Arsip</h4>
                </div>
                <form action="<?php echo base_url('admin/arsip/update') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kategori <span class="text-danger">*</span></label>
                        <input type="hidden" name="id" value="<?php echo $ars->id; ?>">
                        <select class="form-control" name="kategori_id" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php if(isset($list_kategori)): ?>
                                <?php 
                                // Tampilkan kategori parent dan sub-kategori dengan indentasi
                                foreach($list_kategori as $kat): 
                                    $prefix = '';
                                    if($kat->parent_id) {
                                        // Cari nama parent untuk indentasi
                                        $parent = $this->m_model->get_where(array('id' => $kat->parent_id), 'tb_kategori_arsip')->row();
                                        $prefix = ($parent ? $parent->nama . ' > ' : '');
                                    }
                                ?>
                                    <option value="<?php echo $kat->id; ?>" <?php echo ($ars->kategori_id == $kat->id) ? 'selected' : ''; ?>>
                                        <?php echo $prefix . $kat->nama; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nomor Arsip</label>
                        <input type="text" class="form-control" name="nomor_arsip" value="<?php echo $ars->nomor_arsip; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Judul <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="judul" value="<?php echo $ars->judul; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3"><?php echo $ars->deskripsi; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Tahun Dokumen</label>
                        <input type="number" class="form-control" name="tahun_dokumen" placeholder="Contoh: 2024" min="1900" max="<?php echo date('Y') + 1; ?>" value="<?php echo $ars->tahun_dokumen ? $ars->tahun_dokumen : date('Y'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Pembuat</label>
                        <input type="text" class="form-control" name="pembuat" value="<?php echo $ars->pembuat; ?>">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="Aktif" <?php echo ($ars->status == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                            <option value="Tidak Aktif" <?php echo ($ars->status == 'Tidak Aktif') ? 'selected' : ''; ?>>Tidak Aktif</option>
                            <option value="Arsip" <?php echo ($ars->status == 'Arsip') ? 'selected' : ''; ?>>Arsip</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>File Arsip (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="file" class="form-control" name="file_arsip" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.zip,.rar">
                        <small class="text-muted">File saat ini: <?php echo $ars->nama_file; ?> (<?php echo $this->m_model->formatBytes($ars->ukuran_file); ?>)</small>
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
  <?php endif; ?>

  <!-- Modal Edit Sub-Kategori (untuk halaman daftar arsip) -->
  <?php if(isset($sub_kategori) && isset($kategori_id) && !empty($sub_kategori)): ?>
  <?php foreach ($sub_kategori as $sub) { ?>
    <div class="modal fade" id="editSubKategori<?php echo $sub->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><div class="fa fa-edit"></div> Edit Sub-Kategori</h4>
                </div>
                <form action="<?php echo base_url('admin/arsip/update_kategori') ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Sub-Kategori</label>
                        <input type="hidden" name="id" value="<?php echo $sub->id; ?>">
                        <input type="text" class="form-control" name="nama" placeholder="Nama Sub-Kategori" value="<?php echo $sub->nama; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" placeholder="Deskripsi Sub-Kategori" rows="3"><?php echo $sub->deskripsi; ?></textarea>
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
  <?php endif; ?>


