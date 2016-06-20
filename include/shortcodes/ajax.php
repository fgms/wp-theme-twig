<?php
	call_user_func(function () {	
		
		add_shortcode('ajax',function ($atts, $content)  {			

            // get post
            $postid = isset($_REQUEST['postid']) ? intVal($_REQUEST['postid']) : 0;
			$allowed = isset($atts['allowed']) ? explode(',',$atts['allowed']) : false;
			$allowedflag = true;
			$denied = isset($atts['denied']) ? explode(',',$atts['denied']) : false;
			$retr = '';
			$title = '';
			
			// checking if allowed options is set and resource is in group,if so the postid has to be in this group other wise will no output.
			if (is_array($allowed)){
				if (!in_array($postid,$allowed)){
					$allowedflag = false;
				}				
			}
			// checking if denied options is set and resource is not in array otherwise no output
			if (is_array($denied)){
				if ( in_array($postid,$denied)) {
					$allowedflag = false;
				}
			}
			
			// allowed flag set in order to get contents.
			if ($allowedflag){
				$post = get_post($postid);
				if (is_object($post)){
					$title = $post->post_title;
					$retr = apply_filters('the_content', $post->post_content);					
				}

			}
			else {
				$retr .=  '<script>console.error("Error Resource not whitelisted in allowed attr  for postid'. $postid .'"';
				
			}

            // template has to be present and valid to output content inside template.
            if (isset($atts['template'])){
                $t = $atts['template'];         
                
                try {
                    ob_start();
                    Timber::render($t,array('content'=>$retr,'title'=>$title));
                    $retr=ob_get_contents();
                    ob_end_clean();				
                } catch (Twig_Error_Loader $e){	$retr .=  '<script>console.error("Error Loading twig template '. $t .'")</script>';}                
                
            }
            return $retr;

		});
		
		
	});

?>