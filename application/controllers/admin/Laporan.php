<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

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
        $data['title'] = 'Laporan';
        $data['laporan'] = $this->m_model->get('tb_riwayat')->result();

        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/templates/sidebar');
        $this->load->view('admin/laporan', $data);
    }

    public function cetak()
    {
        $tgl_awal   = $this->input->POST('tgl_awal');
        $tgl_akhir  = $this->input->POST('tgl_akhir');

        $convertTglAwal = date('Y-m-d H:i:s', strtotime($tgl_awal . '00:01'));
        $convertTglAkhir = date('Y-m-d H:i:s', strtotime($tgl_akhir . '23:59'));

        $data['tgl_awal']   = $tgl_awal;
        $data['tgl_akhir']  = $tgl_akhir;

        $data['title'] = 'Cetak Riwayat Barang';
        $data['cetakData'] = $this->db->query("SELECT * FROM tb_riwayat WHERE createDate BETWEEN '".$convertTglAwal."' AND '".$convertTglAkhir."' ")->result();
        $data['jumlah'] = $this->db->query("SELECT COUNT(id) AS jumlahData FROM tb_riwayat WHERE createDate BETWEEN '".$convertTglAwal."' AND '".$convertTglAkhir."' ")->result();

        $this->load->view('admin/cetakRiwayatBarangCustom', $data);
    }

    public function exportExcel()
    {
        $tgl_awal   = $this->input->POST('tgl_awal');
        $tgl_akhir  = $this->input->POST('tgl_akhir');

        $convertTglAwal = date('Y-m-d H:i:s', strtotime($tgl_awal . '00:01'));
        $convertTglAkhir = date('Y-m-d H:i:s', strtotime($tgl_akhir . '23:59'));

        $data['tgl_awal']   = $tgl_awal;
        $data['tgl_akhir']  = $tgl_akhir;

        $data['title'] = 'Cetak Riwayat Barang';
        $data['cetakData'] = $this->db->query("SELECT * FROM tb_riwayat WHERE createDate BETWEEN '".$convertTglAwal."' AND '".$convertTglAkhir."' ")->result();
        $data['jumlah'] = $this->db->query("SELECT COUNT(id) AS jumlahData FROM tb_riwayat WHERE createDate BETWEEN '".$convertTglAwal."' AND '".$convertTglAkhir."' ")->result();

        $this->load->view('admin/exportExcel', $data);
    }
}