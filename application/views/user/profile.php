  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Profile
        <small>Profile</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Profile</li>
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

      <!-- Box Data -->
      <div class="box box-warning">

        <div class="box-header with-border">
          <h3 class="box-title">Edit Profile</h3>
        </div>

        <div class="box-body">
          <form action="<?php echo base_url('user/profile/update') ?>" method="POST">
            
            <div class="form-group">
              <label>Nama Lengkap</label>
              <input type="text" name="nama" value="<?= $this->session->userdata('nama') ?>" class="form-control" placeholder="Nama Lengkap">
            </div>

            <div class="form-group">
              <label>Username</label>
              <input type="text" name="username" value="<?= $this->session->userdata('username') ?>" class="form-control" placeholder="Username">
            </div>
            
            <div class="form-group">
              <label>New Password <font color="red">*)</font></label>
              <input type="password" name="password" class="form-control" placeholder="New Password">
            </div>
            
            <div class="form-group">
              <label>Level</label>
              <input type="text" value="<?= $this->session->userdata('level') ?>" class="form-control" readonly>
            </div>

            <div class="form-group">
              <label>Terdaftar Pada</label>
              <input type="text" value="<?= date('d-M-Y H:i:s', strtotime($this->session->userdata('createDate'))) ?>" class="form-control" readonly>
            </div>
            
            <p>
              <font color="red">*) Kosongkan jika tidak ingin diubah</font>
            </p>
            
            <div class="pull-right">
              <button type="reset" class="btn btn-danger">
                <i class="fa fa-trash"></i> Reset
              </button>
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Update
              </button>
            </div>
          
          </form>
        </div>
        
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->