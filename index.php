<?php
/**
 * The main template file.
 */

	// set up page data 
	

	$start = TimberHelper::start_timer();
	if ( is_singular() ) :
		$data = Timber::get_context();
		$data['post'] = new TimberPost();       
	
	else : 
		$data = Timber::get_context();
		$data['posts'] = Timber::get_posts();
		
	endif;
	$data['config']=$get_config();
    $data['menu'] = new TimberMenu();
	if ( is_single() ) :
		$data['page'] = 'single';	
		$template = 'single';
		$data['slideshow']=$get_slideshow();
	
	elseif ( is_page() ) :
		$data['page'] = 'page';	
        $matches = null;
        $template = 'page';
        // for custom templates
        $returnValue = preg_match('/.*\\/(.*?)\\./', get_page_template(), $matches);
        if ($returnValue){
            $template = 'page-' . $matches[1];
        }
		$data['slideshow']=$get_slideshow();

	elseif ( is_home() ) :
		$data['page'] = 'home';
		$template = 'index';
			
	elseif ( is_category() ) :
		$data['archive_title'] = get_cat_name( get_query_var('cat') );
		$data['archive_description'] = term_description();
		$data['page'] = 'archive';
		$template = 'archive';
	
	elseif ( is_tag() ) :
		$data['archive_title'] = get_term_name( get_query_var('tag_id') );
		$data['archive_description'] = term_description();
		$data['page'] = 'archive';
		$template = 'archive';
	
	elseif ( is_author() ) :
		$data['archive_title'] = get_the_author();
		$data['page'] = 'archive';
		$template = 'archive';		

	endif;
   
	
   
	// render using Twig template index.twig
    
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
        echo '<script type="text/javascript">console.log("Cache Type:' . get_option('theme_twig_cache','CACHE_NONE') .'","Time:' . TimberHelper::stop_timer($start) . '");</script>';
    }
    
?>