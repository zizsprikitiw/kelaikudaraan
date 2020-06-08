<?PHP $this->load->view('layout/header'); ?>	
						<!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"> <?php echo $user_menu['page_title'] ?> </h1>
                        <!-- END PAGE TITLE-->
                        <!-- END PAGE HEADER-->
						<div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet light bordered">
									<div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-settings font-dark"></i>
                                            <span class="caption-subject bold uppercase">Setting Menu</span>
                                        </div>
                                        <div class="actions">
                                            <div class="btn-group btn-group-devided">
                                                <button onClick="loadFormAdd()" class="btn sbold green" > Tambah Menu
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
													<th>Menu</th>
													<th>Link</th>
													<th>Tampil</th>
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
						
		<!-- Modal BEGIN:ADD DATA-->
		<div id="modalAddForm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<!-- Form starts.  -->
					<form class="form-horizontal" role="form" id="add_form" action="#" autocomplete="nope">
					  <div class="modal-header">			                        
						<h4 class="modal-title">Tambah</h4>
					  </div>
					  <div class="modal-body">									  		 											
							<input type="hidden" value="" name="id"/> 							
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="nama">Menu</label>
							  <div class="col-lg-9">
								<input type="text" class="form-control" id="nama" name="nama" placeholder="Menu">
							  </div>							  
							</div>
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="singkatan">Halaman</label>
							  <div class="col-lg-9">
								<input type="text" class="form-control" id="halaman" name="halaman" placeholder="Judul Halaman">
							  </div>							  
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="url">Link Halaman</label>
							  <div class="col-lg-9">
								<input type="text" class="form-control" id="url" name="url" placeholder="Link Halaman (tanpa diawali dan diakhiri tanda /)">
							  </div>							  
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="ref_id">Sub Menu Dari</label>							  		
								<div class="col-lg-9">
									<select name="ref_id" id="ref_id" class="form-control">
										<option value="" >--Pilih--</option>
									</select>
								</div>							  										  
							</div>																	
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="icon">Icon</label>
							  <div class="col-lg-5">
								<input type="text" class="form-control" id="icon" name="icon" placeholder="Icon Menu Utama">
							  </div>
							  <div class="col-lg-4">
							  		<button type="button" id="btnIcon" onClick="show_icon()" class="btn btn-sm btn-success">Referensi</button>
							  </div>	  
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="direct_url">Direct URL</label>
							  <div class="col-lg-9">
								<input type="text" class="form-control" id="direct_url" name="direct_url" placeholder="Link Halaman Lengkap">
							  </div>							  
							</div>
														
							<div id="modal_message"></div>
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
						<button type="button" id="btnSave" onClick="data_save()" class="btn btn-sm btn-success">Simpan</button>								
					  </div>
				  </form>
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:ADD DATA-->
		
<?PHP $this->load->view('layout/footer'); ?>	