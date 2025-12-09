<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang extends CI_Controller {

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
    }

    public function index()
    {
        $data['title'] = 'Data Kategori Barang';
        
        // Ambil semua kategori dengan total stok (dihitung dari COUNT barang) dan tempat dari kategori
        $this->db->select('k.id, k.nama, k.tempat, COUNT(b.id) as total_stok');
        $this->db->from('tb_kategori k');
        $this->db->join('tb_barang b', 'b.kategori_id = k.id', 'left');
        $this->db->group_by('k.id, k.nama, k.tempat');
        $this->db->order_by('k.nama', 'ASC');
        $kategori = $this->db->get()->result();
        
        $data['kategori'] = $kategori;
        $data['riwayat'] = $this->m_model->get('tb_riwayat')->result();
        
        // Ambil semua kategori untuk dropdown form
        $data['list_kategori'] = $this->m_model->get('tb_kategori')->result();
        
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/templates/sidebar');
        $this->load->view('admin/barang', $data);
        $this->load->view('admin/templates/footer');
    }

    public function kategori($id = null)
    {
        if(empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('pesan', 'Kategori tidak valid!');
            redirect('admin/barang');
        }
        
        // Ambil data kategori
        $where_kategori = array('id' => $id);
        $kategori = $this->m_model->get_where($where_kategori, 'tb_kategori')->row();
        
        if(!$kategori) {
            $this->session->set_flashdata('pesan', 'Kategori tidak ditemukan!');
            redirect('admin/barang');
        }
        
        $data['title'] = 'Daftar Barang: ' . $kategori->nama;
        
        // Ambil semua barang dengan kategori_id yang sama
        $where = array('kategori_id' => $id);
        $barang = $this->m_model->get_where($where, 'tb_barang')->result();
        
        // Generate kode_qr untuk barang yang belum punya
        foreach($barang as $brg) {
            if(empty($brg->kode_qr)) {
                $kode_qr = $this->generateKodeQR();
                $where_update = array('id' => $brg->id);
                $data_update = array('kode_qr' => $kode_qr);
                $this->m_model->update($where_update, $data_update, 'tb_barang');
                $brg->kode_qr = $kode_qr;
            }
        }
        
        $data['barang'] = $barang;
        $data['kategori_id'] = $id;
        $data['kategori_nama'] = $kategori->nama;
        $data['riwayat'] = $this->m_model->get('tb_riwayat')->result();
        
        // Ambil semua kategori untuk dropdown form
        $data['list_kategori'] = $this->m_model->get('tb_kategori')->result();
        
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/templates/sidebar');
        $this->load->view('admin/barang', $data);
        $this->load->view('admin/templates/footer');
    }

    public function delete($id)
    {
        // Ambil data barang untuk mendapatkan kategori_id sebelum dihapus
        $where = array('id' => $id);
        $barang = $this->m_model->get_where($where, 'tb_barang')->row();
        $kategori_id = $barang ? $barang->kategori_id : null;

        $this->m_model->delete($where, 'tb_barang');
        $this->session->set_flashdata('pesan', 'Data berhasil dihapus!');
        
        // Redirect kembali ke halaman kategori barang jika ada kategori_id
        if(!empty($kategori_id)) {
            redirect('admin/barang/kategori/' . $kategori_id);
        } else {
            redirect('admin/barang');
        }
    }

    public function insert_kategori()
    {
        date_default_timezone_set('Asia/Jakarta');
        $nama       = $_POST['nama'];
        $tempat     = $_POST['tempat'];
        $createDate = date('Y-m-d H:i:s');

        $data = array(
            'nama'          => $nama,
            'tempat'        => $tempat,
            'createDate'    => $createDate,
        );

        $this->m_model->insert($data, 'tb_kategori');
        $this->session->set_flashdata('pesan', 'Kategori berhasil ditambahkan!');
        redirect('admin/barang');
    }

    public function insert()
    {
        date_default_timezone_set('Asia/Jakarta');
        $kategori_id = $_POST['kategori_id'];
        $createDate  = date('Y-m-d H:i:s');
        
        // Ambil nama kategori untuk kode (backward compatibility)
        $where_kategori = array('id' => $kategori_id);
        $kategori = $this->m_model->get_where($where_kategori, 'tb_kategori')->row();
        $kode = $kategori ? $kategori->nama : '';
        
        // Generate kode_qr otomatis
        $kode_qr = $this->generateKodeQR();

        $data = array(
            'kategori_id'   => $kategori_id,
            'kode'          => $kode, // Untuk backward compatibility
            'kode_qr'       => $kode_qr,
            'createDate'    => $createDate,
        );

        $this->m_model->insert($data, 'tb_barang');
        $this->session->set_flashdata('pesan', 'Barang berhasil ditambahkan!');
        redirect('admin/barang/kategori/' . $kategori_id);
    }
    
    private function generateKodeQR()
    {
        // Generate kode QR unik: # + 10 karakter alphanumeric uppercase
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $maxAttempts = 100; // Prevent infinite loop
        $attempts = 0;
        
        do {
            $kode_qr = '#';
            // Generate random 10 karakter
            for ($i = 0; $i < 10; $i++) {
                $kode_qr .= $characters[rand(0, strlen($characters) - 1)];
            }
            
            // Check if kode_qr already exists
            $where = array('kode_qr' => $kode_qr);
            $exists = $this->m_model->get_where($where, 'tb_barang')->num_rows();
            
            $attempts++;
            if ($attempts >= $maxAttempts) {
                // Fallback: use timestamp + random
                $kode_qr = '#' . strtoupper(substr(md5(time() . rand()), 0, 10));
                break;
            }
        } while ($exists > 0);
        
        return $kode_qr;
    }
    
    public function generateQRCode($id)
    {
        // Generate QR Code image untuk barang
        $where = array('id' => $id);
        $barang = $this->m_model->get_where($where, 'tb_barang')->row();
        
        if(!$barang || empty($barang->kode_qr)) {
            show_404();
            return;
        }
        
        // Generate QR Code menggunakan Google Charts API
        $kode_qr = urlencode($barang->kode_qr);
        $size = 200;
        $url = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data=" . $kode_qr;
        
        // Redirect to QR code image
        redirect($url);
    }

    public function insert_kelola()
    {
        date_default_timezone_set('Asia/Jakarta');
        $id         = $this->input->post('id');
        $kode       = $this->input->post('kode');
        $jenis      = $this->input->post('jenis');
        $createDate = date('Y-m-d H:i:s');
        $peminjam   = $this->input->post('peminjam');
        $noTlp      = $this->input->post('noTlp');

        // Validate required fields
        if(empty($peminjam)) {
            $this->session->set_flashdata('pesan', 'Peminjam harus diisi!');
            redirect('admin/barang');
            return;
        }

        if(empty($jenis)) {
            $this->session->set_flashdata('pesan', 'Jenis harus dipilih!');
            redirect('admin/barang');
            return;
        }

        $data = array(
            'kode'          => $kode,
            'jenis'         => $jenis,
            'createDate'    => $createDate,
            'peminjam'      => $peminjam,
            'noTlp'         => $noTlp
        );

        $this->m_model->insert($data, 'tb_riwayat');
        
        // Update is_available berdasarkan jenis
        $whereBarang = array('id' => $id);
        $is_available = ($jenis == 'Pengembalian') ? 1 : 0;
        $updateData = array('is_available' => $is_available);
        $this->m_model->update($whereBarang, $updateData, 'tb_barang');
        
        $this->session->set_flashdata('pesan', 'Data berhasil ditambahkan!');
        
        // Ambil kategori_id dari barang
        $barang = $this->m_model->get_where($whereBarang, 'tb_barang')->row();
        $kategori_id = $barang ? $barang->kategori_id : null;
        
        // Redirect kembali ke halaman kategori barang
        if(!empty($kategori_id)) {
            redirect('admin/barang/kategori/' . $kategori_id);
        } else {
            redirect('admin/barang');
        }
    }

    public function update_kategori()
    {
        $id      = $_POST['id'];
        $nama    = $_POST['nama'];
        $tempat  = $_POST['tempat'];
        
        $where = array('id' => $id);
        $data = array(
            'nama'      => $nama,
            'tempat'    => $tempat
        );

        $this->m_model->update($where, $data, 'tb_kategori');
        $this->session->set_flashdata('pesan', 'Kategori berhasil diubah!');
        redirect('admin/barang');
    }

    public function update()
    {
        $id          = $_POST['id'];
        $kategori_id = $_POST['kategori_id'];
        
        // Check if barang exists and has kode_qr
        $where = array('id' => $id);
        $barang = $this->m_model->get_where($where, 'tb_barang')->row();
        
        // Generate kode_qr jika belum ada
        $kode_qr = null;
        if($barang && empty($barang->kode_qr)) {
            $kode_qr = $this->generateKodeQR();
        }

        // Ambil nama kategori untuk kode (backward compatibility)
        $where_kategori = array('id' => $kategori_id);
        $kategori = $this->m_model->get_where($where_kategori, 'tb_kategori')->row();
        $kode = $kategori ? $kategori->nama : '';

        $data = array(
            'kategori_id'   => $kategori_id,
            'kode'          => $kode // Untuk backward compatibility
        );

        // Add kode_qr if generated
        if($kode_qr) {
            $data['kode_qr'] = $kode_qr;
        }

        $this->m_model->update($where, $data, 'tb_barang');
        $this->session->set_flashdata('pesan', 'Barang berhasil diubah!');
        
        // Redirect kembali ke halaman kategori barang
        redirect('admin/barang/kategori/' . $kategori_id);
    }

    public function riwayat($id)
    {
        $where = array('id' => $id);

        $ambilKode = $this->m_model->get_where($where, 'tb_barang')->result();
        foreach ($ambilKode as $ablKd) {
            $kodeBarang = $ablKd->kode;
        }

        $data['kode'] = $kodeBarang;
        $whereKode = array('kode' => $kodeBarang);
        $data['riwayat'] = $this->m_model->get_where($whereKode, 'tb_riwayat')->result();
        $data['id'] = $id;
        $data['title'] = 'Riwayat Barang : ' . $kodeBarang;
        
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/templates/sidebar');
        $this->load->view('admin/riwayatBarang', $data);
        $this->load->view('admin/templates/footer');
    }

    public function printStokBarang()
    {
       $data['barang'] = $this->m_model->get('tb_barang')->result();
       $data['title'] = 'Cetak Stok Barang';

       $this->load->view('admin/cetakStokBarang', $data);
    }

    public function printRiwayatBarang($id)
    {
       $where = array('id' => $id);

       $ambilKode = $this->m_model->get_where($where, 'tb_barang')->result();
       foreach ($ambilKode as $ablKd) {
        $kodeBarang = $ablKd->kode;
       }

       $data['kode'] = $kodeBarang;
       $whereKode = array('kode' => $kodeBarang);
       $data['riwayat'] = $this->m_model->get_where($whereKode, 'tb_riwayat')->result();
       $data['jumlah'] = $this->m_model->get_where($whereKode, 'tb_riwayat')->num_rows();
       $data['title'] = 'Cetak Riwayat Stok Barang : ' . $kodeBarang;
       $data['id'] = $id;
       
       $this->load->view('admin/cetakRiwayatBarang', $data);
    }
}