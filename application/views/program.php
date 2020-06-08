<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
						<!-- begin:: Content Head -->
						
						<div class="kt-subheader   kt-grid__item" id="kt_subheader">
							<div class="kt-subheader__main">
								<h3 class="kt-subheader__title">Program <?php echo $data['program']; ?></h3>
								<input type="hidden" value="<?php echo $data['proyek_id']; ?>" name="proyek_id" id="proyek_id"/> 	
							</div>
						</div>

						<!-- end:: Content Head -->
						
						<!-- begin:: Content -->
						<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
							<!--Begin::Section-->
							<div class="row">
								<div class="col-xl-12">

									<!--begin:: Widgets/Blog-->
									<div class="kt-portlet kt-portlet--height-fluid kt-widget19">
										<div class="kt-portlet__body kt-portlet__body--fit kt-portlet__body--unfill">
											<!--<div class="kt-widget19__pic kt-portlet-fit--top kt-portlet-fit--sides" style="min-height: 300px; background-image: url(<?php echo base_url($this->config->item('assets')['assets_images']); ?>/LSU-05.jpg)">
												<h3 class="kt-widget19__title kt-font-light">
													<?php echo $data['komponen']['aircraft_registration']; ?>
												</h3>
												<div class="kt-widget19__shadow"></div>
												<div class="kt-widget19__labels">
													<a href="#" class="btn btn-label-light-o2 btn-bold btn-sm ">Download Report</a>
												</div>
											</div>-->
											<div class="fadeOut owl-carousel owl-theme kt-widget19__pic kt-portlet-fit--top kt-portlet-fit--sides" >
												<?php  if(!empty($data['list_file'])) { 
													foreach ($data['list_file'] as $list_item) {
												?>
												<div class="item" style="max-height: 400px;">
													<p class="kt-widget19__title kt-font-light" style="font-size:14px"><mark>
														<span style="font-size:24px"><b><?php echo $data['komponen']['aircraft_registration']; ?></b></span><br>
														<span style="font-size:18px"><b>Posisi:</b> <?php echo $data['posisi']; ?></span><br>
														<?php echo $data['keterangan']; ?>
													</mark></p>
													<div class="kt-widget19__shadow"></div>
													<div class="kt-widget19__labels">
														<a href="<?php echo base_url('program/download_report/'.$data['proyek_id']);?>" class="btn btn-dark btn-bold btn-sm" target="_blank" style="cursor:pointer !important;">Download Report</a>
													</div>
													<img src="<?php echo base_url($this->config->item('uploads')['pesawat']).'/'.$list_item->filename; ?>" />
												</div>
												<?php } } ?>
											</div>
										</div>
										<div class="kt-portlet__body  kt-portlet__body--fit">
											<div class="row row-no-padding row-col-separator-xl">
												<div class="col-md-3">

													<!--begin::Total Profit-->
													<div class="kt-widget24">
														<div class="kt-widget24__details">
															<div class="kt-widget24__info">
																<h4 class="kt-widget24__title">
																	Serial Number
																</h4>
																<span class="kt-widget24__desc kt-font-warning">
																	<?php echo $data['komponen']['serial_number']; ?>
																</span>
															</div>
														</div>
													</div>

													<!--end::Total Profit-->
												</div>
												<div class="col-md-3">

													<!--begin::New Feedbacks-->
													<div class="kt-widget24">
														<div class="kt-widget24__details">
															<div class="kt-widget24__info">
																<h4 class="kt-widget24__title">
																	Aircraft Model
																</h4>
																<span class="kt-widget24__desc kt-font-warning">
																	<?php echo $data['komponen']['model']; ?>
																</span>
															</div>
														</div>
													</div>

													<!--end::New Feedbacks-->
												</div>
												<div class="col-md-3">

													<!--begin::New Feedbacks-->
													<div class="kt-widget24">
														<div class="kt-widget24__details">
															<div class="kt-widget24__info">
																<h4 class="kt-widget24__title">
																	Aircraft Registration
																</h4>
																<span class="kt-widget24__desc kt-font-warning">
																	<?php echo $data['komponen']['aircraft_registration']; ?>
																</span>
															</div>
														</div>
													</div>

													<!--end::New Feedbacks-->
												</div>
												<div class="col-md-3">

													<!--begin::New Feedbacks-->
													<div class="kt-widget24">
														<div class="kt-widget24__details">
															<div class="kt-widget24__info">
																<h4 class="kt-widget24__title">
																	Registration Number
																</h4>
																<span class="kt-widget24__desc kt-font-warning">
																	<?php echo $data['komponen']['registration_number']; ?>
																</span>
															</div>
														</div>
													</div>

													<!--end::New Feedbacks-->
												</div>
												<div class="col-md-3">

													<!--begin::New Feedbacks-->
													<div class="kt-widget24">
														<div class="kt-widget24__details">
															<div class="kt-widget24__info">
																<h4 class="kt-widget24__title">
																	Aircraft Mfg
																</h4>
																<span class="kt-widget24__desc kt-font-warning">
																	<?php echo $data['komponen']['mfg']; ?>
																</span>
															</div>
														</div>
													</div>

													<!--end::New Feedbacks-->
												</div>
												<div class="col-md-3">

													<!--begin::New Feedbacks-->
													<div class="kt-widget24">
														<div class="kt-widget24__details">
															<div class="kt-widget24__info">
																<h4 class="kt-widget24__title">
																	Aircraft Date of mfg
																</h4>
																<span class="kt-widget24__desc kt-font-warning">
																	<?php echo $data['komponen']['date_mfg']; ?>
																</span>
															</div>
														</div>
													</div>

													<!--end::New Feedbacks-->
												</div>
												<div class="col-md-3">

													<!--begin::New Feedbacks-->
													<div class="kt-widget24">
														<div class="kt-widget24__details">
															<div class="kt-widget24__info">
																<h4 class="kt-widget24__title">
																	Aircraft Total Airframe (hours)
																</h4>
																<span class="kt-widget24__desc kt-font-warning">
																	<?php echo $data['komponen']['tsn']; ?>
																</span>
															</div>
														</div>
													</div>

													<!--end::New Feedbacks-->
												</div>
												<div class="col-md-3">

													<!--begin::New Feedbacks-->
													<div class="kt-widget24">
														<div class="kt-widget24__details">
															<div class="kt-widget24__info">
																<h4 class="kt-widget24__title">
																	Aircraft AFL (cycles)
																</h4>
																<span class="kt-widget24__desc kt-font-warning">
																	<?php echo $data['komponen']['afl']; ?>
																</span>
															</div>
														</div>
													</div>

													<!--end::New Feedbacks-->
												</div>
											</div>
										</div>
									</div>

									<!--end:: Widgets/Blog-->
								</div>
							</div>
							<!--End:Section-->
							
							<!--Begin::Section-->
							<div class="row">
								<div class="col">
									<div class="alert alert-light alert-elevate fade show" role="alert">
										<div class="col-md-3">

											<!--begin::New Feedbacks-->
											<div class="kt-widget24">
												<div class="kt-widget24__details">
													<div class="kt-widget24__info">
														<h4 class="kt-widget24__title">
															Status Color
														</h4>
														<span class="kt-widget24__desc kt-font-dark">
															<span class="kt-badge kt-badge--success">1</span> Approved<br>
															<span class="kt-badge kt-badge--dark">2</span> Waiting PIC<br>
															<span class="kt-badge kt-badge--warning">3</span> Waiting Admin
														</span>
													</div>
												</div>
											</div>

											<!--end::New Feedbacks-->
										</div>
										<div class="col-md-3">

											<!--begin::New Feedbacks-->
											<div class="kt-widget24">
												<div class="kt-widget24__details">
													<div class="kt-widget24__info">
														<h4 class="kt-widget24__title">
															Link Color
														</h4>
														<span class="kt-widget24__desc kt-font-dark">
															<span class="kt-badge kt-badge--primary">1</span> Link Download
														</span>
													</div>
												</div>
											</div>

											<!--end::New Feedbacks-->
										</div>
										<div class="col-md-3">

											<!--begin::New Feedbacks-->
											<div class="kt-widget24">
												<div class="kt-widget24__details">
													<div class="kt-widget24__info">
														<h4 class="kt-widget24__title">
															Remaining Color
														</h4>
														<span class="kt-widget24__desc kt-font-dark">
															<span class="kt-badge kt-badge--success">1</span> Actived<br>
															<span class="kt-badge kt-badge--danger">2</span> Expired
														</span>
													</div>
												</div>
											</div>

											<!--end::New Feedbacks-->
										</div>
										<div class="col-md-3">

											<!--begin::New Feedbacks-->
											<div class="kt-widget24">
												<div class="kt-widget24__details">
													<div class="kt-widget24__info">
														<h4 class="kt-widget24__title">
															Action Color
														</h4>
														<span class="kt-widget24__desc kt-font-dark">
															<span class="kt-badge kt-badge--info">1</span> Add Data/ Detail TSN<br>
															<span class="kt-badge kt-shape-font-color-1" style="background-color:#FF7F50">2</span> Inspect<br>
															<span class="kt-badge kt-shape-font-color-1" style="background-color:#A52A2A">3</span> Approve<br>
															<span class="kt-badge kt-badge--danger">4</span> Delete
														</span>
													</div>
												</div>
											</div>

											<!--end::New Feedbacks-->
										</div>
									</div>
								</div>
							</div>
							<!--End:Section-->
							
							<!--Begin::Section-->
							<div class="row">
								<div class="col-md-12">
									<!--begin::Portlet-->
									<div class="kt-portlet">
										<div class="kt-portlet__head">
											<div class="kt-portlet__head-label">
												<span class="kt-portlet__head-icon kt-hidden">
													<i class="la la-gear"></i>
												</span>
												<h3 class="kt-portlet__head-title">
													TSN Aircraft Report
												</h3>
											</div>
											<div class="kt-portlet__head-toolbar">
												<div class="kt-portlet__head-wrapper">
													<div class="kt-portlet__head-actions">
														<button type="button" class="btn btn-brand btn-elevate btn-icon-sm" onClick="loadFormAddTSNAircraftReport()">
															<i class="la la-plus"></i>
															Tambah Data
														</button>
													</div>
												</div>
											</div>
										</div>
										<div class="kt-portlet__body">
											<!--begin: Datatable -->
											<table class="table table-striped- table-bordered table-hover table-checkable" id="tbl_tsn_aircraft_report">
												<thead>
													<tr>
														<th>No</th>
														<th>Description</th>
														<th>TSN Date</th>
														<th>Total TSN</th>
														<th>File</th>
														<th>Status</th>
														<th>Approve</th>
														<th>Pengirim</th>
														<th>Approval</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>

											<!--end: Datatable -->
										</div>
									</div>
								
									<!--end::Portlet-->
								</div>
							</div>

							<!--End::Section-->
							
							<!--Begin::Section-->
							<div class="row">
								<div class="col-xl-6 col-md-6">
									<!--begin::Portlet-->
									<div class="kt-portlet">
										<div class="kt-portlet__head">
											<div class="kt-portlet__head-label">
												<span class="kt-portlet__head-icon kt-hidden">
													<i class="la la-gear"></i>
												</span>
												<h3 class="kt-portlet__head-title">
													Inspection Aircraft Report
												</h3>
											</div>
										</div>
										<div class="kt-portlet__body">
											<!--begin: Datatable -->
											<table class="table table-striped- table-bordered table-hover table-checkable" id="tbl_ins_aircraft_report">
												<thead>
													<tr>
														<th>No</th>
														<th>Description</th>
														<th>File</th>
														<th>Status</th>
														<th>Approve</th>
														<th>Type</th>
														<th>Interval</th>
														<th>Inspection Date</th>
														<th>Pengirim</th>
														<th>Approval</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>

											<!--end: Datatable -->
										</div>
									</div>

									<!--end::Portlet-->
								</div>
								
								<div class="col-xl-6 col-md-6">
									<!--begin::Portlet-->
									<div class="kt-portlet">
										<div class="kt-portlet__head">
											<div class="kt-portlet__head-label">
												<span class="kt-portlet__head-icon kt-hidden">
													<i class="la la-gear"></i>
												</span>
												<h3 class="kt-portlet__head-title">
													Inspection Task Report
												</h3>
											</div>
										</div>
										<div class="kt-portlet__body">
											<!--begin: Datatable -->
											<table class="table table-striped- table-bordered table-hover table-checkable" id="tbl_ins_task_report">
												<thead>
													<tr>
														<th>No</th>
														<th>Description</th>
														<th>File</th>
														<th>Status</th>
														<th>Approve</th>
														<th>Inspection Date</th>
														<th>Pengirim</th>
														<th>Approval</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>

											<!--end: Datatable -->
										</div>
									</div>

									<!--end::Portlet-->
								</div>
							</div>

							<!--End::Section-->
							
							<!--Begin::Section-->
							<div class="row">
								<div class="col-xl-12">
									<!--begin::Portlet-->
									<div class="kt-portlet">
										<div class="kt-portlet__head">
											<div class="kt-portlet__head-label">
												<span class="kt-portlet__head-icon kt-hidden">
													<i class="la la-gear"></i>
												</span>
												<h3 class="kt-portlet__head-title">
													Aircraft Status
												</h3>
											</div>
											<?php if($data['is_admin']) { ?>
											<div class="kt-portlet__head-toolbar">
												<div class="kt-portlet__head-wrapper">
													<div class="kt-portlet__head-actions">
														<button type="button" class="btn btn-brand btn-elevate btn-icon-sm" onClick="loadFormAddKomponen()">
															<i class="la la-plus"></i>
															Tambah Data
														</button>
													</div>
												</div>
											</div>
											<?php } ?>
										</div>
										<div class="kt-portlet__body">
											<!--begin: Datatable -->
											<table class="table table-striped- table-bordered table-hover table-checkable" id="tbl_pesawat_komponen">
												<thead>
													<tr>
														<th rowspan="2">Description</th>
														<th rowspan="2">Information</th>
														<th rowspan="2">TSN (hrs)</th>
														<th rowspan="2">TSO</th>
														<th colspan="2">Inspection</th>
														<th rowspan="2">Last Inspection</th>
														<th rowspan="2">Next Inspection</th>
														<th rowspan="2">Remaining</th>
														<th rowspan="2">Action</th>
													</tr>
													<tr>
														<th>Type</th>
														<th>Interval</th>
													</tr>
												</thead>
												<tbody>
													
												</tbody>
											</table>

											<!--end: Datatable -->
										</div>
									</div>

									<!--end::Portlet-->
								</div>
							</div>

							<!--End::Section-->

							<!--Begin::Section-->
							<div class="row">
								<div class="col-xl-12">
									<!--begin::Portlet-->
									<div class="kt-portlet">
										<div class="kt-portlet__head">
											<div class="kt-portlet__head-label">
												<span class="kt-portlet__head-icon kt-hidden">
													<i class="la la-gear"></i>
												</span>
												<h3 class="kt-portlet__head-title">
													Task Status
												</h3>
											</div>
											<?php if($data['is_admin']) { ?>
											<div class="kt-portlet__head-toolbar">
												<div class="kt-portlet__head-wrapper">
													<div class="kt-portlet__head-actions">
														<button type="button" class="btn btn-brand btn-elevate btn-icon-sm" onClick="loadFormAddTask()">
															<i class="la la-plus"></i>
															Tambah Data
														</button>
													</div>
												</div>
											</div>
											<?php } ?>
										</div>
										<div class="kt-portlet__body">
											<!--begin: Datatable -->
											<table class="table table-striped- table-bordered table-hover table-checkable" id="tbl_task_status">
												<thead>
													<tr>
														<th>No</th>
														<th>Description</th>
														<th>PIC</th>
														<th>Date of Issue</th>
														<th>Interval</th>
														<th>Next Due</th>
														<th>Inspection</th>
														<th>Remaining (Months)</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
													
												</tbody>
											</table>

											<!--end: Datatable -->
										</div>
									</div>

									<!--end::Portlet-->
								</div>
							</div>

							<!--End::Section-->
							
							<!--Begin::Section-->
							
							<div class="row">
								<div class="col-xl-12">

									<!--begin:: Widgets/Support Tickets -->
									<div class="kt-portlet kt-portlet--height-fluid">
										<div class="kt-portlet__head">
											<div class="kt-portlet__head-label">
												<h3 class="kt-portlet__head-title">
													Open Item and Scheduling
												</h3>
											</div>
											<div class="kt-portlet__head-toolbar">
												<div class="kt-portlet__head-wrapper">
													<div class="kt-portlet__head-actions">
														<button type="button" class="btn btn-brand btn-elevate btn-icon-sm" onClick="loadFormAddItem()">
															<i class="la la-plus"></i>
															Tambah Data
														</button>
													</div>
												</div>
											</div>
										</div>
										<div class="kt-portlet__body">
											<div class="kt-widget3" id="tbl_scheduling">
											</div>
										</div>
									</div>

									<!--end:: Widgets/Support Tickets -->
								</div>
							
							</div>

							<!--End::Section-->
							
							<!--Begin::Section-->
							<div class="row">
								<div class="col-xl-12">
									<!--begin::Portlet-->
									<div class="kt-portlet">
										<div class="kt-portlet__head">
											<div class="kt-portlet__head-label">
												<span class="kt-portlet__head-icon kt-hidden">
													<i class="la la-gear"></i>
												</span>
												<h3 class="kt-portlet__head-title">
													Flight Hour Trends
												</h3>
											</div>
										</div>
										<div class="kt-portlet__body">
											<div id="chart_flight" style="height: 500px;"></div>
										</div>
									</div>

									<!--end::Portlet-->
								</div>
							</div>

							<!--End::Section-->

							
						</div>

						<!-- end:: Content -->
						
						
						<!-- Modal BEGIN:ADD TSN Aircraft Report-->
						<div id="modalFormAddTSNAircraftReport" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<!-- Form starts.  -->
									  <div class="modal-header">			                        
										<h4 class="modal-title">Tambah</h4>
									  </div>
									  <div class="modal-body">									  		 											
											<form class="form-horizontal" role="form" id="add_form_tsn_aircraft_report" action="#" autocomplete="nope"  enctype="multipart/form-data">
												<input type="hidden" value="" name="id"/> 		
												<input type="hidden" value="" name="save_method"/> 										
												
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label class="form-control-label" for="nama">Description</label>
															<select class="form-control select2" name="komponen_id" id="filter_komponen"></select>
														</div>
														
														<div class="form-group">
															<label class="control-label" for="nama">TSN of Date</label>
															<input type="text" name="date_of_tsn" class="form-control datepicker">
														</div>
														
														<div class="form-group">
															<label class="control-label" for="nama">TSN</label>
															<input type="text" name="tsn" class="form-control destouchspin">
															<span class="form-text text-muted">(in hours)</span>
														</div>
														
														<div class="form-group">
															<label>File Support</label>
															<div></div>
															<div class="custom-file">
																<input type="file" class="custom-file-input" id="filename" name="filename">
																<label class="custom-file-label" for="filename">Choose file</label>
															</div>
														</div>
													</div>
												</div>
											</form>
																		
											<div id="modal_message"></div>
											<div class="clearfix"></div>
									  </div>	<!--END modal-body-->
									  <div class="modal-footer">										
										<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
										<button type="button" onClick="save_tsn_aircraft_report()" class="btn btn-sm btn-success">Save</button>
									  </div>
								</div>	<!--END modal-content-->
							</div>	<!--END modal-dialog-->
						</div>
						<!-- Modal END:ADD TSN Aircraft Report-->
						
						<!-- Modal BEGIN:ADD Komponen-->
						<div id="modalFormAddKomponen" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<!-- Form starts.  -->
									  <div class="modal-header">			                        
										<h4 class="modal-title">Tambah</h4>
									  </div>
									  <div class="modal-body">									  		 											
											<form class="form-horizontal" role="form" id="add_form_komponen" action="#" autocomplete="nope">
												<input type="hidden" value="" name="id"/> 		
												<input type="hidden" value="" name="save_method"/> 										
												
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label class="form-control-label" for="nama">Description</label>
															<select class="form-control select2" name="description_id" id="filter_description"></select>
														</div>
														
														<div class="form-group">
															<label class="form-control-label" for="nama">Manufacturer</label>
															<select class="form-control select2" name="manufacturer_id" id="filter_manufacturer"></select>
														</div>
														
														<div class="form-group">
															<label class="control-label" for="nama">Aircraft Regiistration</label>
															<input type="text" name="aircraft_registration" class="form-control">
															<span class="form-text text-muted">(for Airframe)</span>
														</div>
														
														<div class="form-group">
															<label class="control-label" for="nama">Registration Number</label>
															<input type="text" name="registration_number" class="form-control">
															<span class="form-text text-muted">(for Airframe)</span>
														</div>
														
														<div class="form-group">
															<label class="control-label" for="nama">Part Number/ Model</label>
															<input type="text" name="model" class="form-control">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label" for="nama">Serial Number</label>
															<input type="text" name="serial_number" class="form-control">
														</div>
														
														<div class="form-group">
															<label class="control-label" for="nama">Date of Install</label>
															<input type="text" name="date_of_install" class="form-control datepicker">
														</div>
														
														<div class="form-group">
															<label class="control-label" for="nama">TSN</label>
															<input type="text" name="tsn" class="form-control destouchspin">
															<span class="form-text text-muted">(in hours)</span>
														</div>
														
														<div class="form-group">
															<label class="control-label" for="nama">TSO</label>
															<input type="text" name="tso" class="form-control destouchspin">
														</div>
												  
														<div class="form-group">
															<label class="form-control-label" for="nama">Approval</label>
															<select class="form-control select2" name="posisi_pic_id" id="filter_posisi"></select>
														</div>
													</div>
												</div>
											</form>
																		
											<div id="modal_message"></div>
											<div class="clearfix"></div>
									  </div>	<!--END modal-body-->
									  <div class="modal-footer">										
										<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
										<button type="button" onClick="saveKomponen()" class="btn btn-sm btn-success">Save</button>
									  </div>
								</div>	<!--END modal-content-->
							</div>	<!--END modal-dialog-->
						</div>
						<!-- Modal END:ADD Komponen-->
						
						<!-- Modal BEGIN:ADD Interval Komponen-->
						<div id="modalFormAddIntKomponen" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<!-- Form starts.  -->
									  <div class="modal-header">			                        
										<h4 class="modal-title">Tambah</h4>
									  </div>
									  <div class="modal-body">									  		 											
											<form class="kt-form" role="form" id="add_form_int_komponen" action="#" autocomplete="nope">
												<input type="hidden" value="" name="id"/> 		
												<input type="hidden" value="" name="save_method"/> 										
												<input type="hidden" value="" name="komponen_id"/> 	

												<div class="form-group row">
													<label class="col-3 col-form-label" for="filter_interval">Type</label>
													<div class="col-6">
														<select class="form-control select2" name="interval_id" id="filter_interval"></select>
													</div>
												</div>
												
												<div class="form-group row">
													<label class="col-3 col-form-label" for="interval_id">Interval</label>
													<div class="col-4">
														<input type="text" name="lama_interval" id="lama_interval" class="form-control touchspin">
													</div>
													<div class="col-5">
														<select class="form-control select2" name="kategori_interval" id="kategori_interval">
															<option value="1">Hours</option>
															<option value="2">Years</option>
														</select>
													</div>
												</div>
												
											</form>
																		
											<div id="modal_message"></div>
											<div class="clearfix"></div>
									  </div>	<!--END modal-body-->
									  <div class="modal-footer">										
										<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
										<button type="button" onClick="saveIntKomponen()" class="btn btn-sm btn-success">Save</button>
									  </div>
								</div>	<!--END modal-content-->
							</div>	<!--END modal-dialog-->
						</div>
						<!-- Modal END:ADD Interval Komponen-->
						
						<!-- Modal BEGIN:ADD FORM TABEL TSN REPORT-->
						<div id="modalFormTsnReport" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									  <div class="modal-header">			                        
										<h4 class="modal-static-title">Tambah</h4>
									  </div>
									  <div class="modal-body">		
											<!--begin: Datatable -->
											<table class="table table-striped- table-bordered table-hover table-checkable" id="tbl_form_tsn_aircraft_report">
												<thead>
													<tr>
														<th>No</th>
														<th>Description</th>
														<th>TSN Date</th>
														<th>Total TSN</th>
														<th>File</th>
														<th>Status</th>
														<th>Approve</th>
														<th>Pengirim</th>
														<th>Approval</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
											<!--end: Datatable -->
									  </div>
									  <!--END modal-body-->
									  <div class="modal-footer">										
										<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
									  </div>
								</div>	<!--END modal-content-->
							</div>	<!--END modal-dialog-->
						</div>
						<!-- Modal END:ADD FORM TABEL TSN REPORT-->
						
						<!-- Modal BEGIN:ADD Task-->
						<div id="modalFormAddTask" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<!-- Form starts.  -->
									  <div class="modal-header">			                        
										<h4 class="modal-title">Tambah</h4>
									  </div>
									  <div class="modal-body">									  		 											
											<form class="form-horizontal" role="form" id="add_form_task" action="#" autocomplete="nope">
												<input type="hidden" value="" name="id"/> 		
												<input type="hidden" value="" name="save_method"/> 										
												
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label class="form-control-label" for="nama">Description</label>
															<select class="form-control select2" name="description_id" id="filter_description_task"></select>
														</div>
														
														<div class="form-group">
															<label class="control-label" for="nama">Date of Issue</label>
															<input type="text" name="date_of_issue" class="form-control datepicker">
														</div>
														
														<div class="form-group">
															<label class="control-label" for="nama">Interval</label>
															<input type="text" name="interval" class="form-control touchspin">
															<input type="hidden" value="2" name="kategori_interval"/> 
															<span class="form-text text-muted">(in years)</span>
														</div>
														
														<div class="form-group">
															<label class="form-control-label" for="nama">Approval</label>
															<select class="form-control select2" name="posisi_pic_id" id="filter_posisi_task"></select>
														</div>
													</div>
												</div>
											</form>
																		
											<div id="modal_message"></div>
											<div class="clearfix"></div>
									  </div>	<!--END modal-body-->
									  <div class="modal-footer">										
										<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
										<button type="button" onClick="saveTask()" class="btn btn-sm btn-success">Save</button>
									  </div>
								</div>	<!--END modal-content-->
							</div>	<!--END modal-dialog-->
						</div>
						<!-- Modal END:ADD Task-->
						
						<!-- Modal BEGIN:ADD Open Item and Scheduling-->
						<div id="modalFormAddItem" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<!-- Form starts.  -->
									  <div class="modal-header">			                        
										<h4 class="modal-title">Tambah</h4>
									  </div>
									  <div class="modal-body">									  		 											
											<form class="form-horizontal" role="form" id="add_form_item" action="#" autocomplete="nope"  enctype="multipart/form-data">
												<input type="hidden" value="" name="id"/> 		
												<input type="hidden" value="" name="save_method"/> 										
												
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label class="form-control-label" for="nama">Item</label>
															<select class="form-control select2" name="kategori_id" id="kategori_id">
																<option value="1">Aircraft</option>
																<option value="2">Task</option>
															</select>
														</div>
														
														<div class="form-group">
															<label class="form-control-label" for="nama">Description</label>
															<select class="form-control select2" name="ref_id" id="filter_item"></select>
														</div>
														
														<div class="form-group">
															<label class="form-control-label" for="filename">File</label>
															<div class="custom-file">
																<input type="file" class="custom-file-input" id="filename" name="filename">
																<label class="custom-file-label" for="filename">Choose file</label>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label" for="nama">Note</label>
															<textarea class="form-control" name="deskripsi" rows="3"></textarea>
														</div>
													</div>
												</div>
											</form>
																		
											<div id="modal_message"></div>
											<div class="clearfix"></div>
									  </div>	<!--END modal-body-->
									  <div class="modal-footer">										
										<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
										<button type="button" onClick="saveItem()" class="btn btn-sm btn-success">Save</button>
									  </div>
								</div>	<!--END modal-content-->
							</div>	<!--END modal-dialog-->
						</div>
						<!-- Modal END:ADD Open Item and Scheduling-->
						
						<!-- Modal BEGIN:ADD FORM TABEL INS TASK REPORT-->
						<div id="modalFormInsTaskReport" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									  <div class="modal-header">			                        
										<h4 class="modal-static-title">Tambah</h4>
									  </div>
									  <div class="modal-body">											
											<form class="kt-form kt-form--label-right form-horizontal" role="form" id="add_form_ins_task_report" action="#" autocomplete="nope"  enctype="multipart/form-data">
												<input type="hidden" value="" name="id"/> 							
												<input type="hidden" value="" name="task_id"/> 
												<div class="kt-portlet__body">
													<div class="form-group row">
														<label for="date_of_ins" class="col-3 col-form-label">Date of Inspection</label>
														<div class="col-6">
															<input type="text" name="date_of_ins" id="date_of_ins" class="form-control datepicker">
														</div>
													</div>
													<div class="form-group row">
														<label for="filename" class="col-3 col-form-label">File</label>
														<div class="col-6">
															<div class="custom-file">
																<input type="file" class="custom-file-input" id="filename" name="filename">
																<label class="custom-file-label" for="filename">Choose file</label>
															</div>
														</div>
													</div>
												</div>
												<div class="kt-portlet__foot">
													<div class="kt-form__actions">
														<div class="row">
															<div class="col-3">
															</div>
															<div class="col-6">
																<button type="button" class="btn btn-success" onClick="saveInsTaskReport()">Tambah</button>
															</div>
														</div>
													</div>
												</div>
											</form>
										
											<div id="modal_message"></div>
											<div class="kt-separator kt-separator--border-dashed kt-separator--space-xs"></div>
											<!--begin: Datatable -->
											<table class="table table-striped- table-bordered table-hover table-checkable" id="form_tbl_ins_task_report">
												<thead>
													<tr>
														<th>No</th>
														<th>Description</th>
														<th>Inspection Date</th>
														<th>File</th>
														<th>Status</th>
														<th>Approve</th>
														<th>Pengirim</th>
														<th>Approval</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
											<!--end: Datatable -->
									  </div>
									  <!--END modal-body-->
									  <div class="modal-footer">										
										<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
									  </div>
								</div>	<!--END modal-content-->
							</div>	<!--END modal-dialog-->
						</div>
						<!-- Modal END:ADD FORM TABEL INS TASK REPORT-->
						
						<!-- Modal BEGIN:ADD FORM TABEL INS KOMPONEN REPORT-->
						<div id="modalFormInsKomponenReport" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									  <div class="modal-header">			                        
										<h4 class="modal-title">Tambah</h4>
									  </div>
									  <div class="modal-body">											
											<form class="form-horizontal" role="form" id="add_form_ins_komponen_report" action="#" autocomplete="nope"  enctype="multipart/form-data">
												<input type="hidden" value="" name="id"/> 							
												<input type="hidden" value="" name="komponen_id"/> 
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label class="form-control-label" for="date_of_ins">Date of Inspection</label>
															<input type="text" name="date_of_ins" id="date_of_ins" class="form-control datepicker">
														</div>
														
														<div class="form-group">
															<label class="form-control-label" for="filename">File</label>
															<div class="custom-file">
																<input type="file" class="custom-file-input" id="filename" name="filename">
																<label class="custom-file-label" for="filename">Choose file</label>
															</div>
														</div>
													</div>
												</div>
											</form>
										
											<div id="modal_message"></div>
											<div class="clearfix"></div>
									  </div>	<!--END modal-body-->
									  <div class="modal-footer">										
										<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
										<button type="button" onClick="saveInsKomponenReport()" class="btn btn-sm btn-success">Save</button>
									  </div>
								</div>	<!--END modal-content-->
							</div>	<!--END modal-dialog-->
						</div>
						<!-- Modal END:ADD FORM TABEL INS KOMPONEN REPORT-->
						
						<!-- Modal BEGIN:ADD APPROVE INSPECT KOMPONEN-->
						<div id="modalApprovalTsnKomponen" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<!-- Form starts.  -->
									  <div class="modal-header">			                        
										<h4 class="modal-title">Approval</h4>
									  </div>
									  <div class="modal-body">									  		 											
											<form class="form-horizontal" role="form" id="approve_tsn_komponen" action="#" >
												<input type="hidden" value="" name="komponen_tsn_id"/> 

												<div class="form-group" align="center">
													<div class="col-lg-12">
														<div id="approval_text"></div>																	  
													</div>																							
												</div> 																					
												<div id="modal_approval_message"></div>
												<iframe id="pdf_frame" src="" width="100%" height="700px" frameborder="0"></iframe>		
												
											</form>
									  </div>	
									  <!--END modal-body-->
									  <div class="modal-footer">										
										<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
										<button type="button" onClick="approval_tsn_komponen('','','','')" class="btn btn-sm btn-success">Approve</button>
									  </div>
								</div>	<!--END modal-content-->
							</div>	<!--END modal-dialog-->
						</div>
						<!-- Modal END:ADD APPROVE INSPECT KOMPONEN-->
						
						<!-- Modal BEGIN:ADD APPROVE INSPECT KOMPONEN-->
						<div id="modalApprovalInsKomponen" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<!-- Form starts.  -->
									  <div class="modal-header">			                        
										<h4 class="modal-title">Approval</h4>
									  </div>
									  <div class="modal-body">									  		 											
											<form class="form-horizontal" role="form" id="approve_ins_komponen" action="#" >
												<input type="hidden" value="" name="komponen_inspeksi_id"/> 

												<div class="form-group" align="center">
													<div class="col-lg-12">
														<div id="approval_text"></div>																	  
													</div>																							
												</div> 																					
												<div id="modal_approval_message"></div>
												<iframe id="pdf_frame" src="" width="100%" height="700px" frameborder="0"></iframe>		
												
											</form>
									  </div>	
									  <!--END modal-body-->
									  <div class="modal-footer">										
										<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
										<button type="button" onClick="approval_ins_komponen('','','','')" class="btn btn-sm btn-success">Approve</button>
									  </div>
								</div>	<!--END modal-content-->
							</div>	<!--END modal-dialog-->
						</div>
						<!-- Modal END:ADD APPROVE INSPECT KOMPONEN-->
						
						<!-- Modal BEGIN:ADD APPROVE INSPECT TASK-->
						<div id="modalApprovalInsTask" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<!-- Form starts.  -->
									  <div class="modal-header">			                        
										<h4 class="modal-title">Approval</h4>
									  </div>
									  <div class="modal-body">									  		 											
											<form class="form-horizontal" role="form" id="approve_ins_task" action="#" >
												<input type="hidden" value="" name="task_inspeksi_id"/> 

												<div class="form-group" align="center">
													<div class="col-lg-12">
														<div id="approval_text"></div>																	  
													</div>																							
												</div> 																					
												<div id="modal_approval_message"></div>
												<iframe id="pdf_frame" src="" width="100%" height="700px" frameborder="0"></iframe>		
												
											</form>
									  </div>	
									  <!--END modal-body-->
									  <div class="modal-footer">										
										<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
										<button type="button" onClick="approval_ins_task('','','','')" class="btn btn-sm btn-success">Approve</button>
									  </div>
								</div>	<!--END modal-content-->
							</div>	<!--END modal-dialog-->
						</div>
						<!-- Modal END:ADD APPROVE INSPECT TASK-->
						
						<!-- Modal BEGIN:DELETE DATA-->										
						<div id="modalDeleteForm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-sm">
								<div class="modal-content">									
									<!-- Form starts.  -->	
									<form class="form-horizontal" role="form" id="delete_form" action="#">
									  <div class="modal-header">			                        
										<h4 class="modal-title">Hapus</h4>
									  </div>
									  <div class="modal-body">
											<input type="hidden" value="" name="id_delete_data"/> 											
											<input type="hidden" value="" name="tablename"/> 																					
											<div class="form-group" align="center">
												<div class="col-lg-12">
													<div id="delete_text"></div>																	  
												</div>																							
											</div> 
											<div class="form-group" align="center">
												<div class="col-lg-12">
													<b >Anda yakin ?!</b>	
												</div>	
											</div> 
											 <div id="modal_delete_message"></div>
									  </div>	<!--END modal-body-->
									  <div class="modal-footer">										
										<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
										<button type="button" id="btnDelete" onClick="data_delete('','','')" class="btn btn-sm btn-success">Hapus</button>								
									  </div>
								  </form>
								</div>	<!--END modal-content-->
							</div>	<!--END modal-dialog-->
						</div>
						<!-- Modal END:DELETE DATA-->	