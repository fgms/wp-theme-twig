<?php
/**
 * The main template file.
 */

	// set up page data 
	

	
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
		$template = 'page';
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

	$data['right']=is_front_page();

	// render using Twig template index.twig	
	Timber::render( $template . '.twig', $data );
?>