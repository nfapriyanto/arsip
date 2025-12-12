<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

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
        $data['title'] = 'Dashboard';
        $data['jumlahArsip'] = $this->m_model->get('tb_arsip')->num_rows();
        $data['jumlahKategori'] = $this->m_model->get('tb_kategori_arsip')->num_rows();
        $data['jumlahPengguna'] = $this->m_model->get('tb_user')->num_rows();
        $data['totalAkses'] = $this->m_model->get('tb_riwayat_arsip')->num_rows();
        
        // Hitung ukuran total file arsip
        $this->db->select_sum('ukuran_file');
        $total_size = $this->db->get('tb_arsip')->row()->ukuran_file;
        $data['totalUkuran'] = $total_size ? $total_size : 0;
        
        // Ambil daftar kategori parent untuk dropdown
        $this->db->select('id, nama');
        $this->db->from('tb_kategori_arsip');
        $this->db->where('parent_id IS NULL');
        $this->db->order_by('nama', 'ASC');
        $data['listKategoriParent'] = $this->db->get()->result();
        
        // Ambil parent_id dari request (default: semua atau parent pertama)
        $parent_id = $this->input->get('parent_id');
        $data['selectedParentId'] = $parent_id;
        
        // Ambil statistik sub-kategori berdasarkan parent_id yang dipilih
        $this->db->select('k.id, k.nama, k.parent_id, COUNT(DISTINCT a.id) as jumlah_arsip');
        $this->db->from('tb_kategori_arsip k');
        $this->db->join('tb_arsip a', 'a.kategori_id = k.id', 'left');
        $this->db->where('k.parent_id IS NOT NULL');
        
        // Filter berdasarkan parent_id jika dipilih
        if(!empty($parent_id) && $parent_id != 'all') {
            $this->db->where('k.parent_id', $parent_id);
        }
        
        $this->db->group_by('k.id, k.nama, k.parent_id');
        $this->db->order_by('k.nama', 'ASC');
        $data['statistikKategori'] = $this->db->get()->result();
        
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/templates/sidebar');
        $this->load->view('admin/dashboard', $data);
        $this->load->view('admin/templates/footer');
    }
    
    // Method untuk AJAX - mengambil statistik berdasarkan parent_id
    public function getStatistikByParent()
    {
        $parent_id = $this->input->post('parent_id');
        
        $this->db->select('k.id, k.nama, k.parent_id, COUNT(DISTINCT a.id) as jumlah_arsip');
        $this->db->from('tb_kategori_arsip k');
        $this->db->join('tb_arsip a', 'a.kategori_id = k.id', 'left');
        $this->db->where('k.parent_id IS NOT NULL');
        
        if(!empty($parent_id) && $parent_id != 'all') {
            $this->db->where('k.parent_id', $parent_id);
        }
        
        $this->db->group_by('k.id, k.nama, k.parent_id');
        $this->db->order_by('k.nama', 'ASC');
        $result = $this->db->get()->result();
        
        // Format data untuk chart
        $chartData = array();
        foreach($result as $stat) {
            $chartData[] = array(
                'y' => $stat->nama,
                'a' => (int)$stat->jumlah_arsip
            );
        }
        
        header('Content-Type: application/json');
        echo json_encode($chartData);
    }
}