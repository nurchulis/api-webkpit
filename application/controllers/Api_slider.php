<?php
//Api Produk
//Created by Nurchulis :D
//24 Januari 2020
//Just Do it
//Junita Nur Atika For 2021 Aamiin

use chriskacerguis\RestServer\RestController;

class Api_slider extends RestController {

    function __construct($config = 'rest') {
        parent::__construct($config);
    }

    // show data mahasiswa
    function index_get() {
        $parameter = $this->get('parameter');
        if ($parameter == 'hapus') {
            $slider = $this->db->get('slider')->result();
        } else {
            $slider = $this->db->get('slider')->result();
        }
        $this->response($slider, 200);
    }

    // insert new data to mahasiswa
    function index_post() {
        $id_slider = $this->post('id_slider');
        $this->db->where('id_slider', $id_slider);
        $slider = $this->db->get('slider')->result();
        if($slider){// Cek apakah id_slider ada


                if(@$_FILES["gambar"]["name"] == "") {
                    $file_name = ($slider[0]->gambar);
                }else{//Jika Gambar ada maka diupload dan file yang lama dihapus
                    //===========================Image Start Upload==============================
                    //load library
                    $this->load->library('upload');
                    //Set the config
                    $config['upload_path'] = './Assets/img/slider/'; //Use relative or absolute path
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
                       // echo $this->upload->display_errors();
                    }
                    //If the upload success
                    $file_name = $this->upload->file_name;
                    //Delete Old Image File
                    $old_file =($slider[0]->gambar); //Nama file Sebelumnya
                    $this->hapus_gambar($old_file);
                    $this->_create_thumbnail($file_name,100,150);
                    $this->hapus_now($file_name);
                    //===========================Image End Upload============================== 
                }
            //----------------Update to database-------------------------------------//
            $data_slider = array(
                        'judul'          => $this->post('judul'),
                        'gambar'         => $file_name,
                        'url'            => $this->post('url'),
                        'keterangan'     => $this->post('keterangan'));
            $this->db->where('id_slider', $id_slider); //Jika id_slider adalah benar
            $update = $this->db->update('slider', $data_slider); //Update to database
            //----------------Update to database-------------------------------------//

            if ($update) {
                $this->response(array('status' => 'success','data' => $data_slider), 200);
            } else {
                $this->response(array('status' => 'fail', 502));
            }
        } else {
            $this->response(array('status' => 'failed','Description' => 'id_slider tidak ada, mungkin gambar terlalu besar'), 200);
        }
    }

    function _create_thumbnail($fileName, $width, $height) //Membuat Gambar thumbnail atau ukuran kecil
    {
        $this->load->library('image_lib');
        $config['image_library']  = 'gd2';
        $config['source_image']   = './Assets/img/slider/'. $fileName;       
        $config['create_thumb']   = FALSE;
        $config['quality'] = '90%';
        $config['maintain_ratio'] = TRUE;
        $config['width']          = $width;
        $config['height']         = $height;
        $config['new_image']      = './Assets/img/slider/compresed/'. $fileName;               
        $this->image_lib->initialize($config);
        if (! $this->image_lib->resize()) { 
            echo $this->image_lib->display_errors();
        }                     
    }

    function hapus_gambar($old_file){ //Menghapus gambar lama saat proses update
      //  $old_file_thumb=$this->merge($old_file,'_thumb'); //Simpan File Thumbnail di folder Server
        $file_ori = './Assets/img/slider/compresed/'.$old_file;//Simpan File ori di folder Server
        unlink($file_ori);
    }
    function hapus_now($now_file){
        $file_ori = './Assets/img/slider/'.$now_file;//Simpan File ori di folder Server
        unlink($file_ori);   
    }


    function merge($file, $language){ //Merge Penyisipan kata "_thumb" pada file thumbnail
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $filename = str_replace('.'.$ext, '', $file).$language.'.'.$ext;
        return ($filename);
    }



}