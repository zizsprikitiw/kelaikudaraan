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
				url : base_url+'pesawat/select_sub_judul/',
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
			"url": base_url+'pesawat/data_list/',
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
			url : base_url+'pesawat/data_init/',
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

function data_edit_proyek(id)
{
  save_method = 'update';
  $('#add_form_proyek')[0].reset(); // reset form on modals
  $('#modal_message').html('');  //reset message	
  document.getElementById('div_upload_status').style.display = "none";	
			
  //Ajax Load data from ajax
  $.ajax({			  		
		url : base_url+'pesawat/data_edit_proyek/' + id,
		type: "GET",
		dataType: "JSON",
		success: function(data)
		{				   
			$('[name="id"]').val(data['list'].id);  
			$('[name="nama_pesawat"]').val(data['list'].nama);  
			$('[name="singkatan"]').val(data['list'].singkatan);  
			$('[name="posisi"]').val(data['list'].posisi);  
			$('[name="keterangan"]').val(data['list'].keterangan);  
			
			$('#modalAddFormProyek').modal('show'); // show bootstrap modal when complete loaded
			$('.modal-title').text('Edit Data'); // Set title to Bootstrap modal title	
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
  document.getElementById('div_upload_status').style.display = "none";	
			
  //Ajax Load data from ajax
  $.ajax({			  		
		url : base_url+'pesawat/data_edit/' + id,
		type: "GET",
		dataType: "JSON",
		success: function(data)
		{				   
			$('[name="id"]').val(data['list'].id);  
			
			$('#modalAddForm').modal('show'); // show bootstrap modal when complete loaded
			$('.modal-title').text('Upload Foto'); // Set title to Bootstrap modal title	

			//load data table														
			table_file = $('#table_file').DataTable({ 			
				"processing": true, //Feature control the processing indicator.
				"serverSide": true, //Feature control DataTables' server-side processing mode.
				//"deferLoading": 0, // here				
				"paging": false,
				"ordering": false,
				"bFilter": false,
				// Load data for the table's content from an Ajax source
				"ajax": {
					"url": base_url+'pesawat/data_list_fle/',
					"type": "POST",						
					"data": {
						proyek_id:data['list'].id
					}					
				},
				
				//Set column definition initialisation properties.
				"columnDefs": [
					{ 
					  "targets": [ 0, -1 ], //last column
					  "width": '30px',
					  "className": 'dt-center',
					  "searchable": false,
					  "orderable": false, //set not orderable
					},
				],
				
				"destroy": true,
			});//end load data table	
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
	// ajax adding data to database
	$.ajax({
		url : base_url+'pesawat/data_save_edit',
		type: "POST",
		data: $('#add_form_proyek').serialize(),
		dataType: "JSON",
		success: function(data)
		{
		   //if success close modal and reload ajax table
		   if(data['status'] == true){
				//berhasil simpan							
				 $('#modalAddFormProyek').modal('hide');					   		
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

function data_save_validation()
{					 								  
  var form = document.getElementById('add_form');					  
  var form_data = new FormData(form);					  			 				  
  var fileInput = document.getElementById('file_foto');
  var file = fileInput.files[0];					
  form_data.append("file_foto", file);						  				  					  				  					  
			
   // ajax adding data to database
	  $.ajax({
		url : base_url+'pesawat/data_save_validation/',
		type: "POST",
		data: form_data,
		processData: false,
		contentType: false,
		dataType: "JSON",
		success: function(data)
		{
		   //if success close modal and reload ajax table
		   if(data['status'] == true){
				//Validation OK, next upload file								
				data_save_pesawat(data['new_file_name']);
			}else{							
				$('#modal_message').html('<div class="alert alert-info">' + data['status'] + '</div>');							
		   }					   					  
		},
		error: function (jqXHR, textStatus, errorThrown)
		{						
			alert('Error adding / update data');									
		}
	});
}
			
function data_save_pesawat(new_file_name)
{					 								  
  var form = document.getElementById('add_form');					  
  var form_data = new FormData(form);					  
  form_data.append("new_file_name", new_file_name);				 				  
  var fileInput = document.getElementById('file_foto');
  var file = fileInput.files[0];					
  form_data.append("file_foto", file);	
  
  document.getElementById('div_upload_status').style.display = "block";	
  $('#new_filename').html(fileInput.value);
																							  
			
   // ajax adding data to database
	  $.ajax({
		xhr: function() {
			var xhr = new window.XMLHttpRequest();
		
			xhr.upload.addEventListener("progress", function(evt) {
			  if (evt.lengthComputable) {
					var percentComplete = evt.loaded / evt.total;
					percentComplete = parseInt(percentComplete * 100);
					$('#status_upload').html("Status upload: "+percentComplete+"%");	
					$('#status_progressbar').html(percentComplete+"% Complete");	
					 document.getElementById('div_progressbar').style.width = percentComplete+"%";							
					//console.log(percentComplete);
			
					if (percentComplete === 100) {
						//console.log(percentComplete);
					}					
			  }
			}, false);
		
			return xhr;
		  },
		url : base_url+'pesawat/data_save_pesawat/',
		type: "POST",
		data: form_data,
		processData: false,
		contentType: false,
		dataType: "JSON",
		success: function(data)
		{
		   //if success close modal and reload ajax table
		   if(data['status'] == true){	
				 $('#add_form')[0].reset(); // reset form on modals
				 $('#modal_message').html('');
				 toastr.success('Data berhasil di simpan.');
				 $('#table_file').DataTable().ajax.reload();
		   }else{							
				toastr.error(data['status']);					
		   }	
			document.getElementById('div_upload_status').style.display = "none";			   
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
			url : base_url+'pesawat/data_delete',
			type: "POST",
			data: $('#delete_form').serialize(),
			dataType: "JSON",
			success: function(data)
			{
			   //if success close modal and reload ajax table
			   if(data['status'] == true){
					//berhasil simpan							
					$('#modalDeleteForm').modal('hide');					   		
					toastr.success('Data berhasil di hapus.');
					$('#table_file').DataTable().ajax.reload();
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