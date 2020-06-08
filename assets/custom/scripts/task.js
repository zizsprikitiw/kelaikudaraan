var oTable;

var reloadTable = function() {	
	oTable.ajax.reload(null,false); //reload datatable ajax 
}

var data_search = function() {							
	oTable.ajax.reload(null,false); //reload datatable ajax 
}

var loadSelectFilter = function() {
	 $.ajax({
		url : "task/data_init",
		type: "POST",
		dataType: "JSON",
		success: function(data)
		{
			var item_sel=["filter_modul"];
			var item_select = {"filter_modul":-1};															
			select_box(data,item_select, item_sel);			
			loadTable();																	
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			alert('Error get data from ajax');
		}
	});	
}

var loadTable = function() {
	//load data table														
	oTable = $('#table').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		"buttons": [
			{ extend: 'print', className: 'btn dark btn-outline' },
			{ extend: 'copy', className: 'btn red btn-outline' },
			{ extend: 'pdf', className: 'btn green btn-outline' },
			{ extend: 'excel', className: 'btn yellow btn-outline ' },
			{ extend: 'csv', className: 'btn purple btn-outline ' },
			{ extend: 'reload', className: 'btn dark btn-outline' }
		],
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": "task/data_list",
			"type": "POST",
			"data": function ( d ) {
				var chkSearch = [];
				$.each($("input[name='chkSearch[]']:checked"), function(){
					chkSearch.push($(this).val());
				});
				
				d.chkSearch = chkSearch;
				var item_selectbox = document.getElementById('filter_modul');
				d.filter_modul = item_selectbox.options[item_selectbox.selectedIndex].value;																																
			}							
		},
		"columnDefs": [
			{
				"className": "dt-head-center", 
				"targets": [1,2,-1]
			},
			{
				"className": "dt-center", 
				"targets": [0,3]
			},
		],
		"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
			SweetAlert.init();

		},
	});//end load data table	
	
	// handle datatable custom tools
	$('#table_tools > li > a.tool-action').on('click', function() {
		var action = $(this).attr('data-action');
		oTable.button(action).trigger();
	});
	
	// handle datatable custom reload tools
	$.fn.dataTable.ext.buttons.reload = {
		text: 'Reload',
		action: function ( e, dt, node, config ) {
			dt.ajax.reload();
		}
	};
}
	
var loadFormAdd = function() {
	$('#add_form')[0].reset(); // reset form on modals		  
	$('#modal_message').html('');  //reset message		
	save_method = 'add';
	$('#modalAddForm [name="save_method"]').val(save_method); 
  
	//Ajax Load data from ajax
	$.ajax({
		url : "task/data_form_add" ,
		type: "POST",
		dataType: "JSON",
		success: function(data)
		{
			var item_sel=["filter_program_form"];
			var item_select = {"filter_program_form":-1};															
			select_box(data,item_select, item_sel);	
			
			$('#modalAddForm').modal('show'); // show bootstrap modal
			$('.modal-title').text('Tambah Task'); // Set Title to Bootstrap modal title
			
			loadSelectFormAdd();
			$('#filter_program_form').change(function(){
				loadSelectFormAdd();
			});
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

var loadFormUpdate = function(id) {
	$('#add_form')[0].reset(); // reset form on modals		  
	$('#modal_message').html('');  //reset message		
	save_method = 'update';
	$('#modalAddForm [name="save_method"]').val(save_method); 
  
	//Ajax Load data from ajax
	$.ajax({
		url : "task/data_form_update" ,
		type: "POST",
		dataType: "JSON",
		data: {"tasks_id":id},
		success: function(data)
		{
			var item_sel=["filter_program_form"];
			var item_select = {"filter_program_form":data.list_task.proyek_id};															
			select_box(data,item_select, item_sel);	
			
			$('#modalAddForm').modal('show'); // show bootstrap modal
			$('.modal-title').text('Update Task'); // Set Title to Bootstrap modal title
			
			$('#modalAddForm [name="id"]').val(data.list_task.id);
			$('#modalAddForm [name="nama_task"]').val(data.list_task.nama_task);
			$('#modalAddForm [name="deskripsi"]').val(data.list_task.deskripsi);
			$('#modalAddForm [name="start_date"]').val(data.list_task.start_date);
			$('#modalAddForm [name="due_date"]').val(data.list_task.due_date);
			
			loadSelectFormUpdate(data.list_task);
			$('#filter_program_form').change(function(){
				loadSelectFormAdd();
			});
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
	
//function load select modul
var loadSelectFormAdd = function() {
	var e = document.getElementById("filter_program_form");
	var proyek_selected = e.options[e.selectedIndex].value;
	
	$.ajax({
		url : base_url+"task/data_select_form_add" ,
		type: "POST",
		dataType: "JSON",
		data: {"proyek_id":proyek_selected},
		success: function(data)
		{				
			var item_sel=["filter_modul_form"];
			var item_select = {"filter_modul_form":-1};															
			select_box(data,item_select, item_sel);		
			
			var item_sel=["filter_pic","filter_approval","filter_member"];
			var item_select = {"filter_pic":[-1],"filter_approval":[-1],"filter_member":[-1]};															
			select_box_group(data,item_select, item_sel);	
			
			$('#modalAddForm .mt-multiselect').multiselect('destroy');
			$('#modalAddForm .mt-multiselect').multiselect({
				enableClickableOptGroups: true,
				enableCollapsibleOptGroups: true,
				collapseOptGroupsByDefault: true,
				enableFiltering: true,
				includeSelectAllOption: true,
				maxHeight: 200,
				buttonWidth: '100%',
			});				
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
	
//function load select modul
var loadSelectFormUpdate = function(list_task) {
	var e = document.getElementById("filter_program_form");
	var proyek_selected = e.options[e.selectedIndex].value;
	
	$.ajax({
		url : base_url+"task/data_select_form_add" ,
		type: "POST",
		dataType: "JSON",
		data: {"proyek_id":proyek_selected},
		success: function(data)
		{				
			var item_sel=["filter_modul_form"];
			var item_select = {"filter_modul_form":list_task.modul_id};															
			select_box(data,item_select, item_sel);		
			
			var item_sel=["filter_pic","filter_approval","filter_member"];
			var item_select = {"filter_pic":[list_task.posisi_pic_id],"filter_approval":[list_task.posisi_approval_id],"filter_member":[list_task.id_members]};															
			select_box_group(data,item_select, item_sel);	
			
			$('#modalAddForm .mt-multiselect').multiselect('destroy');
			$('#modalAddForm .mt-multiselect').multiselect({
				enableClickableOptGroups: true,
				enableCollapsibleOptGroups: true,
				collapseOptGroupsByDefault: true,
				enableFiltering: true,
				includeSelectAllOption: true,
				maxHeight: 200,
				buttonWidth: '100%'
			});				
			
			$("#filter_pic").val(list_task.posisi_pic_id);
			$("#filter_approval").val(list_task.posisi_approval_id);
			$("#filter_member").val(list_task.id_members);
			$("#filter_pic,#filter_approval,#filter_member").multiselect("refresh");
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

function saveTask() {
	var form = document.getElementById('add_form');					  
	var form_data = new FormData(form);	
			
	// ajax adding data to database
	$.ajax({
		url : base_url+"task/data_save",
		type: "POST",
		data: form_data,
		processData: false,
		contentType: false,
		dataType: "JSON",
		success: function(data)
		{
			if(data.status=='success'){
				//saved data																
				$('#modalAddForm').modal('hide');	
				reloadTable();
				toastr.success(data.message);
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
 
var data_edit_status = function(id) {
	$.ajax({
		url : "cms_menu/data_edit_status/"+id,
		type: "POST",
		dataType: "JSON",
		success: function(data)
		{
		   //if success close modal and reload ajax table
		   if(data['status'] == true){
				//berhasil simpan							
				toastr.success('Berhasil update status menu');
				reloadTable();
		   }else{
				toastr.error('Gagal update status menu');						
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

var data_edit_posisi = function (id, pos) {
	var form_data = {
		id: id,
		pos: pos					
	};
		
	$.ajax({
		url : "cms_menu/data_edit_posisi",
		type: "POST",
		dataType: "JSON",
		data: form_data,
		success: function(data)
		{		
			//if success close modal and reload ajax table
			if(data['status'] == true){
				//berhasil simpan							
				toastr.success('Berhasil ubah posisi');
				reloadTable();
			}else{
				toastr.error('Gagal ubah posisi');						
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

var data_delete = function (id, nama_id){
	$('.mt-sweetalert').each(function(){
		var sa_title = $(this).data('title');
		var sa_message = $(this).data('message');
		var sa_type = $(this).data('type');	
		var sa_allowOutsideClick = $(this).data('allow-outside-click');
		var sa_showConfirmButton = $(this).data('show-confirm-button');
		var sa_showCancelButton = $(this).data('show-cancel-button');
		var sa_closeOnConfirm = $(this).data('close-on-confirm');
		var sa_closeOnCancel = $(this).data('close-on-cancel');
		var sa_confirmButtonText = $(this).data('confirm-button-text');
		var sa_cancelButtonText = $(this).data('cancel-button-text');
		var sa_popupTitleSuccess = $(this).data('popup-title-success');
		var sa_popupMessageSuccess = $(this).data('popup-message-success');
		var sa_popupTitleCancel = $(this).data('popup-title-cancel');
		var sa_popupMessageCancel = $(this).data('popup-message-cancel');
		var sa_confirmButtonClass = $(this).data('confirm-button-class');
		var sa_cancelButtonClass = $(this).data('cancel-button-class');
				
		$(this).click(function(){
			swal({
				title: sa_title+" "+nama_id,
				text: sa_message,
				type: sa_type,
				allowOutsideClick: sa_allowOutsideClick,
				showConfirmButton: sa_showConfirmButton,
				showCancelButton: sa_showCancelButton,
				confirmButtonClass: sa_confirmButtonClass,
				cancelButtonClass: sa_cancelButtonClass,
				closeOnConfirm: sa_closeOnConfirm,
				closeOnCancel: sa_closeOnCancel,
				confirmButtonText: sa_confirmButtonText,
				cancelButtonText: sa_cancelButtonText,
			},
			function(isConfirm){
				if (isConfirm){
					$.ajax({
						url : "cms_menu/data_delete",
						type: "POST",
						data: {id_delete_data:id},
						dataType: "JSON",
						success: function(data)
						{
							//if success close modal and reload ajax table
							if(data['status'] == true){
								//berhasil simpan							
								toastr.success('Data berhasil di hapus');
								reloadTable();
							}else{
								toastr.error(data['status']);						
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
			});
		});
	}); 
	/*if(id != ''){
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
			url : "<?php echo site_url('cms_menu/data_delete')?>/",
			type: "POST",
			data: $('#delete_form').serialize(),
			dataType: "JSON",
			success: function(data)
			{
			   //if success close modal and reload ajax table
			   if(data['status'] == true){
					//berhasil simpan
					//alert(data['row']);							
					 $('#modalDeleteForm').modal('hide');					   		
					 $('#page_message').html('<div class="alert alert-info">Data berhasil di hapus.</div>');
					reload_table();
			   }else{
					//form validation
					$('#modal_delete_message').html('<div class="alert alert-info">' + data['status'] + '</div>');							
			   }					   					  
			},
			error: function (jqXHR, textStatus, errorThrown)
			{						
				alert('Error adding / update data');									
			}
		});					
	}		*/		
}

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

var select_box_group = function(data,item_select,item_sel) {
	//insert select item				
	var len_item = item_sel.length;
	select_val = [-1];
	for(var i=0; i<len_item; i++){
		//get id selected
		for(var key in item_select){
			if(key == item_sel[i]){
				select_val = item_select[key];
			} 
		}
		
		var sel = $("#"+item_sel[i]);						
		sel.empty();	
		$.each(data[item_sel[i]], function(key, value){
			var group = $('<optgroup label="' + key + '" />');
			$.each(value, function(){
				if(select_val.includes(parseInt(this.id_item)) === true){
					selected_str = 'selected="selected"';
				}else{
					selected_str = '';
				}
				
				$('<option value="'+this.id_item+'"  '+selected_str+'>'+this.nama_item+'</option>').appendTo(group);
			});
			group.appendTo(sel);
		});
	}
}	
			
 jQuery(document).ready(function() {
    loadSelectFilter();
});
			