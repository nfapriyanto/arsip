<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ManajemenUser extends CI_Controller {

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
        $data['title'] = 'Manajemen User';
        $data['manajemenUser'] = $this->m_model->get('tb_user')->result();
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/templates/sidebar');
        $this->load->view('admin/manajemenUser', $data);
        $this->load->view('admin/templates/footer');
    }

    public function insert()
    {
        date_default_timezone_set('Asia/Jakarta');
        $nama       = $_POST['nama'];
        $username   = $_POST['username'];
        $password   = $_POST['password'];
        $level      = $_POST['level'];

        $data = array(
            'nama'          => $nama,
            'username'      => $username,
            'level'         => $level,
            'password'      => md5($password),
            'createDate'    => date('Y-m-d H:i:s')
        );

        $this->m_model->insert($data, 'tb_user');
        $this->session->set_flashdata('pesan', 'User baru berhasil ditambahkan!');
        redirect('admin/manajemenUser');
    }

    public function delete($id)
    {
        $where = array('id' => $id);

        $this->m_model->delete($where, 'tb_user');
        $this->session->set_flashdata('pesan', 'User berhasil dihapus!');
        redirect('admin/manajemenUser');
    }
}