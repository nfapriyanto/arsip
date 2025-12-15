<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kode extends CI_Controller {

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
        $data['title'] = 'Data Kode Arsip';
        
        // Ambil semua kode arsip
        $this->db->order_by('kode', 'ASC');
        $data['kode'] = $this->db->get('tb_kode_arsip')->result();
        
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/templates/sidebar');
        $this->load->view('admin/kode', $data);
        $this->load->view('admin/templates/footer');
    }

    public function insert()
    {
        date_default_timezone_set('Asia/Jakarta');
        $kode = $this->input->post('kode');
        $nama = $this->input->post('nama');
        $createDate = date('Y-m-d H:i:s');

        // Validasi kode harus unik
        $where_check = array('kode' => $kode);
        $existing = $this->m_model->get_where($where_check, 'tb_kode_arsip')->row();
        
        if($existing) {
            $this->session->set_flashdata('pesan', 'Kode sudah ada! Silakan gunakan kode lain.');
            redirect('admin/kode');
            return;
        }

        $data = array(
            'kode' => $kode,
            'nama' => $nama,
            'createDate' => $createDate,
        );

        $this->m_model->insert($data, 'tb_kode_arsip');
        $this->session->set_flashdata('pesan', 'Kode arsip berhasil ditambahkan!');
        redirect('admin/kode');
    }

    public function update()
    {
        $id = $this->input->post('id');
        $kode = $this->input->post('kode');
        $nama = $this->input->post('nama');
        $updateDate = date('Y-m-d H:i:s');

        // Validasi kode harus unik (kecuali untuk id yang sama)
        $where_check = array('kode' => $kode);
        $existing = $this->m_model->get_where($where_check, 'tb_kode_arsip')->row();
        
        if($existing && $existing->id != $id) {
            $this->session->set_flashdata('pesan', 'Kode sudah digunakan oleh kode lain! Silakan gunakan kode lain.');
            redirect('admin/kode');
            return;
        }

        $where = array('id' => $id);
        $data = array(
            'kode' => $kode,
            'nama' => $nama,
            'updateDate' => $updateDate,
        );

        $this->m_model->update($where, $data, 'tb_kode_arsip');
        $this->session->set_flashdata('pesan', 'Kode arsip berhasil diubah!');
        redirect('admin/kode');
    }

    public function delete($id)
    {
        // Cek apakah kode sedang digunakan di arsip
        $where_arsip = array('kode_id' => $id);
        $jumlah_arsip = $this->m_model->get_where($where_arsip, 'tb_arsip')->num_rows();
        
        if($jumlah_arsip > 0) {
            $this->session->set_flashdata('pesan', 'Kode tidak dapat dihapus karena masih digunakan oleh ' . $jumlah_arsip . ' arsip!');
            redirect('admin/kode');
            return;
        }

        $where = array('id' => $id);
        $this->m_model->delete($where, 'tb_kode_arsip');
        $this->session->set_flashdata('pesan', 'Kode arsip berhasil dihapus!');
        redirect('admin/kode');
    }
}


