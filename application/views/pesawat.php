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
								Tabel Pesawat
							</h3>
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
						</div>
						
						<!--begin: Datatable -->
						<table class="table table-striped- table-bordered table-hover table-checkable responsive no-wrap dataTable dtr-inline collapsed" id="table">
							<thead>
								<tr>
								  <th>No</th>
								  <th>Judul Proyek</th>
								  <th>Singkatan</th>
								  <th>Tahun</th>
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
		
		<!-- Modal BEGIN:ADD DATA PROYEK-->
		<div id="modalAddFormProyek" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					  <div class="modal-header">			                        
						<h4 class="modal-title">Tambah</h4>
					  </div>
					  <div class="modal-body">											
							<form class="kt-form kt-form--label-right form-horizontal" role="form" id="add_form_proyek" action="#" >
								<input type="hidden" value="" name="id"/> 			
								<div class="kt-portlet__body">
									<div class="form-group row">
										<label for="nama_pesawat" class="col-3 col-form-label">Nama Pesawat</label>
										<div class="col-9">
											<input type="text" class="form-control" id="nama_pesawat" name="nama_pesawat">
										</div>
									</div>
									<div class="form-group row">
										<label for="singkatan" class="col-3 col-form-label">Singkatan</label>
										<div class="col-9">
											<input type="text" class="form-control" id="singkatan" name="singkatan">
										</div>
									</div>
									<div class="form-group row">
										<label for="posisi" class="col-3 col-form-label">Posisi</label>
										<div class="col-9">
											<input type="text" class="form-control" id="posisi" name="posisi">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-3 col-form-label" for="deskripsi">Keterangan</label>
										<div class="col-9">
											<textarea class="form-control" id="keterangan" name="keterangan" rows="5"></textarea>
										</div>
									</div>
								</div>
							</form>
						
							<div id="modal_message"></div>
					  </div>
					  <!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
						<button type="button" onClick="data_save()" class="btn btn-sm btn-success">Simpan</button>	
					  </div>
					</form>
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:ADD DATA PROYEK-->
		
		<!-- Modal BEGIN:ADD DATA-->
		<div id="modalAddForm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					  <div class="modal-header">			                        
						<h4 class="modal-title">Tambah</h4>
					  </div>
					  <div class="modal-body">											
							<form class="kt-form kt-form--label-right form-horizontal" role="form" id="add_form" action="#" autocomplete="nope"  enctype="multipart/form-data">
								<input type="hidden" value="" name="id"/> 			
								<div class="kt-portlet__body">
									<div class="form-group row">
										<label for="file_foto" class="col-3 col-form-label">File</label>
										<div class="col-6">
											<div class="custom-file">
												<input type="file" class="custom-file-input" id="file_foto" name="file_foto" accept="image/*">
												<label class="custom-file-label" for="file_foto">Choose file</label>
											</div>
										</div>
									</div>
									<div class="form-group row" id="div_upload_status" style="display:none">
										<label for="file_foto" class="col-3 col-form-label">File</label>
										<div class="col-6">
											<strong><i class="fa fa-upload"></i> <label id="new_filename"></label></strong>
											<div class="file-meta" id="status_upload">Status upload: %</div>													 									
											<!-- Progress bar -->
											<div class="progress progress-striped active">
												<div class="progress-bar progress-bar-info"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 0%" id="div_progressbar">
													<span class="sr-only" id="status_progressbar">% Complete</span>
												</div>
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
												<button type="button" class="btn btn-success" id="btnSave" onClick="data_save_validation()">Tambah</button>
											</div>
										</div>
									</div>
								</div>
							</form>
						
							<div id="modal_message"></div>
							<div class="kt-separator kt-separator--border-dashed kt-separator--space-xs"></div>
							<!--begin: Datatable -->
							<table class="table table-striped- table-bordered table-hover table-checkable" id="table_file">
								<thead>
									<tr>
										<th>No</th>
										<th>File</th>
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
	  
	</div>
	<!-- end:: Content -->