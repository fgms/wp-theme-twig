<?php


	call_user_func(function () {
		
		$data=null;
		
		add_shortcode('custom-template',function ($atts, $content) use (&$data) {
			
			$data=array();
			
			//	Content is ignored
			do_shortcode($content);
			
			if (!isset($atts['template'])) throw new \LogicException('[custom-template] with no "template" attribute');
			$t=$atts['template'];
			unset($atts['template']);
			
			$atts['data']=$data;
			$data=null;
			
			ob_start();
			Timber::render($t,$atts);
			$retr=ob_get_contents();
			ob_end_clean();
			
			return $retr;
			
		});
		
		add_shortcode('custom-item',function ($atts, $content) use (&$data) {
			
			if (is_null($data)) return '';
			
			$atts['content']=do_shortcode($content);
			$data[]=$atts;
			
			return '';
			
		});
		
	});


?>