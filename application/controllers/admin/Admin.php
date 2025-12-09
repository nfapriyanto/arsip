<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Check if already logged in
        if($this->session->userdata('id')){
            if($this->session->userdata('level') == 'Admin'){
                redirect('admin/dashboard');
            } else {
                redirect('user/dashboard');
            }
        }
    }

    public function index()
    {
        // Show login page
        $this->load->view('login');
    }

    public function login()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        if(empty($username) || empty($password)) {
            $this->session->set_flashdata('pesan', 'Username dan Password harus diisi!');
            redirect('admin');
            return;
        }

        $where = array(
            'username' => $username,
            'password' => md5($password)
        );

        $cek = $this->m_model->get_where($where, 'tb_user')->num_rows();
        if($cek > 0){
            $data = $this->m_model->get_where($where, 'tb_user')->result();
            foreach ($data as $dt) {
                $datauser = array(
                    'id' 			=> $dt->id,
                    'nama' 			=> $dt->nama,
                    'username' 		=> $dt->username,
                    'password' 		=> $dt->password,
                    'level' 		=> $dt->level,
                    'createDate' 	=> $dt->createDate
                );
            }

            $this->session->set_userdata($datauser);
            // Clear any existing flash data after successful login
            $this->session->unset_userdata('pesan');
            if($this->session->userdata('level') == 'Admin'){
                redirect('admin/dashboard');
            } else {
                redirect('user/dashboard');
            }
        } else {
            $this->session->set_flashdata('pesan', 'Username atau Password anda salah!');
            redirect('admin');
        }
    }
}

