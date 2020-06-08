$(document).ready(function() {									  			
	//Ajax Load data tahun dan pusat
	load_select_filter();				 															
		
	//load data table																
	table = $('#table').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		"deferLoading": 0, // here	
		"ordering": false,
		"paging": true,
		"dom": 'Blrtip',
		"buttons": ['pdf', 'csv', 'excel', 'print'],
		
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": base_url+'cms_log/data_list',
			"type": "POST",						
			"data": function ( d ) {
					var chkSearch = [];
					$.each($("input[name='chkSearch[]']:checked"), function(){
						chkSearch.push($(this).val());
					});
					
					d.chkSearch = chkSearch;
					var item_selectbox = document.getElementById('filter_tahun');
					d.filter_tahun = item_selectbox.options[item_selectbox.selectedIndex].value;	
					var item_selectbox = document.getElementById('filter_bulan');
					d.filter_bulan = item_selectbox.options[item_selectbox.selectedIndex].value;								
					d.nama = document.getElementById('nama').value;			
					d.keterangan = document.getElementById('keterangan').value;						
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
			url : base_url+'cms_log/data_init/',
			type: "POST",
			dataType: "JSON",
			success: function(data)
			{
				var item_sel=["filter_tahun", "filter_bulan"];
				var item_select = {"filter_tahun":-1, "filter_bulan":-1};															
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

function data_search()			
{							
	table.ajax.reload(null,false); //reload datatable ajax 
}