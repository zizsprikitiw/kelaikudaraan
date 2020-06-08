<?php $this->load->view('layout/header'); ?>
				<!-- BEGIN CONTENT -->		
                <div class="row">
					<div class="col-md-12 page-404">
						<div class="number font-red"> 404 </div>
						<div class="details">
							<h3>Oops! Halaman tidak ditemukan.</h3>
							<p> Kami tidak dapat menemukan halaman yang Anda akses.
								<br/>
								<a class="btn red btn-outline" href="<?php echo site_url();?>"> Kembali ke Beranda </a> </p>
						</div>
					</div>
				</div>
                <!-- END CONTENT -->
	<?php $this->load->view('layout/quick_sidebar'); ?>	
<?php $this->load->view('layout/footer'); ?>
