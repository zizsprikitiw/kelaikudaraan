<?php $this->load->view('layout/header'); ?>
        <!-- begin:: Page -->
		<div class="kt-grid kt-grid--ver kt-grid--root">
			<div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v4 kt-login--signin" id="kt_login">
				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" style="background-image: url(<?php echo base_url();?>assets/metronic/media/bg/bg-2.jpg);">
					<div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
						<div class="kt-login__container">
							<div class="kt-login__logo">
								<img src="<?php echo base_url();?>/assets/img/logo-lapan-apple.png" height="70px">
								<h3 class="kt-login__title kt-shape-font-color-1">Sistem Status Kelaikudaraan Pesawat Udara</h3>
							</div>
							<div class="kt-login__signin">
								<div class="kt-login__head">
									<h3 class="kt-login__title">Sign In To Admin</h3>
								</div>
								<?php echo !empty($data['message'])?$data['message']:''; ?>
								<form class="kt-form" id="loginform" name="loginform" action="login" method="post" accept-charset="utf-8">
									<div class="input-group">
										<input class="form-control" type="text" placeholder="Username" autocomplete="off" id="identity" name="identity" value="<?php $this->form_validation->set_value('identity'); ?>">
									</div>
									<div class="input-group">
										<input class="form-control" type="password" placeholder="Password" name="password" id="password" name="password">
									</div>
									<div class="row kt-login__extra">
										<div class="col">
											<label class="kt-checkbox">
												<input type="checkbox" name="remember"> Remember me
												<span></span>
											</label>
										</div>
										<div class="col kt-align-right">
											<a href="javascript:;" id="kt_login_forgot" class="kt-login__link">Forget Password ?</a>
										</div>
									</div>
									<div class="kt-login__actions">
										<button type="submit" class="btn btn-brand btn-pill kt-login__btn-primary">Sign In</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- end:: Page -->
<?php $this->load->view('layout/footer'); ?>	