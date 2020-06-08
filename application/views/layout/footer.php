					</div>
					<?php if ($this->ion_auth->logged_in()) { ?>
					<!-- begin:: Footer -->
					<div class="kt-footer kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop" id="kt_footer">
						<div class="kt-footer__copyright">
							<?php echo $config['copyright'];?>
						</div>
					</div>

					<!-- end:: Footer -->
					<?php } ?>
				</div>
			</div>
		</div>

		<!-- end:: Page -->

		

		<!-- begin::Scrolltop -->
		<div id="kt_scrolltop" class="kt-scrolltop">
			<i class="fa fa-arrow-up"></i>
		</div>

		<!-- end::Scrolltop -->

		<!-- begin::Global Config(global config for global JS sciprts) -->
		<script>
			var KTAppOptions = {
				"colors": {
					"state": {
						"brand": "#5d78ff",
						"dark": "#282a3c",
						"light": "#ffffff",
						"primary": "#5867dd",
						"success": "#34bfa3",
						"info": "#36a3f7",
						"warning": "#ffb822",
						"danger": "#fd3995"
					},
					"base": {
						"label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
						"shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
					}
				}
			};
		</script>

		<!-- end::Global Config -->

		<!--begin:: Global Mandatory Vendors -->
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/jquery/dist/jquery.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/popper.js/dist/umd/popper.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/js-cookie/src/js.cookie.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/moment/min/moment.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/tooltip.js/dist/umd/tooltip.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/perfect-scrollbar/dist/perfect-scrollbar.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/sticky-js/dist/sticky.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/wnumb/wNumb.js" type="text/javascript"></script>

		<!--end:: Global Mandatory Vendors -->

		<!--begin:: Global Optional Vendors -->
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/jquery-form/dist/jquery.form.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/block-ui/jquery.blockUI.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_custom']); ?>/js/vendors/bootstrap-datepicker.init.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_custom']); ?>/js/vendors/bootstrap-timepicker.init.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/bootstrap-maxlength/src/bootstrap-maxlength.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_custom']); ?>/vendors/bootstrap-multiselectsplitter/bootstrap-multiselectsplitter.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/bootstrap-switch/dist/js/bootstrap-switch.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_custom']); ?>/js/vendors/bootstrap-switch.init.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/select2/dist/js/select2.full.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/ion-rangeslider/js/ion.rangeSlider.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/typeahead.js/dist/typeahead.bundle.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/handlebars/dist/handlebars.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/inputmask/dist/jquery.inputmask.bundle.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/inputmask/dist/inputmask/inputmask.date.extensions.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/inputmask/dist/inputmask/inputmask.numeric.extensions.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/nouislider/distribute/nouislider.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/owl.carousel/dist/owl.carousel.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/autosize/dist/autosize.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/clipboard/dist/clipboard.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/dropzone/dist/dropzone.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/summernote/dist/summernote.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/markdown/lib/markdown.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/bootstrap-markdown/js/bootstrap-markdown.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_custom']); ?>/js/vendors/bootstrap-markdown.init.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/bootstrap-notify/bootstrap-notify.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_custom']); ?>/js/vendors/bootstrap-notify.init.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/jquery-validation/dist/jquery.validate.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/jquery-validation/dist/additional-methods.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_custom']); ?>/js/vendors/jquery-validation.init.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/toastr/build/toastr.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/raphael/raphael.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/morris.js/morris.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/chart.js/dist/Chart.bundle.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_custom']); ?>/vendors/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_custom']); ?>/vendors/jquery-idletimer/idle-timer.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/waypoints/lib/jquery.waypoints.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/counterup/jquery.counterup.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/es6-promise-polyfill/promise.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/sweetalert2/dist/sweetalert2.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_custom']); ?>/js/vendors/sweetalert2.init.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/jquery.repeater/src/lib.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/jquery.repeater/src/jquery.input.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/jquery.repeater/src/repeater.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_general']); ?>/dompurify/dist/purify.js" type="text/javascript"></script>

		<!--end:: Global Optional Vendors -->

		<!--begin::Global Theme Bundle(used by all pages) -->
		<script src="<?php echo base_url($this->config->item('assets')['metronic_scripts']); ?>/demo1/scripts.bundle.js" type="text/javascript"></script>

		<!--end::Global Theme Bundle -->

		<!--begin::Page Vendors(used by this page) -->
		<script src="<?php echo base_url($this->config->item('assets')['metronic_custom']); ?>/fullcalendar/fullcalendar.bundle.js" type="text/javascript"></script>
		<!--<script src="//maps.google.com/maps/api/js?key=AIzaSyBTGnKT7dt597vo9QgeQ7BFhvSRP4eiMSM" type="text/javascript"></script>-->
		<script src="<?php echo base_url($this->config->item('assets')['metronic_custom']); ?>/gmaps/gmaps.js" type="text/javascript"></script>
		<!--<script src="//www.amcharts.com/lib/3/amcharts.js" type="text/javascript"></script>
		<script src="//www.amcharts.com/lib/3/serial.js" type="text/javascript"></script>
		<script src="//www.amcharts.com/lib/3/radar.js" type="text/javascript"></script>
		<script src="//www.amcharts.com/lib/3/pie.js" type="text/javascript"></script>
		<script src="//www.amcharts.com/lib/3/plugins/tools/polarScatter/polarScatter.min.js" type="text/javascript"></script>
		<script src="//www.amcharts.com/lib/3/plugins/animate/animate.min.js" type="text/javascript"></script>
		<script src="//www.amcharts.com/lib/3/plugins/export/export.min.js" type="text/javascript"></script>
		<script src="//www.amcharts.com/lib/3/themes/light.js" type="text/javascript"></script>-->

		<!--end::Page Vendors -->

		<!--begin::Page Scripts(used by this page) -->
		<script src="<?php echo base_url($this->config->item('assets')['metronic_scripts']); ?>/demo1/pages/dashboard.js" type="text/javascript"></script>
		<script src="<?php echo base_url($this->config->item('assets')['metronic_custom']); ?>/datatables/datatables.bundle.js" type="text/javascript"></script>
		
		<!--begin::Custom Scripts -->
		<?php if(!empty($data['add_javascript'])) { foreach($data['add_javascript'] as $javascript){  ?>
			<script src="<?php echo $javascript; ?>" type="text/javascript"></script>
		<?php } } ?>
		<!--end::Custom Scripts -->
		
		<!--end::Page Scripts -->
	</body>

	<!-- end::Body -->
</html>