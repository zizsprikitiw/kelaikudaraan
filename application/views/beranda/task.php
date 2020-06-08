<?php $this->load->view('layout/header'); ?>
						<script>
							var tasks_id = "<?php echo $tasks_id; ?>";
						</script>
						<div class="row margin-top-20">
                            <div class="col-md-12">
                                <!-- BEGIN TODO SIDEBAR -->
                                <div class="todo-ui">
                                    <!-- BEGIN TODO CONTENT -->
                                    <div class="todo-content">
                                        <div class="portlet light ">
                                            <!-- PROJECT HEAD -->
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <div class="todo-taskbody-user">
														<img class="todo-userpic pull-left" src="<?php echo base_url($this->config->item('uploads')['users_thumb50x50']); ?>/<?php echo $photo; ?>" onerror="this.src = '<?php echo base_url($this->config->item('assets')['custom_img']); ?>/50x50.png';" width="50px" height="50px">
														<span class="todo-username pull-left"><?php echo $nama_task; ?></span>
														<?php if ($user_id==$pic_id) { ?>
														<button type="button" class="todo-username-btn btn btn-circle btn-default btn-sm" onClick="loadFormUpdateTask()" >&nbsp;edit&nbsp;</button>
														<?php } ?>
													</div>
                                                </div>
                                                <div class="actions">
													<?php if($is_admin) { ?>
                                                    <div class="btn-group">
                                                        <a class="btn green btn-circle btn-sm" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> MANAGE
                                                            <i class="fa fa-angle-down"></i>
                                                        </a>
                                                        <ul class="dropdown-menu pull-right">
                                                            <li>
                                                                <a href="javascript:;"> New Task </a>
                                                            </li>
                                                            <li class="divider"> </li>
                                                            <li>
                                                                <a href="javascript:;"> Pending
                                                                    <span class="badge badge-danger"> 4 </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:;"> Completed
                                                                    <span class="badge badge-success"> 12 </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:;"> Overdue
                                                                    <span class="badge badge-warning"> 9 </span>
                                                                </a>
                                                            </li>
                                                            <li class="divider"> </li>
                                                            <li>
                                                                <a href="javascript:;"> Delete Project </a>
                                                            </li>
                                                        </ul>
                                                    </div>
													<?php } ?>
                                                </div>
                                            </div>
                                            <!-- end PROJECT HEAD -->
                                            <div class="portlet-body">
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-5">
														<!-- TASK COMMENTS -->
														<div class="form-group" id="task_deskripsi"></div>
														<div class="form-group">
															<div class="col-md-12">
																<h2>Comments</h2>
																<ul class="media-list" id="task_comments" ></ul>
															</div>
														</div>
														<!-- END TASK COMMENTS -->
														<!-- TASK COMMENT FORM -->
														<div class="form-group">
															<div class="col-md-12">
																<form id="add_comment" action="#" enctype="multipart/form-data">
																	<ul class="media-list margin-top-20">
																		<li class="media">
																			<a class="pull-left" href="javascript:;">
																				<img class="todo-userpic" src="<?php echo base_url($this->config->item('uploads')['users_thumb50x50']); ?>/<?php echo $user->photo; ?>" onerror="this.src = '<?php echo base_url($this->config->item('assets')['custom_img']); ?>/50x50.png';" width="27px" height="27px"> </a>
																			<div class="media-body">
																				<div class="form-group">
																					<textarea name="message" class="form-control todo-taskbody-taskdesc bg-grey-cararra" rows="4" placeholder="Type comment..."></textarea>
																				</div>
																				<div id="form_add_file_comment" style="display: none">
																					<div class="form-group">
																						<select class="form-control" name="jenis_file_task" id="filter_jenis_file_task">
																							<option>- Pilih -</option>
																						</select>
																					</div>
																					<div class="form-group">
																						<div class="fileinput fileinput-new" data-provides="fileinput">
																							<div class="input-group input-large">
																								<div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
																									<i class="fa fa-file fileinput-exists"></i>&nbsp;
																									<span class="fileinput-filename"> </span>
																								</div>
																								<span class="input-group-addon btn default btn-file">
																									<span class="fileinput-new"> Select file </span>
																									<span class="fileinput-exists"> Change </span>
																									<input type="file" name="filename" id="filename"> </span>
																								<a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
																							</div>
																						</div>
																						<div class="clearfix margin-top-10">
																							<span class="label label-success">NOTE!</span> <b>File support:</b> <i id="task_file_support"><?php echo $this->config->item('files')['doc_file_type']; ?>.</i>
																						</div>
																					</div>
																				</div>
																			</div>
																		</li>
																	</ul>
																	<div class="clearfix">
																		<button type="button" class="pull-right btn btn-sm btn-circle green" onClick="addComments()"> &nbsp; Submit &nbsp; </button>
																		<button type="button" class="pull-right btn btn-sm btn-circle green btn-outline" id="btn_add_file_comment"> &nbsp; Attach &nbsp; </button>
																	</div>
																</form>
															</div>
														</div>
														<!-- END TASK COMMENT FORM -->                
                                                    </div>
                                                    <div class="todo-tasklist-devider"> </div>
                                                    <div class="col-md-6 col-sm-7">
														<div class="tabbable-line">
															<ul class="nav nav-tabs ">
																<li class="active">
																	<a href="#tab_1" data-toggle="tab"> Dokumen Teknis </a>
																</li>
																<li>
																	<a href="#tab_2" data-toggle="tab"> Dokumen Lainnya </a>
																</li>
															</ul>
															<div class="tab-content">
																<div class="tab-pane active" id="tab_1">
																	<table class="table table-bordered table-hover" id="table_file_report_task">
																		<thead class="bg-green bg-font-green">
																			<tr>
																				<th> # </th>
																				<th> File </th>
																				<th> Tanggal </th>
																				<th> Status </th>
																				<th> Aksi </th>
																			</tr>
																		</thead>
																		<tbody></tbody>
																	</table>
																</div>
																<div class="tab-pane" id="tab_2">
																	<table class="table table-bordered table-hover" id="table_file_other_task">
																		<thead class="bg-green bg-font-green">
																			<tr>
																				<th> # </th>
																				<th> File </th>
																				<th> Tanggal </th>
																				<th> Aksi </th>
																			</tr>
																		</thead>
																		<tbody></tbody>
																	</table>
																</div>
															</div>
														</div>
                                                        
														
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END TODO CONTENT -->
                                </div>
                            </div>
                            <!-- END PAGE CONTENT-->
                        </div>
						
						<!-- Modal BEGIN:UPDATE TASK-->
						<div id="modalFormUpdateTask" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<!-- Form starts.  -->
									  <div class="modal-header">			                        
										<h4 class="modal-title">Tambah</h4>
									  </div>
									  <div class="modal-body">									  		 											
											<form class="form-horizontal" role="form" id="update_form_task" action="#" autocomplete="nope">
												<input type="hidden" value="" name="id"/> 	
												<input type="hidden" value="" name="save_method"/> 	

												<div class="form-group">
												  <label class="col-lg-4 control-label" for="nama">Status</label>
												  <div class="col-lg-6">
													<select class="form-control" name="status" id="filter_status"></select>
												  </div>							  
												</div>		

												<div class="form-group">
												  <label class="col-lg-4 control-label" for="nama">Progress</label>
												  <div class="col-lg-6">
													<input name="progress" id="progress" type="text" value="" />
												  </div>							  
												</div>		
												
												<div class="form-group">
												  <label class="col-lg-4 control-label" for="nama">Deskripsi</label>
												  <div class="col-lg-6">
													<textarea class="form-control" name="deskripsi" rows="3"></textarea>	
												  </div>							  
												</div>	
											</form>
																		
											<div id="modal_message"></div>
											<div class="clearfix"></div>
									  </div>	<!--END modal-body-->
									  <div class="modal-footer">										
										<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
										<button type="button" class="btn green" onClick="updateTask()">Save</button>
									  </div>
								</div>	<!--END modal-content-->
							</div>	<!--END modal-dialog-->
						</div>
						<!-- Modal END:UPDATE TASK-->
						
						<!-- Modal BEGIN:BACA DOKUMEN-->
						<div id="modalStatus" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-md">
								<div class="modal-content">					
									  <div class="modal-header">			                        
										<h4 class="modal-title">STATUS APPROVAL</h4>
									  </div>
									  <div class="modal-body">	
										<form class="form-horizontal">
											<div class="form-group">
											  <label class="col-lg-4 control-label" for="status">Approval</label>
											  <div class="col-lg-8">
												<div id="approval" class="form-control-static"></div>
											  </div>							  
											</div>			
											
											<div class="form-group">
											  <label class="col-lg-4 control-label" for="status">Status</label>
											  <div class="col-lg-8">
												<div id="status" class="form-control-static"></div>
											  </div>							  
											</div>		

											<div class="form-group">
											  <label class="col-lg-4 control-label" for="keterangan">Keterangan</label>
											  <div class="col-lg-8">
												<div id="keterangan" class="form-control-static"></div>
											  </div>							  
											</div>		
										</form>
										<div class="clearfix"></div>
									  </div>	<!--END modal-body-->
									  <div class="modal-footer">										
										<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>														
									  </div>
								  
								</div>	<!--END modal-content-->
							</div>	<!--END modal-dialog-->
						</div>
						<!-- Modal END:BACA DOKUMEN-->
						
						<!-- Modal BEGIN:BACA DOKUMEN-->
						<div id="modalLaporan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">					
									  <div class="modal-header">			                        
										<h4 class="modal-title">DOKUMEN</h4>
									  </div>
									  <div class="modal-body">	
											<div class="row" >
												<div class="col-lg-12">
													<div id="judul_laporan" align="left"></div>
												</div>
											</div>

											<iframe id="pdf_frame_laporan" src="" width="100%" height="700px" frameborder="0"></iframe>														
									  </div>	<!--END modal-body-->
									  <div class="modal-footer">										
										<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>														
									  </div>
								  
								</div>	<!--END modal-content-->
							</div>	<!--END modal-dialog-->
						</div>
						<!-- Modal END:BACA DOKUMEN-->
						
						<!-- Modal BEGIN:APPROVE DOKUMEN-->										
						<div id="modalApproval" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-lg">
								<div class="modal-content" >														
									  <div class="modal-header">			                        
										<h4 class="modal-title">Approval</h4>							
									  </div>
									  <div class="modal-body" >
											<!--<div class="form-group" align="right">
												<div class="col-lg-12">
													<button type="button" id="btnDelete" onClick="approval_laporan('','','','')" class="btn btn-sm btn-success">Approve</button>																 									
													<button type="button" class="btn btn-default" data-dismiss="modal"  aria-hidden="true">Close</button>
												</div>																							
											</div> 		-->
											
										<!-- Form starts.  -->	
										<form class="form-horizontal" role="form" id="approve_form" action="#">
											<input type="hidden" value="" name="task_file_id"/> 									
											<div class="form-group" align="center">
												<div class="col-lg-12">
													<div id="approval_text"></div>																	  
												</div>																							
											</div> 																					
											 <div id="modal_approval_message"></div>
											<iframe id="pdf_frame" src="" width="100%" height="700px" frameborder="0"></iframe>		
											<div class="row">
												<div class="col-md-12">
													<hr style="border-top: 1px solid #ccc;border-bottom: 1px solid #fff;" />
													<div class="col-md-12">
														<div class="form-group">
															<label>Keterangan: <i>min 50 huruf</i></label>
															<textarea class="form-control" id="komentar" name="komentar" rows="3"></textarea>
														</div>
													</div>
												</div>
											</div>
												
										</form>												
									  </div>	<!--END modal-body-->
									  <div class="modal-footer">	
											<button type="button" id="btnApprove" onClick="approval_laporan('','','','','1')" class="btn btn-sm btn-success">Approve</button>																 									
											<button type="button" id="btnReject" onClick="approval_laporan('','','','','2')" class="btn btn-sm btn-danger">Reject</button>																 									
											<button type="button" class="btn btn-default" data-dismiss="modal"  aria-hidden="true">Close</button>
									  </div>
								  
								</div>	<!--END modal-content-->
							</div>	<!--END modal-dialog-->
						</div>
						<!-- Modal END:APPROVE DOKUMEN-->	
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
										<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
										<button type="button" id="btnDelete" onClick="data_delete('','')" class="btn btn-sm btn-success">Hapus</button>								
									  </div>
								  </form>
								</div>	<!--END modal-content-->
							</div>	<!--END modal-dialog-->
						</div>
						<!-- Modal END:DELETE DATA-->				
	<?php $this->load->view('layout/quick_sidebar'); ?>	
<?php $this->load->view('layout/footer'); ?>	