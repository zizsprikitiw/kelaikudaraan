<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cms_bidang extends CI_Controller {
	
	//Global variable
	var $table_name = 'bidang';
	
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
		$this->data['page_title'] = "Manajemen Bidang";
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
			base_url($this->config->item('assets')['assets_scripts'])."/cms_bidang.js",
		);
		
		$this->load->view('layout/header', array('config' => $this->_config, 'data' => $this->data));
		$this->load->view('cms/cms_bidang', $this->data);
		$this->load->view('layout/footer', array('config' => $this->_config, 'data' => $this->data));				
	}				
	
	public function data_list()
	{		
		$datatable_name = "v_bidang";//$this->table_name
		$search_column = array('nama','nama_pusat');
		$search_order = array('nama_pusat' => 'desc', 'nama' => 'desc');
		
		$list = $this->cms_model->get_datatables($datatable_name, $search_column, $search_order);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->nama;
			$row[] = $list_item->nama_pusat;								
			
			//add html for action
			$row[] = '<div class="btn-group btn-group-sm" role="group"><a href="javascript:void()" class="btn btn-primary" title="Edit" onclick="data_edit('."'".$list_item->id."'".')">
                          <i class="la la-edit"></i>
                        </a>
						<a href="javascript:void()" class="btn btn-danger" title="Hapus" onclick="data_delete('."'".$list_item->id."','".$list_item->nama."'".')">
                          <i class="la la-times-circle"></i>
                        </a></div>';
																			
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->cms_model->count_all($datatable_name),
						"recordsFiltered" => $this->cms_model->count_filtered($datatable_name, $search_column, $search_order),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);		
	}
	
	public function data_add()
	{
		//fetch data from department and designation tables
        $data['pusat'] = $this->cms_model->get_pusat();    				
		echo json_encode($data);
	}
	
	public function data_edit($id)
	{				
		$data['pusat'] = $this->cms_model->get_pusat();  
		$data['list'] = $this->cms_model->row_get_by_id($id, $this->table_name);
		echo json_encode($data);
	}
	
	public function data_save_add()
	{				
		
        //set validation rules
		$this->form_validation->set_rules('nama', 'Bidang', 'trim|required|is_unique[' . $this->table_name . '.nama]'); 
		$this->form_validation->set_rules('pusat', 'Pusat', 'required|callback_combo_pusat');	
				
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
				'pusat_id'  => $this->input->post('pusat')
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
			$this->form_validation->set_rules('nama', 'Bidang', 'trim|required|is_unique[' . $this->table_name . '.nama]'); 
		}		
		$this->form_validation->set_rules('pusat', 'Pusat', 'required|callback_combo_pusat');	
		
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    
            //pass validation			
			$additional_data = array(				
				'nama' => $nama,
				'pusat_id'  => $this->input->post('pusat')
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
	
	//custom validation function for dropdown input
    function combo_pusat($str)
    {
        if ($str == '-- Pilih --')
        {
            $this->form_validation->set_message('combo_pusat', 'Kolom %s harus dipilih');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
	
	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
}

