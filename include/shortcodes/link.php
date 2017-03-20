<?php
	call_user_func(function () {
		add_shortcode('link',function ($atts, $content)  {			
			$content = trim(do_shortcode($content));
            $content = ( strlen($content) === 0 ) ? 'Please add lable ie [link herf=""]my label[/link]' : $content;
            // use default as 2 because most installation id 2 is the sample page which becomes home page.
            $link_id = empty($atts['id']) ? 2 : $atts['id'];
            $href = empty($atts['href']) ? get_permalink($link_id) : addslashes(strip_tags($atts['href']));
            $attributes = empty($atts['attr']) ? '' : strip_tags($atts['attr']);
			if (strlen($href)  > 5 ) {				
				return '<a href="'. $href .'" ' . $attributes . '>' . $content .'</a>';
			}
            return 'No id or href attribute usages: [link id="2" attr="target=blank"]my link[/link] or [link href="http://example.com"]my example[/link]"';
		});
	});
?>