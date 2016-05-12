<?php
    /* calling $get_config directly, which means yaml file is the full namespace, no need to pre-append config, ie use {{email.info}} not {{config.email.info}} */
	call_user_func(function ($data)  {
        
		add_shortcode('twig',function ($atts, $content) use(&$data)  {
            $retr = '';
            try {
                ob_start();
                Timber::render_string($content,$data);
                $retr=ob_get_contents();
                ob_end_clean();				
            } catch (Twig_Error_Loader $e){	return '<script>console.error("Error Loading twig string '. $content .'")</script>';}  
            return $retr;
		});		
	},array('config'=>$get_config()));
?>