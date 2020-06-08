<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Program extends CI_Controller {
	
	function __construct()
	{
			parent::__construct();
		
			$this->load->database();
			$this->load->library(array('ion_auth','form_validation','custom','encryption','Report_status'));
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
	
	public function index($pesawat_id = null)
	{
		$user_id = $this->session->userdata('user_id');
		if(sizeof($this->cms_model->row_get_by_criteria('v_users_posisi', 'proyek_id='.$pesawat_id.' AND user_id='.$user_id))==0){
			redirect('index', 'refresh');	
		} else {
			$this->data['page_title'] = "Program";
			$this->data['proyek_id'] = $pesawat_id;
			// $this->data['user_menu'] = $this->cms_model->get_user_menu($this->uri->rsegment(1));
			$items	= $this->cms_model->get_menu_items();
			$menu	= $this->cms_model->generate_menu($items); 
			$this->data['user_menu'] = $menu;
			$this->data['user'] = $this->ion_auth->user()->row();
			$this->data['body_class'] = $this->custom->bodyClass('default');
			$this->data['is_admin'] = $this->cms_model->user_is_admin();
			
			$where = 'id='.$pesawat_id;
			$list = $this->cms_model->row_get_by_criteria('proyek', $where);
			$this->data['program'] = $list->nama;
			$this->data['posisi'] = $list->posisi;
			$this->data['keterangan'] = $list->keterangan;
			
			$airframe = array();
			$where = 'pesawat_id='.$pesawat_id.' AND kat_component_id=1 ';
			$list = $this->cms_model->row_get_by_criteria('v_pesawat_komponen', $where);
			if($list->id!=''){
				$airframe['serial_number'] = $list->serial_number;
				$airframe['model'] = $list->model;
				$airframe['aircraft_registration'] = $list->aircraft_registration;
				$airframe['registration_number'] = $list->number_registration;
				$airframe['mfg'] = $list->nama_manufacturer;
				$airframe['date_mfg'] = strftime("%d %b %Y",strtotime($list->date_of_install));
				$airframe['tsn'] = $list->total_tsn;
				$airframe['afl'] = '0';
			} else {
				$airframe['serial_number'] = '-';
				$airframe['model'] = '-';
				$airframe['aircraft_registration'] = '-';
				$airframe['registration_number'] = '-';
				$airframe['mfg'] = '-';
				$airframe['date_mfg'] = '-';
				$airframe['tsn'] = '-';
				$airframe['afl'] = '-';
			}
			
			$this->data['komponen'] = $airframe;
			$this->data['list_file'] = $this->cms_model->query_get_by_criteria('pesawat_gallery', 'proyek_id='.$pesawat_id, 'submit_date desc');
			
			//tambahan css plugin
			$this->data['add_css'] = array(
				//base_url($this->config->item('assets')['metronic_general'])."/owl.carousel/docs/assets/owlcarousel/assets/owl.carousel.min.css",
				//base_url($this->config->item('assets')['metronic_general'])."/owl.carousel/docs/assets/owlcarousel/assets/owl.theme.default.min.css",
				base_url($this->config->item('assets')['metronic_general'])."/bootstrap-select/dist/css/bootstrap-select.css",
			);
			
			//tambahan javascript plugin
			$this->data['add_javascript'] = array(
				base_url($this->config->item('assets')['assets_scripts'])."/pages.js",
				base_url($this->config->item('assets')['assets_scripts'])."/program.js",
				//base_url($this->config->item('assets')['metronic_general'])."/owl.carousel/docs/assets/owlcarousel/owl.carousel.js",
				base_url($this->config->item('assets')['assets_custom'])."/amcharts/amcharts.js",
				base_url($this->config->item('assets')['assets_custom'])."/amcharts/serial.js",
				base_url($this->config->item('assets')['assets_custom'])."/amcharts/radar.js",
				base_url($this->config->item('assets')['assets_custom'])."/amcharts/pie.js",
				base_url($this->config->item('assets')['assets_custom'])."/amcharts/plugins/animate/animate.min.js",
				base_url($this->config->item('assets')['assets_custom'])."/amcharts/export.min.js",
				base_url($this->config->item('assets')['assets_custom'])."/amcharts/themes/light.js",
				base_url($this->config->item('assets')['assets_custom'])."/datatables/dataTables.rowsGroup.js",
				base_url($this->config->item('assets')['metronic_general'])."/select2/dist/js/select2.full.js",
			);
			
			$this->load->view('layout/header', array('config' => $this->_config, 'data' => $this->data));
			$this->load->view('program', $this->data);
			$this->load->view('layout/footer', array('config' => $this->_config, 'data' => $this->data));
		}
	}
	
	public function list_tsn_aircraft_report()
	{				
		$is_admin = $this->cms_model->user_is_admin(); 
		$datatable_name = "v_pesawat_komponen_tsn";//$this->table_name
		$search_column = array('nama_komponen');
		$search_order = array('nama_komponen' => 'desc');
		$user_id = $this->session->userdata('user_id');
		$proyek_id = $this->input->post('proyek_id');
		$where =  'pesawat_id='.$proyek_id.' AND status=0 ';
		$order_by = 'submit_date desc';				
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			
			$approve = '-';
			$status_approve = false;
			$approval = explode('","', trim($list_item->approval, '{""}'));
			$arraydata = array();
			$col_status = '';
			$nama_approval = '';
			
			foreach($approval as $app) {
				$arraydata = explode(',', trim($app, '()'));
				$nama_approval = trim($arraydata[2], '\"\"');
				$color = 'default';
				$status = '-';
				$title = '-';
				if($list_item->status==1) {
					$color = 'success';
					$status = 'Approved';
					$title = 'Approved by Admin';
				} else if($list_item->status==2) {
					$color = 'danger';
					$status = 'Rejected';
					$title = 'Rejected by Admin';
				} else {
					$color = $arraydata[5]==1?'warning':($arraydata[5]==2?'danger':'dark');
					$status = $arraydata[5]==1?'Waiting':($arraydata[5]==2?'Rejected':'Waiting');
					$title = $arraydata[5]==1?'Waiting by Admin':($arraydata[5]==2?'Rejected by '.$nama_approval:'Waiting by '.$nama_approval);
				}
				
				$status_approve = $arraydata[1]==$user_id&&$arraydata[5]==null?true:$status_approve;
				
				$col_status .= '<span class="kt-badge kt-badge--'.$color.' kt-badge--inline" title="'.$title.'">'.$status.'</span>';
			}
			
			if($list_item->status==0) {
				if($status_approve) {
					$approve = '<a href="javascript:;" class="kt-badge kt-badge--inline kt-shape-font-color-1" style="background-color:#A52A2A" onclick="approval_tsn_komponen('."'".$list_item->id."','".$list_item->pengirim."','".str_replace('_',' ',$list_item->filename)."','".$list_item->id."'".')">Approve</a>';
				} else {
					if($is_admin&&$arraydata[5]!=null) {
						$approve = '<a href="javascript:;" class="kt-badge kt-badge--inline kt-shape-font-color-1" style="background-color:#A52A2A" onclick="approval_tsn_komponen('."'".$list_item->id."','".$list_item->pengirim."','".str_replace('_',' ',$list_item->filename)."','".$list_item->id."'".')">Approve</a>';
					}
				}
			}
			
			$row[] = $no;
			$row[] = $list_item->nama_komponen.' (SN: '.$list_item->serial_number.')';							
			$row[] = $list_item->date_of_tsn;							
			$row[] = $list_item->jml_tsn;							
			$row[] = $list_item->filename!=''?'<a class="kt-badge kt-badge--primary kt-badge--inline ml-1" href="'.base_url($this->config->item('uploads')['tsn_aircraft_report']).'/'.$list_item->filename.'" target="_blank">Download</a>':'';
			$row[] = $col_status;				
			$row[] = $approve;					
			$row[] = $list_item->pengirim;					
			$row[] = $nama_approval;					
			
			//add html for action
			$row[] = $is_admin||$list_item->submit_user==$user_id?'<a href="javascript:void()" onclick="data_delete('."'".$list_item->id."','".$list_item->nama_komponen."','pesawat_komponen_tsn'".')" class="kt-badge kt-badge--danger kt-badge--md kt-badge--rounded" title="Hapus"><i class="la la-times"></i></a>':'';
																			
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
	
	public function list_form_tsn_aircraft_report()
	{				
		$is_admin = $this->cms_model->user_is_admin(); 
		$datatable_name = "v_pesawat_komponen_tsn";//$this->table_name
		$search_column = array('nama_komponen');
		$search_order = array('nama_komponen' => 'desc');
		$user_id = $this->session->userdata('user_id');
		$komponen_id = $this->input->post('komponen_id');
		$where =  array('komponen_id' => $komponen_id);
		$order_by = 'submit_date desc';				
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			
			$approve = '-';
			$status_approve = false;
			$approval = explode('","', trim($list_item->approval, '{""}'));
			$arraydata = array();
			$col_status = '';
			$nama_approval = '';
			
			foreach($approval as $app) {
				$arraydata = explode(',', trim($app, '()'));
				$nama_approval = trim($arraydata[2], '\"\"');
				$color = 'default';
				$status = '-';
				$title = '-';
				if($list_item->status==1) {
					$color = 'success';
					$status = 'Approved';
					$title = 'Approved by Admin';
				} else if($list_item->status==2) {
					$color = 'danger';
					$status = 'Rejected';
					$title = 'Rejected by Admin';
				} else {
					$color = $arraydata[5]==1?'warning':($arraydata[5]==2?'danger':'dark');
					$status = $arraydata[5]==1?'Waiting':($arraydata[5]==2?'Rejected':'Waiting');
					$title = $arraydata[5]==1?'Waiting by Admin':($arraydata[5]==2?'Rejected by '.$nama_approval:'Waiting by '.$nama_approval);
				}
				
				$status_approve = $arraydata[1]==$user_id&&$arraydata[5]==null?true:$status_approve;
				
				$col_status .= '<span class="kt-badge kt-badge--'.$color.' kt-badge--inline" title="'.$title.'">'.$status.'</span>';
			}
			
			if($list_item->status==0) {
				if($status_approve) {
					$approve = '<a href="javascript:;" class="kt-badge kt-badge--inline kt-shape-font-color-1" style="background-color:#A52A2A" onclick="approval_tsn_komponen('."'".$list_item->id."','".$list_item->pengirim."','".str_replace('_',' ',$list_item->filename)."','".$list_item->id."'".')">Approve</a>';
				} else {
					if($is_admin&&$arraydata[5]!=null) {
						$approve = '<a href="javascript:;" class="kt-badge kt-badge--inline kt-shape-font-color-1" style="background-color:#A52A2A" onclick="approval_tsn_komponen('."'".$list_item->id."','".$list_item->pengirim."','".str_replace('_',' ',$list_item->filename)."','".$list_item->id."'".')">Approve</a>';
					}
				}
			}
			
			$row[] = $no;
			$row[] = $list_item->nama_komponen.' (SN: '.$list_item->serial_number.')';							
			$row[] = $list_item->date_of_tsn;							
			$row[] = $list_item->jml_tsn;							
			$row[] = $list_item->filename!=''?'<a class="kt-badge kt-badge--primary kt-badge--inline ml-1" href="'.base_url($this->config->item('uploads')['tsn_aircraft_report']).'/'.$list_item->filename.'" target="_blank">Download</a>':'';
			$row[] = $col_status;				
			$row[] = $approve;				
			$row[] = $list_item->pengirim;					
			$row[] = $nama_approval;
			
			//add html for action
			$row[] = $is_admin||$list_item->submit_user==$user_id?'<a href="javascript:void()" onclick="data_delete('."'".$list_item->id."','".$list_item->nama_komponen."','pesawat_komponen_tsn'".')" class="kt-badge kt-badge--danger kt-badge--md kt-badge--rounded" title="Hapus"><i class="la la-times"></i></a>':'';
																			
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
	
	public function list_ins_aircraft_report()
	{				
		$is_admin = $this->cms_model->user_is_admin(); 
		$datatable_name = "v_pesawat_komponen_inspeksi";//$this->table_name
		$search_column = array('nama_komponen');
		$search_order = array('nama_komponen' => 'desc');
		$user_id = $this->session->userdata('user_id');
		$proyek_id = $this->input->post('proyek_id');
		$where =  'pesawat_id='.$proyek_id.' AND status=0 ';
		$order_by = 'submit_date desc';				
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			
			$approve = '-';
			$status_approve = false;
			$approval = explode('","', trim($list_item->approval, '{""}'));
			$arraydata = array();
			$col_status = '';
			$nama_approval = '';
			
			foreach($approval as $app) {
				$arraydata = explode(',', trim($app, '()'));
				$nama_approval = trim($arraydata[2], '\"\"');
				$color = 'default';
				$status = '-';
				$title = '-';
				if($list_item->status==1) {
					$color = 'success';
					$status = 'Approved';
					$title = 'Approved by Admin';
				} else if($list_item->status==2) {
					$color = 'danger';
					$status = 'Rejected';
					$title = 'Rejected by Admin';
				} else {
					$color = $arraydata[5]==1?'warning':($arraydata[5]==2?'danger':'dark');
					$status = $arraydata[5]==1?'Waiting':($arraydata[5]==2?'Rejected':'Waiting');
					$title = $arraydata[5]==1?'Waiting by Admin':($arraydata[5]==2?'Rejected by '.$nama_approval:'Waiting by '.$nama_approval);
				}
				
				$status_approve = $arraydata[1]==$user_id&&$arraydata[5]==null?true:$status_approve;
				
				$col_status .= '<span class="kt-badge kt-badge--'.$color.' kt-badge--inline" title="'.$title.'">'.$status.'</span>';
			}
			
			if($list_item->status==0) {
				if($status_approve) {
					$approve = '<a href="javascript:;" class="kt-badge kt-badge--inline kt-shape-font-color-1" style="background-color:#A52A2A" onclick="approval_ins_komponen('."'".$list_item->id."','".$list_item->pengirim."','".str_replace('_',' ',$list_item->filename)."','".$list_item->id."'".')">Approve</a>';
				} else {
					if($is_admin&&$arraydata[5]!=null) {
						$approve = '<a href="javascript:;" class="kt-badge kt-badge--inline kt-shape-font-color-1" style="background-color:#A52A2A" onclick="approval_ins_komponen('."'".$list_item->id."','".$list_item->pengirim."','".str_replace('_',' ',$list_item->filename)."','".$list_item->id."'".')">Approve</a>';
					}
				}
			}
			
			$row[] = $no;
			$row[] = $list_item->nama_komponen;							
			$row[] = $list_item->filename!=''?'<a class="kt-badge kt-badge--primary kt-badge--inline ml-1" href="'.base_url($this->config->item('uploads')['ins_aircraft_report']).'/'.$list_item->filename.'" target="_blank">Download</a>':'';
			$row[] = $col_status;				
			$row[] = $approve;				
			$row[] = $list_item->nama_interval;							
			$row[] = $list_item->lama_interval.' '.($list_item->kategori_interval!=''?($list_item->kategori_interval==1?'hrs':'year'):'');
			$row[] = strftime("%d %b %Y",strtotime($list_item->tgl_inspeksi));	
			$row[] = $list_item->pengirim;					
			$row[] = $nama_approval;
			
			//add html for action
			$row[] = $is_admin||$list_item->submit_user==$user_id?'<a href="javascript:void()" onclick="data_delete('."'".$list_item->id."','".$list_item->nama_komponen."','pesawat_komponen_inspeksi'".')" class="kt-badge kt-badge--danger kt-badge--md kt-badge--rounded" title="Hapus"><i class="la la-times"></i></a>':'';
																			
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
	
	public function list_ins_task_report()
	{				
		$is_admin = $this->cms_model->user_is_admin(); 
		$datatable_name = "v_pesawat_task_inspeksi";//$this->table_name
		$search_column = array('nama_komponen');
		$search_order = array('nama_komponen' => 'desc');
		$user_id = $this->session->userdata('user_id');
		$proyek_id = $this->input->post('proyek_id');
		$where =  'pesawat_id='.$proyek_id.' AND status=0 ';
		$order_by = 'submit_date desc';				
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			
			$approve = '-';
			$status_approve = false;
			$approval = explode('","', trim($list_item->approval, '{""}'));
			$arraydata = array();
			$col_status = '';
			$nama_approval = '';
			
			foreach($approval as $app) {
				$arraydata = explode(',', trim($app, '()'));
				$nama_approval = trim($arraydata[2], '\"\"');
				$color = 'default';
				$status = '-';
				$title = '-';
				if($list_item->status==1) {
					$color = 'success';
					$status = 'Approved';
					$title = 'Approved by Admin';
				} else if($list_item->status==2) {
					$color = 'danger';
					$status = 'Rejected';
					$title = 'Rejected by Admin';
				} else {
					$color = $arraydata[5]==1?'warning':($arraydata[5]==2?'danger':'dark');
					$status = $arraydata[5]==1?'Waiting':($arraydata[5]==2?'Rejected':'Waiting');
					$title = $arraydata[5]==1?'Waiting by Admin':($arraydata[5]==2?'Rejected by '.$nama_approval:'Waiting by '.$nama_approval);
				}
				
				$status_approve = $arraydata[1]==$user_id&&$arraydata[5]==null?true:$status_approve;
				
				$col_status .= '<span class="kt-badge kt-badge--'.$color.' kt-badge--inline" title="'.$title.'">'.$status.'</span>';
			}
			
			if($list_item->status==0) {
				if($status_approve) {
					$approve = '<a href="javascript:;" class="kt-badge kt-badge--inline kt-shape-font-color-1" style="background-color:#A52A2A" onclick="approval_ins_task('."'".$list_item->id."','".$list_item->pengirim."','".str_replace('_',' ',$list_item->filename)."','".$list_item->id."'".')">Approve</a>';
				} else {
					if($is_admin&&$arraydata[5]!=null) {
						$approve = '<a href="javascript:;" class="kt-badge kt-badge--inline kt-shape-font-color-1" style="background-color:#A52A2A" onclick="approval_ins_task('."'".$list_item->id."','".$list_item->pengirim."','".str_replace('_',' ',$list_item->filename)."','".$list_item->id."'".')">Approve</a>';
					}
				}
			}		
			
			$row[] = $no;
			$row[] = $list_item->nama_komponen;							
			$row[] = $list_item->filename!=''?'<a class="kt-badge kt-badge--primary kt-badge--inline ml-1" href="'.base_url($this->config->item('uploads')['ins_task_report']).'/'.$list_item->filename.'" target="_blank">Download</a>':'';
			$row[] = $col_status;				
			$row[] = $approve;				
			$row[] = strftime("%d %b %Y",strtotime($list_item->tgl_inspeksi));	
			$row[] = $list_item->pengirim;					
			$row[] = $nama_approval;
			
			//add html for action
			$row[] = $is_admin||$list_item->submit_user==$user_id?'<a href="javascript:void()" onclick="data_delete('."'".$list_item->id."','".$list_item->nama_komponen."','pesawat_task_inspeksi'".')" class="kt-badge kt-badge--danger kt-badge--md kt-badge--rounded" title="Hapus"><i class="la la-times"></i></a>':'';
																			
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
	
	public function list_pesawat_komponen()
	{		
		$is_admin = $this->cms_model->user_is_admin(); 
		$datatable_name = "v_pesawat_komponen_interval";//$this->table_name
		$search_column = array('nama_komponen');
		$search_order = array('nama_komponen' => 'desc');
		$proyek_id = $this->input->post('proyek_id');
		$where =  array('pesawat_id' => $proyek_id);
		$order_by = 'kat_component_id asc, nama_interval asc, kategori_interval asc, lama_interval asc';				
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$btn_inspect = "";
		
		$no = $_POST['start'];		
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			//$row[] = $no;
			
			if($list_item->total_inspeksi==0){
				$btn_inspect = $list_item->interval_type_id!=''?('<a class="kt-badge kt-shape-font-color-1 kt-badge--inline" style="background-color:#FF7F50" href="javascript:void()" onclick="loadFormInsKomponenReport('."'".$list_item->komponen_interval_id."'".')">Inspect</a>'):'';
			} else {
				if($list_item->total_inspeksi_disetujui>0){
					$btn_inspect = $list_item->interval_type_id!=''?('<a class="kt-badge kt-badge--primary kt-badge--inline ml-1" href="'.base_url($this->config->item('uploads')['ins_aircraft_report']).'/'.$list_item->filename.'" target="_blank" title="Download">'.$list_item->tgl_inspeksi.'</a>'):'';
				} else {
					$btn_inspect = "Waiting";
				}
			}
			
			$row[] = $list_item->nama_komponen;							
			$row[] = '<b>SN</b>: '.$list_item->serial_number.'<br><b>Mfg</b>: '.$list_item->nama_manufacturer.'<br><b>Model</b>: '.$list_item->model.'<br><b>Date</b>: '.$list_item->date_of_install.'<br><b>PIC</b>: '.$list_item->nama_approval;							
			$row[] = '<a class="kt-badge kt-badge--info kt-badge--inline kt-badge--pill" title="Click for TSN History" href="javascript:void()" onclick="loadFormTblTsnReport('."'".$list_item->id."'".')">'.$list_item->total_tsn.'</a>';								
			$row[] = $list_item->jml_tso;							
			$row[] = $list_item->nama_interval.($list_item->komponen_interval_id!=''&&$is_admin?' <a href="javascript:void()" onclick="data_delete('."'".$list_item->komponen_interval_id."','".$list_item->nama_interval."','pesawat_komponen_interval'".')" class="kt-badge kt-badge--danger kt-badge--inline" title="Hapus"><i class="la la-times"></i></a>':'');							
			$row[] = $list_item->lama_interval.' '.($list_item->kategori_interval!=''?($list_item->kategori_interval==1?'hrs':'year'):'');					
			$row[] = $btn_inspect;	
			$row[] = $list_item->next_due;				
			$row[] = $list_item->total_inspeksi_disetujui==0?($list_item->interval_type_id!=''?('<span class="kt-badge kt-badge--'.($list_item->remaining<=0?'danger':'success').' kt-badge--inline kt-badge--pill">'.$list_item->remaining.($list_item->kategori_interval==1?' hrs':' mnth').'</span>'):''):'-';				
			// $row[] = $list_item->nama_manufacturer;							
			// $row[] = $list_item->model;							
			// $row[] = $list_item->date_of_install;							
			
			//add html for action
			$row[] = $is_admin?'<span class="dropdown">
						<a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
						  <i class="la la-ellipsis-h"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-right">
							<a class="dropdown-item" href="javascript:void()" onclick="loadFormAddIntKomponen('."'".$list_item->id."'".')"><i class="la la-clock-o"></i> Add Interval</a>
							<a class="dropdown-item" href="javascript:void()" onclick="loadFormUpdateKomponen('."'".$list_item->id."'".')"><i class="la la-edit"></i> Edit Details</a>
							<a class="dropdown-item" href="javascript:void()" onclick="data_delete('."'".$list_item->id."','".$list_item->nama_komponen."','pesawat_komponen'".')"><i class="la la-times-circle"></i> Delete Task</a>
						</div>
					</span>':'';
																			
			$data[] = $row;
			//$data[] = $row;
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
	
	public function list_pesawat_task()
	{		
		$is_admin = $this->cms_model->user_is_admin(); 
		$datatable_name = "v_pesawat_task";//$this->table_name
		$search_column = array('nama_komponen');
		$search_order = array('nama_komponen' => 'desc');
		$proyek_id = $this->input->post('proyek_id');
		$where =  array('pesawat_id' => $proyek_id);
		$order_by = 'komponen_kategori_id asc';				
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];			
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->nama_komponen;							
			$row[] = $list_item->nama_pic;							
			$row[] = $list_item->last_inspeksi!=''?$list_item->last_inspeksi:$list_item->date_of_issue;							
			$row[] = $list_item->lama_interval.' '.$list_item->nama_kategori_interval;						
			$row[] = $list_item->next_due;							
			$row[] = '<a class="kt-badge kt-shape-font-color-1 kt-badge--inline" style="background-color:#FF7F50" href="javascript:void()" onclick="loadFormInsTaskReport('."'".$list_item->id."'".')">Inspect</a>';							
			$row[] = '<span class="kt-badge kt-badge--'.($list_item->remaining<=0?'danger':'success').' kt-badge--inline kt-badge--pill">'.$list_item->remaining.'</span>';									
			
			//add html for action
			$row[] = $is_admin?'<span class="dropdown">
						<a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
						  <i class="la la-ellipsis-h"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-right">
							<a class="dropdown-item" href="javascript:void()" onclick="loadFormUpdateTask('."'".$list_item->id."'".')"><i class="la la-edit"></i> Edit Details</a>
							<a class="dropdown-item" href="javascript:void()" onclick="data_delete('."'".$list_item->id."','".$list_item->nama_komponen."','pesawat_task'".')"><i class="la la-times-circle"></i> Delete Task</a>
						</div>
					</span>':'';												
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
	
	public function list_form_ins_task_report()
	{				
		$is_admin = $this->cms_model->user_is_admin(); 
		$datatable_name = "v_pesawat_task_inspeksi";//$this->table_name
		$search_column = array('nama_komponen');
		$search_order = array('nama_komponen' => 'desc');
		$user_id = $this->session->userdata('user_id');
		$task_id = $this->input->post('task_id');
		$where =  array('task_id' => $task_id);
		$order_by = 'submit_date desc';				
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			
			$approve = '-';
			$status_approve = false;
			$approval = explode('","', trim($list_item->approval, '{""}'));
			$arraydata = array();
			$col_status = '';
			$nama_approval = '';
			
			foreach($approval as $app) {
				$arraydata = explode(',', trim($app, '()'));
				$nama_approval = trim($arraydata[2], '\"\"');
				$color = 'default';
				$status = '-';
				$title = '-';
				if($list_item->status==1) {
					$color = 'success';
					$status = 'Approved';
					$title = 'Approved by Admin';
				} else if($list_item->status==2) {
					$color = 'danger';
					$status = 'Rejected';
					$title = 'Rejected by Admin';
				} else {
					$color = $arraydata[5]==1?'warning':($arraydata[5]==2?'danger':'dark');
					$status = $arraydata[5]==1?'Waiting':($arraydata[5]==2?'Rejected':'Waiting');
					$title = $arraydata[5]==1?'Waiting by Admin':($arraydata[5]==2?'Rejected by '.$nama_approval:'Waiting by '.$nama_approval);
				}
				
				$status_approve = $arraydata[1]==$user_id&&$arraydata[5]==null?true:$status_approve;
				
				$col_status .= '<span class="kt-badge kt-badge--'.$color.' kt-badge--inline" title="'.$title.'">'.$status.'</span>';
			}
			
			if($list_item->status==0) {
				if($status_approve) {
					$approve = '<a href="javascript:;" class="kt-badge kt-badge--inline kt-shape-font-color-1" style="background-color:#A52A2A" onclick="approval_ins_task('."'".$list_item->id."','".$list_item->pengirim."','".str_replace('_',' ',$list_item->filename)."','".$list_item->id."'".')">Approve</a>';
				} else {
					if($is_admin&&$arraydata[5]!=null) {
						$approve = '<a href="javascript:;" class="kt-badge kt-badge--inline kt-shape-font-color-1" style="background-color:#A52A2A" onclick="approval_ins_task('."'".$list_item->id."','".$list_item->pengirim."','".str_replace('_',' ',$list_item->filename)."','".$list_item->id."'".')">Approve</a>';
					}
				}
			}		
			
			$row[] = $no;
			$row[] = $list_item->nama_komponen;							
			$row[] = strftime("%d %b %Y",strtotime($list_item->tgl_inspeksi));	
			$row[] = $list_item->filename!=''?'<a class="kt-badge kt-badge--primary kt-badge--inline ml-1" href="'.base_url($this->config->item('uploads')['ins_task_report']).'/'.$list_item->filename.'" target="_blank">Download</a>':'';
			$row[] = $col_status;				
			$row[] = $approve;	
			$row[] = $list_item->pengirim;					
			$row[] = $nama_approval;
			
			//add html for action
			$row[] = $is_admin||$list_item->submit_user==$user_id?'<a href="javascript:void()" onclick="data_delete('."'".$list_item->id."','".$list_item->nama_komponen."','pesawat_task_inspeksi'".')" class="kt-badge kt-badge--danger kt-badge--md kt-badge--rounded" title="Hapus"><i class="la la-times"></i></a>':'';
																			
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
	
	public function list_scheduling()
	{		
		$is_admin = $this->cms_model->user_is_admin(); 
		$datatable_name = "v_pesawat_schedule";//$this->table_name
		$search_column = array();
		$search_order = array();
		$user_id = $this->session->userdata('user_id');
		$proyek_id = $this->input->post('proyek_id');
		$where =  array('pesawat_id' => $proyek_id);
		$order_by = 'submit_date desc';				
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = '';		
		foreach ($list as $list_item) {
			$data .= '<div class="kt-widget3__item">
					<div class="kt-widget3__header">
						<div class="kt-widget3__user-img">
							<img class="kt-widget3__img" src="'.site_url($this->config->item('uploads')['users_thumb50x50'].'/'.$list_item->photo).'" alt="">
						</div>
						<div class="kt-widget3__info">
							<a href="#" class="kt-widget3__username">
								'.$list_item->pengirim.'
							</a><br>
							<span class="kt-widget3__time">
								'.strftime("%a, %d %b %Y : %R",strtotime($list_item->submit_date)).'
							</span>
							'.($list_item->filename!=''?'<a class="kt-badge kt-badge--primary kt-badge--inline ml-1" href="'.base_url($this->config->item('uploads')['scheduling_report']).'/'.$list_item->filename.'" target="_blank">Download</a>':'').'
							'.($is_admin||$list_item->submit_user==$user_id?'<a href="javascript:void()" onclick="data_delete('."'".$list_item->id."','".$list_item->nama_komponen."','pesawat_schedule'".')" class="kt-badge kt-badge--danger kt-badge--inline" title="Hapus"><i class="la la-times"></i></a>':'').'
						</div>
						<span class="kt-widget3__status kt-font-brand">
							'.$list_item->nama_komponen.'
						</span>
						
					</div>
					<div class="kt-widget3__body">
						<p class="kt-widget3__text">
							'.$list_item->deskripsi.'
						</p>
					</div>
				</div>';
		}

		$output = array("data" => $data);
		//output to json format
		echo json_encode($output);		
	}
	
	public function data_flight_chart()
	{
		$user_id = $this->session->userdata('user_id');
		$proyek_id = $this->input->post('proyek_id');
		
		//get komponen
		$table_name = 'v_pesawat_komponen_tsn';
		$where = 'pesawat_id='.$proyek_id.' AND kat_component_id=1 AND status=1';
		$order_by = 'date_of_tsn ASC';
		$data_chart = array();
		
		$list_chart = $this->cms_model->query_get_by_criteria($table_name, $where, $order_by);
		
		foreach ($list_chart as $list_item) {
			$data_chart[] = array("date" => $list_item->date_of_tsn, "duration" => $list_item->jml_tsn);
		}
			
		$data['data_chart'] = $data_chart;
		
		echo json_encode($data);
	}
	
	public function data_form_add_tsn_aircraft_report()
	{
		$user_id = $this->session->userdata('user_id');
		$proyek_id = $this->input->post('proyek_id');
		
		//get komponen
		$table_name = 'v_pesawat_komponen';
		$where = 'pesawat_id='.$proyek_id.' AND status=1';
		$order_by = 'id ASC';
		$filter_komponen = array();
		
		$list_komponen = $this->cms_model->query_get_by_criteria($table_name, $where, $order_by);
		
		foreach ($list_komponen as $list_item) {
			$filter_komponen[] = array("id_item" => $list_item->id, "nama_item" => $list_item->nama_komponen.' (SN: '.$list_item->serial_number.')');
		}
			
		$data['filter_komponen'] = $filter_komponen;
		echo json_encode($data);
	}
	
	public function data_form_add_komponen()
	{
		$user_id = $this->session->userdata('user_id');
		$proyek_id = $this->input->post('proyek_id');
		$table_name = 'v_users_posisi';
		$where = 'proyek_id='.$proyek_id.' AND posisi_id=3';
		$order_by = 'id ASC';
		$filter_posisi = array();
		
		$list_user = $this->cms_model->query_get_by_criteria($table_name, $where, $order_by);
		
		foreach ($list_user as $list_item) {
			$filter_posisi[] = array("id_item" => $list_item->id, "nama_item" => $list_item->nama_user.' ('.$list_item->leader_nama.')');
		}
		
		//get kategori description
		$table_name = 'pesawat_komponen_kategori';
		$where = 'kategori_id=1';
		$order_by = 'id ASC';
		$filter_description = array();
		
		$list_description = $this->cms_model->query_get_by_criteria($table_name, $where, $order_by);
		
		foreach ($list_description as $list_item) {
			$filter_description[] = array("id_item" => $list_item->id, "nama_item" => $list_item->nama);
		}
		
		//get manufacturer
		$table_name = 'pesawat_manufacturer';
		$where = '';
		$order_by = 'id ASC';
		$filter_manufacturer = array();
		
		$list_manufacturer = $this->cms_model->query_get_by_criteria($table_name, $where, $order_by);
		
		foreach ($list_manufacturer as $list_item) {
			$filter_manufacturer[] = array("id_item" => $list_item->id, "nama_item" => $list_item->nama);
		}
			
		$data['filter_posisi'] = $filter_posisi;
		$data['filter_description'] = $filter_description;
		$data['filter_manufacturer'] = $filter_manufacturer;
		echo json_encode($data);
	}
	
	public function data_form_update_komponen($id)
	{				
		$user_id = $this->session->userdata('user_id');
		$proyek_id = $this->input->get('proyek_id');
		$table_name = 'v_users_posisi';
		$where = 'proyek_id='.$proyek_id.' AND posisi_id=3';
		$order_by = 'id ASC';
		$filter_posisi = array();
		
		$list_user = $this->cms_model->query_get_by_criteria($table_name, $where, $order_by);
		
		foreach ($list_user as $list_item) {
			$filter_posisi[] = array("id_item" => $list_item->id, "nama_item" => $list_item->nama_user.' ('.$list_item->leader_nama.')');
		}
		
		//get kategori description
		$table_name = 'pesawat_komponen_kategori';
		$where = 'kategori_id=1';
		$order_by = 'id ASC';
		$filter_description = array();
		
		$list_description = $this->cms_model->query_get_by_criteria($table_name, $where, $order_by);
		
		foreach ($list_description as $list_item) {
			$filter_description[] = array("id_item" => $list_item->id, "nama_item" => $list_item->nama);
		}
		
		//get manufacturer
		$table_name = 'pesawat_manufacturer';
		$where = '';
		$order_by = 'id ASC';
		$filter_manufacturer = array();
		
		$list_manufacturer = $this->cms_model->query_get_by_criteria($table_name, $where, $order_by);
		
		foreach ($list_manufacturer as $list_item) {
			$filter_manufacturer[] = array("id_item" => $list_item->id, "nama_item" => $list_item->nama);
		}
			
		$data['filter_posisi'] = $filter_posisi;
		$data['filter_description'] = $filter_description;
		$data['filter_manufacturer'] = $filter_manufacturer;
		$data['list'] = $this->cms_model->row_get_by_id($id, 'pesawat_komponen');
		echo json_encode($data);
	}
	
	public function data_form_add_int_komponen()
	{
		$user_id = $this->session->userdata('user_id');
		$proyek_id = $this->input->post('proyek_id');
		
		//get interval type
		$table_name = 'pesawat_interval_type';
		$where = '';
		$order_by = 'id ASC';
		$filter_interval = array();
		
		$list_interval = $this->cms_model->query_get_by_criteria($table_name, $where, $order_by);
		
		foreach ($list_interval as $list_item) {
			$filter_interval[] = array("id_item" => $list_item->id, "nama_item" => $list_item->nama);
		}
			
		$data['filter_interval'] = $filter_interval;
		echo json_encode($data);
	}
	
	public function data_form_add_task()
	{
		$user_id = $this->session->userdata('user_id');
		$proyek_id = $this->input->post('proyek_id');
		$table_name = 'v_users_posisi';
		$where = 'proyek_id='.$proyek_id.' AND posisi_id=3';
		$order_by = 'id ASC';
		$filter_posisi = array();
		
		$list_user = $this->cms_model->query_get_by_criteria($table_name, $where, $order_by);
		
		foreach ($list_user as $list_item) {
			$filter_posisi[] = array("id_item" => $list_item->id, "nama_item" => $list_item->nama_user.' ('.$list_item->leader_nama.')');
		}
		
		//get kategori description
		$table_name = 'pesawat_komponen_kategori';
		$where = 'kategori_id=2';
		$order_by = 'id ASC';
		$filter_description = array();
		
		$list_description = $this->cms_model->query_get_by_criteria($table_name, $where, $order_by);
		
		foreach ($list_description as $list_item) {
			$filter_description[] = array("id_item" => $list_item->id, "nama_item" => $list_item->nama);
		}
			
		$data['filter_posisi_task'] = $filter_posisi;
		$data['filter_description_task'] = $filter_description;
		echo json_encode($data);
	}
	
	public function data_form_update_task($id)
	{				
		$user_id = $this->session->userdata('user_id');
		$proyek_id = $this->input->get('proyek_id');
		$table_name = 'v_users_posisi';
		$where = 'proyek_id='.$proyek_id.' AND posisi_id=3';
		$order_by = 'id ASC';
		$filter_posisi = array();
		
		$list_user = $this->cms_model->query_get_by_criteria($table_name, $where, $order_by);
		
		foreach ($list_user as $list_item) {
			$filter_posisi[] = array("id_item" => $list_item->id, "nama_item" => $list_item->nama_user.' ('.$list_item->leader_nama.')');
		}
		
		//get kategori description
		$table_name = 'pesawat_komponen_kategori';
		$where = 'kategori_id=2';
		$order_by = 'id ASC';
		$filter_description = array();
		
		$list_description = $this->cms_model->query_get_by_criteria($table_name, $where, $order_by);
		
		foreach ($list_description as $list_item) {
			$filter_description[] = array("id_item" => $list_item->id, "nama_item" => $list_item->nama);
		}
			
		$data['filter_posisi_task'] = $filter_posisi;
		$data['filter_description_task'] = $filter_description;
		$data['list'] = $this->cms_model->row_get_by_id($id, 'pesawat_task');
		echo json_encode($data);
	}
	
	public function data_form_ins_task_report()
	{
		$user_id = $this->session->userdata('user_id');
		$task_id = $this->input->post('task_id');
		$table_name = 'v_pesawat_task';
		$where = 'id='.$task_id.'';
		
		$list_ins_task = $this->cms_model->row_get_by_criteria($table_name, $where);
		
		$data['nama_komponen'] = $list_ins_task->nama_komponen;		
		echo json_encode($data);
	}
	
	public function data_select_item()
	{
		$proyek_id = $this->input->post('proyek_id');
		$kategori_id = $this->input->post('kategori_id');
		$table_name = 'v_select_komponen_and_task';
		$where = 'pesawat_id='.$proyek_id.' AND ketgori_komponen='.$kategori_id.' AND status=1';
		$order_by = 'id ASC';
		$filter_item = array();
		
		$list_komponen = $this->cms_model->query_get_by_criteria($table_name, $where, $order_by);
		
		foreach ($list_komponen as $list_item) {
			$filter_item[] = array("id_item" => $list_item->id, "nama_item" => $list_item->nama_komponen);
		}
					
		$data['filter_item'] = $filter_item;
		echo json_encode($data);
	}
	
	function show_tsn_komponen()
	{
		$file_id = $this->input->post('file_id');	
		$note_row = $this->cms_model->row_get_by_id($file_id, 'pesawat_komponen_tsn');		
		//var_dump($note_row);die();
		$note_file = $note_row->filename;						
		$folder_note = base_url($this->config->item('uploads')['tsn_aircraft_report']);
		$data['note_title'] = "File Laporan: ".$note_row->filename."<br />Tanggal Laporan: ".date_format(date_create($note_row->submit_date),"d M Y");
				
		$file_template = FCPATH."assets\custom\scripts\angular_pdf\pdf_loader.php";	
		$file_content = file_get_contents($file_template);		
		$vars = array("{pdf_url}" => $folder_note.'/'.$note_file);
		$file_content = strtr($file_content, $vars);
		$new_filename = mdate("%Y%m%d-%H%i%s", $_SERVER['REQUEST_TIME']).'.php';		
		$file_template = FCPATH."assets\custom\scripts\angular_pdf\pdf_loader_".$new_filename;
		$result = write_file($file_template, $file_content);	
							
		$data['filename_url'] = base_url()."assets/custom/scripts/angular_pdf/pdf_loader_".$new_filename;
		$data['filename_path'] = $file_template;		
		echo json_encode($data);	
	}
	
	public function approval_tsn_komponen()
	{			
        $is_admin = $this->cms_model->user_is_admin(); 
		$status = 'error';
        $message = 'Approve gagal';		
		$user_id = $this->session->userdata('user_id');	
		$komponen_tsn_id = $this->input->post('komponen_tsn_id');	
		
		$additional_data = array(
			'submit_user' => $user_id,
			'komponen_tsn_id' => $komponen_tsn_id,
			'status' => 1,
		);

		try {				
			if($is_admin) {
				$additional_data = array(
					'update_user' => $user_id,
					'update_date' => gmdate("Y-m-d H:i:s", time()+60*60*7),
					'status' => 1,
				);
				$this->cms_model->update(array('id' => $komponen_tsn_id),$additional_data,'pesawat_komponen_tsn');		
			} else {
				$id = $this->cms_model->save($additional_data, 'pesawat_komponen_tsn_approval');	
			}
			
			$status = 'success';
			$message = 'Approve berhasil';
		} catch (Exception $e) {
			$status = 'error';
			$message = $e->getMessage();
		}
		
		echo json_encode(array("status" => $status, "message" => $message));			
	}
	
	function show_ins_komponen()
	{
		$file_id = $this->input->post('file_id');	
		$note_row = $this->cms_model->row_get_by_id($file_id, 'pesawat_komponen_inspeksi');		
		//var_dump($note_row);die();
		$note_file = $note_row->filename;						
		$folder_note = base_url($this->config->item('uploads')['ins_aircraft_report']);
		$data['note_title'] = "File Laporan: ".$note_row->filename."<br />Tanggal Laporan: ".date_format(date_create($note_row->submit_date),"d M Y");
				
		$file_template = FCPATH."assets\custom\scripts\angular_pdf\pdf_loader.php";	
		$file_content = file_get_contents($file_template);		
		$vars = array("{pdf_url}" => $folder_note.'/'.$note_file);
		$file_content = strtr($file_content, $vars);
		$new_filename = mdate("%Y%m%d-%H%i%s", $_SERVER['REQUEST_TIME']).'.php';		
		$file_template = FCPATH."assets\custom\scripts\angular_pdf\pdf_loader_".$new_filename;
		$result = write_file($file_template, $file_content);	
							
		$data['filename_url'] = base_url()."assets/custom/scripts/angular_pdf/pdf_loader_".$new_filename;
		$data['filename_path'] = $file_template;		
		echo json_encode($data);	
	}
	
	public function approval_ins_komponen()
	{			
        $is_admin = $this->cms_model->user_is_admin(); 
		$status = 'error';
        $message = 'Approve gagal';		
		$user_id = $this->session->userdata('user_id');	
		$komponen_inspeksi_id = $this->input->post('komponen_inspeksi_id');	
		
		$additional_data = array(
			'submit_user' => $user_id,
			'komponen_inspeksi_id' => $komponen_inspeksi_id,
			'status' => 1,
		);

		try {				
			if($is_admin) {
				$additional_data = array(
					'update_user' => $user_id,
					'update_date' => gmdate("Y-m-d H:i:s", time()+60*60*7),
					'status' => 1,
				);
				$this->cms_model->update(array('id' => $komponen_inspeksi_id),$additional_data,'pesawat_komponen_inspeksi');		
			} else {
				$id = $this->cms_model->save($additional_data, 'pesawat_komponen_inspeksi_approval');	
			}
			
			$status = 'success';
			$message = 'Approve berhasil';
		} catch (Exception $e) {
			$status = 'error';
			$message = $e->getMessage();
		}
		
		echo json_encode(array("status" => $status, "message" => $message));			
	}
	
	function show_ins_task()
	{
		$file_id = $this->input->post('file_id');	
		$note_row = $this->cms_model->row_get_by_id($file_id, 'pesawat_task_inspeksi');		
		//var_dump($note_row);die();
		$note_file = $note_row->filename;						
		$folder_note = base_url($this->config->item('uploads')['ins_task_report']);
		$data['note_title'] = "File Laporan: ".$note_row->filename."<br />Tanggal Laporan: ".date_format(date_create($note_row->submit_date),"d M Y");
				
		$file_template = FCPATH."assets\custom\scripts\angular_pdf\pdf_loader.php";	
		$file_content = file_get_contents($file_template);		
		$vars = array("{pdf_url}" => $folder_note.'/'.$note_file);
		$file_content = strtr($file_content, $vars);
		$new_filename = mdate("%Y%m%d-%H%i%s", $_SERVER['REQUEST_TIME']).'.php';		
		$file_template = FCPATH."assets\custom\scripts\angular_pdf\pdf_loader_".$new_filename;
		$result = write_file($file_template, $file_content);	
							
		$data['filename_url'] = base_url()."assets/custom/scripts/angular_pdf/pdf_loader_".$new_filename;
		$data['filename_path'] = $file_template;		
		echo json_encode($data);	
	}
	
	public function approval_ins_task()
	{			
        $status = 'error';
        $message = 'Approve gagal';		
		$user_id = $this->session->userdata('user_id');	
		$task_inspeksi_id = $this->input->post('task_inspeksi_id');	
		
		$additional_data = array(
			'submit_user' => $user_id,
			'task_inspeksi_id' => $task_inspeksi_id,
			'status' => 1,
		);

		try {		
			if($is_admin) {
				$additional_data = array(
					'update_user' => $user_id,
					'update_date' => gmdate("Y-m-d H:i:s", time()+60*60*7),
					'status' => 1,
				);
				$this->cms_model->update(array('id' => $task_inspeksi_id),$additional_data,'pesawat_task_inspeksi');		
			} else {
				$id = $this->cms_model->save($additional_data, 'pesawat_task_inspeksi_approval');		
			}
			
			$status = 'success';
			$message = 'Approve berhasil';
		} catch (Exception $e) {
			$status = 'error';
			$message = $e->getMessage();
		}
		
		echo json_encode(array("status" => $status, "message" => $message));			
	}
	
	function data_save_tsn_aircraft_report()
	{
		$status = 'error';
        $message = 'TSN Aircraft Report gagal diupload';		
		$save_method = $this->input->post('save_method');
		$user_id = $this->session->userdata('user_id');		
		$proyek_id = $this->input->post('proyek_id');	
		$report_id = $this->input->post('id');		
		$komponen_id = $this->input->post('komponen_id');														
		$date_of_tsn = new DateTime($this->input->post('date_of_tsn'));
		$tsn = $this->input->post('tsn');			
		
		if(!empty($_FILES['filename']['name']) && $_FILES['filename']['name']!='' && $_FILES['filename']['name']!='undefined')
		{
			//path directory
			$upload_path = $this->config->item('uploads')['tsn_aircraft_report'];
			//membuat directory jika belum ada
			$this->custom->makeDir($upload_path);
			
			$file_name = $_FILES["filename"]["name"];
			$file_name = preg_replace("/ /", '_', $file_name);
			$file_name = preg_replace("/&/", '_', $file_name);
			$file_name = preg_replace("/{/", '_', $file_name);
			$file_name = preg_replace("/}/", '_', $file_name);
			$upload_file = $upload_path.'/'.$file_name;
			
			if(is_file($upload_file)){
				$ext = pathinfo($_FILES['filename']['name'], PATHINFO_EXTENSION);
				$new_filename = str_replace('.'.$ext, '', $file_name);				
				$file_name = $new_filename.'_'.mdate("%Y%m%d-%H%i%s", $_SERVER['REQUEST_TIME']).'.'.$ext;		
			}
			
			$config['upload_path'] = "$upload_path";
			$config['allowed_types'] = "*";
			$config['remove_spaces'] = TRUE;
			$config['file_name'] = $file_name;
			$config['overwrite'] = TRUE; // true berfungsi untuk replace
			//$config['max_width'] = '1024';
			//$config['max_height'] = '768';
			$this->load->library('upload', $config);
			
			if (!$this->upload->do_upload('filename'))
			{
				$status = 'error';
				$message = $this->upload->display_errors('', '');
			} else {
				$file_path = $this->upload->data();
				$doc_path = $file_path['file_name'];
				
				if(is_file("$upload_path/".$doc_path))
				{
					$add_data = array(
						'submit_user' => $user_id,
						'komponen_id' => $komponen_id,
						'date_of_tsn' => $date_of_tsn->format('Y-m-d'),
						'jml_tsn' => $tsn,
						'filename' => $file_path['file_name'],
					);
					
					try {				
						$agenda_file_id = $this->cms_model->save($add_data, 'pesawat_komponen_tsn');	
						$status = 'success';
						$message = 'TSN Aircraft Report berhasil diupload';
					} catch (Exception $e) {
						$status = 'error';
						$message = $e->getMessage();
						if(is_file("$upload_path/".$doc_path)){
							unlink("$upload_path/".$doc_path);
						}	
					}
				}
			}		
		}else{
			$status = 'warning';
			$message = 'Isian harus salah satu diisi';				
		}
		
		echo json_encode(array("status" => $status, "message" => $message));
	}	
	
	function data_save_komponen()
	{
		$status = 'error';
        $message = 'Komponen gagal disimpan';		
		$save_method = $this->input->post('save_method');
		$user_id = $this->session->userdata('user_id');		
		$proyek_id = $this->input->post('proyek_id');	
		$komponen_id = $this->input->post('id');		
		$description_id = $this->input->post('description_id');														
		$manufacturer_id = $this->input->post('manufacturer_id');														
		$aircraft_registration = $this->input->post('aircraft_registration');
		$registration_number = $this->input->post('registration_number');			
		$model = $this->input->post('model');			
		$serial_number = $this->input->post('serial_number');			
		$date_of_install = new DateTime($this->input->post('date_of_install'));
		$tsn = $this->input->post('tsn');			
		$tso = $this->input->post('tso');			
		$posisi_pic_id = $this->input->post('posisi_pic_id');
		
		$additional_data = array(
			'pesawat_id' => $proyek_id,
			'kat_component_id' => $description_id,
			'manufacturer_id' => $manufacturer_id,
			'aircraft_registration' => $aircraft_registration,
			'number_registration' => $registration_number,
			'model' => $model,
			'serial_number' => $serial_number,
			'date_of_install' => $date_of_install->format('Y-m-d'),
			'jml_tsn' => $tsn,
			'jml_tso' => $tso,
			'posisi_pic_id' => $posisi_pic_id,
		);
		
		if($save_method == "update" ){
			$additional_data['update_user'] = $user_id;
			$additional_data['update_date'] = gmdate("Y-m-d H:i:s", time()+60*60*7);
		}else{
			$additional_data['submit_user'] = $user_id;
		}
		
		if($save_method == "update" ){
			try {
				$this->cms_model->update(array('id' => $komponen_id),$additional_data,'pesawat_komponen');		
				$status = 'success';
				$message = "Komponen berhasil diubah";
			} catch (Exception $e) {
				$status = 'error';
				$message = "Komponen gagal diubah";
			}
		} else {
			try {
				$tasks_id = $this->cms_model->save($additional_data, 'pesawat_komponen');	
				$status = 'success';
				$message = "Komponen berhasil disimpan";
			} catch (Exception $e) {
				$status = 'error';
				$message = "Komponen gagal disimpan";
			}
		}
		
		echo json_encode(array("status" => $status, "message" => $message));
	}

	function data_save_int_komponen()
	{
		$status = 'error';
        $message = 'Interval gagal disimpan';		
		$save_method = $this->input->post('save_method');
		$user_id = $this->session->userdata('user_id');		
		$id = $this->input->post('id');		
		$komponen_id = $this->input->post('komponen_id');	
		$interval_id = $this->input->post('interval_id');						
		$lama_interval = $this->input->post('lama_interval');
		$kategori_interval = $this->input->post('kategori_interval');
		
		$additional_data = array(
			'komponen_id' => $komponen_id,
			'interval_type_id' => $interval_id,
			'lama_interval' => $lama_interval,
			'kategori_interval' => $kategori_interval,
		);
		
		if($save_method == "update" ){
			$additional_data['update_user'] = $user_id;
			$additional_data['update_date'] = gmdate("Y-m-d H:i:s", time()+60*60*7);
		}else{
			$additional_data['submit_user'] = $user_id;
		}
		
		if($save_method == "update" ){
			try {
				$this->cms_model->update(array('id' => $item_id),$additional_data,'pesawat_komponen_interval');		
				$status = 'success';
				$message = "Interval berhasil diubah";
			} catch (Exception $e) {
				$status = 'error';
				$message = "Interval gagal diubah";
			}
		} else {
			try {
				$tasks_id = $this->cms_model->save($additional_data, 'pesawat_komponen_interval');	
				$status = 'success';
				$message = "Interval berhasil disimpan";
			} catch (Exception $e) {
				$status = 'error';
				$message = "Interval gagal disimpan";
			}
		}
		
		echo json_encode(array("status" => $status, "message" => $message));
	}
	
	function data_save_ins_komponen_report()
	{
		$status = 'error';
        $message = 'Inspection Aircraft Report gagal diupload';		
		$save_method = $this->input->post('save_method');
		$user_id = $this->session->userdata('user_id');		
		$report_id = $this->input->post('id');		
		$komponen_id = $this->input->post('komponen_id');														
		$date_of_ins = new DateTime($this->input->post('date_of_ins'));			
		
		if(!empty($_FILES['filename']['name']) && $_FILES['filename']['name']!='' && $_FILES['filename']['name']!='undefined')
		{
			//path directory
			$upload_path = $this->config->item('uploads')['ins_aircraft_report'];
			//membuat directory jika belum ada
			$this->custom->makeDir($upload_path);
			
			$file_name = $_FILES["filename"]["name"];
			$file_name = preg_replace("/ /", '_', $file_name);
			$file_name = preg_replace("/&/", '_', $file_name);
			$file_name = preg_replace("/{/", '_', $file_name);
			$file_name = preg_replace("/}/", '_', $file_name);
			$upload_file = $upload_path.'/'.$file_name;
			
			if(is_file($upload_file)){
				$ext = pathinfo($_FILES['filename']['name'], PATHINFO_EXTENSION);
				$new_filename = str_replace('.'.$ext, '', $file_name);				
				$file_name = $new_filename.'_'.mdate("%Y%m%d-%H%i%s", $_SERVER['REQUEST_TIME']).'.'.$ext;		
			}
			
			$config['upload_path'] = "$upload_path";
			$config['allowed_types'] = "*";
			$config['remove_spaces'] = TRUE;
			$config['file_name'] = $file_name;
			$config['overwrite'] = TRUE; // true berfungsi untuk replace
			//$config['max_width'] = '1024';
			//$config['max_height'] = '768';
			$this->load->library('upload', $config);
			
			if (!$this->upload->do_upload('filename'))
			{
				$status = 'error';
				$message = $this->upload->display_errors('', '');
			} else {
				$file_path = $this->upload->data();
				$doc_path = $file_path['file_name'];
				
				if(is_file("$upload_path/".$doc_path))
				{
					$add_data = array(
						'submit_user' => $user_id,
						'komponon_interval_id' => $komponen_id,
						'tgl_inspeksi' => $date_of_ins->format('Y-m-d'),
						'filename' => $file_path['file_name'],
					);
					
					try {				
						$agenda_file_id = $this->cms_model->save($add_data, 'pesawat_komponen_inspeksi');	
						$status = 'success';
						$message = 'Inspection Aircraft Report berhasil diupload';
					} catch (Exception $e) {
						$status = 'error';
						$message = $e->getMessage();
						if(is_file("$upload_path/".$doc_path)){
							unlink("$upload_path/".$doc_path);
						}	
					}
				}
			}		
		}else{
			$status = 'warning';
			$message = 'Isian harus salah satu diisi';				
		}
		
		echo json_encode(array("status" => $status, "message" => $message));
	}	
	
	function data_save_task()
	{
		$status = 'error';
        $message = 'Task gagal disimpan';		
		$save_method = $this->input->post('save_method');
		$user_id = $this->session->userdata('user_id');		
		$proyek_id = $this->input->post('proyek_id');	
		$task_id = $this->input->post('id');		
		$description_id = $this->input->post('description_id');														
		$date_of_issue = new DateTime($this->input->post('date_of_issue'));
		$kategori_interval = $this->input->post('kategori_interval');			
		$interval = $this->input->post('interval');			
		$posisi_pic_id = $this->input->post('posisi_pic_id');
		
		$additional_data = array(
			'pesawat_id' => $proyek_id,
			'komponen_kategori_id' => $description_id,
			'date_of_issue' => $date_of_issue->format('Y-m-d'),
			'kategori_interval' => $kategori_interval,
			'lama_interval' => $interval,
			'posisi_pic_id' => $posisi_pic_id,
		);
		
		if($save_method == "update" ){
			$additional_data['update_user'] = $user_id;
			$additional_data['update_date'] = gmdate("Y-m-d H:i:s", time()+60*60*7);
		}else{
			$additional_data['submit_user'] = $user_id;
		}
		
		if($save_method == "update" ){
			try {
				$this->cms_model->update(array('id' => $task_id),$additional_data,'pesawat_task');		
				$status = 'success';
				$message = "Task berhasil diubah";
			} catch (Exception $e) {
				$status = 'error';
				$message = "Task gagal diubah";
			}
		} else {
			try {
				$tasks_id = $this->cms_model->save($additional_data, 'pesawat_task');	
				$status = 'success';
				$message = "Task berhasil disimpan";
			} catch (Exception $e) {
				$status = 'error';
				$message = "Task gagal disimpan";
			}
		}
		
		echo json_encode(array("status" => $status, "message" => $message));
	}	
	
	function data_save_ins_task_report()
	{
		$status = 'error';
        $message = 'Inspection Task Report gagal diupload';		
		$save_method = $this->input->post('save_method');
		$user_id = $this->session->userdata('user_id');		
		$report_id = $this->input->post('id');		
		$task_id = $this->input->post('task_id');														
		$date_of_ins = new DateTime($this->input->post('date_of_ins'));			
		
		if(!empty($_FILES['filename']['name']) && $_FILES['filename']['name']!='' && $_FILES['filename']['name']!='undefined')
		{
			//path directory
			$upload_path = $this->config->item('uploads')['ins_task_report'];
			//membuat directory jika belum ada
			$this->custom->makeDir($upload_path);
			
			$file_name = $_FILES["filename"]["name"];
			$file_name = preg_replace("/ /", '_', $file_name);
			$file_name = preg_replace("/&/", '_', $file_name);
			$file_name = preg_replace("/{/", '_', $file_name);
			$file_name = preg_replace("/}/", '_', $file_name);
			$upload_file = $upload_path.'/'.$file_name;
			
			if(is_file($upload_file)){
				$ext = pathinfo($_FILES['filename']['name'], PATHINFO_EXTENSION);
				$new_filename = str_replace('.'.$ext, '', $file_name);				
				$file_name = $new_filename.'_'.mdate("%Y%m%d-%H%i%s", $_SERVER['REQUEST_TIME']).'.'.$ext;		
			}
			
			$config['upload_path'] = "$upload_path";
			$config['allowed_types'] = "*";
			$config['remove_spaces'] = TRUE;
			$config['file_name'] = $file_name;
			$config['overwrite'] = TRUE; // true berfungsi untuk replace
			//$config['max_width'] = '1024';
			//$config['max_height'] = '768';
			$this->load->library('upload', $config);
			
			if (!$this->upload->do_upload('filename'))
			{
				$status = 'error';
				$message = $this->upload->display_errors('', '');
			} else {
				$file_path = $this->upload->data();
				$doc_path = $file_path['file_name'];
				
				if(is_file("$upload_path/".$doc_path))
				{
					$add_data = array(
						'submit_user' => $user_id,
						'task_id' => $task_id,
						'tgl_inspeksi' => $date_of_ins->format('Y-m-d'),
						'filename' => $file_path['file_name'],
					);
					
					try {				
						$agenda_file_id = $this->cms_model->save($add_data, 'pesawat_task_inspeksi');	
						$status = 'success';
						$message = 'Inspection Task Report berhasil diupload';
					} catch (Exception $e) {
						$status = 'error';
						$message = $e->getMessage();
						if(is_file("$upload_path/".$doc_path)){
							unlink("$upload_path/".$doc_path);
						}	
					}
				}
			}		
		}else{
			$status = 'warning';
			$message = 'Isian harus salah satu diisi';				
		}
		
		echo json_encode(array("status" => $status, "message" => $message));
	}	
	
	function data_save_item()
	{
		$status = 'error';
        $message = 'Item gagal disimpan';		
		$save_method = $this->input->post('save_method');
		$user_id = $this->session->userdata('user_id');		
		$item_id = $this->input->post('id');		
		$kategori_id = $this->input->post('kategori_id');	
		$ref_id = $this->input->post('ref_id');						
		$deskripsi = $this->input->post('deskripsi');
		
		$additional_data = array(
			'kategori_id' => $kategori_id,
			'ref_id' => $ref_id,
			'deskripsi' => $deskripsi,
		);
		
		if($save_method == "update" ){
			$additional_data['update_user'] = $user_id;
			$additional_data['update_date'] = gmdate("Y-m-d H:i:s", time()+60*60*7);
		}else{
			$additional_data['submit_user'] = $user_id;
		}
		
		if($save_method == "update" ){
			try {
				$this->cms_model->update(array('id' => $item_id),$additional_data,'pesawat_schedule');		
				$status = 'success';
				$message = "Item berhasil diubah";
			} catch (Exception $e) {
				$status = 'error';
				$message = "Item gagal diubah";
			}
		} else {
			$upload_path = '';
			$doc_path = '';
			
			if(!empty($_FILES['filename']['name']) && $_FILES['filename']['name']!='' && $_FILES['filename']['name']!='undefined')
			{
				//path directory
				$upload_path = $this->config->item('uploads')['scheduling_report'];
				//membuat directory jika belum ada
				$this->custom->makeDir($upload_path);
				
				$file_name = $_FILES["filename"]["name"];
				$file_name = preg_replace("/ /", '_', $file_name);
				$file_name = preg_replace("/&/", '_', $file_name);
				$file_name = preg_replace("/{/", '_', $file_name);
				$file_name = preg_replace("/}/", '_', $file_name);
				$upload_file = $upload_path.'/'.$file_name;
				
				if(is_file($upload_file)){
					$ext = pathinfo($_FILES['filename']['name'], PATHINFO_EXTENSION);
					$new_filename = str_replace('.'.$ext, '', $file_name);				
					$file_name = $new_filename.'_'.mdate("%Y%m%d-%H%i%s", $_SERVER['REQUEST_TIME']).'.'.$ext;		
				}
				
				$config['upload_path'] = "$upload_path";
				$config['allowed_types'] = "*";
				$config['remove_spaces'] = TRUE;
				$config['file_name'] = $file_name;
				$config['overwrite'] = TRUE; // true berfungsi untuk replace
				//$config['max_width'] = '1024';
				//$config['max_height'] = '768';
				$this->load->library('upload', $config);
				
				if ($this->upload->do_upload('filename'))
				{
					$file_path = $this->upload->data();
					$doc_path = $file_path['file_name'];
					
					if(is_file("$upload_path/".$doc_path))
					{
						$additional_data['filename'] = $file_path['file_name'];
					}
				}		
			}
			try {
				$tasks_id = $this->cms_model->save($additional_data, 'pesawat_schedule');	
				$status = 'success';
				$message = "Item berhasil disimpan";
			} catch (Exception $e) {
				$status = 'error';
				$message = "Item gagal disimpan";
				if(is_file("$upload_path/".$doc_path)){
					unlink("$upload_path/".$doc_path);
				}	
			}
		}
		
		echo json_encode(array("status" => $status, "message" => $message));
	}	
	
	public function data_delete()
	{			
        $id = $this->input->post('id_delete_data');						
        $tablename = $this->input->post('tablename');						
		$file_row = $this->cms_model->row_get_by_id($id, $tablename);
		$status = 'error';
		$message = '';
		
		//hapus file
		if($file_row->id != ""){
			//hapus note
			try {				
				$this->cms_model->delete($tablename, array('id' => $id));	
				switch($tablename) {
					case 'pesawat_komponen_tsn' :
						$upload_path = $this->config->item('uploads')['tsn_aircraft_report'];
						$filename = $file_row->filename;
						if(is_file("$upload_path/".$filename)){
							unlink("$upload_path/".$filename);
						}	
					break;
					case 'pesawat_komponen_inspeksi' :
						$upload_path = $this->config->item('uploads')['ins_aircraft_report'];
						$filename = $file_row->filename;
						if(is_file("$upload_path/".$filename)){
							unlink("$upload_path/".$filename);
						}	
					break;
					case 'pesawat_task_inspeksi' :
						$upload_path = $this->config->item('uploads')['ins_task_report'];
						$filename = $file_row->filename;
						if(is_file("$upload_path/".$filename)){
							unlink("$upload_path/".$filename);
						}	
					break;
					case 'pesawat_schedule' :
						$upload_path = $this->config->item('uploads')['scheduling_report'];
						$filename = $file_row->filename;
						if(is_file("$upload_path/".$filename)){
							unlink("$upload_path/".$filename);
						}	
					break;
				}
				$status = 'success';
				$message = 'Data behasil dihapus';
			} catch (Exception $e) {
				$status = 'error';
				$message = $e->getMessage;
			}				
		}
		
		echo json_encode(array("status" => $status, "message" => $message));
	}
	
	public function download_report($pesawat_id)
	{					
		$user_id = $this->session->userdata('user_id');
		$cur_user = $this->cms_model->row_get_by_id($user_id, 'users');
		$cur_leader = $this->db->query('select * from v_pesawat_komponen_interval where pesawat_id='.$pesawat_id.' AND kat_component_id=1 AND status=1 limit 1')->row();
		
		setlocale(LC_ALL, "IND");
		$file_name = '';
		
		// create new PDF document
		$pdf = new Report_status(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Abdul Aziz');
		$pdf->SetTitle('REPORT STATUS KELAIKUDARAAN PESAWAT UDARA');
		$pdf->SetSubject('REPORT STATUS KELAIKUDARAAN PESAWAT UDARA');
		$pdf->SetKeywords('PDF');

		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+9, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER+10);
		//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}
		
		// ---------------------------------------------------------
		// set font
		$pdf->SetFont('times', '', 8);

		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Print a table
		
		$pdf->CustomHeaderTitle = "REPORT STATUS KELAIKUDARAAN PESAWAT UDARA";

		// add a page
		$pdf->AddPage('L', 'A4');
		
		$html = '';
		$html .= '<br>';
		
		$where = 'pesawat_id='.$pesawat_id.' AND kat_component_id=1 ';
		$list = $this->cms_model->row_get_by_criteria('v_pesawat_komponen', $where);
		
		$html .= '
			<style>
				.label {
					display: inline;
					padding: .2em .6em .3em;
					line-height: 1;
					color: #000;
					text-align: center;
					white-space: nowrap;
					vertical-align: baseline;
					border-radius: .25em;
				}
				.label-success {
					background-color: #5cb85c;
				}
				.label-danger {
					background-color: #d9534f;
				}
			</style>';
		
		$html .= '	<table border="0" style="padding:1px;">
				<tr>
					<td width="60%"></td>
					<td width="20%">
						Prepared by : <b>'.$cur_user->nama.'</b>	
					</td>
					<td width="20%">
						Approved by : <b>'.$cur_leader->nama_approval.'</b>
					</td>
				</tr>
				<tr>
					<td width="60%"></td>
					<td width="20%">
						Sign/ Stamp :<br>	
					</td>
					<td width="20%">
						Sign/ Stamp :<br>	
					</td>
				</tr>
			</table>';
			
		$html .= '<br><br><table border="0.5" style="padding:1px;">
				<tr>
					<td>
						Aircraft Serial number :<br>
						<b>'.$list->serial_number.'</b>	
					</td>
					<td>
						Aircraft Model :<br>
						<b>'.$list->model.'</b>
					</td>
					<td>
						Aircraft Registration :<br>
						<b>'.$list->aircraft_registration.'</b>
					</td>
					<td>
						Registration Number :<br>
						<b>'.$list->number_registration.'</b>
					</td>
					<td>
						Aircraft mfg :<br>
						<b>'.$list->nama_manufacturer.'</b>
					</td>
					<td>
						Aircraft Date of mfg :<br>
						<b>'.strftime("%d %b %Y",strtotime($list->date_of_install)).'</b>
					</td>
					<td>
						Aircraft Total airframe (hours) :<br>
						<b>'.$list->total_tsn.'</b>
					</td>
					<td>
						Aircraft AFL (cycles) :<br>
						<b>'.$list->jml_tso.'</b>
					</td>
					<td>
						Report date :<br>
						<b>'.strftime("%d %b %Y",strtotime(gmdate("Y-m-d H:i:s", time()+60*60*7))).'</b>
					</td>
				</tr>
			</table>';
			
		$html .= '
			<br><br>
			<table border="0.5"  align="center" cellpadding="1" cellspacing="0">
				<thead>
					<tr>
						<td rowspan="2">
							<b>DESCRIPTION</b>
						</td>
						<td rowspan="2">
							<b>MANUFACTURER</b>
						</td>
						<td rowspan="2">
							<b>PART NUMBER OR MODEL</b>
						</td>
						<td rowspan="2">
							<b>SERIAL NUMBER</b>
						</td>
						<td rowspan="2">
							<b>DATE OF INSTALL</b>
						</td>
						<td rowspan="2">
							<b>TSN</b>
						</td>
						<td rowspan="2">
							<b>TSO</b>
						</td>
						<td colspan="2">
							<b>INSPECTION</b>
						</td>
						<td rowspan="2">
							<b>LAST INSPECTION</b>
						</td>
						<td rowspan="2">
							<b>NEXT INSPECTION</b>
						</td>
						<td rowspan="2">
							<b>REMAINING</b>
						</td>
					</tr>
					<tr>
						<td><b>Type</b></td>
						<td><b>Interval</b></td>
					</tr>
				</thead>
				<tbody>';
				
		$datatable_name = "v_pesawat_komponen_interval";//$this->table_name
		$search_column = array();
		$search_order = array();
		$where =  array('pesawat_id' => $pesawat_id, 'status' => 1);
		$order_by = 'kat_component_id asc, nama_interval asc, kategori_interval asc, lama_interval asc';				
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();		
		$no = 0;		
		foreach ($list as $list_item) {
			$no++;

			$html .= '
					<tr>
						<td>
							'.$list_item->nama_komponen.'
						</td>
						<td>
							'.$list_item->nama_manufacturer.'
						</td>
						<td>
							'.$list_item->model.'
						</td>
						<td>
							'.$list_item->serial_number.'
						</td>
						<td>
							'.$list_item->date_of_install.'
						</td>
						<td>
							'.$list_item->total_tsn.'
						</td>
						<td>
							'.$list_item->jml_tso.'
						</td>
						<td>
							'.$list_item->nama_interval.'
						</td>
						<td>
							'.($list_item->lama_interval.' '.($list_item->kategori_interval!=''?($list_item->kategori_interval==1?'hrs':'year'):'')).'
						</td>
						<td>
							'.($list_item->total_inspeksi_disetujui>0?$list_item->tgl_inspeksi:'-').'
						</td>
						<td>
							'.$list_item->next_due.'
						</td>
						<td>
							'.($list_item->total_inspeksi_disetujui==0?($list_item->interval_type_id!=''?('<span class="label label-'.($list_item->remaining<=0?'danger':'success').'">'.$list_item->remaining.($list_item->kategori_interval==1?' hrs':' mnth').'</span>'):''):'-').'
						</td>
						
					</tr>';
			
		}
					
		$html .= '
				</tbody> 
			</table>';
		
		$html .='
			<br><br><table border="0">
				<tr>';		
			
		$html .='<td width="49%">
			<table border="0.5"  align="center" cellpadding="1" cellspacing="0">
				<thead>
					<tr>
						<td>
							<b>DESCRIPTION</b>
						</td>
						<td>
							<b>DATE OF ISSUE</b>
						</td>
						<td>
							<b>INTERVAL</b>
						</td>
						<td>
							<b>NEXT DUE</b>
						</td>
						<td>
							<b>REMAINING (MONTHS)</b>
						</td>
					</tr>
				</thead>
				<tbody>';
				
		$datatable_name = "v_pesawat_task";//$this->table_name
		$search_column = array();
		$search_order = array();
		$where =  array('pesawat_id' => $pesawat_id, 'status' => 1);
		$order_by = 'komponen_kategori_id asc';				
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = 0;			
		foreach ($list as $list_item) {
			$no++;
			$html .= '
					<tr>
						<td>
							'.$list_item->nama_komponen.'
						</td>
						<td>
							'.($list_item->last_inspeksi!=''?$list_item->last_inspeksi:$list_item->date_of_issue).'
						</td>
						<td>
							'.($list_item->lama_interval.' '.$list_item->nama_kategori_interval).'
						</td>
						<td>
							'.$list_item->next_due.'
						</td>
						<td>
							<span class="label label-'.($list_item->remaining<=0?'danger':'success').'">'.$list_item->remaining.'</span>
						</td>
					</tr>';
		}
		
		$html .= '
				</tbody> 
			</table></td>
			<td width="2%"></td>
			<td width="49%">
			<table border="0.5"  cellpadding="1" cellspacing="0">
				<thead>
					<tr>
						<td align="center" width="25%">
							<b>OPEN ITEM AND SCHEDULING</b>
						</td>
						<td align="center" width="75%">
							<b>REMARK</b>
						</td>
					</tr>
				</thead>
				<tbody>';
				
		$table_name = 'v_pesawat_schedule';
		$is_distinct = 'false';
		$select = '*';
		$where = '';
		$where_in_field = '';
		$where_in_array = array('pesawat_id' => $pesawat_id);
		$order_by = 'submit_date desc';	
		$group_by = '';
		$limit = '5';
		
		$list = $this->cms_model->get_query_rows($table_name, $is_distinct, $select, $where, $where_in_field, $where_in_array, $order_by, $group_by, $limit);
			
		foreach ($list as $list_item) {				
			$html .='
				<tr>
					<td width="25%">
						'.$list_item->nama_komponen.'<br>
						<i>'.strftime("%d %b %Y",strtotime($list_item->submit_date)).'</i>
					</td>
					<td width="75%">
						'.$list_item->deskripsi.'
					</td>
				</tr>';
		}
		
		$html .='
				</tbody> 
			</table>
			</td>
			';
			
		$html .='</tr>
			</table>';

		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');

		// reset pointer to the last page
		$pdf->lastPage();
		
		//Close and output PDF document
		$pdf->Output('Report.pdf', 'I');
	}
}
