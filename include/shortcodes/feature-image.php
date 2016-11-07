<?php
	call_user_func(function () {			
		$data=null;		
		add_shortcode('feature-image',function ($atts, $content) use (&$data) {			
			$data=array();	
			//	Content is ignored

			$atts['content'] = do_shortcode($content);
            $retr = '*******FEATURE IMAGE ';
            if ( has_post_thumbnail() ) {
                $thumb = array();
                $thumb_id = get_post_thumbnail_id();
            
                // first grab all of the info on the image... title/description/alt/etc.
                $args = array(
                    'post_type' => 'attachment',
                    'include' => $thumb_id
                );
                $thumbs = get_posts( $args );
                if ( $thumbs ) {
                    // now create the new array
                    $thumb['title'] = $thumbs[0]->post_title;
                    $thumb['description'] = $thumbs[0]->post_content;
                    $thumb['caption'] = $thumbs[0]->post_excerpt;
                    $thumb['alt'] = get_post_meta( $thumb_id, '_wp_attachment_image_alt', true );
                    $thumb['sizes'] = array(
                        'full' => wp_get_attachment_image_src( $thumb_id, 'full', false )
                    );
                    // add the additional image sizes
                    foreach ( get_intermediate_image_sizes() as $size ) {
                        $thumb['sizes'][$size] = wp_get_attachment_image_src( $thumb_id, $size, false );
                    }
                } // end if
                $retr = $thumb;
                // display the 'custom-size' image
                $retr =  '<img src="' . $thumb['sizes']['full'][0] . '" alt="' . $thumb['alt'] . '" title="' . $thumb['title'] . '"  style="max-width:' . $thumb['sizes']['full'][1] . '; max-height: ' . $thumb['sizes']['full'][2] . '" />';
            } // end if			
                    
			return $retr;
		});
		
		
		
	});

?>