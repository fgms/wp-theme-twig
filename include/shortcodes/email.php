<?php
	
	
	call_user_func(function () {	

		add_shortcode('email',function ($atts, $content)  {			
            return do_shortcode($content);	
		});
		
		
		
	});
	
	