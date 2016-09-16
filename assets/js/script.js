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
  $.each(document.getElementsByTagName("img"), function() {
    if (this.getAttribute('src') === null) {
      this.setAttribute('src', this.getAttribute('data-image'));
    }

  });

  $('.panel-group').on('shown.bs.collapse', function(event) {
    $(this).find('.panel-title *').removeClass('active');

    $(this).find('.panel-title *[href="#' + $(event.target).attr('id') + '"]').addClass('active');

  });
/*
  var imgs=$('#articleContent img');
  if ((imgs.length > 0) && imgs.smoothZoom) {
    imgs.smoothZoom({
      navigationRight: '<i class=\"fa fa-angle-right\"></i>',
      navigationLeft: '<i class=\"fa fa-angle-left\"></i>',
	  navigationClose: '<i class="fa fa-times-circle" aria-hidden="true"></i>'
    });

  }*/

  //adding hover code to bootstrap menus
  if (window.innerWidth > 993)  {
    $('ul.nav li.dropdown').on('mouseover',function(){
				var $dropdownMenu = $(this).find('.dropdown-menu');
				var menuOffset = 20;
				var menuWindowTop = $dropdownMenu.parent().offset().top - $(window).scrollTop();
				var menuFitBottom = $(window).innerHeight() >= menuWindowTop + $dropdownMenu.outerHeight()+ menuOffset;
				var menuFitTop = ( menuWindowTop - $(window).scrollTop()) > 0 ;
				if (menuFitBottom || !menuFitTop ){
					$dropdownMenu.css('top','100%');
				}
				else {
					$dropdownMenu.css('top','-'+$dropdownMenu.outerHeight()+'px');
				}			
        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
				
      });
    $('ul.nav li.dropdown').on('mouseout',function(){
      $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
      });
    $('.no-touch ul.nav li.dropdown .dropdown-toggle').on('click',function(){ 
        window.location = $(this).attr('href');
      });    
  }

  
  
	/*removes data in generalmodal*/
	$('#general-modal').on('hidden.bs.modal', function() {
	    $(this).removeData('bs.modal');
	});		
	$('#general-modal').on('loaded.bs.modal', function() {
	  emailCloak();
	});
	
	
  if ( (typeof $().smoothZoom  === 'function') && ( $('.script-gallery-action img').length > 0) ){
    console.log('smoothzoom');
	$('.script-gallery-action img').smoothZoom({
	  navigationRight: '<i class=\"fa fa-angle-right\"></i>',
	  navigationLeft: '<i class=\"fa fa-angle-left\"></i>',
	  navigationClose: '<i class="fa fa-times-circle" aria-hidden="true"></i>',
	  zoomoutSpeed : 800,
		onImageChange: function($element){			
			var $bigImageThumb = $('[data-rel="'+$element.parent().attr('id')+'"]');			
			$bigImageThumb.closest('ul').find('li').removeClass('active');
			$bigImageThumb.closest('li').addClass('active');
			if ($bigImageThumb.length > 0){				
				$bigImageThumb.trigger('click');
			}
		}
	});
  }

  
  if (($('.script-grid-gallery').length > 0) && $().isotope ) {	
	  var $element = $('.script-grid-gallery');
	  var $grid = $element.find('ul').isotope({itemSelector: 'li', layoutMode: 'fitRows'});
	  imagesLoaded($element.find('img'), function(e,msg){
			$grid.isotope('layout');		
			$('.gallery-filters').on('click','button',function(){
					var filterValue = $(this).data('filter');
					$grid.isotope({filter: filterValue}) ;
					$('.gallery-filters button').removeClass('active');				
					$(this).addClass('active');
			}); 			
	  });
  }
	
	if ($('.script-load-feature').length > 0 ){
		$('.script-load-feature').on('click',function(e){			
			$(this).closest('ul').find('li').removeClass('active');
			$(this).closest('li').addClass('active');		
			e.preventDefault();
			var $closestGallery = $(this).closest('.script-grid-gallery');
			var newImageId = $(this).attr('data-rel');			
			if ((newImageId !== undefined) && ($closestGallery.find('#'+ newImageId).length > 0)){
				$closestGallery.find('.script-feature-content').css({
					'overflow': 'hidden',
					'height' : $closestGallery.find('.script-feature-content > *').outerHeight() + 'px'
				});
				$closestGallery.find('.script-feature-content > .active').removeClass('active').stop(true,true).fadeOut(300,function(){

					$closestGallery.find('#'+ newImageId).addClass('active').stop(true,true).fadeIn();
				});				
			}

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
	
	$('.script-sidebar-sticky').on('fg.stickycomponent.active', function(){
		var left = $(this).parent().parent().position().left +15;				
		$(this).css({'left': left+'px'});				
	});
	$('.script-sidebar-sticky').on('fg.stickycomponent.moving', function(){
		var left = $(this).parent().parent().position().left +15;				
		$(this).css({'left': left+'px'});				
	});
	$('.script-sidebar-sticky').on('fg.stickycomponent.bottom', function(){		
		$(this).css({'left': 'inherit'});				
	});
	$('.script-sidebar-sticky').on('fg.stickycomponent.normal', function(){
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
  if ((hash.length>1) && ($('a[href="' + hash + '"]').length > 0)) {
		(hash && $('a[href="' + hash + '"]').tab('show'));		
		if ($('a[href="' + hash + '"]').length > 0) {
			$('html, body').animate({ scrollTop: $('a[href="' + hash + '"]').offset().top - 150   }, 2000);
		}	
  }
  
  $('header ul li:first-child').each(function (i, e) {
	e=$(e);
	e.click(e.toggleClass.bind(e,'active'));
  });
  
  $('.script-clone').on('click',function(){
    console.log($(this).closest('.script-clone-wrapper').find('.script-clone-trigger'));
    $(this).closest('.script-clone-wrapper').find('.script-clone-trigger')[0].click();
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
	
	$.each(function(){
	  if (this.complete) {
		--c;
		if (c === 0) { fn({ e : { type: ''}},msg); }
	  }
	  
	});
	
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
function updateParallax($obj, minheight){
	minheight = minheight || 1200;	
	var $ = jQuery;
	var pageBottom = (parseInt($(window).scrollTop()) + parseInt($(window).height()));
	
	if (window.innerWidth >= minheight) {
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
	else {
	  $obj.css({'background-position' : '0 0', 'background-size' : 'cover'});
	}		
}

function randomizeChildren($children){
    var allElems = $children.get(),
		getRandom = function(max) {	return Math.floor(Math.random() * max);	},
		shuffled = $.map(allElems, function(){
			var random = getRandom(allElems.length),
				randEl = $(allElems[random]).clone(true)[0];
			allElems.splice(random, 1);
			return randEl;
	   });
	$children.each(function(i){$(this).replaceWith($(shuffled[i])); });
}

