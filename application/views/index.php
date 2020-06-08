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

									<!--begin:: Widgets/Trends-->
									<div class="kt-portlet kt-portlet--head--noborder kt-portlet--height-fluid">
										<div class="kt-portlet__head kt-portlet__head--noborder">
											<div class="kt-portlet__head-label">
												<h3 class="kt-portlet__head-title">
													Flight Hour Trends
												</h3>
											</div>
										</div>
										<div class="kt-portlet__body kt-portlet__body--fluid kt-portlet__body--fit">
											<div class="kt-widget4 kt-widget4--sticky">
												<div class="kt-widget4__chart">
													<canvas id="chart_flight" style="height: 240px;"></canvas>
												</div>
												<div class="kt-widget4__items kt-widget4__items--bottom kt-portlet__space-x kt-margin-b-20" id="div_pesawat">
												</div>
											</div>
										</div>
									</div>

									<!--end:: Widgets/Trends-->
								</div>
							</div>

							<!--End::Section-->
							
						</div>

						<!-- end:: Content -->