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
													<th>No</th>
													  <th>Nama</th>
													  <th>NIP</th>
													  <th>Username</th>							  
													  <th>Login</th>	
													  <th>Status</th>
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
		
<?PHP $this->load->view('layout/footer'); ?>	