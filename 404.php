<?php
  $data = Timber::get_context();
  $data['posts'] = Timber::get_posts();
  $data['page'] = 'page';
  $data['config']=$get_config();
  $data['menu'] = new TimberMenu();
  require_once(__DIR__.'/include/twig-filter-functions.php');
  $data['widget_footer_sidebar_left'] = Timber::get_widgets('widget_footer_sidebar_left');
  $data['widget_footer_sidebar_right'] = Timber::get_widgets('widget_footer_sidebar_right');
  $data['widget_page_sidebar'] = Timber::get_widgets('widget_page_sidebar');
  // render using Twig template index.twig
  Timber::render('page-404.twig', $data );
?>
