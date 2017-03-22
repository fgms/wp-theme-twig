<?php
  $data = Timber::get_context();
  $data['posts'] = Timber::get_posts();
  $data['page'] = 'page';
  $data['slideshow']=$get_slideshow();
  $data['config']=$get_config();
  $data['menu'] = new TimberMenu();
  require_once(__DIR__.'/include/twig-filter-functions.php');
  // render using Twig template index.twig
  Timber::render('page-404.twig', $data );
?>
