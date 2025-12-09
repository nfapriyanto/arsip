<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CariBarang extends CI_Controller {

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
        $data['title'] = 'Cari Barang';
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/templates/sidebar');
        $this->load->view('admin/cariBarang', $data);
        $this->load->view('admin/templates/footer');
    }

    public function search()
    {
        // Set header untuk JSON response
        header('Content-Type: application/json');
        
        $kode = $this->input->post('kode');
        
        // Remove # if present for comparison
        $kodeOriginal = trim($kode);
        $kode = str_replace('#', '', $kodeOriginal);
        $kode = trim($kode);
        
        if(empty($kode)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Kode tidak boleh kosong'
            ]);
            return;
        }

        // First, try to search by kode_qr (QR Code) - EXACT MATCH ONLY
        // Remove # from kode_qr for comparison
        $this->db->select('b.*, k.nama as kategori_nama, k.tempat');
        $this->db->from('tb_barang b');
        $this->db->join('tb_kategori k', 'k.id = b.kategori_id', 'left');
        $this->db->where("REPLACE(LOWER(b.kode_qr), '#', '') =", strtolower($kode));
        $barang = $this->db->get()->row();
        
        // If not found by kode_qr, try by kode (nama barang) - exact match, case-insensitive
        if(!$barang) {
            $this->db->select('b.*, k.nama as kategori_nama, k.tempat');
            $this->db->from('tb_barang b');
            $this->db->join('tb_kategori k', 'k.id = b.kategori_id', 'left');
            $this->db->where('LOWER(b.kode)', strtolower($kode));
            $barang = $this->db->get()->row();
        }
        
        // If exact match not found, try partial match on kode (nama barang) - but NOT on kode_qr
        if(!$barang) {
            $this->db->select('b.*, k.nama as kategori_nama, k.tempat');
            $this->db->from('tb_barang b');
            $this->db->join('tb_kategori k', 'k.id = b.kategori_id', 'left');
            $this->db->like('b.kode', $kode, 'both');
            $barang = $this->db->get()->row();
        }
        
        if($barang) {
            $kodeBarang = $barang->kode;
            
            // Get latest riwayat (peminjaman aktif) - check if there's a peminjaman without matching pengembalian
            $whereRiwayat = array('kode' => $kodeBarang);
            $this->db->where($whereRiwayat);
            $this->db->where('jenis', 'Peminjaman');
            $this->db->order_by('createDate', 'DESC');
            $riwayatAktif = $this->db->get('tb_riwayat')->row();
            
            // Get all riwayat for this barang
            $this->db->where($whereRiwayat);
            $this->db->order_by('createDate', 'DESC');
            $allRiwayat = $this->db->get('tb_riwayat')->result();
            
            echo json_encode([
                'status' => 'success',
                'barang' => [
                    'id' => $barang->id,
                    'kode' => $barang->kode,
                    'kode_qr' => isset($barang->kode_qr) ? $barang->kode_qr : null,
                    'kategori_nama' => isset($barang->kategori_nama) ? $barang->kategori_nama : null,
                    'tempat' => isset($barang->tempat) ? $barang->tempat : null,
                    'createDate' => $barang->createDate
                ],
                'peminjamAktif' => $riwayatAktif ? [
                    'peminjam' => isset($riwayatAktif->peminjam) ? $riwayatAktif->peminjam : (isset($riwayatAktif->unit) ? $riwayatAktif->unit : '-'),
                    'noTlp' => $riwayatAktif->noTlp,
                    'createDate' => $riwayatAktif->createDate
                ] : null,
                'riwayat' => $allRiwayat
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Barang dengan kode "' . $kode . '" tidak ditemukan'
            ]);
        }
    }
}

