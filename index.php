<?php
/**
 * The main template file.
 */

	// set up page data 
	
	$autoloader=require_once('vendor/autoload.php');
	$config_file=dirname(__FILE__).'/config/settings.yml';
	$config=@file_get_contents($config_file);
	if ($config===false) die('Could not read '.$config_file);
	$config=\Symfony\Component\Yaml\Yaml::parse($config);
	//	TODO: Check schema?
	if (!isset($config['theme']) && is_string($config['theme'])) die('No theme');
	$theme=$config['theme'];
	
	$get_slideshow=function () {
		
		$meta=rwmb_meta('fgms_slideshow_items');
		if (count($meta)===0) return null;
		
		$retr=array();
		foreach ($meta as $m) {
			
			$retr[]=array(
				'caption' => $m['caption'],
				'url' => $m['full_url'],
				'alt' => $m['alt'],
				'title' => $m['title']
			);
			
		}
		
		return $retr;
		
	};
	
	if ( is_singular() ) :
		$data = Timber::get_context();
		$data['post'] = new TimberPost();
	
	else : 
		$data = Timber::get_context();
		$data['posts'] = Timber::get_posts();
		
	endif;
	$data['config']=$config;
    $data['menu'] = new TimberMenu();
	if ( is_single() ) :
		$data['page'] = 'single';	
		$template = 'single';
		$data['slideshow_items']=$get_slideshow();
	
	elseif ( is_page() ) :
		$data['page'] = 'page';	
		$template = 'page';
		$data['slideshow_items']=$get_slideshow();

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
    $data['base_template'] = 'base.twig';
	
	// render using Twig template index.twig
	Timber::$locations=dirname(__FILE__).'/views/'.$theme;
	Timber::render('wp/' . $template . '.twig', $data );
?>