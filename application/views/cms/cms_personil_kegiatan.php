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
								Personil Kegiatan
							</h3>
						</div>
						<div class="kt-portlet__head-toolbar">
							<div class="kt-portlet__head-wrapper">
								<div class="kt-portlet__head-actions">
									<button type="button" class="btn btn-brand btn-elevate btn-icon-sm" onClick="data_add()" id="btnAddPersonil">
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
						
						<div class="row kt-margin-b-20">
							<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
								<label>Tahun:</label>
								<select name="filter_tahun" id="filter_tahun" class="form-control">
									<option value="" >-- Pilih --</option>
								</select>
							</div>
							<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
								<label>Pusat:</label>
								<select name="filter_pusat" id="filter_pusat" class="form-control">
									<option value="" >-- Pilih --</option>
								</select> 
							</div>
							<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
								<label>Judul:</label>
								<select name="filter_judul" id="filter_judul" class="form-control">
									<option value="" >-- Pilih --</option>
								</select> 		
							</div>
						</div>
						
						<h3 id="judul_proyek"></h3>	
						
						<!--begin: Datatable -->
						<table class="table table-striped- table-bordered table-hover table-checkable responsive no-wrap dataTable dtr-inline collapsed" id="tablePersonil">
							<thead>
								<tr>
								  <th>No</th>
								  <th>Nama</th>
								  <th>Posisi</th>
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
					  <div class="modal-header">			                        
						<h4 class="modal-title">Tambah</h4>
					  </div>
					  <div class="modal-body">	
					  	<!-- Form starts.  -->
						<form class="form-horizontal" role="form" id="add_form" action="#" autocomplete="nope">								  		 											
							<input type="hidden" value="" name="id" id="id"/> 											
							<input type="hidden" value="" name="proyek_id" id="proyek_id"/>	
							<div class="form-group" >
							  <label class="col-lg-2 control-label" for="id_posisi_as">Posisi</label>
							  <div class="col-lg-4">
									<select name="id_posisi_as" id="id_posisi_as" class="form-control" >
										<option value="0" >Struktural</option>
										<option value="1" >Kegiatan</option>
									</select>
							  </div>
							</div>
							
							<div class="form-group" >
							  <label class="col-lg-2 control-label"  for="id_posisi_kegiatan"></label>
							  <div class="col-lg-8">
									<select name="id_posisi_kegiatan" id="id_posisi_kegiatan" class="form-control" >
										<option value="0" >-- Pilih --</option>
									</select>
							  </div>
							</div>	
							
							<div class="form-group" id="div_posisi_gl_ld" style="display:none">
							  <label class="col-lg-2 control-label"  for="id_posisi_gl_ld"></label>
							  <div class="col-lg-8">
									<select name="id_posisi_gl_ld" id="id_posisi_gl_ld" class="form-control" >
										<option value="0" >-- Pilih --</option>
									</select>
							  </div>
							</div>															
														
							<div class="form-group" id="div_nama" style="display:none">
							  <label class="col-lg-2 control-label" for="nama" id="lbl_nama">Nama</label>
							  <div class="col-lg-8">
								<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama">
								<input type="hidden" value="" name="user_id" id="user_id"/>								
							  </div>
							  <div class="col-lg-2">
									<button type="button" id="btnSearch" onClick="data_search()" class="btn btn-sm btn-primary">Cari</button>		  
							  </div>							  
							</div>																									
							
							<div id="modal_message"></div>
							
							<div class="form-group" >
								<div class="col-lg-3">
								</div>
								<div class="col-lg-8"  align="right">
									<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
									<button type="button" id="btnSave" onClick="data_save()" class="btn btn-sm btn-success">Simpan</button>	
								</div>
							</div>
						 </form>
							<div id="div_search_nama" style="display:none">
								<!--begin: Datatable -->
								<table class="table table-striped- table-bordered table-hover table-checkable responsive no-wrap dataTable dtr-inline collapsed" id="table_user">
									<thead>
										<tr>
										  <th>No</th>
										  <th>Nama</th>
										  <th>NIP</th>
										  <th>Aksi</th>
										</tr>
									</thead>	
								</table>
								<!--end: Datatable -->
							</div>
							
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">																							
					  </div>
				 
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
							<input type="hidden" value="" name="proyek_id_del" id="proyek_id_del"/> 																					
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
    </div>
	<!-- end:: Content -->