<?php
$autoloader = require_once( str_replace('/wp-content/themes', '', get_theme_root()) .'/vendor/autoload.php');

// Timber load fix which got broken in V1.1.0 of timber/timber
if (!class_exists('Timber') {
   new \Timber\Timber;
}

add_action( 'after_setup_theme', function(){
    load_theme_textdomain( 'blankslate', get_template_directory() . '/languages' );
	add_post_type_support( 'page', 'excerpt' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'post-thumbnails' );
	
	// don't want this annoying p tag wrap and br tag.
	remove_filter( 'the_content', 'wpautop' );
    register_nav_menus(array( 'main-menu' => __( 'Main Menu', 'blankslate' ) ) );

    // removes admin bar if user is signed out, which is a false bar because a user at once was signed in
    $current_user = wp_get_current_user();
    if ( 0 == $current_user->ID){      
        show_admin_bar(false);
    }	
});



add_action( 'wp_enqueue_scripts', function(){
	
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script('modernizr', get_template_directory_uri() .'/assets/js/modernizr.js');
	wp_enqueue_script('bootstrap', get_template_directory_uri() .'/assets/js/bootstrap.min.js');
	wp_enqueue_script('theme-master-script', get_template_directory_uri() .'/assets/js/script.js');
	wp_enqueue_script('google-api','http://www.google.com/jsapi');
   
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() .'/assets/css/bootstrap.min.css' );
	wp_enqueue_style('fontawesome',get_template_directory_uri() .'/assets/css/font-awesome.min.css');
	wp_enqueue_style('master-style',get_template_directory_uri() .'/assets/css/style.css');
   
},20);


add_filter( 'the_title', function($title){
    if ( $title == '' ) {  return '&rarr;'; }
	else 				{  return $title;   }
});


add_action( 'widgets_init', function() {	
	register_sidebar(array(
		'name' => 		'Footer - Left',
		'id'=> 			'widget_footer_sidebar_left',
		'before_widget' => '<li id="%1$s" class="widget-container  %2$s">',
		'after_widget' => "</li>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
		'class' => 'widget_footer_sidebar_left',
		
	));
	register_sidebar(array(
		'name' => 		'Footer - Right',
		'id'=> 			'widget_footer_sidebar_right',
		'before_widget' => '<li id="%1$s" class="widget-container widget_footer_sidebar_right %2$s">',
		'after_widget' => "</li>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
		'class' => 'widget_footer_sidebar_right',
	));
	register_sidebar(array(
		'name' => 		'Sidebar (Home)',
		'id'=> 			'widget_home_sidebar',
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => "</li>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
		'class' => 'widget_home_sidebar',
		
	));		
	register_sidebar(array(
		'name' => 		'Sidebar (Page)',
		'id'=> 			'widget_page_sidebar',
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => "</li>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
		'class' => 'widget_page_sidebar',
		
	));	
	register_sidebar(array(
		'name' => 		'Sidebar (Blog)',
		'id'=> 			'widget_blog_sidebar',
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => "</li>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
		'class' => 'widget_blog_sidebar',
		
	));
	register_sidebar(array(
		'name' => 		'Sidebar (Contact)',
		'id'=> 			'widget_contact_sidebar',
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => "</li>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
		'class' => 'widget_contact_sidebar ',
		
	));		
});
// adding config and context data into templates
add_filter('wpcf7_fg_email_data','wpcf7_fg_email_data');
function wpcf7_fg_email_data($data){
	global $get_config;
	return array_merge($data,Timber::get_context(),array('config'=>$get_config()));
}

// adding shortcode / or twig render.
add_filter('widget_text', function($text) {
	$text = do_shortcode($text);
	return $text;
});



add_filter('rwmb_meta_boxes','fgms_meta_boxes');
function fgms_meta_boxes ($bs) {	
	$prefix='fgms_';	
	$bs[]=array(
		'title' => 'Slideshow',
		'post_types' => array('post','page'),
		'fields' => array(
			array(
				'id' => $prefix.'slideshow_items',
				'name' => __('Slides','fgms'),
				'type' => 'image_advanced'
			),
			array(
				'id' => $prefix.'slideshow_id',
				'name' => __('Slideshow ID','fgms'),
				'type' => 'text'
			),
			array(
				'id' => $prefix.'slideshow_outerclass',
				'name' => __('Slideshow Outer Class','fgms'),
				'type' => 'text'
			),
			array(
				'id' => $prefix.'slideshow_innerclass',
				'name' => __('Slideshow Inner Class','fgms'),
				'type' => 'text'
			),
            array(
                'id' => $prefix.'slideshow_hide_captions',
                'name' => __('Hide Captions'),
                'type' => 'checkbox'
            ),
            array(
                'id' => $prefix.'slideshow_hide_navigation',
                'name' => __('Hide Navigation'),
                'type' => 'checkbox'
            ),
            array(
                'id' => $prefix.'slideshow_hide_indicators',
                'name' => __('Hide Indicators'),
                'type' => 'checkbox'
            )
		)
	);	
	return $bs;	
}

$get_config=call_user_func(function() {	
	return function() use (&$config) {
		// lets see if we have a cached version
		if (!is_null($config)){
			
			return $config;
		}
		
		
		$cache = array('config_ts'=> get_transient('fg_config_timestamp'),
					   'config'=>get_transient('fg_config')
				);
		$errors = array();
		$theme = get_option('theme_directory','default');
		$config_file = get_stylesheet_directory().'/config/settings.yml';
		if (!file_exists ( $config_file ) ) {
			echo 'Error Config file not found';
			return;
		}
		$filetime = filemtime($config_file);
		
		// checking if settings.yml has changed if so lets update, but only if is valid
		if ($cache['config_ts'] != $filetime) {					
			$config=@file_get_contents($config_file);
			if ($config===false) die('Could not read '.$config_file);
			try {
				$config=\Symfony\Component\Yaml\Yaml::parse($config);
			}
			catch(\Symfony\Component\Yaml\Exception\ParseException $e){
				$errors[] = 'ParseException in function.php Line '. __LINE__ . ' '. $e;
			}
			// have a good config, lets update tranient values
			if (is_array($config) && (count($errors) === 0)){
				set_transient('fg_config',$config,0);
				set_transient('fg_config_timestamp',$filetime,0);			
			}
			else {
				//means an error in yaml settings. -- need a way to notify user it failed			
				$config = $cache['config'];			
			}
		}
		// no changes, so load transients.
		else {
			$config = $cache['config'];
			
		}
		//adding errors
		$config['ERRORS'] = $errors;
	 
		$dirnameChildTheme = get_stylesheet_directory(). '/twig-templates';
		$dirnameTheme = get_template_directory(). '/twig-templates';
		
		
		
		Timber::$dirname=array('twig-templates');
		
		 //setting up timber twig file locations
		 // it will look in theme first, if it doesn't find it it will look in master
		$timberLocationsArray = array($dirnameChildTheme,
								 $dirnameChildTheme.'/wp',
								 $dirnameChildTheme.'/partials',
								 $dirnameChildTheme.'/email',
								 $dirnameChildTheme.'/form');
		// only merge if seperate;
		if ( $dirnameChildTheme !== $dirnameTheme ) {
			$timberLocationsArray = array_merge($timberLocationsArray, array(	$dirnameTheme,
																				$dirnameTheme.'/wp',
																				$dirnameTheme.'/partials',
																				$dirnameTheme.'/email',
																				$dirnameTheme.'/form')
												);
		}
		// adding filter to add twig locations
		$timberLocationsArray = apply_filters('fg_theme_master_twig_locations', $timberLocationsArray);
		Timber::$locations=	 $timberLocationsArray;
		
		return $config;
	};
});

$get_slideshow=function () {	
	if (function_exists('rwmb_meta')){
		$meta=rwmb_meta('fgms_slideshow_items');
		if (!is_array($meta) || (count($meta)===0)) return null;
		
		$items=array();
		foreach ($meta as $m) {
			
			$items[]=array(
				'caption' => $m['caption'],
				'url' => $m['full_url'],
				'alt' => $m['alt'],
				'title' => $m['title']
			);
			
		}
		
		$filter=function ($str) {	return ($str==='') ? null : $str;	};
		$id=$filter(rwmb_meta('fgms_slideshow_id'));
		$outer_class=$filter(rwmb_meta('fgms_slideshow_outerclass'));
		$inner_class=$filter(rwmb_meta('fgms_slideshow_innerclass'));
		$captions=rwmb_meta('fgms_slideshow_hide_captions')!=='1';
		$indicators=rwmb_meta('fgms_slideshow_hide_indicators')!=='1';
		$navigation=rwmb_meta('fgms_slideshow_hide_navigation')!=='1';
		
		return array(
			'items' => $items,
			'id' => $id,
			'outer_class' => $outer_class,
			'inner_class' => $inner_class,
			'captions' => $captions,
			'titles' => $captions,
			'indicators' => $indicators,
			'controls' => $navigation
		);		
	}
    
};

if (is_admin()){
	require_once(__DIR__.'/include/theme-settings.php');
}
?>
