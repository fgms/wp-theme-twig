<?php
	call_user_func(function () {			
		$data=null;		
		add_shortcode('fgallery',function ($atts, $content) use (&$data) {			
			$data=array();	
			//	Content is ignored
			$atts['content'] = do_shortcode($content);
            $matches = [];
            $width = empty($atts['width']) ? '100px' :  $atts['width'] ;
            $height = empty($atts['height']) ? 'auto' :  $atts['height'] ;
            $alt = empty($att['alt']) ? '' : $atts['alt'];
            $class = empty($att['class']) ? '' : $atts['class'];
            preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $atts['content'] , $matches);
            $retr = $atts['content'];
            if (count($matches) == 2){
                $post_id = url_to_postid($matches[1]);
                
                $retr = '<div style="width: '.$width.';height:'.$height.'" class="script-gallery-action"><a href="'. $matches[1] .'" data-smoothzoom="group'. rand(0, 50000) .'"> ';
                $retr .= '<img src="'.  $matches[1]. '" alt="'.  $alt .'" class="'. $class .'"  />';
                $retr .= '</a></div>';                                
            }        
                    
			return $retr ;
		});
		
		
		
	});

?>