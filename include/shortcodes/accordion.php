<?php	
	call_user_func(function () {		
		$panels=null;
		$id=0;
		$aid=0;
		//	Each panel is an object:
		//
		//	{
		//		content:
		//		id:
		//		title:
		//	}
		
		add_shortcode('accordion',function ($atts, $content) use (&$panels, &$aid) {
			
			$panels=array();
			$retr ='';
			
			do_shortcode($content);
			
			$id='accordion'.(++$aid);
			$retr.=sprintf(
				'<div class="panel-group" id="%s" role="tablist">',
				htmlspecialchars($id)
			);
			foreach ($panels as $p) {
				
				$retr.=sprintf(
					'<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="%5$s" role="button" data-toggle="collapse" data-parent="#%1$s" href="#%2$s" aria-expanded="%7$s" aria-controls="%2$s">
									%3$s
								</a>
							</h4>
						</div>
						<div id="%2$s" class="panel-collapse %6$s ">
							<div class="panel-body">
								%4$s
							</div>
						</div>
					</div>',
					$id,
					$p->id,
					$p->title,
					$p->content,
					$p->titleactive,
					$p->panelactive,
					$p->ariaexpanded
				);
				
			}
			$retr.='</div>';
			
			$panels=null;
			
			return $retr;
			
		});
		
		add_shortcode('panel',function ($atts, $content) use (&$panels, &$id) {
			
			if (is_null($panels)) return '';
			
			$atts=shortcode_atts(array('title' => '', 'id' => null,'active'=>null),$atts);
			if (!isset($atts['id'])) $atts['id']='panel'.(++$id);
			
			$panels[]=(object)array(
				'title' => $atts['title'],
				'id' => $atts['id'],
				'titleactive' => $atts['active'] == 'true' ? 'in active' : 'collapsed',
				'panelactive' => $atts['active'] == 'true' ? 'in collapsed' : 'collapsed collapse',
				'ariaexpanded' => $atts['active'] == 'true' ? 'true' : 'false',
				'content' => do_shortcode($content)
			);
			
			return '';
			
		});
		
	});	
?>