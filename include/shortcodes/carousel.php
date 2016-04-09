<?php
	
	
	call_user_func(function () {
		
		$imgs=null;
		$id=0;
		//	Each image/slide will be an object:
		//
		//	{
		//		caption:
		//		url:
		//		alt:
		//		title:
		//	}
		
		add_shortcode('carousel',function ($atts, $content) use (&$imgs, &$id) {
			
			$imgs=array();
			
			//	Ignore content
			do_shortcode($content);
			
			$atts=shortcode_atts(array('id' => null, 'outerclass' => null, 'innerclass' => null, 'controls' => true, 'indicators' => true),$atts);
			if (!isset($atts['id'])) $atts['id']='carousel'.(++$id);
			if ($atts['controls']==='false') $atts['controls']=false;
			if ($atts['indicators']==='false') $atts['indicators']=false;
			
			$data=array(
				'items' => array_map(function ($obj) {	return (array)$obj;	},$imgs),
				'id' => $atts['id'],
				'indicators' => $atts['indicators'],
				'controls' => $atts['controls'],
				'innerclass' => $atts['innerclass'],
				'outerclass' => $atts['outerclass']
			);
			
			ob_start();
			Timber::render('bootstrap-carousel.twig',$data);
			$retr=ob_get_contents();
			ob_end_clean();
			
			$imgs=null;
			
			return $retr;
			
		});
		
		add_shortcode('slide',function ($atts) use (&$imgs) {
			
			//	If we're not in a carousel just bail out
			if (is_null($imgs)) return '';
			
			$atts=shortcode_atts(array('caption' => null, 'url' => null, 'alt' => null, 'title' => null),$atts);
			
			$imgs[]=(object)array(
				'title' => $atts['title'],
				'url' => $atts['url'],
				'alt' => $atts['alt'],
				'caption' => $atts['caption']
			);
			
			return '';
			
		});
		
	});
	
	
?>