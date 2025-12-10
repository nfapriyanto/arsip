<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CariArsip extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // No authentication required - public access
    }

    public function index()
    {
        // Show cariArsip page
        $this->cariArsip();
    }

    public function cariArsip()
    {
        $data['title'] = 'Cari Arsip - Sistem Arsip Digital';
        $this->load->view('public/cariArsip', $data);
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

        // Search by no_berkas, kode, indeks_pekerjaan, or uraian_masalah_kegiatan
        $this->db->select('a.*, k.nama as kategori_nama');
        $this->db->from('tb_arsip a');
        $this->db->join('tb_kategori_arsip k', 'k.id = a.kategori_id', 'left');
        $this->db->group_start();
        $this->db->like('a.no_berkas', $keyword, 'both');
        $this->db->or_like('a.kode', $keyword, 'both');
        $this->db->or_like('a.indeks_pekerjaan', $keyword, 'both');
        $this->db->or_like('a.uraian_masalah_kegiatan', $keyword, 'both');
        $this->db->group_end();
        $this->db->order_by('a.createDate', 'DESC');
        $arsip = $this->db->get()->result();
        
        if($arsip) {
            $result = [];
            foreach($arsip as $a) {
                $result[] = [
                    'id' => $a->id,
                    'no_berkas' => $a->no_berkas,
                    'no_urut' => $a->no_urut,
                    'kode' => $a->kode,
                    'indeks_pekerjaan' => $a->indeks_pekerjaan,
                    'uraian_masalah_kegiatan' => $a->uraian_masalah_kegiatan,
                    'kategori_nama' => $a->kategori_nama,
                    'tahun' => $a->tahun,
                    'jumlah_berkas' => $a->jumlah_berkas,
                    'asli_kopi' => $a->asli_kopi,
                    'box' => $a->box,
                    'klasifikasi_keamanan' => $a->klasifikasi_keamanan,
                    'nama_file' => $a->nama_file,
                    'ukuran_file' => $a->ukuran_file,
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

