<?PHP $this->load->view('layout/header'); ?>	
						<!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"> <?php echo $user_menu['page_title'] ?> </h1>
                        <!-- END PAGE TITLE-->
                        <!-- END PAGE HEADER-->
						<div class="row">
							<div class="col-sm-12 form-group">
								<div class="row">
									<div class="col-md-4">
										<label class="col-md-5 checkbox-inline"><b>
										  <input type="checkbox" name="chkSearch[]" id="chkSearch[]" value="modul"> Modul:</b>
										</label>								
										<div class="col-md-7">                               
											<select name="filter_modul" id="filter_modul" class="form-control"><option>--Pilih--</option></select> 
										</div>
									</div>
									<div class="col-md-2">
										<button type="button" class="btn btn-primary" id="btnSearch" onclick="data_search()"><i class="fa fa-search"></i> Cari</button>	
									</div>	
								</div>	
							</div>
                            <div class="col-md-12">
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet light bordered">
									<div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-settings font-dark"></i>
                                            <span class="caption-subject bold uppercase">Task</span>
                                        </div>
                                        <div class="actions">
                                            <div class="btn-group btn-group-devided">
                                                <button onClick="loadFormAdd()" class="btn sbold green" > Tambah Task
													<i class="fa fa-plus"></i>
												</button>
                                            </div>
                                            <div class="btn-group">
												<button class="btn green  btn-outline dropdown-toggle" data-toggle="dropdown">Tools
													<i class="fa fa-angle-down"></i>
												</button>
                                                <ul class="dropdown-menu pull-right" id="table_tools">
                                                    <li>
                                                        <a href="javascript:;" data-action="0" class="tool-action">
                                                            <i class="icon-printer"></i> Print</a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;" data-action="1" class="tool-action">
                                                            <i class="icon-check"></i> Copy</a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;" data-action="2" class="tool-action">
                                                            <i class="icon-doc"></i> PDF</a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;" data-action="3" class="tool-action">
                                                            <i class="icon-paper-clip"></i> Excel</a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;" data-action="4" class="tool-action">
                                                            <i class="icon-cloud-upload"></i> CSV</a>
                                                    </li>
                                                    <li class="divider"> </li>
                                                    <li>
                                                        <a href="javascript:;" data-action="5" class="tool-action">
                                                            <i class="icon-refresh"></i> Reload</a>
                                                    </li>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
										<table class="table table-bordered" id="table">
											<thead>
												<tr>
													<th>#</th>
													<th>Nama Task</th>
													<th>DE</th>
													<th>AWO</th>
													<th>Status</th>
													<th>Due Date</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
                                    </div>
                                </div>
                                <!-- END EXAMPLE TABLE PORTLET-->
                            </div>
                        </div>
				<!-- Modal BEGIN:ADD TASK-->
				<div id="modalAddForm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<!-- Form starts.  -->
							  <div class="modal-header">			                        
								<h4 class="modal-title">Tambah</h4>
							  </div>
							  <div class="modal-body">									  		 											
									<form class="form-horizontal" role="form" id="add_form" action="#" autocomplete="nope">
										<input type="hidden" value="" name="id"/> 		
										<input type="hidden" value="" name="save_method"/> 										
										
										<div class="row">
											<div class="col-md-6">
												<div class="clearfix">
													<label class="control-label" for="nama">Program</label>
													<select class="form-control" name="program_id" id="filter_program_form"></select>
												</div>
												
												<div class="clearfix">
													<label class="control-label" for="nama">Modul</label>
													<select class="form-control" name="modul_id" id="filter_modul_form"></select>
												</div>
												
												<div class="clearfix">
													<label class="control-label" for="nama">Nama Task</label>
													<input type="text" name="nama_task" class="form-control">
												</div>
										  
												<div class="clearfix">
													<label class="control-label" for="nama">Deskripsi</label>
													<textarea class="form-control" name="deskripsi" rows="3"></textarea>							  
												</div>
										  
												<div class="clearfix">
													<label class="control-label" for="nama">Waktu</label>
													<div class="input-group date date-picker input-daterange" data-date-format="yyyy-mm-dd" today-highlight="true">
														<input type="text" class="form-control" name="start_date" readonly>
														<span class="input-group-addon"> to </span>
														<input type="text" class="form-control" name="due_date" readonly>
													</div>															
												</div>
											</div>
											<div class="col-md-6">
												<div class="clearfix">
													<label class="control-label" for="nama">DE</label>
													<select class="mt-multiselect btn btn-default" name="posisi_pic_id" id="filter_pic"></select>						  
												</div>
										
												<div class="clearfix">
													<label class="control-label" for="nama">AWO/ Approval</label>
													<select class="mt-multiselect btn btn-default" name="posisi_approval_id" id="filter_approval"></select>						  
												</div>
										  
												<div class="clearfix">
													<label class="col-lg-3 control-label" for="nama">Member</label>
													<select class="mt-multiselect btn btn-default" multiple="multiple" name="member_id[]" id="filter_member"></select>						  
												</div>
											</div>
										</div>
									</form>
																
									<div id="modal_message"></div>
									<div class="clearfix"></div>
							  </div>	<!--END modal-body-->
							  <div class="modal-footer">										
								<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
								<button type="button" class="btn green" onClick="saveTask()">Save</button>
							  </div>
						</div>	<!--END modal-content-->
					</div>	<!--END modal-dialog-->
				</div>
				<!-- Modal END:ADD TASK-->
	<?php $this->load->view('layout/quick_sidebar'); ?>	
<?PHP $this->load->view('layout/footer'); ?>	