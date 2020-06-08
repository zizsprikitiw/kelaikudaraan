<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pesawat extends CI_Controller {
	
	//Global variable
	var $table_name = 'proyek';
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth','form_validation','custom','encryption'));
		$this->load->helper(array('url','language','file'));
		$this->load->config('custom');
		$this->load->config('webconfig');
		$this->load->model('cms_model');
		setlocale(LC_ALL, "IND");
		
		$this->_config = array(
			"webtitle"    		=> $this->config->item('title'),
			"meta_keywords" 	=> $this->config->item('keywords'),
			"meta_descryption" 	=> $this->config->item('descryption'),
			"meta_author" 		=> $this->config->item('author'),
			"copyright" 		=> $this->config->item('copyright'),
		);
		
		$this->lang->load('auth');
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('login', 'refresh');			
		}
	}
	
	public function index()
	{																	
		$this->data['page_title'] = "Manajemen Pesawat";
		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$items	= $this->cms_model->get_menu_items();
		$menu	= $this->cms_model->generate_menu($items); 
		$this->data['user_menu'] = $menu;
		$this->data['user'] = $this->ion_auth->user()->row();	
		
		//tambahan css plugin
		$this->data['add_css'] = array(
		);
		
		//tambahan javascript plugin
		$this->data['add_javascript'] = array(
			base_url($this->config->item('assets')['assets_scripts'])."/pesawat.js",
		);
		
		$this->load->view('layout/header', array('config' => $this->_config, 'data' => $this->data));
		$this->load->view('pesawat', $this->data);
		$this->load->view('layout/footer', array('config' => $this->_config, 'data' => $this->data));		
	}		
	
	public function data_init()
	{
		$data['filter_pusat'] = $this->cms_model->get_pusat();  
		$data['filter_tahun'] = $this->cms_model->get_tahun_proyek($this->table_name);  
		
		echo json_encode($data);
	}
	
	public function data_list()
	{		
		$datatable_name = $this->table_name;
		$search_column = array('nama','singkatan','tahun');
		$search_order = array('tahun' => 'asc');
		$where =  array('tahun' => $_POST['filter_tahun'], 'pusat_id' => $_POST['filter_pusat']);//$this->input->post('filter_pusat')
		$order_by = 'pusat_id asc, tahun asc, ref_id asc, no_urut asc';		
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		
		$main = array();
		$sub = array();
		$i = 0;
		$ref = 0;
		
		foreach ($list as $list_item) {
			if(trim($list_item->ref_id) == ''){
				$main[] = $list_item;
			}else{
				if($ref != $list_item->ref_id){
					//create new
					$i++;
					$j=1;
				}
				$ref = $list_item->ref_id;				
				$sub[$i][$j] = $list_item;				
				$j++;
			}
		}
		
		$sub_len = count($sub);
		$main_len = count($main);
		$i = 1;
		foreach($main as $main_item){
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $main_item->nama;
			$row[] = $main_item->singkatan;
			$row[] = $main_item->tahun;									
			//add html for action  
			$i++;
			
			$row[] = '<div class="btn-group btn-group-sm" role="group">
					<a class="btn btn-sm btn-warning" href="javascript:void()" title="Edit" onclick="data_edit_proyek('."'".$main_item->id."'".')"><i class="la la-edit"></i></a>
					<a class="btn btn-sm btn-primary" href="javascript:void()" title="Upload" onclick="data_edit('."'".$main_item->id."'".')"><i class="la la-upload"></i></a></div>';
																			
			$data[] = $row;
						
		}
						
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->cms_model->count_all_where($datatable_name, $where),
			"recordsFiltered" => $this->cms_model->count_filtered_where($datatable_name, $search_column, $search_order, $where, $order_by),
			"data" => $data,
				);
		//output to json format
		echo json_encode($output);		
	}
	
	public function data_edit_proyek($id)
	{				
		$data['list'] = $this->cms_model->row_get_by_id($id, $this->table_name);
		
		if($data['list']->ref_id != ''){			
     		$sub_data = $this->cms_model->row_get_by_id($data['list']->ref_id, $this->table_name);	//get parent judul
			$data['ref_id_tahun'] = $sub_data->tahun;			
			//sub judul
			$data['ref_id'] = $this->cms_model->get_singkatan_proyek($this->table_name, $sub_data->tahun, $sub_data->pusat_id);
		}
		
		echo json_encode($data);
	}
	
	public function data_save_edit()
	{				
		$id = $this->input->post('id');
		$nama = $this->input->post('nama_pesawat');
		$data_list = $this->cms_model->row_get_by_id($id, $this->table_name);		//get user data sebelumnya
		
        //set validation rules
		//cek cek validasi jika berbeda 
		if($data_list->nama != $nama){
			$this->form_validation->set_rules('nama', 'Nama Pesawat', 'trim|required|is_unique[' . $this->table_name . '.nama]'); 
		}	
				
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));  
        }
        else
        {    
			//pass validation							
			$additional_data = array(				
				'nama' => $this->input->post('nama_pesawat'),
				'singkatan' => $this->input->post('singkatan'),
				'posisi' => $this->input->post('posisi'),
				'keterangan' => $this->input->post('keterangan')
			);
			
			if($this->cms_model->update(array("id" => $id), $additional_data, $this->table_name))
			{
				//berhasil update, lakukan update lainnya pada tabel user group dan user bidang								
				echo json_encode(array("status" => TRUE));
			}else{
				//gagal update
				echo json_encode(array("status" => "Gagal update data"));
			}		
        }		
	}
	
	public function data_edit($id)
	{				
		$data['pusat'] = $this->cms_model->get_pusat(); 		
		$data['select_tahun'] = $this->cms_model->get_tahun_proyek($this->table_name);  
		array_unshift($data['select_tahun'],array('id_item' => '--Pilih--', 'nama_item' => '--Pilih--'));
		
		$data['list'] = $this->cms_model->row_get_by_id($id, $this->table_name);
		
		if($data['list']->ref_id != ''){			
     		$sub_data = $this->cms_model->row_get_by_id($data['list']->ref_id, $this->table_name);	//get parent judul
			$data['ref_id_tahun'] = $sub_data->tahun;			
			//sub judul
			$data['ref_id'] = $this->cms_model->get_singkatan_proyek($this->table_name, $sub_data->tahun, $sub_data->pusat_id);
		}
		
		echo json_encode($data);
	}
	
	public function data_list_fle()
	{		
		$datatable_name = 'pesawat_gallery';
		$search_column = array();
		$search_order = array();
		$where =  array('proyek_id' => $_POST['proyek_id']);
		$order_by = 'submit_date desc';		
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach($list as $list_item){
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<a class="kt-badge kt-badge--primary kt-badge--inline ml-1" href="'.base_url($this->config->item('uploads')['pesawat']).'/'.$list_item->filename.'" target="_blank">Download</a>';		
			
			//add html for action
			$row[] = '<a href="javascript:void()" onclick="data_delete('."'".$list_item->id."','".$list_item->filename."'".')" class="kt-badge kt-badge--danger kt-badge--md kt-badge--rounded" title="Hapus"><i class="la la-times"></i></a>';
																			
			$data[] = $row;
						
		}
						
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->cms_model->count_all_where($datatable_name, $where),
			"recordsFiltered" => $this->cms_model->count_filtered_where($datatable_name, $search_column, $search_order, $where, $order_by),
			"data" => $data,
				);
		//output to json format
		echo json_encode($output);		
	}
	
	public function select_sub_judul()
	{							
		$data['ref_id'] = $this->cms_model->get_singkatan_proyek($this->table_name, $_POST['select_tahun'], $_POST['select_pusat']);		
		echo json_encode($data);
	}
	
	public function data_save_validation()
	{								      				
		//set validation rules
		$this->form_validation->set_rules('file_foto', 'File Foto', 'callback_check_file_lap');
						
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    						
			$user_id = $this->session->userdata('user_id');					
			
			//path directory
			$upload_path = $this->config->item('uploads')['pesawat'];
			//membuat directory jika belum ada
			$this->custom->makeDir($upload_path);
			
			$file_name = $_FILES["file_foto"]["name"];
			$file_name = preg_replace("/ /", '_', $file_name);
			$file_name = preg_replace("/&/", '_', $file_name);
			$file_name = preg_replace("/{/", '_', $file_name);
			$file_name = preg_replace("/}/", '_', $file_name);
			$upload_file = $upload_path.'/'.$file_name;
						
			if(is_file($upload_file)){
				$ext = pathinfo($_FILES['file_foto']['name'], PATHINFO_EXTENSION);
				$new_filename = str_replace('.'.$ext, '', $file_name);				
				$file_name = $new_filename.'_'.mdate("%Y%m%d-%H%i%s", $_SERVER['REQUEST_TIME']).'.'.$ext;
				$upload_file = $upload_path.$file_name;				
			}
			
			echo json_encode(array("status" => TRUE, "new_file_name" => $file_name));			
        }//END FORM VALIDATION TRUE		
	}
	
	function data_save_pesawat(){
		$user_id = $this->session->userdata('user_id');			
		$id = $this->input->post('id');																		
		$new_file_name = $this->input->post('new_file_name');														
		//path directory
		$upload_path = $this->config->item('uploads')['pesawat'];
		$upload_file = $upload_path.'/'.$new_file_name;
		
		move_uploaded_file($_FILES["file_foto"]["tmp_name"],$upload_file);	//UPLOAD THE FILE
		
		if(is_file($upload_file))
		{
			//update db		
			
			$additional_data = array(
					'proyek_id' => $id,
					'submit_user' => $user_id,
					'filename' => $new_file_name
			);	
			
			//Update foto pesawat			
			if($this->cms_model->save($additional_data, 'pesawat_gallery'))
			{
				//berhasil update, lakukan update lainnya pada tabel user group dan user bidang								
				echo json_encode(array("status" => TRUE));
			}else{
				if(is_file($upload_file)){
					unlink($upload_file);
				}	
				//gagal update
				echo json_encode(array("status" => "File gagal upload ! <br />Ulangi beberapa saat lagi."));
			}		
		}else{
			//File gagal upload				
			echo json_encode(array("status" => "File gagal upload ! <br />Ulangi beberapa saat lagi."));
		}
	}		
	
	//custom validation function for dropdown input
   function check_file_lap()
	{			
		if (!isset($_FILES['file_foto']) || empty($_FILES['file_foto']['name']) || ($_FILES['file_foto']['name'] == ''))
		{
			$this->form_validation->set_message('check_file_lap', 'Kolom %s belum diisi');
			return FALSE;	
		}else{
			$max_file_size = $this->config->item('files')['image_file_size'];
			if($_FILES['file_foto']['size'] > $max_file_size){
				//cek ukuran file
				$this->form_validation->set_message('check_file_lap', 'Ukuran file lebih dari '.$this->cms_model->bytesToSize($max_file_size));
				return FALSE;	
			}else{
				//cek file type				
				$file_type = $this->config->item('files')['image_file_type'];
				$ext = strtolower(pathinfo($_FILES['file_foto']['name'], PATHINFO_EXTENSION));
				
				if($ext == ""){
					//tidak punya ekstensi file
					$this->form_validation->set_message('check_file_lap', 'Format file hanya '.$file_type);
					return FALSE;
				}else{
					$str_pos = strpos($file_type, $ext);

					if ($str_pos !== FALSE) {
						return TRUE;	
					}else{
						$this->form_validation->set_message('check_file_lap', 'Format file hanya '.$file_type);
						return FALSE;					
					}	
				}
							
			}			
		}
	}
	
	public function data_delete()
	{			
        $id = $this->input->post('id_delete_data');	
		$tablename = 'pesawat_gallery';
		
		$file_row = $this->cms_model->row_get_by_id($id, $tablename);
		$status = FALSE;
		
		//hapus file
		if($file_row->id != ""){
			//hapus note
			try {				
				$this->cms_model->delete($tablename, array('id' => $id));	
				$upload_path = $this->config->item('uploads')['pesawat'];
				$filename = $file_row->filename;
				if(is_file("$upload_path/".$filename)){
					unlink("$upload_path/".$filename);
				}	
			
				$status = TRUE;
			} catch (Exception $e) {
				$status = FALSE;
			}				
		}
		
		echo json_encode(array("status" => $status));
	}	
	
	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
}

