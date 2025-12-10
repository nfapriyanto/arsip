<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat extends CI_Controller {

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
        $data['title'] = 'Riwayat Akses Arsip';
        
        // Get riwayat with arsip and user info
        $this->db->select('r.*, a.no_berkas, a.uraian_masalah_kegiatan as arsip_judul, u.nama as user_nama');
        $this->db->from('tb_riwayat_arsip r');
        $this->db->join('tb_arsip a', 'a.id = r.arsip_id', 'left');
        $this->db->join('tb_user u', 'u.id = r.user_id', 'left');
        $this->db->order_by('r.createDate', 'DESC');
        $data['riwayat'] = $this->db->get()->result();

        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/templates/sidebar');
        $this->load->view('admin/riwayat', $data);
        $this->load->view('admin/templates/footer');
    }

    public function delete($id)
    {
        $where = array('id' => $id);
        $this->m_model->delete($where, 'tb_riwayat_arsip');
        $this->session->set_flashdata('pesan', 'Data riwayat berhasil dihapus!');
        redirect('admin/riwayat');
    }

    public function cetak()
    {
        $tgl_awal   = $this->input->POST('tgl_awal');
        $tgl_akhir  = $this->input->POST('tgl_akhir');

        $convertTglAwal = date('Y-m-d H:i:s', strtotime($tgl_awal . ' 00:01:00'));
        $convertTglAkhir = date('Y-m-d H:i:s', strtotime($tgl_akhir . ' 23:59:59'));

        $data['tgl_awal']   = $tgl_awal;
        $data['tgl_akhir']  = $tgl_akhir;

        $data['title'] = 'Cetak Riwayat Arsip';
        $this->db->select('r.*, a.no_berkas, a.uraian_masalah_kegiatan as arsip_judul, u.nama as user_nama');
        $this->db->from('tb_riwayat_arsip r');
        $this->db->join('tb_arsip a', 'a.id = r.arsip_id', 'left');
        $this->db->join('tb_user u', 'u.id = r.user_id', 'left');
        $this->db->where("r.createDate BETWEEN '".$convertTglAwal."' AND '".$convertTglAkhir."'");
        $data['cetakData'] = $this->db->get()->result();
        $data['jumlah'] = $this->db->query("SELECT COUNT(id) AS jumlahData FROM tb_riwayat_arsip WHERE createDate BETWEEN '".$convertTglAwal."' AND '".$convertTglAkhir."' ")->result();

        $this->load->view('admin/cetakRiwayatArsipCustom', $data);
    }

    public function exportExcel()
    {
        $tgl_awal   = $this->input->POST('tgl_awal');
        $tgl_akhir  = $this->input->POST('tgl_akhir');

        $convertTglAwal = date('Y-m-d H:i:s', strtotime($tgl_awal . ' 00:01:00'));
        $convertTglAkhir = date('Y-m-d H:i:s', strtotime($tgl_akhir . ' 23:59:59'));

        $data['tgl_awal']   = $tgl_awal;
        $data['tgl_akhir']  = $tgl_akhir;

        $data['title'] = 'Export Excel Riwayat Arsip';
        $this->db->select('r.*, a.no_berkas, a.uraian_masalah_kegiatan as arsip_judul, u.nama as user_nama');
        $this->db->from('tb_riwayat_arsip r');
        $this->db->join('tb_arsip a', 'a.id = r.arsip_id', 'left');
        $this->db->join('tb_user u', 'u.id = r.user_id', 'left');
        $this->db->where("r.createDate BETWEEN '".$convertTglAwal."' AND '".$convertTglAkhir."'");
        $data['cetakData'] = $this->db->get()->result();
        $data['jumlah'] = $this->db->query("SELECT COUNT(id) AS jumlahData FROM tb_riwayat_arsip WHERE createDate BETWEEN '".$convertTglAwal."' AND '".$convertTglAkhir."' ")->result();

        $this->load->view('admin/exportExcel', $data);
    }
}