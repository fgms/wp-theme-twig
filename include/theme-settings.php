<?php
	call_user_func(function ($data) {

        add_action("admin_menu", function() use ($data){
            
            add_menu_page("Theme Panel", "Theme Panel", "manage_options", "theme-panel", function() use ($data){
                
                ?>
                    <div class="wrap">
                    <h1>Fifth Gear Theme Panel</h1>
                    <form method="post" action="options.php">
                        <?php
                            settings_fields("section");
                            do_settings_sections("theme-options");      
                            submit_button(); 
                        ?>          
                    </form>
                    </div>
					<div>
						<h2>Email Template</h2>
						
						
						<?php
						    if (class_exists('Timber') ) {
								$data = array_merge($data,array('posted'=>array('first_name'=>'John',
																				'last_name'=>'Smith',
																				'company'=>'Acme Incorporated',
																				'email'=>'john.smith@acme.com',
																				'message'=>'Lorem ipsum dolor sit amet, sociis at dictumst cras, curabitur quis adipiscing egestas inceptos eiusmod in, sed pretium feugiat vitae tristique mus sit, mi qui vel mi hymenaeos nisl. Nam nullam, velit tellus.'),
																'body_message'=>'This text can be added to the email body in contactform7 default email area',
																'form'=>array('title'=>'Form Title')));
								
								Timber::render('wpcf7-email-mail.twig',$data);
							}
						?>
						
					</div>
                <?php        
            
            }, null, 99);
            
        });
    
    
        add_action("admin_init", function(){
            add_settings_section("section", "All Settings", null, "theme-options");          
     
			
			// Sets the twig cache type
            add_settings_field("theme_twig_cache", "Twig Cache", function(){
                $select = '<select  name="theme_twig_cache" id="theme_twig_cache"  >';
				$cache_options = array('CACHE_NONE'=>'None',
									   'CACHE_OBJECT'=>'Wp Object Cache',
									   'CACHE_TRANSIENT'=>'Transients',
									   'CACHE_SITE_TRANSIENT'=>'Network wide transients',
									   'CACHE_USE_DEFAULT'=>'Use Default Cache');

                foreach ( $cache_options as $key=>$text){                   
                    $select .= '<option ' . ((get_option('theme_twig_cache','CACHE_NONE') === $key ) ? 'SELECTED' : '') .' value="'. $key .'">' . $text .'</option>';
                }
                $select .= '</select>';
                echo $select;
                
            }, "theme-options", "section");			
			register_setting("section", "theme_twig_cache");
			
			// sets thw twig expire type
            add_settings_field("theme_twig_cache_expire", "Cache Expire (0 no expire) in ms", function(){
                
                echo '<input type="text" value="'. get_option('theme_twig_cache_expire','7200').'" name="theme_twig_cache_expire" id="theme_twig_cache_expire" />';
                
            }, "theme-options", "section");
			register_setting("section", "theme_twig_cache_expire");
			
			// Enabel for showing Render time
            add_settings_field("theme_twig_cache_performance", "View Cache Performance (js console)", function(){
                $select = '<select  name="theme_twig_cache_performance" id="theme_twig_cache_performance"  >';
				$cache_options = array('true'=>'Enable', 'false'=>'Disable' );

                foreach ( $cache_options as $key=>$text){                   
                    $select .= '<option ' . ((get_option('theme_twig_cache_performance','false') === $key ) ? 'SELECTED' : '') .' value="'. $key .'">' . $text .'</option>';
                }
                $select .= '</select>';
                echo $select;
                
            }, "theme-options", "section");			
			register_setting("section", "theme_twig_cache_performance");			
            
                
        });
    
    },array_merge(Timber::get_context(),array('config'=>$get_config())));

?>