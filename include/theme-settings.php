<?php
	call_user_func(function () {

        add_action("admin_menu", function(){
            
            add_menu_page("Theme Panel", "Theme Panel", "manage_options", "theme-panel", function(){
                
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
                <?php        
            
            }, null, 99);
            
        });
    
    
        add_action("admin_init", function(){
            add_settings_section("section", "All Settings", null, "theme-options");
            
			// this is the directory to get config
            add_settings_field("theme_directory", "Theme", function(){
                $select = '<select  name="theme_directory" id="theme_directory"  >';
                $path = get_template_directory().'/views/theme/';
                $dirs = array_filter(glob($path . '*'), 'is_dir');
                foreach ( $dirs as $dir){
                    $theme = str_replace($path, '', $dir);
                    $select .= '<option ' . ((get_option('theme_directory') === $theme ) ? 'SELECTED' : '') .'>' . $theme .'</option>';
                }
                $select .= '</select>';
                echo $select;
                
            }, "theme-options", "section");
			register_setting("section", "theme_directory");
			
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
    
    });

?>