  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php if(isset($kategori_nama)): ?>
          <?php echo $kategori_nama; ?>
        <?php else: ?>
          Data Arsip
        <?php endif; ?>
        <small>Data Arsip Digital</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <?php if(isset($kategori_parent) && !empty($kategori_parent)): ?>
          <!-- Jika ada kategori parent, tampilkan: Home > [Nama Parent] > [Nama Kategori] -->
          <li><a href="<?php echo base_url('admin/arsip/kategori/' . $kategori_parent->id); ?>"><?php echo $kategori_parent->nama; ?></a></li>
          <li class="active"><?php echo isset($kategori_nama) ? $kategori_nama : 'Data Arsip'; ?></li>
        <?php elseif(isset($kategori_nama)): ?>
          <!-- Jika kategori parent, tampilkan: Home > [Nama Kategori] -->
          <li class="active"><?php echo $kategori_nama; ?></li>
        <?php else: ?>
          <!-- Jika halaman utama kategori, tampilkan: Home > Data Arsip -->
          <li class="active">Data Arsip</li>
        <?php endif; ?>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <?php if(isset($kategori) && !isset($arsip)): ?>
            <!-- TAMPILAN KATEGORI -->
            <!-- Tombol Tambah Data -->
            <div class="btn btn-danger" data-toggle="modal" data-target="#tambahKategoriUtama">
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

            <?php 
            // Hanya tampilkan tombol arsip jika bukan kategori parent (ada parent_id)
            if(isset($kategori_parent_id) && !empty($kategori_parent_id)): 
            ?>
            <!-- Tombol Tambah Arsip -->
            <div class="btn btn-danger" data-toggle="modal" data-target="#tambahData">
                <div class="fa fa-plus"></div> Tambah Arsip
            </div>
            
            <!-- Tombol Bulk Upload -->
            <div class="btn btn-success" data-toggle="modal" data-target="#bulkUploadModal">
                <div class="fa fa-upload"></div> Bulk Upload
            </div>

            <!-- Tombol Import Excel -->
            <div class="btn btn-info" data-toggle="modal" data-target="#importExcel">
                <div class="fa fa-upload"></div> Import Excel
            </div>

            <!-- Tombol Export Excel -->
            <a href="<?php echo base_url('admin/arsip/export_excel/' . $kategori_id); ?>" class="btn btn-success">
                <div class="fa fa-download"></div> Export Excel
            </a>
            <?php endif; ?>

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

            <!-- Tabel Daftar Arsip (hanya tampil jika bukan kategori parent) -->
            <?php if(isset($kategori_parent_id) && !empty($kategori_parent_id)): ?>
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
                                    <?php 
                                    // Cek kondisi: jika kategori parent = 'arsip aktif' dan parent_id = null, ubah header menjadi TAHUN
                                    $is_arsip_aktif_table = isset($kategori_parent_check) && 
                                                           $kategori_parent_check && 
                                                           strtolower(trim($kategori_parent_check->nama)) == 'arsip aktif' && 
                                                           empty($kategori_parent_check->parent_id);
                                    ?>
                                    <th><?php echo $is_arsip_aktif_table ? 'TAHUN' : 'TANGGAL'; ?></th>
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
                                        <td>
                                        <?php 
                                        if(isset($ars->kode_arsip) && !empty($ars->kode_arsip)) {
                                            echo $ars->kode_arsip;
                                            if(isset($ars->kode_nama) && !empty($ars->kode_nama)) {
                                                echo '<br><small class="text-muted">' . $ars->kode_nama . '</small>';
                                            }
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                        <td><?php echo $ars->indeks_pekerjaan ? $ars->indeks_pekerjaan : '-'; ?></td>
                                        <td><?php echo $ars->uraian_masalah_kegiatan ? $ars->uraian_masalah_kegiatan : '-'; ?></td>
                                        <td>
                                            <?php 
                                            if($ars->tahun) {
                                                // Jika kategori parent = 'arsip aktif' dan parent_id = null, tampilkan hanya tahun
                                                if($is_arsip_aktif_table) {
                                                    echo date('Y', strtotime($ars->tahun));
                                                } else {
                                                    echo date('d-m-Y', strtotime($ars->tahun));
                                                }
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>
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
        <?php endif; ?>
      
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Modal Tambah Kategori (untuk halaman kategori) -->
  <?php if(isset($kategori) && !isset($arsip)): ?>
  <div class="modal fade" id="tambahKategoriUtama" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                <select class="form-control" name="kode_id" id="kode_select">
                    <option value="">-- Pilih Kode --</option>
                    <?php if(isset($list_kode) && !empty($list_kode)): ?>
                        <?php foreach($list_kode as $kod): ?>
                            <option value="<?php echo $kod->id; ?>"><?php echo $kod->kode . ' – ' . $kod->nama; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <small class="text-muted">Jika kode tidak ada, <a href="<?php echo base_url('admin/kode'); ?>" target="_blank">tambahkan kode baru</a></small>
            </div>
            <div class="form-group">
                <label>INDEKS/PEKERJAAN</label>
                <input type="text" class="form-control" name="indeks_pekerjaan" placeholder="Indeks/Pekerjaan" value="Satker Balai Penilaian Kompetensi">
            </div>
            <div class="form-group">
                <label>URAIAN MASALAH/KEGIATAN</label>
                <textarea class="form-control" name="uraian_masalah_kegiatan" placeholder="Uraian Masalah/Kegiatan" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>TANGGAL</label>
                <?php 
                // Cek kondisi: jika kategori parent = 'arsip inaktif' dan parent_id = null, gunakan input year
                $is_arsip_inaktif = isset($kategori_parent_check) && 
                                     $kategori_parent_check && 
                                     strtolower(trim($kategori_parent_check->nama)) == 'arsip inaktif' && 
                                     empty($kategori_parent_check->parent_id);
                ?>
                <?php if($is_arsip_inaktif): ?>
                    <input type="number" class="form-control" name="tahun" placeholder="Contoh: 2024" min="1900" max="<?php echo date('Y') + 1; ?>" value="<?php echo date('Y'); ?>" id="tahun_year_input">
                    <small class="text-muted">Hanya isi tahun (bulan dan tanggal tidak perlu)</small>
                <?php else: ?>
                    <input type="date" class="form-control" name="tahun" value="<?php echo date('Y-m-d'); ?>">
                <?php endif; ?>
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
                <?php 
                // Cek kondisi: jika kategori parent = 'arsip aktif' dan parent_id = null, sembunyikan field BOX
                $is_arsip_aktif = isset($kategori_parent_check) && 
                                  $kategori_parent_check && 
                                  strtolower(trim($kategori_parent_check->nama)) == 'arsip aktif' && 
                                  empty($kategori_parent_check->parent_id);
                ?>
                <?php if(!$is_arsip_aktif): ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>BOX</label>
                        <input type="text" class="form-control" name="box" placeholder="Contoh: 1, 2, A-1">
                    </div>
                </div>
                <?php endif; ?>
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
                    <small class="text-muted">Format: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, GIF, ZIP, RAR</small>
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
  
  <!-- Modal Progress Upload Single Arsip -->
  <div class="modal fade" id="uploadProgressModal" tabindex="-1" role="dialog" aria-labelledby="uploadProgressModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="uploadProgressModalLabel">
            <i class="fa fa-cloud-upload"></i> Upload Progress
          </h4>
        </div>
        <div class="modal-body">
          <div class="text-center" style="margin-bottom: 15px;">
            <h4 id="single-upload-status">Mengupload arsip...</h4>
          </div>
          <div class="progress" style="height: 30px; margin-bottom: 10px;">
            <div id="single-upload-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated progress-bar-info" 
                 role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
              <span id="single-upload-progress-text" style="font-size: 16px; font-weight: bold;">0%</span>
            </div>
          </div>
          <div class="text-center">
            <p id="single-upload-file-info" class="text-muted">
              <i class="fa fa-file"></i> <span id="single-upload-filename">Mempersiapkan upload...</span>
            </p>
            <p id="single-upload-size-info" class="text-muted" style="margin-top: -10px;">
              <small>Ukuran: <span id="single-upload-filesize">-</span></small>
            </p>
          </div>
          <div id="single-upload-complete-info" style="display: none; margin-top: 15px;">
            <div class="alert alert-success text-center">
              <i class="fa fa-check-circle" style="font-size: 24px;"></i><br>
              <strong>Upload berhasil!</strong><br>
              <small>Halaman akan dimuat ulang...</small>
            </div>
          </div>
          <div id="single-upload-error-info" style="display: none; margin-top: 15px;">
            <div class="alert alert-danger">
              <i class="fa fa-exclamation-triangle"></i>
              <strong>Upload gagal!</strong><br>
              <span id="single-upload-error-message"></span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" id="btn-close-single-progress" style="display: none;">
            <i class="fa fa-times"></i> Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Modal Bulk Upload -->
  <div class="modal fade" id="bulkUploadModal" tabindex="-1" role="dialog" aria-labelledby="bulkUploadModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="bulkUploadModalLabel"><div class="fa fa-upload"></div> Bulk Upload Arsip</h4>
        </div>
        <form action="<?php echo base_url('admin/arsip/bulk_upload') ?>" method="POST" enctype="multipart/form-data" id="formBulkUpload">
          <div class="modal-body">
            <!-- Kategori tidak bisa dipilih, langsung dari kategori yang sedang dibuka -->
            <input type="hidden" name="kategori_id" value="<?php echo isset($kategori_id) ? $kategori_id : ''; ?>">
            
            <div class="alert alert-info">
              <i class="fa fa-info-circle"></i> <strong>Petunjuk:</strong><br>
              - Pilih multiple file sekaligus untuk diupload<br>
              - Semua file akan menggunakan data yang sama di bawah<br>
              - No Berkas akan digenerate otomatis untuk setiap file<br>
              - Format file: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, GIF, ZIP, RAR
            </div>
            
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" class="form-control" value="<?php echo isset($kategori_nama) ? $kategori_nama : ''; ?>" readonly>
                <small class="text-muted">Arsip akan dibuat di kategori ini</small>
            </div>
            
            <div class="form-group">
                <label>File Arsip (Multiple) <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="files_arsip" name="files_arsip[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.zip,.rar" multiple required>
                <small class="text-muted">Pilih multiple file dengan menahan Ctrl (Windows) atau Cmd (Mac) saat klik</small>
                <div id="file-list" style="margin-top: 10px;"></div>
            </div>
            
            <div class="form-group">
                <label>NO URUT (Opsional)</label>
                <input type="number" class="form-control" name="no_urut" placeholder="Nomor Urut" min="1">
                <small class="text-muted">Jika diisi, akan digunakan untuk semua file (akan increment otomatis)</small>
            </div>
            
            <div class="form-group">
                <label>KODE</label>
                <select class="form-control" name="kode_id" id="kode_select_bulk">
                    <option value="">-- Pilih Kode --</option>
                    <?php if(isset($list_kode) && !empty($list_kode)): ?>
                        <?php foreach($list_kode as $kod): ?>
                            <option value="<?php echo $kod->id; ?>"><?php echo $kod->kode . ' – ' . $kod->nama; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <small class="text-muted">Jika kode tidak ada, <a href="<?php echo base_url('admin/kode'); ?>" target="_blank">tambahkan kode baru</a></small>
            </div>
            
            <div class="form-group">
                <label>INDEKS/PEKERJAAN</label>
                <input type="text" class="form-control" name="indeks_pekerjaan" placeholder="Indeks/Pekerjaan" value="Satker Balai Penilaian Kompetensi">
            </div>
            
            <div class="form-group">
                <label>URAIAN MASALAH/KEGIATAN</label>
                <textarea class="form-control" name="uraian_masalah_kegiatan" placeholder="Uraian Masalah/Kegiatan" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label>TANGGAL</label>
                <?php 
                // Cek kondisi: jika kategori parent = 'arsip inaktif' dan parent_id = null, gunakan input year
                $is_arsip_inaktif_bulk = isset($kategori_parent_check) && 
                                         $kategori_parent_check && 
                                         strtolower(trim($kategori_parent_check->nama)) == 'arsip inaktif' && 
                                         empty($kategori_parent_check->parent_id);
                ?>
                <?php if($is_arsip_inaktif_bulk): ?>
                    <input type="number" class="form-control" name="tahun" placeholder="Contoh: 2024" min="1900" max="<?php echo date('Y') + 1; ?>" value="<?php echo date('Y'); ?>" id="tahun_year_input_bulk">
                    <small class="text-muted">Hanya isi tahun (bulan dan tanggal tidak perlu)</small>
                <?php else: ?>
                    <input type="date" class="form-control" name="tahun" value="<?php echo date('Y-m-d'); ?>">
                <?php endif; ?>
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
                <?php 
                // Cek kondisi: jika kategori parent = 'arsip aktif' dan parent_id = null, sembunyikan field BOX
                $is_arsip_aktif_bulk = isset($kategori_parent_check) && 
                                      $kategori_parent_check && 
                                      strtolower(trim($kategori_parent_check->nama)) == 'arsip aktif' && 
                                      empty($kategori_parent_check->parent_id);
                ?>
                <?php if(!$is_arsip_aktif_bulk): ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>BOX</label>
                        <input type="text" class="form-control" name="box" placeholder="Contoh: 1, 2, A-1">
                    </div>
                </div>
                <?php endif; ?>
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
            
            <!-- Progress Bar Container (Hidden by default) -->
            <div id="upload-progress-container" style="display: none; margin-top: 20px;">
                <div class="alert alert-info">
                    <strong><i class="fa fa-spinner fa-spin"></i> Sedang mengupload file...</strong>
                </div>
                <div class="progress" style="height: 30px;">
                    <div id="upload-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" 
                         role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        <span id="upload-progress-text">0%</span>
                    </div>
                </div>
                <div style="margin-top: 10px;">
                    <small id="upload-progress-detail" class="text-muted">
                        Memproses file: <span id="current-file-name">-</span>
                    </small>
                    <br>
                    <small class="text-muted">
                        Berhasil: <span id="success-count">0</span> | 
                        Gagal: <span id="error-count">0</span> | 
                        Total: <span id="total-count">0</span>
                    </small>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-cancel-upload"><div class="fa fa-times"></div> Batal</button>
            <button type="submit" class="btn btn-success" id="btn-bulk-upload"><div class="fa fa-upload"></div> Upload Semua</button>
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
                        <select class="form-control" name="kode_id" id="kode_select_edit<?php echo $ars->id; ?>">
                            <option value="">-- Pilih Kode --</option>
                            <?php if(isset($list_kode) && !empty($list_kode)): ?>
                                <?php foreach($list_kode as $kod): ?>
                                    <option value="<?php echo $kod->id; ?>" <?php echo (isset($ars->kode_id) && $ars->kode_id == $kod->id) ? 'selected' : ''; ?>>
                                        <?php echo $kod->kode . ' – ' . $kod->nama; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="text-muted">Jika kode tidak ada, <a href="<?php echo base_url('admin/kode'); ?>" target="_blank">tambahkan kode baru</a></small>
                    </div>
                    <div class="form-group">
                        <label>INDEKS/PEKERJAAN</label>
                        <input type="text" class="form-control" name="indeks_pekerjaan" value="<?php echo isset($ars->indeks_pekerjaan) && !empty($ars->indeks_pekerjaan) ? $ars->indeks_pekerjaan : 'Satker Balai Penilaian Kompetensi'; ?>">
                    </div>
                    <div class="form-group">
                        <label>URAIAN MASALAH/KEGIATAN</label>
                        <textarea class="form-control" name="uraian_masalah_kegiatan" rows="3"><?php echo isset($ars->uraian_masalah_kegiatan) ? $ars->uraian_masalah_kegiatan : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>TANGGAL</label>
                        <?php 
                        // Ambil kategori dari arsip yang sedang diedit
                        $kategori_arsip_edit = $this->m_model->get_where(array('id' => $ars->kategori_id), 'tb_kategori_arsip')->row();
                        $kategori_parent_edit = null;
                        if($kategori_arsip_edit) {
                            if(empty($kategori_arsip_edit->parent_id)) {
                                $kategori_parent_edit = $kategori_arsip_edit;
                            } else {
                                $kategori_parent_edit = $this->m_model->get_where(array('id' => $kategori_arsip_edit->parent_id), 'tb_kategori_arsip')->row();
                            }
                        }
                        // Cek kondisi: jika kategori parent = 'arsip inaktif' dan parent_id = null, gunakan input year
                        $is_arsip_inaktif_edit = $kategori_parent_edit && 
                                                 strtolower(trim($kategori_parent_edit->nama)) == 'arsip inaktif' && 
                                                 empty($kategori_parent_edit->parent_id);
                        ?>
                        <?php if($is_arsip_inaktif_edit): ?>
                            <?php 
                            // Jika tahun sudah ada, ambil tahun saja
                            $tahun_value = '';
                            if(isset($ars->tahun) && !empty($ars->tahun)) {
                                $tahun_value = date('Y', strtotime($ars->tahun));
                            } else {
                                $tahun_value = date('Y');
                            }
                            ?>
                            <input type="number" class="form-control" name="tahun" placeholder="Contoh: 2024" min="1900" max="<?php echo date('Y') + 1; ?>" value="<?php echo $tahun_value; ?>" id="tahun_year_input_edit<?php echo $ars->id; ?>">
                            <small class="text-muted">Hanya isi tahun (bulan dan tanggal tidak perlu)</small>
                        <?php else: ?>
                            <input type="date" class="form-control" name="tahun" value="<?php echo isset($ars->tahun) && !empty($ars->tahun) ? date('Y-m-d', strtotime($ars->tahun)) : date('Y-m-d'); ?>">
                        <?php endif; ?>
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
                        <?php 
                        // Cek kondisi: jika kategori parent = 'arsip aktif' dan parent_id = null, sembunyikan field BOX
                        $is_arsip_aktif_edit = $kategori_parent_edit && 
                                              strtolower(trim($kategori_parent_edit->nama)) == 'arsip aktif' && 
                                              empty($kategori_parent_edit->parent_id);
                        ?>
                        <?php if(!$is_arsip_aktif_edit): ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>BOX</label>
                                <input type="text" class="form-control" name="box" placeholder="Contoh: 1, 2, A-1" value="<?php echo isset($ars->box) ? $ars->box : ''; ?>">
                            </div>
                        </div>
                        <?php endif; ?>
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


<style>
/* Custom styling untuk modal upload progress */
#uploadProgressModal .modal-content {
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,.3);
}

#uploadProgressModal .modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px 8px 0 0;
    padding: 20px;
}

#uploadProgressModal .modal-header .modal-title {
    font-weight: 600;
    font-size: 20px;
}

#uploadProgressModal .modal-body {
    padding: 30px;
}

#single-upload-status {
    color: #333;
    font-weight: 500;
    margin-bottom: 20px;
    font-size: 18px;
}

#single-upload-progress-bar {
    transition: width 0.3s ease;
    font-size: 16px;
    font-weight: bold;
}

#single-upload-file-info {
    font-size: 14px;
    margin-top: 15px;
}

#single-upload-file-info i {
    color: #667eea;
    margin-right: 5px;
}

#single-upload-filename {
    font-weight: 600;
    color: #333;
}

.progress {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: inset 0 2px 4px rgba(0,0,0,.1);
}

.progress-bar-info {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.progress-bar-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.progress-bar-danger {
    background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
}

#single-upload-complete-info .alert {
    border-radius: 8px;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,.1);
}

#single-upload-complete-info .fa-check-circle {
    color: #38ef7d;
    margin-bottom: 10px;
}

#single-upload-error-info .alert {
    border-radius: 8px;
    border: none;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

#single-upload-complete-info .fa-check-circle {
    animation: pulse 1.5s ease infinite;
}
</style>

<script>
// Fungsi toggle untuk form tambah arsip (DI LUAR document.ready agar bisa dipanggil dari inline onchange)
function toggleFileInput() {
    var fileType = $('input[name="file_type"]:checked').val();
    var fileSection = $('#file_upload_section');
    var linkSection = $('#link_drive_section');
    var fileInput = $('#file_arsip');
    var linkInput = $('#link_drive');
    
    console.log('toggleFileInput called, fileType:', fileType); // Debug log
    
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
    } else if(fileType === 'drive') {
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

// Fungsi toggle untuk form edit arsip (DI LUAR document.ready)
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

// Validasi form: harus ada file atau link drive (DI LUAR document.ready)
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

$(document).ready(function() {
    // Inisialisasi toggle saat halaman dimuat untuk form tambah
    if($('input[name="file_type"]:checked').length > 0) {
        toggleFileInput();
    }
    
    // Inisialisasi saat modal tambah arsip dibuka
    $('#tambahData').on('shown.bs.modal', function() {
        // Panggil toggleFileInput untuk memastikan tampilan sesuai dengan radio button yang dipilih
        toggleFileInput();
    });
    
    // Handle submit form tambah arsip dengan AJAX dan progress bar
    $('#formTambahArsip').on('submit', function(e) {
        e.preventDefault();
        
        // Validasi file atau link
        if(!validateFileOrLink()) {
            return false;
        }
        
        var fileType = $('input[name="file_type"]:checked').val();
        var fileInput = $('#file_arsip')[0];
        
        // Jika upload file, tampilkan progress modal
        if(fileType === 'upload' && fileInput.files.length > 0) {
            var file = fileInput.files[0];
            var fileSize = (file.size / 1024 / 1024).toFixed(2);
            
            // Set info file
            $('#single-upload-filename').text(file.name);
            $('#single-upload-filesize').text(fileSize + ' MB');
            
            // Reset progress bar
            $('#single-upload-progress-bar').css('width', '0%').attr('aria-valuenow', 0);
            $('#single-upload-progress-text').text('0%');
            $('#single-upload-status').text('Mengupload arsip...');
            $('#single-upload-progress-bar').removeClass('progress-bar-success progress-bar-danger').addClass('progress-bar-info progress-bar-striped progress-bar-animated');
            $('#single-upload-complete-info').hide();
            $('#single-upload-error-info').hide();
            $('#btn-close-single-progress').hide();
            
            // Tutup modal tambah dan buka modal progress
            $('#tambahData').modal('hide');
            $('#uploadProgressModal').modal('show');
            
            // Buat FormData
            var formData = new FormData(this);
            
            // Submit via AJAX dengan progress tracking
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    
                    // Track upload progress
                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            var percentComplete = (e.loaded / e.total) * 100;
                            $('#single-upload-progress-bar').css('width', percentComplete + '%').attr('aria-valuenow', percentComplete);
                            $('#single-upload-progress-text').text(percentComplete.toFixed(1) + '%');
                            
                            // Update status
                            if(percentComplete < 100) {
                                $('#single-upload-status').text('Mengupload arsip... (' + (e.loaded / 1024 / 1024).toFixed(2) + ' / ' + (e.total / 1024 / 1024).toFixed(2) + ' MB)');
                            } else {
                                $('#single-upload-status').text('Memproses data...');
                            }
                        }
                    }, false);
                    
                    return xhr;
                },
                success: function(response) {
                    // Update progress bar to 100%
                    $('#single-upload-progress-bar').css('width', '100%').attr('aria-valuenow', 100);
                    $('#single-upload-progress-text').text('100%');
                    $('#single-upload-progress-bar').removeClass('progress-bar-info progress-bar-striped progress-bar-animated').addClass('progress-bar-success');
                    $('#single-upload-status').text('Upload selesai!');
                    
                    // Tampilkan pesan sukses
                    $('#single-upload-complete-info').show();
                    
                    // Tunggu 2 detik lalu reload
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                },
                error: function(xhr, status, error) {
                    console.error('Upload error:', error, xhr);
                    
                    var errorMsg = 'Terjadi kesalahan saat upload';
                    
                    // Cek error response
                    if(xhr.status === 0 || xhr.status === 413) {
                        errorMsg = 'Ukuran file melebihi limit server. Silakan upload file dengan ukuran lebih kecil atau tingkatkan limit di php.ini.';
                    } else if(xhr.responseText) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if(response.message) {
                                errorMsg = response.message;
                            }
                        } catch(e) {
                            errorMsg = xhr.responseText;
                        }
                    } else {
                        errorMsg += ': ' + error;
                    }
                    
                    // Update progress bar menjadi merah
                    $('#single-upload-progress-bar').removeClass('progress-bar-info progress-bar-striped progress-bar-animated').addClass('progress-bar-danger');
                    $('#single-upload-status').text('Upload gagal!');
                    
                    // Tampilkan error message
                    $('#single-upload-error-message').text(errorMsg);
                    $('#single-upload-error-info').show();
                    $('#btn-close-single-progress').show();
                }
            });
        } else {
            // Jika link drive, submit normal tanpa progress
            this.submit();
        }
        
        return false;
    });
    
    // Handle close button di progress modal
    $('#btn-close-single-progress').on('click', function() {
        $('#uploadProgressModal').modal('hide');
        // Kembali ke form tambah
        $('#tambahData').modal('show');
        // Reset form
        $('#formTambahArsip')[0].reset();
        toggleFileInput();
    });
    
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
    
    // Bulk Upload - Tampilkan daftar file yang dipilih
    $('#files_arsip').on('change', function() {
        var files = this.files;
        var fileList = $('#file-list');
        fileList.empty();
        
        if(files.length > 0) {
            var html = '<div class="alert alert-success"><strong>File yang dipilih (' + files.length + ' file):</strong><ul style="margin-bottom: 0; padding-left: 20px;">';
            var totalSize = 0;
            
            for(var i = 0; i < files.length; i++) {
                var fileSize = (files[i].size / 1024 / 1024).toFixed(2);
                totalSize += files[i].size;
                html += '<li>' + files[i].name + ' (' + fileSize + ' MB)</li>';
            }
            
            var totalSizeMB = (totalSize / 1024 / 1024).toFixed(2);
            html += '</ul><strong>Total ukuran: ' + totalSizeMB + ' MB</strong></div>';
            fileList.html(html);
        }
    });
    
    // Bulk Upload - Validasi sebelum submit dengan AJAX
    $('#formBulkUpload').on('submit', function(e) {
        e.preventDefault();
        
        var files = $('#files_arsip')[0].files;
        if(files.length === 0) {
            alert('Pilih minimal 1 file untuk diupload!');
            return false;
        }
        
        // Tampilkan progress bar
        $('#upload-progress-container').show();
        $('#btn-bulk-upload').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Uploading...');
        $('#btn-cancel-upload').prop('disabled', true);
        
        // Reset progress
        updateProgress(0, 0, 0, files.length, 'Memulai upload...');
        
        // Buat FormData
        var formData = new FormData(this);
        
        // Pastikan file terkirim dengan benar - append file secara eksplisit
        // Hapus dulu jika ada (untuk menghindari duplikasi)
        formData.delete('files_arsip[]');
        for(var i = 0; i < files.length; i++) {
            formData.append('files_arsip[]', files[i]);
        }
        
        // Debug: log jumlah file dan ukuran
        var totalSize = Array.from(files).reduce((sum, f) => sum + f.size, 0);
        console.log('Jumlah file yang akan diupload:', files.length);
        console.log('Total ukuran:', (totalSize / 1024 / 1024).toFixed(2) + ' MB');
        
        // Validasi: pastikan file benar-benar ada di FormData
        if(!formData.has('files_arsip[]')) {
            alert('Error: File tidak dapat ditambahkan ke FormData. Silakan coba lagi.');
            $('#upload-progress-container').hide();
            $('#btn-bulk-upload').prop('disabled', false).html('<div class="fa fa-upload"></div> Upload Semua');
            $('#btn-cancel-upload').prop('disabled', false);
            return false;
        }
        
        // Submit via AJAX
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                return xhr;
            },
            success: function(response) {
                // Parse response jika string
                if(typeof response === 'string') {
                    try {
                        response = JSON.parse(response);
                    } catch(e) {
                        // Jika bukan JSON, anggap sukses
                        response = {success: true, message: response};
                    }
                }
                
                if(response.success) {
                    updateProgress(100, response.success_count || 0, response.error_count || 0, 
                                 response.success_count + response.error_count || 0, 'Upload selesai!');
                    
                    // Tunggu 2 detik lalu reload
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                } else {
                    alert('Error: ' + (response.message || 'Upload gagal'));
                    $('#upload-progress-container').hide();
                    $('#btn-bulk-upload').prop('disabled', false).html('<div class="fa fa-upload"></div> Upload Semua');
                    $('#btn-cancel-upload').prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error('Upload error:', error, xhr);
                var errorMsg = 'Terjadi kesalahan saat upload: ' + error;
                
                // Cek apakah error karena POST terpotong
                if(xhr.status === 0 || xhr.status === 413) {
                    errorMsg = 'Upload gagal! Kemungkinan ukuran file melebihi limit PHP server. ';
                    errorMsg += 'Silakan tingkatkan post_max_size dan upload_max_filesize di php.ini. ';
                    errorMsg += 'Atau coba upload file dengan ukuran lebih kecil.';
                } else if(xhr.responseText) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if(response.message) {
                            errorMsg = response.message;
                        }
                    } catch(e) {
                        // Bukan JSON, gunakan error default
                    }
                }
                
                alert(errorMsg);
                $('#upload-progress-container').hide();
                $('#btn-bulk-upload').prop('disabled', false).html('<div class="fa fa-upload"></div> Upload Semua');
                $('#btn-cancel-upload').prop('disabled', false);
            }
        });
        
        // Mulai polling progress
        var progressInterval = setInterval(function() {
            $.ajax({
                url: '<?php echo base_url("admin/arsip/get_upload_progress"); ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if(response.success && response.progress) {
                        var progress = response.progress;
                        var percentage = progress.percentage || 0;
                        var processed = progress.processed || 0;
                        var success = progress.success || 0;
                        var error = progress.error || 0;
                        var total = progress.total || 0;
                        var currentFile = progress.current_file || 'Memproses...';
                        
                        updateProgress(percentage, success, error, total, currentFile);
                        
                        // Jika sudah selesai, stop polling
                        if(progress.status === 'completed') {
                            clearInterval(progressInterval);
                        }
                    }
                },
                error: function() {
                    // Ignore error saat polling
                }
            });
        }, 500); // Poll setiap 500ms
        
        return false;
    });
    
    // Function untuk update progress bar
    function updateProgress(percentage, success, error, total, currentFile) {
        $('#upload-progress-bar').css('width', percentage + '%').attr('aria-valuenow', percentage);
        $('#upload-progress-text').text(percentage.toFixed(1) + '%');
        $('#current-file-name').text(currentFile);
        $('#success-count').text(success);
        $('#error-count').text(error);
        $('#total-count').text(total);
        
        // Update warna progress bar berdasarkan status
        var progressBar = $('#upload-progress-bar');
        if(percentage === 100) {
            progressBar.removeClass('progress-bar-animated').removeClass('progress-bar-striped')
                      .removeClass('progress-bar-warning').addClass('progress-bar-success');
        } else if(error > 0) {
            progressBar.removeClass('progress-bar-success').addClass('progress-bar-warning');
        }
    }
});
</script> 
