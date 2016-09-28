<?php
	call_user_func(function ($data)  {			
		add_shortcode('twig',function ($atts, $content) use(&$data)  {
			$data = array_merge(Timber::get_context(),$data);
            $retr = '';			
            try {
                ob_start();
                Timber::render_string($content,$data);
                $retr=ob_get_contents();
                ob_end_clean();				
            } catch (Twig_Error_Loader $e){	return '<script>console.error("Error Loading twig string '. $content .'")</script>';}
            
            return $retr;
		});		
	},array('config'=>$get_config()) );
?>