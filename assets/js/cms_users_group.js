var save_method; //for save method string
var table;

$(document).ready(function() {	
	load_select_filter();
	
	$('#filter_user_group').on('change',function(){					
		reload_table_group_personil();
	});		
		
	//load data table																
	table = $('#table').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": base_url+'cms_users_group/data_list',
			"type": "POST"
		},

		//Set column definition initialisation properties.
		"columnDefs": [
			{ 
			  "targets": [ -1 ], //last column
			  "searchable": false,
			  "orderable": false, //set not orderable
			},
		],

	});//end load data table
	
	table_group_personil = $('#table_group_personil').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": base_url+'cms_users_group/data_list_group_personil',
			"type": "POST",						
			"data": function ( d ) {
					var item_selectbox = document.getElementById('filter_user_group');
					d.filter_user_group = item_selectbox.options[item_selectbox.selectedIndex].value;								
				}
		},

		//Set column definition initialisation properties.
		"columnDefs": [
			{ 
			  "targets": [ -1 ], //last column
			  "searchable": false,
			  "orderable": false, //set not orderable
			},
		],

	});//end load data table
					
	table_personil = $('#table_personil').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		
		
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": base_url+'cms_users_group/data_list_personil',
			"type": "POST",
			"data":function ( d ) {
					var item_tab = document.getElementById('id_user_groups_personil');
					d.id_user_groups = item_tab.value;
				}
		},

		//Set column definition initialisation properties.
		"columnDefs": [
			{ 
			  "targets": [ -1 ], //last column
			  "searchable": false,
			  "orderable": false, //set not orderable
			},
		],

	});//end load data table
	
	//load data table														
	table_menu = $('#table_menus').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.												
		"deferLoading": 0, // here	
		"paging": false,						
		"ordering": false,
		"bFilter": false,
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": base_url+'cms_users_group/menu_list/',
			"type": "POST",
			"data":function ( d ) {
					var item_tab = document.getElementById('id_user_groups');
					d.id_user_groups = item_tab.value;			//document.getElementById('id_tabs').value					
				}										
		},
		
		//Set column definition initialisation properties.
		"columnDefs": [
			{ 
			  "targets": [ -1 ], //last column
			  "searchable": false,
			  "orderable": false, //set not orderable
			},
		],							
	});//end load data table
	
});	

 //function create lecect box
function select_box(data,item_select,item_sel)
{					
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

//function load select filter
function load_select_filter(){
	 $.ajax({
			url : base_url+'cms_users_group/data_init/',
			type: "POST",
			dataType: "JSON",
			success: function(data)
			{
				var item_sel=["filter_user_group"];
				var item_select = {"filter_user_group":-1};															
				select_box(data,item_select, item_sel);			
				reload_table_group_personil();																	
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				alert('Error get data from ajax');
			}
		});	
}															

function data_add()
{
  save_method = 'add';
  $('#add_form')[0].reset(); // reset form on modals		  
  $('#modal_message').html('');  //reset message	
  $('#modalAddForm').modal('show'); // show bootstrap modal
  $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title					
}

function data_edit(id)
{
  save_method = 'update';
  $('#add_form')[0].reset(); // reset form on modals
  $('#modal_message').html('');  //reset message	
			
  //Ajax Load data from ajax
  $.ajax({			  		
		url : base_url+'cms_users_group/data_edit/' + id,
		type: "GET",
		dataType: "JSON",
		success: function(data)
		{				   
			$('[name="id"]').val(data['list'].id);  
			$('[name="name"]').val(data['list'].name);
			$('[name="description"]').val(data['list'].description);																	
					
			$('#modalAddForm').modal('show'); // show bootstrap modal when complete loaded
			$('.modal-title').text('Edit Data'); // Set title to Bootstrap modal title					
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			alert('Error get data from ajax');
		}
	});
}

function reload_table()
{
  table.ajax.reload(null,false); //reload datatable ajax 
}
			
function reload_table_group_personil()
{
  table_group_personil.ajax.reload(null,false); //reload datatable ajax 
}

function reload_table_personil()
{
  table_personil.ajax.reload(null,false); //reload datatable ajax 
}

function data_save()
{
  var url;
  if(save_method == 'add') 
  {
	  url = base_url+'cms_users_group/data_save_add';
  }
  else
  {
	url = base_url+'cms_users_group/data_save_edit';
  }

   // ajax adding data to database
	  $.ajax({
		url : url,
		type: "POST",
		data: $('#add_form').serialize(),
		dataType: "JSON",
		success: function(data)
		{
		   //if success close modal and reload ajax table
		   if(data['status'] == true){
				//berhasil simpan							
				 $('#modalAddForm').modal('hide');					   		
				 $('#page_message').html('<div class="alert alert-info">Data berhasil di simpan.</div>');
				reload_table();
		   }else{
				//form validation
				$('#modal_message').html('<div class="alert alert-info">' + data['status'] + '</div>');							
		   }					   					  
		},
		error: function (jqXHR, textStatus, errorThrown)
		{						
			alert('Error adding / update data');									
		}
	});
}

function data_delete(id, nama_id)
{
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
			url : base_url+'cms_users_group/data_delete',
			type: "POST",
			data: $('#delete_form').serialize(),
			dataType: "JSON",
			success: function(data)
			{
			   //if success close modal and reload ajax table
			   if(data['status'] == true){
					//berhasil simpan							
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
	}				
}		

function reload_table_menu()
{
  table_menu.ajax.reload(null,false); //reload datatable ajax 
} 			

function groups_menu(groups_id, groups_name)
{
	document.getElementById('id_user_groups').value = groups_id;
	reload_table_menu(); 
	$('#modalMenus').modal('show'); // show bootstrap modal when complete loaded
	$('.modal-title2').text('Menu '+groups_name); // Set Title to Bootstrap modal title					
}		
			
function data_edit_status(menu_id, groups_menu_id)
{
	var form_data = {
			id_user_groups: document.getElementById('id_user_groups').value,
			id_menu: menu_id,
			id_groups_menu: groups_menu_id					
		};
		
	$.ajax({
			url : base_url+'cms_users_group/data_edit_status',
			type: "POST",
			dataType: "JSON",
			data: form_data,
			success: function(data)
			{
			   //if success close modal and reload ajax table
			   if(data['status'] == true){
					//berhasil simpan							
					 $('#modal_menu_message').html('<div class="alert alert-info">Berhasil ubah status.</div>');
					reload_table_menu();
			   }else{
					$('#modal_menu_message').html('<div class="alert alert-info">Gagal ubah status.</div>');						
			   }					   					  
			},
			error: function (jqXHR, textStatus, errorThrown)
			{						
				alert('Error adding / update data');									
			}
		});	
}							
			
function data_modal_personil()
{							  
	var item_selectbox = document.getElementById('filter_user_group');								
	document.getElementById('id_user_groups_personil').value = item_selectbox.options[item_selectbox.selectedIndex].value;
	//reload_table_personil(); 
	$('#modalPersonil').modal('show'); // show bootstrap modal when complete loaded
}
			
function data_add_personil(user_id, user_groups_id)
{				
	var form_data = {
			user_id: user_id,
			user_groups_id: user_groups_id					
		};
		
	$.ajax({
			url : base_url+'cms_users_group/data_tambah_personil_group',
			type: "POST",
			dataType: "JSON",
			data: form_data,
			success: function(data)
			{
			   //if success close modal and reload ajax table
			   if(data['status'] == true){
					//berhasil simpan							
					 //$('#modal_menu_message').html('<div class="alert alert-info">Berhasil ubah status.</div>');
					 $('#modalPersonil').modal('hide');									 
					reload_table_group_personil();
			   }else{
					//$('#modal_menu_message').html('<div class="alert alert-info">Gagal ubah status.</div>');						
			   }					   					  
			},
			error: function (jqXHR, textStatus, errorThrown)
			{						
				alert('Error adding / update data');									
			}
		});	
}	
			
function data_delete_personil(id, nama_id)
{				
	if(id != ''){
		//show modal confirmation
		$('#delete_form_personil')[0].reset(); // reset form on modals
		$('#modal_delete_message_personil').html('');  //reset message
		
		$('[name="id_delete_data_personil"]').val(id);
		$('#delete_text_personil').html('<b >Hapus data ' + nama_id + '</b>');	
		$('#modalDeletePersonil').modal('show'); // show bootstrap modal when complete loaded
		//$('.modal-title').text('Hapus Data'); // Set Title to Bootstrap modal title	
	}else{					
		//lakukan hapus data
		// ajax hapus data to database
		$.ajax({
			url : base_url+'cms_users_group/data_delete_personil',
			type: "POST",
			data: $('#delete_form_personil').serialize(),
			dataType: "JSON",
			success: function(data)
			{
			   //if success close modal and reload ajax table
			   if(data['status'] == true){
					//berhasil simpan							
					 $('#modalDeletePersonil').modal('hide');					   		
					 $('#page_message').html('<div class="alert alert-info">Data berhasil di hapus.</div>');
					reload_table_group_personil();
			   }else{
					//form validation
					$('#modal_delete_message_personil').html('<div class="alert alert-info">' + data['status'] + '</div>');							
			   }					   					  
			},
			error: function (jqXHR, textStatus, errorThrown)
			{						
				alert('Error adding / update data');									
			}
		});					
	}				
}		