var save_method; //for save method string
var table;

$(document).ready(function() {	
	//Ajax Load data tahun dan pusat
	load_select_filter();
					
	$('#filter_approval').on('change',function(){					
		reload_table();
	});
										
	//load data table														
	table = $('#table').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		"deferLoading": 0, // here	
		"paging": false,						
		"ordering": false,
		"bFilter": false,
		"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			if ( aData[5] == "info" )
			{
				$('td', nRow).css('background-color', '#fcfcfc');
			}
			else if ( aData[5] == "default" )
			{
				$('td', nRow).css('background-color', '#d9edf7');
			}
		},
		
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": base_url+'cms_approval/data_list',
			"type": "POST",						
			"data": function ( d ) {
					var item_selectbox = document.getElementById('filter_approval');
					d.approval_type_id = item_selectbox.options[item_selectbox.selectedIndex].value;								
				}
		},

		//Set column definition initialisation properties.
		"columnDefs": [
			{ 
			  "targets": [ 4 ], //last column
			  "searchable": false,
			  "orderable": false, //set not orderable
			},
			{ 
			  "targets": [ 5 ], //last column
			  "searchable": false,
			  "orderable": false, //set not orderable
			  "visible": false
			}
		],
		"rowsGroup": [// Always the array (!) of the column-selectors in specified order to which rows groupping is applied
					// (column-selector could be any of specified in https://datatables.net/reference/type/column-selector)
			1
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
			url : base_url+'cms_approval/data_init/',
			type: "POST",
			dataType: "JSON",
			success: function(data)
			{
				var item_sel=["filter_approval"];
				var item_select = {"filter_approval":-1};															
				select_box(data,item_select, item_sel);			
				reload_table();																	
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

function disableSelectBox(opsi, status)
{
	document.getElementById('select_struktural_'+opsi).disabled = status;
	document.getElementById('select_posisi_'+opsi).disabled = status;
	document.getElementById('select_fungsional_'+opsi).disabled = status;
	document.getElementById('select_group_'+opsi).disabled = status;				
}

function optRadio_Click(name, opsi)
{
	var category = document.getElementsByName(name);
	var check1 = 0;
		for(i=0;i<category.length;i++){
			if(category[i].checked){
				disableSelectBox(opsi, true);
				var nilai = category[i].value;
				document.getElementById('select_'+nilai+'_'+opsi).disabled = false;				
				break;
		}
	}	
}

function data_add()
{			  				  
  save_method = 'add';
  $('#add_form')[0].reset(); // reset form on modals		  
  $('#modal_message').html('');  //reset message
			
  //Ajax Load data from ajax
  $.ajax({			  		
		url : base_url+'cms_approval/data_add/',
		type: "POST",
		dataType: "JSON",
		success: function(data)
		{				   
			//pengirim
			var item_sel=["select_struktural_out","select_posisi_out","select_fungsional_out","select_group_out"];
			var item_select = {"select_struktural_out":-1,"select_posisi_out":-1,"select_fungsional_out":-1,"select_group_out":-1};															
			select_box(data,item_select, item_sel);
			//penerima
			var item_sel=["select_struktural_in","select_posisi_in","select_fungsional_in","select_group_in"];
			var item_select = {"select_struktural_in":-1,"select_posisi_in":-1,"select_fungsional_in":-1,"select_group_in":-1};															
			select_box(data,item_select, item_sel);																			
			
			disableSelectBox('out', true);						
			document.getElementById('option_struktural_out').checked = true;
			document.getElementById('select_struktural_out').disabled = false;	
			
			disableSelectBox('in', true);						
			document.getElementById('option_struktural_in').checked = true;
			document.getElementById('select_struktural_in').disabled = false;	
					
			$('#modalAddForm').modal('show'); // show bootstrap modal
			$('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title						
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			alert('Error get data from ajax');
		}
	});				
}

function data_edit(id)
{
  save_method = 'update';
  $('#add_form')[0].reset(); // reset form on modals
  $('#modal_message').html('');  //reset message	
			
  //Ajax Load data from ajax
  $.ajax({			  		
		url : base_url+'cms_approval/data_edit/' + id,
		type: "GET",
		dataType: "JSON",
		success: function(data)
		{				   
			//pengirim
			$('[name="id"]').val(data['list'].id);  
			$('[name="wait_before"]').val(data['list'].wait_before);
			
			var item_sel=["select_struktural_out","select_posisi_out","select_fungsional_out","select_group_out"];
			var item_select = {"select_struktural_out":data['list'].out_struktural_id,"select_posisi_out":data['list'].out_posisi_id,"select_fungsional_out":data['list'].out_fungsional_id,"select_group_out":data['list'].out_group_id};															
			select_box(data,item_select, item_sel);
			//penerima
			var item_sel=["select_struktural_in","select_posisi_in","select_fungsional_in","select_group_in"];
			var item_select = {"select_struktural_in":data['list'].in_struktural_id,"select_posisi_in":data['list'].in_posisi_id,"select_fungsional_in":data['list'].in_fungsional_id,"select_group_in":data['list'].in_group_id};															
			select_box(data,item_select, item_sel);																			
			
			if(data['list'].out_struktural_id != null){
				optNameOut = "struktural";
			}else if(data['list'].out_posisi_id != null){
				optNameOut = "posisi";
			}else if(data['list'].out_fungsional_id != null){
				optNameOut = "fungsional";
			}else{
				optNameOut = "group";
			}
			
			if(data['list'].in_struktural_id != null){
				optNameIn = "struktural";
			}else if(data['list'].in_posisi_id != null){
				optNameIn = "posisi";
			}else if(data['list'].in_fungsional_id != null){
				optNameIn = "fungsional";
			}else{
				optNameIn = "group";
			}
			
			disableSelectBox('out', true);						
			document.getElementById('option_'+optNameOut+'_out').checked = true;
			document.getElementById('select_'+optNameOut+'_out').disabled = false;	
			
			disableSelectBox('in', true);						
			document.getElementById('option_'+optNameIn+'_in').checked = true;
			document.getElementById('select_'+optNameIn+'_in').disabled = false;																																
					
			$('#modalAddForm').modal('show'); // show bootstrap modal when complete loaded
			$('.modal-title').text('Edit Data'); // Set title to Bootstrap modal title					
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			alert('Error get data from ajax');
		}
	});
}					

function data_save()
{
  var url;
  var form = document.getElementById('add_form');					  
  var form_data = new FormData(form);	
	
  var item_selectbox = document.getElementById('filter_approval');															  
  form_data.append("approval_type_id", item_selectbox.options[item_selectbox.selectedIndex].value);
	
  if(save_method == 'add') 
  {
	  url = base_url+'cms_approval/data_save_add';
  }
  else
  {
	url = base_url+'cms_approval/data_save_edit';
  }				
		  
   // ajax adding data to database
	  $.ajax({
		url : url,
		type: "POST",
		data: form_data,					
		processData: false,
		contentType: false,
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
			url : base_url+'cms_approval/data_delete',
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
						
function data_edit_posisi(id, pos)
{
	var form_data = {
			id: id,
			pos: pos					
		};
		
	$.ajax({
			url : base_url+'cms_approval/data_edit_posisi',
			type: "POST",
			dataType: "JSON",
			data: form_data,
			success: function(data)
			{
			   //if success close modal and reload ajax table
			   if(data['status'] == true){
					//berhasil simpan							
					 $('#page_message').html('<div class="alert alert-info">Berhasil ubah posisi.</div>');
					reload_table();
			   }else{
					$('#page_message').html('<div class="alert alert-info">Gagal ubah posisi.</div>');						
			   }					   					  
			},
			error: function (jqXHR, textStatus, errorThrown)
			{						
				alert('Error adding / update data');									
			}
		});	
}		