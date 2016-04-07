<?php
include_once('includes/demo.php');
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


call_user_func(function () {
	
	$num=0;
	$tabs=null;
	//	Tabs are going to be an object:
	//
	//	{
	//		title:
	//		id:
	//		active:
	//		content:
	//	}
	
	add_shortcode('tabs',function ($atts, $content) use (&$tabs, &$num) {
		
		$tabs=array();
		
		//	Ignore the content: Any content that isn't
		//	delivered through a [tab] shortcode is ignored
		do_shortcode($content);
		
		$c=count($tabs);
		
		//	Preprocess tabs:
		//
		//	-	Determine which tab starts active
		//	-	Set IDs for all tabs (auto generate if
		//		not set explicitly)
		$active=0;
		array_walk($tabs,function ($v, $k) use (&$active, &$num) {
			
			if (!isset($v->id)) $v->id='t'.++$num;
			
			if ($v->active) $active=$k;
			
		});
		
		//	Generate tabs
		$html='<ul class="nav nav-tabs" role="tablist">';
		array_walk($tabs,function ($v, $k) use ($active, &$html) {
			
			$html.=sprintf(
				'<li role="presentation" class="%1$s"><a href="#%2$s" role="tab" data-toggle="tab" aria-controls="%2$s">%3$s</a></li>',
				$active===$k ? 'active' : '',
				htmlspecialchars($v->id),
				htmlspecialchars($v->title)
			);
			
		});
		$html.='</ul>';
		
		//	Generate panes
		$html.='<div class="tab-content">';
		array_walk($tabs,function ($v, $k) use ($active, &$html) {
			
			$html.=sprintf(
				'<div role="tabpanel" class="tab-pane %1$s" id="%2$s">%3$s</div>',
				$active===$k ? 'active' : '',
				htmlspecialchars($v->id),
				$v->content
			);
			
		});
		$html.='</div>';
		
		//	Clean state for next [tabs]
		$tabs=null;
		
		return $html;
		
	});
	
	add_shortcode('tab',function ($atts, $content) use (&$tabs) {
		
		//	Guard against invalid user input: Silently delete the content of
		//	a [tab] if we're not currently parsing a [tabs]
		if (is_null($tabs)) return '';
		
		$atts=shortcode_atts(array('title' => '', 'id' => null, 'active' => null),$atts);
		
		$tabs[]=(object)array(
			'title' => $atts['title'],
			'content' => do_shortcode($content),
			'id' => $atts['id'],
			'active' => isset($atts['active']) && ($atts['active']==='true')
		);
		
		//	Content is handled through other mechanisms
		return '';
		
	});
	
});


call_user_func(function () {
	
	$imgs=null;
	$id=0;
	//	Each image/slide will be an object:
	//
	//	{
	//		caption:
	//		url:
	//		alt:
	//		title:
	//	}
	
	add_shortcode('carousel',function ($atts, $content) use (&$imgs, &$id) {
		
		$imgs=array();
		
		//	Ignore content
		do_shortcode($content);
		
		$atts=shortcode_atts(array('id' => null, 'outerclass' => null, 'innerclass' => null, 'controls' => true, 'indicators' => true),$atts);
		if (!isset($atts['id'])) $atts['id']='carousel'.(++$id);
		if ($atts['controls']==='false') $atts['controls']=false;
		if ($atts['indicators']==='false') $atts['indicators']=false;
		
		$data=array(
			'items' => array_map(function ($obj) {	return (array)$obj;	},$imgs),
			'id' => $atts['id'],
			'indicators' => $atts['indicators'],
			'controls' => $atts['controls'],
			'innerclass' => $atts['innerclass'],
			'outerclass' => $atts['outerclass']
		);
		
		ob_start();
		Timber::render('bootstrap-carousel.twig',$data);
		$retr=ob_get_contents();
		ob_end_clean();
		
		$imgs=null;
		
		return $retr;
		
	});
	
	add_shortcode('slide',function ($atts) use (&$imgs) {
		
		//	If we're not in a carousel just bail out
		if (is_null($imgs)) return '';
		
		$atts=shortcode_atts(array('caption' => null, 'url' => null, 'alt' => null, 'title' => null),$atts);
		
		$imgs[]=(object)array(
			'title' => $atts['title'],
			'url' => $atts['url'],
			'alt' => $atts['alt'],
			'caption' => $atts['caption']
		);
		
		return '';
		
	});
	
});
