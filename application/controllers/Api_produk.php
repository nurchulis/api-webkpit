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

    function index_get() {
        $id_produk = $this->get('id_produk'); //Id unik untuk select by id_produk
        $parameter = $this->get('parameter'); //Paramter untuk menentukan action 
        $kategori  = $this->get('kategori');  //Kategori untuk menentukan yang dipilih: tanah, rumah dll
        $provinsi  = $this->get('provinsi');  //Get Parameter Provinsi
        $kabupaten = $this->get('kabupaten'); //Get Parameter Kabupaten
        $limit     = $this->get('limit');    //limit artikel yang ingin ditampilkan

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
                    $produk=$this->db->get('produk')->result(); //Menampilkan data produk berdasarkan Provinsi dan kabupaten
                }else if(!$provinsi == '') {
                    $this->db->where('provinsi', $provinsi);  //Jika Provinsi nya sama
                    $this->db->where('kategori', $kategori);  //Jika Kategorinya sama
                    $this->db->order_by('tgl_edit', 'DESC');  //Berdarkan tgl_edit terbaru
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
                $produk = $this->db->get()->result();   //Menampilakan detail data produk Rumah

            } else if($kategori == 'tanah') {
                $this->db->select('*');
                $this->db->from('produk');
                $this->db->join('detail_tanah', 'detail_tanah.id_detail = produk.id_detail');
                $this->db->where('id_produk', $id_produk);
                $produk = $this->db->get()->result();   //Menampilkan detail data produk tanah
            } else {
                $this->response(array('status' => 'failed'), 200);
            }

        } else if($parameter == 'show_list'){ //1. Menampilkan Produk List
            $this->db->where('kategori', $kategori);
            $produk = $this->db->get('produk')->result();
        } else if($parameter == 'hapus') {
            //-------------------------Hapus Produk--------------------------//
            $this->db->where('id_produk', $id_produk);
            $produk = $this->db->get('produk')->result();
            if(!$kategori==''){ //cek Parameter Kategori tidak kosong
                if($produk){  //Cek Data Produk Jika ada
                    for ($i=1; $i <=4 ; $i++) { 
                        $foto ="foto_".$i;
                        @$$foto;
                        $file_gambar=($produk[0]->$foto); //Ambil File gambar berdasarkan id_produk
                        $gambar=($produk[0]->gambar);
                        $this->hapus_gambar_real($file_gambar); //Hapus Gambar Di Folder Real
                    }
                        $this->hapus_gambar_thumb($gambar); //Hapus Gambar thumbnail
                        //-----------------------------------------//

                        $this->db->where('id_produk', $id_produk);
                        $hasil=$this->db->delete('produk');

                        $id_detail = ($produk[0]->id_detail); // Ambil Id detail Produk
                        if($kategori=='rumah'){  // Menghapus Data Detail Produk di Tabel Detail
                            $this->db->where('id_detail', $id_detail);
                            $hapus_detail=$this->db->delete('detail_rumah');
                        }else {
                            $this->db->where('id_detail', $id_detail);
                            $hapus_detail=$this->db->delete('detail_tanah');
                        }

                        //-----------------------------------------//
                    if($hasil){
                        $this->response(array('status' => 'success'), 201);    
                    }else {
                        $this->response(array('status' => 'failed'), 201);
                    }
                } else { //Jika Data Produk Tidak ada maka tampilkan Failed
                        $this->response(array('status' => 'failed'), 201);
                }
            } else {
                $this->response(array('status' => 'failed','description' => 'parameter kategori belum dimasukan'), 201); 
            }

        }else if ($parameter == 'log_penjunjung') {
            $query=$this->db->query("UPDATE produk SET log_kunjungan = log_kunjungan+1 WHERE id_produk=".$id_produk.""); // Menambah Kan Long Kunjungan ke produk
            $this->response(array('status' => 'success menambahkan log kunjungan'), 201);
        }else {
            $this->response(array('status' => 'noparameter'), 201);
        }
        $this->response($produk, 200);
}
    // insert new data to mahasiswa
    function index_post() {
        $parameter = $this->post('parameter');
        $id_produk = $this->post('id_produk');
        $kategori  = $this->post('kategori');


        if($parameter == 'update'){
            $this->db->where('id_produk', $id_produk);
            $produk = $this->db->get('produk')->result();
            if($produk){
            $id_detail =($produk[0]->id_detail); //Ambil Data id_detail per produk
            //Data Update Untuk Posting Produk
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
                $files_gambar=[];
                $nomor_gambar=0;
                for ($i=1; $i <=5 ; $i++) { 
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
                            $this->db->where('id_produk', $id_produk);
                            $produk = $this->db->get('produk')->result();
                            $file_gambar=($produk[0]->gambar); //Ambil File gambar berdasarkan id_produk
                            $gambar=$files_gambar[0];

                            $this->update_thumb($gambar,300,250); //Update Gambar Thumbnail Create in folder Thumbnail
                            $this->update_gambar_thumb($gambar,$id_produk); //Update TO DATABASE name Thumbnail
                            $this->hapus_gambar_thumb($file_gambar); //Hapus File gambar thumbnail yang lama
                        }


                        $this->_compres_ori_image($gambar);    //Compress Image To Folder /real

                        //==================Ambil Data Imagenya=======================//
                        $this->db->where('id_produk', $id_produk);
                        $produk = $this->db->get('produk')->result();
                        $file_gambar=($produk[0]->$foto); //Ambil File gambar berdasarkan id_artikel


                        $this->hapus_gambar_real($file_gambar); //Function Hapus Gambar Di Folder Real
                        $this->hapus_gambar($gambar);
                        
                        //===================End Data Imagenya========================//

                        //==================Image Update To Database==================//
                        $this->db->where('id_produk', $id_produk); //Update Multi Image di Table Produk
                        $data_gambar= array($foto => $files_gambar[$nomor_gambar]);
                        $update = $this->db->update('produk', $data_gambar);

                        //==================Image Update To Database=================//
                        $nomor_gambar=$nomor_gambar+1; // Add +1 For Array data gambar                    
                    }

                   // $this->db->where('id_produk', $id_produk); //Update Gambar di Tabel Produk
                   // $update = $this->db->update('produk', $data_gambar);
                    
                }
                //==========================Update Produk To database===================//    
                $data_update = array(
                    'judul'             => $this->post('judul'),
                    'keterangan'        => $this->post('keterangan'),
                    'status'            => $this->post('status'),
                    'kategori'          => $this->post('kategori'),
                    'sertifikat'        => $this->post('sertifikat'),
                    'provinsi'          => $this->post('provinsi'),
                    'kabupaten'         => $this->post('kabupaten'),
                    'harga'             => $this->post('harga'),
                    'nomor_kontak'      => $this->post('nomor_kontak'),
                    'nama_cs'           => $this->post('nama_cs'),    
                    'tgl_edit'          => date("Y-m-d h:i:s")
                );

                //--------------------------Data Jika Kategori Rumah--------------------------//
                if($kategori == 'rumah'){
                $data_detail = array(
                    'id_detail'         => $id_detail,
                    'luas_tanah'        => $this->post('luas_tanah'), 
                    'luas_bangunan'     => $this->post('luas_bangunan'),
                    'kamar_tidur'       => $this->post('kamar_tidur'),
                    'kamar_mandi'       => $this->post('kamar_mandi'),
                    'garasi'            => $this->post('garasi'),
                    'spesifikasi_lain'  => $this->post('spesifikasi_lain'));

             
                $this->db->where('id_detail', $id_detail);
                $update = $this->db->update('detail_rumah', $data_detail);   //Update detail produk Rumah

                } else {
                //--------------------------Data Jika Kategori Tanah--------------------------//
                $data_detail = array(
                    'luas_tanah'        => $this->post('luas_tanah'));
                $this->db->where('id_detail', $id_detail);
                $update = $this->db->update('detail_tanah', $data_detail);    
                }

                $this->db->where('id_produk', $id_produk);

                $update = $this->db->update('produk', $data_update);
                    if ($update) {
                        $this->response($data_update, 200);
                    } else {
                        $this->response(array('status' => 'fail', 502));
                    }
                //============================Update Produk To database=================//    


                $this->response(array('status' => 'success'), 200);  
                if( ! $this->upload->do_upload("gambar")){
                    //echo the errors
                    echo $this->upload->display_errors();
                }
                //If the upload success
            }else {
                $this->response(array('status' => 'failed','Description'=> 'id_produk tidak ada'), 201);
            }

        } else if($parameter == ''){
            //Data input Untuk Posting Produk
                //===========================Image Start Upload==============================
                //load library
                $this->load->library('upload');
                //Set the config
                $config['upload_path'] = './Assets/img/produk/'; //Use relative or absolute path
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
                        array_push($files_gambar, $gambar);
                    }

                    
                }
              
                //If the upload success
                $nomor=0;
                $hasil="";
                for($i=0; $i<count($files_gambar); $i++){
                    $file_name=$files_gambar[$i];
                    $hasil ="hasil".$nomor;
                    $nomor =$nomor+1;
                    $$hasil=$file_name;
                }
      
////////////////////////////////////////////////////////////////////////////////////////////////////                
                //==============Compress Image Real=============//                                //
                $foto_hasil=$hasil0;                                                              //
                $this->_compres_ori_image($foto_hasil);
                $this->_compres_ori_image($hasil1);
                $this->_compres_ori_image($hasil2);
                $this->_compres_ori_image($hasil3);
                
                //$file_name = $this->upload->file_name;
                $uploadedImage = $this->upload->data();
                
                //=================Create Thumbnail=================//
                $this->_create_thumbnail($hasil0,300,250);
                //==================================================//

                //==============Delete Gambar Ori==============//
                $this->hapus_gambar($hasil0);
                $this->hapus_gambar($hasil1);
                $this->hapus_gambar($hasil2);
                $this->hapus_gambar($hasil3);
//////////////////////////////////////////////////////////////////////////////////////////////////

                $id_detail=mt_rand(1,10000);
                $kategori =$this->post('kategori');
                //===========================Image End Upload==============================     
                //---------------------------Data Utama ----------------------------------//   
                $data_post = array(
                    'judul'             => $this->post('judul'),
                    'keterangan'        => $this->post('keterangan'),
                    'gambar'            => $hasil0,
                    'status'            => $this->post('status'),
                    'kategori'          => $this->post('kategori'),
                    'sertifikat'        => $this->post('sertifikat'),
                    'provinsi'          => $this->post('provinsi'),
                    'kabupaten'         => $this->post('kabupaten'),
                    'harga'             => $this->post('harga'),
                    'foto_1'            => $hasil0,
                    'foto_2'            => $hasil1,
                    'foto_3'            => $hasil2,
                    'foto_4'            => $hasil3,
                    'id_detail'         => $id_detail,
                    'nama_cs'           => $this->post('nama_cs'),    
                    'tgl_edit'          => date("Y-m-d h:i:s")
                );
                //--------------------------Data Jika Kategori Rumah--------------------------//
                if($kategori == 'rumah'){
                $data_detail = array(
                    'id_detail'         => $id_detail,
                    'luas_tanah'        => $this->post('luas_tanah'), 
                    'luas_bangunan'     => $this->post('luas_bangunan'),
                    'kamar_tidur'       => $this->post('kamar_tidur'),
                    'kamar_mandi'       => $this->post('kamar_mandi'),
                    'garasi'            => $this->post('garasi'),
                    'spesifikasi_lain'  => $this->post('spesifikasi_lain'));
                $insert = $this->db->insert('detail_rumah', $data_detail);
                } else {
                //--------------------------Data Jika Kategori Tanah--------------------------//
                $data_detail = array(
                    'id_detail'         => $id_detail,
                    'luas_tanah'        => $this->post('luas_tanah'));
                $insert = $this->db->insert('detail_tanah', $data_detail);    
                }   

                $insert = $this->db->insert('produk', $data_post);
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
        $config['source_image']   = './Assets/img/produk/'. $fileName;       
        $config['create_thumb']   = FALSE;
        $config['quality'] = '90%';
        $config['maintain_ratio'] = TRUE;
        $config['width']          = $width;
        $config['height']         = $height;
        $config['new_image']      = './Assets/img/produk/thumbnail/'. $fileName;               
        $this->image_lib->initialize($config);
        if (! $this->image_lib->resize()) { 
            echo $this->image_lib->display_errors();
        }     
                

    }
    function update_thumb($fileName, $width, $height)
    {
        $this->load->library('image_lib');
        $config['image_library']  = 'gd2';
        $config['source_image']   = './Assets/img/produk/'. $fileName;       
        $config['create_thumb']   = FALSE;
        $config['quality'] = '90%';
        $config['maintain_ratio'] = TRUE;
        $config['width']          = $width;
        $config['height']         = $height;
        $config['new_image']      = './Assets/img/produk/thumbnail/'. $fileName;               
        $this->image_lib->initialize($config);
        if (! $this->image_lib->resize()) { 
            echo $this->image_lib->display_errors();
        }     

    }
    function update_gambar_thumb($gambar,$id_produk)
    {
        //==================Image Update To Database==================//
        $this->db->where('id_produk', $id_produk); //Update Multi Image di Table Produk
        $data_gambar= array('gambar' => $gambar);
        $update = $this->db->update('produk', $data_gambar);
    }

    function _compres_ori_image($fileName)
    {
     $this->load->library('image_lib');
        $config['image_library']  = 'gd2';
        $config['source_image']   = './Assets/img/produk/'. $fileName;       
        $config['create_thumb']   = FALSE;
        $config['quality'] = '100%';
        $config['maintain_ratio'] = TRUE;
        $config['width']          = 700;
        $config['height']         = 400;
        $config['new_image']      = './Assets/img/produk/real/'. $fileName;               
        $this->image_lib->initialize($config);
        if (! $this->image_lib->resize()) { 
            echo $this->image_lib->display_errors();
        } 
    }

    function hapus_gambar($old_file){ //Menghapus gambar lama saat proses update
        $file_ori = './Assets/img/produk/'.$old_file;//Simpan File ori di folder Server
        unlink($file_ori);

      /*  $old_file_thumb=$this->merge($old_file,'_thumb'); //Simpan File Thumbnail di folder Server
        $file_thumb = './Assets/img/artikel/produk/'.$old_file_thumb;
        unlink($file_thumb);
        */
    }

    function hapus_gambar_real($old_file){ //Menghapus gambar lama saat proses update
       // $old_file_thumb=$this->merge($old_file,'_thumb'); //Simpan File Thumbnail di folder Server
        $file_thumb = './Assets/img/produk/real/'.$old_file;
        unlink($file_thumb);

      /*  $old_file_thumb=$this->merge($old_file,'_thumb'); //Simpan File Thumbnail di folder Server
        $file_thumb = './Assets/img/artikel/produk/'.$old_file_thumb;
        unlink($file_thumb);
        */
    }
    function hapus_gambar_thumb($old_file){
       // $old_file_thumb=$this->merge($old_file,'_thumb'); //Simpan File Thumbnail di folder Server
        $file_thumb = './Assets/img/produk/thumbnail/'.$old_file;
        unlink($file_thumb);

    }

    function merge($file, $language){ //Merge Penyisipan kata "_thumb" pada file thumbnail
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $filename = str_replace('.'.$ext, '', $file).$language.'.'.$ext;
        return ($filename);
    }


}