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
						
						<div class="row kt-margin-b-20">
							<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
								<label>Tipe Approval:</label>
								<select name="filter_approval" id="filter_approval" class="form-control">
										<option value="" >-- Pilih --</option>
									</select> 
							</div>
						</div>
						
						<!--begin: Datatable -->
						<table class="table table-striped- table-bordered table-hover table-checkable responsive no-wrap dataTable dtr-inline collapsed" id="table">
							<thead>
								<tr>
								  <th>No</th>
								  <th>Pengirim</th>
								  <th>Penerima</th>
								  <th>Konfirmasi Level</th>
								  <th>Aksi</th>
								  <th>bgcolor</th>
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
							<!--PENGIRIM-->
							<div class="form-group">
							  <label class="col-lg-2 control-label" for="select_struktural_out">Pengirim</label>
							  <div class="col-lg-10">
							  	<div class="col-lg-4">
									<div class="radio">
                                      <label>
                                        <input type="radio" name="optPengirim" id="option_struktural_out" value="struktural" onclick="optRadio_Click('optPengirim','out')">
                                        Struktural
                                      </label>
                                    </div>
								</div>
								<div class="col-lg-8">
									<select name="select_struktural_out" id="select_struktural_out" class="form-control" >
										<option value="" >--Pilih--</option>
									</select>
								</div>									
							  </div>
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-2 control-label" for="select_posisi_out"></label>
							  <div class="col-lg-10">
							  	<div class="col-lg-4">
									<div class="radio">
                                      <label>
                                        <input type="radio" name="optPengirim" id="option_posisi_out" value="posisi" onclick="optRadio_Click('optPengirim','out')">
                                        Posisi Kegiatan
                                      </label>
                                    </div>
								</div>
								<div class="col-lg-8">
									<select name="select_posisi_out" id="select_posisi_out" class="form-control" >
										<option value="" >--Pilih--</option>
									</select>
								</div>									
							  </div>
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-2 control-label" for="select_fungsional_out"></label>
							  <div class="col-lg-10">
							  	<div class="col-lg-4">
									<div class="radio">
                                      <label>
                                        <input type="radio" name="optPengirim" id="option_fungsional_out" value="fungsional" onclick="optRadio_Click('optPengirim','out')">
                                        Fungsional
                                      </label>
                                    </div>
								</div>
								<div class="col-lg-8">
									<select name="select_fungsional_out" id="select_fungsional_out" class="form-control" >
										<option value="" >--Pilih--</option>
									</select>
								</div>									
							  </div>
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-2 control-label" for="select_group_out"></label>
							  <div class="col-lg-10">
							  	<div class="col-lg-4">
									<div class="radio">
                                      <label>
                                        <input type="radio" name="optPengirim" id="option_group_out" value="group" onclick="optRadio_Click('optPengirim','out')" >
                                        Group User
                                      </label>
                                    </div>
								</div>
								<div class="col-lg-8">
									<select name="select_group_out" id="select_group_out" class="form-control" >
										<option value="" >--Pilih--</option>
									</select>
								</div>									
							  </div>
							</div> 
							<!--END PENGIRIM-->
							
							<hr style="border-top: 1px solid #ccc;border-bottom: 1px solid #fff;" />								
							<!--PENERIMA-->
							<div class="form-group">
							  <label class="col-lg-2 control-label" for="select_struktural_in">Penerima</label>
							  <div class="col-lg-10">
							  	<div class="col-lg-4">
									<div class="radio">
                                      <label>
                                        <input type="radio" name="optPenerima" id="option_struktural_in" value="struktural" onclick="optRadio_Click('optPenerima','in')">
                                        Struktural
                                      </label>
                                    </div>
								</div>
								<div class="col-lg-8">
									<select name="select_struktural_in" id="select_struktural_in" class="form-control" >
										<option value="" >--Pilih--</option>
									</select>
								</div>									
							  </div>
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-2 control-label" for="select_posisi_in"></label>
							  <div class="col-lg-10">
							  	<div class="col-lg-4">
									<div class="radio">
                                      <label>
                                        <input type="radio" name="optPenerima" id="option_posisi_in" value="posisi" onclick="optRadio_Click('optPenerima','in')">
                                        Posisi Kegiatan
                                      </label>
                                    </div>
								</div>
								<div class="col-lg-8">
									<select name="select_posisi_in" id="select_posisi_in" class="form-control" >
										<option value="" >--Pilih--</option>
									</select>
								</div>									
							  </div>
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-2 control-label" for="select_fungsional_in"></label>
							  <div class="col-lg-10">
							  	<div class="col-lg-4">
									<div class="radio">
                                      <label>
                                        <input type="radio" name="optPenerima" id="option_fungsional_in" value="fungsional" onclick="optRadio_Click('optPenerima','in')">
                                        Fungsional
                                      </label>
                                    </div>
								</div>
								<div class="col-lg-8">
									<select name="select_fungsional_in" id="select_fungsional_in" class="form-control" >
										<option value="" >--Pilih--</option>
									</select>
								</div>									
							  </div>
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-2 control-label" for="select_group_in"></label>
							  <div class="col-lg-10">
							  	<div class="col-lg-4">
									<div class="radio">
                                      <label>
                                        <input type="radio" name="optPenerima" id="option_group_in" value="group" onclick="optRadio_Click('optPenerima','in')">
                                        Group User
                                      </label>
                                    </div>
								</div>
								<div class="col-lg-8">
									<select name="select_group_in" id="select_group_in" class="form-control" >
										<option value="" >--Pilih--</option>
									</select>
								</div>									
							  </div>
							</div> 
							<!--END PENERIMA-->
							<hr style="border-top: 1px solid #ccc;border-bottom: 1px solid #fff;" />	
							<div class="form-group">
							  <label class="col-lg-2 control-label" for="wait_before">Konfirmasi</label>
							  <div class="col-lg-3">
								<input type="text" class="form-control" placeholder="Konfirmasi level" id="wait_before" name="wait_before">
							  </div>
							  <div class="col-lg-7">
							  	*Biarkan kosong: Tanpa konfirmasi<br />
								 0: Tunggu konfirmasi level dibawahnya<br />
								 1, 2, dst: : Tunggu konfirmasi n level dibawahnya
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
														
    </div>
	<!-- end:: Content -->