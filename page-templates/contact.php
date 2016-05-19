<?php
/**
 * Template Name: Contact Page
 *
 * @package WordPress
 *  
 */   
    $data = Timber::get_context();
    $data['posts'] = Timber::get_posts();
    $data['post'] = new TimberPost();
    $data['page'] = 'page';	
  
    $data['slideshow']=$get_slideshow();
	$data['config']=$get_config();
    $data['menu'] = new TimberMenu();

    // render using Twig template index.twig	  
	Timber::render('page-contact.twig', $data ); 
 
?>
