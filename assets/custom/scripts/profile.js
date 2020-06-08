var Profile = function() {

    var changePassword = function() {

        jQuery('#change-password-btn').click(function() {
			var form1 = $('#form-change-password');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                messages: {
                    // select_multi: {
                        // maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
                        // minlength: jQuery.validator.format("At least {0} items must be selected")
                    // }
                },
                rules: {
                    old_password: {
                        required: true
                    },
                    new_password: {
                        minlength: 8,
						required: true
                    },
                    new_password_confirm: {
                        equalTo: "#new_password"
                    }
                },

                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success1.hide();
                    error1.show();
                    App.scrollTo(error1, -200);
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element).parent('.input-group');
                    if (cont.size() > 0) {
                        cont.after(error);
                    } else {
                        element.after(error);
                    }
                },

                highlight: function (element) { // hightlight error inputs

                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
					error1.hide();
					
					var form = document.getElementById('form-change-password');					  
					var form_data = new FormData(form);	
								   
					$.ajax({
						type: "POST",
						url: base_url+"login/change_password",
						data: form_data,
						processData: false,
						contentType: false,
						dataType: "JSON",
						success: function(data){
							if(data.status=='success'){
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
            });
		});
		
    }
	
	var cropImage = function() {
		var crop_max_width = 300;
		var crop_max_height = 300;
		var jcrop_api;
		var canvas;
		var context;
		var image;
		var prefsize;

		var form1 = $('#form-change-avatar');
		$("#file_avatar",form1).change(function(){
			loadImage(this);
		});
		
		function loadImage(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				canvas = null;
				reader.onload = function(e) {
					image = new Image();
					image.onload = validateImage;
					image.src = e.target.result;
				}
				reader.readAsDataURL(input.files[0]);
			}
		}

		function dataURLtoBlob(dataURL) {
			var BASE64_MARKER = ';base64,';
			if (dataURL.indexOf(BASE64_MARKER) == -1) {
				var parts = dataURL.split(',');
				var contentType = parts[0].split(':')[1];
				var raw = decodeURIComponent(parts[1]);

				return new Blob([raw], {
					type: contentType
				});
			}
			var parts = dataURL.split(BASE64_MARKER);
			var contentType = parts[0].split(':')[1];
			var raw = window.atob(parts[1]);
			var rawLength = raw.length;
			var uInt8Array = new Uint8Array(rawLength);
			for (var i = 0; i < rawLength; ++i) {
				uInt8Array[i] = raw.charCodeAt(i);
			}

			return new Blob([uInt8Array], {
				type: contentType
			});
		}

		function validateImage() {
			if (canvas != null) {
				image = new Image();
				image.onload = restartJcrop;
				image.src = canvas.toDataURL('image/png');
			} else restartJcrop();
		}

		function restartJcrop() {
			if (jcrop_api != null) {
				jcrop_api.destroy();
			}
			$("#views").empty();
			$("#views").append("<canvas id=\"canvas\">");
			canvas = $("#canvas")[0];
			context = canvas.getContext("2d");
			canvas.width = image.width;
			canvas.height = image.height;
			context.drawImage(image, 0, 0);
			$("#canvas").Jcrop({
				onSelect: selectcanvas,
				onRelease: clearcanvas,
				boxWidth: crop_max_width,
				boxHeight: crop_max_height,
				bgOpacity: 0.2,
				bgColor: 'black',
				addClass: 'jcrop-dark',
				aspectRatio: 1
			}, function() {
				jcrop_api = this;
			});
			clearcanvas();
		}

		function clearcanvas() {
			prefsize = {
				x: 0,
				y: 0,
				w: canvas.width,
				h: canvas.height,
			};
		}

		function selectcanvas(coords) {
			prefsize = {
				x: Math.round(coords.x),
				y: Math.round(coords.y),
				w: Math.round(coords.w),
				h: Math.round(coords.h)
			};
		}

		function applyCrop() {
			canvas.width = prefsize.w;
			canvas.height = prefsize.h;
			context.drawImage(image, prefsize.x, prefsize.y, prefsize.w, prefsize.h, 0, 0, canvas.width, canvas.height);
			validateImage();
		}

		$("#cropbutton").click(function(e) {
			applyCrop();
		});
		
		/*function picture(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function (e) {
					$("#jcrop, #preview").html("").append("<h4>Crop Image</h4><div class=\"table-scrollable\" style=\"height:200px; overflow-y:auto;\"> <img src=\""+e.target.result+"\" alt=\"\"  /></div>");
					var api;
					$('#jcrop  img').Jcrop({
						// start off with jcrop-light class
						bgOpacity: 0.5,
						bgColor: 'white',
						addClass: 'jcrop-light',
						aspectRatio: 1,
						onSelect: updateCoords
					},function(){
						api = this;
						api.setSelect([10,10,300,300]);
						api.setOptions({ bgFade: true });
						api.ui.selection.addClass('jcrop-selection');
					});

					function updateCoords(c)
					{
						$('#crop_x').val(c.x);
						$('#crop_y').val(c.y);
						$('#crop_w').val(c.w);
						$('#crop_h').val(c.h);
					};
					
				}
				reader.readAsDataURL(input.files[0]);
			} else {
				$("#jcrop, #preview").html("");
			}
		}*/
		
		$('#form-change-avatar').submit(function(){
			var form = document.getElementById('form-change-avatar');					  
			var form_data = new FormData($(this)[0]);	
			// var fileInput = document.getElementById('file_avatar');
			// var file = fileInput.files[0];					
			// form_data.append("file_avatar", file);
			
			if (canvas != null) {
				var blob = dataURLtoBlob(canvas.toDataURL('image/jpeg'));
				form_data.append("cropped_image", blob);
			}
						   
			$.ajax({
				type: "POST",
				url: base_url+"profile/change_avatar",
				data: form_data,
				processData: false,
				contentType: false,
				dataType: "JSON",
				success: function(data){
					if(data.status=='success'){
						$('#form-change-avatar')[0].reset();
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
			return false;
		});
	}

    return {
        //main function to initiate the module
        init: function() {

            changePassword();
			cropImage();
			
        }

    };

}();
 
 jQuery(document).ready(function() {
    Profile.init();
});