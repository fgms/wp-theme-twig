<?php
//include_once('includes/demo.php');
add_action( 'after_setup_theme', 'blankslate_setup' );

function blankslate_setup() {
    load_theme_textdomain( 'blankslate', get_template_directory() . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'post-thumbnails' );
    global $content_width;
    if ( ! isset( $content_width ) ) $content_width = 640;
    register_nav_menus(
    array( 'main-menu' => __( 'Main Menu', 'blankslate' ) )
    );
    
    

    
}

add_action( 'wp_enqueue_scripts', 'blankslate_load_scripts' );
function blankslate_load_scripts(){
   wp_enqueue_script( 'jquery' );
   wp_enqueue_script('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js');
   wp_enqueue_style( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css' );
   wp_enqueue_script('fg-script',get_stylesheet_directory_uri() . '/assets/js/wp-theme-fg.js');
   wp_enqueue_style('theme',get_stylesheet_directory_uri().'/assets/css/style.css');
}

add_action( 'comment_form_before', 'blankslate_enqueue_comment_reply_script' );
function blankslate_enqueue_comment_reply_script(){
    if ( get_option( 'thread_comments' ) ) { wp_enqueue_script( 'comment-reply' ); }
}

add_filter( 'the_title', 'blankslate_title' );
function blankslate_title( $title ) {
    if ( $title == '' ) {
    return '&rarr;';
    } else {
    return $title;
    }
}
add_filter( 'wp_title', 'blankslate_filter_wp_title' );
function blankslate_filter_wp_title( $title ){
    return $title . esc_attr( get_bloginfo( 'name' ) );
}

add_action( 'widgets_init', 'blankslate_widgets_init' );
function blankslate_widgets_init()
{
    register_sidebar( array (
    'name' => __( 'Sidebar Widget Area', 'blankslate' ),
    'id' => 'primary-widget-area',
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => "</li>",
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
    ) );
}

function blankslate_custom_pings( $comment ){
    $GLOBALS['comment'] = $comment;
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo comment_author_link(); ?></li>
    <?php
}

add_filter( 'get_comments_number', 'blankslate_comments_number' );
function blankslate_comments_number( $count ){
    if ( !is_admin() ) {
    global $id;
    $comments_by_type = &separate_comments( get_comments( 'status=approve&post_id=' . $id ) );
    return count( $comments_by_type['comment'] );
    } else {
    return $count;
    }
}
/*
add_filter('timber/context', 'add_to_context');
function add_to_context($data){
    $data['menu'] = new TimberMenu(); // This is where you can also send a WordPress menu slug or ID
    return $data;
}

*/

/* remove admin bar for development */
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {    
    show_admin_bar(false);
}

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
			)
		)
	);
	
	return $bs;
	
}

$get_config=function() {
    $autoloader=require_once('vendor/autoload.php');
	$config_file=dirname(__FILE__).'/config/settings.yml';
	$config=@file_get_contents($config_file);
	if ($config===false) die('Could not read '.$config_file);
	$config=\Symfony\Component\Yaml\Yaml::parse($config);
	//	TODO: Check schema?
	if (!isset($config['theme']) && is_string($config['theme'])) die('No theme');
	$theme=$config['theme'];
    
     //setting up timber twig file locations
    Timber::$locations=array(dirname(__FILE__).'/views/'.$config['theme'],
                             dirname(__FILE__).'/views/'.$config['theme'].'/wp',
                             dirname(__FILE__).'/views/'.$config['theme'].'/partials',
                             dirname(__FILE__).'/views/'.$config['theme'].'/custom');   
    return $config;
};

$get_slideshow=function () {
    
    $meta=rwmb_meta('fgms_slideshow_items');
    if (count($meta)===0) return null;
    
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
    
    return array(
        'items' => $items,
        'id' => $id,
        'outer_class' => $outer_class,
        'inner_class' => $inner_class
    );
    
};   
require_once(__DIR__.'/include/shortcodes.php');
