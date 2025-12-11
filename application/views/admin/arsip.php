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
            <?php 
            // Tentukan URL kembali berdasarkan parent_id
            if(isset($kategori_parent_id) && !empty($kategori_parent_id)) {
                // Jika ada parent_id, kembali ke halaman kategori parent
                $back_url = base_url('admin/arsip/kategori/' . $kategori_parent_id);
                $back_text = 'Kembali ke Kategori Parent';
            } else {
                // Jika tidak ada parent_id, kembali ke halaman utama kategori
                $back_url = base_url('admin/arsip');
                $back_text = 'Kembali ke Kategori';
            }
            ?>
            <a href="<?php echo $back_url; ?>" class="btn btn-default">
                <div class="fa fa-arrow-left"></div> <?php echo $back_text; ?>
            </a>

            <!-- Tombol Tambah Kategori -->
            <div class="btn btn-success" data-toggle="modal" data-target="#tambahKategori">
                <div class="fa fa-folder"></div> Tambah Kategori
            </div>

            <!-- Tombol Tambah Arsip -->
            <div class="btn btn-danger" data-toggle="modal" data-target="#tambahData">
                <div class="fa fa-plus"></div> Tambah Arsip
            </div>

            <!-- Tombol Import Excel -->
            <div class="btn btn-info" data-toggle="modal" data-target="#importExcel">
                <div class="fa fa-upload"></div> Import Excel
            </div>

            <!-- Tombol Export Excel -->
            <a href="<?php echo base_url('admin/arsip/export_excel/' . $kategori_id); ?>" class="btn btn-success">
                <div class="fa fa-download"></div> Export Excel
            </a>

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
                                    <th>NO BERKAS</th>
                                    <th>NO URUT</th>
                                    <th>KODE</th>
                                    <th>INDEKS/PEKERJAAN</th>
                                    <th>URAIAN MASALAH/KEGIATAN</th>
                                    <th>TAHUN</th>
                                    <th>JUMLAH BERKAS</th>
                                    <th>ASLI/KOPI</th>
                                    <th>BOX</th>
                                    <th>KLASIFIKASI KEAMANAN</th>
                                    <th>NAMA PIC</th>
                                    <th>File/Link</th>
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
                                        <td><strong><?php echo $ars->no_berkas ? $ars->no_berkas : '-'; ?></strong></td>
                                        <td><?php echo $ars->no_urut ? $ars->no_urut : '-'; ?></td>
                                        <td><?php echo $ars->kode ? $ars->kode : '-'; ?></td>
                                        <td><?php echo $ars->indeks_pekerjaan ? $ars->indeks_pekerjaan : '-'; ?></td>
                                        <td><?php echo $ars->uraian_masalah_kegiatan ? $ars->uraian_masalah_kegiatan : '-'; ?></td>
                                        <td><?php echo $ars->tahun ? $ars->tahun : '-'; ?></td>
                                        <td><?php echo $ars->jumlah_berkas ? $ars->jumlah_berkas : 1; ?></td>
                                        <td>
                                            <?php if(isset($ars->asli_kopi) && $ars->asli_kopi): ?>
                                                <span class="label label-info"><?php echo $ars->asli_kopi; ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if(isset($ars->box) && $ars->box): ?>
                                                <span class="badge bg-green"><?php echo $ars->box; ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $ars->klasifikasi_keamanan ? $ars->klasifikasi_keamanan : '-'; ?></td>
                                        <td><?php echo isset($ars->nama_pengisi) && $ars->nama_pengisi ? $ars->nama_pengisi : '-'; ?></td>
                                        <td>
                                            <?php if(!empty($ars->nama_file) && !empty($ars->path_file)): ?>
                                                <i class="fa fa-file"></i> <?php echo $ars->nama_file; ?><br>
                                                <small><?php echo $this->m_model->formatBytes($ars->ukuran_file); ?></small>
                                            <?php elseif(!empty($ars->link_drive)): ?>
                                                <i class="fa fa-cloud"></i> <a href="<?php echo $ars->link_drive; ?>" target="_blank">Link Drive</a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
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
        <form action="<?php echo base_url('admin/arsip/insert') ?>" method="POST" enctype="multipart/form-data" id="formTambahArsip" onsubmit="return validateFileOrLink()">
          <div class="modal-body">
            <!-- Kategori tidak bisa dipilih, langsung dari kategori yang sedang dibuka -->
            <input type="hidden" name="kategori_id" value="<?php echo isset($kategori_id) ? $kategori_id : ''; ?>">
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" class="form-control" value="<?php echo isset($kategori_nama) ? $kategori_nama : ''; ?>" readonly>
                <small class="text-muted">Arsip akan dibuat di kategori ini</small>
            </div>
            <div class="form-group">
                <label>NO BERKAS</label>
                <input type="text" class="form-control" name="no_berkas" placeholder="Kosongkan untuk generate otomatis">
                <small class="text-muted">Jika dikosongkan, nomor berkas akan digenerate otomatis</small>
            </div>
            <div class="form-group">
                <label>NO URUT</label>
                <input type="number" class="form-control" name="no_urut" placeholder="Nomor Urut" min="1">
            </div>
            <div class="form-group">
                <label>KODE</label>
                <input type="text" class="form-control" name="kode" placeholder="Kode Arsip">
            </div>
            <div class="form-group">
                <label>INDEKS/PEKERJAAN</label>
                <input type="text" class="form-control" name="indeks_pekerjaan" placeholder="Indeks/Pekerjaan">
            </div>
            <div class="form-group">
                <label>URAIAN MASALAH/KEGIATAN</label>
                <textarea class="form-control" name="uraian_masalah_kegiatan" placeholder="Uraian Masalah/Kegiatan" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>TAHUN</label>
                <input type="number" class="form-control" name="tahun" placeholder="Contoh: 2024" min="1900" max="<?php echo date('Y') + 1; ?>" value="<?php echo date('Y'); ?>">
            </div>
            <div class="form-group">
                <label>JUMLAH BERKAS</label>
                <input type="number" class="form-control" name="jumlah_berkas" placeholder="Jumlah Berkas" min="1" value="1">
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>ASLI/KOPI</label>
                        <select class="form-control" name="asli_kopi">
                            <option value="">-- Pilih --</option>
                            <option value="Asli">Asli</option>
                            <option value="Kopi">Kopi</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>BOX</label>
                        <input type="text" class="form-control" name="box" placeholder="Contoh: 1, 2, A-1">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>KLASIFIKASI KEAMANAN DAN AKSES ARSIP DINAMIS</label>
                <select class="form-control" name="klasifikasi_keamanan">
                    <option value="">-- Pilih Klasifikasi --</option>
                    <option value="Umum">Umum</option>
                    <option value="Terbatas">Terbatas</option>
                    <option value="Rahasia">Rahasia</option>
                    <option value="Sangat Rahasia">Sangat Rahasia</option>
                </select>
            </div>
            <div class="form-group">
                <label>File Arsip atau Link Drive <span class="text-danger">*</span></label>
                <div class="radio">
                    <label>
                        <input type="radio" name="file_type" value="upload" checked onchange="toggleFileInput()">
                        Upload File
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="file_type" value="drive" onchange="toggleFileInput()">
                        Link Drive
                    </label>
                </div>
                <div id="file_upload_section">
                    <input type="file" class="form-control" id="file_arsip" name="file_arsip" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.zip,.rar" required>
                    <small class="text-muted">Format: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, GIF, ZIP, RAR (Max: 10MB)</small>
                </div>
                <div id="link_drive_section" style="display:none;">
                    <input type="url" class="form-control" id="link_drive" name="link_drive" placeholder="https://onedrive.live.com/...">
                    <small class="text-muted">Masukkan link Drive</small>
                </div>
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
                        <?php if(isset($kategori_id)): ?>
                        <input type="hidden" name="current_kategori_id" value="<?php echo $kategori_id; ?>">
                        <?php endif; ?>
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
                        <label>NO BERKAS</label>
                        <input type="text" class="form-control" name="no_berkas" value="<?php echo isset($ars->no_berkas) ? $ars->no_berkas : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>NO URUT</label>
                        <input type="number" class="form-control" name="no_urut" placeholder="Nomor Urut" min="1" value="<?php echo isset($ars->no_urut) ? $ars->no_urut : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>KODE</label>
                        <input type="text" class="form-control" name="kode" value="<?php echo isset($ars->kode) ? $ars->kode : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>INDEKS/PEKERJAAN</label>
                        <input type="text" class="form-control" name="indeks_pekerjaan" value="<?php echo isset($ars->indeks_pekerjaan) ? $ars->indeks_pekerjaan : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>URAIAN MASALAH/KEGIATAN</label>
                        <textarea class="form-control" name="uraian_masalah_kegiatan" rows="3"><?php echo isset($ars->uraian_masalah_kegiatan) ? $ars->uraian_masalah_kegiatan : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>TAHUN</label>
                        <input type="number" class="form-control" name="tahun" placeholder="Contoh: 2024" min="1900" max="<?php echo date('Y') + 1; ?>" value="<?php echo isset($ars->tahun) ? $ars->tahun : date('Y'); ?>">
                    </div>
                    <div class="form-group">
                        <label>JUMLAH BERKAS</label>
                        <input type="number" class="form-control" name="jumlah_berkas" placeholder="Jumlah Berkas" min="1" value="<?php echo isset($ars->jumlah_berkas) ? $ars->jumlah_berkas : 1; ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ASLI/KOPI</label>
                                <select class="form-control" name="asli_kopi">
                                    <option value="">-- Pilih --</option>
                                    <option value="Asli" <?php echo (isset($ars->asli_kopi) && $ars->asli_kopi == 'Asli') ? 'selected' : ''; ?>>Asli</option>
                                    <option value="Kopi" <?php echo (isset($ars->asli_kopi) && $ars->asli_kopi == 'Kopi') ? 'selected' : ''; ?>>Kopi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>BOX</label>
                                <input type="text" class="form-control" name="box" placeholder="Contoh: 1, 2, A-1" value="<?php echo isset($ars->box) ? $ars->box : ''; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>KLASIFIKASI KEAMANAN DAN AKSES ARSIP DINAMIS</label>
                        <select class="form-control" name="klasifikasi_keamanan">
                            <option value="">-- Pilih Klasifikasi --</option>
                            <option value="Umum" <?php echo (isset($ars->klasifikasi_keamanan) && $ars->klasifikasi_keamanan == 'Umum') ? 'selected' : ''; ?>>Umum</option>
                            <option value="Terbatas" <?php echo (isset($ars->klasifikasi_keamanan) && $ars->klasifikasi_keamanan == 'Terbatas') ? 'selected' : ''; ?>>Terbatas</option>
                            <option value="Rahasia" <?php echo (isset($ars->klasifikasi_keamanan) && $ars->klasifikasi_keamanan == 'Rahasia') ? 'selected' : ''; ?>>Rahasia</option>
                            <option value="Sangat Rahasia" <?php echo (isset($ars->klasifikasi_keamanan) && $ars->klasifikasi_keamanan == 'Sangat Rahasia') ? 'selected' : ''; ?>>Sangat Rahasia</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>File Arsip atau Link Drive</label>
                        <?php 
                        $has_file = !empty($ars->nama_file) && !empty($ars->path_file);
                        $has_link = !empty($ars->link_drive);
                        $current_type = $has_file ? 'upload' : ($has_link ? 'drive' : 'upload');
                        ?>
                        <div class="radio">
                            <label>
                                <input type="radio" name="file_type_edit<?php echo $ars->id; ?>" value="upload" <?php echo $current_type == 'upload' ? 'checked' : ''; ?> onchange="toggleFileInputEdit(<?php echo $ars->id; ?>)">
                                Upload File
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="file_type_edit<?php echo $ars->id; ?>" value="drive" <?php echo $current_type == 'drive' ? 'checked' : ''; ?> onchange="toggleFileInputEdit(<?php echo $ars->id; ?>)">
                                Link Drive
                            </label>
                        </div>
                        <div id="file_upload_section_edit<?php echo $ars->id; ?>" style="display:<?php echo $current_type == 'upload' ? 'block' : 'none'; ?>;">
                            <input type="file" class="form-control" id="file_arsip_edit<?php echo $ars->id; ?>" name="file_arsip" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.zip,.rar">
                            <?php if($has_file): ?>
                                <small class="text-muted">File saat ini: <?php echo $ars->nama_file; ?> (<?php echo $this->m_model->formatBytes($ars->ukuran_file); ?>)</small>
                            <?php else: ?>
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah</small>
                            <?php endif; ?>
                        </div>
                        <div id="link_drive_section_edit<?php echo $ars->id; ?>" style="display:<?php echo $current_type == 'drive' ? 'block' : 'none'; ?>;">
                            <input type="url" class="form-control" id="link_drive_edit<?php echo $ars->id; ?>" name="link_drive" placeholder="https://onedrive.live.com/..." value="<?php echo isset($ars->link_drive) ? $ars->link_drive : ''; ?>">
                            <small class="text-muted">Masukkan link Drive</small>
                        </div>
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
                        <?php if(isset($kategori_id)): ?>
                        <input type="hidden" name="current_kategori_id" value="<?php echo $kategori_id; ?>">
                        <?php endif; ?>
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

  <!-- Modal Import Excel -->
  <?php if(isset($arsip) && isset($kategori_id)): ?>
  <div class="modal fade" id="importExcel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><div class="fa fa-upload"></div> Import Data dari Excel</h4>
        </div>
        <form action="<?php echo base_url('admin/arsip/import') ?>" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
            <input type="hidden" name="kategori_id" value="<?php echo isset($kategori_id) ? $kategori_id : ''; ?>">
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" class="form-control" value="<?php echo isset($kategori_nama) ? $kategori_nama : ''; ?>" readonly>
                <small class="text-muted">Data akan diimport ke kategori ini (atau sesuai kolom Kategori di Excel)</small>
            </div>
            <div class="form-group">
                <label>File Excel (.xls atau .xlsx) <span class="text-danger">*</span></label>
                <input type="file" class="form-control" name="file_excel" accept=".xls,.xlsx" required>
                <small class="text-muted">Format: XLS atau XLSX (Max: 5MB)</small>
            </div>
            <div class="alert alert-info">
              <i class="fa fa-info-circle"></i> <strong>Petunjuk:</strong><br>
              - Download template Excel terlebih dahulu untuk melihat format yang benar<br>
              - Pastikan kolom sesuai dengan template<br>
              - Kategori di Excel (kolom A) opsional, jika kosong akan menggunakan kategori yang dipilih<br>
              - No Berkas kosong akan digenerate otomatis<br>
              - Asli/Kopi harus diisi dengan "Asli" atau "Kopi"<br>
              - Link Drive opsional, bisa dikosongkan jika tidak ada
            </div>
            <div class="alert alert-warning">
              <i class="fa fa-exclamation-triangle"></i> <strong>Perhatian:</strong> Pastikan file Excel sudah sesuai format sebelum diimport!
            </div>
          </div>
          <div class="modal-footer">
            <a href="<?php echo base_url('admin/arsip/download_template'); ?>" class="btn btn-success">
                <div class="fa fa-download"></div> Download Template
            </a>
            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary"><div class="fa fa-upload"></div> Import</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php endif; ?>



<script>
$(document).ready(function() {
    // Inisialisasi toggle saat halaman dimuat untuk form tambah
    if($('input[name="file_type"]:checked').length > 0) {
        toggleFileInput();
    }
    
    // Inisialisasi toggle untuk setiap form edit saat modal dibuka
    $('input[name^="file_type_edit"]').on('change', function() {
        var id = $(this).attr('name').replace('file_type_edit', '');
        toggleFileInputEdit(id);
    });
    
    // Inisialisasi saat modal edit dibuka
    $('[id^="editData"]').on('shown.bs.modal', function() {
        var modalId = $(this).attr('id');
        var id = modalId.replace('editData', '');
        if($('input[name="file_type_edit' + id + '"]:checked').length > 0) {
            toggleFileInputEdit(id);
        }
    });
});

function toggleFileInput() {
    var fileType = $('input[name="file_type"]:checked').val();
    var fileSection = $('#file_upload_section');
    var linkSection = $('#link_drive_section');
    var fileInput = $('#file_arsip');
    var linkInput = $('#link_drive');
    
    if(fileType === 'upload') {
        fileSection.show();
        linkSection.hide();
        if(fileInput.length) {
            fileInput.prop('required', true);
            fileInput.prop('disabled', false);
        }
        if(linkInput.length) {
            linkInput.prop('required', false);
            linkInput.val('');
            linkInput.prop('disabled', true);
        }
    } else {
        fileSection.hide();
        linkSection.show();
        if(fileInput.length) {
            fileInput.prop('required', false);
            fileInput.val('');
            fileInput.prop('disabled', true);
        }
        if(linkInput.length) {
            linkInput.prop('required', true);
            linkInput.prop('disabled', false);
        }
    }
}

function toggleFileInputEdit(id) {
    var fileType = $('input[name="file_type_edit' + id + '"]:checked').val();
    var fileSection = $('#file_upload_section_edit' + id);
    var linkSection = $('#link_drive_section_edit' + id);
    var fileInput = $('#file_arsip_edit' + id);
    var linkInput = $('#link_drive_edit' + id);
    
    if(fileType === 'upload') {
        fileSection.show();
        linkSection.hide();
        if(linkInput.length) {
            linkInput.val('');
            linkInput.prop('disabled', true);
        }
        if(fileInput.length) {
            fileInput.prop('disabled', false);
        }
    } else {
        fileSection.hide();
        linkSection.show();
        if(fileInput.length) {
            fileInput.val('');
            fileInput.prop('disabled', true);
        }
        if(linkInput.length) {
            linkInput.prop('disabled', false);
        }
    }
}

// Validasi form: harus ada file atau link drive
function validateFileOrLink() {
    var fileType = $('input[name="file_type"]:checked').val();
    var fileInput = $('#file_arsip');
    var linkInput = $('#link_drive');
    
    if(fileType === 'upload') {
        if(!fileInput[0].files || fileInput[0].files.length === 0) {
            alert('Harus mengupload file atau memilih Link Drive!');
            return false;
        }
    } else {
        if(!linkInput.val() || linkInput.val().trim() === '') {
            alert('Harus mengisi link Drive atau memilih Upload File!');
            return false;
        }
    }
    return true;
}
</script> 
