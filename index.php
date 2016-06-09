<?php
/**
 * The main template file.
 */

	// set up page data 	

	$template = false;
	$start = TimberHelper::start_timer();
	$data = Timber::get_context();
	
		
	if ( is_singular() ) :		
		$data['post'] = new TimberPost();       
	
	else : 
		$data['posts'] = Timber::get_posts();
		
	endif;
	
	$data['config']=$get_config();
    $data['menu'] = new TimberMenu();
	if ( is_single() ) :
		$template = 'single';
		$data['slideshow']=$get_slideshow();
	
	elseif ( is_page() ) :
        $matches = null;
        $template = 'page';
        // for custom templates
        $returnValue = preg_match('/.*\\/(.*?)\\./', get_page_template(), $matches);
        if ($returnValue){
            $template = 'page-' . $matches[1];
        }
		$data['slideshow']=$get_slideshow();

	elseif ( is_home() ) :
		$template = 'index';
			
	elseif ( is_category() ) :
		$data['archive_title'] = get_cat_name( get_query_var('cat') );
		$data['archive_description'] = term_description();
		$template = 'archive';
	
	elseif ( is_tag() ) :
		$data['archive_title'] = get_term_name( get_query_var('tag_id') );
		$data['archive_description'] = term_description();
		$template = 'archive';
	
	elseif ( is_author() ) :
		$data['archive_title'] = get_the_author();		
		$template = 'archive';		

	endif;
   
	
   
	// render using Twig template index.twig
    if ($template !== false ){
		
		require_once(__DIR__.'/include/shortcodes.php');
		require_once(__DIR__.'/include/twig-filter-functions.php');
			
		// adding footer widgets
		$data['widget_footer_sidebar_left'] = Timber::get_widgets('widget_footer_sidebar_left');
		$data['widget_footer_sidebar_right'] = Timber::get_widgets('widget_footer_sidebar_right');
		
		// adding page widgets
		$data['widget_home_sidebar'] = Timber::get_widgets('widget_home_sidebar');
		$data['widget_page_sidebar'] = Timber::get_widgets('widget_page_sidebar');
		$data['widget_contact_sidebar'] = Timber::get_widgets('widget_page_sidebar');
		$data['widget_blog_sidebar'] = Timber::get_widgets('widget_blog_sidebar');
		
		$cache_type_array = array('CACHE_NONE'=>TimberLoader::CACHE_NONE,
										   'CACHE_OBJECT'=>TimberLoader::CACHE_OBJECT,
										   'CACHE_TRANSIENT'=>TimberLoader::CACHE_TRANSIENT,
										   'CACHE_SITE_TRANSIENT'=>TimberLoader::CACHE_SITE_TRANSIENT,
										   'CACHE_USE_DEFAULT'=>TimberLoader::CACHE_USE_DEFAULT);
		
		Timber::render( $template . '.twig',
					   $data,
					   get_option('theme_twig_cache_expire','0'),
					   $cache_type_array[get_option('theme_twig_cache','CACHE_NONE')]                   
					   );
		
		if (get_option('theme_twig_cache_performance', 'true') == 'true'){
			//echo '<script type="text/javascript">console.log("Cache Type:' . get_option('theme_twig_cache','CACHE_NONE') .'","Time:' . TimberHelper::stop_timer($start) . '");</script>';
		}		
	}    
?>