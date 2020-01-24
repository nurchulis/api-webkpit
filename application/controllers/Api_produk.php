<?php
//Api Produk
//Created by Nurchulis :D
//24 Januari 2020
//Just Do it
//Junita Nur Atika For 2021 Aamiin

use chriskacerguis\RestServer\RestController;

class Api_produk extends RestController {

    function __construct($config = 'rest') {
        parent::__construct($config);
    }
    //Api Produk Function LIST
    //1. Show Produk Per Kategori: tanah, rumah 
    //2. Show Produk By id_produk
    //3. Show Produk Sesuai provinsi dan kategori yang sama
    //4. Show Produk Sesuai kabupaten dan kategori yang sama
    //5. Input Produk : tanah, rumah
    //6. Hapus Produk berdasarkan id_produk
    //7. Update Produk berdasarkan id_produk
    //8. 

    function index_get() {
        $id_produk = $this->get('id_produk'); //Id unik untuk select by id_produk
        $parameter = $this->get('parameter'); //Paramter untuk menentukan action 
        $kategori  = $this->get('kategori');  //Kategori untuk menentukan yang dipilih: tanah, rumah dll
        $provinsi  = $this->get('provinsi');  //Get Parameter Provinsi
        $kabupaten = $this->get('kabupaten'); //Get Parameter Kabupaten
        $limit     = $this->get('limit');

        if ($parameter == '') {
            if(!$kategori == ''){//Jika Kategori yang dipilih tidak kosong
            //==================Function For Filter Kategori, Provinsi, Kabupaten==========================//
                
                if(!$provinsi == '' && !$kabupaten == ''){
                    $this->db->where('provinsi', $provinsi);
                    $this->db->where('kabupaten', $kabupaten); 
                    $this->db->where('kategori', $kategori); //Berdasarkan Kategori
                    $this->db->order_by('tgl_edit', 'DESC'); //Menampilkan dari urutan tanggal paling terbaru
                    if(!$limit==''){ //Cek Apakah Data dilimit 
                        $this->db->limit($limit);   
                    }
                    $produk=$this->db->get('produk')->result();
                }else if(!$provinsi == '') {
                    $this->db->where('provinsi', $provinsi);
                    $this->db->where('kategori', $kategori);
                    $this->db->order_by('tgl_edit', 'DESC');
                    if(!$limit==''){ //Cek Apakah Data dilimit 
                        $this->db->limit($limit);   
                    }
                    $produk=$this->db->get('produk')->result();
                }else {
                    $this->db->where('kabupaten', $kabupaten);
                    $this->db->where('kategori', $kategori);
                    $this->db->order_by('tgl_edit', 'DESC');
                    if(!$limit==''){ //Cek Apakah Data dilimit 
                        $this->db->limit($limit);   
                    }
                    $produk=$this->db->get('produk')->result();
                }
            //===================================================//   
            } else {
                $produk = $this->db->get('produk')->result();
            }
        } else if($parameter == 'show') { //2. Menampilkan Produk berdasarkan id_produk yang di pilih 
            if($kategori == 'rumah'){
                $this->db->select('*');
                $this->db->from('produk');
                $this->db->join('detail_rumah', 'detail_rumah.id_detail = produk.id_detail');
                $this->db->where('id_produk', $id_produk);
                $produk = $this->db->get()->result();

            } else if($kategori == 'tanah') {
                $this->db->select('*');
                $this->db->from('produk');
                $this->db->join('detail_tanah', 'detail_tanah.id_detail = produk.id_detail');
                $this->db->where('id_produk', $id_produk);
                $produk = $this->db->get()->result();
            } else {
                $this->response(array('status' => 'failed', 200));
            }

        } else if($parameter == 'show_list'){ //1. Menampilkan Produk List
            $this->db->where('kategori', $kategori);
            $produk = $this->db->get('produk')->result();
        } else if($parameter == 'hapus') {
            $produk = $this->db->get('produk')->result();
        }else {
            $this->response(array('status' => 'noparameter'), 201);
        }
        $this->response($produk, 200);
    }

    // insert new data to mahasiswa
    function index_post() {
        $parameter = $this->get('parameter');
        $id_produk = $this->get('id_produk');
        if($parameter == 'update'){

        } else if($parameter == ''){
            //Data input Untuk Posting Produk

                //===========================Image Start Upload==============================
                //load library
                $this->load->library('upload');
                //Set the config
                $config['upload_path'] = './Assets/img/produk/'; //Use relative or absolute path
                $config['allowed_types'] = 'gif|jpg|png|jpeg'; 
                $config['max_size'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_width'] = FALSE;
                $config['max_height'] = FALSE;
                $config['overwrite'] = FALSE; //If the file exists it will be saved with a progressive number appended
                
                //$this->load->library('upload',$config);
                //Initialize
                $this->upload->initialize($config);
                //Upload file

               for ($i=1; $i <=4 ; $i++) { 
                    if(!empty($_FILES['foto_'.$i]['name'])){
                    if(!$this->upload->do_upload('foto_'.$i))
                        $this->upload->display_errors();  
                    else
                        echo "Foto berhasil di upload";
                    }
                }
                if( ! $this->upload->do_upload("gambar")){

                    //echo the errors
                    echo $this->upload->display_errors();
                }
                //If the upload success
                $file_name = $this->upload->file_name;
                $uploadedImage = $this->upload->data();
                $this->_create_thumbnail($file_name,300,250);
                //===========================Image End Upload==============================
                   
                $data_post = array(
                    'judul'             => $this->post('judul'),
                    'keterangan'        => $this->post('deskripsi_singkat'),
                    'gambar'            => 'a.png',
                    'status'            => $this->post('status'),
                    'kategori'          => $this->post('kategori'),
                    'sertifikat'        => $this->post('sertifikat'),
                    'provinsi'          => $this->post('provinsi'),
                    'kabupaten'         => $this->post('kabupaten'),
                    'harga'             => $this->post('harga'),
                    'foto_1'            => ,
                    'foto_2'            => ,
                    'foto_3'            => ,
                    'foto_4'            => ,
                    'id_detail'         => ,
                    'nomor_kontak'      => $this->post('nomor_kontak'),
                    'tgl_edit'          => date("Y-m-d h:i:s")
                );
        
               $insert = $this->db->insert('artikel', $data_post);
                if ($insert) {
                   $this->response($data_post, 200);
                } else {
                    $this->response(array('status' => 'fail', 502));
                }

        } else {
            $this->response(array('status' => 'noparameter'), 201);   
        }
    }

 
 

}