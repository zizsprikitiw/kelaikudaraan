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
								Tabel Users Group
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
								  <th>User Group</th>
								  <th>Keterangan</th>
								  <th>Aksi</th>
								</tr>
							</thead>	
						</table>
						<!--end: Datatable -->
					</div>
				</div>
			</div>
			
			<div class="col-xl-12">
				<div class="kt-portlet kt-portlet--mobile">
					<div class="kt-portlet__head kt-portlet__head--lg">
						<div class="kt-portlet__head-label">
							<span class="kt-portlet__head-icon">
								<i class="kt-font-brand flaticon-clipboard"></i>
							</span>
							<h3 class="kt-portlet__head-title">
								Tabel Personil
							</h3>
						</div>
						<div class="kt-portlet__head-toolbar">
							<div class="kt-portlet__head-wrapper">
								<div class="kt-portlet__head-actions">
									<button type="button" class="btn btn-brand btn-elevate btn-icon-sm" onClick="data_modal_personil()">
										<i class="la la-plus"></i>
										Tambah Data
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="kt-portlet__body">
						<div class="row kt-margin-b-20">
							<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
								<label>User Group:</label>
								<select name="filter_user_group" id="filter_user_group" class="form-control">
									<option value="0" >-- Pilih --</option>
								</select>
							</div>
						</div>
						
						<!--begin: Datatable -->
						<table class="table table-striped- table-bordered table-hover table-checkable responsive no-wrap dataTable dtr-inline collapsed" id="table_group_personil">
							<thead>
								<tr>
								  <th>No</th>
								  <th>Nama</th>
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
							  <label class="col-lg-3 control-label" for="name">User Group</label>
							  <div class="col-lg-6">
								<input type="text" class="form-control" id="name" name="name" placeholder="Nama User Group">
							  </div>
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="description">Keterangan</label>
							  <div class="col-lg-6">
								<input type="text" class="form-control" placeholder="Keterangan User Group" id="description" name="description">
							  </div>
							</div> 							
							
							<div id="modal_message"></div>
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
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
						<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
						<button type="button" id="btnDelete" onClick="data_delete('','')" class="btn btn-sm btn-success">Hapus</button>								
					  </div>
				  </form>
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:DELETE DATA-->		
		
		<!-- Modal BEGIN:MENUS-->
		<div id="modalMenus" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">					
									
					  <div class="modal-header">			                        
						<h4 class="modal-title2">Menu</h4>
					  </div>
					  <div class="modal-body">
					  		 <div id="modal_menu_message"></div>
					  		<input type="hidden" value="" name="id_user_groups" id="id_user_groups"/>
							<table class="table table-striped- table-bordered table-hover table-checkable responsive no-wrap dataTable dtr-inline collapsed" id="table_menus">
								<thead>
									<tr>
									  <th>No</th>
									  <th>Menu</th>
									  <th>Pilih</th>
									</tr>
								</thead>	
							</table>																					
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
					  </div>
					  
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:MENUS-->
		
		<!-- Modal BEGIN:PERSONIL-->
		<div id="modalPersonil" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">					
									
					  <div class="modal-header">			                        
						<h4 class="modal-title3">Personil</h4>
					  </div>
					  <div class="modal-body">
					  		 <div id="modal_menu_message"></div>
					  		<input type="hidden" value="" name="id_user_groups_personil" id="id_user_groups_personil"/>
							<table class="table table-striped- table-bordered table-hover table-checkable responsive no-wrap dataTable dtr-inline collapsed" id="table_personil">
								<thead>
									<tr>
									  <th>No</th>
									  <th>Nama</th>
									  <th>Pilih</th>
									</tr>
								</thead>	
							</table>																						
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
					  </div>
					  
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:PERSONIL-->
		
		<!-- Modal BEGIN:DELETE DATA-->										
		<div id="modalDeletePersonil" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">									
					<!-- Form starts.  -->	
					<form class="form-horizontal" role="form" id="delete_form_personil" action="#">
					  <div class="modal-header">			                        
						<h4 class="modal-title6">Hapus Data</h4>
					  </div>
					  <div class="modal-body">
					  		<input type="hidden" value="" name="id_delete_data_personil"/> 																					
							<div class="form-group" align="center">
								<div class="col-lg-12">
									<div id="delete_text_personil"></div>																	  
								</div>																							
							</div> 
							<div class="form-group" align="center">
								<div class="col-lg-12">
									<b >Anda yakin ?!</b>	
								</div>	
							</div> 
							 <div id="modal_delete_message_personil"></div>
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
						<button type="button" id="btnDelete" onClick="data_delete_personil('','')" class="btn btn-sm btn-success">Hapus</button>								
					  </div>
				  </form>
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:DELETE DATA-->	
														
    </div>
	<!-- end:: Content -->