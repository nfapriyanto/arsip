  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo base_url('assets') ?>/dist/img/avatar4.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $this->session->userdata('nama') ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>

        <li class="treeview">
          <a href="<?php echo base_url('user/dashboard') ?>">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>

        <li class="treeview">
          <a href="<?php echo base_url('user/barang') ?>">
            <i class="fa fa-book"></i> <span>Stok Barang</span>
          </a>
        </li>

        <li class="treeview">
          <a href="<?php echo base_url('user/riwayat') ?>">
            <i class="fa fa-history"></i> <span>Riwayat Barang</span>
          </a>
        </li>
        
        <li class="treeview">
          <a href="<?php echo base_url('user/profile') ?>">
            <i class="fa fa-user"></i> <span>Profile</span>
          </a>
        </li>

        <li class="treeview">
          <a href="<?php echo base_url('welcome/logout') ?>" class="tombol-yakin" data-isiData="Ingin keluar dari sistem ini!">
            <i class="fa fa-sign-out"></i> <span>Sign Out</span>
          </a>
        </li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>