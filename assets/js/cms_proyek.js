var save_method; //for save method string
var table;		

$(document).ready(function() {		
	//Ajax Load data tahun dan pusat
	load_select_filter();
	 
	
	//filter tahun on change	
	$('#filter_tahun').on('change',function(){					
		reload_table();
	});	
	
	//load sub judul by tahun
	 $('#select_tahun').on('change',function(){					
		var item_selectbox = document.getElementById('select_tahun');
		var select_tahun = item_selectbox.options[item_selectbox.selectedIndex].value;
		item_selectbox = document.getElementById('pusat');
		var select_pusat = item_selectbox.options[item_selectbox.selectedIndex].value;
		
		var form_data = {
			select_tahun: select_tahun,
			select_pusat: select_pusat					
		};
		
		if((select_tahun == '--Pilih--') | (select_tahun == '')){
			$('#ref_id').html('<Option value="--Pilih--">--Pilih--</option>');						
		}else{
			//load data judul
			$.ajax({
				url : base_url+'cms_proyek/select_sub_judul/',
				type: "POST",
				dataType: "JSON",
				data: form_data,
				success: function(data)
				{
					var item_sel=["ref_id"];
					var item_select = {"ref_id":-1};															
					select_box(data,item_select, item_sel);																															
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					alert('Error get data from ajax');
				}
			});	
		}
							
	});		
											
	//load data table														
	table = $('#table').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		"deferLoading": 0, // here				
		"paging": false,
		"ordering": false,
		"bFilter": false,
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": base_url+'cms_proyek/data_list/',
			"type": "POST",						
			"data": function ( d ) {
					var item_selectbox = document.getElementById('filter_tahun');
					d.filter_tahun = item_selectbox.options[item_selectbox.selectedIndex].value;
					
					var item_selectbox = document.getElementById('filter_pusat');
					d.filter_pusat = item_selectbox.options[item_selectbox.selectedIndex].value;
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

//function load select filter
function load_select_filter(){
	 $.ajax({
			url : base_url+'cms_proyek/data_init/',
			type: "POST",
			dataType: "JSON",
			success: function(data)
			{
				var item_sel=["filter_pusat","filter_tahun"];
				var item_select = {"filter_pusat":-1,"filter_tahun":-1};															
				select_box(data,item_select, item_sel);			
				reload_table();																	
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
	  $('#ref_id').html('<Option value="--Pilih--">--Pilih--</option>');
	  document.getElementById('pusat').disabled = false;
	  document.getElementById('select_tahun').disabled = false;
	  document.getElementById('ref_id').disabled = false;				  
	  
	  //Ajax Load data from ajax
	  $.ajax({
			url : base_url+'cms_proyek/data_add/',
			type: "POST",
			dataType: "JSON",
			success: function(data)
			{
				var item_sel=["pusat", "select_tahun"];
				var item_select = {"pusat":-1,"select_tahun":-1};															
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

function data_edit(id)
{
  save_method = 'update';
  $('#add_form')[0].reset(); // reset form on modals
  $('#modal_message').html('');  //reset message	
  document.getElementById('pusat').disabled = true;
  document.getElementById('select_tahun').disabled = true;
  document.getElementById('ref_id').disabled = true;	
  $('#ref_id').html('<Option value="--Pilih--">--Pilih--</option>');
			
  //Ajax Load data from ajax
  $.ajax({			  		
		url : base_url+'cms_proyek/data_edit/' + id,
		type: "GET",
		dataType: "JSON",
		success: function(data)
		{				   
			$('[name="id"]').val(data['list'].id);  
			$('[name="nama"]').val(data['list'].nama);
			$('[name="singkatan"]').val(data['list'].singkatan);
			$('[name="tahun"]').val(data['list'].tahun);
			
			var item_sel=["pusat"];
			var item_select = {"pusat":data['list'].pusat_id};															
			select_box(data,item_select, item_sel);	
			
			if(data['list'].ref_id == null){
				//judul utama							
				var item_sel=["select_tahun"];
				var item_select = {"select_tahun":-1};															
				select_box(data,item_select, item_sel);	
			}else{
				//sub judul dari							
				var item_sel=["select_tahun", "ref_id"];
				var item_select = {"select_tahun":data['ref_id_tahun'],"ref_id":data['list'].ref_id};															
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

function reload_table()
{				
  //alert(filter_tahun_value);
  table.ajax.reload(null,false); //reload datatable ajax 
}

function data_save()
{
  var url;
  document.getElementById('pusat').disabled = false;
  document.getElementById('select_tahun').disabled = false;
  document.getElementById('ref_id').disabled = false;	
  
  if(save_method == 'add') 
  {
	  url = base_url+'cms_proyek/data_save_add';
  }
  else
  {
	url = base_url+'cms_proyek/data_save_edit';
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
				load_select_filter();
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
			url : base_url+'cms_proyek/data_delete',
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
	}				
}		

function data_edit_posisi(id, pos)
{
	var form_data = {
			id: id,
			pos: pos					
		};
		
	$.ajax({
			url : base_url+'cms_proyek/data_edit_posisi',
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