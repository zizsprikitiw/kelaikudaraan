var loadContent = function() {
	loadSelectModul();
	loadSelectModulDrive();
	loadDashboardStat();
	loadTaskChart();
	loadTaskTimeline();
	loadTimeline();
	organisasiChart();
	calendarProgram();
}

var loadTaskChart = function() {
	var e = document.getElementById("filter_proyek");
	var proyek_selected = e.options[e.selectedIndex].value;
	var el = $('#task-chart').parents('.portlet').find('.portlet-body');	
	
	var chart = AmCharts.makeChart("task-chart", {
	  "theme": "light",
	  "type": "serial",
	  "startDuration": 1,
	  "valueAxes": [{
		"minimum": 0,
		"maximum": 100,
		"axisAlpha": 0,
		"position": "left",
		"title": "Persentage of Completed (%)"
	  }],
	  //"dataProvider": [],
	  "graphs": [{
		"balloonText": "[[category]]: <b>[[value]]%</b>",
		"fillColorsField": "color",
		"fillAlphas": 0.9,
		"lineAlpha": 0.2,
		"type": "column",
		"valueField": "persen"
	  }],
	  "chartCursor": {
		"categoryBalloonEnabled": false,
		"cursorAlpha": 0,
		"zoomable": false
	  },
	  "categoryField": "modul",
	  "categoryAxis": {
		"gridPosition": "start",
		"labelRotation": 45
	  },
	  "export": {
		"enabled": true
	  }

	});
	
	$.ajax({
		url : base_url+"index/data_task_chart" ,
		type: "POST",
		dataType: "JSON",
		data: {"proyek_id":proyek_selected},
		beforeSend: function() 
		{    
			App.blockUI({
				target: el,
				animate: true,
				overlayColor: 'none'
			});
		},
		success: function(data)
		{
			App.unblockUI(el);
			
			chart.dataProvider = data.data_task_chart;
			chart.validateData();
		},
		error: function (jqXHR, exception) {
			App.unblockUI(el);
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
	
//function load task timeline
var loadTaskTimeline = function() {
	var e = document.getElementById("filter_proyek");
	var proyek_selected = e.options[e.selectedIndex].value;
	var el = $('#task-timeline').parents('.portlet').find('.portlet-body');	
	$.ajax({
		url : base_url+"index/data_task_timeline" ,
		type: "POST",
		dataType: "JSON",
		data: {"proyek_id":proyek_selected},
		beforeSend: function() 
		{    
			App.blockUI({
				target: el,
				animate: true,
				overlayColor: 'none'
			});
		},
		success: function(data)
		{
			App.unblockUI(el);
			
			// create a data set with groups
			var group = data.data_task_group;
			var groups = new vis.DataSet();
			for (var g = 0; g < group.length; g++) {
				groups.add({id: group[g]['id'], content: group[g]['nama']});
			}
			
			// create a dataset with items
            items = new vis.DataSet();
            items.add(data.data_task_timeline);
			// var today = new Date(new Date().setHours(0,0,0,0));
			// var tomorrow = new Date(new Date().setHours(23,59,59,999)+1);
			var today = new Date((new Date()).valueOf() - (3000 * 60 * 60 * 24));
			var tomorrow = new Date(12000 * 60 * 60 * 24 + (new Date()).valueOf());

            var options = {
				orientation: 'top',
                align: 'left',
				stack: true,
				horizontalScroll: true,
				//zoomKey: 'ctrlKey',
				//zoomMax: 1000 * 60 * 60 * 24*31*3, 
				start:today,
				end:tomorrow,
				maxHeight: 500,
				margin: {
				  item: 10, // minimal margin between items
				  axis: 5   // minimal margin between items and the axis
				},
                groupOrder: 'content',  // groupOrder can be a property name or a sorting function
				tooltip: {
				  followMouse: true
				}
            };
            var container = document.getElementById('task-timeline');
            timeline = new vis.Timeline(container, items, groups, options);
		},
		error: function (jqXHR, exception) {
			App.unblockUI(el);
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
	
	/* var now = moment().minutes(0).seconds(0).milliseconds(0);
	  var groupCount = 3;
	  var itemCount = 20;

	  // create a data set with groups
	  var names = ['John', 'Alston', 'Lee', 'Grant'];
	  var groups = new vis.DataSet();
	  for (var g = 0; g < groupCount; g++) {
		groups.add({id: g, content: names[g]});
	  }

	  // create a dataset with items
	  var items = new vis.DataSet();
	  for (var i = 0; i < itemCount; i++) {
		var start = now.clone().add(Math.random() * 200, 'hours');
		var group = Math.floor(Math.random() * groupCount);
		items.add({
		  id: i,
		  group: group,
		  content: 'item ' + i +
			  ' <span style="color:#97B0F8;">(' + names[group] + ')</span>',
		  start: start,
		  type: 'box',
		  title: 'Normal text'
		});
	  }

	  // create visualization
	  var container = document.getElementById('tooltips-follow');
	  var options = {
		groupOrder: 'content',  // groupOrder can be a property name or a sorting function
		tooltip: {
		  followMouse: true
		}
	  };

	  var timeline = new vis.Timeline(container);
	  timeline.setOptions(options);
	  timeline.setGroups(groups);
	  timeline.setItems(items);*/
	  
	 /* 
	// Create a DataSet (allows two way data-binding)
	  var items = new vis.DataSet([
		{id: 1, content: 'Item 1', start: '2016-01-01', end: '2016-01-02',
		  title: 'Normal text'},
		{id: 2, content: 'Item 2', start: '2016-01-02', title: '<b>Bold</b>'},
		{id: 3, content: 'Item 3', start: '2016-01-03', type: 'point',
		  title: '<span style="color: red">Red</span> text'},
		{id: 4, content: '<h1>HTML</h1> Item', start: '2016-01-03', end: '2016-01-04',
		  title: '<table border="1"><tr><td>Cell 1</td><td>Cell 2</td></tr></table>'}
	  ]);


	  // Follow options
	  var follow_options = {
		tooltip: {
		  followMouse: true
		}
	  };

	  var timelineFollow = new vis.Timeline(document.getElementById('tooltips-follow'),
		  items, follow_options);
		  */
}	

//function load select filter
var loadSelectFilter = function() {
	$.ajax({
		url : base_url+"index/data_init" ,
		type: "POST",
		dataType: "JSON",
		success: function(data)
		{
			var d = new Date();
			var item_sel=["filter_proyek_year"];
			var item_select = {"filter_proyek_year":d.getFullYear()};			
			select_box(data,item_select, item_sel);	
			
			var e = document.getElementById("filter_proyek_year");
			var year_selected = e.options[e.selectedIndex].text;
			var tahun = e.options[e.selectedIndex].value;
			loadSelectProyek(tahun);
			
			$('#filter_proyek').change(function(){
				loadSelectProyek(this.value);
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
	
	$('#form-filter-program').submit(function(){
		var e = document.getElementById("filter_proyek_year");
		var year_selected = e.options[e.selectedIndex].text;
		var e = document.getElementById("filter_proyek");
		var proyek_selected = e.options[e.selectedIndex].text;
		
		$('#proyek_selected span').html(year_selected+' - Program '+proyek_selected);
		loadContent();
	});
	
	$('.close-toggle').each(function(){
		$(this).click(function(){
			$(this).parents('.btn-group').find('[data-toggle="dropdown"]').dropdown('toggle');
		});
	});
}

//function load select proyek
var loadSelectProyek = function(tahun) {
	$.ajax({
		url : base_url+"index/data_select_proyek" ,
		type: "POST",
		dataType: "JSON",
		data: {"tahun":tahun},
		success: function(data)
		{
			var d = new Date();
			var item_sel=["filter_proyek"];
			var item_select = {"filter_proyek":-1};			
			select_box(data,item_select, item_sel);	
			
			var e = document.getElementById("filter_proyek_year");
			var year_selected = e.options[e.selectedIndex].text;			
			var e = document.getElementById("filter_proyek");
			var proyek_selected = e.options[e.selectedIndex].text;
			
			
			$('#proyek_selected span').html(year_selected+' - Program '+proyek_selected);
			$('.caption .caption-helper').html(year_selected+' - Program '+proyek_selected);
			
			loadContent();
			
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
var loadSelectModul = function() {
	var e = document.getElementById("filter_proyek");
	var proyek_selected = e.options[e.selectedIndex].value;
	
	$.ajax({
		url : base_url+"index/data_select_modul" ,
		type: "POST",
		dataType: "JSON",
		data: {"proyek_id":proyek_selected},
		success: function(data)
		{				
			$('#filter_modul').html(data.filter_modul);
			$('#filter_modul li').each(function(){
				if ($(this).hasClass('active')){
					var modul_id = $(this).data('id');
					var modul_name = $(this).data('name');
					$('#tasks_caption').html(modul_name);
					//loadTask(modul_id);
					loadTabelTask(modul_id);
				}

				$(this).click(function(){
					var modul_id = $(this).data('id');
					var modul_name = $(this).data('name');
					$('#filter_modul li').removeClass("active");
					$(this).addClass("active");
					$('#tasks_caption').html(modul_name);
					//loadTask(modul_id);
					loadTabelTask(modul_id);
				});
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

//function load select modul drive
var loadSelectModulDrive = function() {
	var e = document.getElementById("filter_proyek");
	var proyek_selected = e.options[e.selectedIndex].value;
	
	$.ajax({
		url : base_url+"index/data_select_modul_drive" ,
		type: "POST",
		dataType: "JSON",
		data: {"proyek_id":proyek_selected},
		success: function(data)
		{				
			$('#filter_modul_drive').html(data.filter_modul_drive);
			$('#filter_modul_drive li').each(function(){
				if ($(this).hasClass('active')){
					var modul_id = $(this).data('id');
					var modul_name = $(this).data('name');
					$('#drive_caption').html(modul_name);
					loadTabelDrive(modul_id);
				}

				$(this).click(function(){
					var modul_id = $(this).data('id');
					var modul_name = $(this).data('name');
					$('#filter_modul_drive li').removeClass("active");
					$(this).addClass("active");
					$('#drive_caption').html(modul_name);
					loadTabelDrive(modul_id);
				});
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

//function load task
// var loadTask = function(modul_id) {
	// var e = document.getElementById("filter_proyek");
	// var proyek_selected = e.options[e.selectedIndex].value;
	// var el = $('#tasks-content').parents('.portlet').find('.portlet-body');		
	
	// $.ajax({
		// url : base_url+"index/data_task",
		// type: "POST",
		// dataType: "JSON",
		// data: {"modul_id":modul_id},
		// beforeSend: function() 
		// {    
			// App.blockUI({
				// target: el,
				// animate: true,
				// overlayColor: 'none'
			// });
		// },
		// success: function(data)
		// {				
			// App.unblockUI(el);
			// var cont = $('#tasks-content');
			// cont.html(data.tasks);
			
			// var div_height = document.getElementById('tasks-content').offsetHeight;
			// div_height = div_height>350?350:div_height;
			
			// cont.slimScroll({
				// start : 'top',
				// height: div_height,
			// });
			
			// $('[data-toggle="tooltip"]').tooltip();
		// },
		// error: function (jqXHR, exception) {
			// App.unblockUI(el);
			// var msgerror = ''; 
			// if (jqXHR.status === 0) {
				// msgerror = 'jaringan tidak terkoneksi.';
			// } else if (jqXHR.status == 404) {
				// msgerror = 'Halamam tidak ditemukan. [404]';
			// } else if (jqXHR.status == 500) {
				// msgerror = 'Internal Server Error [500].';
			// } else if (exception === 'parsererror') {
				// msgerror = 'Requested JSON parse gagal.';
			// } else if (exception === 'timeout') {
				// msgerror = 'RTO.';
			// } else if (exception === 'abort') {
				// msgerror = 'Gagal request ajax.';
			// } else {
				// msgerror = 'Error.\n' + jqXHR.responseText;
			// }
			// toastr.error("Error System", msgerror, 'error');
		// }		
	// });
// }

var loadDashboardStat = function() {
	var e = document.getElementById("filter_proyek");
	var proyek_selected = e.options[e.selectedIndex].value;
	
	$.ajax({
		url : base_url+"index/data_dashboard_stat" ,
		type: "POST",
		dataType: "JSON",
		data: {"proyek_id":proyek_selected},
		success: function(data)
		{
			$('#count_member').html(data.count_member);
			$('#count_wp').html(data.count_wp);
			$('#count_tasks').html(data.count_tasks);
			$('#avg_progress').html(data.avg_progress);
			$('[data-counter="counterup"]').counterUp({
				delay: 10,
				time: 1000
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

var loadTimeline = function() {
	var e = document.getElementById("filter_proyek");
	var proyek_selected = e.options[e.selectedIndex].value;
	var el = $('#timeline-program').parents('.portlet').find('.portlet-body');	
	
	$.ajax({
		url : base_url+"index/data_timeline" ,
		type: "POST",
		dataType: "JSON",
		data: {"proyek_id":proyek_selected},
		beforeSend: function() 
		{    
			App.blockUI({
				target: el,
				animate: true,
				overlayColor: 'none'
			});
		},
		success: function(data)
		{
			App.unblockUI(el);
			$('#timeline-program').html(data.deskripsi);
			$('#timeline-program [data-id="'+data.sel_id+'"]').each(function(){
				$(this).addClass("selected");
			});
			jqueryTimeline();
		},
		error: function (jqXHR, exception) {
			App.unblockUI(el);
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

var organisasiChart = function() {
	var e = document.getElementById("filter_proyek");
	var proyek_selected = e.options[e.selectedIndex].value;
	var el = $('#people').parents('.portlet').find('.portlet-body');	
			
	var peopleElement = document.getElementById("people");
	var orgChart = new getOrgChart(peopleElement, {
		enableDetailsView: false,
		enableEdit: false,
		enableZoom: false,
		enableMove: false,
		enableSearch: false,
		enableExportToImage: true,
		primaryFields: ["name", "title", "phone", "mail"],
		photoFields: ["image"],
		expandToLevel: 100,		
		color: "lightblue",			
		layout: getOrgChart.MIXED_HIERARCHY_RIGHT_LINKS
	});
	
	$.ajax({
		url : base_url+"index/data_struktur_organisasi" ,
		type: "POST",
		dataType: "JSON",
		data: {"proyek_id":proyek_selected},
		beforeSend: function() 
		{    
			App.blockUI({
				target: el,
				animate: true,
				overlayColor: 'none'
			});
		},
		success: function(data)
		{
			App.unblockUI(el);
			orgChart.loadFromJSON(data.data_struktur);
			//setInterval(function(){ $('a[title="GetOrgChart jquery plugin"]').hide(); }, 10);
			
		},
		error: function (jqXHR, exception) {
			App.unblockUI(el);
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

var calendarProgram = function() {
	var e = document.getElementById("filter_proyek");
	var proyek_selected = e.options[e.selectedIndex].value;
	
	if (!jQuery().fullCalendar) {
		return;
	}

	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();

	var h = {};

	if ($('#kalender').width() <= 400) {
		$('#kalender').addClass("mobile");
		h = {
			left: 'title, prev, next',
			center: '',
			right: 'today,month,agendaWeek,agendaDay'
		};
	} else {
		$('#kalender').removeClass("mobile");
		if (App.isRTL()) {
			h = {
				right: 'title',
				center: '',
				left: 'prev,next,today,month,agendaWeek,agendaDay'
			};
		} else {
			h = {
				left: 'title',
				center: '',
				right: 'prev,next,today,month,agendaWeek,listWeek'
			};
		}
	}
	
	$('#kalender').fullCalendar('destroy'); // destroy the calendar
	$('#kalender').fullCalendar({
		header: h,
		timeFormat: 'H:mm',
		locale: 'id',
		disableDragging: false,
		editable: false,
		//eventLimit: true,
		events: function (start, end, timezone, callback) {		
			var el = $('#kalender').parents('.portlet').find('.portlet-body');	
			
			$.ajax({
				type: 'POST',
				url: 'index/data_kalender',
				dataType:'json',
				crossDomain: true,
				data: {
					start: Math.round(new Date(start).getTime()/1000),
					end: Math.round(new Date(end).getTime()/1000),        
					proyek_id: proyek_selected,        
				},
				beforeSend: function() 
				{    
					App.blockUI({
						target: el,
						animate: true,
						overlayColor: 'none'
					});
				},
				success: function(data) {     
					App.unblockUI(el);
					
					var events = [];
					var allday = null; //Workaround
					
					$.each(data.event, function(i, item) {
						// if($(this).attr('allDay') == "false") //Workaround 
								// allday = false; //Workaround 
						// if($(this).attr('allDay') == "true") //Workaround 
								// allday = true; //Workaround                     
						
						//alert(new Date(item.end) + new Date(y, m, 1));
						
						events.push({
							id: item.id,
							title: item.title,
							start: new Date(item.start),
							end: new Date(item.end),   
							backgroundColor: item.color,						
							allDay: false,
						});            
					});  
					callback(events);
				},
				error: function (jqXHR, exception) {
					App.unblockUI(el);
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
		},
		eventClick: function(eventObj) {
			loadDetailAgenda(eventObj.id);
		},
	});
		
	//console.log(test);

	/*$('#kalender').fullCalendar('destroy'); // destroy the calendar
	$('#kalender').fullCalendar({ //re-initialize the calendar
		disableDragging: false,
		header: h,
		editable: true,
		events: [{
			title: 'All Day',
			start: new Date(y, m, 1),
			backgroundColor: App.getBrandColor('yellow')
		}, {
			title: 'Long Event',
			start: new Date(y, m, d - 5),
			end: new Date(y, m, d - 2),
			backgroundColor: App.getBrandColor('blue')
		}, {
			title: 'Repeating Event',
			start: new Date(y, m, d - 3, 16, 0),
			allDay: false,
			backgroundColor: App.getBrandColor('red')
		}, {
			title: 'Repeating Event',
			start: new Date(y, m, d + 6, 16, 0),
			allDay: false,
			backgroundColor: App.getBrandColor('green')
		}, {
			title: 'Meeting',
			start: new Date(y, m, d + 9, 10, 30),
			allDay: false
		}, {
			title: 'Lunch',
			start: new Date(y, m, d, 14, 0),
			end: new Date(y, m, d, 14, 0),
			backgroundColor: App.getBrandColor('grey'),
			allDay: false
		}, {
			title: 'Birthday',
			start: new Date(y, m, d + 1, 19, 0),
			end: new Date(y, m, d + 1, 22, 30),
			backgroundColor: App.getBrandColor('purple'),
			allDay: false
		}, {
			title: 'Click for Google',
			start: new Date(y, m, 28),
			end: new Date(y, m, 29),
			backgroundColor: App.getBrandColor('yellow'),
			url: 'http://google.com/'
		}]
	});*/
}

var loadDetailAgenda = function(agenda_id) { 
	//Ajax Load data from ajax
	$.ajax({
		url : base_url+"index/data_form_file_agenda",
		type: "POST",
		dataType: "JSON",
		data: {agenda_id:agenda_id},
		success: function(data)
		{
															
			$('#modalDetailAgenda').modal('show'); // show bootstrap modal
			$('.modal-title').text(data.nama_agenda); // Set Title to Bootstrap modal title
			$('#tanggal_agenda').text(data.tanggal); // Set Title to Bootstrap modal title
			$('#lokasi_agenda').text(data.lokasi); // Set Title to Bootstrap modal title
			$('#deskripsi_agenda').text(data.deskripsi); // Set Title to Bootstrap modal title
			
			loadTabelFileDetailAgenda(agenda_id);

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

var jqueryTimeline = function() {
	var timelines = $('.cd-horizontal-timeline');
	var eventsMinDistance;

	(timelines.length > 0) && initTimeline(timelines);

	function initTimeline(timelines) {
		timelines.each(function(){
			eventsMinDistance = $(this).data('spacing');
			var timeline = $(this),
				timelineComponents = {};
			//cache timeline components 
			timelineComponents['timelineWrapper'] = timeline.find('.events-wrapper');
			timelineComponents['eventsWrapper'] = timelineComponents['timelineWrapper'].children('.events');
			timelineComponents['fillingLine'] = timelineComponents['eventsWrapper'].children('.filling-line');
			timelineComponents['timelineEvents'] = timelineComponents['eventsWrapper'].find('a');
			timelineComponents['timelineDates'] = parseDate(timelineComponents['timelineEvents']);
			timelineComponents['eventsMinLapse'] = minLapse(timelineComponents['timelineDates']);
			timelineComponents['timelineNavigation'] = timeline.find('.cd-timeline-navigation');
			timelineComponents['eventsContent'] = timeline.children('.events-content');

			//assign a left postion to the single events along the timeline
			setDatePosition(timelineComponents, eventsMinDistance);
			//assign a width to the timeline
			var timelineTotWidth = setTimelineWidth(timelineComponents, eventsMinDistance);
			//the timeline has been initialize - show it
			timeline.addClass('loaded');

			//detect click on the next arrow
			timelineComponents['timelineNavigation'].on('click', '.next', function(event){
				event.preventDefault();
				updateSlide(timelineComponents, timelineTotWidth, 'next');
			});
			//detect click on the prev arrow
			timelineComponents['timelineNavigation'].on('click', '.prev', function(event){
				event.preventDefault();
				updateSlide(timelineComponents, timelineTotWidth, 'prev');
			});
			//detect click on the a single event - show new event content
			timelineComponents['eventsWrapper'].on('click', 'a', function(event){
				event.preventDefault();
				timelineComponents['timelineEvents'].removeClass('selected');
				$(this).addClass('selected');
				updateOlderEvents($(this));
				updateFilling($(this), timelineComponents['fillingLine'], timelineTotWidth);
				updateVisibleContent($(this), timelineComponents['eventsContent']);
			});

			//on swipe, show next/prev event content
			timelineComponents['eventsContent'].on('swipeleft', function(){
				var mq = checkMQ();
				( mq == 'mobile' ) && showNewContent(timelineComponents, timelineTotWidth, 'next');
			});
			timelineComponents['eventsContent'].on('swiperight', function(){
				var mq = checkMQ();
				( mq == 'mobile' ) && showNewContent(timelineComponents, timelineTotWidth, 'prev');
			});

			//keyboard navigation
			$(document).keyup(function(event){
				if(event.which=='37' && elementInViewport(timeline.get(0)) ) {
					showNewContent(timelineComponents, timelineTotWidth, 'prev');
				} else if( event.which=='39' && elementInViewport(timeline.get(0))) {
					showNewContent(timelineComponents, timelineTotWidth, 'next');
				}
			});
		});
	}

	function updateSlide(timelineComponents, timelineTotWidth, string) {
		//retrieve translateX value of timelineComponents['eventsWrapper']
		var translateValue = getTranslateValue(timelineComponents['eventsWrapper']),
			wrapperWidth = Number(timelineComponents['timelineWrapper'].css('width').replace('px', ''));
		//translate the timeline to the left('next')/right('prev') 
		(string == 'next') 
			? translateTimeline(timelineComponents, translateValue - wrapperWidth + eventsMinDistance, wrapperWidth - timelineTotWidth)
			: translateTimeline(timelineComponents, translateValue + wrapperWidth - eventsMinDistance);
	}

	function showNewContent(timelineComponents, timelineTotWidth, string) {
		//go from one event to the next/previous one
		var visibleContent =  timelineComponents['eventsContent'].find('.selected'),
			newContent = ( string == 'next' ) ? visibleContent.next() : visibleContent.prev();

		if ( newContent.length > 0 ) { //if there's a next/prev event - show it
			var selectedDate = timelineComponents['eventsWrapper'].find('.selected'),
				newEvent = ( string == 'next' ) ? selectedDate.parent('li').next('li').children('a') : selectedDate.parent('li').prev('li').children('a');
			
			updateFilling(newEvent, timelineComponents['fillingLine'], timelineTotWidth);
			updateVisibleContent(newEvent, timelineComponents['eventsContent']);
			newEvent.addClass('selected');
			selectedDate.removeClass('selected');
			updateOlderEvents(newEvent);
			updateTimelinePosition(string, newEvent, timelineComponents);
		}
	}

	function updateTimelinePosition(string, event, timelineComponents) {
		//translate timeline to the left/right according to the position of the selected event
		var eventStyle = window.getComputedStyle(event.get(0), null),
			eventLeft = Number(eventStyle.getPropertyValue("left").replace('px', '')),
			timelineWidth = Number(timelineComponents['timelineWrapper'].css('width').replace('px', '')),
			timelineTotWidth = Number(timelineComponents['eventsWrapper'].css('width').replace('px', ''));
		var timelineTranslate = getTranslateValue(timelineComponents['eventsWrapper']);

		if( (string == 'next' && eventLeft > timelineWidth - timelineTranslate) || (string == 'prev' && eventLeft < - timelineTranslate) ) {
			translateTimeline(timelineComponents, - eventLeft + timelineWidth/2, timelineWidth - timelineTotWidth);
		}
	}

	function translateTimeline(timelineComponents, value, totWidth) {
		var eventsWrapper = timelineComponents['eventsWrapper'].get(0);
		value = (value > 0) ? 0 : value; //only negative translate value
		value = ( !(typeof totWidth === 'undefined') &&  value < totWidth ) ? totWidth : value; //do not translate more than timeline width
		setTransformValue(eventsWrapper, 'translateX', value+'px');
		//update navigation arrows visibility
		(value == 0 ) ? timelineComponents['timelineNavigation'].find('.prev').addClass('inactive') : timelineComponents['timelineNavigation'].find('.prev').removeClass('inactive');
		(value == totWidth ) ? timelineComponents['timelineNavigation'].find('.next').addClass('inactive') : timelineComponents['timelineNavigation'].find('.next').removeClass('inactive');
	}

	function updateFilling(selectedEvent, filling, totWidth) {
		//change .filling-line length according to the selected event
		var eventStyle = window.getComputedStyle(selectedEvent.get(0), null),
			eventLeft = eventStyle.getPropertyValue("left"),
			eventWidth = eventStyle.getPropertyValue("width");
		eventLeft = Number(eventLeft.replace('px', '')) + Number(eventWidth.replace('px', ''))/2;
		var scaleValue = eventLeft/totWidth;
		setTransformValue(filling.get(0), 'scaleX', scaleValue);
	}

	function setDatePosition(timelineComponents, min) {
		for (i = 0; i < timelineComponents['timelineDates'].length; i++) { 
			var distance = daydiff(timelineComponents['timelineDates'][0], timelineComponents['timelineDates'][i]),
				distanceNorm = Math.round(distance/timelineComponents['eventsMinLapse']) + 2;
			timelineComponents['timelineEvents'].eq(i).css('left', distanceNorm*min+'px');
		}
	}

	function setTimelineWidth(timelineComponents, width) {
		var timeSpan = daydiff(timelineComponents['timelineDates'][0], timelineComponents['timelineDates'][timelineComponents['timelineDates'].length-1]),
			timeSpanNorm = timeSpan/timelineComponents['eventsMinLapse'],
			timeSpanNorm = Math.round(timeSpanNorm) + 4,
			totalWidth = timeSpanNorm*width;
		timelineComponents['eventsWrapper'].css('width', totalWidth+'px');
		updateFilling(timelineComponents['eventsWrapper'].find('a.selected'), timelineComponents['fillingLine'], totalWidth);
		updateTimelinePosition('next', timelineComponents['eventsWrapper'].find('a.selected'), timelineComponents);
	
		return totalWidth;
	}

	function updateVisibleContent(event, eventsContent) {
		var eventDate = event.data('date'),
			visibleContent = eventsContent.find('.selected'),
			selectedContent = eventsContent.find('[data-date="'+ eventDate +'"]'),
			selectedContentHeight = selectedContent.height();

		if (selectedContent.index() > visibleContent.index()) {
			var classEnetering = 'selected enter-right',
				classLeaving = 'leave-left';
		} else {
			var classEnetering = 'selected enter-left',
				classLeaving = 'leave-right';
		}

		selectedContent.attr('class', classEnetering);
		visibleContent.attr('class', classLeaving).one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(){
			visibleContent.removeClass('leave-right leave-left');
			selectedContent.removeClass('enter-left enter-right');
		});
		eventsContent.css('height', selectedContentHeight+'px');
	}

	function updateOlderEvents(event) {
		event.parent('li').prevAll('li').children('a').addClass('older-event').end().end().nextAll('li').children('a').removeClass('older-event');
	}

	function getTranslateValue(timeline) {
		var timelineStyle = window.getComputedStyle(timeline.get(0), null),
			timelineTranslate = timelineStyle.getPropertyValue("-webkit-transform") ||
				timelineStyle.getPropertyValue("-moz-transform") ||
				timelineStyle.getPropertyValue("-ms-transform") ||
				timelineStyle.getPropertyValue("-o-transform") ||
				timelineStyle.getPropertyValue("transform");

		if( timelineTranslate.indexOf('(') >=0 ) {
			var timelineTranslate = timelineTranslate.split('(')[1];
			timelineTranslate = timelineTranslate.split(')')[0];
			timelineTranslate = timelineTranslate.split(',');
			var translateValue = timelineTranslate[4];
		} else {
			var translateValue = 0;
		}

		return Number(translateValue);
	}

	function setTransformValue(element, property, value) {
		element.style["-webkit-transform"] = property+"("+value+")";
		element.style["-moz-transform"] = property+"("+value+")";
		element.style["-ms-transform"] = property+"("+value+")";
		element.style["-o-transform"] = property+"("+value+")";
		element.style["transform"] = property+"("+value+")";
	}

	//based on http://stackoverflow.com/questions/542938/how-do-i-get-the-number-of-days-between-two-dates-in-javascript
	function parseDate(events) {
		var dateArrays = [];
		events.each(function(){
			var singleDate = $(this),
				dateComp = singleDate.data('date').split('T');
			if( dateComp.length > 1 ) { //both DD/MM/YEAR and time are provided
				var dayComp = dateComp[0].split('/'),
					timeComp = dateComp[1].split(':');
			} else if( dateComp[0].indexOf(':') >=0 ) { //only time is provide
				var dayComp = ["2000", "0", "0"],
					timeComp = dateComp[0].split(':');
			} else { //only DD/MM/YEAR
				var dayComp = dateComp[0].split('/'),
					timeComp = ["0", "0"];
			}
			var	newDate = new Date(dayComp[2], dayComp[1]-1, dayComp[0], timeComp[0], timeComp[1]);
			dateArrays.push(newDate);
		});
		return dateArrays;
	}

	function daydiff(first, second) {
		return Math.round((second-first));
	}

	function minLapse(dates) {
		//determine the minimum distance among events
		var dateDistances = [];
		for (i = 1; i < dates.length; i++) { 
			var distance = daydiff(dates[i-1], dates[i]);
			dateDistances.push(distance);
		}
		return Math.min.apply(null, dateDistances);
	}

	/*
		How to tell if a DOM element is visible in the current viewport?
		http://stackoverflow.com/questions/123999/how-to-tell-if-a-dom-element-is-visible-in-the-current-viewport
	*/
	function elementInViewport(el) {
		var top = el.offsetTop;
		var left = el.offsetLeft;
		var width = el.offsetWidth;
		var height = el.offsetHeight;

		while(el.offsetParent) {
			el = el.offsetParent;
			top += el.offsetTop;
			left += el.offsetLeft;
		}

		return (
			top < (window.pageYOffset + window.innerHeight) &&
			left < (window.pageXOffset + window.innerWidth) &&
			(top + height) > window.pageYOffset &&
			(left + width) > window.pageXOffset
		);
	}

	function checkMQ() {
		//check if mobile or desktop device
		return window.getComputedStyle(document.querySelector('.cd-horizontal-timeline'), '::before').getPropertyValue('content').replace(/'/g, "").replace(/"/g, "");
	}
}

//function load datetimepicker
var load_datetimepicker = function() {
	$('#from').datetimepicker({
		format: 'yyyy-mm-dd hh:ii',
		autoclose: true,
        minuteStep: 30
	}).on('changeDate', function(ev){
		var minDate = new Date(ev.date.valueOf());
		$('#to').datetimepicker('setStartDate', minDate);
	});
	
	$('#to').datetimepicker({
		format: 'yyyy-mm-dd hh:ii',
		autoclose: true,
        minuteStep: 30,
		useCurrent: true //Important! See issue #1075
	}).on('changeDate', function(ev){
		var maxDate = new Date(ev.date.valueOf());
		$('#from').datetimepicker('setEndDate', maxDate);
	});
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

var loadFormAddTask = function() {
	var e = document.getElementById("filter_proyek");
	var proyek_selected = e.options[e.selectedIndex].value;
	save_method = 'add';
	$('#modalFormAddTask [name="save_method"]').val(save_method);  
	$('#add_form_task')[0].reset(); // reset form on modals		  
	$('#modal_message').html('');  //reset message					 			  
	//document.getElementById('ref_id').disabled = false;
	//document.getElementById('url').disabled = false;
	//$('#modalFormAttachFileAgenda').modal('show');
  
	//Ajax Load data from ajax
	$.ajax({
		url : base_url+"index/data_form_add_task",
		type: "POST",
		dataType: "JSON",
		data: {proyek_id:proyek_selected},
		success: function(data)
		{
			var item_sel=["filter_modul_form"];
			var item_select = {"filter_modul_form":-1};															
			select_box(data,item_select, item_sel);		
			
			var item_sel=["filter_pic","filter_approval","filter_member"];
			var item_select = {"filter_pic":[-1],"filter_approval":[-1],"filter_member":[-1]};															
			select_box_group(data,item_select, item_sel);	
															
			$('#modalFormAddTask').modal('show'); // show bootstrap modal
			$('.modal-title').text('Tambah Task'); // Set Title to Bootstrap modal title
			
			$('#modalFormAddTask .mt-multiselect').multiselect({
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

var loadFormAttachFileDrive = function() {
	var e = document.getElementById("filter_proyek");
	var proyek_selected = e.options[e.selectedIndex].value;
	//save_method = 'add';
	$('#add_form_file_drive')[0].reset(); // reset form on modals		  
	$('#modal_message').html('');  //reset message					 		
  
	//Ajax Load data from ajax
	$.ajax({
		url : base_url+"index/data_form_file_drive",
		type: "POST",
		dataType: "JSON",
		data: {proyek_id:proyek_selected},
		success: function(data)
		{
			var item_sel=["filter_modul_drive_form"];
			var item_select = {"filter_modul_drive_form":-1};															
			select_box(data,item_select, item_sel);	
															
			$('#modalFormAttachFileDrive').modal('show'); // show bootstrap modal
			$('.modal-title').text('Upload File Drive'); // Set Title to Bootstrap modal title
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

var loadFormAddAgenda = function() {
	var e = document.getElementById("filter_proyek");
	var proyek_selected = e.options[e.selectedIndex].value;
	save_method = 'add';
	$('#modalFormAddAgenda [name="save_method"]').val(save_method);  
	$('#add_form_agenda')[0].reset(); // reset form on modals		  
	$('#modal_message').html('');  //reset message					 			  
	//document.getElementById('ref_id').disabled = false;
	//document.getElementById('url').disabled = false;
	//$('#modalFormAttachFileAgenda').modal('show');
  
	//Ajax Load data from ajax
	$.ajax({
		url : base_url+"index/data_form_add_agenda",
		type: "POST",
		dataType: "JSON",
		data: {proyek_id:proyek_selected},
		success: function(data)
		{			
			var item_sel=["filter_kategori_agenda"];
			var item_select = {"filter_kategori_agenda":[-1]};															
			select_box(data,item_select, item_sel);
			
			var item_sel=["filter_member_agenda"];
			var item_select = {"filter_member_agenda":[-1]};															
			select_box_group(data,item_select, item_sel);	
															
			$('#modalFormAddAgenda').modal('show'); // show bootstrap modal
			$('.modal-title').text('Tambah Agenda'); // Set Title to Bootstrap modal title
			
			$('#modalFormAddAgenda .mt-multiselect').multiselect({
				enableClickableOptGroups: true,
				enableCollapsibleOptGroups: true,
				collapseOptGroupsByDefault: true,
				enableFiltering: true,
				includeSelectAllOption: true,
				maxHeight: 200,
				buttonWidth: '100%',
			});
			
			load_datetimepicker();

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
	var form = document.getElementById('add_form_task');					  
	var form_data = new FormData(form);	
			
	// ajax adding data to database
	$.ajax({
		url : base_url+"index/data_save_task",
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
				loadSelectModul();
				loadDashboardStat();
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

function saveAgenda() {
	var form = document.getElementById('add_form_agenda');					  
	var form_data = new FormData(form);	
	var e = document.getElementById("filter_proyek");
	var proyek_selected = e.options[e.selectedIndex].value;
	form_data.append("proyek_id", proyek_selected);
			
	// ajax adding data to database
	$.ajax({
		url : base_url+"index/data_save_agenda",
		type: "POST",
		data: form_data,
		processData: false,
		contentType: false,
		dataType: "JSON",
		success: function(data)
		{
			if(data.status=='success'){
				//saved data																
				$('#modalFormAddAgenda').modal('hide');	
				loadContent();
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

var loadFormAttachFileAgenda = function(agenda_id) {
	//save_method = 'add';
	$('#add_form_file_agenda')[0].reset(); // reset form on modals		  
	$('#modal_message').html('');  //reset message					 			  
	//document.getElementById('ref_id').disabled = false;
	//document.getElementById('url').disabled = false;
	//$('#modalFormAttachFileAgenda').modal('show');
	$('#add_form_file_agenda [name="agenda_id"]').val(agenda_id);  
  
	//Ajax Load data from ajax
	$.ajax({
		url : base_url+"index/data_form_file_agenda",
		type: "POST",
		dataType: "JSON",
		data: {agenda_id:agenda_id},
		success: function(data)
		{
			// var item_sel=["ref_id"];
			// var item_select = {"ref_id":-1};															
			// select_box(data,item_select, item_sel);		
															
			$('#modalFormAttachFileAgenda').modal('show'); // show bootstrap modal
			$('.modal-title').text(data.nama_agenda); // Set Title to Bootstrap modal title
			
			loadTabelFileAgenda(agenda_id);

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

function saveFileAgenda() {
	var form = document.getElementById('add_form_file_agenda');					  
	var form_data = new FormData(form);	
	// var fileInput = document.getElementById('filename');
	// var file = fileInput.files[0];					
	//form_data.append("filename", file);
			
	// ajax adding data to database
	$.ajax({
		url : base_url+"index/data_save_file_agenda",
		type: "POST",
		data: form_data,
		processData: false,
		contentType: false,
		dataType: "JSON",
		success: function(data)
		{
			if(data.status=='success'){
				toastr.success(data.message);
				$('#add_form_file_agenda')[0].reset(); // reset form on modals		
				$('#table_file_agenda').DataTable().ajax.reload();
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

function saveFileDrive() {
	var form = document.getElementById('add_form_file_drive');					  
	var form_data = new FormData(form);	
			
	// ajax adding data to database
	$.ajax({
		url : base_url+"index/data_save_file_drive",
		type: "POST",
		data: form_data,
		processData: false,
		contentType: false,
		dataType: "JSON",
		success: function(data)
		{
			if(data.status=='success'){
				//saved data																
				$('#modalFormAttachFileDrive').modal('hide');	
				loadSelectModulDrive();
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

var loadTabelTask = function(modul_id) {
	var e = document.getElementById("filter_proyek");
	var proyek_selected = e.options[e.selectedIndex].value;
	//load data table																
	oTable = $('#table_task').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		"buttons": [
			{ extend: 'pdf', className: 'btn green btn-outline' },
			{ extend: 'excel', className: 'btn yellow btn-outline ' },
			{ extend: 'reload', className: 'btn dark btn-outline' }
		],
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": base_url+"index/data_list_task",
			"type": "POST",				
			"data": function ( d ) {
				d.proyek_id = proyek_selected;								
				d.modul_id = modul_id;								
			}
		},
		"destroy": true,
		//Set column definition initialisation properties.
		"columnDefs": [
			{ 
			  "targets": [ -1 ], //last column
			  "searchable": false,
			  "orderable": false, //set not orderable
			},
			{
				"className": "dt-center", 
				"targets": [0,3],
				"width": '10%'
			},
			{
				"targets": [2],
				"width": '20%'
			},
		],
		"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
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

var loadTabelDrive = function(modul_id) {
	var e = document.getElementById("filter_proyek");
	var proyek_selected = e.options[e.selectedIndex].value;
	//load data table																
	$('#table_file_drive').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": base_url+"index/data_list_drive",
			"type": "POST",				
			"data": function ( d ) {
				d.proyek_id = proyek_selected;
				d.modul_id = modul_id;				
			}
		},
		"destroy": true,
		//Set column definition initialisation properties.
		"columnDefs": [
			{ 
			  "targets": [ -1 ], //last column
			  "searchable": false,
			  "orderable": false, //set not orderable
			},
			{
				"className": "dt-center", 
				"targets": [0,2],
				"width": '10%'
			},
		],

	});//end load data table
}

var loadTabelFileDetailAgenda = function(agenda_id) {
	//load data table																
	$('#table_file_detail_agenda').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": base_url+"index/data_list_file_agenda",
			"type": "POST",						
			"data": {agenda_id:agenda_id},
		},
		"destroy": true,
		//Set column definition initialisation properties.
		"columnDefs": [
			{ 
			  "targets": [ -1 ], //last column
			  "searchable": false,
			  "orderable": false, //set not orderable
			},
			{
				"className": "dt-center", 
				"targets": [0,4],
				"width": "5%",
			},
		],

	});//end load data table
}

var loadTabelFileAgenda = function(agenda_id) {
	//load data table																
	$('#table_file_agenda').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": base_url+"index/data_list_file_agenda",
			"type": "POST",						
			"data": {agenda_id:agenda_id},
		},
		"destroy": true,
		//Set column definition initialisation properties.
		"columnDefs": [
			{ 
			  "targets": [ -1 ], //last column
			  "searchable": false,
			  "orderable": false, //set not orderable
			},
			{
				"className": "dt-center", 
				"targets": [0,4],
				"width": "5%",
			},
		],

	});//end load data table
}

var data_delete = function (id, nama_id){
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
			url: base_url+"index/data_delete",
			type: "POST",
			data: $('#delete_form').serialize(),
			dataType: "JSON",
			success: function(data)
			{
				if(data.status=='success'){
					toastr.success(data.message);
					$('#modalDeleteForm').modal('hide');		
					$('#table_file_agenda').DataTable().ajax.reload();
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


var data_delete_drive = function (id, nama_id){
	if(id != ''){
		//show modal confirmation
		$('#delete_drive_form')[0].reset(); // reset form on modals
		$('#modal_delete_message').html('');  //reset message
		
		$('#modalDeleteDriveForm [name="id_delete_data"]').val(id);
		$('#modalDeleteDriveForm #delete_text').html('<b >Hapus data ' + nama_id + '</b>');	
		$('#modalDeleteDriveForm').modal('show'); // show bootstrap modal when complete loaded
		$('.modal-title').text('Hapus Data'); // Set Title to Bootstrap modal title	
	}else{
		//lakukan hapus data
		// ajax hapus data to database
		$.ajax({
			url: base_url+"index/data_delete_drive",
			type: "POST",
			data: $('#delete_drive_form').serialize(),
			dataType: "JSON",
			success: function(data)
			{
				if(data.status=='success'){
					toastr.success(data.message);
					$('#modalDeleteDriveForm').modal('hide');		
					loadSelectModulDrive();
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

 jQuery(document).ready(function() {
    loadSelectFilter();
});