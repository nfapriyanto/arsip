  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Manajemen User
        <small>Manajemen User</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manajemen User</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Button Tambah -->
        <button class="btn btn-danger" data-toggle="modal" data-target="#tambahData">
            <div class="fa fa-user-plus"></div> Tambah User
        </button>

        <!-- User -->
        <div class="row" style="margin-top:10px">
            <?php
                foreach ($manajemenUser as $usr) {
            ?>
                <div class="col-md-3">
                    <div class="box box-danger">
                        <div class="box-body box-profile">
                            <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url('assets') ?>/dist/img/avatar4.png" alt="User profile picture">

                            <h3 class="profile-username text-center"><?php echo $usr->nama ?></h3>

                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                <b>Username</b> <a class="pull-right"><?php echo $usr->username ?></a>
                                </li>
                                <li class="list-group-item">
                                <b>Password</b> <a class="pull-right">Disembunyikan</a>
                                </li>
                                <li class="list-group-item">
                                <b>Level</b>
                                  <?php 
                                    if($usr->level == 'Admin') {
                                      echo '<div class="label label-success pull-right"> <div class="fa fa-graduation-cap"> '.$usr->level.'</div>';
                                    } else {
                                      echo '<div class="label label-danger pull-right"> <div class="fa fa-users"> '.$usr->level.'</div>';
                                    }
                                  ?>
                                </li>
                                <li class="list-group-item">
                                <b>Dibuat Pada</b> <a class="pull-right"><?php echo date('d-M-Y H:i:s', strtotime($usr->createDate)) ?></a>
                                </li>
                            </ul>

                            <div class="pull-right">
                                <?php if($usr->id == $this->session->userdata('id')){ ?>
                                  <a href="<?php echo base_url('admin/profile') ?>" class="btn btn-success btn-sm">
                                    <div class="fa fa-user"></div> Profile
                                  </a>
                                <?php } else { ?>
                                  <a href="<?php echo base_url('admin/manajemenUser/delete/').$usr->id ?>" class="btn btn-danger btn-sm tombol-yakin" data-isiData="Ingin menghapus user ini!">
                                    <div class="fa fa-trash"></div> Hapus
                                  </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
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
          <h4 class="modal-title" id="myModalLabel"><div class="fa fa-user-plus"></div> Tambah User Baru</h4>
        </div>
        <form action="<?php echo base_url('admin/manajemenUser/insert') ?>" method="POST">
          <div class="modal-body">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap" required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <label>Level</label>
                <select name="level" class="form-control" required>
                    <option value=""> -- Pilih Level -- </option>
                    <option value="Admin">Admin</option>
                    <option value="User">User</option>
                </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="reset" class="btn btn-danger"><div class="fa fa-trash"></div> Reset</button>
            <button type="submit" class="btn btn-primary"><div class="fa fa-save"></div> Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>