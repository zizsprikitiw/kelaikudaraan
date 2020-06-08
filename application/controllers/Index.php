<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {
	
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
		$this->data['page_title'] = "Beranda";
		// $this->data['user_menu'] = $this->cms_model->get_user_menu($this->uri->rsegment(1));
		$items	= $this->cms_model->get_menu_items();
		$menu	= $this->cms_model->generate_menu($items); 
		$this->data['user_menu'] = $menu;
		$this->data['user'] = $this->ion_auth->user()->row();
		$this->data['body_class'] = $this->custom->bodyClass('default');
		
		//tambahan css plugin
		$this->data['add_css'] = array(
		);
		
		//tambahan javascript plugin
		$this->data['add_javascript'] = array(
			base_url($this->config->item('assets')['assets_scripts'])."/pages.js",
			base_url($this->config->item('assets')['assets_scripts'])."/index.js",
		);
		
		$this->load->view('layout/header', array('config' => $this->_config, 'data' => $this->data));
		$this->load->view('index', $this->data);
		$this->load->view('layout/footer', array('config' => $this->_config, 'data' => $this->data));
	}
	
	public function data_init()
	{
		$user_id = $this->session->userdata('user_id');
		
		$table_name = 'v_pesawat_komponen';
		$is_distinct = 'false';
		$select = 'nama_pesawat, singkatan_pesawat, pesawat_id, SUM(total_tsn) as total_tsn,
					(select count(vpki.remaining) from v_pesawat_komponen_interval vpki
					where vpki.pesawat_id = pesawat_id AND vpki.remaining < 0
					) as remaining_komponen,
					(select count(vpt.remaining) from v_pesawat_task vpt
					where vpt.pesawat_id = pesawat_id AND vpt.remaining < 0
					) as remaining_task';
		$where = 'status=1 AND kat_component_id=1';
		$where_in_field = '';
		$where_in_array = array();
		$order_by = 'nama_pesawat ASC';
		$group_by = 'nama_pesawat, singkatan_pesawat, pesawat_id';
		$limit = '';
		
		$list_pesawat = $this->cms_model->get_query_rows($table_name, $is_distinct, $select, $where, $where_in_field, $where_in_array, $order_by, $group_by, $limit);
		
		$div_pesawat = '';
		$status = 'Not Airworthy';
		$color = 'danger';
		$access = '';
		$link = 'javascript:void()';
		
		
		foreach ($list_pesawat as $list_item) {
			if($list_item->remaining_komponen==0 && $list_item->remaining_task==0) {
				$status = 'Airworthy';
				$color = 'success';
			}
			
			if(sizeof($this->cms_model->row_get_by_criteria('v_users_posisi', 'proyek_id='.$list_item->pesawat_id.' AND user_id='.$user_id))==0){
				$access = 'style="cursor: no-drop;" title="No Access"';
			} else {
				$link = site_url('program/index/').$list_item->pesawat_id;
			}
			
			$div_pesawat .= '<div class="kt-widget4__item">
								<span class="kt-userpic kt-userpic--circle kt-userpic--info kt-margin-r-15 kt-margin-t-5">
									<span>'.substr($list_item->singkatan_pesawat,0,1).' '.substr($list_item->singkatan_pesawat,-1).'</span>
								</span>
								
								<div class="kt-widget4__info">
									<a href="'.$link .'" class="kt-widget4__title" '.$access.'>
										'.$list_item->singkatan_pesawat.'
									</a>
									<span class="kt-widget4__sub">
										'.$list_item->nama_pesawat.'
									</span>
								</div>
								<span class="kt-widget4__ext">
									<span class="kt-widget4__number kt-font-info">'.$list_item->total_tsn.' hours</span><br>
									<span class="kt-badge kt-badge--'.$color.' kt-badge--inline kt-badge--pill kt-badge--rounded">'.$status.'</span>
								</span>
							</div>
			';
		}
		
		$data['div_pesawat'] = $div_pesawat;	
		echo json_encode($data);
	}
	
	public function list_pesawat()
	{
		$user_id = $this->session->userdata('user_id');
		$proyek_id = $this->input->post('proyek_id');
		
		$table_name = 'v_pesawat_komponen';
		$is_distinct = 'false';
		$select = 'nama_pesawat, pesawat_id, SUM(total_tsn) as total_tsn';
		$where = 'status=1 AND kat_component_id=1';
		$where_in_field = '';
		$where_in_array = array();
		$order_by = 'nama_pesawat ASC';
		$group_by = 'nama_pesawat, pesawat_id';
		$limit = '';
		
		$list_task_chart = $this->cms_model->get_query_rows($table_name, $is_distinct, $select, $where, $where_in_field, $where_in_array, $order_by, $group_by, $limit);
		$data = array();
		$data_task_chart = array();
		
		foreach ($list_task_chart as $list_item) {			
			$data_task_chart[] = array("modul" => $list_item->nama_modul, "persen" => $list_item->persen, "color" => sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
		}
		  
		$data['data_task_chart'] = $data_task_chart;
		
		echo json_encode($data);
	}
	
	public function data_flight_chart()
	{
		$user_id = $this->session->userdata('user_id');
		
		//get komponen
		$table_name = 'v_pesawat_komponen_tsn';
		$where = 'kat_component_id=1 AND status=1';
		$order_by = 'date_of_tsn ASC';
		$data_chart = array();
		
		$list_chart = $this->cms_model->query_get_by_criteria($table_name, $where, $order_by);
		
		foreach ($list_chart as $list_item) {
			$data_chart[] = array("date" => $list_item->nama_pesawat.': '.strftime("%d %b %Y",strtotime($list_item->date_of_tsn)), "duration" => $list_item->jml_tsn);
		}
			
		$data['data_chart'] = $data_chart;
		
		echo json_encode($data_chart);
	}
	
	public function data_info_bar()
	{
		$user_id = $this->session->userdata('user_id');
		$table_name = 'v_pesawat_notifikasi';
		$is_distinct = 'false';
		$select = 'tableid, info, pesawat_id, nama_pesawat, count(id) AS jml_pending';
		$where = "status=0 AND status_approval is not null AND userid_approval='".$user_id."' ";
		$where_in_field = '';
		$where_in_array = array();
		$order_by = 'pesawat_id ASC';
		$group_by = 'tableid, info, pesawat_id, nama_pesawat';
		$limit = '';
		
		$list_info = $this->cms_model->get_query_rows($table_name, $is_distinct, $select, $where, $where_in_field, $where_in_array, $order_by, $group_by, $limit);
		$info = '';
		$jml_pending = 0;
		
		foreach ($list_info as $list_item) {
			$jml_pending = ($jml_pending+$list_item->jml_pending);
			$icon = '';
			$color = '';
			switch($list_item->tableid){
				case 1:
					$icon = 'flaticon-file';
					$color = 'success';
				break;
				case 2:
					$icon = 'flaticon-cogwheel';
					$color = 'warning';
				break;
				case 3:
					$icon = 'flaticon-interface-4';
					$color = 'danger';
				break;
				default:
			}
			
			$info .= '<a href="'.site_url('program/index/').$list_item->pesawat_id.'" class="kt-notification__item">
						<div class="kt-notification__item-icon">
							<i class="'.$icon.' kt-font-'.$color.'"></i>
						</div>
						<div class="kt-notification__item-details">
							<div class="kt-notification__item-title">
								'.$list_item->info.'
							</div>
							<div class="kt-notification__item-time">
								'.$list_item->nama_pesawat.'
								'.($list_item->jml_pending>0?'<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">'.$list_item->jml_pending.' pending</span>':'').'
							</div>
						</div>
					</a>';
		}
		
		$data['info'] = $info;		
		$data['jml_pending'] = $jml_pending;		
		echo json_encode($data);
	}
}
