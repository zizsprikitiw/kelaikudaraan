<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {
	
	function __construct()
	{
			parent::__construct();
			$this->load->database();
			$this->load->library(array('ion_auth','form_validation','custom'));
			$this->load->helper(array('url','language','file'));
			$this->load->config('custom');
			$this->load->model('cms_model');	
			
			$this->lang->load('auth');
			if (!$this->ion_auth->logged_in())
			{
				// redirect them to the login page
				redirect('login', 'refresh');			
			}
	}
	
	public function index()
	{
		$this->data['title'] = "Profile";
		$this->data['user_menu'] = $this->cms_model->get_user_menu($this->uri->rsegment(1));
		$this->data['user'] = $this->ion_auth->user()->row();
		
		//tambahan css plugin
		$this->data['add_css'] = array(
			base_url($this->config->item('assets')['global_plugins'])."/bootstrap-fileinput/bootstrap-fileinput.css",
			base_url($this->config->item('assets')['global_plugins'])."/jcrop/css/jquery.Jcrop.min.css",
			base_url($this->config->item('assets')['custom_css'])."/profile.css",
		);
		
		//tambahan javascript plugin
		$this->data['add_javascript'] = array(
			base_url($this->config->item('assets')['global_plugins'])."/bootstrap-fileinput/bootstrap-fileinput.js",
			base_url($this->config->item('assets')['global_plugins'])."/jquery.sparkline.min.js",
			base_url($this->config->item('assets')['global_plugins'])."/jcrop/js/jquery.color.js",
			base_url($this->config->item('assets')['global_plugins'])."/jcrop/js/jquery.Jcrop.min.js",
			base_url($this->config->item('assets')['custom_scripts'])."/profile.js",
		);
		
		$this->_render_page('profile', $this->data);		
	}
	
	// change avatar
	function change_avatar()
	{
		$user_id = $this->session->userdata('user_id');
		$status = 'error';
        $message = 'Upload image gagal';
			
		//set validation rules
		$this->form_validation->set_rules('file_avatar', 'Image', "callback_check_image_required"); 

		if ($this->form_validation->run() == false)
		{			
			//validation fails
			$status = 'warning';
            $message = validation_errors();
		}
		else
		{
			//path directory
			$upload_path = $this->config->item('uploads')['users'];
			//membuat directory jika belum ada
			$this->custom->makeDir($upload_path);
			
			$file_name = "avatar_".$user_id.".jpg";
			
			$config['upload_path'] = "$upload_path";
			$config['allowed_types'] = "*";
			$config['remove_spaces'] = TRUE;
			$config['file_name'] = $file_name;
			$config['overwrite'] = TRUE; // true berfungsi untuk replace
			//$config['max_width'] = '1024';
			//$config['max_height'] = '768';
			$this->load->library('upload', $config);
			//Original Image Upload - End
			
			if (!$this->upload->do_upload('cropped_image'))
			{
				$status = 'error';
				$message = $this->upload->display_errors('', '');
			} else {
				$file_path = $this->upload->data();
				$doc_path = $file_path['file_name'];
				$this->load->library('image_lib');
				//path directory
				$upload_path_thumb50x50 = $this->config->item('uploads')['users_thumb50x50'];
				//membuat directory jika belum ada
				$this->custom->makeDir($upload_path_thumb50x50);
				
				//Thumbnail Image
				$config['image_library'] = 'gd2';
				$config['source_image'] =  "$upload_path/".$doc_path;
				$config['new_image'] = "$upload_path/".$doc_path;
				$config['width'] = 600;
				$config['height'] = 600;
				//load resize library
				$this->image_lib->clear();
				$this->image_lib->initialize($config);
				$this->image_lib->resize();
				//Thumbnail Image Upload - End			
				
				//Thumbnail Image 50x50
				$config['image_library'] = 'gd2';
				$config['source_image'] =  "$upload_path/".$doc_path;
				$config['new_image'] = "$upload_path_thumb50x50/".$doc_path;
				$config['width'] = 50;
				$config['height'] = 50;
				//load resize library
				$this->image_lib->clear();
				$this->image_lib->initialize($config);
				$this->image_lib->resize();
				//Thumbnail Image 50x50 Upload - End		
				
				if(is_file("$upload_path/".$doc_path))
				{
					try {
						$this->cms_model->update(array('id' => $user_id),array('photo'=>$doc_path),'users');	
						$status = 'success';
						$message = 'Sukses upload image';
					} catch (Exception $e) {
						$status = 'error';
						$message = $e->getMessage();
					}
				}
			}
			
		}
		echo json_encode(array("status" => $status, "message" => $message));
	}
	
	//custom validation function for dropdown input
	function check_image_required()
	{			
		if (!isset($_FILES['file_avatar']) || empty($_FILES['file_avatar']['name']) || ($_FILES['file_avatar']['name'] == ''))
		{
			$this->form_validation->set_message('check_image_required', 'Kolom %s belum diisi');
			return FALSE;	
		}else{
			$max_file_size = $this->config->item('files')['image_file_size'];
			if($_FILES['file_avatar']['size'] > $max_file_size){
				//cek ukuran file
				$this->form_validation->set_message('check_image_required', 'Ukuran file lebih dari '.$this->custom->bytesToSize($max_file_size));
				return FALSE;	
			}else{
				//cek file type				
				$file_type = $this->config->item('files')['image_file_type'];
				$ext = strtolower(pathinfo($_FILES['file_avatar']['name'], PATHINFO_EXTENSION));
				
				if($ext == ""){
					//tidak punya ekstensi file
					$this->form_validation->set_message('check_image_required', 'Format file hanya '.$file_type);
					return FALSE;
				}else{
					$str_pos = strpos($file_type, $ext);

					if ($str_pos !== FALSE) {
						return TRUE;	
					}else{
						$this->form_validation->set_message('check_image_required', 'Format file hanya '.$file_type);
						return FALSE;					
					}	
				}
							
			}			
		}
	}
	
	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
}
