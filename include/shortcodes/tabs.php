<?php
	
	
	call_user_func(function () {
		
		$num=0;
		$tabs=null;
		//	Tabs are going to be an object:
		//
		//	{
		//		title:
		//		id:
		//		active:
		//		content:
		//	}
		
		add_shortcode('tabs',function ($atts, $content) use (&$tabs, &$num) {
			
			$tabs=array();
			
			//	Ignore the content: Any content that isn't
			//	delivered through a [tab] shortcode is ignored
			do_shortcode($content);
			
			$c=count($tabs);
			
			//	Preprocess tabs:
			//
			//	-	Determine which tab starts active
			//	-	Set IDs for all tabs (auto generate if
			//		not set explicitly)
			$active=0;
			array_walk($tabs,function ($v, $k) use (&$active, &$num) {
				
				if (!isset($v->id)) $v->id='t'.++$num;
				
				if ($v->active) $active=$k;
				
			});
			
			//	Generate tabs
			$html='<ul class="nav nav-tabs accordion" role="tablist">';
			array_walk($tabs,function ($v, $k) use ($active, &$html) {
				
				$html.=sprintf(
					'<li role="presentation" class="%1$s"><a href="#%2$s" role="tab" data-toggle="tab" aria-controls="%2$s">%3$s</a></li>',
					$active===$k ? 'active' : '',
					htmlspecialchars($v->id),
					htmlspecialchars($v->title)
				);
				
			});
			$html.='</ul>';
			
			//	Generate panes
			$html.='<div class="tab-content accordion">';
			array_walk($tabs,function ($v, $k) use ($active, &$html) {
				
				$html.=sprintf(
					
					'<div class="tab-accordion-header %1$s"><a href="#%2$s" role="tab">%3$s</a></div><div role="tabpanel" class="tab-pane %1$s" id="%2$s">%4$s</div>',
					$active===$k ? 'active' : '',
					htmlspecialchars($v->id),
					htmlspecialchars($v->title),
					$v->content
				);
				
			});
			$html.='</div>';
			
			//	Clean state for next [tabs]
			$tabs=null;
			
			return $html;
			
		});
		
		add_shortcode('tab',function ($atts, $content) use (&$tabs) {
			
			//	Guard against invalid user input: Silently delete the content of
			//	a [tab] if we're not currently parsing a [tabs]
			if (is_null($tabs)) return '';
			
			$atts=shortcode_atts(array('title' => '', 'id' => null, 'active' => null),$atts);
			
			$tabs[]=(object)array(
				'title' => $atts['title'],
				'content' => do_shortcode($content),
				'id' => $atts['id'],
				'active' => isset($atts['active']) && ($atts['active']==='true')
			);
			
			//	Content is handled through other mechanisms
			return '';
			
		});
		
	});
	
	
?>