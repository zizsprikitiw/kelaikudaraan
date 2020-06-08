var save_method; //for save method string
var table;			

$(document).ready(function() {		
//Ajax Load data tahun dan pusat
load_select_filter();				 			

//load sub judul by tahun
$('#filter_tahun').on('change',function(){					
		load_filter_judul();											
});		

$('#filter_pusat').on('change',function(){					
		load_filter_judul();											
});

$('#filter_judul').on('change',function(){					
		reload_table_group_leader();											
});
										
//load data table group leader														
tableGroupLeader = $('#tableGroupLeader').DataTable({ 			
	"processing": true, //Feature control the processing indicator.
	"serverSide": true, //Feature control DataTables' server-side processing mode.
	"deferLoading": 0, // here	
	"paging": false,								
	"ordering": false,
	"bFilter": false,
	// Load data for the table's content from an Ajax source
	"ajax": {
		"url": base_url+'cms_struktur/data_list_group_leader/',
		"type": "POST",						
		"data": function ( d ) {
				var item_selectbox = document.getElementById('filter_judul');
				d.filter_judul = item_selectbox.options[item_selectbox.selectedIndex].value;																								
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

//load data table leader														
tableLeader = $('#tableLeader').DataTable({ 			
	"processing": true, //Feature control the processing indicator.
	"serverSide": true, //Feature control DataTables' server-side processing mode.
	"deferLoading": 0, // here									
	"ordering": false,
	"bFilter": false,
	// Load data for the table's content from an Ajax source
	"ajax": {
		"url": base_url+'cms_struktur/data_list_leader/',
		"type": "POST",						
		"data": function ( d ) {
				var item_selectbox = document.getElementById('filter_judul');
				d.filter_judul = item_selectbox.options[item_selectbox.selectedIndex].value;																								
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

});//end document																
						

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

//function get judul
function get_judul_proyek(){
	var item_selectbox = document.getElementById('filter_judul');
	var filter_judul = item_selectbox.options[item_selectbox.selectedIndex].value;				
	
	if(filter_judul == 0){
		 document.getElementById('btnAddGroupLeader').disabled = true;	
		 document.getElementById('btnAddLeader').disabled = true;							
	}else{
		document.getElementById('btnAddGroupLeader').disabled = false;
		document.getElementById('btnAddLeader').disabled = false;	
	}
	
	var form_data = {
		filter_judul: filter_judul					
	};
	
	$.ajax({
			url : base_url+'cms_struktur/get_judul_proyek/',
			type: "POST",
			data: form_data,
			dataType: "JSON",						
			success: function(data)
			{
				$('#judul_proyek').html(data['nama']);
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				alert('Error get data from ajax');
			}
		});	
}

//function load select filter
function load_select_filter(){
	 $.ajax({
			url : base_url+'cms_struktur/data_init/',
			type: "POST",
			dataType: "JSON",
			success: function(data)
			{
				var item_sel=["filter_pusat","filter_tahun"];
				var item_select = {"filter_pusat":-1,"filter_tahun":-1};															
				select_box(data,item_select, item_sel);			
				load_filter_judul();																								
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				alert('Error get data from ajax');
			}
		});	
}

//fungtion select judul
function load_filter_judul(){
	var item_selectbox = document.getElementById('filter_tahun');
	var select_tahun = item_selectbox.options[item_selectbox.selectedIndex].value;
	item_selectbox = document.getElementById('filter_pusat');
	var select_pusat = item_selectbox.options[item_selectbox.selectedIndex].value;
	
	var form_data = {
		filter_tahun: select_tahun,
		filter_pusat: select_pusat					
	};
	
	if((select_tahun == '--Pilih--') | (select_tahun == '')){
		$('#ref_id').html('<Option value="--Pilih--">--Pilih--</option>');						
	}else{
		//load data judul
		$.ajax({
			url : base_url+'cms_struktur/select_sub_judul/',
			type: "POST",
			dataType: "JSON",
			data: form_data,
			success: function(data)
			{
				var item_sel=["filter_judul"];
				var item_select = {"filter_judul":-1};															
				select_box(data,item_select, item_sel);	
				reload_table_group_leader();																														
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				alert('Error get data from ajax');
			}
		});	
	}
}		
		
function data_add(tbl_name)
{
	  save_method = 'add';
	  $('#add_form')[0].reset(); // reset form on modals		  
	  $('#modal_message').html('');  //reset message
	  
	  var item_selectbox = document.getElementById('filter_judul');
	  project_id = item_selectbox.options[item_selectbox.selectedIndex].value;
			
	  document.getElementById('tbl_name').value = tbl_name;
	  document.getElementById('project_id').value = project_id;
	  
	  
	  if(tbl_name == 'groups_leader'){
			//group leader
			$('#lbl_nama').text('Nama Groups Leader');
			document.getElementById('nama').placeholder = 'Nama Groups Leader';	
			document.getElementById('div_groups_leader').style.display = "none";		
			
			$('#modalAddForm').modal('show'); // show bootstrap modal
			$('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title												
	  }else{
			//leader
			$('#lbl_nama').text('Nama Leader');
			document.getElementById('nama').placeholder = 'Nama Leader';
			document.getElementById('div_groups_leader').style.display = "block";												
				
			var form_data = {
				project_id: project_id					
			};
							
			//Ajax Load data from ajax
		  $.ajax({
				url : base_url+'cms_struktur/data_add_leader/',
				type: "POST",
				dataType: "JSON",
				data: form_data,
				success: function(data)
				{
					var item_sel=["id_groups_leader"];
					var item_select = {"id_groups_leader":-1};															
					select_box(data,item_select, item_sel);		
																	
					$('#modalAddForm').modal('show'); // show bootstrap modal
					$('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title					
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					alert('Error get data from ajax');
				}
			});				
	  }				  				 			
}

function data_edit(id, tbl_name)
{
  save_method = 'update';
  $('#add_form')[0].reset(); // reset form on modals
  $('#modal_message').html('');  //reset message	
  
  var item_selectbox = document.getElementById('filter_judul');
  project_id = item_selectbox.options[item_selectbox.selectedIndex].value;
		
  document.getElementById('tbl_name').value = tbl_name;
  document.getElementById('project_id').value = project_id;						 			  
	  
   if(tbl_name == 'groups_leader'){
		//group leader
		$('#lbl_nama').text('Nama Groups Leader');
		document.getElementById('nama').placeholder = 'Nama Groups Leader';	
		document.getElementById('div_groups_leader').style.display = "none";
   }else{
		//leader
		$('#lbl_nama').text('Nama Leader');
		document.getElementById('nama').placeholder = 'Nama Leader';
		document.getElementById('div_groups_leader').style.display = "block";
   }			  			 
			
  var form_data = {
		id: id,
		table_name:tbl_name,
		project_id: project_id					
	};
			
  //Ajax Load data from ajax
  $.ajax({			  		
		url : base_url+'cms_struktur/data_edit/',
		type: "POST",
		dataType: "JSON",
		data: form_data,
		success: function(data)
		{				   
			$('[name="id"]').val(data['list'].id);  
			$('[name="nama"]').val(data['list'].nama);
			$('[name="singkatan"]').val(data['list'].singkatan);
			
			if(tbl_name == 'leader'){
				var item_sel=["id_groups_leader"];
				var item_select = {"id_groups_leader":data['list'].id_groups_leader};															
				select_box(data,item_select, item_sel);	
			}	
			
			$('#modalAddForm').modal('show'); // show bootstrap modal when complete loaded
			$('.modal-title').text('Edit Data'); // Set title to Bootstrap modal title					
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			alert('Error get data from ajax');
		}
	});
}

function reload_table_group_leader()
{		
	get_judul_proyek();					  
	tableGroupLeader.ajax.reload(null,false); //reload datatable ajax 
	tableLeader.ajax.reload(null,false); //reload datatable ajax 
}					

function data_save()
{
  var url;
  tbl_name = document.getElementById('tbl_name').value;
			  
  if(save_method == 'add') 
  {
	  url = base_url+'cms_struktur/data_save_add';
  }
  else
  {
	url = base_url+'cms_struktur/data_save_edit';
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
				 
				 if(data['table_name'] == 'groups_leader'){
					tableGroupLeader.ajax.reload(null,false); //reload datatable ajax 				
				 }else{
					tableLeader.ajax.reload(null,false); //reload datatable ajax 
				 }							 							
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

function data_delete(id, nama_id, tbl_name)
{
	if(id != ''){
		//show modal confirmation
		$('#delete_form')[0].reset(); // reset form on modals
		$('#modal_delete_message').html('');  //reset message
							
		$('[name="id_delete_data"]').val(id);
		$('[name="tbl_name_del"]').val(tbl_name);
		$('#delete_text').html('<b >Hapus data ' + nama_id + '</b>');	
		$('#modalDeleteForm').modal('show'); // show bootstrap modal when complete loaded
		$('.modal-title').text('Hapus Data'); // Set Title to Bootstrap modal title	
	}else{
		//lakukan hapus data
		// ajax hapus data to database
		$.ajax({
			url : base_url+'cms_struktur/data_delete',
			type: "POST",
			data: $('#delete_form').serialize(),
			dataType: "JSON",
			success: function(data)
			{
			   //if success close modal and reload ajax table
			   if(data['status'] == true){
					 $('#modalDeleteForm').modal('hide');					   		
					 $('#page_message').html('<div class="alert alert-info">Data berhasil di hapus.</div>');
					
					if(data['table_name'] == 'groups_leader'){
						tableGroupLeader.ajax.reload(null,false); //reload datatable ajax 				
					 }else{
						tableLeader.ajax.reload(null,false); //reload datatable ajax 
					 }	
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