<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

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
        $data['title'] = 'Profile';
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/templates/sidebar');
        $this->load->view('admin/profile', $data);
        $this->load->view('admin/templates/footer');
    }

    public function update()
    {
        $nama       = $this->input->post('nama');
        $username   = $this->input->post('username');
        $password   = $this->input->post('password');

        if($password == ''){
            $data = array(
                'nama'      => $nama, 
                'username'  => $username
            );
        } else {
            $data = array(
                'nama'      => $nama, 
                'username'  => $username, 
                'password'  => md5($password), 
            );
        }

        $where = array('id' => $this->session->userdata('id') );

        $this->m_model->update($where, $data, 'tb_user');
        $this->session->set_userdata($data);
        $this->session->set_flashdata('pesan', 'Data berhasil diubah');
        redirect('admin/profile');
    }
}