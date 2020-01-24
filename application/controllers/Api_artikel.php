<?php
//Source Code API untuk Artikel 
//24 January 2020
//Nurchulis :D

use chriskacerguis\RestServer\RestController;
class Api_artikel extends RestController {

    function __construct($config = 'rest') {
        parent::__construct($config);
    }

    // show data mahasiswa
    function index_get() { 
        //Terdapat Function untuk
        //1. Menampilkan semua artikel
        //2. Menambah log artikel pengunjung
        //3. Menghapus Artikel berdasarkan id_artikel
        //4. Menampilkan artikel Berdasarkan id_artikel
        //5. Menampilkan Artikel terbaru berasarkan data tgl_edit artikel
        //6. Show Artikel Terpopulter 6 show

        $id_artikel = $this->get('id_artikel');
        $parameter  = $this->get('parameter'); //Parameter untuk menentukan action api

        if ($id_artikel == '') {
            if($parameter=='show_terbaru'){//5. Menampilkan Artikel terbaru berasarkan data tgl_edit artikel, 6 artikel
                $this->db->order_by('tgl_edit', 'DESC');
                $this->db->limit(6);
                $artikel= $this->db->get('artikel')->result();
            }else if($parameter=='show_populer') {//6. Menampilkan Artikel Terpopuler Sebanyak 6 Artikel
                $this->db->order_by('log_kunjungan', 'DESC');
                $this->db->limit(6);
                $artikel = $this->db->get('artikel')->result();
            }else {
                $this->db->order_by('tgl_edit', 'DESC');
                $artikel = $this->db->get('artikel')->result(); //1. Menampilkan semua artikel
            }
        } else {
            if($parameter=='hapus'){//3. Menghapus artikel berdasarkan id_artikel
                //$this->db->where('id_artikel', $id_artikel);
                //$this->db->delete('artikel');
                $this->db->where('id_artikel', $id_artikel);
                $artikel = $this->db->get('artikel')->result();
                $file_gambar=($artikel[0]->gambar); //Ambil File gambar berdasarkan id_artikel
                $this->hapus_gambar($file_gambar);
                $this->response(array('status' => 'success'), 201);

            }else if($parameter=='show'){ //4.Jika Parameter Nya adalah Untuk menampilakn data berdasarkan id_artikel
                $this->db->where('id_artikel', $id_artikel);
                $artikel = $this->db->get('artikel')->result();
            }else if($parameter=='log_penjunjung'){//2. Menambah log pengunjung artikel
                $query=$this->db->query("UPDATE artikel SET log_kunjungan = log_kunjungan+1 WHERE id_artikel=".$id_artikel."");
                $this->response(array('status' => 'success menambahkan log kunjungan'), 201);
            }else{
                $this->response(array('status' => 'noparameter'), 201);
            }
        }
        $this->response($artikel, 200);
    }

    // insert new data to mahasiswa
    function index_post() {
        $parameter = $this->post('parameter'); //paramater untuk menentukan apakah update atau posting
        $id_artikel = $this->post('id_artikel');
        if($parameter == 'update'){  //DIPILIH UPDATE
            //Data input Untuk Update artikel
            //Cek Apakah Gambar Ada
            if($_FILES["gambar"]["name"] == '') {
                $file_name = $this->post('gambar_sebelumnya');   
            }else{//Jika Gambar ada maka diupload dan file yang lama dihapus
                //===========================Image Start Upload==============================
                //load library
                $this->load->library('upload');
                //Set the config
                $config['upload_path'] = './Assets/img/artikel/'; //Use relative or absolute path
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
                $old_file = $this->post('gambar_sebelumnya');   //Nama file Sebelumnya
                $this->hapus_gambar($old_file);
                
                $this->_create_thumbnail($file_name,100,150);
                //===========================Image End Upload============================== 
            }
                $data_update = array(
                    'judul'    => $this->post('judul'),
                    'deskripsi_singkat' => $this->post('deskripsi_singkat'),
                    'gambar'        => $file_name,
                    'keterangan'        => $this->post('keterangan'),
                    'status'        => $this->post('status'),
                    'label'        => $this->post('label'),
                    'tgl_edit'        => date("Y-m-d h:i:s")
                );

                $this->db->where('id_artikel', $id_artikel);
                $update = $this->db->update('artikel', $data_update);
                    if ($update) {
                        $this->response($data_update, 200);
                    } else {
                        $this->response(array('status' => 'fail', 502));
                    }

            }else {
            //Data input Untuk Posting artikel

                //===========================Image Start Upload==============================
                //load library
                $this->load->library('upload');
                //Set the config
                $config['upload_path'] = './Assets/img/artikel/'; //Use relative or absolute path
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
                $uploadedImage = $this->upload->data();
                $this->_create_thumbnail($file_name,300,250);
                //===========================Image End Upload==============================

                $data_post = array(
                    'judul'    => $this->post('judul'),
                    'deskripsi_singkat' => $this->post('deskripsi_singkat'),
                    'gambar'        => $file_name,
                    'keterangan'        => $this->post('keterangan'),
                    'status'        => $this->post('status'),
                    'label'        => $this->post('label'),
                    'tgl_edit'        => date("Y-m-d h:i:s"),
                    'tgl_buat'          => date("Y-m-d h:i:s")
                );

                $insert = $this->db->insert('artikel', $data_post);
                if ($insert) {
                    $this->response($data_post, 200);
                } else {
                    $this->response(array('status' => 'fail', 502));
                }
            }
    }

    function _create_thumbnail($fileName, $width, $height) //Membuat Gambar thumbnail atau ukuran kecil
    {
        $this->load->library('image_lib');
        $config['image_library']  = 'gd2';
        $config['source_image']   = './Assets/img/artikel/'. $fileName;       
        $config['create_thumb']   = TRUE;
        $config['quality'] = '90%';
        $config['maintain_ratio'] = TRUE;
        $config['width']          = $width;
        $config['height']         = $height;
        $config['new_image']      = './Assets/img/artikel/thumbnail/'. $fileName;               
        $this->image_lib->initialize($config);
        if (! $this->image_lib->resize()) { 
            echo $this->image_lib->display_errors();
        }        
    }

    function hapus_gambar($old_file){ //Menghapus gambar lama saat proses update
        $file_ori = './Assets/img/artikel/'.$old_file;//Simpan File ori di folder Server
        unlink($file_ori);

        $old_file_thumb=$this->merge($old_file,'_thumb'); //Simpan File Thumbnail di folder Server
        $file_thumb = './Assets/img/artikel/thumbnail/'.$old_file_thumb;
        unlink($file_thumb);

    }

    function merge($file, $language){ //Merge Penyisipan kata "_thumb" pada file thumbnail
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $filename = str_replace('.'.$ext, '', $file).$language.'.'.$ext;
        return ($filename);
    }


 

}