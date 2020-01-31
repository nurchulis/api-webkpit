<?php
//Api prospektus
//Created by Nurchulis :D
//24 Januari 2020
//Just Do it
//Junita Nur Atika For 2021 Aamiin

use chriskacerguis\RestServer\RestController;

class Api_prospektus extends RestController {

    function __construct($config = 'rest') {
        parent::__construct($config);
    }


    function index_get() {
        $id_prospektus = $this->get('id_prospektus'); //Id unik untuk select by id_prospektus
        $parameter = $this->get('parameter'); //Paramter untuk menentukan action 
        $limit     = $this->get('limit');    //limit artikel yang ingin ditampilkan

        if ($parameter == '') {
            //==================Function Show Prospektus==========================//
                $this->db->order_by('id_prospektus', 'DESC');
                if(!$limit==''){ //Cek Apakah Data dilimit 
                    $this->db->limit($limit);   
                }
                $prospektus=$this->db->get('prospektus')->result();
            //===================================================//   
        } else if($parameter == 'show') { //2. Menampilkan Prospektus berdasarkan id_prospektus yang di pilih 

                $this->db->select('*');
                $this->db->from('prospektus');
                $this->db->where('id_prospektus', $id_prospektus);
                $prospektus = $this->db->get()->result();   //Menampilakan detail data prospektus Rumah

        } else if($parameter == 'hapus') {
            //-------------------------Hapus prospektus--------------------------//
            $this->db->where('id_prospektus', $id_prospektus);
            $prospektus = $this->db->get('prospektus')->result();
                if($prospektus){  //Cek Data prospektus Jika ada
                    for ($i=1; $i <=4 ; $i++) { 
                        $foto ="foto_".$i;
                        @$$foto;
                        $file_gambar=($prospektus[0]->$foto); //Ambil File gambar berdasarkan id_prospektus
                        $gambar=($prospektus[0]->foto_1);
                        $this->hapus_gambar_real($file_gambar); //Hapus Gambar Di Folder Real
                    }
                        $this->hapus_gambar_thumb($gambar); //Hapus Gambar thumbnail
                        //-----------------------------------------//

                        $this->db->where('id_prospektus', $id_prospektus);
                        $hasil=$this->db->delete('prospektus');

                        //-----------------------------------------//
                    if($hasil){
                        $this->response(array('status' => 'success'), 201);    
                    }else {
                        $this->response(array('status' => 'failed'), 201);
                    }
                } else { //Jika Data prospektus Tidak ada maka tampilkan Failed
                        $this->response(array('status' => 'failed'), 201);
                }
          

        }else {
            $this->response(array('status' => 'noparameter'), 201);
        }
        $this->response($prospektus, 200);
}
    // insert new data to mahasiswa
    function index_post() {
        $parameter = $this->post('parameter');
        $id_prospektus = $this->post('id_prospektus');

        if($parameter == 'update'){
            $this->db->where('id_prospektus', $id_prospektus);
            $prospektus = $this->db->get('prospektus')->result();
            if($prospektus){
            //Data Update Untuk Posting Prospektus
                //===========================Image Start Upload==============================
                //load library
                $this->load->library('upload');
                //Set the config
                $config['upload_path'] = './Assets/img/prospektus/'; //Use relative or absolute path
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
                $files_gambar=[];
                $nomor_gambar=0;
                for ($i=1; $i <5 ; $i++) { 
                    if(!empty($_FILES['foto_'.$i]['name'])){
                    if(!$this->upload->do_upload('foto_'.$i))
                        $this->upload->display_errors();  
                    else
                        $foto ="foto_".$i;
                        @$$foto;
                        
                       // echo $foto;
                       // echo "Foto berhasil di upload";
                        
                        $gambar = $this->upload->file_name;    

                        array_push($files_gambar, $gambar);    //Push Array Image Jika Gambar nya banyak
                        //echo $files_gambar[$nomor_gambar];

                        if($foto=="foto_1"){ //Jika Param Foto adalah foto_1 maka ganti thumbnail
                            $this->db->where('id_prospektus', $id_prospektus);
                            $prospektus = $this->db->get('prospektus')->result();
                            $file_gambar=($prospektus[0]->foto_1); //Ambil File gambar berdasarkan id_prospektus
                            $gambar=$files_gambar[0];

                            $this->update_thumb($gambar,300,250); //Update Gambar Thumbnail Create in folder Thumbnail
                            //$this->update_gambar_thumb($gambar,$id_prospektus); //Update TO DATABASE name Thumbnail
                            $this->hapus_gambar_thumb($file_gambar); //Hapus File gambar thumbnail yang lama
                        }


                        $this->_compres_ori_image($gambar);    //Compress Image To Folder /real

                        //==================Ambil Data Imagenya=======================//
                        $this->db->where('id_prospektus', $id_prospektus);
                        $prospektus = $this->db->get('prospektus')->result();
                        $file_gambar=($prospektus[0]->$foto); //Ambil File gambar berdasarkan id_artikel


                        $this->hapus_gambar_real($file_gambar); //Function Hapus Gambar Di Folder Real
                        $this->hapus_gambar($gambar);
                        
                        //===================End Data Imagenya========================//

                        //==================Image Update To Database==================//
                        $this->db->where('id_prospektus', $id_prospektus); //Update Multi Image di Table prospektus
                        $data_gambar= array($foto => $files_gambar[$nomor_gambar]);
                        $update = $this->db->update('prospektus', $data_gambar);

                        //==================Image Update To Database=================//
                        $nomor_gambar=$nomor_gambar+1; // Add +1 For Array data gambar                    
                    }

                   // $this->db->where('id_prospektus', $id_prospektus); //Update Gambar di Tabel prospektus
                   // $update = $this->db->update('prospektus', $data_gambar);
                    
                }
                //==========================Update prospektus To database===================//    
                $data_update = array(
                    'judul'             => $this->post('judul'),
                    'lokasi'            => $this->post('lokasi'),
                    'keterangan'        => $this->post('keterangan')
                );

                $this->db->where('id_prospektus', $id_prospektus);

                $update = $this->db->update('prospektus', $data_update);
                    if ($update) {
                        $this->response($data_update, 200);
                    } else {
                        $this->response(array('status' => 'fail', 502));
                    }
                //============================Update prospektus To database=================//    


                $this->response(array('status' => 'success'), 200);  
                if( ! $this->upload->do_upload("gambar")){
                    //echo the errors
                    echo $this->upload->display_errors();
                }
                //If the upload success
            }else {
                $this->response(array('status' => 'failed','Description'=> 'id_prospektus tidak ada'), 201);
            }

        } else if($parameter == ''){
            //Data input Untuk Posting prospektus
                //===========================Image Start Upload==============================
                //load library
                $this->load->library('upload');
                //Set the config
                $config['upload_path'] = './Assets/img/prospektus/'; //Use relative or absolute path
                $config['allowed_types'] = 'gif|jpg|png|jpeg'; 
                $config['max_size'] = FALSE;
                $config['overwrite'] = FALSE; //overwrite user avatar
                $config['encrypt_name'] = TRUE;
                $config['max_width'] = FALSE;
                $config['max_height'] = FALSE;
                $config['overwrite'] = FALSE; //If the file exists it will be saved with a progressive number appended
                //$this->load->library('upload',$config);
                //Initialize
                $this->upload->initialize($config);
                //Upload file
                $files_gambar=[];
                for ($i=1; $i <=5 ; $i++) { 
                    if(!empty($_FILES['foto_'.$i]['name'])){
                    if(!$this->upload->do_upload('foto_'.$i))
                        $hasil=$this->upload->display_errors();

                    else
                        $gambar = $this->upload->file_name;
                        //echo $gambar;
                        array_push($files_gambar, $gambar);
                    }

                    
                }
              
                //If the upload success
                $nomor=0;
                $hasil="";
                for($i=0; $i<count($files_gambar); $i++){
                    $file_name=$files_gambar[$i];
                    $hasil ="hasil".$nomor;
                    $$hasil=$file_name;
                    $nomor =$nomor+1;
                }
////////////////////////////////////////////////////////////////////////////////////////////////////                
                //==============Compress Image Real=============//                                //
                                                       //
                $this->_compres_ori_image($hasil0);
                $this->_compres_ori_image($hasil1);
                $this->_compres_ori_image($hasil2);
                $this->_compres_ori_image($hasil3);

                
                //$file_name = $this->upload->file_name;
                $uploadedImage = $this->upload->data();
                
                //=================Create Thumbnail=================//
                $this->_create_thumbnail($hasil0,300,250);
                //==================================================//

                //==============Delete Gambar Ori==============//
                //echo $hasil0;
                $this->hapus_gambar($hasil0);
                //echo $hasil1;
                $this->hapus_gambar($hasil1);
                //echo $hasil2;
                $this->hapus_gambar($hasil2);
                //echo $hasil3;
                $this->hapus_gambar($hasil3);

//////////////////////////////////////////////////////////////////////////////////////////////////

                $id_detail=mt_rand(1,10000);
                //===========================Image End Upload==============================     
                //---------------------------Data Utama ----------------------------------//   
                $data_post = array(
                    'judul'             => $this->post('judul'),
                    'foto_1'            => $hasil0,
                    'foto_2'            => $hasil1,
                    'foto_3'            => $hasil2,
                    'foto_4'            => $hasil3,
                    'lokasi'            => $this->post('lokasi'),    
                    'keterangan'        => $this->post('keterangan')
                );   

                $insert = $this->db->insert('prospektus', $data_post);
                if ($insert) {
                   $this->response($data_post, 200);
                } else {
                    $this->response(array('status' => 'fail', 502));
                }

        } else {
            $this->response(array('status' => 'noparameter'), 201);   
        }
    }

    function _create_thumbnail($fileName, $width, $height) //Membuat Gambar thumbnail atau ukuran kecil
    {
        $this->load->library('image_lib');
        $config['image_library']  = 'gd2';
        $config['source_image']   = './Assets/img/prospektus/'. $fileName;       
        $config['create_thumb']   = FALSE;
        $config['quality'] = '90%';
        $config['maintain_ratio'] = TRUE;
        $config['width']          = $width;
        $config['height']         = $height;
        $config['new_image']      = './Assets/img/prospektus/thumbnail/'. $fileName;               
        $this->image_lib->initialize($config);
        if (! $this->image_lib->resize()) { 
            echo $this->image_lib->display_errors();
        }     
                

    }
    function update_thumb($fileName, $width, $height)
    {
        $this->load->library('image_lib');
        $config['image_library']  = 'gd2';
        $config['source_image']   = './Assets/img/prospektus/'. $fileName;       
        $config['create_thumb']   = FALSE;
        $config['quality'] = '90%';
        $config['maintain_ratio'] = TRUE;
        $config['width']          = $width;
        $config['height']         = $height;
        $config['new_image']      = './Assets/img/prospektus/thumbnail/'. $fileName;               
        $this->image_lib->initialize($config);
        if (! $this->image_lib->resize()) { 
            echo $this->image_lib->display_errors();
        }     

    }
    function update_gambar_thumb($gambar,$id_prospektus)
    {
        //==================Image Update To Database==================//
        $this->db->where('id_prospektus', $id_prospektus); //Update Multi Image di Table prospektus
        $data_gambar= array('gambar' => $gambar);
        $update = $this->db->update('prospektus', $data_gambar);
    }

    function _compres_ori_image($fileName)
    {
     $this->load->library('image_lib');
        $config['image_library']  = 'gd2';
        $config['source_image']   = './Assets/img/prospektus/'. $fileName;       
        $config['create_thumb']   = FALSE;
        $config['quality'] = '100%';
        $config['maintain_ratio'] = TRUE;
        $config['width']          = 700;
        $config['height']         = 400;
        $config['new_image']      = './Assets/img/prospektus/real/'. $fileName;               
        $this->image_lib->initialize($config);
        if (! $this->image_lib->resize()) { 
            echo $this->image_lib->display_errors();
        } 
    }

    function hapus_gambar($old_file){ //Menghapus gambar lama saat proses update
        $file_ori = './Assets/img/prospektus/'.$old_file;//Simpan File ori di folder Server
        unlink($file_ori);

      /*  $old_file_thumb=$this->merge($old_file,'_thumb'); //Simpan File Thumbnail di folder Server
        $file_thumb = './Assets/img/artikel/prospektus/'.$old_file_thumb;
        unlink($file_thumb);
        */
    }

    function hapus_gambar_real($old_file){ //Menghapus gambar lama saat proses update
       // $old_file_thumb=$this->merge($old_file,'_thumb'); //Simpan File Thumbnail di folder Server
        $file_thumb = './Assets/img/prospektus/real/'.$old_file;
        unlink($file_thumb);

      /*  $old_file_thumb=$this->merge($old_file,'_thumb'); //Simpan File Thumbnail di folder Server
        $file_thumb = './Assets/img/artikel/prospektus/'.$old_file_thumb;
        unlink($file_thumb);
        */
    }
    function hapus_gambar_thumb($old_file){
       // $old_file_thumb=$this->merge($old_file,'_thumb'); //Simpan File Thumbnail di folder Server
        $file_thumb = './Assets/img/prospektus/thumbnail/'.$old_file;
        unlink($file_thumb);

    }

    function merge($file, $language){ //Merge Penyisipan kata "_thumb" pada file thumbnail
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $filename = str_replace('.'.$ext, '', $file).$language.'.'.$ext;
        return ($filename);
    }


}