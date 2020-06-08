<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Custom
 */
class Custom
{
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('cms_model');
	}
	
	function bodyClass($parameter)
	{ 
		$attr = "";
		switch ($parameter) {
			case 'default' :
				$attr = "page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white page-sidebar-closed";
			break;
			case 'login' :
				$attr = "login";
			break;
			case 'error_404' :
				$attr = "page-404-full-page";
			break;
			default :
				$attr = "";
		}
		return $attr;
	}
	
	function statusColor($parameter)
	{ 
		switch (strtolower($parameter)) {
			case 'new' : $label = 'default'; break;
			case 'proses' : $label = 'info'; break;
			case 'pending' : $label = 'warning'; break;
			case 'completed' : $label = 'success'; break;
			case 'urgent' : $label = 'danger'; break;
			default : $label = "default";
		}
		return $label;
	}
	
	function bytesToSize($bytes, $precision = 2)
	{  
		$kilobyte = 1024;
		$megabyte = $kilobyte * 1024;
		$gigabyte = $megabyte * 1024;
		$terabyte = $gigabyte * 1024;
	   
		if (($bytes >= 0) && ($bytes < $kilobyte)) {
			return $bytes . ' B';
	 
		} elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
			return round($bytes / $kilobyte, $precision) . ' KB';
	 
		} elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
			return round($bytes / $megabyte, $precision) . ' MB';
	 
		} elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
			return round($bytes / $gigabyte, $precision) . ' GB';
	 
		} elseif ($bytes >= $terabyte) {
			return round($bytes / $terabyte, $precision) . ' TB';
		} else {
			return $bytes . ' B';
		}
	}
	
	function makeDir($upload_path)
	{ 
		$dirs = explode('/', $upload_path);
		foreach ($dirs as $key=>$val) {
			if ($val == '') {
				continue;
			}
			$pathArray = array();
			for ($i = 0; $i <= $key; $i++) {
				array_push($pathArray, $dirs[$i]);
			}
			$path = implode('/', $pathArray);
			
			if(!is_dir($path)){
				mkdir($path,0777);
			}
		}
		return true;
	}
	
	public function sendEmail($to,$subject,$message,$attach){
		$this->CI =& get_instance();
		$this->CI->load->library('email');

		//konfigurasi email
		$config = array();
		$config['charset'] = 'utf-8';
		$config['useragent'] = 'Codeigniter';
		$config['protocol']= "smtp";
		$config['mailtype']= "html";
		$config['smtp_host']= "ssl://smtp.googlemail.com";
		$config['smtp_port']= 465;
		$config['smtp_timeout']= "5";
		$config['smtp_user'] = 'pustekbang1225@gmail.com';
		$config['smtp_pass'] = 'Adm1n12345';        
		$config['crlf']="\r\n";
		$config['newline']="\r\n";
		$config['newline']="\r\n";
		$config['wordwrap'] = TRUE;

		$this->CI->email->initialize($config);
		$this->CI->email->from('pustekbang1225@gmail.com', 'Admin Pustekbang');
		$this->CI->email->to($to);
		$this->CI->email->subject($subject);
		$this->CI->email->message($message);
		$this->CI->email->attach($attach);
		
		// $this->email->send();
		// $status = FALSE;
		// if($this->CI->email->send()){
			// $status = TRUE;
		// } else{
			// echo $this->email->print_debugger(array('headers'));die();
			// $status = FALSE;
		// }
		// return $status;
		if($this->CI->email->send()){
		echo 'Email sent.';
		} else {
		show_error($this->CI->email->print_debugger());
		}
	}
}
