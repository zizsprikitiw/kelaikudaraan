<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Task extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth','form_validation','custom','encryption'));
		$this->load->helper(array('url','language'));
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
		$this->data['title'] = "Task";
		$this->data['user_menu'] = $this->cms_model->get_user_menu($this->uri->rsegment(1));
		$this->data['user'] = $this->ion_auth->user()->row();
		$this->data['body_class'] = $this->custom->bodyClass('default');
		
		//tambahan css plugin
		$this->data['add_css'] = array(
			base_url($this->config->item('assets')['global_plugins'])."/datatables/datatables.min.css",
			base_url($this->config->item('assets')['global_plugins'])."/datatables/plugins/bootstrap/datatables.bootstrap.css",
			base_url($this->config->item('assets')['global_plugins'])."/bootstrap-sweetalert/sweetalert.css",
			base_url($this->config->item('assets')['global_plugins'])."/bootstrap-multiselect/css/bootstrap-multiselect.css",
			base_url($this->config->item('assets')['global_plugins'])."/bootstrap-datepicker/css/bootstrap-datepicker3.min.css",
			base_url($this->config->item('assets')['global_plugins'])."/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css",
			base_url($this->config->item('assets')['global_plugins'])."/bootstrap-fileinput/bootstrap-fileinput.css",
			base_url($this->config->item('assets')['global_plugins'])."/morris/morris.css",
			base_url($this->config->item('assets')['global_plugins'])."/fullcalendar-3.9.0/fullcalendar.min.css",
			base_url($this->config->item('assets')['global_plugins'])."/jqvmap/jqvmap/jqvmap.css",
			base_url($this->config->item('assets')['global_plugins'])."/getorgchart/getorgchart.css",
		);
		
		//tambahan javascript plugin
		$this->data['add_javascript'] = array(
			//base_url($this->config->item('assets')['custom_scripts'])."/pages.js",
			base_url($this->config->item('assets')['custom_scripts'])."/task.js",
			base_url($this->config->item('assets')['global_plugins'])."/moment.min.js",
			base_url($this->config->item('assets')['global_plugins'])."/bootstrap-datepicker/js/bootstrap-datepicker.min.js",
			base_url($this->config->item('assets')['global_plugins'])."/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js",
			base_url($this->config->item('assets')['pages_scripts'])."/components-date-time-pickers.min.js",
			base_url($this->config->item('assets')['global_scripts'])."/datatable.js",
			base_url($this->config->item('assets')['global_plugins'])."/datatables/datatables.min.js",
			base_url($this->config->item('assets')['global_plugins'])."/datatables/plugins/bootstrap/datatables.bootstrap.js",
			base_url($this->config->item('assets')['pages_scripts'])."/ui-sweetalert.min.js",
			base_url($this->config->item('assets')['global_plugins'])."/bootstrap-sweetalert/sweetalert.min.js",
			base_url($this->config->item('assets')['global_plugins'])."/bootstrap-multiselect/js/bootstrap-multiselect.js",
			
			base_url($this->config->item('assets')['global_plugins'])."/getorgchart/getorgchart.js",
			base_url($this->config->item('assets')['global_plugins'])."/bootstrap-fileinput/bootstrap-fileinput.js",
		);
		
		$this->load->view('task',$this->data);
	}
	
	public function data_init()
	{
		$table_name = 'tasks_modul';
		$where = '';
		$order_by = 'id ASC';
		$filter_modul = array();
		
		$list_modul = $this->cms_model->query_get_by_criteria($table_name, $where, $order_by);
		
		foreach ($list_modul as $list_item) {
			$filter_modul[] = array("id_item" => $list_item->id, "nama_item" => $list_item->nama_modul);
		}
						
		$data['filter_modul'] = $filter_modul;  
			
		echo json_encode($data);
	}
	
	public function data_list()
	{
		$is_admin = $this->cms_model->user_is_admin(); 
		$user_id = $this->session->userdata('user_id');
		$chkSearch = $this->input->post('chkSearch');
		
		$datatable_name = 'v_tasks';
		$where = array();
		$search_column = array('nama_task');
		$search_order = array();
		$order_by = 'nama_task ASC';	

		if($chkSearch[0] != "")
		{			
			foreach($chkSearch as $chkSearch_item)						
			{
				if($chkSearch_item == "modul"){
					$where['modul_id'] =  $this->input->post('filter_modul');					
				}
			}
		}		
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$aksi = '';
			if($is_admin){
				$aksi .= '<a class="btn btn-xs btn-warning" href="javascript:void()" data-toggle="tooltip" title="Edit" onclick="loadFormUpdate('."'".$list_item->id."'".')"><i class="fa fa-pencil"></i></a>';
			}
			
			$reminder = '';
			
			//reminder due date
			$date1=date_create(date('Y-m-d'));
			$date2=date_create($list_item->due_date);
			$diff=date_diff($date1,$date2);
			
			if($diff->format('%R%a')<=14 && strtolower($list_item->nama_status)!='completed'){
				$reminder = '<span class="task-bell" data-toggle="tooltip" data-placement="right" title="'.$diff->format('%R%a days').'"><i class="fa fa-bell-o"></i></span>';
			}
			
			$admin = '';
			if ($is_admin) {
				$admin = $user_id;
			}
			$members = explode(',', trim($list_item->id_members, '{}'));
			array_push($members,$list_item->pic_id,$list_item->approval_id,$admin);
							
			$file_current = $this->db->query("select user_id, submit_date, filename from v_tasks_file_current where tasks_id=".$list_item->id." AND jenis_file=2 order by submit_date desc limit 1")->row();
							
			$link_doc = '';
			if(!empty($file_current)) {
				$link_doc = '<a href="'.base_url($this->config->item('uploads')['tasks']).'/'.$file_current->user_id.'/'.$file_current->filename.'" target="_blank"  class="btn btn-xs red" data-toggle="tooltip" title="Download Dokumen" data-placement="left" ><i class="fa fa-file-pdf-o"></i></a>';
			}
			
			$no++;
			$row = array();
			$row[] = $no;			
			$row[] = (in_array($user_id,$members)?'<a href="'.base_url('index/task/'.base64_encode($list_item->id)).'" target="_blank">':'').$list_item->nama_task.(in_array($user_id,$members)?'</a>':'');	
			$row[] = $list_item->nama_pic;
			$row[] = $list_item->nama_approval;
			$row[] = '<span class="label label-sm label-'.$this->custom->statusColor($list_item->nama_status).'">'.$list_item->nama_status.' <span class="badge bg-white bg-font-white" style="height:17px">'.$list_item->progress.'%</span></span> ';		
			$row[] = $list_item->due_date;	
			$row[] = $link_doc.$aksi;
			$data[] = $row;
		}
		
		$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->cms_model->count_all_where($datatable_name, $where),
				"recordsFiltered" => $this->cms_model->count_filtered_where($datatable_name, $search_column, $search_order, $where, $order_by),
				"data" => $data,
		);
		
		echo json_encode($output);
	}
	
	public function data_form_add()
	{
		$table_name = 'proyek';
		$where = '';
		$order_by = 'id ASC';
		$filter_program = array();
		
		$list_program = $this->cms_model->query_get_by_criteria($table_name, $where, $order_by);
		
		foreach ($list_program as $list_item) {
			$filter_program[] = array("id_item" => $list_item->id, "nama_item" => $list_item->nama);
		}
			
		$data['filter_program_form'] = $filter_program;
		echo json_encode($data);
	}
	
	public function data_form_update()
	{
		$tasks_id = $this->input->post('tasks_id');
		$table_name = 'v_tasks';
		$where = 'id='.$tasks_id.'';
		
		$list_task = $this->cms_model->row_get_by_criteria($table_name, $where);
		
		$table_name = 'proyek';
		$where = '';
		$order_by = 'id ASC';
		$filter_program = array();
		
		$list_program = $this->cms_model->query_get_by_criteria($table_name, $where, $order_by);
		
		foreach ($list_program as $list_item) {
			$filter_program[] = array("id_item" => $list_item->id, "nama_item" => $list_item->nama);
		}
			
		$data['list_task'] = $list_task;
		$data['filter_program_form'] = $filter_program;
		echo json_encode($data);
	}
	
	public function data_select_form_add()
	{
		$user_id = $this->session->userdata('user_id');
		$proyek_id = $this->input->post('proyek_id');
		$table_name = 'v_users_posisi';
		$where = 'proyek_id='.$proyek_id.'';
		$order_by = 'id ASC';
		$filter_posisi = array();
		$filter_user = array();
		
		$list_user = $this->cms_model->query_get_by_criteria($table_name, $where, $order_by);
		
		foreach ($list_user as $list_item) {
			$filter_posisi[$list_item->leader_nama][] = array("id_item" => $list_item->id, "nama_item" => ($list_item->nama_user.' ['.$list_item->nama_posisi.']'));
			$filter_user[$list_item->leader_nama][] = array("id_item" => $list_item->user_id, "nama_item" => ($list_item->nama_user.' ['.$list_item->nama_posisi.']'));
		}
		
		$table_name = 'tasks_modul';
		$where = 'proyek_id='.$proyek_id.'';
		$order_by = 'id ASC';
		$filter_modul = array();
		
		$list_modul = $this->cms_model->query_get_by_criteria($table_name, $where, $order_by);
		
		foreach ($list_modul as $list_item) {
			$filter_modul[] = array("id_item" => $list_item->id, "nama_item" => $list_item->nama_modul);
		}
			
		$data['filter_pic'] = $filter_posisi;
		$data['filter_approval'] = $filter_posisi;
		$data['filter_member'] = $filter_user;
		$data['filter_modul_form'] = $filter_modul;
		echo json_encode($data);
	}
	
	function data_save()
	{
		$status = 'error';
        $message = 'Task gagal disimpan';		
		$save_method = $this->input->post('save_method');
		$user_id = $this->session->userdata('user_id');		
		$tasks_id = $this->input->post('id');															
		$modul_id = $this->input->post('modul_id');														
		$nama_task = $this->input->post('nama_task');
		$deskripsi = $this->input->post('deskripsi');			
		$start_date = new DateTime($this->input->post('start_date'));
		$due_date = new DateTime($this->input->post('due_date'));
		$posisi_pic_id = $this->input->post('posisi_pic_id');			
		$posisi_approval_id = $this->input->post('posisi_approval_id');			
		$member_id = $this->input->post('member_id');	
		
		$additional_data = array(
			'update_user' => $user_id,
			'modul_id' => $modul_id,
			'nama_task' => $nama_task,
			'posisi_pic_id' => $posisi_pic_id,
			'posisi_approval_id' => $posisi_approval_id,
			'deskripsi' => $deskripsi,
			'start_date' => $start_date->format('Y-m-d'),
			'due_date' => $due_date->format('Y-m-d'),
		);
		
		if($save_method == "update" ){
			$additional_data['update_date'] = gmdate("Y-m-d H:i:s", time()+60*60*7);
		}else{
			$additional_data['user_id'] = $user_id;
		}
		
		if($save_method == "update" ){
			try {
				$this->cms_model->update(array('id' => $tasks_id),$additional_data,'tasks');	
				try {
					$this->cms_model->delete('tasks_members', array('tasks_id' => $tasks_id));	
				} catch (Exception $e) {
					$e->getMessage();
				}	
				$status = 'success';
				$message = "Task berhasil diubah";
			} catch (Exception $e) {
				$status = 'error';
				$message = "Task gagal diubah";
			}
		} else {
			try {
				$tasks_id = $this->cms_model->save($additional_data, 'tasks');
				try {
					$this->cms_model->delete('tasks_members', array('tasks_id' => $tasks_id));	
				} catch (Exception $e) {
					$e->getMessage();
				}	
				$status = 'success';
				$message = "Task berhasil disimpan";
			} catch (Exception $e) {
				$status = 'error';
				$message = "Task gagal disimpan";
			}
		}
		
		if($member_id!=''){
			foreach($member_id as $item) {
				$user_data = array(
					'tasks_id' => $tasks_id,
					'user_id' => $item
				);
				
				$tasks_members_is = $this->cms_model->save($user_data, "tasks_members");
			}
		}
		
		echo json_encode(array("status" => $status, "message" => $message));
	}	
}
