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
		
		add_shortcode('accordion',function($atts,$content)  {			
			return do_shortcode('[collapse type="accordion"]' . $content.'[/collapse]');
		});
		
		add_shortcode('collapse',function ($atts, $content) use (&$panels, &$aid) {
			
			$panels=array();
			$retr ='';
			
			do_shortcode($content);
			$iconup = isset($atts['iconup']) ? $atts['iconup']: ' ';
			$icondown = isset($atts['icondown']) ? $atts['icondown'] : ' ';
			$fontfamily = isset($atts['fontfamily']) ? $atts['fontfamily']: 'FontAwesome';
			$id='accordion'.(++$aid);
			$retr.=sprintf(
				'
				<style>
				#%1$s .collapse-trigger:before {
					content: "\%2$s";
					font-family: %4$s;
					margin-right: 4px;
					transform: rotate(90deg);
					-moz-transform:  rotate(90deg);
					-webkit-transform: rotate(90deg);
					-o-transform: rotate(90deg);
					-ms-transform: rotate(90deg);					
					transition: all 0.25s linear;
					-moz-transition: all 0.25s linear;
					-webkit-transition: all 0.25s linear;
					-o-transition: all 0.25s linear;				
					
					display: inline-block;
				}
				#%1$s .collapse-trigger:hover {
					text-decoration: none;
				}
				
				#%1$s .collapse-trigger.collapsed:before {
					content: "\%2$s";
					font-family: %4$s;
					margin-right: 4px;
					transform: rotate(0);
					-moz-transform:  rotate(0);
					-webkit-transform: rotate(0);
					-o-transform: rotate(0);
					-ms-transform: rotate(0);				
				}				
				</style>
				<div class="panel-group" id="%1$s" role="tablist">',
				htmlspecialchars($id),
				$iconup,
				$icondown,
				$fontfamily
				
			);
			$type = isset($atts['type']) ? $atts['type'] : '';
			foreach ($panels as $p) {
				if ($type == 'accordion'){
					if ($p->posturl === null ){
					$retr.=sprintf(
						'<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title" >
									<a class="%5$s collapse-trigger" role="button" data-toggle="collapse" data-parent="#%1$s" href="#%2$s" aria-expanded="%7$s" aria-controls="%2$s">
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
					else {
						$retr.=sprintf(
						'<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a class="collapse-trigger" role="button"  href="%2$s" aria-expanded="false" aria-controls="false">
										%1$s
									</a>
								</h4>
							</div>							
						</div>',						
						$p->title,						
						$p->posturl
					);							
					}
			
				}
				else {
					$retr.=sprintf(
						'<div>
							<h4 class="collapse-title"><a class="collapse-trigger %5$s" role="button" data-toggle="collapse" data-parent="#%1$s" href="#%2$s" aria-expanded="%7$s" aria-controls="%2$s">
										%3$s
							</a></h4>
							<div class="collapse" id="%2$s">
								<div class="collapse-panel-body">
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
						$p->ariaexpanded,
						$p->posturl
					);
				}

				
				
			}
			$retr.='</div>';
			
			$panels=null;
			
			return $retr;
			
		});
		
		add_shortcode('panel',function ($atts, $content) use (&$panels, &$id) {
			
			if (is_null($panels)) return '';
			
			$atts=shortcode_atts(array('title' => '', 'id' => null,'active'=>null, 'posturl'=>null),$atts);
			if (!isset($atts['id'])) $atts['id']='panel'.(++$id);
			
			$panels[]=(object)array(
				'title' => $atts['title'],
				'id' => $atts['id'],
				'posturl' => $atts['posturl'],
				'titleactive' => $atts['active'] == 'true' ? 'in active' : 'collapsed',
				'panelactive' => $atts['active'] == 'true' ? 'in collapsed' : 'collapsed collapse',
				'ariaexpanded' => $atts['active'] == 'true' ? 'true' : 'false',
				'content' => do_shortcode($content)
			);
			
			return '';
			
		});
		
	});	
?>