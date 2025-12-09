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
        
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/templates/sidebar');
        $this->load->view('admin/dashboard', $data);
        $this->load->view('admin/templates/footer');
    }
}