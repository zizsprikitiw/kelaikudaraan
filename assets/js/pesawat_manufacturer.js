var save_method; //for save method string
var table;

$(document).ready(function() {																
	table = $('#table').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": base_url+'pesawat_manufacturer/data_list',
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

	});
});				

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
		url : base_url+'pesawat_manufacturer/data_edit/' + id,
		type: "GET",
		dataType: "JSON",
		success: function(data)
		{				   
			$('[name="id"]').val(data['list'].id);  
			$('[name="nama"]').val(data['list'].nama);												
					
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

function data_save()
{
  var url;
  if(save_method == 'add') 
  {
	  url = base_url+'pesawat_manufacturer/data_save_add';
  }
  else
  {
	url = base_url+'pesawat_manufacturer/data_save_edit';
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
			url : base_url+'pesawat_manufacturer/data_delete',
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