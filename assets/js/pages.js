jQuery(document).ready(function() {
	loadInfoHeader();
});

var loadInfoHeader = function() {	   
	$.ajax({
		url : base_url+"index/data_info_bar",
		type: "POST",
		dataType: "JSON",
		success: function(data)
		{				
			var cont = $('#header_info_bar');
			cont.html(data.info);
			
			ps = new PerfectScrollbar('#header_info_bar', {
			  wheelSpeed: 2,
			  wheelPropagation: true,
			  minScrollbarLength: 20
			});
			
			$('#header_info_bar_jml_pending').html(data.jml_pending+' new');
			
			// cont.find('.scroller').slimScroll({
				// start : 'top',
				// height: '275px',
			// });
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