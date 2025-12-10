<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CariArsip extends CI_Controller {

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
        $data['title'] = 'Cari Arsip';
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/templates/sidebar');
        $this->load->view('admin/cariArsip', $data);
        $this->load->view('admin/templates/footer');
    }

    public function search()
    {
        // Set header untuk JSON response
        header('Content-Type: application/json');
        
        $keyword = $this->input->post('keyword');
        $keyword = trim($keyword);
        
        if(empty($keyword)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Kata kunci tidak boleh kosong'
            ]);
            return;
        }

        // Search by nomor_arsip, judul, or deskripsi
        $this->db->select('a.*, k.nama as kategori_nama');
        $this->db->from('tb_arsip a');
        $this->db->join('tb_kategori_arsip k', 'k.id = a.kategori_id', 'left');
        $this->db->group_start();
        $this->db->like('a.nomor_arsip', $keyword, 'both');
        $this->db->or_like('a.judul', $keyword, 'both');
        $this->db->or_like('a.deskripsi', $keyword, 'both');
        $this->db->group_end();
        $this->db->order_by('a.createDate', 'DESC');
        $arsip = $this->db->get()->result();
        
        if($arsip) {
            $result = [];
            foreach($arsip as $a) {
                $result[] = [
                    'id' => $a->id,
                    'nomor_arsip' => $a->nomor_arsip,
                    'judul' => $a->judul,
                    'deskripsi' => $a->deskripsi,
                    'kategori_nama' => $a->kategori_nama,
                    'tahun_dokumen' => $a->tahun_dokumen,
                    'pembuat' => $a->pembuat,
                    'nama_file' => $a->nama_file,
                    'ukuran_file' => $a->ukuran_file,
                    'status' => $a->status,
                    'createDate' => $a->createDate
                ];
            }
            
            echo json_encode([
                'status' => 'success',
                'arsip' => $result
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Arsip dengan kata kunci "' . $keyword . '" tidak ditemukan'
            ]);
        }
    }
}


