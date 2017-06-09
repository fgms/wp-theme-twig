<?php
/**
 * The main template file.
 */

	// set up page data
	$template = false;
	$start = TimberHelper::start_timer();
	$data = Timber::get_context();
	$data['pagination'] = Timber::get_pagination();

	$data['latest_news'] = get_fg_latest_posts();

	if ( is_singular() ) :
		$data['post'] = new TimberPost();

	else :
		$data['posts'] = Timber::get_posts();

	endif;

	$data['config']=$get_config();
    $data['menu'] = new TimberMenu('main-menu');
	if ( is_single() ) :
		$template = 'single';

	elseif ( is_page() ) :
        $matches = null;
        $template = 'page';
        // for custom templates
        $returnValue = preg_match('/.*\\/(.*?)\\./', get_page_template(), $matches);
        if ($returnValue){
            $template = 'page-' . $matches[1];
        }
		$data['slideshow']=null;

	elseif ( is_home() ) :
		$template = 'index';

	elseif ( is_category() ) :
		$data['archive_title'] = '<div class="pre-title pre-title-category"></div>'. get_cat_name( get_query_var('cat') );
		$data['archive_description'] = term_description();
		$template = 'archive';

	elseif ( is_tag() ) :
		$data['archive_title'] = '<div class="pre-title pre-title-tag"></div>'. get_cat_name( get_query_var('tag_id') );
		$data['archive_description'] = term_description();
		$template = 'archive';

	elseif ( is_author() ) :
		$data['archive_title'] = get_the_author();
		$template = 'archive';

	elseif (is_archive() ) :

		if (is_post_type_archive('post') or is_month()){
			$data['archive_title'] = '<div class="pre-title pre-title-archive"></div>'. $GLOBALS['wp_locale']->get_month(get_query_var('monthnum')) . ' ' . get_query_var('year') ;
		}
		else {
			if (!empty(get_post_type())){			
				$data['archive_title'] = get_post_type_object(get_post_type())->labels->name;
			}
			else {
				// redirect 404
				include( get_query_template( '404' ) );
				header('HTTP/1.0 404 Not Found');
				exit;
			}

		}

		$data['archive_description'] = term_description();
		$template = 'archive';
	elseif (is_search()) :
		$data['archive_title'] = '<span class="pre-title pre-title-archive">Search results for</span> '. get_search_query();
		$data['search_results'] = get_search_query();
		$template = 'archive';
	endif;

	if (!is_page()) {
		get_blog_sidebar($data);
	}

	// render using Twig template index.twig
    if ($template !== false ){
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

		// checking for custom post types
		$template_post_type =  get_post_type();
		if (($template_post_type !== 'post') AND ($template_post_type !== 'page') and !is_search()){
			$template = array($template . '-'.$template_post_type.'.twig', $template.'.twig');
		}
		else {
			$template = $template . '.twig';
		}
		Timber::render( $template,
					   $data,
					   get_option('theme_twig_cache_expire','0'),
					   $cache_type_array[get_option('theme_twig_cache','CACHE_NONE')]
					   );

		if (get_option('theme_twig_cache_performance', 'true') == 'true'){
			//echo '<script type="text/javascript">console.log("Cache Type:' . get_option('theme_twig_cache','CACHE_NONE') .'","Time:' . TimberHelper::stop_timer($start) . '");</script>';
		}
	}

	function get_blog_sidebar(&$data){
		$args = array('echo'=>false,
								  'title_li'=>'');
		$data['get_categories'] = '<ul>' . wp_list_categories($args) .'</ul>';
		$args = array( 'posts_per_page' => 10, 'order'=> 'DESC', 'orderby' => 'date' );
		$data['all_posts'] =Timber::get_posts($args);
		$data['get_tags'] = get_tags();
		$args = array('echo'=>false);
		$data['get_archives'] = '<ul>' .wp_get_archives($args).'</ul>';
	}

	function get_fg_latest_posts(){
		$args = array('posts_per_page' => 10, 'order'=>'DESC', 'orderby'=> 'date','post_type'=>'post');
		$posts =  Timber::get_posts($args);
		return $posts;
	}
?>
