<?php

use chriskacerguis\RestServer\RestController;

class Api_gallery extends RestController {

    function __construct($config = 'rest') {
        parent::__construct($config);
    }

    // show data gallery
    function index_get() {
        $parameter  = $this->get('parameter');
        $id_gallery = $this->get('id_gallery');
        if ($parameter == '') {
            $gallery = $this->db->get('gallery')->result();
        } else if($parameter == 'hapus') {
            $this->db->where('id_gallery', $id_gallery);
            $artikel = $this->db->get('gallery')->result();
            if($artikel){    
                    $file_gambar=($artikel[0]->url); //Ambil File gambar berdasarkan id_artikel
                    $this->hapus_gambar_real($file_gambar);  //Menghapus File Gambar yang ada dalam Folder Thumbnail dan Artikel
                    //-----------------------------------------//
                    $this->db->where('id_gallery', $id_gallery); //Function Untuk Menghapus berdasarkan id_arikel
                    $hapus=$this->db->delete('gallery');         //   
                    //-----------------------------------------//
                    if($hapus){ //Cek apakah Berhasil Menghapus
                        $this->response(array('status' => 'success'), 201); //Jika ya maka success
                        }else{
                        $this->response(array('status' => 'failed'), 201); //Jika tidak maka failed
                    }
            } else { //Jika id_artikel salah atau jika data sudah dihapus
                    $this->response(array('status' => 'failed','Description' => 'id_gallery salah gan atau data sudah dihapus'), 201);
            }


        }
        $this->response($gallery, 200);
    }
    // insert new data to mahasiswa
    function index_post() {
        $parameter = $this->post('parameter');
        $id_gallery = $this->post('id_gallery');
        $this->db->where('id_gallery', $id_gallery);
        $gallery = $this->db->get('gallery')->result();

        if($parameter == 'update') { //Cek Apakah Parameter nya adalah untuk update

            if(@$_FILES["gambar"]["name"] == "") {
                $file_name =($gallery[0]->url); //Nama file Sebelumnya
            } else {
            //===========================Image Start Upload==============================
            //load library
            $this->load->library('upload');
            //Set the config
            $config['upload_path'] = './Assets/img/gallery/'; //Use relative or absolute path
            $config['allowed_types'] = 'gif|jpg|png|jpeg'; 
            $config['max_size'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $config['max_width'] = FALSE;
            $config['max_height'] = FALSE;
            $config['overwrite'] = FALSE; //If the file exists it will be saved with a progressive number appended
            //Initialize

            $this->upload->initialize($config);
                //Upload file
                if( ! $this->upload->do_upload("gambar")){
                    //echo the errors
                    echo $this->upload->display_errors();
                }
                //If the upload success
                $file_name = $this->upload->file_name;
                //Delete Old Image File
                $this->_create_thumbnail($file_name,1000,600);
                $old_file =($gallery[0]->url); //Nama file Sebelumnya
                $this->hapus_gambar_real($old_file);
                //Delete Image in folder Gallery
                $this->hapus_gambar($file_name);

            //===========================Image End Upload============================== 
            }
            $data_update = array(
                        'judul'       => $this->post('judul'),
                        'kategori'    => $this->post('kategori'),
                        'url'         => $file_name);

            $this->db->where('id_gallery', $id_gallery); //Dimana id_gallery nya sama
            $insert = $this->db->update('gallery', $data_update); //Update to database Gallery
            if ($insert) {
                $this->response($data_update, 200);
            } else {
                $this->response(array('status' => 'fail', 502));
            }
        //Jika Parameter bukan untuk update
        } else {
            //===========================Image Start Upload==============================
            //load library
            $this->load->library('upload');
            //Set the config
            $config['upload_path'] = './Assets/img/gallery/'; //Use relative or absolute path
            $config['allowed_types'] = 'gif|jpg|png|jpeg'; 
            $config['max_size'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $config['max_width'] = FALSE;
            $config['max_height'] = FALSE;
            $config['overwrite'] = FALSE; //If the file exists it will be saved with a progressive number appended
            //Initialize

            $this->upload->initialize($config);
                //Upload file
                if( ! $this->upload->do_upload("gambar")){
                    //echo the errors
                    echo $this->upload->display_errors();
                }
                //If the upload success
                $file_name = $this->upload->file_name;
                //Delete Old Image File
                $this->_create_thumbnail($file_name,1000,600);
                //Delete Image in folder Gallery
                $this->hapus_gambar($file_name);


            //===========================Image End Upload============================== 
            $data = array(
                        'judul'       => $this->post('judul'),
                        'kategori'    => $this->post('kategori'),
                        'url'         => $file_name);
            $insert = $this->db->insert('gallery', $data);
            if ($insert) {
                $this->response($data, 200);
            } else {
                $this->response(array('status' => 'fail', 502));
            }

        }
    }

    function _create_thumbnail($fileName, $width, $height) //Membuat Gambar thumbnail atau ukuran kecil
    {
        $this->load->library('image_lib');
        $config['image_library']  = 'gd2';
        $config['source_image']   = './Assets/img/gallery/'. $fileName;       
        $config['create_thumb']   = FALSE;
        $config['quality'] = '90%';
        $config['maintain_ratio'] = TRUE;
        $config['width']          = $width;
        $config['height']         = $height;
        $config['new_image']      = './Assets/img/gallery/real/'. $fileName;               
        $this->image_lib->initialize($config);
        if (! $this->image_lib->resize()) { 
            echo $this->image_lib->display_errors();
        }        
    }

    function hapus_gambar($old_file){ //Menghapus gambar lama saat proses update
        $file_ori = './Assets/img/gallery/'.$old_file;//Simpan File ori di folder Server
        unlink($file_ori);

      /*  $old_file_thumb=$this->merge($old_file,'_thumb'); //Simpan File Thumbnail di folder Server
        $file_thumb = './Assets/img/artikel/produk/'.$old_file_thumb;
        unlink($file_thumb);
        */
    }

    function hapus_gambar_real($old_file){
        $file_ori = './Assets/img/gallery/real/'.$old_file;//Simpan File ori di folder Server
        unlink($file_ori);
    }

}