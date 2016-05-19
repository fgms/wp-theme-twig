<?php
	
	call_user_func(function ($config) {		
		
		$data=null;
		
		add_shortcode('custom-template',function ($atts, $content) use (&$data,$config) {		
			$data=array();	
			//	Content is ignored

			$atts['content'] = do_shortcode($content);
			
			$retr = '<script>console.error("[custom-template] shortcode Template was not defined");</script>';
			if (isset($atts['template'])) {
				$t=$atts['template'];
				unset($atts['template']);				
				$atts['data']=$data;
				$data=null;
				
				try {
					ob_start();
					Timber::render($t,array_merge($atts,$config));
					$retr=ob_get_contents();
					ob_end_clean();				
				} catch (Twig_Error_Loader $e){
					$retr = '<script>console.error("Error Loading twig template '. $t .'")</script>';
				}				
			}
			return $retr;
		});
		
		add_shortcode('custom-item',function ($atts, $content) use (&$data) {
			
			if (is_null($data)) return '';
			
			$atts['content']=do_shortcode($content);
			$data[]=$atts;
			
			return '';
			
		});
		
	},array('config'=>$get_config()));

?>