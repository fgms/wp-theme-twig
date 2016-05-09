<?php
	call_user_func(function () {	
		
		add_shortcode('ajax',function ($atts, $content)  {			

            // get post
            $postid = intVal($_REQUEST['postid']);
            $post = get_post($postid);
            $retr = apply_filters('the_content', $post->post_content);
            
            if (isset($atts['template'])){
                $t = $atts['template'];            
                
                try {
                    ob_start();
                    Timber::render($t,array('content'=>$retr,'title'=>$post->post_title));
                    $retr=ob_get_contents();
                    ob_end_clean();				
                } catch (Twig_Error_Loader $e){	return '<script>console.error("Error Loading twig template '. $t .'")</script>';}                
                
            }
            return $retr;

		});
		
		
	});

?>