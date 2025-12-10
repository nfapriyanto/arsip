<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $title ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('assets') ?>/image/logo.png">
  
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url('assets') ?>/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('assets') ?>/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?php echo base_url('assets') ?>/dist/css/skins/_all-skins.min.css">
  
  <style>
    body {
      background-color: #ecf0f5;
      padding-top: 20px;
    }
    .public-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 15px;
    }
    .public-header {
      background: linear-gradient(135deg, #364878 0%, #364878 100%);
      color: white;
      padding: 30px;
      border-radius: 5px 5px 0 0;
      margin-bottom: 0;
      text-align: center;
    }
    .public-header h1 {
      margin: 0;
      font-size: 28px;
      font-weight: bold;
    }
    .public-header p {
      margin: 10px 0 0 0;
      font-size: 14px;
      opacity: 0.9;
    }
    .login-link {
      position: absolute;
      top: 20px;
      right: 20px;
    }
    .box {
      border-radius: 0 0 5px 5px;
    }
  </style>
</head>
<body>
  <div class="public-container">
    <!-- Header -->
    <div class="public-header">
      <h1><i class="fa fa-archive"></i> Cari Arsip</h1>
      <p>Sistem Penyimpanan Arsip Digital</p>
      <a href="<?php echo base_url('admin') ?>" class="btn btn-default login-link">
        <i class="fa fa-sign-in"></i> Login Admin
      </a>
    </div>

    <!-- Search Section -->
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-search"></i> Pencarian Arsip</h3>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Cari Arsip (Nomor Arsip, Judul, atau Deskripsi)</label>
              <div class="input-group">
                <input type="text" class="form-control" id="searchKeyword" placeholder="Masukkan nomor arsip, judul, atau kata kunci">
                <span class="input-group-btn">
                  <button class="btn btn-primary" type="button" id="btnSearch">
                    <i class="fa fa-search"></i> Cari
                  </button>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Results Section -->
    <div class="row" id="resultSection" style="display:none;">
      <div class="col-md-12">
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-list"></i> Hasil Pencarian</h3>
          </div>
          <div class="box-body" id="resultContent">
            <!-- Results will be loaded here -->
          </div>
        </div>
      </div>
    </div>

    <!-- Error Section -->
    <div class="row" id="errorSection" style="display:none;">
      <div class="col-md-12">
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h4><i class="icon fa fa-ban"></i> Error!</h4>
          <p id="errorMessage"></p>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery 2.2.3 -->
  <script src="<?php echo base_url('assets') ?>/plugins/jQuery/jquery-2.2.3.min.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="<?php echo base_url('assets') ?>/bootstrap/js/bootstrap.min.js"></script>
  
  <!-- Custom Script for Search -->
  <script>
    jQuery(document).ready(function($) {
      // Manual Search
      $('#btnSearch').on('click', function() {
        performSearch();
      });

      $('#searchKeyword').on('keypress', function(e) {
        if(e.which == 13) {
          performSearch();
        }
      });

      function performSearch() {
        let keyword = $('#searchKeyword').val().trim();
        
        if(keyword === '') {
          showError('Silakan masukkan kata kunci pencarian');
          return;
        }

        // Hide previous results
        $('#resultSection').hide();
        $('#errorSection').hide();

        // Show loading
        $('#resultContent').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i><br><p>Mencari arsip...</p></div>');
        $('#resultSection').show();

        // Perform AJAX search
        var baseUrl = '<?php echo base_url("CariArsip/search") ?>';
        
        $.ajax({
          url: baseUrl,
          type: 'POST',
          data: { keyword: keyword },
          dataType: 'json',
          success: function(response) {
            if(response.status === 'success') {
              displayResult(response);
            } else {
              showError(response.message);
            }
          },
          error: function(xhr, status, error) {
            console.error("AJAX error:", xhr, status, error);
            showError('Terjadi kesalahan saat mencari data: ' + error);
          }
        });
      }

      function displayResult(data) {
        let html = '';
        
        if(data.arsip && data.arsip.length > 0) {
          html += '<div class="table-responsive">';
          html += '<table class="table table-bordered table-hover">';
          html += '<thead>';
          html += '<tr>';
          html += '<th width="5px">No</th>';
          html += '<th>Nomor Arsip</th>';
          html += '<th>Judul</th>';
          html += '<th>Kategori</th>';
          html += '<th>Tahun Dokumen</th>';
          html += '<th>Pembuat</th>';
          html += '<th>File</th>';
          html += '</tr>';
          html += '</thead>';
          html += '<tbody>';
          
          data.arsip.forEach(function(arsip, index) {
            html += '<tr>';
            html += '<td>' + (index + 1) + '</td>';
            html += '<td><strong>' + escapeHtml(arsip.nomor_arsip) + '</strong></td>';
            html += '<td>' + escapeHtml(arsip.judul) + '</td>';
            html += '<td>' + escapeHtml(arsip.kategori_nama || '-') + '</td>';
            html += '<td>' + (arsip.tahun_dokumen ? arsip.tahun_dokumen : '-') + '</td>';
            html += '<td>' + escapeHtml(arsip.pembuat || '-') + '</td>';
            html += '<td><i class="fa fa-file"></i> ' + escapeHtml(arsip.nama_file) + '</td>';
            html += '</tr>';
          });
          
          html += '</tbody>';
          html += '</table>';
          html += '</div>';
        } else {
          html += '<div class="alert alert-info">';
          html += '<i class="fa fa-info-circle"></i> Tidak ada arsip yang ditemukan.';
          html += '</div>';
        }

        $('#resultContent').html(html);
        $('#resultSection').show();
        $('#errorSection').hide();
      }

      function showError(message) {
        $('#errorMessage').text(message);
        $('#errorSection').show();
        $('#resultSection').hide();
      }

      function escapeHtml(text) {
        if (!text) return '';
        const map = {
          '&': '&amp;',
          '<': '&lt;',
          '>': '&gt;',
          '"': '&quot;',
          "'": '&#039;'
        };
        return text.toString().replace(/[&<>"']/g, function(m) { return map[m]; });
      }

      function formatDate(dateString) {
        if (!dateString) return '-';
        let date = new Date(dateString);
        let options = { 
          year: 'numeric', 
          month: 'long', 
          day: 'numeric'
        };
        return date.toLocaleDateString('id-ID', options);
      }
    });
  </script>
</body>
</html>
