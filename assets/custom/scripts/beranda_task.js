var select_box = function(data,item_select,item_sel) {
	//insert select item				
	var len_item = item_sel.length;
	select_val = -1;
	for(var i=0; i<len_item; i++){
		//get id selected
		for(var key in item_select){
			if(key == item_sel[i]){
				select_val = item_select[key];
			} 
		}
		
		var sel = $("#"+item_sel[i]);						
		sel.empty();					
		var len_sub = data[item_sel[i]].length;
		htmlString = "";//<option value='-- Pilih --' >-- Pilih --</option>						
		for(var j=0; j<len_sub; j++){
			if((select_val == -1) & (j==0)){
				selected_str = "selected='selected'";
			}else if(data[item_sel[i]][j].id_item == select_val){
				selected_str = "selected='selected'";
			}else{
				selected_str = "";
			}
			
			htmlString = htmlString+ "<Option value="+data[item_sel[i]][j].id_item+" "+selected_str+">"+data[item_sel[i]][j].nama_item+"</option>"							
		}
		sel.html(htmlString);	
	}	
}

var loadDesTask = function() {
	var el = $('#task_deskripsi');		
	$.ajax({
		url : base_url+"beranda_task/data_task_deskripsi",
		type: "POST",
		dataType: "JSON",
		data: {"tasks_id":tasks_id},
		beforeSend: function() 
		{    
			App.blockUI({
				target: el,
				animate: true,
				overlayColor: 'none'
			});
		},
		success: function(data)
		{				
			App.unblockUI(el);
			var cont = el;
			cont.html(data.deskripsi);
		},
		error: function (jqXHR, exception) {
			App.unblockUI(el);
			var msgerror = ''; 
			if (jqXHR.status === 0) {
				msgerror = 'jaringan tidak terkoneksi.';
			} else if (jqXHR.status == 404) {
				msgerror = 'Halamam tidak ditemukan. [404]';
			} else if (jqXHR.status == 500) {
				msgerror = 'Internal Server Error [500].';
			} else if (exception === 'parsererror') {
				msgerror = 'Requested JSON parse gagal.';
			} else if (exception === 'timeout') {
				msgerror = 'RTO.';
			} else if (exception === 'abort') {
				msgerror = 'Gagal request ajax.';
			} else {
				msgerror = 'Error.\n' + jqXHR.responseText;
			}
			toastr.error("Error System", msgerror, 'error');
		}		
	});
}


var loadFormUpdateTask = function() {
	$('#update_form_task')[0].reset(); // reset form on modals		  
	$('#modal_message').html('');  
	
	//Ajax Load data from ajax
	$.ajax({
		url : base_url+"beranda_task/data_form_update_task",
		type: "POST",
		dataType: "JSON",
		data: {"tasks_id":tasks_id},
		success: function(data)
		{	
															
			$('#modalFormUpdateTask').modal('show'); // show bootstrap modal
			$('.modal-title').text('Update Task'); // Set Title to Bootstrap modal title
			
			var item_sel=["filter_status"];
			var item_select = {"filter_status":data.status};															
			select_box(data,item_select, item_sel);	
			
			$('#modalFormUpdateTask [name="id"]').val(data.id);
			$('#modalFormUpdateTask [name="deskripsi"]').val(data.deskripsi);
			$('#modalFormUpdateTask [name="progress"]').val(data.progress);
			
			$("#modalFormUpdateTask #progress").ionRangeSlider({
				grid: true,
				min: 0,
				max: 100,
				values: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100]
			});
			
			//loadTabelFileAgenda(agenda_id);

		},
		error: function (jqXHR, exception) {
			var msgerror = ''; 
			if (jqXHR.status === 0) {
				msgerror = 'jaringan tidak terkoneksi.';
			} else if (jqXHR.status == 404) {
				msgerror = 'Halamam tidak ditemukan. [404]';
			} else if (jqXHR.status == 500) {
				msgerror = 'Internal Server Error [500].';
			} else if (exception === 'parsererror') {
				msgerror = 'Requested JSON parse gagal.';
			} else if (exception === 'timeout') {
				msgerror = 'RTO.';
			} else if (exception === 'abort') {
				msgerror = 'Gagal request ajax.';
			} else {
				msgerror = 'Error.\n' + jqXHR.responseText;
			}
			toastr.error("Error System", msgerror, 'error');
		}		
	});					
}

function updateTask() {
	var form = document.getElementById('update_form_task');					  
	var form_data = new FormData(form);	
			
	// ajax adding data to database
	$.ajax({
		url : base_url+"beranda_task/data_update_task",
		type: "POST",
		data: form_data,
		processData: false,
		contentType: false,
		dataType: "JSON",
		success: function(data)
		{
			if(data.status=='success'){
				toastr.success(data.message);
				loadDesTask();
				$('#modalFormUpdateTask').modal('hide'); // show bootstrap modal
			} else if (data.status=='warning'){
				toastr.warning(data.message);
			} else {
				toastr.error(data.message);
			}			   					  
		},
		error: function (jqXHR, exception) {
			var msgerror = ''; 
			if (jqXHR.status === 0) {
				msgerror = 'jaringan tidak terkoneksi.';
			} else if (jqXHR.status == 404) {
				msgerror = 'Halamam tidak ditemukan. [404]';
			} else if (jqXHR.status == 500) {
				msgerror = 'Internal Server Error [500].';
			} else if (exception === 'parsererror') {
				msgerror = 'Requested JSON parse gagal.';
			} else if (exception === 'timeout') {
				msgerror = 'RTO.';
			} else if (exception === 'abort') {
				msgerror = 'Gagal request ajax.';
			} else {
				msgerror = 'Error.\n' + jqXHR.responseText;
			}
			toastr.error("Error System", msgerror, 'error');
		}		
	});
}

var loadTabelFileTask = function() {
	//load data table																
	$('#table_file_report_task').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		"ordering": false,
		"bAutoWidth": false,
		"paging": true,
		
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": base_url+"beranda_task/data_list_file_report_task",
			"type": "POST",						
			"data": {"tasks_id":tasks_id},
		},
		"destroy": true,
		//Set column definition initialisation properties.
		"columnDefs": [
			{ 
			  "targets": [ -1 ], //last column
			  "searchable": false,
			  "orderable": false, //set not orderable
			},
			{
				"className": "dt-center", 
				"targets": [0,3]
			},
			{
			  "targets": [ 2 ], // your case first column
			  "className": "text-center",
			},
			{
			  "width": "15%",
			  "targets": [2,3,4],
			},
		],

	});//end load data table
	
	//load data table																
	$('#table_file_other_task').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		"ordering": false,
		"bAutoWidth": false,
		"paging": true,
		
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": base_url+"beranda_task/data_list_file_other_task",
			"type": "POST",						
			"data": {"tasks_id":tasks_id},
		},
		"destroy": true,
		//Set column definition initialisation properties.
		"columnDefs": [
			{ 
			  "targets": [ -1 ], //last column
			  "searchable": false,
			  "orderable": false, //set not orderable
			},
			{
				"className": "dt-center", 
				"targets": [0,2,3]
			},
			{
			  "targets": [ 2 ], // your case first column
			  "className": "text-center",
			},
			{
			  "width": "8%",
			  "targets": [2,3],
			},
		],

	});//end load data table
}

var loadComments = function() {
	var el = $('#task_comments');		
	$.ajax({
		url : base_url+"beranda_task/data_task_comments",
		type: "POST",
		dataType: "JSON",
		data: {"tasks_id":tasks_id},
		beforeSend: function() 
		{    
			App.blockUI({
				target: el,
				animate: true,
				overlayColor: 'none'
			});
		},
		success: function(data)
		{				
			App.unblockUI(el);
			var cont = el;
			cont.html(data.comments);
			
			var div_height = document.getElementById('task_comments').offsetHeight;
			div_height = div_height>400?400:div_height;
			
			scroller(cont,div_height,'bottom');
			
			$("#btn_add_file_comment").click(function(){
				var type_file = [];
				$.ajax({
					url : base_url+"beranda_task/data_add_file_comment",
					type: "POST",
					dataType: "JSON",
					data: {tasks_id:tasks_id},
					success: function(data)
					{
						var item_sel=["filter_jenis_file_task"];
						var item_select = {"filter_jenis_file_task":-1};															
						select_box(data,item_select, item_sel);	
						type_file = data.type_file;
						filter_jenis_file_task = data.filter_jenis_file_task;
						$("#form_add_file_comment #task_file_support").html(type_file[data.filter_jenis_file_task[0]['id_item']]);
					},	
				});					
				$("#form_add_file_comment").toggle("slow");
				$('#filter_jenis_file_task').change(function(){
					$("#form_add_file_comment #task_file_support").html(type_file[$(this).val()]);
				})
				
			});
			
			$('.reply-toggle').each(function(){
				$(this).click(function(){
					var cont_reply = $(this).parents('.media').find('.media-list');
					param_height = div_height;
					if(cont_reply.is(':visible')==false) {
						param_height = param_height+160;
						param_height = param_height>400?400:param_height;
					}
					cont_reply.toggle('slow');
					scroller(cont,param_height,'bottom');
				});
			});
		},
		error: function (jqXHR, exception) {
			App.unblockUI(el);
			var msgerror = ''; 
			if (jqXHR.status === 0) {
				msgerror = 'jaringan tidak terkoneksi.';
			} else if (jqXHR.status == 404) {
				msgerror = 'Halamam tidak ditemukan. [404]';
			} else if (jqXHR.status == 500) {
				msgerror = 'Internal Server Error [500].';
			} else if (exception === 'parsererror') {
				msgerror = 'Requested JSON parse gagal.';
			} else if (exception === 'timeout') {
				msgerror = 'RTO.';
			} else if (exception === 'abort') {
				msgerror = 'Gagal request ajax.';
			} else {
				msgerror = 'Error.\n' + jqXHR.responseText;
			}
			toastr.error("Error System", msgerror, 'error');
		}		
	});
}

function addComments() {
	var form = document.getElementById('add_comment');					  
	var form_data = new FormData(form);	
	// var fileInput = document.getElementById('filename');
	// var file = fileInput.files[0];					
	//form_data.append("filename", file);
	form_data.append("tasks_id", tasks_id);
			
	// ajax adding data to database
	$.ajax({
		url : base_url+"beranda_task/data_add_comment",
		type: "POST",
		data: form_data,
		processData: false,
		contentType: false,
		dataType: "JSON",
		success: function(data)
		{
			if(data.status=='success'){
				toastr.success(data.message);
				$('#add_comment')[0].reset();
				if($("#form_add_file_comment").is(':visible')){
					$("#form_add_file_comment").toggle("slow");
				}
				loadComments();
				// $('#task_comments').append(data.comment);	
				// $('#task_comments li.media:last-child .reply-toggle').each(function(){
					// $(this).click(function(){
						// var cont = $('#task_comments');
						// var div_height = $('#task_comments').scrollHeight;
						// div_height = div_height>400?400:div_height;
						// var cont_reply = $(this).parents('.media').find('.media-list');
						// param_height = div_height;
						// if(cont_reply.is(':visible')==false) {
							// param_height = param_height+160;
							// param_height = param_height>400?400:param_height;
						// }
						// cont_reply.toggle('slow');
						// scroller(cont,param_height,'bottom');
					// });
				// });
				loadTabelFileTask();
			} else if (data.status=='warning'){
				toastr.warning(data.message);
			} else {
				toastr.error(data.message);
			}			   					  
		},
		error: function (jqXHR, exception) {
			var msgerror = ''; 
			if (jqXHR.status === 0) {
				msgerror = 'jaringan tidak terkoneksi.';
			} else if (jqXHR.status == 404) {
				msgerror = 'Halamam tidak ditemukan. [404]';
			} else if (jqXHR.status == 500) {
				msgerror = 'Internal Server Error [500].';
			} else if (exception === 'parsererror') {
				msgerror = 'Requested JSON parse gagal.';
			} else if (exception === 'timeout') {
				msgerror = 'RTO.';
			} else if (exception === 'abort') {
				msgerror = 'Gagal request ajax.';
			} else {
				msgerror = 'Error.\n' + jqXHR.responseText;
			}
			toastr.error("Error System", msgerror, 'error');
		}		
	});
}

function replyComments(id, tasks_id) {
	var form = document.getElementById('reply_comment'+id);					  
	var form_data = new FormData(form);	
	form_data.append("parent_id", id);
	form_data.append("tasks_id", tasks_id);
			
	// ajax adding data to database
	$.ajax({
		url : base_url+"beranda_task/data_reply_comment",
		type: "POST",
		data: form_data,
		processData: false,
		contentType: false,
		dataType: "JSON",
		success: function(data)
		{
			if(data.status=='success'){
				toastr.success(data.message);
				$('#reply_comment'+id)[0].reset();
				// if($("#form_add_file_comment").is(':visible')){
					// $("#form_add_file_comment").toggle("slow");
				// }
				loadComments();
			} else if (data.status=='warning'){
				toastr.warning(data.message);
			} else {
				toastr.error(data.message);
			}			   					  
		},
		error: function (jqXHR, exception) {
			var msgerror = ''; 
			if (jqXHR.status === 0) {
				msgerror = 'jaringan tidak terkoneksi.';
			} else if (jqXHR.status == 404) {
				msgerror = 'Halamam tidak ditemukan. [404]';
			} else if (jqXHR.status == 500) {
				msgerror = 'Internal Server Error [500].';
			} else if (exception === 'parsererror') {
				msgerror = 'Requested JSON parse gagal.';
			} else if (exception === 'timeout') {
				msgerror = 'RTO.';
			} else if (exception === 'abort') {
				msgerror = 'Gagal request ajax.';
			} else {
				msgerror = 'Error.\n' + jqXHR.responseText;
			}
			toastr.error("Error System", msgerror, 'error');
		}		
	});
}

function show_laporan(file_id)
{				 				
	var form_data = {
		file_id: file_id					
	};
		
	$.ajax({
		url : base_url+"beranda_task/show_laporan",
		type: "POST",
		dataType: "JSON",
		data: form_data,
		success: function(data)
		{	
			$('#judul_laporan').html('<b>' + data['note_title'] + '</b>');					    														
			pdf_file = 	data['filename_path'];																						
			document.getElementById('pdf_frame_laporan').src = data['filename_url'];																																					
			$('#modalLaporan').modal('show'); // show bootstrap modal when complete loaded																			   				   					  
		},
		error: function (jqXHR, textStatus, errorThrown)
		{						
			alert('Error adding / update data');									
		}
	});									
}

function show_deskripsi(file_id,user_id,nama_approval)
{				 				
	var form_data = {
		file_id: file_id,			
		user_id: user_id					
	};
		
	$.ajax({
		url : base_url+"beranda_task/show_deskripsi",
		type: "POST",
		dataType: "JSON",
		data: form_data,
		success: function(data)
		{	
			$('#modalStatus #approval').html('<b>' + nama_approval + '</b>');					    														
			$('#modalStatus #status').html(data['status']);					    														
			$('#modalStatus #keterangan').html(data['keterangan']);																																					
			$('#modalStatus').modal('show'); // show bootstrap modal when complete loaded																			   				   					  
		},
		error: function (jqXHR, textStatus, errorThrown)
		{						
			alert('Error adding / update data');									
		}
	});									
}

function approval_laporan(id, nama, judul, file_id, jenis_approve)
{
	if(id != ''){
		//show modal confirmation
		$('#approve_form')[0].reset(); // reset form on modals
		$('#modal_approval_message').html('');  //reset message
		
		$('[name="task_file_id"]').val(id);
		$('#approval_text').html('<b >Laporan dari ' + nama + '</b><br />Dengan judul: <br />' + judul);
		
		var form_data = {
			file_id: file_id					
		};
		
		$.ajax({
				url : base_url+"beranda_task/show_laporan",
				type: "POST",
				dataType: "JSON",
				data: form_data,
				success: function(data)
				{	
					pdf_file = 	data['filename_path'];																						
					document.getElementById('pdf_frame').src = data['filename_url'];																										
					$('#modalApproval').modal('show'); // show bootstrap modal when complete loaded		
					$("#komentar").val(""); 
					var editor = $("#komentar").wysihtml5();
				},
				error: function (jqXHR, exception) {
					var msgerror = ''; 
					if (jqXHR.status === 0) {
						msgerror = 'jaringan tidak terkoneksi.';
					} else if (jqXHR.status == 404) {
						msgerror = 'Halamam tidak ditemukan. [404]';
					} else if (jqXHR.status == 500) {
						msgerror = 'Internal Server Error [500].';
					} else if (exception === 'parsererror') {
						msgerror = 'Requested JSON parse gagal.';
					} else if (exception === 'timeout') {
						msgerror = 'RTO.';
					} else if (exception === 'abort') {
						msgerror = 'Gagal request ajax.';
					} else {
						msgerror = 'Error.\n' + jqXHR.responseText;
					}
					toastr.error("Error System", msgerror, 'error');
				}		
			});																							
	}else{		
		var form = document.getElementById('approve_form');					  
		var form_data = new FormData(form);	
		form_data.append("jenis_approve", jenis_approve);
		
		// ajax hapus data to database
		$.ajax({
			url : base_url+"beranda_task/approval_laporan",
			type: "POST",
			data: form_data,
			processData: false,
			contentType: false,
			dataType: "JSON",
			success: function(data)
			{
				if(data.status=='success'){
					$('#modalApproval').modal('hide');	
					$('#table_file_report_task').DataTable().ajax.reload();
					toastr.success(data.message);
				} else {
					toastr.error(data.message);
				}						   					  
			},
			error: function (jqXHR, exception) {
				var msgerror = ''; 
				if (jqXHR.status === 0) {
					msgerror = 'jaringan tidak terkoneksi.';
				} else if (jqXHR.status == 404) {
					msgerror = 'Halamam tidak ditemukan. [404]';
				} else if (jqXHR.status == 500) {
					msgerror = 'Internal Server Error [500].';
				} else if (exception === 'parsererror') {
					msgerror = 'Requested JSON parse gagal.';
				} else if (exception === 'timeout') {
					msgerror = 'RTO.';
				} else if (exception === 'abort') {
					msgerror = 'Gagal request ajax.';
				} else {
					msgerror = 'Error.\n' + jqXHR.responseText;
				}
				toastr.error("Error System", msgerror, 'error');
			}		
		});					
	}
}	

var data_delete = function (id, nama_id){
	if(id != ''){
		//show modal confirmation
		$('#delete_form')[0].reset(); // reset form on modals
		$('#modal_delete_message').html('');  //reset message
		
		$('[name="id_delete_data"]').val(id);
		$('#delete_text').html('<b >Hapus data ' + nama_id + '</b>');	
		$('#modalDeleteForm').modal('show'); // show bootstrap modal when complete loaded
		$('.modal-title').text('Hapus Data'); // Set Title to Bootstrap modal title	
	}else{
		//lakukan hapus data
		// ajax hapus data to database
		$.ajax({
			url: base_url+"beranda_task/data_delete",
			type: "POST",
			data: $('#delete_form').serialize(),
			dataType: "JSON",
			success: function(data)
			{
				if(data.status=='success'){
					toastr.success(data.message);
					$('#modalDeleteForm').modal('hide');		
					loadTabelFileTask();
				} else {
					toastr.error(data.message);
				}			
			},
			error: function (jqXHR, exception) {
				var msgerror = ''; 
				if (jqXHR.status === 0) {
					msgerror = 'jaringan tidak terkoneksi.';
				} else if (jqXHR.status == 404) {
					msgerror = 'Halamam tidak ditemukan. [404]';
				} else if (jqXHR.status == 500) {
					msgerror = 'Internal Server Error [500].';
				} else if (exception === 'parsererror') {
					msgerror = 'Requested JSON parse gagal.';
				} else if (exception === 'timeout') {
					msgerror = 'RTO.';
				} else if (exception === 'abort') {
					msgerror = 'Gagal request ajax.';
				} else {
					msgerror = 'Error.\n' + jqXHR.responseText;
				}
				toastr.error("Error System", msgerror, 'error');
			}		
		});					
	}				
}	

function scroller(cont,height,start) {
	cont.slimScroll({
		start : (start ? start : 'bottom'),
		allowPageScroll: true, // allow page scroll when the element scroll is ended
		size: '7px',
		color: '#bbb',
		wrapperClass: 'slimScrollDiv',
		railColor: '#ffffff',
		height: height,
		alwaysVisible: "1",
		railVisible: "1",
		disableFadeOut: true
	});
}
	
 jQuery(document).ready(function() {
    loadDesTask();
    loadTabelFileTask();
    loadComments();
});