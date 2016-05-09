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
                
        });
    
    });

?>