				<!-- BEGIN SIDEBAR -->
                <div class="page-sidebar-wrapper">
                    <!-- BEGIN SIDEBAR -->
                    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                    <div class="page-sidebar navbar-collapse collapse">
                        <!-- BEGIN SIDEBAR MENU -->
                        <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                        <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                        <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                        <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                        <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                        <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-closed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                            <li class="sidebar-toggler-wrapper hide">
                                <div class="sidebar-toggler">
                                    <span></span>
                                </div>
                            </li>
                            <!-- END SIDEBAR TOGGLER BUTTON -->
							<?php
								$has_sub = '';
								//print_r($user_menu);
								$n = 0;
								foreach($user_menu['user_menu'] as $menu_item){										
									if($menu_item['ref_id'] == ''){
										if($has_sub != ''){
											echo '</ul></li>';	//tutup menu jika punya sub menu
										}
										
										//menu utama
										if($menu_item['has_sub'] == 'true'){
											$has_sub = 'has_sub';
											$icon_right = '<span class="arrow"></span>';
										}else{
											$has_sub = '';
											$icon_right = '';
										}
																
										if($menu_item['open'] == 'true'){
											$open = ' active open';
											$selected = '<span class="selected"></span>';
										}else{
											$open = '';
											$selected = '';
										}											
																
										$str_menu = '<li class="nav-item '.$has_sub.$open.'">';
										$str_menu = $str_menu.'<a href="'.$menu_item['url'].'" class="nav-link nav-toggle" ><i class="'.$menu_item['icon'].'" ></i><span class="title">'.$menu_item['nama'].'</span>'.$selected.$icon_right.'</a>';						
										echo $str_menu;
										
										if($has_sub == ''){
											echo '</li>';	//tutup menu jika tidak punya sub menu
										}else{
											echo '<ul class="sub-menu">';
										}
									}else{
										if($menu_item['open'] == 'true'){
											$open = ' active';
										}else{
											$open = '';
										}	
										
										//sub menu									
										$str_menu = '<li class="nav-item '.$has_sub.$open.'"><a href="'.$menu_item['url'].'" class="nav-link "><i class="'.$menu_item['icon'].'" ></i><span class="title">'.$menu_item['nama'].'</span></a></li>';
										echo $str_menu;
									}
									$n++;
								}
								
								if($has_sub != ''){
									echo '</ul></li>';	//tutup menu jika punya sub menu
								}
						  ?>	
						</ul>
                        <!-- END SIDEBAR MENU -->
                        <!-- END SIDEBAR MENU -->
                    </div>
                    <!-- END SIDEBAR -->
                </div>
                <!-- END SIDEBAR -->