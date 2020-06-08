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
								Tabel Menu
							</h3>
						</div>
						<div class="kt-portlet__head-toolbar">
							<div class="kt-portlet__head-wrapper">
								<div class="kt-portlet__head-actions">
									<button type="button" class="btn btn-brand btn-elevate btn-icon-sm" onClick="data_add()">
										<i class="la la-plus"></i>
										Tambah Menu
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
						<table class="table table-striped- table-bordered table-hover table-checkable responsive no-wrap dataTable dtr-inline collapsed" id="table">
							<thead>
								<tr>
								  <th>No</th>
								  <th>Menu</th>
								  <th>Link</th>
								  <th>Tampil</th>
								  <th>Aksi</th>
								</tr>
							</thead>													
						</table>						
					</div>
				</div>
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
		
		<!-- Modal BEGIN:ADD DATA SUB PROYEK-->
		<div id="modalAddSubProyek" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<!-- Form starts.  -->
					<form class="form-horizontal" role="form" id="add_form_sub_proyek" action="#" autocomplete="nope">
					  <div class="modal-header">			                        
						<h4 class="modal-title">Tambah</h4>
					  </div>
					  <div class="modal-body">
					  		<input type="hidden" value="" name="ref_id"/>							  		 											
							<input type="hidden" value="" name="id_sub_proyek"/> 							
							
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
							  <label class="col-lg-3 control-label" for="ref_id">Tombol Menu</label>							  		
								<div class="col-lg-9">
									<div id="div_button_id">
									</div>									
								</div>							  										  
							</div>																	
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="icon">Icon</label>
							  <div class="col-lg-5">
								<input type="text" class="form-control" id="icon" name="icon" placeholder="Icon Tombol">
							  </div>
							  <div class="col-lg-4">
							  		<button type="button" id="btnIcon" onClick="show_icon()" class="btn btn-sm btn-success">Referensi</button>
							  </div>	  
							</div> 														 
														
							<div id="modal_message_sub"></div>
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
						<button type="button" id="btnSave" onClick="data_save_sub_proyek()" class="btn btn-sm btn-success">Simpan</button>								
					  </div>
				  </form>
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:ADD DATA SUB PROYEK-->
		
		<!-- Modal BEGIN:ICONS-->
		<div id="modalIcons" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">					
									
					  <div class="modal-header">			                        
						<h4 class="modal-title2">Referensi Icon</h4>
					  </div>
					  <div class="modal-body">
					  		<input type="hidden" value="" name="id_tabs" id="id_tabs"/> 									  		 											
							<ul id="myTab" class="nav nav-tabs">
							  <li class="active"><a href="#home" data-toggle="tab">Home</a></li>
							  <li><a href="#profile" data-toggle="tab">Profile</a></li>
							  <li><a href="#cont" data-toggle="tab">Content</a></li>
							</ul>
							<div id="myTabContent" class="tab-content">
							  <div class="tab-pane active" >
								<!-- Table Page -->
								<div class="page-tables">
									<!-- Table -->
									<div class="table-responsive">
										<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="table_icons" width="100%">
											<thead style="background-color:#006699; color:#FFFFFF;">
												<tr>
												  <th width="60px"><b>No</b></th>
												  <th ><b>Nama</b></th>
												  <th width="80px"><b>Icon</b></th>												  
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
		<!-- Modal END:ICONS-->
		
		
	</div>
	<!-- end:: Content -->