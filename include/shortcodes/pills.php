<?php
	call_user_func(function () {

		$num=0;
		$pills=null;
		add_shortcode('pills',function ($atts, $content) use (&$pills, &$num) {

			$pills=array();
			//	Ignore the content: Any content that isn't
			//	delivered through a [tab] shortcode is ignored
			$content = do_shortcode(strip_tags($content,'<div><a><em><bold><strong><i><li><ul><img><p><h2><h3><h4><h5><table><thead><tbody><td><th><tr>'));
			$c=count($pills);
			//	Preprocess tabs:
			//
			//	-	Determine which tab starts active
			//	-	Set IDs for all tabs (auto generate if
			//		not set explicitly)
			$active=0;
			array_walk($pills,function ($v, $k) use (&$active, &$num) {
				if (!isset($v->id)) $v->id='p'.++$num;
				if ($v->active) $active=$k;
			});

			//	Generate pills
			$html='<ul class="nav nav-pills accordion" role="tablist">';
			array_walk($pills,function ($v, $k) use ($active, &$html) {

				$html.=sprintf(
					'<li role="presentation" class="%1$s"><a href="#%2$s" role="tab" data-toggle="pill" aria-controls="%2$s">%3$s</a></li>',
					$active===$k ? 'active' : '',
					htmlspecialchars($v->id),
					htmlspecialchars($v->title)
				);
			});
			$html.='</ul>';

			//	Generate panes
			$html.='<div class="tab-content ">';
			array_walk($pills,function ($v, $k) use ($active, &$html) {
				$html.=sprintf(
					'<div role="tabpanel" class="tab-pane %1$s" id="%2$s">%3$s</div>',
					$active===$k ? 'active' : 'no-active-'. $active. '' . $k,
					htmlspecialchars($v->id),
					$v->content
				);
			});
			$html.='</div>';
			//	Clean state for next [tabs]
			$pills=null;
			return $html;

		});
		add_shortcode('pill',function ($atts, $content) use (&$pills) {
			//	Guard against invalid user input: Silently delete the content of
			//	a [pill] if we're not currently parsing a [pills]
			if (is_null($pills)) return '';
			$atts=shortcode_atts(array('title' => '', 'id' => null, 'active' => null),$atts);
			$pills[]=(object)array(
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
