 /*!
 ***************** script.js ***********************
 * generic scripts
 */

var sizePostfix = 'xl';
if (window.innerWidth < 1920) { sizePostfix = 'lg'; }
if (window.innerWidth < 1200) { sizePostfix = 'md'; }
if (window.innerWidth < 993)  { sizePostfix = 'sm'; }
if (window.innerWidth <= 768)  { sizePostfix = 'xs'; }
if (window.innerWidth <= 480)  { sizePostfix = 'xxs'; }

jQuery(function($) {
  /*** Email cloaking ***/
  var lastWindowTopLocation = $(window).scrollTop();
  
  emailCloak();

  /*** Updates all background images according to screen size; */
  $('*[data-background-' + sizePostfix + ']').each(function() {
    $(this).css({
      'background-image': 'url(' + $(this).data('background-' + sizePostfix) + ')'
    });
  });
  $('*[data-background]').each(function() {
    $(this).css({
      'background-image': 'url(' + $(this).data('background')+')'
    });
  });  


  
  /*** Updates all  images according to screen size;  */
  //$('img[data-image-'+sizePostfix+']')

  $('img').each(function() {
	getImageSize($(this));
    
   

  });
  $.each(document.getElementsByTagName("img"), function(index, value) {
    if (this.getAttribute('src') === null) {
      this.setAttribute('src', this.getAttribute('data-image'));
    }

  });

  $('.panel-group').on('shown.bs.collapse', function(event) {
    $(this).find('.panel-title *').removeClass('active');

    $(this).find('.panel-title *[href="#' + $(event.target).attr('id') + '"]').addClass('active');

  });

  var imgs=$('#articleContent img');
  if ((imgs.length > 0) && imgs.smoothZoom) {
    imgs.smoothZoom({
      navigationRight: '<i class=\"fa fa-angle-right\"></i>',
      navigationLeft: '<i class=\"fa fa-angle-left\"></i>'
    });

  }

  //adding hover code to bootstrap menus
  /*$('ul.nav li.dropdown').on('hover',
    function() {
      $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
    }, function() {
      $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
  });*/
	/*removes data in generalmodal*/
	$('#general-modal').on('hidden.bs.modal', function() {
	    $(this).removeData('bs.modal');
	});		
	$('#general-modal').on('loaded.bs.modal', function() {
	  emailCloak();
	});
	
	
  if ( (typeof $().smoothZoom  === 'function') && ( $('.script-gallery-action img').length > 0) ){	
	$('.script-gallery-action img').smoothZoom({ navigationRight: '<i class=\"fa fa-angle-right\"></i>', navigationLeft: '<i class=\"fa fa-angle-left\"></i>'});
  }
  
  
  if (($('.script-grid-gallery').length > 0) && $().isotope ) {	
	  var $element = $('.script-grid-gallery');
	  var $grid = $element.find('ul').isotope({itemSelector: 'li', layoutMode: 'fitRows'});
	  imagesLoaded($element.find('img'), function(e,msg){
		$grid.isotope('layout');		
	  });


  }
  
  //this is the code for stickycomponent
  if ( ($('.scipt-sidebar-sticky') ) && ( typeof $().fgStickyComponent === 'function') ) {
    $('.script-sidebar-sticky').fgStickyComponent({
        startselector: $('.script-sidebar-sticky').parent(),
        topoffset : 36,
		bottomoffset: 36,
		removesize : 990,
		stopselector : '.footer',
    });
	
	$('.script-sidebar-sticky').on('fg.stickycomponent.active', function(e, globals, wtop){
		var left = $(this).parent().parent().position().left +15;				
		$(this).css({'left': left+'px'});				
	});
	$('.script-sidebar-sticky').on('fg.stickycomponent.moving', function(e, globals, wtop){
		var left = $(this).parent().parent().position().left +15;				
		$(this).css({'left': left+'px'});				
	});
	$('.script-sidebar-sticky').on('fg.stickycomponent.bottom', function(e, globals, wtop){		
		$(this).css({'left': 'inherit'});				
	});
	$('.script-sidebar-sticky').on('fg.stickycomponent.normal', function(e, globals, wtop){
		$(this).css({'left': 'inherit'});				
	});
  }    

  $(window).scroll(function() {
    didScroll = true;
    // this checks if there is a youtube video
    $('.script-autoplay').each(function() {
      var inview = (($(window).outerHeight() + $(window).scrollTop()) > ($(this).offset().top + $(this).outerHeight()));

      // top of screen passed object above && top + height is greater than item offscreen below
      var outview = (($(window).scrollTop() > $(this).offset().top) || (($(window).scrollTop() + $(window).outerHeight()) < $(this).offset().top));
      //	console.log(($(window).scrollTop() + $(window).outerHeight()) < $(this).offset().top , $(window).scrollTop(), $(this).offset().top)
      if (($(this).data('playstate') === undefined) && (inview) && videoready) {
        $(this).data('playstate', 'play');
        youtube_hp.playVideo();

      } else if ((outview) && ($(this).data('playstate') == 'play')) {
        $(this).data('playstate', 'stop');
        youtube_hp.stopVideo();

      }

    });
    $('.script-parallax').each(function() {
      if (updateParallax($(this))) {}

    });
  });
  
  
	/* script-parallax
  *  Adds parallaxing background 
  *  data-offset="0" data-ratio="2.5"
  */
  $('.script-parallax').each(function(){
	  var offset = parseInt($(this).data('offset'));
	  var backgroundX  = '50%';
	  if ($(this).hasClass('background-image-pull-right')) {
		  backgroundX = 'right';
	  }
	  if ($(this).hasClass('background-image-pull-left')) {
		  backgroundX = 'left';
	  }
	  $(this).css({            
		  'background-transparent' : 'transparent',		  		
		  'background-position' : backgroundX+ ' ' + offset	+'px',
		  'background-size': '100% auto'
	  });		
  });
  // this animates to hash
  var hash = window.location.hash;
  if ((hash.length>1) && ($('ul.nav a[href="' + hash + '"]').length > 0)) {
	hash = hash && $('ul.nav a[href="' + hash + '"]').tab('show');
	$('html, body').animate({ scrollTop: $('ul.nav a[href="' + hash + '"]').offset().top -50   }, 2000);
  }
  
  $('header ul li:first-child').each(function (i, e) {
	e=$(e);
	e.click(e.toggleClass.bind(e,'active'));
  });
  
  // this is a fix to add placeholders to form-7 in footer
  $('.footer .form-group > label.sr-only ~ * > input[type="text"], .footer .form-group > label.sr-only ~ * > input[type="email"], .footer .form-group > label.sr-only ~ * > textarea').each(function (i, e) {
    var label=$(e.parentElement.parentElement).children('label.sr-only')[0];
    e.setAttribute('placeholder',label.innerHTML);
  });

});

function getImageSize($img) {
  var sizeArray = ['xxs','xs','sm','md','lg','xl'];	  
  var src;
  for (index = sizeArray.indexOf(sizePostfix); index < sizeArray.length; ++index) {	  
	var attr = $img.attr('data-image-'+sizeArray[index]);
	if ( attr !== undefined) {
	  if (attr.length > 5) {
		$img.attr('src', attr);			
		break;
	  }
	  
	}
  }
}
  
function emailCloak() {
  jQuery('.mailto, a[data-domain]').each(function() {
	jQuery(this).attr('href', 'mailto:' + jQuery(this).attr('data-prefix') + '@' + jQuery(this).attr('data-domain'));
	if (jQuery(this).text().length < 2) {
	  jQuery(this).text(jQuery(this).attr('data-prefix') + '@' + jQuery(this).attr('data-domain'));
	}
  });
}

function imagesLoaded($, fn) {
    var c = $.length;
    var msg = [];
	/*
    $.addEventListener('onload',action);
    $.addEventListener('onerror',action); */
	
    $.on('load',action);
    $.on('error',action);   
    function action(e){
        --c;
        if (e.type === 'error') {          
            msg.push('Error Loading.. ' + e.target.src);
        }
        if (c === 0) { fn(e,msg); }        
    }    
}
function fitScreen($resizeSelector, $arrayOfSelectors, callback) {
    var height = 0;    
    jQuery.each($arrayOfSelectors, function(){
        height +=jQuery(this).outerHeight();
    });    
    callback((jQuery(window).outerHeight() - height), height);    
}

// logic to check if parallax is going to go out of region, to do a fix   
function updateParallax($obj){
	var $ = jQuery;
	var pageBottom = (parseInt($(window).scrollTop()) + parseInt($(window).height()));
	
	
	if (pageBottom > $obj.offset().top) {
		
		var offset = $obj.data('offset');
		offset = (offset !== undefined) ? offset : 0;
		var ratio = $obj.data('ratio') ;
		ratio = (ratio !== undefined) ? ratio : 3;			
		var parallaxDiff = pageBottom - parseInt($obj.offset().top);
		var parallaxAdj = -(parallaxDiff / ratio)  + offset;
		$obj.data('imagePositionY',parallaxAdj);
		var backgroundX  = '50%';
		if ($obj.hasClass('background-image-pull-right')) {
			backgroundX = 'right';
		}
		if ($obj.hasClass('background-image-pull-left')) {
			backgroundX = 'left';
		}
		
		$obj.css('background-position',backgroundX+ ' ' + parallaxAdj +'px' );		
		
		
		// THIS IS first time
		if ($obj.data('image') === undefined) {
			var image_url = $obj.css('background-image');
			image_url = image_url.match(/^url\("?(.+?)"?\)$/);			
			if (image_url === null) {return false;}
			if (image_url[1]) {
				image_url = image_url[1];
				image = new Image();
				// just in case it gets called while loading image
				$obj.data('image',[]);
				$(image).load(function () {
					$obj.data('image',this);
					$obj.data('imageHeight', this.naturalHeight);
					$obj.data('imageUrl',image_url);
					var backgroundPos = parseInt($obj.css('background-position-y'),10);
					var checkImageFit = this.naturalHeight + backgroundPos;						
					//console.log('first --self', $self.outerHeight(),'img ',this.naturalHeight, 'bckpos ',backgroundPos, 'check', checkImageFit );
					
				});			
				image.src = image_url;			
			}
	
		}
		// this means i have already created image it is stored in data-image
		else {
			var imageHeight = ($obj.data('imageHeight') !== undefined) ? $obj.data('imageHeight'): 200;
			var backgroundPos = $obj.data('imagePositionY');
			var checkImageFit = imageHeight + backgroundPos;
			$obj.data('imagePositionY',parallaxAdj);
			$obj.css('background-position',backgroundX + parallaxAdj +'px' );						
		}
		return true;
	}		
	
}