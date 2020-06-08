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
								Log Sistem
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
								<label><input type="checkbox" name="chkSearch[]" id="chkSearch[]" value="tahun"> Tahun:</label>
								<select name="filter_tahun" id="filter_tahun" class="form-control">
									<option value="" >-- Pilih --</option>
								</select>
							</div>
							<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
								<label><input type="checkbox" name="chkSearch[]" id="chkSearch[]" value="bulan"> Bulan:</label>
								<select name="filter_bulan" id="filter_bulan" class="form-control">
									<option value="" >-- Pilih --</option>
								</select> 
							</div>
							<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
								<label><input type="checkbox" name="chkSearch[]" id="chkSearch[]" value="nama"> Nama User:</label>
								<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama user">		
							</div>
							<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
								<label><input type="checkbox" name="chkSearch[]" id="chkSearch[]" value="keterangan"> Keterangan:</label>
								<input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan"> 		
							</div>
						</div>
						<div class="row kt-margin-b-20">
							<div class="col-lg-12 kt-margin-b-10-tablet-and-mobile text-right">
								<button type="button" class="btn btn-brand btn-elevate btn-icon-sm" id="btnSearch" onClick="data_search()">
									<i class="la la-search"></i>
									Cari
								</button>		
							</div>
						</div>
						
						<!--begin: Datatable -->
						<table class="table table-striped- table-bordered table-hover table-checkable responsive no-wrap dataTable dtr-inline collapsed" id="table">
							<thead>
								<tr>
								  <th>No</th>
								  <th>Tanggal</th>
								  <th>ID</th>
								  <th>Nama User</th>
								  <th>Keterangan</th>
								</tr>
							</thead>	
						</table>
						<!--end: Datatable -->
					</div>
				</div>
			</div>
		</div>

		<!--End::Section-->		
	</div>
	<!-- end:: Content -->						