  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Cari Barang
        <small>Pencarian Barang dengan Kode atau QR Code</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Cari Barang</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- Search Section -->
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-search"></i> Pencarian Barang</h3>
            </div>
            <div class="box-body">
              <div class="row">
                <!-- Manual Search -->
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Cari dengan Kode Barang</label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="searchKode" placeholder="Masukkan kode barang (contoh: #ASFISDJO837 atau ASFISDJO837)">
                      <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" id="btnSearch">
                          <i class="fa fa-search"></i> Cari
                        </button>
                      </span>
                    </div>
                  </div>
                </div>
                
                <!-- QR Code Scanner -->
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Scan QR Code</label>
                    <div class="btn-group btn-group-justified">
                      <button class="btn btn-success" type="button" id="btnStartScan">
                        <i class="fa fa-camera"></i> Aktifkan Kamera
                      </button>
                      <button class="btn btn-danger" type="button" id="btnStopScan" style="display:none;">
                        <i class="fa fa-stop"></i> Stop Scan
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- QR Code Scanner Container -->
              <div id="qr-reader" style="display:none; margin-top: 20px; text-align: center;"></div>
              <div id="qr-reader-results" style="display:none;"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Results Section -->
      <div class="row" id="resultSection" style="display:none;">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-info-circle"></i> Detail Barang</h3>
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
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Custom CSS for Button Full Width -->
  <style>
    .btn-group-justified > .btn,
    .btn-group-justified > .btn-group {
      width: 100%;
    }
  </style>

  <!-- QR Code Scanner Library - Load after jQuery -->
  <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
  
  <!-- Custom Script for Search and QR Scanner -->
  <script>
    // Wait for jQuery and all scripts to load
    (function() {
      function initSearchAndScanner() {
        // Check if jQuery is loaded
        if (typeof jQuery === 'undefined') {
          setTimeout(initSearchAndScanner, 100);
          return;
        }

        let html5QrcodeScanner = null;
        let isScanning = false;

        jQuery(document).ready(function($) {
          // Manual Search
          $('#btnSearch').on('click', function() {
            performSearch();
          });

          $('#searchKode').on('keypress', function(e) {
            if(e.which == 13) {
              performSearch();
            }
          });

          // Start QR Scanner
          $('#btnStartScan').on('click', function() {
            startQRScanner();
          });

          // Stop QR Scanner
          $('#btnStopScan').on('click', function() {
            stopQRScanner().catch(function(err) {
              console.error("Error stopping scanner:", err);
            });
          });
        });

        function performSearch() {
          let kode = jQuery('#searchKode').val().trim();
          console.log("performSearch() called with kode:", kode);
          
          if(kode === '') {
            showError('Silakan masukkan kode barang');
            return;
          }

          // Hide previous results
          jQuery('#resultSection').hide();
          jQuery('#errorSection').hide();

          // Show loading
          jQuery('#resultContent').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i><br><p>Mencari data...</p></div>');
          jQuery('#resultSection').show();

          // Perform AJAX search
          // Use current page protocol to avoid mixed content issues
          var currentProtocol = window.location.protocol;
          var baseUrl = '<?php echo base_url("admin/cariBarang/search") ?>';
          // Ensure URL uses same protocol as current page (fix for Cloudflare reverse proxy)
          if (currentProtocol === 'https:' && baseUrl.indexOf('http://') === 0) {
            baseUrl = baseUrl.replace('http://', 'https://');
          }
          
          console.log("Sending AJAX request to:", baseUrl, "with kode:", kode);
          
          jQuery.ajax({
            url: baseUrl,
            type: 'POST',
            data: { kode: kode },
            dataType: 'json',
            success: function(response) {
              console.log("AJAX response received:", response);
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
          let html = '<div class="row">';
          
          // Barang Info
          html += '<div class="col-md-6">';
          html += '<h4><i class="fa fa-cube"></i> Informasi Barang</h4>';
          html += '<table class="table table-bordered">';
          html += '<tr><th width="40%">Nama Barang</th><td><strong>' + escapeHtml(data.barang.kode) + '</strong></td></tr>';
          if(data.barang.kategori_nama) {
            html += '<tr><th>Kategori</th><td>' + escapeHtml(data.barang.kategori_nama) + '</td></tr>';
          }
          if(data.barang.kode_qr) {
            html += '<tr><th>Kode QR</th><td><strong>' + escapeHtml(data.barang.kode_qr) + '</strong></td></tr>';
          }
          if(data.barang.tempat) {
            html += '<tr><th>Tempat</th><td>' + escapeHtml(data.barang.tempat) + '</td></tr>';
          }
          html += '<tr><th>Tanggal Dibuat</th><td>' + formatDate(data.barang.createDate) + '</td></tr>';
          html += '</table>';
          html += '</div>';

          // Peminjam Aktif
          html += '<div class="col-md-6">';
          html += '<h4><i class="fa fa-user"></i> Peminjam Aktif</h4>';
          if(data.peminjamAktif) {
            html += '<table class="table table-bordered">';
            html += '<tr><th>Peminjam</th><td>' + escapeHtml(data.peminjamAktif.peminjam || data.peminjamAktif.unit || '-') + '</td></tr>';
            html += '<tr><th>No. Telepon</th><td>' + formatPhoneNumber(data.peminjamAktif.noTlp) + '</td></tr>';
            html += '<tr><th>Tanggal Pinjam</th><td>' + formatDate(data.peminjamAktif.createDate) + '</td></tr>';
            html += '</table>';
          } else {
            html += '<div class="alert alert-info">';
            html += '<i class="fa fa-info-circle"></i> Tidak ada peminjaman aktif untuk barang ini.';
            html += '</div>';
          }
          html += '</div>';
          html += '</div>';

          // Riwayat Peminjaman
          if(data.riwayat && data.riwayat.length > 0) {
            html += '<div class="row" style="margin-top: 20px;">';
            html += '<div class="col-md-12">';
            html += '<h4><i class="fa fa-history"></i> Riwayat Peminjaman</h4>';
            html += '<div class="table-responsive">';
            html += '<table class="table table-bordered table-striped">';
            html += '<thead><tr>';
            html += '<th>No</th>';
            html += '<th>Jenis</th>';
            html += '<th>Peminjam</th>';
            html += '<th>No. Telepon</th>';
            html += '<th>Tanggal</th>';
            html += '</tr></thead>';
            html += '<tbody>';
            
            let no = 1;
            data.riwayat.forEach(function(riwayat) {
              html += '<tr>';
              html += '<td>' + no++ + '</td>';
              if(riwayat.jenis === 'Peminjaman') {
                html += '<td><span class="label label-danger">' + escapeHtml(riwayat.jenis) + '</span></td>';
              } else {
                html += '<td><span class="label label-success">' + escapeHtml(riwayat.jenis) + '</span></td>';
              }
              html += '<td>' + escapeHtml(riwayat.peminjam || riwayat.unit || '-') + '</td>';
              html += '<td>' + escapeHtml(riwayat.noTlp) + '</td>';
              html += '<td>' + formatDate(riwayat.createDate) + '</td>';
              html += '</tr>';
            });
            
            html += '</tbody></table>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
          }

          jQuery('#resultContent').html(html);
          jQuery('#resultSection').show();
          jQuery('#errorSection').hide();
        }

        function showError(message) {
          jQuery('#errorMessage').text(message);
          jQuery('#errorSection').show();
          jQuery('#resultSection').hide();
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

        function formatPhoneNumber(phoneNumber) {
          if (!phoneNumber) return '';
          // Get last 4 digits
          let last4 = phoneNumber.toString().slice(-4);
          return '***' + last4;
        }

        function formatDate(dateString) {
          let date = new Date(dateString);
          let options = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
          };
          return date.toLocaleDateString('id-ID', options);
        }

        function startQRScanner() {
          if(isScanning) {
            return;
          }

          // Check if Html5Qrcode is available
          if (typeof Html5Qrcode === 'undefined') {
            showError('Library QR Scanner belum dimuat. Silakan refresh halaman.');
            return;
          }

          jQuery('#qr-reader').show();
          jQuery('#btnStartScan').hide();
          jQuery('#btnStopScan').show();
          isScanning = true;

          html5QrcodeScanner = new Html5Qrcode("qr-reader");
          
          // Try to get available cameras first
          Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length > 0) {
              // Use first available camera (prefer back camera)
              let cameraId = devices[0].id;
              for(let i = 0; i < devices.length; i++) {
                if(devices[i].label.toLowerCase().includes('back') || 
                   devices[i].label.toLowerCase().includes('rear') ||
                   devices[i].label.toLowerCase().includes('environment')) {
                  cameraId = devices[i].id;
                  break;
                }
              }

              html5QrcodeScanner.start(
                cameraId,
                {
                  fps: 10,
                  qrbox: { width: 300, height: 300 },
                  aspectRatio: 1.0
                },
                function(decodedText, decodedResult) {
                  // Success callback
                  console.log("QR Code detected:", decodedText);
                  
                  // Set the code in search field first
                  jQuery('#searchKode').val(decodedText);
                  console.log("QR Code set to input field:", decodedText);
                  
                  // Stop scanner and then perform search
                  stopQRScanner().then(function() {
                    console.log("Scanner stopped, performing search...");
                    // Small delay to ensure everything is ready
                    setTimeout(function() {
                      console.log("Calling performSearch()...");
                      performSearch();
                    }, 200);
                  }).catch(function(err) {
                    console.error("Error stopping scanner:", err);
                    // Even if stop fails, still perform search
                    setTimeout(function() {
                      console.log("Calling performSearch() after error...");
                      performSearch();
                    }, 200);
                  });
                },
                function(errorMessage) {
                  // Error callback - ignore, scanner will keep trying
                }
              ).catch(function(err) {
                // Handle start error
                console.error("Error starting scanner:", err);
                showError('Tidak dapat mengakses kamera: ' + err.message);
                stopQRScanner().catch(function(stopErr) {
                  console.error("Error stopping scanner:", stopErr);
                });
              });
            } else {
              showError('Tidak ada kamera yang tersedia.');
              stopQRScanner().catch(function(err) {
                console.error("Error stopping scanner:", err);
              });
            }
          }).catch(err => {
            console.error("Error getting cameras:", err);
            showError('Tidak dapat mengakses kamera. Pastikan browser mendukung dan izin kamera telah diberikan.');
            stopQRScanner().catch(function(stopErr) {
              console.error("Error stopping scanner:", stopErr);
            });
          });
        }

        function stopQRScanner() {
          return new Promise(function(resolve, reject) {
            if(html5QrcodeScanner) {
              html5QrcodeScanner.stop().then(function() {
                console.log("QR Scanner stopped");
                html5QrcodeScanner.clear();
                html5QrcodeScanner = null;
                jQuery('#qr-reader').hide();
                jQuery('#btnStartScan').show();
                jQuery('#btnStopScan').hide();
                isScanning = false;
                resolve();
              }).catch(function(err) {
                console.error("Error stopping scanner:", err);
                // Clear anyway
                html5QrcodeScanner = null;
                jQuery('#qr-reader').hide();
                jQuery('#btnStartScan').show();
                jQuery('#btnStopScan').hide();
                isScanning = false;
                resolve(); // Resolve anyway to continue
              });
            } else {
              jQuery('#qr-reader').hide();
              jQuery('#btnStartScan').show();
              jQuery('#btnStopScan').hide();
              isScanning = false;
              resolve();
            }
          });
        }

        // Clean up scanner when page is unloaded
        jQuery(window).on('beforeunload', function() {
          stopQRScanner().catch(function(err) {
            console.error("Error stopping scanner on unload:", err);
          });
        });
      }

      // Start initialization
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSearchAndScanner);
      } else {
        initSearchAndScanner();
      }
    })();
  </script>

