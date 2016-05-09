<?php
/**
 * Template Name: No Template
 *
 * @package WordPress
 *  
 */   
    $data = Timber::get_context();
    $data['posts'] = Timber::get_posts();	
    $data['page'] = 'page';	    
	$data['config']=$get_config();    

    // render using Twig template index.twig	  
	Timber::render('page-blank.twig', $data ); 
 
?>