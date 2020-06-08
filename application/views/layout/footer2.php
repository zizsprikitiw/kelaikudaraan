				</div>
            <!-- END CONTAINER -->
		<?php if ($this->ion_auth->logged_in()) { ?>
            <!-- BEGIN FOOTER -->
            <div class="page-footer">
                <div class="page-footer-inner"> 2016 &copy; Metronic Theme By
                    <a target="_blank" href="http://keenthemes.com">Keenthemes</a> &nbsp;|&nbsp;
                    <a href="http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes" title="Purchase Metronic just for 27$ and get lifetime updates for free" target="_blank">Purchase Metronic!</a>
                </div>
                <div class="scroll-to-top">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>
            <!-- END FOOTER -->
        </div>
		<?php } ?>
        <!--[if lt IE 9]>
<script src="../assets/global/plugins/respond.min.js"></script>
<script src="../assets/global/plugins/excanvas.min.js"></script> 
<script src="../assets/global/plugins/ie8.fix.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="<?php echo base_url($this->config->item('assets')['global_plugins']); ?>/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url($this->config->item('assets')['global_plugins']); ?>/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url($this->config->item('assets')['global_plugins']); ?>/js.cookie.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url($this->config->item('assets')['global_plugins']); ?>/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url($this->config->item('assets')['global_plugins']); ?>/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url($this->config->item('assets')['global_plugins']); ?>/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?php echo base_url($this->config->item('assets')['global_scripts']); ?>/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="<?php echo base_url($this->config->item('assets')['global_plugins']); ?>/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url($this->config->item('assets')['global_plugins']); ?>/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url($this->config->item('assets')['global_plugins']); ?>/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url($this->config->item('assets')['pages_scripts']); ?>/components-bootstrap-select.min.js" type="text/javascript"></script>
		<?php if(!empty($add_javascript)) { foreach($add_javascript as $javascript){  ?>
			<script src="<?php echo $javascript; ?>" type="text/javascript"></script>
		<?php } } ?>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <!--<script src="<?php echo base_url($this->config->item('assets')['custom_scripts']); ?>/dashboard.min.js" type="text/javascript"></script>-->
        <!-- END PAGE LEVEL SCRIPTS -->
		<?php if ($this->ion_auth->logged_in()) { ?>
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="<?php echo base_url($this->config->item('assets')['layouts_layout']); ?>/scripts/layout.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url($this->config->item('assets')['layouts_layout']); ?>/scripts/demo.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url($this->config->item('assets')['layouts_global']); ?>/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url($this->config->item('assets')['layouts_global']); ?>/scripts/quick-nav.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
		<?php } ?>
        <script>
            $(document).ready(function()
            {
                $('#clickmewow').click(function()
                {
                    $('#radio1003').attr('checked', 'checked');
                });
            })
        </script>
    </body>

</html>