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
        $kategori_id = $arsip ? $arsip->kategori_id : null;
        $path_file = $arsip ? $arsip->path_file : null;

        // Hapus file fisik jika ada
        if($path_file && file_exists($path_file)) {
            @unlink($path_file);
        }

        // Hapus data dari database
        $this->m_model->delete($where, 'tb_arsip');
        
        // Log aksi delete
        if($arsip) {
            $this->logAksi($id, 'Delete', 'Arsip dihapus');
        }
        
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
        // Cek apakah kategori memiliki arsip
        $where_arsip = array('kategori_id' => $id);
        $jumlah_arsip = $this->m_model->get_where($where_arsip, 'tb_arsip')->num_rows();
        
        // Cek apakah kategori memiliki sub-kategori
        $where_sub = array('parent_id' => $id);
        $jumlah_sub = $this->m_model->get_where($where_sub, 'tb_kategori_arsip')->num_rows();
        
        if($jumlah_arsip > 0 || $jumlah_sub > 0) {
            $this->session->set_flashdata('pesan', 'Kategori tidak dapat dihapus karena masih memiliki arsip atau sub-kategori!');
            redirect('admin/arsip');
            return;
        }
        
        // Hapus kategori
        $where = array('id' => $id);
        $this->m_model->delete($where, 'tb_kategori_arsip');
        $this->session->set_flashdata('pesan', 'Kategori berhasil dihapus!');
        redirect('admin/arsip');
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
        $createDate                 = date('Y-m-d H:i:s');
        
        // Validasi
        if(empty($kategori_id)) {
            $this->session->set_flashdata('pesan', 'Kategori harus diisi!');
            redirect('admin/arsip/kategori/' . $kategori_id);
            return;
        }

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
        
        $where = array('id' => $id);
        $data = array(
            'nama'      => $nama,
            'deskripsi' => $deskripsi
        );

        $this->m_model->update($where, $data, 'tb_kategori_arsip');
        $this->session->set_flashdata('pesan', 'Kategori berhasil diubah!');
        redirect('admin/arsip');
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
            'updateDate'             => $updateDate
        );
        
        // Jika ada file baru diupload
        if(!empty($_FILES['file_arsip']['name'])) {
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
    }
}


