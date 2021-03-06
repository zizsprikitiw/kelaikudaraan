<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cms_struktural extends CI_Controller {
	
	//Global variable
	var $table_name = 'struktural';
	
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
		$this->data['page_title'] = "Manajemen Struktural";
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
			base_url($this->config->item('assets')['assets_scripts'])."/cms_struktural.js",
		);
		
		$this->load->view('layout/header', array('config' => $this->_config, 'data' => $this->data));
		$this->load->view('cms/cms_struktural', $this->data);
		$this->load->view('layout/footer', array('config' => $this->_config, 'data' => $this->data));					
	}		
	
	public function data_list()
	{		
		$search_column = array('nama');
		$search_order = array('nama' => 'desc');
		
		$list = $this->cms_model->get_datatables($this->table_name, $search_column, $search_order);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->nama;	
			$row[] = $list_item->singkatan;			
			
			//add html for action
			$row[] = '<div class="btn-group btn-group-sm" role="group"><a class="btn btn-primary" href="javascript:void()" title="Menu" onclick="struktural_menu('."'".$list_item->id."','".$list_item->nama."'".')"><i class="la la-sitemap"></i></a>
					<a class="btn btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$list_item->id."'".')"><i class="la la-edit"></i></a>
				  <a class="btn btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete('."'".$list_item->id."','".$list_item->nama."'".')"><i class="la la-times-circle"></i></a></div>';
																			
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->cms_model->count_all($this->table_name),
						"recordsFiltered" => $this->cms_model->count_filtered($this->table_name, $search_column, $search_order),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);		
	}		
		
	public function data_edit($id)
	{							
		$data['list'] = $this->cms_model->row_get_by_id($id, $this->table_name);
		echo json_encode($data);
	}
	
	public function data_save_add()
	{					
        //set validation rules
		$this->form_validation->set_rules('nama', 'Struktural', 'trim|required|is_unique[' . $this->table_name . '.nama]');	
		$this->form_validation->set_rules('singkatan', 'Singkatan', 'trim|required');		
				
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    							
            //pass validation			
			$additional_data = array(				
				'nama' => $this->input->post('nama'),
				'singkatan' => $this->input->post('singkatan')
			);
			
			$insert = $this->cms_model->save($additional_data, $this->table_name);										
			echo json_encode(array("status" => TRUE));
        }		
	}
	
	public function data_save_edit()
	{				
		$id = $this->input->post('id');
		$nama = $this->input->post('nama');
		$data_list = $this->cms_model->row_get_by_id($id, $this->table_name);		//get user data sebelumnya
		
        //set validation rules
		//cek cek validasi jika berbeda 
		if($data_list->nama != $nama){
			$this->form_validation->set_rules('nama', 'Struktural', 'trim|required|is_unique[' . $this->table_name . '.nama]'); 
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
				'nama' => $this->input->post('nama'),
				'singkatan' => $this->input->post('singkatan')			
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
	
	public function data_delete()
	{			
        $id = $this->input->post('id_delete_data');				
		
		//hapus user
		$this->cms_model->delete($this->table_name, array('id' => $id));		
		
		echo json_encode(array("status" => TRUE));
	}		
	
	public function menu_list()
	{
		//get user group menu list
		$id_struktural = $this->input->post('id_struktural');	
		$struktural_menu = $this->cms_model->struktural_menu($id_struktural);										

		//singkronisasi dengan menu
		$datatable_name = 'functions';
		$search_column = array('nama','halaman');
		$search_order = array('nama' => 'asc');
		$where =  '';
		$order_by = 'ref_id asc, no_urut asc';		
		
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
			$row[] = '<h3><i class="'.$main_item->icon.'">&nbsp;'.$main_item->nama.'</h3>';					
			
			$struktural_menu_id = $this->cms_model->menu_in_group($struktural_menu, $main_item->id);
			if($struktural_menu_id != 0){
				$row[] = '<button class="btn btn-sm btn-success" onclick="data_edit_status('."'".$main_item->id."','".$struktural_menu_id."'".')">On</button>';
			}else{
				$row[] = '<button class="btn btn-sm btn-danger" onclick="data_edit_status('."'".$main_item->id."','0'".')">Off</button>';
			}						
																			
			$data[] = $row;
						
			//cek sub menu
			//====================
			for($j=1; $j<=$sub_len; $j++){
				if($main_item->id == $sub[$j][1]->ref_id){
					//sub main terkait
					$k = 1;
					$sub_sub_len = count($sub[$j]);
					foreach($sub[$j] as $sub_item){
						$no++;
						$row = array();
						$row[] = $no;
						$row[] = '<div style="padding-left:30px;">--> '.$sub_item->nama.'</div>';						
						
						$struktural_menu_id = $this->cms_model->menu_in_group($struktural_menu, $sub_item->id);
						if($struktural_menu_id != 0){
							$row[] = '<button class="btn btn-sm btn-success" onclick="data_edit_status('."'".$sub_item->id."','".$struktural_menu_id."'".')">On</button>';
						}else{
							$row[] = '<button class="btn btn-sm btn-danger" onclick="data_edit_status('."'".$sub_item->id."','0'".')">Off</button>';
						}															
						
						$data[] = $row;
					}
				}
			}//end loop sub menu					
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
	
	public function data_edit_status()
	{
		$id_struktural = $this->input->post('id_struktural');	
		$id_menu = $this->input->post('id_menu');	
		$id_struktural_menu = $this->input->post('id_struktural_menu');
		
		if($id_struktural_menu == 0){
			//tambahkan pada authority
			//pass validation			
			$additional_data = array(				
				'functions_id' => $id_menu,
				'struktural_id'  => $id_struktural
			);
			
			$insert = $this->cms_model->save($additional_data, 'authority_struktural');			
		}else{
			//delete item authority
			$this->cms_model->delete('authority_struktural', array('id' => $id_struktural_menu));				
		}
		
		echo json_encode(array("status" => TRUE));
	}
	
	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
}

