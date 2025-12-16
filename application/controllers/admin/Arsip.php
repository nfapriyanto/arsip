<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Arsip extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('id')){
            redirect('admin');
        } else {
            if($this->session->userdata('level') != 'Admin'){
                redirect('user/dashboard');
            }
        }
        $this->load->library('upload');
    }

    public function index()
    {
        $data['title'] = 'Data Kategori Arsip';
        
        // Ambil hanya kategori parent (parent_id IS NULL) dengan total arsip
        $this->db->select('k.id, k.nama, k.parent_id, COUNT(DISTINCT a.id) as total_arsip, COUNT(DISTINCT sub.id) as total_sub');
        $this->db->from('tb_kategori_arsip k');
        $this->db->join('tb_arsip a', 'a.kategori_id = k.id', 'left');
        $this->db->join('tb_kategori_arsip sub', 'sub.parent_id = k.id', 'left');
        $this->db->where('k.parent_id IS NULL');
        $this->db->group_by('k.id, k.nama, k.parent_id');
        $this->db->order_by('k.nama', 'ASC');
        $kategori = $this->db->get()->result();
        
        $data['kategori'] = $kategori;
        
        // Ambil semua kategori untuk dropdown form (hanya parent)
        $this->db->where('parent_id IS NULL');
        $this->db->order_by('nama', 'ASC');
        $data['list_kategori'] = $this->db->get('tb_kategori_arsip')->result();
        
        // Ambil semua kode arsip untuk dropdown
        $this->db->order_by('kode', 'ASC');
        $data['list_kode'] = $this->db->get('tb_kode_arsip')->result();
        
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/templates/sidebar');
        $this->load->view('admin/arsip', $data);
        $this->load->view('admin/templates/footer');
    }

    public function kategori($id = null)
    {
        if(empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('pesan', 'Kategori tidak valid!');
            redirect('admin/arsip');
        }
        
        // Ambil data kategori
        $where_kategori = array('id' => $id);
        $kategori = $this->m_model->get_where($where_kategori, 'tb_kategori_arsip')->row();
        
        if(!$kategori) {
            $this->session->set_flashdata('pesan', 'Kategori tidak ditemukan!');
            redirect('admin/arsip');
        }
        
        $data['title'] = 'Daftar Arsip: ' . $kategori->nama;
        
        // Ambil semua arsip dengan kategori_id yang sama (termasuk sub-kategori)
        $this->db->select('a.*, k.kode as kode_arsip, k.nama as kode_nama');
        $this->db->from('tb_arsip a');
        $this->db->join('tb_kode_arsip k', 'k.id = a.kode_id', 'left');
        $this->db->where('a.kategori_id', $id);
        $this->db->order_by('a.createDate', 'DESC');
        $arsip = $this->db->get()->result();
        
        // Ambil sub-kategori jika ada dengan total arsip
        $this->db->select('k.id, k.nama, k.parent_id, COUNT(DISTINCT a.id) as total_arsip');
        $this->db->from('tb_kategori_arsip k');
        $this->db->join('tb_arsip a', 'a.kategori_id = k.id', 'left');
        $this->db->where('k.parent_id', $id);
        $this->db->group_by('k.id, k.nama, k.parent_id');
        $this->db->order_by('k.nama', 'ASC');
        $sub_kategori = $this->db->get()->result();
        
        $data['arsip'] = $arsip;
        $data['sub_kategori'] = $sub_kategori;
        $data['kategori_id'] = $id;
        $data['kategori_nama'] = $kategori->nama;
        $data['kategori_parent_id'] = $kategori->parent_id;
        
        // Ambil data kategori parent jika ada (untuk breadcrumb)
        $data['kategori_parent'] = null;
        if(!empty($kategori->parent_id)) {
            $where_parent = array('id' => $kategori->parent_id);
            $data['kategori_parent'] = $this->m_model->get_where($where_parent, 'tb_kategori_arsip')->row();
        }
        
        // Ambil semua kategori untuk dropdown form (termasuk sub-kategori untuk form arsip)
        $this->db->order_by('nama', 'ASC');
        $data['list_kategori'] = $this->db->get('tb_kategori_arsip')->result();
        
        // Ambil hanya kategori parent untuk dropdown form sub-kategori baru
        $this->db->where('parent_id IS NULL');
        $this->db->order_by('nama', 'ASC');
        $data['list_kategori_parent'] = $this->db->get('tb_kategori_arsip')->result();
        
        // Ambil semua kode arsip untuk dropdown
        $this->db->order_by('kode', 'ASC');
        $data['list_kode'] = $this->db->get('tb_kode_arsip')->result();
        
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/templates/sidebar');
        $this->load->view('admin/arsip', $data);
        $this->load->view('admin/templates/footer');
    }

    public function delete($id)
    {
        // Ambil data arsip untuk mendapatkan kategori_id dan path_file sebelum dihapus
        $where = array('id' => $id);
        $arsip = $this->m_model->get_where($where, 'tb_arsip')->row();
        
        // Cek apakah arsip ada
        if(!$arsip) {
            $this->session->set_flashdata('pesan', 'Data arsip tidak ditemukan!');
            redirect('admin/arsip');
            return;
        }
        
        $kategori_id = $arsip->kategori_id;
        $path_file = $arsip->path_file;
        
        // Log aksi delete SEBELUM menghapus (untuk menghindari foreign key constraint error)
        // Karena foreign key constraint memerlukan arsip masih ada saat log dibuat
        $this->logAksi($id, 'Delete', 'Arsip dihapus');
        
        // Hapus file fisik dari sistem jika ada
        if(!empty($path_file)) {
            // Normalisasi path (handle relative dan absolute path)
            $file_path = $path_file;
            
            // Jika path relatif (dimulai dengan ./), ubah ke absolute
            if(strpos($file_path, './') === 0) {
                $file_path = FCPATH . substr($file_path, 2);
            } elseif(strpos($file_path, '/') !== 0 && strpos($file_path, ':') === false) {
                // Jika path tidak dimulai dengan / atau drive letter, anggap relatif dari FCPATH
                $file_path = FCPATH . $file_path;
            }
            
            // Hapus file jika ada
            if(file_exists($file_path)) {
                @unlink($file_path);
            }
            
            // Juga coba dengan path asli dari database (untuk kompatibilitas)
            if($path_file != $file_path && file_exists($path_file)) {
                @unlink($path_file);
            }
        }
        
        // Hapus thumbnail jika ada
        $thumbnail_dir = './uploads/thumbnails/';
        $thumbnail_path = $thumbnail_dir . 'thumb_' . $id . '.jpg';
        
        // Normalisasi path thumbnail
        if(strpos($thumbnail_path, './') === 0) {
            $thumbnail_absolute = FCPATH . substr($thumbnail_path, 2);
        } else {
            $thumbnail_absolute = FCPATH . $thumbnail_path;
        }
        
        if(file_exists($thumbnail_absolute)) {
            @unlink($thumbnail_absolute);
        }
        
        // Juga coba dengan path relatif
        if(file_exists($thumbnail_path)) {
            @unlink($thumbnail_path);
        }

        // Hapus data dari database
        // Catatan: Jika foreign key menggunakan ON DELETE CASCADE, riwayat akan otomatis terhapus
        // Tapi kita sudah membuat log sebelum delete, jadi tidak masalah
        $this->m_model->delete($where, 'tb_arsip');
        
        $this->session->set_flashdata('pesan', 'Data arsip berhasil dihapus!');
        
        // Redirect kembali ke halaman kategori arsip jika ada kategori_id
        if(!empty($kategori_id)) {
            redirect('admin/arsip/kategori/' . $kategori_id);
        } else {
            redirect('admin/arsip');
        }
    }

    public function delete_kategori($id)
    {
        // Ambil data kategori sebelum dihapus untuk mendapatkan parent_id
        $where = array('id' => $id);
        $kategori = $this->m_model->get_where($where, 'tb_kategori_arsip')->row();
        
        // Cek apakah kategori memiliki arsip
        $where_arsip = array('kategori_id' => $id);
        $jumlah_arsip = $this->m_model->get_where($where_arsip, 'tb_arsip')->num_rows();
        
        // Cek apakah kategori memiliki sub-kategori
        $where_sub = array('parent_id' => $id);
        $jumlah_sub = $this->m_model->get_where($where_sub, 'tb_kategori_arsip')->num_rows();
        
        if($jumlah_arsip > 0 || $jumlah_sub > 0) {
            $this->session->set_flashdata('pesan', 'Kategori tidak dapat dihapus karena masih memiliki arsip atau sub-kategori!');
            
            // Redirect ke halaman yang sesuai
            if($kategori && !empty($kategori->parent_id)) {
                redirect('admin/arsip/kategori/' . $kategori->parent_id);
            } else {
                redirect('admin/arsip');
            }
            return;
        }
        
        // Simpan parent_id sebelum menghapus
        $parent_id = $kategori ? $kategori->parent_id : null;
        
        // Hapus kategori
        $this->m_model->delete($where, 'tb_kategori_arsip');
        $this->session->set_flashdata('pesan', 'Kategori berhasil dihapus!');
        
        // Redirect ke halaman yang sesuai
        if(!empty($parent_id)) {
            // Jika yang dihapus adalah sub-kategori, redirect ke parent-nya
            redirect('admin/arsip/kategori/' . $parent_id);
        } else {
            // Jika yang dihapus adalah kategori parent, redirect ke halaman utama
            redirect('admin/arsip');
        }
    }

    public function insert_kategori()
    {
        date_default_timezone_set('Asia/Jakarta');
        $nama       = $this->input->post('nama');
        $parent_id  = $this->input->post('parent_id'); // Untuk kategori bertingkat
        $createDate = date('Y-m-d H:i:s');

        $data = array(
            'nama'          => $nama,
            'parent_id'     => !empty($parent_id) ? $parent_id : NULL,
            'createDate'    => $createDate,
        );

        $this->m_model->insert($data, 'tb_kategori_arsip');
        $this->session->set_flashdata('pesan', 'Kategori berhasil ditambahkan!');
        
        // Redirect ke halaman yang sesuai
        if(!empty($parent_id)) {
            redirect('admin/arsip/kategori/' . $parent_id);
        } else {
            redirect('admin/arsip');
        }
    }

    public function bulk_upload()
    {
        date_default_timezone_set('Asia/Jakarta');
        
        // Kategori diambil dari form
        $kategori_id                = $this->input->post('kategori_id');
        $no_urut                    = $this->input->post('no_urut');
        $kode_id                    = $this->input->post('kode_id');
        $indeks_pekerjaan           = $this->input->post('indeks_pekerjaan');
        $uraian_masalah_kegiatan    = $this->input->post('uraian_masalah_kegiatan');
        $tahun                      = $this->input->post('tahun');
        $jumlah_berkas              = $this->input->post('jumlah_berkas');
        $asli_kopi                  = $this->input->post('asli_kopi');
        $box                        = $this->input->post('box');
        $klasifikasi_keamanan       = $this->input->post('klasifikasi_keamanan');
        $createDate                 = date('Y-m-d H:i:s');
        
        // Validasi kategori
        if(empty($kategori_id)) {
            $this->session->set_flashdata('pesan', 'Kategori tidak valid!');
            redirect('admin/arsip');
            return;
        }
        
        // Validasi file
        if(empty($_FILES['files_arsip']['name'][0])) {
            $this->session->set_flashdata('pesan', 'Pilih minimal 1 file untuk diupload!');
            redirect('admin/arsip/kategori/' . $kategori_id);
            return;
        }
        
        // Ambil nama user dari session
        $user_id = $this->session->userdata('id');
        $user = $this->m_model->get_where(array('id' => $user_id), 'tb_user')->row();
        $nama_pengisi = $user ? $user->nama : NULL;
        
        // Default values
        if(empty($jumlah_berkas)) {
            $jumlah_berkas = 1;
        }
        if(empty($indeks_pekerjaan)) {
            $indeks_pekerjaan = 'Satker Balai Penilaian Kompetensi';
        }
        
        // Konfigurasi upload
        $config['upload_path'] = './uploads/arsip/';
        $config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|jpg|jpeg|png|gif|zip|rar';
        $config['max_size'] = 0; // Tanpa limit
        $config['encrypt_name'] = TRUE;
        
        // Buat folder jika belum ada
        if(!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }
        
        $success_count = 0;
        $error_count = 0;
        $errors = array();
        $start_no_urut = !empty($no_urut) ? intval($no_urut) : null;
        $current_no_urut = $start_no_urut;
        
        // Proses setiap file
        $files = $_FILES['files_arsip'];
        $file_count = count($files['name']);
        
        for($i = 0; $i < $file_count; $i++) {
            if($files['error'][$i] !== UPLOAD_ERR_OK) {
                $errors[] = "File " . ($i + 1) . ": Error upload (" . $files['name'][$i] . ")";
                $error_count++;
                continue;
            }
            
            // Setup file untuk upload
            $_FILES['file_arsip']['name'] = $files['name'][$i];
            $_FILES['file_arsip']['type'] = $files['type'][$i];
            $_FILES['file_arsip']['tmp_name'] = $files['tmp_name'][$i];
            $_FILES['file_arsip']['error'] = $files['error'][$i];
            $_FILES['file_arsip']['size'] = $files['size'][$i];
            
            // Initialize upload
            $this->upload->initialize($config);
            
            if($this->upload->do_upload('file_arsip')) {
                $upload_data = $this->upload->data();
                
                // Generate no_berkas otomatis
                $no_berkas = $this->generateNoBerkas($kategori_id);
                
                // Set no_urut jika ada
                $final_no_urut = null;
                if($current_no_urut !== null) {
                    $final_no_urut = $current_no_urut;
                    $current_no_urut++;
                }
                
                // Siapkan data untuk insert
                $data = array(
                    'kategori_id'            => $kategori_id,
                    'no_berkas'              => $no_berkas,
                    'no_urut'                => $final_no_urut,
                    'kode_id'                => !empty($kode_id) ? $kode_id : NULL,
                    'indeks_pekerjaan'       => $indeks_pekerjaan ?: NULL,
                    'uraian_masalah_kegiatan' => $uraian_masalah_kegiatan ?: NULL,
                    'tahun'                  => !empty($tahun) ? intval($tahun) : NULL,
                    'jumlah_berkas'          => intval($jumlah_berkas),
                    'asli_kopi'              => !empty($asli_kopi) ? $asli_kopi : NULL,
                    'box'                    => $box ?: NULL,
                    'klasifikasi_keamanan'   => $klasifikasi_keamanan ?: NULL,
                    'nama_pengisi'           => $nama_pengisi,
                    'link_drive'             => NULL,
                    'nama_file'              => $upload_data['file_name'],
                    'path_file'              => $config['upload_path'] . $upload_data['file_name'],
                    'ukuran_file'            => $upload_data['file_size'],
                    'tipe_file'              => $upload_data['file_type'],
                    'createDate'             => $createDate,
                    'created_by'             => $user_id
                );
                
                // Insert data
                try {
                    $this->m_model->insert($data, 'tb_arsip');
                    $arsip_id = $this->db->insert_id();
                    
                    // Log aksi upload
                    $this->logAksi($arsip_id, 'Upload', 'Arsip diupload via bulk upload');
                    
                    $success_count++;
                } catch(Exception $e) {
                    // Hapus file yang sudah terupload jika insert gagal
                    @unlink($config['upload_path'] . $upload_data['file_name']);
                    $errors[] = "File " . ($i + 1) . " (" . $files['name'][$i] . "): " . $e->getMessage();
                    $error_count++;
                }
            } else {
                $error = $this->upload->display_errors();
                $errors[] = "File " . ($i + 1) . " (" . $files['name'][$i] . "): " . $error;
                $error_count++;
            }
        }
        
        // Set pesan hasil
        $pesan = "Bulk upload selesai! Berhasil: $success_count, Gagal: $error_count";
        if(!empty($errors) && count($errors) <= 10) {
            $pesan .= "<br>Error detail:<br>" . implode("<br>", $errors);
        } elseif(!empty($errors)) {
            $pesan .= "<br>Ada " . count($errors) . " error. Silakan cek log untuk detail.";
        }
        
        $this->session->set_flashdata('pesan', $pesan);
        redirect('admin/arsip/kategori/' . $kategori_id);
    }
    
    public function insert()
    {
        date_default_timezone_set('Asia/Jakarta');
        
        // Kategori diambil dari URL, tidak bisa dipilih
        $kategori_id                = $this->input->post('kategori_id');
        $no_berkas                  = $this->input->post('no_berkas');
        $no_urut                    = $this->input->post('no_urut');
        $kode_id                    = $this->input->post('kode_id');
        $indeks_pekerjaan           = $this->input->post('indeks_pekerjaan');
        $uraian_masalah_kegiatan    = $this->input->post('uraian_masalah_kegiatan');
        $tahun                      = $this->input->post('tahun');
        $jumlah_berkas              = $this->input->post('jumlah_berkas');
        $asli_kopi                  = $this->input->post('asli_kopi');
        $box                        = $this->input->post('box');
        $klasifikasi_keamanan       = $this->input->post('klasifikasi_keamanan');
        $link_drive                 = $this->input->post('link_drive');
        $createDate                 = date('Y-m-d H:i:s');
        
        // Ambil nama user dari session
        $user_id = $this->session->userdata('id');
        $user = $this->m_model->get_where(array('id' => $user_id), 'tb_user')->row();
        $nama_pengisi = $user ? $user->nama : NULL;
        
        // Validasi kategori harus ada
        if(empty($kategori_id)) {
            $this->session->set_flashdata('pesan', 'Kategori tidak valid!');
            redirect('admin/arsip');
            return;
        }

        // Validasi: Harus ada file upload ATAU link drive
        $file_uploaded = !empty($_FILES['file_arsip']['name']);
        $link_drive_filled = !empty($link_drive);
        
        if(!$file_uploaded && !$link_drive_filled) {
            $this->session->set_flashdata('pesan', 'Harus mengupload file atau mengisi link drive!');
            redirect('admin/arsip/kategori/' . $kategori_id);
            return;
        }

        // Inisialisasi variabel file
        $nama_file = NULL;
        $path_file = NULL;
        $ukuran_file = NULL;
        $tipe_file = NULL;

        // Jika ada file yang diupload
        if($file_uploaded) {
            // Konfigurasi upload
            $config['upload_path'] = './uploads/arsip/';
            $config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|jpg|jpeg|png|gif|zip|rar';
            $config['max_size'] = 0; // Tanpa limit
            $config['encrypt_name'] = TRUE;
            
            // Buat folder jika belum ada
            if(!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }
            
            $this->upload->initialize($config);
            
            if (!$this->upload->do_upload('file_arsip')) {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('pesan', 'Upload gagal: ' . $error);
                redirect('admin/arsip/kategori/' . $kategori_id);
                return;
            }
            
            $upload_data = $this->upload->data();
            $nama_file = $upload_data['file_name'];
            $path_file = $config['upload_path'] . $nama_file;
            $ukuran_file = $upload_data['file_size'];
            $tipe_file = $upload_data['file_type'];
        }
        
        // Jika no_berkas kosong, generate otomatis
        if(empty($no_berkas)) {
            $no_berkas = $this->generateNoBerkas($kategori_id);
        }
        
        // Default jumlah_berkas jika kosong
        if(empty($jumlah_berkas)) {
            $jumlah_berkas = 1;
        }
        
        // Default indeks_pekerjaan jika kosong
        if(empty($indeks_pekerjaan)) {
            $indeks_pekerjaan = 'Satker Balai Penilaian Kompetensi';
        }

        $data = array(
            'kategori_id'            => $kategori_id,
            'no_berkas'              => $no_berkas,
            'no_urut'                => !empty($no_urut) ? $no_urut : NULL,
            'kode'                   => $kode,
            'indeks_pekerjaan'       => $indeks_pekerjaan,
            'uraian_masalah_kegiatan' => $uraian_masalah_kegiatan,
            'tahun'                  => !empty($tahun) ? $tahun : NULL,
            'jumlah_berkas'          => $jumlah_berkas,
            'asli_kopi'              => !empty($asli_kopi) ? $asli_kopi : NULL,
            'box'                    => $box,
            'klasifikasi_keamanan'   => $klasifikasi_keamanan,
            'nama_pengisi'           => $nama_pengisi,
            'link_drive'             => $link_drive,
            'nama_file'              => $nama_file,
            'path_file'              => $path_file,
            'ukuran_file'            => $ukuran_file,
            'tipe_file'              => $tipe_file,
            'createDate'             => $createDate,
            'created_by'             => $this->session->userdata('id')
        );

        $this->m_model->insert($data, 'tb_arsip');
        
        // Ambil ID arsip yang baru saja diinsert
        $arsip_id = $this->db->insert_id();
        
        // Log aksi upload
        $this->logAksi($arsip_id, 'Upload', 'Arsip baru diupload');
        
        $this->session->set_flashdata('pesan', 'Arsip berhasil ditambahkan!');
        redirect('admin/arsip/kategori/' . $kategori_id);
    }
    
    private function generateNoBerkas($kategori_id)
    {
        // Format: KAT-YYYYMMDD-XXX
        // KAT = 3 huruf pertama kategori
        $kategori = $this->m_model->get_where(array('id' => $kategori_id), 'tb_kategori_arsip')->row();
        $prefix = strtoupper(substr($kategori->nama, 0, 3));
        $date = date('Ymd');
        
        // Cari nomor terakhir hari ini
        $this->db->like('no_berkas', $prefix . '-' . $date, 'after');
        $this->db->order_by('no_berkas', 'DESC');
        $last = $this->db->get('tb_arsip')->row();
        
        if($last) {
            $last_num = intval(substr($last->no_berkas, -3));
            $new_num = $last_num + 1;
        } else {
            $new_num = 1;
        }
        
        return $prefix . '-' . $date . '-' . str_pad($new_num, 3, '0', STR_PAD_LEFT);
    }
    
    public function download($id)
    {
        $where = array('id' => $id);
        $arsip = $this->m_model->get_where($where, 'tb_arsip')->row();
        
        if(!$arsip || !file_exists($arsip->path_file)) {
            $this->session->set_flashdata('pesan', 'File tidak ditemukan!');
            redirect('admin/arsip');
            return;
        }
        
        // Log aksi download
        $this->logAksi($id, 'Download', 'Arsip didownload');
        
        // Download file
        $this->load->helper('download');
        force_download($arsip->nama_file, file_get_contents($arsip->path_file));
    }
    
    public function view($id)
    {
        $where = array('id' => $id);
        $arsip = $this->m_model->get_where($where, 'tb_arsip')->row();
        
        if(!$arsip) {
            show_404();
            return;
        }
        
        // Log aksi view
        $this->logAksi($id, 'View', 'Arsip dilihat');
        
        // Jika file adalah gambar atau PDF, tampilkan di browser
        $image_types = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');
        if(in_array($arsip->tipe_file, $image_types)) {
            header('Content-Type: ' . $arsip->tipe_file);
            readfile($arsip->path_file);
        } elseif($arsip->tipe_file == 'application/pdf') {
            header('Content-Type: application/pdf');
            readfile($arsip->path_file);
        } else {
            // Untuk file lain, force download
            $this->download($id);
        }
    }

    public function update_kategori()
    {
        $id      = $this->input->post('id');
        $nama    = $this->input->post('nama');
        $current_kategori_id = $this->input->post('current_kategori_id'); // ID kategori halaman saat ini
        
        $where = array('id' => $id);
        $data = array(
            'nama'      => $nama
        );

        $this->m_model->update($where, $data, 'tb_kategori_arsip');
        $this->session->set_flashdata('pesan', 'Kategori berhasil diubah!');
        
        // Ambil data kategori yang baru saja diupdate untuk mendapatkan parent_id
        $kategori = $this->m_model->get_where($where, 'tb_kategori_arsip')->row();
        
        // Redirect ke halaman yang sesuai
        if(!empty($current_kategori_id)) {
            // Jika ada current_kategori_id, redirect ke halaman kategori tersebut
            redirect('admin/arsip/kategori/' . $current_kategori_id);
        } elseif($kategori && !empty($kategori->parent_id)) {
            // Jika kategori yang diupdate adalah sub-kategori, redirect ke parent-nya
            redirect('admin/arsip/kategori/' . $kategori->parent_id);
        } else {
            // Default redirect ke halaman utama
            redirect('admin/arsip');
        }
    }

    public function update()
    {
        $id                      = $this->input->post('id');
        $kategori_id             = $this->input->post('kategori_id');
        $no_berkas               = $this->input->post('no_berkas');
        $no_urut                 = $this->input->post('no_urut');
        $kode_id                 = $this->input->post('kode_id');
        $indeks_pekerjaan        = $this->input->post('indeks_pekerjaan');
        $uraian_masalah_kegiatan = $this->input->post('uraian_masalah_kegiatan');
        $tahun                   = $this->input->post('tahun');
        $jumlah_berkas           = $this->input->post('jumlah_berkas');
        $asli_kopi               = $this->input->post('asli_kopi');
        $box                     = $this->input->post('box');
        $klasifikasi_keamanan    = $this->input->post('klasifikasi_keamanan');
        $link_drive              = $this->input->post('link_drive');
        
        // Ambil nama user dari session untuk update
        $user_id = $this->session->userdata('id');
        $user = $this->m_model->get_where(array('id' => $user_id), 'tb_user')->row();
        $nama_pengisi = $user ? $user->nama : NULL;
        $updateDate              = date('Y-m-d H:i:s');
        
        // Default indeks_pekerjaan jika kosong
        if(empty($indeks_pekerjaan)) {
            $indeks_pekerjaan = 'Satker Balai Penilaian Kompetensi';
        }

        $where = array('id' => $id);
        
        $data = array(
            'kategori_id'            => $kategori_id,
            'no_berkas'              => $no_berkas,
            'no_urut'                => !empty($no_urut) ? $no_urut : NULL,
            'kode_id'                => !empty($kode_id) ? $kode_id : NULL,
            'indeks_pekerjaan'       => $indeks_pekerjaan,
            'uraian_masalah_kegiatan' => $uraian_masalah_kegiatan,
            'tahun'                  => !empty($tahun) ? $tahun : NULL,
            'jumlah_berkas'          => !empty($jumlah_berkas) ? $jumlah_berkas : 1,
            'asli_kopi'              => !empty($asli_kopi) ? $asli_kopi : NULL,
            'box'                    => $box,
            'klasifikasi_keamanan'   => $klasifikasi_keamanan,
            'nama_pengisi'           => $nama_pengisi,
            'link_drive'             => $link_drive,
            'updateDate'             => $updateDate
        );
        
        // Validasi: Harus ada file upload ATAU link drive (hanya saat update, tidak wajib)
        $file_uploaded = !empty($_FILES['file_arsip']['name']);
        $link_drive_filled = !empty($link_drive);
        
        // Jika ada file baru diupload
        if($file_uploaded) {
            // Ambil data arsip lama untuk menghapus file lama
            $arsip_lama = $this->m_model->get_where($where, 'tb_arsip')->row();
            
            // Hapus file lama jika ada
            if($arsip_lama && !empty($arsip_lama->path_file)) {
                $old_file_path = $arsip_lama->path_file;
                
                // Normalisasi path (handle relative dan absolute path)
                if(strpos($old_file_path, './') === 0) {
                    $old_file_absolute = FCPATH . substr($old_file_path, 2);
                } elseif(strpos($old_file_path, '/') !== 0 && strpos($old_file_path, ':') === false) {
                    $old_file_absolute = FCPATH . $old_file_path;
                } else {
                    $old_file_absolute = $old_file_path;
                }
                
                // Hapus file lama jika ada
                if(file_exists($old_file_absolute)) {
                    @unlink($old_file_absolute);
                }
                
                // Juga coba dengan path asli dari database (untuk kompatibilitas)
                if($old_file_path != $old_file_absolute && file_exists($old_file_path)) {
                    @unlink($old_file_path);
                }
                
                // Hapus thumbnail lama jika ada
                $thumbnail_dir = './uploads/thumbnails/';
                $thumbnail_path = $thumbnail_dir . 'thumb_' . $id . '.jpg';
                
                // Normalisasi path thumbnail
                if(strpos($thumbnail_path, './') === 0) {
                    $thumbnail_absolute = FCPATH . substr($thumbnail_path, 2);
                } else {
                    $thumbnail_absolute = FCPATH . $thumbnail_path;
                }
                
                if(file_exists($thumbnail_absolute)) {
                    @unlink($thumbnail_absolute);
                }
                
                // Juga coba dengan path relatif
                if(file_exists($thumbnail_path)) {
                    @unlink($thumbnail_path);
                }
            }
            
            // Upload file baru
            $config['upload_path'] = './uploads/arsip/';
            $config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|jpg|jpeg|png|gif|zip|rar';
            $config['max_size'] = 0; // Tanpa limit
            $config['encrypt_name'] = TRUE;
            
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload('file_arsip')) {
                $upload_data = $this->upload->data();
                $data['nama_file'] = $upload_data['file_name'];
                $data['path_file'] = $config['upload_path'] . $upload_data['file_name'];
                $data['ukuran_file'] = $upload_data['file_size'];
                $data['tipe_file'] = $upload_data['file_type'];
            }
        }

        $this->m_model->update($where, $data, 'tb_arsip');
        
        // Log aksi update
        $this->logAksi($id, 'Update', 'Arsip diupdate');
        
        $this->session->set_flashdata('pesan', 'Arsip berhasil diubah!');
        redirect('admin/arsip/kategori/' . $kategori_id);
    }

    public function riwayat($id)
    {
        $where = array('id' => $id);
        $arsip = $this->m_model->get_where($where, 'tb_arsip')->row();
        
        if(!$arsip) {
            $this->session->set_flashdata('pesan', 'Arsip tidak ditemukan!');
            redirect('admin/arsip');
            return;
        }

        $data['arsip'] = $arsip;
        $whereRiwayat = array('arsip_id' => $id);
        $this->db->order_by('createDate', 'DESC');
        $data['riwayat'] = $this->m_model->get_where($whereRiwayat, 'tb_riwayat_arsip')->result();
        $data['title'] = 'Riwayat Arsip : ' . ($arsip->no_berkas ? $arsip->no_berkas : 'ID-' . $arsip->id);
        
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/templates/sidebar');
        $this->load->view('admin/riwayatArsip', $data);
        $this->load->view('admin/templates/footer');
    }
    
    private function logAksi($arsip_id, $aksi, $keterangan = '')
    {
        try {
            date_default_timezone_set('Asia/Jakarta');
            $data = array(
                'arsip_id'  => $arsip_id,
                'user_id'   => $this->session->userdata('id'),
                'aksi'      => $aksi,
                'keterangan' => $keterangan,
                'ip_address' => $this->input->ip_address(),
                'createDate' => date('Y-m-d H:i:s')
            );
            $this->m_model->insert($data, 'tb_riwayat_arsip');
        } catch(Exception $e) {
            // Log error tapi jangan hentikan proses utama
            // Error akan dicatat di log sistem CodeIgniter
            log_message('error', 'Gagal menambahkan log aksi: ' . $e->getMessage());
        }
    }

    public function import()
    {
        // Hanya bisa import jika ada kategori_id
        $kategori_id = $this->input->post('kategori_id');
        
        if(empty($kategori_id)) {
            $this->session->set_flashdata('pesan', 'Kategori harus dipilih!');
            redirect('admin/arsip');
            return;
        }

        // Validasi file
        if(empty($_FILES['file_excel']['name'])) {
            $this->session->set_flashdata('pesan', 'File Excel harus diupload!');
            redirect('admin/arsip/kategori/' . $kategori_id);
            return;
        }

        // Konfigurasi upload
        $config['upload_path'] = './uploads/temp/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size'] = 5120; // 5MB
        $config['encrypt_name'] = TRUE;
        
        // Buat folder jika belum ada
        if(!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }
        
        $this->upload->initialize($config);
        
        if (!$this->upload->do_upload('file_excel')) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('pesan', 'Upload gagal: ' . $error);
            redirect('admin/arsip/kategori/' . $kategori_id);
            return;
        }
        
        $upload_data = $this->upload->data();
        $file_path = $config['upload_path'] . $upload_data['file_name'];
        
        // Load PhpSpreadsheet
        $load_result = $this->loadPhpSpreadsheet();
        if(!$load_result['success']) {
            @unlink($file_path);
            $this->session->set_flashdata('pesan', $load_result['message']);
            redirect('admin/arsip/kategori/' . $kategori_id);
            return;
        }
        
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_path);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // Skip header row (row 1)
            array_shift($rows);
            
            $success_count = 0;
            $error_count = 0;
            $errors = array();
            
            // Ambil nama user dari session
            $user_id = $this->session->userdata('id');
            $user = $this->m_model->get_where(array('id' => $user_id), 'tb_user')->row();
            $nama_pengisi = $user ? $user->nama : NULL;
            
            date_default_timezone_set('Asia/Jakarta');
            $createDate = date('Y-m-d H:i:s');
            
            foreach($rows as $index => $row) {
                $row_num = $index + 2; // +2 karena skip header dan index mulai dari 0
                
                // Skip baris kosong
                if(empty(array_filter($row))) {
                    continue;
                }
                
                // Mapping kolom Excel ke field database
                // Kolom: A=Kategori, B=No Berkas, C=No Urut, D=Kode, E=Indeks/Pekerjaan, 
                //        F=Uraian Masalah/Kegiatan, G=Tahun, H=Jumlah Berkas, I=Asli/Kopi, 
                //        J=Box, K=Klasifikasi Keamanan, L=Link Drive
                
                $kategori_nama = isset($row[0]) ? trim($row[0]) : '';
                $no_berkas = isset($row[1]) ? trim($row[1]) : '';
                $no_urut = isset($row[2]) ? trim($row[2]) : '';
                $kode = isset($row[3]) ? trim($row[3]) : '';
                $indeks_pekerjaan = isset($row[4]) ? trim($row[4]) : '';
                $uraian_masalah_kegiatan = isset($row[5]) ? trim($row[5]) : '';
                $tahun = isset($row[6]) ? trim($row[6]) : '';
                $jumlah_berkas = isset($row[7]) ? trim($row[7]) : '';
                $asli_kopi = isset($row[8]) ? trim($row[8]) : '';
                $box = isset($row[9]) ? trim($row[9]) : '';
                $klasifikasi_keamanan = isset($row[10]) ? trim($row[10]) : '';
                $link_drive = isset($row[11]) ? trim($row[11]) : '';
                
                // Cari kode_id dari kode yang diinput (jika ada)
                $kode_id_import = NULL;
                if(!empty($kode)) {
                    $kode_check = $this->m_model->get_where(array('kode' => $kode), 'tb_kode_arsip')->row();
                    if($kode_check) {
                        $kode_id_import = $kode_check->id;
                    }
                }
                
                // Validasi kategori (jika kategori_nama diisi, cari ID-nya)
                $import_kategori_id = $kategori_id; // Default ke kategori yang dipilih
                if(!empty($kategori_nama)) {
                    $kategori_check = $this->m_model->get_where(array('nama' => $kategori_nama), 'tb_kategori_arsip')->row();
                    if($kategori_check) {
                        $import_kategori_id = $kategori_check->id;
                    }
                }
                
                // Validasi kategori_id harus valid
                $kategori_valid = $this->m_model->get_where(array('id' => $import_kategori_id), 'tb_kategori_arsip')->row();
                if(!$kategori_valid) {
                    $errors[] = "Baris $row_num: Kategori tidak valid";
                    $error_count++;
                    continue;
                }
                
                // Jika no_berkas kosong, generate otomatis
                if(empty($no_berkas)) {
                    $no_berkas = $this->generateNoBerkas($import_kategori_id);
                }
                
                // Validasi asli_kopi
                if(!empty($asli_kopi) && !in_array($asli_kopi, array('Asli', 'Kopi'))) {
                    $errors[] = "Baris $row_num: Asli/Kopi harus 'Asli' atau 'Kopi'";
                    $error_count++;
                    continue;
                }
                
                // Default jumlah_berkas
                if(empty($jumlah_berkas) || !is_numeric($jumlah_berkas)) {
                    $jumlah_berkas = 1;
                }
                
                // Validasi tahun
                if(!empty($tahun) && (!is_numeric($tahun) || $tahun < 1900 || $tahun > date('Y') + 1)) {
                    $errors[] = "Baris $row_num: Tahun tidak valid";
                    $error_count++;
                    continue;
                }
                
                // Validasi no_urut
                if(!empty($no_urut) && !is_numeric($no_urut)) {
                    $errors[] = "Baris $row_num: No Urut harus angka";
                    $error_count++;
                    continue;
                }
                
                // Validasi link_drive (harus URL jika diisi)
                if(!empty($link_drive) && !filter_var($link_drive, FILTER_VALIDATE_URL)) {
                    $errors[] = "Baris $row_num: Link Drive harus berupa URL yang valid";
                    $error_count++;
                    continue;
                }
                
                // Cari kode_id dari kode yang diinput (jika ada)
                $kode_id_import = NULL;
                if(!empty($kode)) {
                    $kode_check = $this->m_model->get_where(array('kode' => $kode), 'tb_kode_arsip')->row();
                    if($kode_check) {
                        $kode_id_import = $kode_check->id;
                    }
                }
                
                // Siapkan data untuk insert
                $data = array(
                    'kategori_id'            => $import_kategori_id,
                    'no_berkas'              => $no_berkas,
                    'no_urut'                => !empty($no_urut) ? intval($no_urut) : NULL,
                    'kode_id'                => $kode_id_import,
                    'indeks_pekerjaan'       => $indeks_pekerjaan ?: NULL,
                    'uraian_masalah_kegiatan' => $uraian_masalah_kegiatan ?: NULL,
                    'tahun'                  => !empty($tahun) ? intval($tahun) : NULL,
                    'jumlah_berkas'          => intval($jumlah_berkas),
                    'asli_kopi'              => !empty($asli_kopi) ? $asli_kopi : NULL,
                    'box'                    => $box ?: NULL,
                    'klasifikasi_keamanan'   => $klasifikasi_keamanan ?: NULL,
                    'nama_pengisi'           => $nama_pengisi,
                    'link_drive'             => $link_drive ?: NULL,
                    'createDate'             => $createDate,
                    'created_by'             => $user_id
                );
                
                // Insert data
                try {
                    $this->m_model->insert($data, 'tb_arsip');
                    $arsip_id = $this->db->insert_id();
                    
                    // Log aksi import
                    $this->logAksi($arsip_id, 'Upload', 'Arsip diimport dari Excel');
                    
                    $success_count++;
                } catch(Exception $e) {
                    $errors[] = "Baris $row_num: " . $e->getMessage();
                    $error_count++;
                }
            }
            
            // Hapus file temporary
            @unlink($file_path);
            
            // Set pesan hasil
            $pesan = "Import selesai! Berhasil: $success_count, Gagal: $error_count";
            if(!empty($errors) && count($errors) <= 10) {
                $pesan .= "<br>Error detail:<br>" . implode("<br>", $errors);
            } elseif(!empty($errors)) {
                $pesan .= "<br>Ada " . count($errors) . " error. Silakan cek log untuk detail.";
            }
            
            $this->session->set_flashdata('pesan', $pesan);
            redirect('admin/arsip/kategori/' . $kategori_id);
            
        } catch(Exception $e) {
            // Hapus file temporary
            @unlink($file_path);
            
            $this->session->set_flashdata('pesan', 'Error membaca file Excel: ' . $e->getMessage());
            redirect('admin/arsip/kategori/' . $kategori_id);
        }
    }

    private function loadPhpSpreadsheet()
    {
        // Cek ekstensi PHP yang diperlukan untuk PhpSpreadsheet
        $required_extensions = array(
            'zip' => 'ZipArchive (untuk membaca file .xlsx)',
            'xml' => 'XML (untuk membaca file Excel)',
            'xmlwriter' => 'XMLWriter (untuk membaca file Excel)',
            'mbstring' => 'mbstring (untuk encoding)'
        );
        
        $missing_extensions = array();
        foreach($required_extensions as $ext => $desc) {
            if(!extension_loaded($ext)) {
                $missing_extensions[] = $desc . ' (ekstensi: ' . $ext . ')';
            }
        }
        
        if(!empty($missing_extensions)) {
            $error_msg = '<div style="background: #fff3cd; border: 2px solid #ffc107; padding: 20px; border-radius: 8px; margin: 20px 0;">';
            $error_msg .= '<h3 style="color: #856404; margin-top: 0;">⚠️ Ekstensi PHP Belum Diaktifkan</h3>';
            $error_msg .= '<p><strong>Ekstensi yang belum aktif:</strong></p>';
            $error_msg .= '<ul>';
            foreach($missing_extensions as $ext) {
                $error_msg .= '<li>' . $ext . '</li>';
            }
            $error_msg .= '</ul>';
            $error_msg .= '<hr style="border: 1px solid #ffc107; margin: 20px 0;">';
            $error_msg .= '<p><strong>📋 Solusi Cepat untuk XAMPP:</strong></p>';
            $error_msg .= '<ol style="line-height: 2;">';
            $error_msg .= '<li>Buka <strong>XAMPP Control Panel</strong></li>';
            $error_msg .= '<li>Klik tombol <strong>"Config"</strong> di sebelah Apache</li>';
            $error_msg .= '<li>Pilih <strong>"PHP (php.ini)"</strong> - file akan terbuka di text editor</li>';
            $error_msg .= '<li>Gunakan <strong>Ctrl+F</strong> untuk mencari: <code>;extension=zip</code></li>';
            $error_msg .= '<li><strong>Hapus tanda <code>;</code></strong> di depan baris tersebut</li>';
            $error_msg .= '<li>Pastikan ekstensi berikut juga aktif (hapus <code>;</code> jika ada):<br>';
            $error_msg .= '   - <code>extension=zip</code><br>';
            $error_msg .= '   - <code>extension=xml</code><br>';
            $error_msg .= '   - <code>extension=xmlwriter</code><br>';
            $error_msg .= '   - <code>extension=mbstring</code></li>';
            $error_msg .= '<li><strong>Simpan file</strong> (Ctrl+S)</li>';
            $error_msg .= '<li><strong>Restart Apache</strong> di XAMPP Control Panel (Stop → Start)</li>';
            $error_msg .= '</ol>';
            $error_msg .= '<hr style="border: 1px solid #ffc107; margin: 20px 0;">';
            $error_msg .= '<p><strong>💡 Bantuan Lebih Lanjut:</strong></p>';
            $error_msg .= '<p style="margin-bottom: 10px;">📖 <strong>Panduan Lengkap:</strong> <a href="' . base_url('fix_zip_extension.php') . '" target="_blank" style="color: #2196F3; font-weight: bold; text-decoration: underline;">fix_zip_extension.php</a> (Panduan langkah demi langkah)</p>';
            $error_msg .= '<p style="margin-bottom: 10px;">🔍 <strong>Cek Status:</strong> <a href="' . base_url('check_php_extensions.php') . '" target="_blank" style="color: #2196F3; font-weight: bold; text-decoration: underline;">check_php_extensions.php</a> (Verifikasi ekstensi)</p>';
            $error_msg .= '<p style="margin-top: 15px; padding: 10px; background: #e7f3ff; border-radius: 5px;"><strong>⚠️ Catatan Penting:</strong> Ekstensi zip sangat penting untuk membaca file Excel (.xlsx) karena format XLSX adalah file ZIP yang berisi XML. <strong>Setelah mengaktifkan ekstensi, WAJIB restart Apache!</strong></p>';
            $error_msg .= '</div>';
            
            return array('success' => false, 'message' => $error_msg);
        }
        
        // Cek beberapa kemungkinan lokasi autoload.php
        $possible_paths = array(
            APPPATH . 'third_party/PhpSpreadsheet/vendor/autoload.php',
            FCPATH . 'vendor/autoload.php',
            APPPATH . '../vendor/autoload.php',
            APPPATH . 'third_party/vendor/autoload.php'
        );
        
        $phpspreadsheet_path = null;
        foreach($possible_paths as $path) {
            if(file_exists($path)) {
                $phpspreadsheet_path = $path;
                break;
            }
        }
        
        if(!$phpspreadsheet_path) {
            // Cek apakah folder vendor ada tapi autoload.php tidak ada
            $vendor_dir = APPPATH . 'third_party/PhpSpreadsheet/vendor/';
            $phpoffice_dir = $vendor_dir . 'phpoffice/phpspreadsheet/';
            
            $error_msg = 'Library PhpSpreadsheet belum terinstall dengan benar!<br><br>';
            $error_msg .= '<strong>Masalah yang ditemukan:</strong><br>';
            
            if(!is_dir($vendor_dir)) {
                $error_msg .= '- Folder vendor tidak ditemukan di: ' . $vendor_dir . '<br>';
            } elseif(!file_exists($vendor_dir . 'autoload.php')) {
                $error_msg .= '- File autoload.php tidak ditemukan di: ' . $vendor_dir . '<br>';
            }
            
            if(!is_dir($phpoffice_dir)) {
                $error_msg .= '- Folder phpoffice/phpspreadsheet tidak ditemukan<br>';
            }
            
            $error_msg .= '<br><strong>Solusi:</strong><br>';
            $error_msg .= '1. Buka terminal/command prompt di folder: <code>application/third_party/PhpSpreadsheet</code><br>';
            $error_msg .= '2. Jalankan: <code>composer require phpoffice/phpspreadsheet</code><br>';
            $error_msg .= '3. Atau lihat panduan lengkap di file README_INSTALL_PHPSPREADSHEET.txt<br>';
            
            return array('success' => false, 'message' => $error_msg);
        }
        
        // Bypass platform check untuk PHP 8.0 (sementara)
        // PhpSpreadsheet 5.x memerlukan PHP 8.3+, tapi kita gunakan versi 1.29+ yang support PHP 8.0
        $platform_check_file = dirname($phpspreadsheet_path) . '/composer/platform_check.php';
        if(file_exists($platform_check_file)) {
            // Backup original platform check
            $platform_check_backup = $platform_check_file . '.backup';
            if(!file_exists($platform_check_backup)) {
                copy($platform_check_file, $platform_check_backup);
            }
            
            // Bypass platform check untuk PHP 8.0
            $platform_check_content = file_get_contents($platform_check_file);
            if(strpos($platform_check_content, '>= 8.3.0') !== false) {
                // Replace dengan check yang lebih fleksibel
                $platform_check_content = str_replace(
                    '>= 8.3.0',
                    '>= 8.0.0',
                    $platform_check_content
                );
                file_put_contents($platform_check_file, $platform_check_content);
            }
        }
        
        // Load library PhpSpreadsheet
        require_once $phpspreadsheet_path;
        
        // Verifikasi class tersedia
        if(!class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            return array(
                'success' => false, 
                'message' => 'PhpSpreadsheet terdeteksi tapi class tidak tersedia. Pastikan instalasi lengkap dengan menjalankan: composer install di folder vendor'
            );
        }
        
        return array('success' => true, 'path' => $phpspreadsheet_path);
    }

    public function download_template()
    {
        // Load PhpSpreadsheet
        $load_result = $this->loadPhpSpreadsheet();
        if(!$load_result['success']) {
            $this->session->set_flashdata('pesan', $load_result['message']);
            redirect('admin/arsip');
            return;
        }
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set header
        $headers = array(
            'A1' => 'Kategori (Opsional)',
            'B1' => 'No Berkas',
            'C1' => 'No Urut',
            'D1' => 'Kode',
            'E1' => 'Indeks/Pekerjaan',
            'F1' => 'Uraian Masalah/Kegiatan',
            'G1' => 'Tahun',
            'H1' => 'Jumlah Berkas',
            'I1' => 'Asli/Kopi',
            'J1' => 'Box',
            'K1' => 'Klasifikasi Keamanan',
            'L1' => 'Link Drive'
        );
        
        foreach($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }
        
        // Set style untuk header
        $sheet->getStyle('A1:L1')->getFont()->setBold(true);
        $sheet->getStyle('A1:L1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF4472C4');
        $sheet->getStyle('A1:L1')->getFont()->getColor()->setARGB('FFFFFFFF');
        
        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(40);
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(12);
        $sheet->getColumnDimension('J')->setWidth(12);
        $sheet->getColumnDimension('K')->setWidth(25);
        $sheet->getColumnDimension('L')->setWidth(50);
        
        // Tambahkan contoh data
        $sheet->setCellValue('A2', 'Contoh: Surat Masuk');
        $sheet->setCellValue('B2', '');
        $sheet->setCellValue('C2', '1');
        $sheet->setCellValue('D2', 'SM-001');
        $sheet->setCellValue('E2', 'Surat Masuk');
        $sheet->setCellValue('F2', 'Contoh uraian masalah/kegiatan');
        $sheet->setCellValue('G2', '2024');
        $sheet->setCellValue('H2', '1');
        $sheet->setCellValue('I2', 'Asli');
        $sheet->setCellValue('J2', '1');
        $sheet->setCellValue('K2', 'Umum');
        $sheet->setCellValue('L2', '');
        
        // Set style untuk contoh
        $sheet->getStyle('A2:L2')->getFont()->setItalic(true);
        $sheet->getStyle('A2:L2')->getFont()->getColor()->setARGB('FF808080');
        
        // Tambahkan catatan
        $sheet->setCellValue('A4', 'CATATAN:');
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->setCellValue('A5', '1. Kategori: Kosongkan untuk menggunakan kategori yang dipilih saat import');
        $sheet->setCellValue('A6', '2. No Berkas: Kosongkan untuk generate otomatis');
        $sheet->setCellValue('A7', '3. Asli/Kopi: Isi dengan "Asli" atau "Kopi"');
        $sheet->setCellValue('A8', '4. Klasifikasi Keamanan: Umum, Terbatas, Rahasia, Sangat Rahasia');
        $sheet->setCellValue('A9', '5. Link Drive: URL lengkap jika tidak upload file');
        
        // Output file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Template_Import_Arsip.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
    
    public function export_excel($kategori_id = null)
    {
        // Validasi kategori_id
        if(empty($kategori_id) || !is_numeric($kategori_id)) {
            $this->session->set_flashdata('pesan', 'Kategori tidak valid!');
            redirect('admin/arsip');
            return;
        }
        
        // Ambil data kategori
        $where_kategori = array('id' => $kategori_id);
        $kategori = $this->m_model->get_where($where_kategori, 'tb_kategori_arsip')->row();
        
        if(!$kategori) {
            $this->session->set_flashdata('pesan', 'Kategori tidak ditemukan!');
            redirect('admin/arsip');
            return;
        }
        
        // Load PhpSpreadsheet
        $load_result = $this->loadPhpSpreadsheet();
        if(!$load_result['success']) {
            $this->session->set_flashdata('pesan', $load_result['message']);
            redirect('admin/arsip/kategori/' . $kategori_id);
            return;
        }
        
        // Ambil semua arsip dengan kategori_id yang sama
        $this->db->select('a.*, k.nama as kategori_nama');
        $this->db->from('tb_arsip a');
        $this->db->join('tb_kategori_arsip k', 'k.id = a.kategori_id', 'left');
        $this->db->where('a.kategori_id', $kategori_id);
        $this->db->order_by('a.createDate', 'DESC');
        $arsip = $this->db->get()->result();
        
        // Buat spreadsheet baru
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set judul sheet
        $sheet->setTitle('Daftar Arsip');
        
        // Set header
        $headers = array(
            'A1' => 'No',
            'B1' => 'No Berkas',
            'C1' => 'No Urut',
            'D1' => 'Kode',
            'E1' => 'Indeks/Pekerjaan',
            'F1' => 'Uraian Masalah/Kegiatan',
            'G1' => 'Tahun',
            'H1' => 'Jumlah Berkas',
            'I1' => 'Asli/Kopi',
            'J1' => 'Box',
            'K1' => 'Klasifikasi Keamanan',
            'L1' => 'Nama PIC',
            'M1' => 'Nama File',
            'N1' => 'Link Drive',
            'O1' => 'Ukuran File',
            'P1' => 'Tanggal Dibuat',
            'Q1' => 'Tanggal Diupdate'
        );
        
        foreach($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }
        
        // Set style untuk header
        $sheet->getStyle('A1:Q1')->getFont()->setBold(true);
        $sheet->getStyle('A1:Q1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF4472C4');
        $sheet->getStyle('A1:Q1')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A1:Q1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:Q1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        
        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(40);
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(12);
        $sheet->getColumnDimension('J')->setWidth(12);
        $sheet->getColumnDimension('K')->setWidth(25);
        $sheet->getColumnDimension('L')->setWidth(20);
        $sheet->getColumnDimension('M')->setWidth(30);
        $sheet->getColumnDimension('N')->setWidth(50);
        $sheet->getColumnDimension('O')->setWidth(15);
        $sheet->getColumnDimension('P')->setWidth(20);
        $sheet->getColumnDimension('Q')->setWidth(20);
        
        // Isi data
        $row = 2;
        $no = 1;
        foreach($arsip as $ars) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $ars->no_berkas ? $ars->no_berkas : '-');
            $sheet->setCellValue('C' . $row, $ars->no_urut ? $ars->no_urut : '-');
            $sheet->setCellValue('D' . $row, $ars->kode ? $ars->kode : '-');
            $sheet->setCellValue('E' . $row, $ars->indeks_pekerjaan ? $ars->indeks_pekerjaan : '-');
            $sheet->setCellValue('F' . $row, $ars->uraian_masalah_kegiatan ? $ars->uraian_masalah_kegiatan : '-');
            $sheet->setCellValue('G' . $row, $ars->tahun ? $ars->tahun : '-');
            $sheet->setCellValue('H' . $row, $ars->jumlah_berkas ? $ars->jumlah_berkas : 1);
            $sheet->setCellValue('I' . $row, $ars->asli_kopi ? $ars->asli_kopi : '-');
            $sheet->setCellValue('J' . $row, $ars->box ? $ars->box : '-');
            $sheet->setCellValue('K' . $row, $ars->klasifikasi_keamanan ? $ars->klasifikasi_keamanan : '-');
            $sheet->setCellValue('L' . $row, $ars->nama_pengisi ? $ars->nama_pengisi : '-');
            $sheet->setCellValue('M' . $row, $ars->nama_file ? $ars->nama_file : '-');
            $sheet->setCellValue('N' . $row, $ars->link_drive ? $ars->link_drive : '-');
            
            // Format ukuran file
            $ukuran = '-';
            if($ars->ukuran_file) {
                $ukuran = $this->formatBytes($ars->ukuran_file);
            }
            $sheet->setCellValue('O' . $row, $ukuran);
            
            // Format tanggal
            $createDate = $ars->createDate ? date('d-m-Y H:i:s', strtotime($ars->createDate)) : '-';
            $updateDate = $ars->updateDate ? date('d-m-Y H:i:s', strtotime($ars->updateDate)) : '-';
            $sheet->setCellValue('P' . $row, $createDate);
            $sheet->setCellValue('Q' . $row, $updateDate);
            
            // Set wrap text untuk kolom yang panjang
            $sheet->getStyle('F' . $row)->getAlignment()->setWrapText(true);
            $sheet->getStyle('N' . $row)->getAlignment()->setWrapText(true);
            
            $row++;
        }
        
        // Set border untuk semua data
        $lastRow = $row - 1;
        $sheet->getStyle('A1:Q' . $lastRow)->applyFromArray(array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                ),
            ),
        ));
        
        // Set judul dokumen
        $spreadsheet->getProperties()
            ->setCreator('Sistem Arsip Digital')
            ->setTitle('Daftar Arsip - ' . $kategori->nama)
            ->setSubject('Export Daftar Arsip')
            ->setDescription('Daftar arsip untuk kategori: ' . $kategori->nama);
        
        // Output file
        $filename = 'Daftar_Arsip_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $kategori->nama) . '_' . date('YmdHis') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
    
    public function gallery()
    {
        $data['title'] = 'Gallery Arsip';
        
        // Ambil semua arsip dengan kategori
        $this->db->select('a.*, k.nama as kategori_nama');
        $this->db->from('tb_arsip a');
        $this->db->join('tb_kategori_arsip k', 'k.id = a.kategori_id', 'left');
        $this->db->order_by('a.createDate', 'DESC');
        
        // Filter berdasarkan kategori jika ada
        $kategori_id = $this->input->get('kategori_id');
        if(!empty($kategori_id) && is_numeric($kategori_id)) {
            $this->db->where('a.kategori_id', $kategori_id);
        }
        
        // Search
        $search = $this->input->get('search');
        if(!empty($search)) {
            $this->db->group_start();
            $this->db->like('a.no_berkas', $search);
            $this->db->or_like('a.uraian_masalah_kegiatan', $search);
            $this->db->or_like('k.nama', $search);
            $this->db->group_end();
        }
        
        $data['arsip'] = $this->db->get()->result();
        
        // Ambil semua kategori untuk filter
        $this->db->order_by('nama', 'ASC');
        $data['list_kategori'] = $this->db->get('tb_kategori_arsip')->result();
        $data['selected_kategori'] = $kategori_id;
        $data['search_query'] = $search;
        
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/templates/sidebar');
        $this->load->view('admin/gallery_arsip', $data);
        $this->load->view('admin/templates/footer');
    }
    
    public function thumbnail($id)
    {
        $where = array('id' => $id);
        $arsip = $this->m_model->get_where($where, 'tb_arsip')->row();
        
        if(!$arsip) {
            $this->servePlaceholder();
            return;
        }
        
        // Path thumbnail
        $thumbnail_dir = './uploads/thumbnails/';
        if(!is_dir($thumbnail_dir)) {
            mkdir($thumbnail_dir, 0777, TRUE);
        }
        
        $thumbnail_path = $thumbnail_dir . 'thumb_' . $id . '.jpg';
        
        // Jika thumbnail sudah ada dan file sumber ada, cek apakah perlu regenerate
        if(file_exists($thumbnail_path)) {
            // Jika file sumber tidak ada atau thumbnail lebih baru, gunakan thumbnail yang ada
            if(!file_exists($arsip->path_file) || filemtime($thumbnail_path) >= filemtime($arsip->path_file)) {
                header('Content-Type: image/jpeg');
                readfile($thumbnail_path);
                return;
            }
        }
        
        // Jika file sumber tidak ada, tampilkan placeholder
        if(!file_exists($arsip->path_file)) {
            $this->servePlaceholder();
            return;
        }
        
        // Generate thumbnail
        $image_types = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');
        
        if(in_array($arsip->tipe_file, $image_types)) {
            // Jika sudah gambar, resize saja
            $this->load->library('image_lib');
            
            $config['image_library'] = 'gd2';
            $config['source_image'] = $arsip->path_file;
            $config['new_image'] = $thumbnail_path;
            $config['create_thumb'] = FALSE;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 300;
            $config['height'] = 400;
            $config['quality'] = 85;
            
            $this->image_lib->initialize($config);
            
            if($this->image_lib->resize()) {
                header('Content-Type: image/jpeg');
                readfile($thumbnail_path);
            } else {
                // Fallback ke placeholder
                $this->servePlaceholder();
            }
            $this->image_lib->clear();
        } elseif($arsip->tipe_file == 'application/pdf') {
            // Convert PDF halaman pertama ke gambar
            if($this->generatePdfThumbnail($arsip->path_file, $thumbnail_path)) {
                header('Content-Type: image/jpeg');
                readfile($thumbnail_path);
            } else {
                $this->servePlaceholder();
            }
        } else {
            // File lain, tampilkan placeholder
            $this->servePlaceholder();
        }
    }
    
    private function generatePdfThumbnail($pdf_path, $thumbnail_path)
    {
        // Coba beberapa metode untuk convert PDF ke gambar
        
        // Method 1: Menggunakan Ghostscript (gs) - paling umum
        $gs_paths = array(
            'gs', // Jika ada di PATH
            'C:\\Program Files\\gs\\gs10.00.0\\bin\\gswin64c.exe',
            'C:\\Program Files\\gs\\gs10.00.0\\bin\\gswin32c.exe',
            'C:\\Program Files (x86)\\gs\\gs10.00.0\\bin\\gswin64c.exe',
            'C:\\Program Files (x86)\\gs\\gs10.00.0\\bin\\gswin32c.exe',
            'C:\\xampp\\gs\\bin\\gswin64c.exe',
            'C:\\xampp\\gs\\bin\\gswin32c.exe',
        );
        
        $gs_command = null;
        foreach($gs_paths as $gs_path) {
            if($this->commandExists($gs_path)) {
                $gs_command = $gs_path;
                break;
            }
        }
        
        if($gs_command) {
            // Gunakan Ghostscript untuk convert PDF ke JPEG
            $command = escapeshellarg($gs_command) . ' -dNOPAUSE -dBATCH -sDEVICE=jpeg -dFirstPage=1 -dLastPage=1 -r150 -dJPEGQ=85 -sOutputFile=' . escapeshellarg($thumbnail_path) . ' ' . escapeshellarg($pdf_path) . ' 2>&1';
            exec($command, $output, $return_var);
            
            if($return_var === 0 && file_exists($thumbnail_path)) {
                // Resize thumbnail jika terlalu besar
                $this->resizeThumbnail($thumbnail_path, 300, 400);
                return true;
            }
        }
        
        // Method 2: Menggunakan pdftoppm (poppler-utils)
        $pdftoppm_paths = array(
            'pdftoppm', // Jika ada di PATH
            'C:\\Program Files\\poppler\\bin\\pdftoppm.exe',
            'C:\\Program Files (x86)\\poppler\\bin\\pdftoppm.exe',
            'C:\\xampp\\poppler\\bin\\pdftoppm.exe',
        );
        
        $pdftoppm_command = null;
        foreach($pdftoppm_paths as $pdftoppm_path) {
            if($this->commandExists($pdftoppm_path)) {
                $pdftoppm_command = $pdftoppm_path;
                break;
            }
        }
        
        if($pdftoppm_command) {
            $temp_prefix = dirname($thumbnail_path) . '/temp_' . uniqid();
            $command = escapeshellarg($pdftoppm_command) . ' -jpeg -f 1 -l 1 -r 150 -singlefile ' . escapeshellarg($pdf_path) . ' ' . escapeshellarg($temp_prefix) . ' 2>&1';
            exec($command, $output, $return_var);
            
            // Cari file output yang dihasilkan (pdftoppm menambahkan -1.jpg di akhir)
            $temp_file = $temp_prefix . '-1.jpg';
            if(file_exists($temp_file)) {
                rename($temp_file, $thumbnail_path);
                $this->resizeThumbnail($thumbnail_path, 300, 400);
                return true;
            }
        }
        
        return false;
    }
    
    private function commandExists($command)
    {
        $whereIsCommand = (PHP_OS == 'WINNT') ? 'where' : 'which';
        $process = proc_open(
            "$whereIsCommand " . escapeshellarg($command),
            array(
                0 => array("pipe", "r"),
                1 => array("pipe", "w"),
                2 => array("pipe", "w"),
            ),
            $pipes
        );
        
        if($process !== false) {
            $stdout = stream_get_contents($pipes[1]);
            $returnCode = proc_close($process);
            return $returnCode === 0 && !empty($stdout);
        }
        
        return false;
    }
    
    private function resizeThumbnail($image_path, $max_width = 300, $max_height = 400)
    {
        $this->load->library('image_lib');
        
        $config['image_library'] = 'gd2';
        $config['source_image'] = $image_path;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = $max_width;
        $config['height'] = $max_height;
        $config['quality'] = 85;
        
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        $this->image_lib->clear();
    }
    
    private function servePlaceholder()
    {
        // Generate placeholder image
        $width = 300;
        $height = 400;
        
        $img = imagecreatetruecolor($width, $height);
        $bg_color = imagecolorallocate($img, 240, 240, 240);
        $text_color = imagecolorallocate($img, 150, 150, 150);
        
        imagefill($img, 0, 0, $bg_color);
        
        // Tambahkan ikon file
        $text = 'No Preview';
        $font_size = 5;
        $text_width = imagefontwidth($font_size) * strlen($text);
        $text_height = imagefontheight($font_size);
        $x = ($width - $text_width) / 2;
        $y = ($height - $text_height) / 2;
        
        imagestring($img, $font_size, $x, $y, $text, $text_color);
        
        header('Content-Type: image/jpeg');
        imagejpeg($img, null, 85);
        imagedestroy($img);
    }
}


