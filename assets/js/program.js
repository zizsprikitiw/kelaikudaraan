var tbl_tsn_aircraft_report;
var tbl_ins_aircraft_report;
var tbl_ins_task_report;
var tbl_pesawat_komponen;
var tbl_task_status;

jQuery(document).ready(function() {
    slider();
    chart();
	tsn_aircraft_report();
	ins_aircraft_report();
	ins_task_report();
	aircraft_status();
	task_status();
	scheduling();
	chart();
});

function reload_table()
{
  tbl_tsn_aircraft_report.ajax.reload(null,false); //reload datatable ajax 
  tbl_ins_aircraft_report.ajax.reload(null,false); //reload datatable ajax 
  tbl_ins_task_report.ajax.reload(null,false); //reload datatable ajax 
  tbl_pesawat_komponen.ajax.reload(null,false); //reload datatable ajax 
  tbl_task_status.ajax.reload(null,false); //reload datatable ajax 
  scheduling();
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

var slider = function() {
	$('.fadeOut').owlCarousel({
		items: 1,
		animateOut: 'fadeOut',
		autoHeight:true,
		loop: true,
		margin: 10,
		autoplay:true,
		autoplayTimeout:5000,
		autoplayHoverPause:true,
	});
}

function chart() {
	var proyek_id = document.getElementById("proyek_id").value;
	
	var chart = AmCharts.makeChart("chart_flight", {
		"type": "serial",
		"theme": "light",
		"legend": {
			"equalWidths": false,
			"useGraphSettings": true,
			"valueAlign": "left",
			"valueWidth": 120
		},
		/*"dataProvider": [{
			"date": "2012-01-01",
			"duration": 408
		}, {
			"date": "2012-01-02",
			"duration": 482
		}, {
			"date": "2012-01-03",
			"duration": 562
		}, {
			"date": "2012-01-04",
			"duration": 379
		}, {
			"date": "2012-01-05",
			"duration": 501
		}, {
			"date": "2012-01-06",
			"duration": 443
		}, {
			"date": "2012-01-07",
			"duration": 405
		}, {
			"date": "2012-01-08",
			"duration": 309
		}, {
			"date": "2012-01-09",
			"duration": 287
		}, {
			"date": "2012-01-10",
			"duration": 485
		}, {
			"date": "2012-01-11",
			"duration": 890
		}, {
			"date": "2012-01-12",
			"duration": 810
		}, {
			"date": "2012-01-13",
			"duration": 670
		}, {
			"date": "2012-01-14",
			"duration": 470
		}],*/
		"valueAxes": [{
			"id": "durationAxis",
			"duration": "hh",
			"durationUnits": {
				"hh": "h ",
				"mm": "min"
			},
			"axisAlpha": 0,
			"gridAlpha": 0,
			"inside": true,
			"position": "right",
			"title": "duration"
		}],
		"graphs": [{
			"bullet": "square",
			"bulletBorderAlpha": 1,
			"bulletBorderThickness": 1,
			"dashLengthField": "dashLength",
			"legendValueText": "[[value]]",
			"title": "duration",
			"fillAlphas": 0,
			"valueField": "duration",
			"valueAxis": "durationAxis"
		}],
		"chartCursor": {
			"categoryBalloonDateFormat": "DD",
			"cursorAlpha": 0.1,
			"cursorColor": "#000000",
			"fullWidth": true,
			"valueBalloonsEnabled": false,
			"zoomable": false
		},
		"dataDateFormat": "YYYY-MM-DD",
		"categoryField": "date",
		"categoryAxis": {
			"dateFormats": [{
				"period": "DD",
				"format": "DD"
			}, {
				"period": "WW",
				"format": "MMM DD"
			}, {
				"period": "MM",
				"format": "MMM"
			}, {
				"period": "YYYY",
				"format": "YYYY"
			}],
			"parseDates": true,
			"autoGridCount": false,
			"axisColor": "#555555",
			"gridAlpha": 0.1,
			"gridColor": "#FFFFFF",
			"gridCount": 50
		},
		"export": {
			"enabled": true
		}
	});
	
	$.ajax({
		url : base_url+"program/data_flight_chart" ,
		type: "POST",
		dataType: "JSON",
		data: {"proyek_id":proyek_id},
		beforeSend: function() 
		{    
			// App.blockUI({
				// target: el,
				// animate: true,
				// overlayColor: 'none'
			// });
		},
		success: function(data)
		{
			//App.unblockUI(el);
			
			chart.dataProvider = data.data_chart;
			chart.validateData();
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

var tsn_aircraft_report = function() {
	var proyek_id = document.getElementById("proyek_id").value;

	// begin first table
	tbl_tsn_aircraft_report = $('#tbl_tsn_aircraft_report').DataTable({
		processing: true, //Feature control the processing indicator.
		serverSide: true, //Feature control DataTables' server-side processing mode.
		
		// Load data for the table's content from an Ajax source
		ajax: {
			"url": base_url+'program/list_tsn_aircraft_report',
			"type": "POST",
			"data": {proyek_id:proyek_id},
		},
		
		responsive: true,

		// DOM Layout settings
		dom: `<'row'<'col-sm-12'tr>>
		<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,

		lengthMenu: [5, 10, 25, 50],

		pageLength: 10,

		language: {
			'lengthMenu': 'Display _MENU_',
		},

		// Order settings
		order: [[0, 'asc']],

		headerCallback: function(thead, data, start, end, display) {
			// thead.getElementsByTagName('th')[0].innerHTML = `
				// <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
					// <input type="checkbox" value="" class="m-group-checkable">
					// <span></span>
				// </label>`;
		},

		columnDefs: [
			{
				targets: [ 0, -1],
				width: '30px',
				className: 'dt-center',
				orderable: false,
				// render: function(data, type, full, meta) {
					// return `
					// <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
						// <input type="checkbox" value="" class="m-checkable">
						// <span></span>
					// </label>`;
				// },
			},
			{
				targets: -1,
				title: 'Action',
				orderable: false,
			},
		],
	});
}

var ins_aircraft_report = function() {
	var proyek_id = document.getElementById("proyek_id").value;

	// begin first table
	tbl_ins_aircraft_report = $('#tbl_ins_aircraft_report').DataTable({
		processing: true, //Feature control the processing indicator.
		serverSide: true, //Feature control DataTables' server-side processing mode.
		
		// Load data for the table's content from an Ajax source
		ajax: {
			"url": base_url+'program/list_ins_aircraft_report',
			"type": "POST",
			"data": {proyek_id:proyek_id},
		},
		
		responsive: true,

		// DOM Layout settings
		dom: `<'row'<'col-sm-12'tr>>
		<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,

		lengthMenu: [5, 10, 25, 50],

		pageLength: 10,

		language: {
			'lengthMenu': 'Display _MENU_',
		},

		// Order settings
		order: [[0, 'asc']],

		headerCallback: function(thead, data, start, end, display) {
			// thead.getElementsByTagName('th')[0].innerHTML = `
				// <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
					// <input type="checkbox" value="" class="m-group-checkable">
					// <span></span>
				// </label>`;
		},

		columnDefs: [
			{
				targets: [0,-1],
				width: '30px',
				className: 'dt-center',
				orderable: false,
				// render: function(data, type, full, meta) {
					// return `
					// <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
						// <input type="checkbox" value="" class="m-checkable">
						// <span></span>
					// </label>`;
				// },
			},
			{
				targets: -1,
				title: 'Action',
				orderable: false,
			},
		],
	});
}

var ins_task_report = function() {
	var proyek_id = document.getElementById("proyek_id").value;
	
	// begin first table
	tbl_ins_task_report = $('#tbl_ins_task_report').DataTable({
		processing: true, //Feature control the processing indicator.
		serverSide: true, //Feature control DataTables' server-side processing mode.
		
		// Load data for the table's content from an Ajax source
		ajax: {
			"url": base_url+'program/list_ins_task_report',
			"type": "POST",
			"data": {proyek_id:proyek_id},
		},
		
		responsive: true,

		// DOM Layout settings
		dom: `<'row'<'col-sm-12'tr>>
		<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,

		lengthMenu: [5, 10, 25, 50],

		pageLength: 10,

		language: {
			'lengthMenu': 'Display _MENU_',
		},

		// Order settings
		order: [[0, 'asc']],

		headerCallback: function(thead, data, start, end, display) {
			// thead.getElementsByTagName('th')[0].innerHTML = `
				// <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
					// <input type="checkbox" value="" class="m-group-checkable">
					// <span></span>
				// </label>`;
		},

		columnDefs: [
			{
				targets: [0,-1],
				width: '30px',
				className: 'dt-center',
				orderable: false,
				// render: function(data, type, full, meta) {
					// return `
					// <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
						// <input type="checkbox" value="" class="m-checkable">
						// <span></span>
					// </label>`;
				// },
			},
			{
				targets: -1,
				title: 'Action',
				orderable: false,
			},
		],
	});
}

var aircraft_status = function() {
	var proyek_id = document.getElementById("proyek_id").value;

	// begin first table
	tbl_pesawat_komponen = $('#tbl_pesawat_komponen').DataTable({
		processing: true, //Feature control the processing indicator.
		serverSide: true, //Feature control DataTables' server-side processing mode.
		
		// Load data for the table's content from an Ajax source
		ajax: {
			"url": base_url+'program/list_pesawat_komponen',
			"type": "POST",
			"data": {proyek_id:proyek_id},
		},
		
		responsive: true,

		// DOM Layout settings
		dom: `<'row'<'col-sm-12'tr>>
		<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,

		lengthMenu: [5, 10, 25, 50],

		pageLength: 10,

		language: {
			'lengthMenu': 'Display _MENU_',
		},

		// Order settings
		order: [[0, 'asc']],

		headerCallback: function(thead, data, start, end, display) {
			// thead.getElementsByTagName('th')[0].innerHTML = `
				// <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
					// <input type="checkbox" value="" class="m-group-checkable">
					// <span></span>
				// </label>`;
		},
		
		rowsGroup: [0,1,2,3,9],

		columnDefs: [
			{
				targets: [0,-1],
				width: '30px',
				className: 'dt-center',
				orderable: false,
				// render: function(data, type, full, meta) {
					// return `
					// <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
						// <input type="checkbox" value="" class="m-checkable">
						// <span></span>
					// </label>`;
				// },
			},
			{
				targets: -1,
				title: 'Action',
				orderable: false,
			},
		],
	});
}

var task_status = function() {
	var proyek_id = document.getElementById("proyek_id").value;

	// begin first table
	tbl_task_status = $('#tbl_task_status').DataTable({
		processing: true, //Feature control the processing indicator.
		serverSide: true, //Feature control DataTables' server-side processing mode.
		
		// Load data for the table's content from an Ajax source
		ajax: {
			"url": base_url+'program/list_pesawat_task',
			"type": "POST",
			"data": {proyek_id:proyek_id},
		},
		
		responsive: true,

		// DOM Layout settings
		dom: `<'row'<'col-sm-12'tr>>
		<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,

		lengthMenu: [5, 10, 25, 50],

		pageLength: 10,

		language: {
			'lengthMenu': 'Display _MENU_',
		},

		// Order settings
		order: [[0, 'asc']],

		headerCallback: function(thead, data, start, end, display) {
			// thead.getElementsByTagName('th')[0].innerHTML = `
				// <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
					// <input type="checkbox" value="" class="m-group-checkable">
					// <span></span>
				// </label>`;
		},

		columnDefs: [
			{
				targets: [0,-1],
				width: '30px',
				className: 'dt-center',
				orderable: false,
				// render: function(data, type, full, meta) {
					// return `
					// <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
						// <input type="checkbox" value="" class="m-checkable">
						// <span></span>
					// </label>`;
				// },
			},
			{
				targets: -1,
				title: 'Action',
				orderable: false,
			},
		],
	});
}

var scheduling = function() {
	var proyek_id = document.getElementById("proyek_id").value;
	//Ajax Load data from ajax
	$.ajax({
		url : base_url+"program/list_scheduling",
		type: "POST",
		dataType: "JSON",
		data: {proyek_id:proyek_id},
		success: function(data)
		{
			$("#tbl_scheduling").html(data.data);

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

var loadFormAddTSNAircraftReport = function() {
	var proyek_id = document.getElementById("proyek_id").value;
	save_method = 'add';
	$('#modalFormAddTSNAircraftReport [name="save_method"]').val(save_method);  
	$('#add_form_tsn_aircraft_report')[0].reset(); // reset form on modals		  
	$('#modal_message').html('');  //reset message					 			  
	//document.getElementById('ref_id').disabled = false;
	//document.getElementById('url').disabled = false;
	//$('#modalFormAttachFileAgenda').modal('show');
  
	//Ajax Load data from ajax
	$.ajax({
		url : base_url+"program/data_form_add_tsn_aircraft_report",
		type: "POST",
		dataType: "JSON",
		data: {proyek_id:proyek_id},
		success: function(data)
		{
			var item_sel=["filter_komponen"];
			var item_select = {"filter_komponen":-1};															
			select_box(data,item_select, item_sel);		
															
			$('#modalFormAddTSNAircraftReport').modal('show'); // show bootstrap modal
			$('.modal-title').text('Tambah TSN Aircraft Report'); // Set Title to Bootstrap modal title
			
			loadThemeSelect2();
			loadThemeDatepicker();
			loadThemeDesTouchspin();

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

var loadFormAddKomponen = function() {
	var proyek_id = document.getElementById("proyek_id").value;
	save_method = 'add';
	$('#modalFormAddKomponen [name="save_method"]').val(save_method);  
	$('#add_form_komponen')[0].reset(); // reset form on modals		  
	$('#modal_message').html('');  //reset message					 			  
	//document.getElementById('ref_id').disabled = false;
	//document.getElementById('url').disabled = false;
	//$('#modalFormAttachFileAgenda').modal('show');
  
	//Ajax Load data from ajax
	$.ajax({
		url : base_url+"program/data_form_add_komponen",
		type: "POST",
		dataType: "JSON",
		data: {proyek_id:proyek_id},
		success: function(data)
		{
			var item_sel=["filter_description","filter_manufacturer","filter_posisi"];
			var item_select = {"filter_description":-1,"filter_manufacturer":-1,"filter_posisi":-1};															
			select_box(data,item_select, item_sel);		
			
			// var item_sel=["filter_pic","filter_approval","filter_member"];
			// var item_select = {"filter_pic":[-1],"filter_approval":[-1],"filter_member":[-1]};															
			// select_box_group(data,item_select, item_sel);	
															
			$('#modalFormAddKomponen').modal('show'); // show bootstrap modal
			$('.modal-title').text('Tambah Komponen'); // Set Title to Bootstrap modal title
			
			loadThemeSelect2();
			loadThemeDatepicker();
			loadThemeDesTouchspin();

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

var loadFormUpdateKomponen = function(id) 
{
  var proyek_id = document.getElementById("proyek_id").value;
  save_method = 'update';
  $('#add_form_komponen')[0].reset(); // reset form on modals		  
  $('#modal_message').html('');  //reset message					
  $('#modalFormAddKomponen [name="save_method"]').val(save_method);  
			
  //Ajax Load data from ajax
  $.ajax({			  		
		url : base_url+'program/data_form_update_komponen/' + id,
		type: "GET",
		dataType: "JSON",
		data: {proyek_id:proyek_id},
		success: function(data)
		{				   
			$('[name="id"]').val(data['list'].id);  
			$('[name="aircraft_registration"]').val(data['list'].aircraft_registration);  
			$('[name="registration_number"]').val(data['list'].number_registration);				
			$('[name="model"]').val(data['list'].model);				
			$('[name="serial_number"]').val(data['list'].serial_number);				
			$('[name="date_of_install"]').val(data['list'].date_of_install);				
			$('[name="tsn"]').val(data['list'].jml_tsn);				
			$('[name="tso"]').val(data['list'].jml_tso);				
			
			var item_sel=["filter_description","filter_manufacturer","filter_posisi"];
			var item_select = {"filter_description":data['list'].kat_component_id,"filter_manufacturer":data['list'].manufacturer_id,"filter_posisi":data['list'].posisi_pic_id};
			
			select_box(data,item_select, item_sel);		
															
			$('#modalFormAddKomponen').modal('show'); // show bootstrap modal
			$('.modal-title').text('Update Komponen'); // Set Title to Bootstrap modal title
			
			loadThemeSelect2();
			loadThemeDatepicker();
			loadThemeDesTouchspin();				
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			alert('Error get data from ajax');
		}
	});
}

var loadFormAddIntKomponen = function(komponen_id) {
	var proyek_id = document.getElementById("proyek_id").value;
	save_method = 'add';
	$('#modalFormAddIntKomponen [name="save_method"]').val(save_method);  
	$('#modalFormAddIntKomponen [name="komponen_id"]').val(komponen_id);  
	$('#add_form_int_komponen')[0].reset(); // reset form on modals		  
	$('#modal_message').html('');  //reset message					 			  
	//document.getElementById('ref_id').disabled = false;
	//document.getElementById('url').disabled = false;
	//$('#modalFormAttachFileAgenda').modal('show');
  
	//Ajax Load data from ajax
	$.ajax({
		url : base_url+"program/data_form_add_int_komponen",
		type: "POST",
		dataType: "JSON",
		data: {proyek_id:proyek_id},
		success: function(data)
		{
			var item_sel=["filter_interval"];
			var item_select = {"filter_interval":-1};															
			select_box(data,item_select, item_sel);		
															
			$('#modalFormAddIntKomponen').modal('show'); // show bootstrap modal
			$('.modal-title').text('Tambah Aircraft Interval'); // Set Title to Bootstrap modal title
			
			loadThemeSelect2();
			loadThemeIntTouchspin();

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

var loadFormTblTsnReport = function(komponen_id) {
	$('#modalFormTsnReport').modal('show'); // show bootstrap modal
	$('.modal-static-title').text('Table TSN History'); // Set Title to Bootstrap modal title
  
	// begin first table
	tbl_form_tsn_aircraft_report = $('#tbl_form_tsn_aircraft_report').DataTable({
		processing: true, //Feature control the processing indicator.
		serverSide: true, //Feature control DataTables' server-side processing mode.
		
		// Load data for the table's content from an Ajax source
		ajax: {
			"url": base_url+'program/list_form_tsn_aircraft_report',
			"type": "POST",
			"data": {komponen_id:komponen_id},
		},
		
		responsive: true,

		// DOM Layout settings
		dom: `<'row'<'col-sm-12'tr>>
		<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,

		lengthMenu: [5, 10, 25, 50],

		pageLength: 10,

		language: {
			'lengthMenu': 'Display _MENU_',
		},

		// Order settings
		order: [[0, 'asc']],

		headerCallback: function(thead, data, start, end, display) {
			// thead.getElementsByTagName('th')[0].innerHTML = `
				// <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
					// <input type="checkbox" value="" class="m-group-checkable">
					// <span></span>
				// </label>`;
		},

		columnDefs: [
			{
				targets: [ 0, -1],
				width: '10px',
				className: 'dt-center',
				orderable: false,
				// render: function(data, type, full, meta) {
					// return `
					// <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
						// <input type="checkbox" value="" class="m-checkable">
						// <span></span>
					// </label>`;
				// },
			},
			{
				targets: -1,
				title: 'Action',
				orderable: false,
			},
		],
		
		destroy: true,
	});				
}

var loadFormAddTask = function() {
	var proyek_id = document.getElementById("proyek_id").value;
	save_method = 'add';
	$('#modalFormAddTask [name="save_method"]').val(save_method);  
	$('#add_form_task')[0].reset(); // reset form on modals		  
	$('#modal_message').html('');  //reset message					 			  
	//document.getElementById('ref_id').disabled = false;
	//document.getElementById('url').disabled = false;
	//$('#modalFormAttachFileAgenda').modal('show');
  
	//Ajax Load data from ajax
	$.ajax({
		url : base_url+"program/data_form_add_task",
		type: "POST",
		dataType: "JSON",
		data: {proyek_id:proyek_id},
		success: function(data)
		{
			var item_sel=["filter_description_task","filter_posisi_task"];
			var item_select = {"filter_description_task":-1,"filter_posisi_task":-1};															
			select_box(data,item_select, item_sel);		
															
			$('#modalFormAddTask').modal('show'); // show bootstrap modal
			$('.modal-title').text('Tambah Task'); // Set Title to Bootstrap modal title
			
			loadThemeSelect2();
			loadThemeDatepicker();
			loadThemeIntTouchspin();

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

var loadFormUpdateTask = function(id) 
{
  var proyek_id = document.getElementById("proyek_id").value;
  save_method = 'update';
  $('#add_form_task')[0].reset(); // reset form on modals		  
  $('#modal_message').html('');  //reset message					
  $('#modalFormAddTask [name="save_method"]').val(save_method);  
			
  //Ajax Load data from ajax
  $.ajax({			  		
		url : base_url+'program/data_form_update_task/' + id,
		type: "GET",
		dataType: "JSON",
		data: {proyek_id:proyek_id},
		success: function(data)
		{				   
			$('[name="id"]').val(data['list'].id);  
			$('[name="date_of_issue"]').val(data['list'].date_of_issue);  
			$('[name="interval"]').val(data['list'].lama_interval);				

			var item_sel=["filter_description_task","filter_posisi_task"];
			var item_select = {"filter_description_task":data['list'].komponen_kategori_id,"filter_posisi_task":data['list'].posisi_pic_id};															
			select_box(data,item_select, item_sel);		
															
			$('#modalFormAddTask').modal('show'); // show bootstrap modal
			$('.modal-title').text('Update Task'); // Set Title to Bootstrap modal title
			
			loadThemeSelect2();
			loadThemeDatepicker();
			loadThemeIntTouchspin();				
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			alert('Error get data from ajax');
		}
	});
}

var loadFormAddItem = function() {
	var proyek_id = document.getElementById("proyek_id").value;
	save_method = 'add';
	$('#modalFormAddItem [name="save_method"]').val(save_method);  
	$('#add_form_item')[0].reset(); // reset form on modals		  
	$('#modal_message').html('');  //reset message					 			  
	
	$('#modalFormAddItem').modal('show'); // show bootstrap modal
	$('.modal-title').text('Tambah Item'); // Set Title to Bootstrap modal title
	
	loadSelectItem(1);
	$('#add_form_item #kategori_id').change(function(){
		loadSelectItem($(this).val());
	});
	
	loadThemeSelect2();
}

var loadFormInsTaskReport = function(task_id) {
	//save_method = 'add';
	$('#add_form_ins_task_report')[0].reset(); // reset form on modals		  
	$('#modal_message').html('');  //reset message					 			  
	//document.getElementById('ref_id').disabled = false;
	//document.getElementById('url').disabled = false;
	//$('#modalFormAttachFileAgenda').modal('show');
	$('#add_form_ins_task_report [name="task_id"]').val(task_id);  
  
	//Ajax Load data from ajax
	$.ajax({
		url : base_url+"program/data_form_ins_task_report",
		type: "POST",
		dataType: "JSON",
		data: {task_id:task_id},
		success: function(data)
		{
			// var item_sel=["ref_id"];
			// var item_select = {"ref_id":-1};															
			// select_box(data,item_select, item_sel);		
															
			$('#modalFormInsTaskReport').modal('show'); // show bootstrap modal
			$('.modal-static-title').text('Inspection Task Report - '+data.nama_komponen); // Set Title to Bootstrap modal title
			
			loadThemeDatepicker();
			loadFormTableInsTaskReport(task_id);

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

var loadFormInsKomponenReport = function(komponen_id) {
	//save_method = 'add';
	$('#add_form_ins_komponen_report')[0].reset(); // reset form on modals		  
	$('#modal_message').html('');  //reset message		
	$('#add_form_ins_komponen_report [name="komponen_id"]').val(komponen_id); 

	$('#modalFormInsKomponenReport').modal('show'); // show bootstrap modal
	$('.modal-title').text('Inspection Aircraft Report'); // Set Title to Bootstrap modal title
	
	loadThemeDatepicker();			
}

var loadFormTableInsTaskReport = function(task_id) {

	// begin first table
	form_tbl_ins_task_report = $('#form_tbl_ins_task_report').DataTable({
		processing: true, //Feature control the processing indicator.
		serverSide: true, //Feature control DataTables' server-side processing mode.
		
		// Load data for the table's content from an Ajax source
		ajax: {
			"url": base_url+'program/list_form_ins_task_report',
			"type": "POST",
			"data": {task_id:task_id},
		},
		
		responsive: true,

		// DOM Layout settings
		dom: `<'row'<'col-sm-12'tr>>
		<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,

		lengthMenu: [5, 10, 25, 50],

		pageLength: 10,

		language: {
			'lengthMenu': 'Display _MENU_',
		},

		// Order settings
		order: [[0, 'asc']],

		headerCallback: function(thead, data, start, end, display) {
			// thead.getElementsByTagName('th')[0].innerHTML = `
				// <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
					// <input type="checkbox" value="" class="m-group-checkable">
					// <span></span>
				// </label>`;
		},

		columnDefs: [
			{
				targets: [0,-1],
				width: '30px',
				className: 'dt-center',
				orderable: false,
				// render: function(data, type, full, meta) {
					// return `
					// <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
						// <input type="checkbox" value="" class="m-checkable">
						// <span></span>
					// </label>`;
				// },
			},
			{
				targets: -1,
				title: 'Action',
				orderable: false,
			},
		],
		
		destroy: true,
	});
}

var loadSelectItem = function(kategori_id) {
	var proyek_id = document.getElementById("proyek_id").value;
	//Ajax Load data from ajax
	$.ajax({
		url : base_url+"program/data_select_item",
		type: "POST",
		dataType: "JSON",
		data: {kategori_id:kategori_id, proyek_id:proyek_id},
		success: function(data)
		{
			var item_sel=["filter_item"];
			var item_select = {"filter_item":-1};															
			select_box(data,item_select, item_sel);					
			loadThemeSelect2();
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

function approval_tsn_komponen(id, nama, judul, file_id)
{
	if(id != ''){
		//show modal confirmation
		$('#approve_tsn_komponen')[0].reset(); // reset form on modals
		$('#approve_tsn_komponen #modal_approval_message').html('');  //reset message
		
		$('#approve_tsn_komponen [name="komponen_tsn_id"]').val(id);
		$('#approve_tsn_komponen #approval_text').html('<b >Laporan dari ' + nama + '</b><br />Dengan judul: <br />' + judul);
		
		var form_data = {
			file_id: file_id					
		};
		
		$.ajax({
				url : base_url+"program/show_tsn_komponen",
				type: "POST",
				dataType: "JSON",
				data: form_data,
				success: function(data)
				{	
					pdf_file = 	data['filename_path'];																						
					document.getElementById('pdf_frame').src = data['filename_url'];																										
					$('#modalApprovalTsnKomponen').modal('show'); // show bootstrap modal when complete loaded		
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
		var form = document.getElementById('approve_tsn_komponen');					  
		var form_data = new FormData(form);	
		
		// ajax hapus data to database
		$.ajax({
			url : base_url+"program/approval_tsn_komponen",
			type: "POST",
			data: form_data,
			processData: false,
			contentType: false,
			dataType: "JSON",
			success: function(data)
			{
				if(data.status=='success'){
					$('#modalApprovalTsnKomponen').modal('hide');	
					reload_table();
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

function approval_ins_komponen(id, nama, judul, file_id)
{
	if(id != ''){
		//show modal confirmation
		$('#approve_ins_komponen')[0].reset(); // reset form on modals
		$('#approve_ins_komponen #modal_approval_message').html('');  //reset message
		
		$('#approve_ins_komponen [name="komponen_inspeksi_id"]').val(id);
		$('#approve_ins_komponen #approval_text').html('<b >Laporan dari ' + nama + '</b><br />Dengan judul: <br />' + judul);
		
		var form_data = {
			file_id: file_id					
		};
		
		$.ajax({
				url : base_url+"program/show_ins_komponen",
				type: "POST",
				dataType: "JSON",
				data: form_data,
				success: function(data)
				{	
					pdf_file = 	data['filename_path'];																						
					document.getElementById('pdf_frame').src = data['filename_url'];																										
					$('#modalApprovalInsKomponen').modal('show'); // show bootstrap modal when complete loaded		
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
		var form = document.getElementById('approve_ins_komponen');					  
		var form_data = new FormData(form);	
		
		// ajax hapus data to database
		$.ajax({
			url : base_url+"program/approval_ins_komponen",
			type: "POST",
			data: form_data,
			processData: false,
			contentType: false,
			dataType: "JSON",
			success: function(data)
			{
				if(data.status=='success'){
					$('#modalApprovalInsKomponen').modal('hide');	
					reload_table();
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

function approval_ins_task(id, nama, judul, file_id)
{
	if(id != ''){
		//show modal confirmation
		$('#approve_ins_task')[0].reset(); // reset form on modals
		$('#approve_ins_task #modal_approval_message').html('');  //reset message
		
		$('#approve_ins_task [name="task_inspeksi_id"]').val(id);
		$('#approve_ins_task #approval_text').html('<b >Laporan dari ' + nama + '</b><br />Dengan judul: <br />' + judul);
		
		var form_data = {
			file_id: file_id					
		};
		
		$.ajax({
				url : base_url+"program/show_ins_task",
				type: "POST",
				dataType: "JSON",
				data: form_data,
				success: function(data)
				{	
					pdf_file = 	data['filename_path'];																						
					document.getElementById('pdf_frame').src = data['filename_url'];																										
					$('#modalApprovalInsTask').modal('show'); // show bootstrap modal when complete loaded		
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
		var form = document.getElementById('approve_ins_task');					  
		var form_data = new FormData(form);	
		
		// ajax hapus data to database
		$.ajax({
			url : base_url+"program/approval_ins_task",
			type: "POST",
			data: form_data,
			processData: false,
			contentType: false,
			dataType: "JSON",
			success: function(data)
			{
				if(data.status=='success'){
					$('#modalApprovalInsTask').modal('hide');	
					reload_table();
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

function save_tsn_aircraft_report() {
	var proyek_id = document.getElementById("proyek_id").value;
	var form = document.getElementById('add_form_tsn_aircraft_report');					  
	var form_data = new FormData(form);	
	form_data.append("proyek_id", proyek_id);
			
	// ajax adding data to database
	$.ajax({
		url : base_url+"program/data_save_tsn_aircraft_report",
		type: "POST",
		data: form_data,
		processData: false,
		contentType: false,
		dataType: "JSON",
		success: function(data)
		{
			if(data.status=='success'){
				//saved data																
				$('#modalFormAddTSNAircraftReport').modal('hide');	
				reload_table();
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

function saveKomponen() {
	var proyek_id = document.getElementById("proyek_id").value;
	var form = document.getElementById('add_form_komponen');					  
	var form_data = new FormData(form);	
	form_data.append("proyek_id", proyek_id);
			
	// ajax adding data to database
	$.ajax({
		url : base_url+"program/data_save_komponen",
		type: "POST",
		data: form_data,
		processData: false,
		contentType: false,
		dataType: "JSON",
		success: function(data)
		{
			if(data.status=='success'){
				//saved data																
				$('#modalFormAddKomponen').modal('hide');	
				reload_table();
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

function saveIntKomponen() {
	var form = document.getElementById('add_form_int_komponen');					  
	var form_data = new FormData(form);	
			
	// ajax adding data to database
	$.ajax({
		url : base_url+"program/data_save_int_komponen",
		type: "POST",
		data: form_data,
		processData: false,
		contentType: false,
		dataType: "JSON",
		success: function(data)
		{
			if(data.status=='success'){
				//saved data																
				$('#modalFormAddIntKomponen').modal('hide');	
				reload_table();
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

function saveInsKomponenReport() {
	var form = document.getElementById('add_form_ins_komponen_report');					  
	var form_data = new FormData(form);	
			
	// ajax adding data to database
	$.ajax({
		url : base_url+"program/data_save_ins_komponen_report",
		type: "POST",
		data: form_data,
		processData: false,
		contentType: false,
		dataType: "JSON",
		success: function(data)
		{
			if(data.status=='success'){
				//saved data																
				$('#modalFormInsKomponenReport').modal('hide');	
				reload_table();
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

function saveTask() {
	var proyek_id = document.getElementById("proyek_id").value;
	var form = document.getElementById('add_form_task');					  
	var form_data = new FormData(form);	
	form_data.append("proyek_id", proyek_id);
			
	// ajax adding data to database
	$.ajax({
		url : base_url+"program/data_save_task",
		type: "POST",
		data: form_data,
		processData: false,
		contentType: false,
		dataType: "JSON",
		success: function(data)
		{
			if(data.status=='success'){
				//saved data																
				$('#modalFormAddTask').modal('hide');	
				reload_table();
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

function saveInsTaskReport() {
	var form = document.getElementById('add_form_ins_task_report');					  
	var form_data = new FormData(form);	
			
	// ajax adding data to database
	$.ajax({
		url : base_url+"program/data_save_ins_task_report",
		type: "POST",
		data: form_data,
		processData: false,
		contentType: false,
		dataType: "JSON",
		success: function(data)
		{
			if(data.status=='success'){
				//saved data																
				//$('#modalFormInsTaskReport').modal('hide');	
				$('#add_form_ins_task_report')[0].reset();
				reload_table();
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


function saveItem() {
	var form = document.getElementById('add_form_item');					  
	var form_data = new FormData(form);	
			
	// ajax adding data to database
	$.ajax({
		url : base_url+"program/data_save_item",
		type: "POST",
		data: form_data,
		processData: false,
		contentType: false,
		dataType: "JSON",
		success: function(data)
		{
			if(data.status=='success'){
				//saved data																
				$('#modalFormAddItem').modal('hide');	
				scheduling();
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

var data_delete = function (id, nama_id, tablename){
	if(id != ''){
		//show modal confirmation
		$('#delete_form')[0].reset(); // reset form on modals
		$('#modal_delete_message').html('');  //reset message
		
		$('[name="id_delete_data"]').val(id);
		$('[name="tablename"]').val(tablename);
		$('#delete_text').html('<b >Hapus data ' + nama_id + '</b>');	
		$('#modalDeleteForm').modal('show'); // show bootstrap modal when complete loaded
		$('.modal-title').text('Hapus Data'); // Set Title to Bootstrap modal title	
	}else{
		//lakukan hapus data
		// ajax hapus data to database
		$.ajax({
			url: base_url+"program/data_delete",
			type: "POST",
			data: $('#delete_form').serialize(),
			dataType: "JSON",
			success: function(data)
			{
				if(data.status=='success'){
					toastr.success(data.message);
					$('#modalDeleteForm').modal('hide');	
					
					reload_table();
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

var loadThemeSelect2 = function() {
	// untuk select2
	$('.select2').select2({
		width: "100%"
	});
}

var loadThemeDatepicker = function() {
	// untuk datepicker
	var arrows;
	if (KTUtil.isRTL()) {
		arrows = {
			leftArrow: '<i class="la la-angle-right"></i>',
			rightArrow: '<i class="la la-angle-left"></i>'
		}
	} else {
		arrows = {
			leftArrow: '<i class="la la-angle-left"></i>',
			rightArrow: '<i class="la la-angle-right"></i>'
		}
	}
	$('.datepicker').datepicker({
		rtl: KTUtil.isRTL(),
		todayHighlight: true,
		orientation: "bottom left",
		format: "yyyy-mm-dd",
		templates: arrows
	});
}

var loadThemeDesTouchspin = function() {
	// untuk desimal touchspin
        $('.destouchspin').TouchSpin({
            buttondown_class: 'btn btn-secondary',
            buttonup_class: 'btn btn-secondary',

            min: 0,
			max: 100000,
            step: 0.1,
            decimals: 2,
            boostat: 5,
            maxboostedstep: 10,
        });
}

var loadThemeIntTouchspin = function() {
	// untuk desimal touchspin
        $('.touchspin').TouchSpin({
            buttondown_class: 'btn btn-secondary',
            buttonup_class: 'btn btn-secondary',

            min: 0,
			max: 100000,
            boostat: 5,
            maxboostedstep: 10,
        });
}