<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Beranda_task extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth','form_validation','custom','encryption'));
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
	
	// log the user in
	function index()
	{
		if ($this->ion_auth->logged_in())
		{
			redirect('index', 'refresh');	
		}
	}
	
	public function data_form_update_task()
	{
		$user_id = $this->session->userdata('user_id');
		$tasks_id = $this->input->post('tasks_id');
		$table_name = 'tasks';
		$where = 'id='.$tasks_id.'';
		
		$list_task = $this->cms_model->row_get_by_criteria($table_name, $where);
		
		$filter_status[] = array("id_item" => 1, "nama_item" => "Proses");
		$filter_status[] = array("id_item" => 2, "nama_item" => "Pending");
		$filter_status[] = array("id_item" => 3, "nama_item" => "Completed");
		$filter_status[] = array("id_item" => 4, "nama_item" => "Urgent");
			
		$data['id'] = $list_task->id;
		$data['status'] = $list_task->status;
		$data['progress'] = $list_task->progress;
		$data['deskripsi'] = $list_task->deskripsi;
		$data['filter_status'] = $filter_status;
		echo json_encode($data);
	}
	
	public function data_add_file_comment()
	{
		$user_id = $this->session->userdata('user_id');
		$tasks_id = $this->input->post('tasks_id');
		$table_name = 'v_tasks';
		$where = 'id='.$tasks_id.'';
		
		$list_task = $this->cms_model->row_get_by_criteria($table_name, $where);
		
		if($list_task->approval_id==$user_id){
			$filter_jenis_file_task[] = array("id_item" => 1, "nama_item" => "Sample");
			$type_file[1] = $this->config->item('files')['pdf_file_type'];
		}
		if($list_task->pic_id==$user_id){
			$filter_jenis_file_task[] = array("id_item" => 2, "nama_item" => "Dokumen Teknis");
			$type_file[2] = $this->config->item('files')['doc_file_type'];
		}
		$filter_jenis_file_task[] = array("id_item" => 0, "nama_item" => "Other");
		$type_file[0] = $this->config->item('files')['all_file_type'];
		
		$data['filter_jenis_file_task'] = $filter_jenis_file_task;		
		$data['type_file'] = $type_file;		
		echo json_encode($data);
	}
	
	public function data_task_deskripsi()
	{
		$user_id = $this->session->userdata('user_id');
		$tasks_id = $this->input->post('tasks_id');
		$table_name = 'v_tasks';
		$where = 'id='.$tasks_id.'';
		
		$list_task = $this->cms_model->row_get_by_criteria($table_name, $where);
		
		$con = '';
		setlocale(LC_ALL, "IND");
		if(!empty($list_task)) {
			$con .= '<div class="note note-'.$this->custom->statusColor($list_task->nama_status).'">
						'.(!empty($list_task->deskripsi)?'<p>'.$list_task->deskripsi.'</p>':'').'
						<div class="'.(!empty($list_task->deskripsi)?'margin-top-10':'').'"><p><b>Status:</b> '.$list_task->nama_status.'</p>
						<p><b>Progress:</b> <span class="badge bg-white bg-font-white">'.$list_task->progress.'%</span></p>
						<p><b>Due Date:</b> '.strftime("%a, %d %b %Y",strtotime($list_task->due_date)).'</p>
						</div>
					</div>';
		}
		
		$data['deskripsi'] = $con;		
		echo json_encode($data);
	}
	
	public function data_task_comments()
	{
		$user = $this->ion_auth->user()->row();
		$user_id = $this->session->userdata('user_id');
		$tasks_id = $this->input->post('tasks_id');
		$table_name = 'v_tasks_comments';
		$where = 'tasks_id='.$tasks_id.'';
		$order_by = 'submit_date ASC';
		$array_comments = array();
		
		$list_comment = $this->cms_model->query_get_by_criteria($table_name, $where, $order_by);
		setlocale(LC_ALL, "IND");
		
		$no = 0;
		foreach ($list_comment as $list_item) {
			$parent_id = $list_item->parent_id != null ? $list_item->parent_id : $list_item->id;
			$sub_comment = $list_item->parent_id != null ? 'sub_comment' : '';
			if($list_item->parent_id != null) {
				$array_comments[$parent_id][$sub_comment][] = array(
					"id" => $list_item->id,
					"parent_id" => $list_item->parent_id,
					"tasks_id" => $list_item->tasks_id,
					"user_id" => $list_item->user_id,
					"nama" => $list_item->nama,
					"photo" => $list_item->photo,
					"submit_date" => $list_item->submit_date,
					"message" => $list_item->message,
					"filename" => $list_item->filename,
					"tasks_file_id" => $list_item->tasks_file_id
				);
			} else {
				$array_comments[$parent_id] = array(
					"id" => $list_item->id,
					"parent_id" => $list_item->parent_id,
					"tasks_id" => $list_item->tasks_id,
					"user_id" => $list_item->user_id,
					"nama" => $list_item->nama,
					"photo" => $list_item->photo,
					"submit_date" => $list_item->submit_date,
					"message" => $list_item->message,
					"filename" => $list_item->filename,
					"tasks_file_id" => $list_item->tasks_file_id
				);
			}
		}
		
		$el = '';
		foreach ($array_comments as $item) {
				$filename = $item['filename'] !=  '' ? '<a href="'.base_url($this->config->item('uploads')['tasks']).'/'.$item['user_id'].'/'.$item['filename'].'" target="_blank" class="btn btn-xs green">'.$item['filename'].' <i class="fa fa-file"></i></a>' : ($item['tasks_file_id'] !=  '' ? '<a class="btn btn-xs green">File telah dihapus</a>' : ''); 
				$el .= '<li class="media">
							<a class="pull-left" href="javascript:;">
								<img class="todo-userpic" src="'.base_url($this->config->item('uploads')['users_thumb50x50']).'/'.$item['photo'].'" onerror="this.src = \"'.base_url($this->config->item('assets')['custom_img']).'/50x50.png\" width="27px" height="27px">
							</a>
							<div class="media-body todo-comment">
								<button type="button" class="todo-comment-btn btn btn-circle btn-default btn-sm reply-toggle" data-id="'.$item['id'].'">&nbsp; Reply &nbsp;</button>
								<p class="todo-comment-head">
									<span class="todo-comment-username">'.$item['nama'].'</span> &nbsp;
									<span class="todo-comment-date">'.strftime("%d %b %Y at %R",strtotime($item['submit_date'])).'</span>
								</p>
								<p class="todo-text-color"> '.$item['message'].'&nbsp;&nbsp;'.$filename.'</p>
								<!-- Nested media object -->';
				if(!empty($item['sub_comment'])){
				foreach ($item['sub_comment'] as $sub_item) {
				$filename = $sub_item['filename'] !=  '' ? '<a href="'.base_url($this->config->item('uploads')['tasks']).'/'.$sub_item['user_id'].'/'.$sub_item['filename'].'" target="_blank" class="btn btn-xs green">'.$sub_item['filename'].' <i class="fa fa-file"></i></a>' : ''; 
				$el .= '		<div class="media">
									<a class="pull-left" href="javascript:;">
										<img class="todo-userpic" src="'.base_url($this->config->item('uploads')['users_thumb50x50']).'/'.$sub_item['photo'].'" onerror="this.src = \"'.base_url($this->config->item('assets')['custom_img']).'/50x50.png\" width="27px" height="27px">
									</a>
									<div class="media-body">
										<p class="todo-comment-head">
											<span class="todo-comment-username">'.$sub_item['nama'].'</span> &nbsp;
											<span class="todo-comment-date">'.strftime("%d %b %Y at %R",strtotime($sub_item['submit_date'])).'</span>
										</p>
										<p class="todo-text-color"> '.$sub_item['message'].'&nbsp;&nbsp;'.$filename.'</p>
									</div>
								</div>';
				}
				}
				$el .= 		'<!-- TASK COMMENT FORM -->
						<ul class="media-list" style="display:none">
							<li class="media">
								<a class="pull-left" href="javascript:;">
									<img class="todo-userpic" src="'.base_url($this->config->item('uploads')['users_thumb50x50']).'/'.$user->photo.'" onerror="this.src = \"'.base_url($this->config->item('assets')['custom_img']).'/50x50.png\";" width="27px" height="27px">
								</a>						
								<div class="media-body">
									<form id="reply_comment'.$item['id'].'" action="#">
										<div class="form-group">
											<textarea class="form-control todo-taskbody-taskdesc" name="message" rows="1" placeholder="Reply..."></textarea>
										</div>
										<div class="form-group">
											<button type="button" class="pull-right btn btn-sm btn-circle green" onClick="replyComments('."'".$item['id']."','".$item['tasks_id']."'".')"> &nbsp; Submit &nbsp; </button>
										</div>
									</form>
								</div>
							</li>
						</ul>
				<!-- END TASK COMMENT FORM -->';  
				$el .= 		'</div>
						</li>';
		}
		
		$data['comments'] = $el;		
		echo json_encode($data);
	}
	
	public function data_append_task_comments($id)
	{
		$user = $this->ion_auth->user()->row();
		$user_id = $this->session->userdata('user_id');
		$table_name = 'v_tasks_comments';
		$where = 'id='.$id.'';
		$order_by = 'submit_date ASC';
		$array_comments = array();
		
		$list_comment = $this->cms_model->query_get_by_criteria($table_name, $where, $order_by);
		setlocale(LC_ALL, "IND");
		
		$no = 0;
		foreach ($list_comment as $list_item) {
			$parent_id = $list_item->id;
			$array_comments[$parent_id] = array(
				"id" => $list_item->id,
				"parent_id" => $list_item->parent_id,
				"tasks_id" => $list_item->tasks_id,
				"user_id" => $list_item->user_id,
				"nama" => $list_item->nama,
				"photo" => $list_item->photo,
				"submit_date" => $list_item->submit_date,
				"message" => $list_item->message,
				"filename" => $list_item->filename
			);
		}
		
		$el = '';
		foreach ($array_comments as $item) {
				$filename = $item['filename'] !=  '' ? '<a href="'.base_url($this->config->item('uploads')['tasks']).'/'.$item['user_id'].'/'.$item['filename'].'" class="btn btn-xs green">Lampiran <i class="fa fa-file"></i></a>' : ''; 
				$el .= '<li class="media">
							<a class="pull-left" href="javascript:;">
								<img class="todo-userpic" src="'.base_url($this->config->item('uploads')['users_thumb50x50']).'/'.$item['photo'].'" onerror="this.src = \"'.base_url($this->config->item('assets')['custom_img']).'/50x50.png\" width="27px" height="27px">
							</a>
							<div class="media-body todo-comment">
								<button type="button" class="todo-comment-btn btn btn-circle btn-default btn-sm reply-toggle" data-id="'.$item['id'].'">&nbsp; Reply &nbsp;</button>
								<p class="todo-comment-head">
									<span class="todo-comment-username">'.$item['nama'].'</span> &nbsp;
									<span class="todo-comment-date">'.strftime("%d %b %Y at %R",strtotime($item['submit_date'])).'</span>
								</p>
								<p class="todo-text-color"> '.$item['message'].'&nbsp;&nbsp;'.$filename.'</p>
								<!-- Nested media object -->';
				$el .= 		'<!-- TASK COMMENT FORM -->
						<ul class="media-list" style="display:none">
							<li class="media">
								<a class="pull-left" href="javascript:;">
									<img class="todo-userpic" src="'.base_url($this->config->item('uploads')['users_thumb50x50']).'/'.$user->photo.'" onerror="this.src = \"'.base_url($this->config->item('assets')['custom_img']).'/50x50.png\";" width="27px" height="27px">
								</a>						
								<div class="media-body">
									<div class="form-group">
										<textarea class="form-control todo-taskbody-taskdesc" rows="1" placeholder="Reply..."></textarea>
									</div>
									<div class="form-group">
										<button type="button" class="pull-right btn btn-sm btn-circle green" onClick="replyComments()"> &nbsp; Submit &nbsp; </button>
									</div>
								</div>
							</li>
						</ul>
				<!-- END TASK COMMENT FORM -->';  
				$el .= 		'</div>
						</li>';
		}
		
		return $el;
	}
	
	public function data_list_file_report_task()
	{
		$is_admin = $this->cms_model->user_is_admin();
		$user_id = $this->session->userdata('user_id');
		$tasks_id = $this->input->post('tasks_id');
		
		$datatable_name = 'v_tasks_file';
		$where = 'tasks_id = '.$tasks_id.' AND jenis_file=2 ';
		$search_column = array();
		$search_order = array();
		$order_by = 'submit_date desc';		
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			//$filename = '<a href="'.base_url($this->config->item('uploads')['tasks']).'/'.$list_item->user_id.'/'.$list_item->filename.'">'.str_replace('_',' ',$list_item->filename).'</a>';
			$filename = '<a href="javascript:void()" onclick="show_laporan('."'".$list_item->id."'".')" >'.str_replace('_',' ',$list_item->filename).'</a>';	
			
			$aksi = '';
			$hapus = '';
			$approve = '';
			$status_approve = false;
			if($is_admin){
				$hapus = '<li><a href="javascript:;" onclick="data_delete('."'".$list_item->id."','".$list_item->filename."'".')"><i class="fa fa-trash font-red"></i> Hapus </a></li>';
			} else if ($user_id==$list_item->user_id) {
				$hapus = '';
				$date = strtotime($list_item->submit_date);
				$dateweek = strtotime("+7 day", $date);
				$datenow = strtotime("now");
				if($datenow<=$dateweek) {
					$hapus = '<li><a href="javascript:;" onclick="data_delete('."'".$list_item->id."','".$list_item->filename."'".')"><i class="fa fa-trash font-red"></i> Hapus </a></li>';
				}
			}
			
			$approval = explode('","', trim($list_item->approval, '{""}'));
			$arraydata = array();
			$col_approval = '';
			
			foreach($approval as $app) {
				$arraydata = explode(',', trim($app, '()'));
				$nama_approval = trim($arraydata[2], '\"\"');
				$border = $arraydata[5]==1?'border: 2px solid #00ff00':($arraydata[5]==2?'border: 2px solid #ff0000':'');
				$title = $arraydata[5]==1?'Approved by '.$nama_approval:($arraydata[5]==2?'Rejected by '.$nama_approval:'Not yet approved by '.$nama_approval);
				
				$status_approve = $arraydata[1]==$user_id&&$arraydata[5]==null?true:$status_approve;
				
				$col_approval .= '<a href="javascript:;" onclick="show_deskripsi('."'".$list_item->id."','".$arraydata[1]."','".$nama_approval."'".')"><img class="todo-userpic" style="'.$border.'" src="'.($arraydata[3]!=''?base_url($this->config->item('uploads')['users_thumb50x50']).'/'.$arraydata[3]:base_url($this->config->item('assets')['custom_img']).'/50x50.png').'" onerror="this.src = \"'.base_url($this->config->item('assets')['custom_img']).'/50x50.png\" title="'.$title.'" width="27px" height="27px"></a>';
			}
			
			if($status_approve) {
				$approve = '<li><a href="javascript:;" onclick="approval_laporan('."'".$list_item->id."','".$list_item->pengirim."','".str_replace('_',' ',$list_item->filename)."','".$list_item->id."'".')"><i class="fa fa-check font-green"></i> Approve </a></li><li class="divider"></li>';
			}
			
			$aksi .= '<div class="btn-group">
						<button class="btn green btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Aksi
							<i class="fa fa-angle-down"></i>
						</button>
						<ul class="dropdown-menu pull-right" role="menu">
							'.$approve.'
							'.$hapus.'
							<li>
								<a href="'.base_url($this->config->item('uploads')['tasks']).'/'.$list_item->user_id.'/'.$list_item->filename.'" target="_blank"><i class="fa fa-download font-yellow"></i> Download </a>
							</li>
						</ul>
					</div>';
			
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $filename;		
			$row[] = date_format(date_create($list_item->submit_date),"j M Y");		
			$row[] = $col_approval; 
			$row[] = $aksi;	
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
	
	public function data_list_file_other_task()
	{
		$is_admin = $this->cms_model->user_is_admin();
		$user_id = $this->session->userdata('user_id');
		$tasks_id = $this->input->post('tasks_id');
		
		$datatable_name = 'tasks_file';
		$where = 'tasks_id = '.$tasks_id.' AND jenis_file<>2 ';
		$search_column = array();
		$search_order = array();
		$order_by = '(CASE WHEN jenis_file = 0 THEN 5 ELSE jenis_file END) asc, submit_date desc';		
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$filename = '<a href="'.base_url($this->config->item('uploads')['tasks']).'/'.$list_item->user_id.'/'.$list_item->filename.'" target="_blank">'.str_replace('_',' ',$list_item->filename).'</a>';
			$aksi = '';
			if($is_admin){
				$aksi .= '<a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete('."'".$list_item->id."','".$list_item->filename."'".')"><i class="fa fa-times"></i></a>';
			} else if ($user_id==$list_item->user_id) {
				$date = strtotime($list_item->submit_date);
				$dateweek = strtotime("+7 day", $date);
				$datenow = strtotime("now");
				if($datenow<=$dateweek) {
					$aksi .= '<a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete('."'".$list_item->id."','".$list_item->filename."'".')"><i class="fa fa-times"></i></a>';
				}
			}
			
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $filename;		
			$row[] = date_format(date_create($list_item->submit_date),"j M Y");				
			$row[] = $aksi;	
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
	
	function data_add_comment()
	{
		$status = 'error';
        $message = 'Gagal tambah komentar';		
		$user_id = $this->session->userdata('user_id');
		$tasks_id = $this->input->post('tasks_id');														
		$jenis_file_task = $this->input->post('jenis_file_task');														
		$message = $this->input->post('message');		
		$filename = $this->input->post('filename');	
		$comment = '';
		
		if((!empty($_FILES['filename']['name']) && $_FILES['filename']['name']!='' && $_FILES['filename']['name']!='undefined')||(!empty($message) && $message!='' && $message!='undefined' && $message!=null))
		{
			$additional_data = array(
				'user_id' => $user_id,
				'tasks_id' => $tasks_id,
				'message' => $message,
			);
					
			if(!empty($_FILES['filename']['name'])) {
				//path directory
				$upload_path = $this->config->item('uploads')['tasks'].'/'.$user_id;
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
							'user_id' => $user_id,
							'tasks_id' => $tasks_id,
							'jenis_file' => $jenis_file_task,
							'filename' => $file_path['file_name'],
						);
						
						try {				
							$tasks_file_id = $this->cms_model->save($add_data, 'tasks_file');	
							$additional_data['tasks_file_id'] = $tasks_file_id;
						} catch (Exception $e) {
							if(is_file("$upload_path/".$doc_path)){
								unlink("$upload_path/".$doc_path);
							}	
						}
					}
				}		
			}
			
			try {				
				$tasks_comments_id = $this->cms_model->save($additional_data, 'tasks_comments');	
				$comment = $this->data_append_task_comments($tasks_comments_id);
				$status = 'success';
				$message = 'Komentar telah ditambahkan';
			} catch (Exception $e) {
				$status = 'error';
				$message = $e->getMessage();
			}
		}else{
			$status = 'warning';
			$message = 'Isian harus salah satu diisi';				
		}
		echo json_encode(array("status" => $status, "message" => $message, "comment" => $comment));
	}
	
	function data_reply_comment()
	{
		$status = 'error';
        $message = 'Gagal tambah komentar';		
		$user_id = $this->session->userdata('user_id');
		$tasks_id = $this->input->post('tasks_id');																	
		$parent_id = $this->input->post('parent_id');																	
		$message = $this->input->post('message');	
		$comment = '';
		
		if(!empty($message) && $message!='' && $message!='undefined' && $message!=null)
		{
			$additional_data = array(
				'user_id' => $user_id,
				'tasks_id' => $tasks_id,
				'parent_id' => $parent_id,
				'message' => $message,
			);
			
			try {				
				$tasks_comments_id = $this->cms_model->save($additional_data, 'tasks_comments');
				$status = 'success';
				$message = 'Komentar telah ditambahkan';
			} catch (Exception $e) {
				$status = 'error';
				$message = $e->getMessage();
			}
		}else{
			$status = 'warning';
			$message = 'Isian harus salah satu diisi';				
		}
		echo json_encode(array("status" => $status, "message" => $message, "comment" => $comment));
	}	
	
	function data_update_task()
	{
		$status = 'error';
        $message = 'Task gagal diubah';		
		$user_id = $this->session->userdata('user_id');		
		$tasks_id = $this->input->post('id');		
		$status = $this->input->post('status');														
		$progress = $this->input->post('progress');	
		$deskripsi = $this->input->post('deskripsi');	
		
		$additional_data = array(
			'update_user' => $user_id,
			'update_date' => gmdate("Y-m-d H:i:s", time()+60*60*7),
			'status' => $status,
			'progress' => $progress,
			'deskripsi' => $deskripsi,
		);
		
		try {
			$this->cms_model->update(array('id' => $tasks_id),$additional_data,'tasks');	
			
			$status = 'success';
			$message = "Task berhasil diubah";
		} catch (Exception $e) {
			$status = 'error';
			$message = "Task gagal diubah";
		}
		
		echo json_encode(array("status" => $status, "message" => $message));
	}
	
	function show_laporan()
	{
		$file_id = $this->input->post('file_id');	
		$note_row = $this->cms_model->row_get_by_id($file_id, 'tasks_file');		
		//var_dump($note_row);die();
		$note_file = $note_row->filename;						
		$folder_note = base_url('uploads/tasks/'.$note_row->user_id);
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
	
	function show_deskripsi()
	{
		$file_id = $this->input->post('file_id');	
		$user_id = $this->input->post('user_id');	
		$table_name = 'tasks_file_approval';
		$where = 'tasks_file_id='.$file_id.' AND user_id='.$user_id;
		
		$deskripsi_row = $this->cms_model->row_get_by_criteria($table_name, $where);
		
		$status = '<span class="label label-default">Belum disetujui</span>';
		$keterangan = '-';
		
		if(!empty($deskripsi_row)) {
			$keterangan = $deskripsi_row->keterangan;
			$submit_date = strftime("%d %b %Y, %R",strtotime($deskripsi_row->submit_date));
			if($deskripsi_row->status==1) {
				$status = '<span class="label label-success">Disetujui</span> <i>'.$submit_date.'</i>';
			} else if($deskripsi_row->status==2) {
				$status = '<span class="label label-danger">Ditolak</span> <i>'.$submit_date.'</i>';
			}
		}
							
		$data['status'] = $status;
		$data['keterangan'] = $keterangan;			
		echo json_encode($data);	
	}
	
	public function approval_laporan()
	{			
        $status = 'error';
        $message = 'Approve gagal';		
		$user_id = $this->session->userdata('user_id');	
		$task_file_id = $this->input->post('task_file_id');	
        $komentar = $this->input->post('komentar');	
        $jenis_approve = $this->input->post('jenis_approve');
		
		$additional_data = array(
			'user_id' => $user_id,
			'tasks_file_id' => $task_file_id,
			'status' => $jenis_approve,
			'keterangan' => $komentar,
		);

		try {				
			$id = $this->cms_model->save($additional_data, 'tasks_file_approval');	
			$status = 'success';
			$message = 'Approve berhasil';
		} catch (Exception $e) {
			$status = 'error';
			$message = $e->getMessage();
		}
		
		echo json_encode(array("status" => $status, "message" => $message));			
	}
	
	public function data_delete()
	{			
        $file_id = $this->input->post('id_delete_data');						
		$file_row = $this->cms_model->row_get_by_id($file_id, 'tasks_file');
		$status = 'error';
		$message = '';
		
		//hapus file
		if($file_row->filename != ""){
			$upload_path = $this->config->item('uploads')['tasks'];																		
			$upload_file = $upload_path.'/'.$file_row->filename;
			
			//hapus note
			try {				
				$this->cms_model->delete('tasks_file', array('id' => $file_id));		
				$this->cms_model->delete('tasks_file_approval', array('tasks_file_id' => $file_id));	
				$status = 'success';
				$message = 'Data behasil dihapus';
				
				if(is_file($upload_file)){
					unlink($upload_file);
				}	
			} catch (Exception $e) {
				$status = 'error';
				$message = $e->getMessage;
			}				
		}
		
		echo json_encode(array("status" => $status, "message" => $message));
	}
}
