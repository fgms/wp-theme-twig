<?php 
	call_user_func(function () {
		add_shortcode('email',function ($atts, $content)  {
			// 0 is string, 1 is prefix, 2 is domain, and 3 is the .{com | ca | info ...}
			$content = do_shortcode($content);
			$returnValue = preg_match('/(\\w+)@(\\w+)\\.(\\w{2,5})/', $content, $match);
			if (($returnValue) && count($match === 4) ) {
				$label = isset($atts['label']) ? $atts['label'] : $match[0]; 
				return '<a href="" data-prefix="' . $match[1] .'" data-domain="'. $match[2] .'.'.$match[3] .'">' . $label .'</a>';
			}
            return 'Email was invalid -- '. $content;
		});		
	});
?> 