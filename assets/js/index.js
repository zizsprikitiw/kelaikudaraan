jQuery(document).ready(function() {
    data_init();
    chart();
});

function data_init()
{
  //Ajax Load data from ajax
  $.ajax({			  		
		url : base_url+'index/data_init',
		type: "POST",
		dataType: "JSON",
		success: function(data)
		{				   									
			$('#div_pesawat').html(data.div_pesawat);				
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			alert('Error get data from ajax');
		}
	});
}

// Trends Stats.
// Based on Chartjs plugin - http://www.chartjs.org/
var chart = function() {
	$.ajax({
		url : base_url+"index/data_flight_chart" ,
		type: "POST",
		dataType: "JSON",
		data: {},
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
			var label = [];
			var value = [];
			for (var i in data) {
				label.push (data[i].date);
				value.push (data[i].duration);
			}
					
			if ($('#chart_flight').length == 0) {
				return;
			}

			var ctx = document.getElementById("chart_flight").getContext("2d");

			var gradient = ctx.createLinearGradient(0, 0, 0, 240);
			gradient.addColorStop(0, Chart.helpers.color('#00c5dc').alpha(0.7).rgbString());
			gradient.addColorStop(1, Chart.helpers.color('#f2feff').alpha(0).rgbString());

			var config = {
				type: 'line',
				data: {
					labels: label,
					datasets: [{
						label: "TSN (hrs)",
						backgroundColor: gradient, // Put the gradient here as a fill color
						borderColor: '#0dc8de',

						pointBackgroundColor: Chart.helpers.color('#ffffff').alpha(0).rgbString(),
						pointBorderColor: Chart.helpers.color('#ffffff').alpha(0).rgbString(),
						pointHoverBackgroundColor: KTApp.getStateColor('danger'),
						pointHoverBorderColor: Chart.helpers.color('#000000').alpha(0.2).rgbString(),

						//fill: 'start',
						data: value
					}]
				},
				options: {
					title: {
						display: false,
					},
					tooltips: {
						intersect: false,
						mode: 'nearest',
						xPadding: 10,
						yPadding: 10,
						caretPadding: 10
					},
					legend: {
						display: false
					},
					responsive: true,
					maintainAspectRatio: false,
					hover: {
						mode: 'index'
					},
					scales: {
						xAxes: [{
							display: false,
							gridLines: false,
							scaleLabel: {
								display: true,
								labelString: 'Month'
							}
						}],
						yAxes: [{
							display: false,
							gridLines: false,
							scaleLabel: {
								display: true,
								labelString: 'Value'
							},
							ticks: {
								beginAtZero: true
							}
						}]
					},
					elements: {
						line: {
							tension: 0.19
						},
						point: {
							radius: 4,
							borderWidth: 12
						}
					},
					layout: {
						padding: {
							left: 0,
							right: 0,
							top: 5,
							bottom: 0
						}
					}
				}
			};

			var chart = new Chart(ctx, config);
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