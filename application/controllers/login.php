<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
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

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');			
	}
	
	
	// log the user in
	function index()
	{
		if ($this->ion_auth->logged_in())
		{
			redirect('index', 'refresh');	
		}
		
		$this->data['page_title'] = "Login";
		$this->data['body_class'] = $this->custom->bodyClass('login');
		
		//tambahan css plugin
		$this->data['add_css'] = array(
			base_url($this->config->item('assets')['metronic_css'])."/demo1/pages/general/login/login-4.css",
		);
		
		//tambahan javascript plugin
		$this->data['add_javascript'] = array(
			base_url($this->config->item('assets')['assets_scripts'])."/login.js",
		);
		
		$this->data['user_menu'] = array('page_title' => 'Login');		
		
		//validate form input
		$this->form_validation->set_rules('identity', 'Identity', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == true)
		{
			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool) $this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				//if the login is successful
				//redirect them back to the home page
				
				redirect('index', 'refresh');				
			}
			else
			{
				// if the login was un-successful
				// redirect them back to the login page
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries				
			}
		}
		else
		{
			// the user is not logging in so display the login page
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
									
			$this->load->view('login', array('config' => $this->_config, 'data' => $this->data));
		}
	}
	
	// change password
	function change_password()
	{
		$status = 'error';
        $message = $this->lang->line('password_change_unsuccessful');
			
		$this->form_validation->set_rules('old_password', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new_password', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_password_confirm]');
		$this->form_validation->set_rules('new_password_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() == false)
		{			
			//validation fails
			$status = 'warning';
            $message = validation_errors();
		}
		else
		{
			$identity = $this->session->userdata('identity');

			$change = $this->ion_auth->change_password($identity, $this->input->post('old_password'), $this->input->post('new_password'));

			if ($change)
			{
				//if the password was successfully changed
				$status = 'success';
				$message = $this->ion_auth->messages();				
				$this->logout();
			}
			else
			{	$status = 'error';
				$message = $this->ion_auth->errors();								
			}
		}
		echo json_encode(array("status" => $status, "message" => $message));
	}
	
	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
}
