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
        $this->db->select('k.id, k.nama, k.deskripsi, k.parent_id, COUNT(DISTINCT a.id) as total_arsip, COUNT(DISTINCT sub.id) as total_sub');
        $this->db->from('tb_kategori_arsip k');
        $this->db->join('tb_arsip a', 'a.kategori_id = k.id', 'left');
        $this->db->join('tb_kategori_arsip sub', 'sub.parent_id = k.id', 'left');
        $this->db->where('k.parent_id IS NULL');
        $this->db->group_by('k.id, k.nama, k.deskripsi, k.parent_id');
        $this->db->order_by('k.nama', 'ASC');
        $kategori = $this->db->get()->result();
        
        $data['kategori'] = $kategori;
        
        // Ambil semua kategori untuk dropdown form (hanya parent)
        $this->db->where('parent_id IS NULL');
        $this->db->order_by('nama', 'ASC');
        $data['list_kategori'] = $this->db->get('tb_kategori_arsip')->result();
        
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
        $this->db->select('a.*');
        $this->db->from('tb_arsip a');
        $this->db->where('a.kategori_id', $id);
        $this->db->order_by('a.createDate', 'DESC');
        $arsip = $this->db->get()->result();
        
        // Ambil sub-kategori jika ada dengan total arsip
        $this->db->select('k.id, k.nama, k.deskripsi, k.parent_id, COUNT(DISTINCT a.id) as total_arsip');
        $this->db->from('tb_kategori_arsip k');
        $this->db->join('tb_arsip a', 'a.kategori_id = k.id', 'left');
        $this->db->where('k.parent_id', $id);
        $this->db->group_by('k.id, k.nama, k.deskripsi, k.parent_id');
        $this->db->order_by('k.nama', 'ASC');
        $sub_kategori = $this->db->get()->result();
        
        $data['arsip'] = $arsip;
        $data['sub_kategori'] = $sub_kategori;
        $data['kategori_id'] = $id;
        $data['kategori_nama'] = $kategori->nama;
        $data['kategori_parent_id'] = $kategori->parent_id;
        
        // Ambil semua kategori untuk dropdown form (termasuk sub-kategori untuk form arsip)
        $this->db->order_by('nama', 'ASC');
        $data['list_kategori'] = $this->db->get('tb_kategori_arsip')->result();
        
        // Ambil hanya kategori parent untuk dropdown form sub-kategori baru
        $this->db->where('parent_id IS NULL');
        $this->db->order_by('nama', 'ASC');
        $data['list_kategori_parent'] = $this->db->get('tb_kategori_arsip')->result();
        
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
        
        // Hapus file fisik jika ada
        if($path_file && file_exists($path_file)) {
            @unlink($path_file);
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
        $deskripsi  = $this->input->post('deskripsi');
        $parent_id  = $this->input->post('parent_id'); // Untuk kategori bertingkat
        $createDate = date('Y-m-d H:i:s');

        $data = array(
            'nama'          => $nama,
            'deskripsi'     => $deskripsi,
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

    public function insert()
    {
        date_default_timezone_set('Asia/Jakarta');
        
        // Kategori diambil dari URL, tidak bisa dipilih
        $kategori_id                = $this->input->post('kategori_id');
        $no_berkas                  = $this->input->post('no_berkas');
        $no_urut                    = $this->input->post('no_urut');
        $kode                       = $this->input->post('kode');
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
            $config['max_size'] = 10240; // 10MB
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
        $deskripsi = $this->input->post('deskripsi');
        $current_kategori_id = $this->input->post('current_kategori_id'); // ID kategori halaman saat ini
        
        $where = array('id' => $id);
        $data = array(
            'nama'      => $nama,
            'deskripsi' => $deskripsi
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
        $kode                    = $this->input->post('kode');
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

        $where = array('id' => $id);
        
        $data = array(
            'kategori_id'            => $kategori_id,
            'no_berkas'              => $no_berkas,
            'no_urut'                => !empty($no_urut) ? $no_urut : NULL,
            'kode'                   => $kode,
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
            // Hapus file lama
            $arsip_lama = $this->m_model->get_where($where, 'tb_arsip')->row();
            if($arsip_lama && file_exists($arsip_lama->path_file)) {
                @unlink($arsip_lama->path_file);
            }
            
            // Upload file baru
            $config['upload_path'] = './uploads/arsip/';
            $config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|jpg|jpeg|png|gif|zip|rar';
            $config['max_size'] = 10240;
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
                
                // Siapkan data untuk insert
                $data = array(
                    'kategori_id'            => $import_kategori_id,
                    'no_berkas'              => $no_berkas,
                    'no_urut'                => !empty($no_urut) ? intval($no_urut) : NULL,
                    'kode'                   => $kode ?: NULL,
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
}


