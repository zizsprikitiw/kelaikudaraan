<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title>Progment Pustekbang | <?php echo !empty($title)?$title:$user_menu['page_title']; ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #1 for statistics, charts, recent events and reports" name="description" />
        <meta content="" name="author" />
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
		<!-- BEGIN PAGE FIRST SCRIPTS -->
        <script src="<?php echo base_url($this->config->item('assets')['global_plugins']); ?>/pace/pace.min.js" type="text/javascript"></script>
        <!-- END PAGE FIRST SCRIPTS -->
		<!-- BEGIN PAGE TOP STYLES -->
        <link href="<?php echo base_url($this->config->item('assets')['global_plugins']); ?>/pace/themes/pace-theme-flash.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE TOP STYLES -->
		<!-- BEGIN PAGE LEVEL PLUGINS -->
		<link href="<?php echo base_url($this->config->item('assets')['global_plugins']); ?>/bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url($this->config->item('assets')['global_plugins']); ?>/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" type="text/css" />
		<?php if(!empty($add_css)) { foreach($add_css as $css){  ?>
			<link href="<?php echo $css; ?>" rel="stylesheet" type="text/css" />
		<?php } } ?>
		<!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="<?php echo base_url($this->config->item('assets')['global_plugins']); ?>/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url($this->config->item('assets')['global_plugins']); ?>/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url($this->config->item('assets')['global_plugins']); ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url($this->config->item('assets')['global_plugins']); ?>/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?php echo base_url($this->config->item('assets')['global_css']); ?>/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo base_url($this->config->item('assets')['global_css']); ?>/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
		<?php if ($this->ion_auth->logged_in()) { ?>
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?php echo base_url($this->config->item('assets')['layouts_layout']); ?>/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url($this->config->item('assets')['layouts_layout']); ?>/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="<?php echo base_url($this->config->item('assets')['layouts_layout']); ?>/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
		<?php } ?>
        <link rel="shortcut icon" href="favicon.ico" /> </head>
		<!-- Favicon -->
		<link rel="shortcut icon" href="<?php echo base_url($this->config->item('assets')['custom_img']); ?>/logo-lapan.ico" type="image/x-icon" />
		<link rel="apple-touch-icon" href="<?php echo base_url($this->config->item('assets')['custom_img']); ?>/logo-lapan-apple.png">
		<script>
		var base_url = "<?php echo base_url();?>";
		var site_url = "<?php echo site_url();?>";
		</script>
    <!-- END HEAD -->
	
	<?php if ($this->ion_auth->logged_in()) { ?>
    <body class="<?php echo !empty($body_class)?$body_class:$this->custom->bodyClass('default'); ?>">
        <div class="page-wrapper">
            <?php $this->load->view('layout/top_menu'); ?>
            <!-- BEGIN HEADER & CONTENT DIVIDER -->
            <div class="clearfix"> </div>
            <!-- END HEADER & CONTENT DIVIDER -->
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <?php $this->load->view('layout/sidebar_menu'); ?>
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        <!-- BEGIN PAGE BAR -->
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="<?php echo site_url(); ?>">Beranda</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span><?php echo !empty($title)?$title:$user_menu['page_title']; ?></span>
                                </li>
                            </ul>
							<?php if($this->uri->rsegment(1)=='index'&&$this->uri->rsegment(2)=='index') { ?>
                            <div class="page-toolbar">
								<div class="btn-group">
									<div id="proyek_selected" class="pull-right tooltips btn btn-sm" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-container="body" data-placement="bottom" data-close-others="true" data-original-title="Pilih Program">
										<i class="icon-briefcase"></i>&nbsp;
										<span class="thin uppercase hidden-xs"></span>&nbsp;
										<i class="fa fa-angle-down"></i>
									</div>
									<div class="dropdown-menu pull-right hold-on-click dropdown-checkboxes" role="menu">
										<form action="#" id="form-filter-program">
											<div class="form-group">
												<select class="form-control" id="filter_proyek_year"></select>
												<select class="form-control" id="filter_proyek"></select>
											</div>
											<div class="input-group">
                                                <button type="submit" class="btn btn-sm green close-toggle" >Apply</button>
												<button type="button" class="btn btn-sm default close-toggle" >Cancel</button>
                                            </div>
										</form>
									</div>
								</div>
                            </div>
							<?php } ?>
                        </div>
                        <!-- END PAGE BAR -->
					
	<?php } else { ?>
    <body class="<?php echo $body_class; ?>">
    <?php } ?>    