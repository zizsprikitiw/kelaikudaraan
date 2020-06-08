var save_method; //for save method string
var table;		
var is_where;	

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
			reload_table();											
	});
		
	$('#id_posisi_as').on('change',function(){					
			load_filter_posisi(-1, -1);											
	});				
						
	$('#id_posisi_kegiatan').on('change',function(){					
			load_filter_ld_gl(-1);											
	});
					
	//load data table leader														
	tablePersonil = $('#tablePersonil').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		"deferLoading": 0, // here		
		"paging": false,							
		"ordering": false,					
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": base_url+'cms_personil_kegiatan/data_list_personil/',
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
	
	table_user = $('#table_user').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		"deferLoading": 0, // here												
		"ordering": false,					
		
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": base_url+'cms_personil_kegiatan/data_list_users',
			"type": "POST",
			"data": function ( d ) {
				//alert(is_where);							
				d.is_where = is_where;
				
				var nama_user = document.getElementById('nama').value;
				if(nama_user == ''){
					d.nama_user = '-';
				}else{
					d.nama_user = nama_user;
				}
											
				is_where = '';
			}
		},

		//Set column definition initialisation properties.
		"columnDefs": [
			{ 
			  "targets": [ -1, -2 ], //last column
			  "searchable": false,
			  "orderable": false, //set not orderable
			},
		],

	});
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
		 document.getElementById('btnAddPersonil').disabled = true;							
	}else{
		document.getElementById('btnAddPersonil').disabled = false;
	}
	
	var form_data = {
		filter_judul: filter_judul					
	};
	
	$.ajax({
			url : base_url+'cms_personil_kegiatan/get_judul_proyek/',
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
			url : base_url+'cms_personil_kegiatan/data_init/',
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
			url : base_url+'cms_personil_kegiatan/select_sub_judul/',
			type: "POST",
			dataType: "JSON",
			data: form_data,
			success: function(data)
			{
				var item_sel=["filter_judul"];
				var item_select = {"filter_judul":-1};															
				select_box(data,item_select, item_sel);	
				reload_table();																														
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				alert('Error get data from ajax');
			}
		});	
	}
}			

//fungtion select posisi struktural/posisi jabatan kegiatan (tambah data)
function load_filter_posisi(posIndex, posIndexGlLd){
	var item_selectbox = document.getElementById('id_posisi_as');
	var id_posisi_as = item_selectbox.options[item_selectbox.selectedIndex].value;																
	
	var form_data = {
		id_posisi_as: id_posisi_as															
	};
					
	//load data
	$.ajax({
		url : base_url+'cms_personil_kegiatan/select_posisi_kegiatan/',
		type: "POST",
		dataType: "JSON",
		data: form_data,
		success: function(data)
		{
			var item_sel=["id_posisi_kegiatan"];
			var item_select = {"id_posisi_kegiatan":posIndex};															
			select_box(data,item_select, item_sel);	
			load_filter_ld_gl(posIndexGlLd);																													
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			alert('Error get data from ajax');
		}
	});	
	
}	

function load_filter_ld_gl(posIndex){						
	var item_selectbox = document.getElementById('id_posisi_as');
	var id_posisi_as = item_selectbox.options[item_selectbox.selectedIndex].value;	
													
	document.getElementById('div_posisi_gl_ld').style.display = "none";	
	document.getElementById('div_nama').style.display = "none";
	$('#modal_message').html('');  //reset message
	
	if(id_posisi_as == 1){
		//bukan struktural															
		item_selectbox = document.getElementById('id_posisi_kegiatan');
		var id_posisi_kegiatan = item_selectbox.options[item_selectbox.selectedIndex].value;	
		
		item_selectbox = document.getElementById('filter_judul');
		var filter_judul = item_selectbox.options[item_selectbox.selectedIndex].value;
		var user_id = document.getElementById('user_id').value;
		
		var form_data = {
			id_posisi_kegiatan: id_posisi_kegiatan,
			filter_judul: filter_judul,
			user_id:user_id																											
		};
		
		//load data
		$.ajax({
			url : base_url+'cms_personil_kegiatan/select_posisi_ld_gl/',
			type: "POST",
			dataType: "JSON",
			data: form_data,
			success: function(data)
			{
				var item_sel=["id_posisi_gl_ld"];
				var item_select = {"id_posisi_gl_ld":posIndex};															
				select_box(data,item_select, item_sel);
						
				if(data['have_sub'] == 'false'){
					//tidak memiliki sub	
					document.getElementById('div_nama').style.display = "block";																	
				}else{
					//memiliki sub
					if(data['is_empty'] == 'false'){																	
						document.getElementById('div_posisi_gl_ld').style.display = "block";	
						document.getElementById('div_nama').style.display = "block";																	
					}else{
						//leader/group leader empty, tampilkan info tambahakan leader/group leader																															
						$('#modal_message').html('<div class="alert alert-info">' + data['is_empty'] + ' masih kosong atau telah memiliki personil</div>');	
					}
				}
																															
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				alert('Error get data from ajax');
			}
		});
	}else{
		//struktural					
		document.getElementById('div_nama').style.display = "block";
		/*
		var form_data = {
			id_posisi_kegiatan: id_posisi_kegiatan,
			filter_tahun: filter_tahun,
			filter_pusat: filter_pusat																											
		};
		
		//load data
		$.ajax({
			url : "< ?php echo site_url('cms_personil_kegiatan/get_nama_struktural/')?>" ,
			type: "POST",
			dataType: "JSON",
			data: form_data,
			success: function(data)
			{								
				
				$('[name="nama"]').val(data['list'][0].nama_user); 
				$('[name="user_id"]').val(data['list'][0].user_id);																																																
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				alert('Error get data from ajax');
			}
		});	*/				
	}				
}									
	
function reload_table()
{		
	get_judul_proyek();					  
	tablePersonil.ajax.reload(null,false); //reload datatable ajax 
}															
		
function data_add()
{
  save_method = 'add';
  $('#add_form')[0].reset(); // reset form on modals		  
  $('#modal_message').html('');  //reset message			  			  			  
  $('[name="user_id"]').val('0');
  
  document.getElementById('id_posisi_as').disabled = false;
  document.getElementById('id_posisi_kegiatan').disabled = false;
  document.getElementById('id_posisi_gl_ld').disabled = false;	
			
  var item_selectbox = document.getElementById('filter_judul');
  $('[name="proyek_id"]').val(item_selectbox.options[item_selectbox.selectedIndex].value);			  
  
  document.getElementById('div_search_nama').style.display = "none";
  load_filter_posisi(-1);	
				  
  $('#modalAddForm').modal('show'); // show bootstrap modal
  $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title				 		  				 			
}
			
function data_edit(id_users_posisi)
{
  save_method = 'update';
  $('#add_form')[0].reset(); // reset form on modals
  $('#modal_message').html('');  //reset message		
  
  var item_selectbox = document.getElementById('filter_judul');
  $('[name="proyek_id"]').val(item_selectbox.options[item_selectbox.selectedIndex].value);			  		  			 				 			  				 			  document.getElementById('div_search_nama').style.display = "none";
			
  var form_data = {
		id_users_posisi: id_users_posisi			
	};
			
  //Ajax Load data from ajax
  $.ajax({			  		
		url : base_url+'cms_personil_kegiatan/data_edit/',
		type: "POST",
		dataType: "JSON",
		data: form_data,
		success: function(data)
		{				   							
			document.getElementById('div_nama').style.display = "block";
					
			$('[name="id"]').val(data['list'].id);  
			$('[name="nama"]').val(data['list'].nama_user); 						
			$('[name="user_id"]').val(data['list'].user_id);	 						
			
			if((data['list'].struktural_id == null) || (data['list'].struktural_id == '')){
				document.getElementById('id_posisi_as').selectedIndex = "1";																					
				
				if(data['list'].groups_leader_id != null){
					posIndexGlLd = data['list'].groups_leader_id;
				}else if(data['list'].leader_id != null){
					posIndexGlLd = data['list'].leader_id;									
				}
				
				load_filter_posisi(data['list'].posisi_id, posIndexGlLd);
			}else{
				//struktural
				document.getElementById('id_posisi_as').selectedIndex = "0";
				load_filter_posisi(data['list'].struktural_id);
			}
			
			document.getElementById('id_posisi_as').disabled = true;
			document.getElementById('id_posisi_kegiatan').disabled = true;
			document.getElementById('id_posisi_gl_ld').disabled = true;												
															
			$('#modalAddForm').modal('show'); // show bootstrap modal when complete loaded
			$('.modal-title').text('Edit Data'); // Set title to Bootstrap modal title					
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			alert('Error get data from ajax');
		}
	});
}	
		
function data_search()
{			 	  			  			  
  is_where = 'true';
  document.getElementById('div_search_nama').style.display = "block";			  
  table_user.ajax.reload(null,false); //reload datatable ajax 							  			  
}	
	
function data_pick(id_users, nama_user)
{
	$('[name="nama"]').val(nama_user); 						
	$('[name="user_id"]').val(id_users);
}			

function data_save()
{
  var url;		
  document.getElementById('id_posisi_as').disabled = false;
  document.getElementById('id_posisi_kegiatan').disabled = false;
  document.getElementById('id_posisi_gl_ld').disabled = false;	
							  
  if(save_method == 'add') 
  {
	  url = base_url+'cms_personil_kegiatan/data_save_add';
  }else{
	  url = base_url+'cms_personil_kegiatan/data_save_edit';
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
		
		var item_selectbox = document.getElementById('filter_judul');
		$('[name="proyek_id_del"]').val(item_selectbox.options[item_selectbox.selectedIndex].value);	
							
		$('#delete_text').html('<b >Hapus data ' + nama_id + '</b>');	
		$('#modalDeleteForm').modal('show'); // show bootstrap modal when complete loaded
		$('.modal-title').text('Hapus Data'); // Set Title to Bootstrap modal title	
	}else{
		//lakukan hapus data
		// ajax hapus data to database
		$.ajax({
			url : base_url+'cms_personil_kegiatan/data_delete',
			type: "POST",
			data: $('#delete_form').serialize(),
			dataType: "JSON",
			success: function(data)
			{
			   //if success close modal and reload ajax table
			   if(data['status'] == true){
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