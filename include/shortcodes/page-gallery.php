<?php
call_user_func(function ($data) {
	function slugify($str){
	  return strtolower(preg_replace('/[^a-z]/i', '_', $str, -1));
	}

	add_shortcode('page_gallery',function ($atts, $content) use(&$data)   {
		$id = empty($atts['id']) ? 0: intval($atts['id']);
		$random = false;

		if (is_array(get_post_meta($id,'randomize', false))){
			$random = (get_post_meta($id,'randomize', false)[0] == 'yes');
		}

		$feature_enable = empty($atts['feature']) ? false : ($atts['feature'] == 'true');
		$filter_enable = empty($atts['filters']) ? true : ($atts['filters'] == 'true');
		$images = [];
		$items = [];
		$gallery = [
		  'filter' =>  ['enable' => $filter_enable,],
		  'filters' => [],
		  'options' => ['limit' => 500,'random'=> $random,'thumbs_per_row' => get_post_meta($id,'thumbs-per-row', '5'),	'feature' => $feature_enable],
		  'images' => &$images
		];
		$retr = 'Not a valid ID';
		$post = get_post($id);
		// check if it is a gallery type post
    if ($post->post_type == 'gallery'){
			$isempty = get_post_meta($id,'database-or-yaml');
			$yamlflag = empty($isempty) ? false : (get_post_meta($id,'database-or-yaml')[0] == 'yaml') ;

			// code if it is yaml file.
			if ($yamlflag){
				$isempty =get_post_meta($id,'yaml-location');
				$yaml_name = empty($isempty) ? false : get_post_meta($id,'yaml-location')[0];
				if (($yaml_name !== false) AND (strlen($yaml_name) > 2) ){
					//setting up items
					$items = (!empty($data['config']['gallery'][$yaml_name])) ? $data['config']['gallery'][$yaml_name] : [];
				}
			}
			// code if it is internally configured.
			else {
				$items = get_post_meta($id,'cp_gallery')[0];
			}

      foreach ( $items as $item){
				$thumb=null; $medium=null; $large=null; $full=null; $title=null; $youtube=null;
				//means is file.
				if ( (!empty($item['id'])) or (!empty($item['thumb_id'])) ) {
					$thumb = (! empty($item['thumb_id'])) ? wp_get_attachment_image_src($item['thumb_id'])[0]: null;
					$medium= wp_get_attachment_image_src($item['id'],'medium')[0];
					$large= wp_get_attachment_image_src($item['id'],'large')[0];
					$full= wp_get_attachment_image_src($item['id'],'full')[0];
					if (!empty($item['thumb_id'])){
							$isempty = get_post_meta($id,'gallery_type');
							$type = $isempty ?  get_post_meta($id,'gallery_type')[0] : '';
							$medium_override = ($type != 'media-kit') ?wp_get_attachment_image_src($item['thumb_id'], 'full')[0] : $large;
					}
					$youtube=(empty($item['youtubeid'])) ? false : $item['youtubeid'];;
					$title=get_the_title($item['id']);

				}
				//means interal
				else {
					if ( (!empty($item['image'][0])) or (!empty($item['youtubeid'])) ){
            $thumb =  (! empty ($item['thumb'][0])) ?  wp_get_attachment_image_src($item['thumb'][0])[0] : null;
						$medium = wp_get_attachment_image_src($item['image'][0],'medium')[0];
						$large = wp_get_attachment_image_src($item['image'][0],'large')[0];
						if (!empty($item['thumb'][0])){
							$medium_override =  wp_get_attachment_image_src($item['thumb'][0], 'full')[0];
						}
						$full = wp_get_attachment_image_src($item['image'][0], 'full')[0];
						$title = get_the_title($item['image'][0]);
						$youtube = (empty($item['youtubeid'])) ? false : $item['youtubeid'];
					}
				}

				// filters
        $filter = [];
				if (!empty($item['fg-filters'])){
					foreach ($item['fg-filters'] as $f){
						if (strlen(trim($f)) > 0){
							// this adds the filter to list
	            $slug = slugify($f);
	            $gallery['filters'][$slug] =$f;
	            $filter[] = $slug;
						}
	        }
				}

        $images[] = [
          'thumb' => empty($thumb) ? $medium : $thumb,
          'medium'=> empty($thumb) ? $large : $medium_override,
          'large' => $full,
          /*'caption' => (empty($item['caption'])) ? false : $item['caption'],*/
          'title' => $title,
          'filters'=> $filter,
          'youtubeid' => $youtube,
					'group' => 'group'
        ];
      }
			$template = 'page-gallery.twig';
			$isempty = get_post_meta($id,'gallery_type');
			$type = $isempty ?  get_post_meta($id,'gallery_type')[0] :'';
			if ($type == 'media-kit'){
				$template = 'media-kit-gallery.twig';
			}

      try {
        ob_start();
        Timber::render($template,array_merge($data,$gallery));
        $retr=ob_get_contents();
        ob_end_clean();
      } catch (Twig_Error_Loader $e){
        $retr = '<script>console.error("Error Loading twig template '. $t . ' ' .str_replace('"',"'",$e->getMessage()) .'")</script>';
      }
/*
      $retr .= '<pre>';
      $retr .= print_r($gallery, true);
      $retr .= '</pre>';
*/
      }

			return $retr ;
		});
	},array('config'=>$get_config()) );
?>
