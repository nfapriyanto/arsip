  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Data Barang
        <small>Data Barang</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Data Barang</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <?php if(isset($kategori) && !isset($barang)): ?>
            <!-- TAMPILAN KATEGORI -->
            <!-- Tombol Tambah Data -->
            <div class="btn btn-danger" data-toggle="modal" data-target="#tambahData">
                <div class="fa fa-plus"></div> Tambah Data
            </div>

            <!-- Tombol Cetak Data -->
            <a href="<?php echo base_url('admin/barang/printStokBarang') ?>" class="btn btn-primary">
                <div class="fa fa-print"></div> Cetak Data
            </a>

            <!-- Tabel Kategori -->
            <div class="box box-danger" style="margin-top: 15px">
                <div class="box-header">
                    <h3 class="box-title">Daftar Kategori Barang</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped" id="example1">
                            <thead>
                                <tr>
                                    <th width="5px">No</th>
                                    <th>Nama Kategori</th>
                                    <th>Total Stok</th>
                                    <th>Tempat</th>
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
                                        <td><strong><?php echo isset($kat->nama) ? $kat->nama : $kat->kode; ?></strong></td>
                                        <td><?php echo $kat->total_stok; ?></td>
                                        <td><?php echo $kat->tempat; ?></td>
                                        <td>
                                            <!-- Tombol Edit Kategori -->
                                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editKategori<?php echo $kat->id; ?>">
                                                <div class="fa fa-edit"></div> Edit
                                            </button>
                                            <!-- Tombol Lihat Daftar Barang -->
                                            <a href="<?php echo base_url('admin/barang/kategori/').$kat->id; ?>" class="btn btn-info btn-sm">
                                                <div class="fa fa-eye"></div> Lihat Daftar Barang
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        <?php elseif(isset($barang) && isset($kategori_id)): ?>
            <!-- TAMPILAN DAFTAR BARANG PER KATEGORI -->
            <!-- Tombol Kembali -->
            <a href="<?php echo base_url('admin/barang') ?>" class="btn btn-default">
                <div class="fa fa-arrow-left"></div> Kembali ke Kategori
            </a>

            <!-- Tombol Tambah Data -->
            <div class="btn btn-danger" data-toggle="modal" data-target="#tambahData">
                <div class="fa fa-plus"></div> Tambah Data
            </div>

            <!-- Tabel Daftar Barang -->
            <div class="box box-danger" style="margin-top: 15px">
                <div class="box-header">
                    <h3 class="box-title">Daftar Barang: <?php echo isset($kategori_nama) ? $kategori_nama : ''; ?></h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped" id="example1">
                            <thead>
                                <tr>
                                    <th width="5px">No</th>
                                    <th>Nama Barang</th>
                                    <th>Kode QR</th>
                                    <th>QR Code</th>
                                    <th>Status</th>
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
                                        <td><strong><?php echo isset($brg->kode_qr) ? $brg->kode_qr : '-'; ?></strong></td>
                                        <td>
                                            <?php if(isset($brg->kode_qr) && !empty($brg->kode_qr)): ?>
                                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=<?php echo urlencode($brg->kode_qr); ?>" 
                                                     alt="QR Code" 
                                                     style="width: 80px; height: 80px; cursor: pointer;"
                                                     onclick="window.open('<?php echo base_url('admin/barang/generateQRCode/'.$brg->id) ?>', '_blank')"
                                                     title="Klik untuk melihat QR Code ukuran besar">
                                            <?php else: ?>
                                                <span class="label label-warning">Belum ada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if(isset($brg->is_available) && $brg->is_available == 1): ?>
                                                <span class="label label-success">Tersedia</span>
                                            <?php else: ?>
                                                <span class="label label-danger">Dipinjam</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <!-- Tombol Kelola -->
                                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#kelola<?= $brg->id ?>">
                                                <div class="fa fa-plus-square"></div> Kelola
                                            </button>
                                            <!-- Tombol History -->
                                            <a href="<?php echo base_url('admin/barang/riwayat/').$brg->id ; ?>" class="btn btn-primary btn-sm">
                                                <div class="fa fa-history"></div> History
                                            </a>
                                            <!-- Tombol Delete -->
                                            <a href="<?php echo base_url('admin/barang/delete/').$brg->id; ?>" class="btn btn-danger btn-sm tombol-yakin" data-isiData="Ingin menghapus data ini!">
                                                <div class="fa fa-trash"></div> Delete
                                            </a>
                                            <!-- Tombol Edit -->
                                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editData<?php echo $brg->id; ?>">
                                                <div class="fa fa-edit"></div> Edit
                                            </button>
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
  <?php if(isset($kategori) && !isset($barang)): ?>
  <div class="modal fade" id="tambahData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><div class="fa fa-plus"></div> Tambah Kategori</h4>
        </div>
        <form action="<?php echo base_url('admin/barang/insert_kategori') ?>" method="POST">
          <div class="modal-body">
            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text" class="form-control" name="nama" placeholder="Nama Kategori" required>
            </div>
            <div class="form-group">
                <label>Tempat</label>
                <input type="text" class="form-control" name="tempat" placeholder="Tempat" required>
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

  <!-- Modal Tambah Barang (untuk halaman daftar barang) -->
  <?php if(isset($barang) && isset($kategori_id)): ?>
  <div class="modal fade" id="tambahData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><div class="fa fa-plus"></div> Tambah Barang</h4>
        </div>
        <form action="<?php echo base_url('admin/barang/insert') ?>" method="POST">
          <div class="modal-body">
            <div class="form-group">
                <label>Kategori</label>
                <select class="form-control" name="kategori_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php if(isset($list_kategori)): ?>
                        <?php foreach($list_kategori as $kat): ?>
                            <option value="<?php echo $kat->id; ?>" <?php echo (isset($kategori_id) && $kategori_id == $kat->id) ? 'selected' : ''; ?>>
                                <?php echo $kat->nama; ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
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
  <?php if(isset($kategori) && !isset($barang)): ?>
  <?php foreach ($kategori as $kat) { ?>
    <div class="modal fade" id="editKategori<?php echo $kat->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><div class="fa fa-edit"></div> Edit Kategori</h4>
                </div>
                <form action="<?php echo base_url('admin/barang/update_kategori') ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input type="hidden" name="id" value="<?php echo $kat->id; ?>">
                        <input type="text" class="form-control" name="nama" placeholder="Nama Kategori" value="<?php echo isset($kat->nama) ? $kat->nama : $kat->kode; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Tempat</label>
                        <input type="text" class="form-control" name="tempat" placeholder="Tempat" value="<?php echo isset($kat->tempat) ? $kat->tempat : ''; ?>" required>
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
  <?php if(isset($barang)): ?>
  <?php foreach ($barang as $brg) { ?>
    <div class="modal fade" id="editData<?php echo $brg->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><div class="fa fa-edit"></div> Edit Data</h4>
                </div>
                <form action="<?php echo base_url('admin/barang/update') ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kategori</label>
                        <input type="hidden" name="id" value="<?php echo $brg->id; ?>">
                        <select class="form-control" name="kategori_id" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php if(isset($list_kategori)): ?>
                                <?php foreach($list_kategori as $kat): ?>
                                    <option value="<?php echo $kat->id; ?>" <?php echo (isset($brg->kategori_id) && $brg->kategori_id == $kat->id) ? 'selected' : ''; ?>>
                                        <?php echo $kat->nama; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-danger"><div class="fa fa-trash"></div> Reset</button>
                    <button type="submit" class="btn btn-primary"><div class="fa fa-save"></div> update</button>
                </div>
                </form>
            </div>
        </div>
    </div>
  <?php } ?>
  <?php endif; ?>

  <!-- Modal Kelola Data -->
  <?php if(isset($barang)): ?>
  <?php foreach ($barang as $brg) { ?>
    <div class="modal fade" id="kelola<?= $brg->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><div class="fa fa-plus-square"></div> Kelola Data</h4>
                </div>
                <form action="<?php echo base_url('admin/barang/insert_kelola') ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="hidden" name="id" value="<?php echo $brg->id; ?>">
                        <input type="text" class="form-control" name="kode" value="<?= $brg->kode ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Jenis</label>
                        <input type="hidden" name="id" value="<?php echo $brg->id; ?>">
                        <select class="form-control" name="jenis" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Pengembalian">Pengembalian</option>
                            <option value="Peminjaman">Peminjaman</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Peminjam</label>
                        <input type="text" class="form-control" name="peminjam" placeholder="Peminjam" required>
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
  <?php endif; ?>