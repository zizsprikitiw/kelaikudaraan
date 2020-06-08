<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<!-- begin:: Content Head -->
	
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-subheader__main">
			<h3 class="kt-subheader__title"><?php echo $data['page_title']; ?></h3>
		</div>
	</div>

	<!-- end:: Content Head -->
	
	<!-- begin:: Content -->
	<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
  
		<!--Begin::Section-->
		<div class="row">
			<div class="col-xl-12">
				<div class="kt-portlet kt-portlet--mobile">
					<div class="kt-portlet__head kt-portlet__head--lg">
						<div class="kt-portlet__head-label">
							<span class="kt-portlet__head-icon">
								<i class="kt-font-brand flaticon-clipboard"></i>
							</span>
							<h3 class="kt-portlet__head-title">
								Tabel Users
							</h3>
						</div>
						<div class="kt-portlet__head-toolbar">
							<div class="kt-portlet__head-wrapper">
								<div class="kt-portlet__head-actions">
									<button type="button" class="btn btn-brand btn-elevate btn-icon-sm" onClick="data_add()">
										<i class="la la-plus"></i>
										Tambah Data
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="kt-portlet__body">
						<!-- Message -->
						<div id="page_message"></div>
						<?php
							if ($message != '')
							{
								echo '<div id="infoMessage" class="alert alert-info">'.$message.'</div>';
							}
						?>
						<!-- End Message -->
						
						<!--begin: Datatable -->
						<table class="table table-striped- table-bordered table-hover table-checkable responsive no-wrap dataTable dtr-inline collapsed" id="table">
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
						</table>
						<!--end: Datatable -->
					</div>
				</div>
			</div>
		</div>

		<!--End::Section-->
			  		  
		<script type="text/javascript">
			
	  </script>
	  
	  <!-- Modal BEGIN:ADD DATA-->
		<div id="modalAddForm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<!-- Form starts.  -->
					<form class="form-horizontal" role="form" id="add_form" action="#" autocomplete="nope" >
					  <div class="modal-header">			                        
						<h4 class="modal-title">Tambah User</h4>
					  </div>
					  <div class="modal-body">									  		 											
							<input type="hidden" value="" name="id"/> 
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="nama">Nama</label>
							  <div class="col-lg-6">
								<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap">
							  </div>
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="nip">NIP</label>
							  <div class="col-lg-6">
								<input type="text" class="form-control" placeholder="Nomor Induk Pegawai" id="nip" name="nip">
							  </div>
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="email">Email</label>
							  <div class="col-lg-6">
								<input type="text" class="form-control" id="txt_email" name="txt_email" placeholder="Email">
							  </div>
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="username">Username</label>
							  <div class="col-lg-6">
								<input type="text" class="form-control" id="txt_username" name="txt_username" placeholder="Username" >
							  </div>
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="password">Password</label>
							  <div class="col-lg-6">
								<input type="password" class="form-control" id="txt_password" name="txt_password" placeholder="Password" >
							  </div>
							</div> 		
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="password_confirm">Ulangi Password</label>
							  <div class="col-lg-6">
								<input type="password" class="form-control" id="txt_password_confirm" name="txt_password_confirm" placeholder="Password">
							  </div>
							</div> 	
																					
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="users_groups">User Group</label>
							  <div class="col-lg-6">
									<select name="users_groups" id="users_groups" class="form-control">
										<option value="0" >--Pilih--</option>
									</select>
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
		
		
		<!-- Modal BEGIN:DELETE DATA-->										
		<div id="modalDeleteForm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">									
					<!-- Form starts.  -->	
					<form class="form-horizontal" role="form" id="delete_form" action="#">
					  <div class="modal-header">			                        
						<h4 class="modal-title">Hapus Data</h4>
					  </div>
					  <div class="modal-body">
					  		<input type="hidden" value="" name="id_delete_user"/> 																					
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
		
		<!-- Modal BEGIN:USERS GROUP-->										
		<div id="modalUsersGroup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">									
					<!-- Form starts.  -->	
					<form class="form-horizontal" role="form" id="users_group_form" action="#">
					  <div class="modal-header">			                        
						<h4 class="modal-titlef">Group User</h4>
					  </div>
					  <div class="modal-body">
					  		<input type="hidden" value="" name="id_user_for_ug" id="id_user_for_ug"/> 																					
							<div class="form-group" align="left">
								<div class="col-lg-12">
									<div id="users_group_text"></div>																	  
								</div>																							
							</div> 
							
							<div class="form-group" align="center">
								<div class="col-lg-12">
									<div id="select_user_group" align="left"></div>									
								</div>	
							</div> 
							 <div id="modal_users_group_message"></div>
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
						<button type="button" id="btnSimpanUg" onClick="data_edit_user_group('','')" class="btn btn-sm btn-success">Simpan</button>								
					  </div>
				  </form>
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:USERS GROUP-->
		
		<!-- Modal BEGIN:FUNGSIONAL-->										
		<div id="modalFungsional" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">	
					<div class="modal-header">			                        
						<h4 class="modal-title-fungsional">Fungsional</h4>
					  </div>
					  <div class="modal-body">		
					  	<div class="row" >
							<div class="col-lg-12">
								<div id="fungsional_text" align="left"></div>
							</div>
						</div>
						<div id="page_message_fungsional"></div>
						<br  />								
						<div class="row" id="div_AddFungsional" style="display:none">
							<div class="col-lg-12">
								<!-- Form starts.  -->
								<form class="form-horizontal" role="form"  id="fungsional_form" action="#">						  							  		 											
									<input type="hidden" value="" name="id_user_for_fungsional" id="id_user_for_fungsional"/> 
									<input type="hidden" value="" name="id_users_fungsional" id="id_users_fungsional"/>
										
									<div class="form-group">
									  <label class="col-lg-3 control-label" for="fungsional_name">Fungsional</label>
									  <div class="col-lg-6">
											<select name="fungsional_name" id="fungsional_name" class="form-control">
												<option value="-1" >-- Pilih --</option>
											</select>
									  </div>
									</div> 
									
									<div class="form-group">
									  <label class="col-lg-3 control-label" for="fungsional_tahun_awal">Tahun Awal</label>
									  <div class="col-lg-4">
										 <select name="fungsional_tahun_awal" id="fungsional_tahun_awal" class="form-control">
												<option value="-1" >-- Pilih --</option>
											</select>
									  </div>
									</div> 
									
									<div class="form-group">
									  <label class="col-lg-3 control-label" for="fungsional_tahun_akhir">Tahun Akhir</label>
									  <div class="col-lg-4">
										 <select name="fungsional_tahun_akhir" id="fungsional_tahun_akhir" class="form-control">
												<option value="-1" >-- Pilih --</option>
											</select>
									  </div>
									</div> 
									 <div id="modal_fungsional_message"></div>
									 <label class="col-lg-3 control-label" ></label>
									 <div class="form-group">							  
									  <div class="col-lg-4">
									  		<button type="button" onClick="show_add_fungsional(false,'','')" class="btn btn-sm btn-success">Batal</button>	
											<button type="button" onClick="data_save_fungsional()" class="btn btn-sm btn-success">Simpan</button>	
									  </div>
									</div> 
								</form>	
							</div>
						</div>		
						 						
						<button type="button" id="btnAddFungsional" onClick="show_add_fungsional(true,'add','')" class="btn btn-sm btn-success" >Tambah Fungsional</button>	
						<br />
						<br />
						<div class="row">
							<div class="col-lg-12">
								<!-- Table Page -->
								<div class="page-tables">
									<!-- Table -->
									<div class="table-responsive">
										<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="table_fungsional" width="100%">
											<thead style="background-color:#006699; color:#FFFFFF;" align="center">
												<tr>
												  <th style="max-width:60px"><b>No</b></th>
												  <th ><b>Fungsional</b></th>
												  <th style="max-width:140px"><b>Tahun Awal</b></th>
												  <th style="max-width:140px"><b>Tahun Akhir</b></th>
												  <th style="max-width:100px"><b>Aksi</b></th>
												</tr>
											</thead>													
										</table>						
										<div class="clearfix"></div>									
									</div>
								</div>
								<!-- Table Page -->
							</div>					
						</div>
						
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>														
					  </div>
				  			  
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:FUNGSIONAL-->
		
		<!-- Modal BEGIN:STRUKTURAL-->										
		<div id="modalStruktural" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">	
					<div class="modal-header">			                        
						<h4 class="modal-title-struktural">Struktural</h4>
					  </div>
					  <div class="modal-body">		
					  	<div class="row" >
							<div class="col-lg-12">
								<div id="struktural_text" align="left"></div>
							</div>
						</div>
						<div id="page_message_struktural"></div>
						<br  />								
						<div class="row" id="div_AddStruktural" style="display:none">
							<div class="col-lg-12">
								<!-- Form starts.  -->
								<form class="form-horizontal" role="form"  id="struktural_form" action="#">						  							  		 											
									<input type="hidden" value="" name="id_user_for_struktural" id="id_user_for_struktural"/> 
									<input type="hidden" value="" name="id_users_struktural" id="id_users_struktural"/>
										
									<div class="form-group">
									  <label class="col-lg-3 control-label" for="struktural_name">Struktural</label>
									  <div class="col-lg-6">
											<select name="struktural_name" id="struktural_name" class="form-control">
												<option value="-1" >-- Pilih --</option>
											</select>
									  </div>
									</div> 
									
									<div class="form-group">
									  <label class="col-lg-3 control-label" for="struktural_tahun_awal">Tahun Awal</label>
									  <div class="col-lg-4">
										 <select name="struktural_tahun_awal" id="struktural_tahun_awal" class="form-control">
												<option value="-1" >-- Pilih --</option>
											</select>
									  </div>
									</div> 
									
									<div class="form-group">
									  <label class="col-lg-3 control-label" for="struktural_tahun_akhir">Tahun Akhir</label>
									  <div class="col-lg-4">
										 <select name="struktural_tahun_akhir" id="struktural_tahun_akhir" class="form-control">
												<option value="-1" >-- Pilih --</option>
											</select>
									  </div>
									</div> 
									 <div id="modal_struktural_message"></div>
									 <label class="col-lg-3 control-label" ></label>
									 <div class="form-group">							  
									  <div class="col-lg-4">
									  		<button type="button" onClick="show_add_struktural(false,'','')" class="btn btn-sm btn-success">Batal</button>	
											<button type="button" onClick="data_save_struktural()" class="btn btn-sm btn-success">Simpan</button>	
									  </div>
									</div> 
								</form>	
							</div>
						</div>		
						 						
						<button type="button" id="btnAddStruktural" onClick="show_add_struktural(true,'add','')" class="btn btn-sm btn-success" >Tambah Struktural</button>	
						<br />
						<br />
						<div class="row">
							<div class="col-lg-12">
								<!-- Table Page -->
								<div class="page-tables">
									<!-- Table -->
									<div class="table-responsive">
										<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="table_struktural" width="100%">
											<thead style="background-color:#006699; color:#FFFFFF;" align="center">
												<tr>
												  <th style="max-width:60px"><b>No</b></th>
												  <th ><b>Struktural</b></th>
												  <th style="max-width:140px"><b>Tahun Awal</b></th>
												  <th style="max-width:140px"><b>Tahun Akhir</b></th>
												  <th style="max-width:100px"><b>Aksi</b></th>
												</tr>
											</thead>													
										</table>						
										<div class="clearfix"></div>									
									</div>
								</div>
								<!-- Table Page -->
							</div>					
						</div>
						
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>														
					  </div>
				  			  
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:STRUKTURAL-->
		
		<!-- Modal BEGIN:BIDANG-->										
		<div id="modalBidang" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">	
					<div class="modal-header">			                        
						<h4 class="modal-title-bidang">Bidang</h4>
					  </div>
					  <div class="modal-body">		
					  	<div class="row" >
							<div class="col-lg-12">
								<div id="bidang_text" align="left"></div>
							</div>
						</div>
						<div id="page_message_bidang"></div>
						<br  />								
						<div class="row" id="div_AddBidang" style="display:none">
							<div class="col-lg-12">
								<!-- Form starts.  -->
								<form class="form-horizontal" role="form"  id="bidang_form" action="#">						  							  		 											
									<input type="hidden" value="" name="id_user_for_bidang" id="id_user_for_bidang"/> 
									<input type="hidden" value="" name="id_users_bidang" id="id_users_bidang"/>
										
									<div class="form-group">
									  <label class="col-lg-3 control-label" for="bidang_name">Bidang</label>
									  <div class="col-lg-6">
											<select name="bidang_name" id="bidang_name" class="form-control">
												<option value="-1" >-- Pilih --</option>
											</select>
									  </div>
									</div> 
									
									<div class="form-group">
									  <label class="col-lg-3 control-label" for="bidang_tahun_awal">Tahun Awal</label>
									  <div class="col-lg-4">
										 <select name="bidang_tahun_awal" id="bidang_tahun_awal" class="form-control">
												<option value="-1" >-- Pilih --</option>
											</select>
									  </div>
									</div> 
									
									<div class="form-group">
									  <label class="col-lg-3 control-label" for="bidang_tahun_akhir">Tahun Akhir</label>
									  <div class="col-lg-4">
										 <select name="bidang_tahun_akhir" id="bidang_tahun_akhir" class="form-control">
												<option value="-1" >-- Pilih --</option>
											</select>
									  </div>
									</div> 
									 <div id="modal_bidang_message"></div>
									 <label class="col-lg-3 control-label" ></label>
									 <div class="form-group">							  
									  <div class="col-lg-4">
									  		<button type="button" onClick="show_add_bidang(false,'','')" class="btn btn-sm btn-success">Batal</button>	
											<button type="button" onClick="data_save_bidang()" class="btn btn-sm btn-success">Simpan</button>	
									  </div>
									</div> 
								</form>	
							</div>
						</div>		
						 						
						<button type="button" id="btnAddBidang" onClick="show_add_bidang(true,'add','')" class="btn btn-sm btn-success" >Tambah Bidang</button>	
						<br />
						<br />
						<div class="row">
							<div class="col-lg-12">
								<!-- Table Page -->
								<div class="page-tables">
									<!-- Table -->
									<div class="table-responsive">
										<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="table_bidang" width="100%">
											<thead style="background-color:#006699; color:#FFFFFF;" align="center">
												<tr>
												  <th style="max-width:60px"><b>No</b></th>
												  <th ><b>Bidang</b></th>
												  <th style="max-width:140px"><b>Tahun Awal</b></th>
												  <th style="max-width:140px"><b>Tahun Akhir</b></th>
												  <th style="max-width:100px"><b>Aksi</b></th>
												</tr>
											</thead>													
										</table>						
										<div class="clearfix"></div>									
									</div>
								</div>
								<!-- Table Page -->
							</div>					
						</div>
						
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>														
					  </div>
				  			  
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:BIDANG-->
														
    </div>
	<!-- end:: Content -->