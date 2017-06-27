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


 $('*[data-random="true"]').each(function(){
   randomizeChildren($(this));
 });
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

 //adding hover code to bootstrap menus
 /*
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
*/
 if (($('ul.nav li.dropdown').length > 0) && true ){
   //	Add hover code to Bootstrap menus
   $('ul.nav li.dropdown').on('mouseover',function () {
       if (window.innerWidth <= 768) return;
       var dropdown = $(this);
       dropdown.addClass('open');
       var menu = dropdown.find('.dropdown-menu');
       //  Figure out whether the menu should go below (default)
       //  or above (if it doesn't fit below)
       var offset = 20;
       var window_top = menu.parent().offset().top - $(window).scrollTop();
       var fit_bottom = $(window).innerHeight() >= window_top + menu.outerHeight() + offset;
       var fit_top = (window_top - $(window).scrollTop()) > 0;
       if (fit_bottom || !fit_top) {
           menu.css('top','100%');
       } else {
           menu.css('top','-' + menu.outerHeight() + 'px');
       }
       //  Figure out whether the menu should go to the right
       //  (default) or to the left (if it doesn't fit to the
       //  right)
       var left = menu.parent().offset().left;
       var window_width = $(window).innerWidth();
       if (left > (window_width / 2)) {
           if ((left + menu.parent().outerWidth() - menu.outerWidth()) < 0) {
               menu.addClass('no-fit');
           } else {
               menu.removeClass('no-fit');
           }
           menu.css('left','auto');
           menu.css('right','0px');
           menu.addClass('flipped');
       } else {
           if ((left + menu.outerWidth()) > window_width) {
               menu.addClass('no-fit');
           } else {
               menu.removeClass('no-fit');
           }
           menu.css('left','0px');
           menu.css('right','auto');
           menu.removeClass('flipped');
       }
   }).on('mouseout',function () {
       if (window.innerWidth < 768) return;
       var dropdown = $(this);
       dropdown.removeClass('open');
   });
 }

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
/*
 imagesLoaded($('.feature-carousel img'),
     function(e, msg) {
         if (msg.length > 0 ) {
             console.log('Error loading images.', msg);
         }
         // this updates the window and menu location after images have been loaded.
         $('.carousel').css({'min-height' : '100%'}).animate({opacity: 1});

         $(window).trigger('resize');
     }
 );
*/
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




 /*removes data in generalmodal*/
 $('#general-modal').on('hidden.bs.modal', function() {
     $(this).removeData('bs.modal');
 });
 $('#general-modal').on('loaded.bs.modal', function() {
   //emailCloak();
 });









 if ($('.script-load-feature').length > 0 ){
   $('.script-load-feature').on('click',function(e){
     $(this).closest('ul').find('li').removeClass('active');
     $(this).closest('li').addClass('active');
     e.preventDefault();
     var $closestGallery = $(this).closest('.script-grid-gallery');
     var newImageId = $(this).attr('data-rel');
     if ((newImageId !== undefined) && ($closestGallery.find('#'+ newImageId).length > 0)){
     /*
       $closestGallery.find('.script-feature-content').css({
         'overflow': 'hidden',
         'height' : $closestGallery.find('.script-feature-content > *').outerHeight() + 'px'
       });*/

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

 $(window).on('resize', function(){
   $('.script-parallax').each(function() {
     if (updateParallax($(this))) {}
   });
 });

 if ($('.multi-item-carousel').length > 0){
   var interval = ($('.multi-item-carousel').attr('data-interval') !==  undefined) ? $('.multi-item-carousel').attr('data-interval') : 8000;
   if (interval === 'false'){
     interval = false;
   }
   $('.multi-item-carousel').carousel({
     interval: interval
   });

  // for every slide in carousel, copy the next slide's item in the slide.
  // Do the same for the next, next item.
   $('.multi-item-carousel .item').each(function(){
     var next = $(this).next();
     if (!next.length) {
       next = $(this).siblings(':first');
     }
     next.children(':first-child').clone().appendTo($(this));
     if (next.next().length>0) {
       next.next().children(':first-child').clone().appendTo($(this));
     } else {
       $(this).siblings(':first').children(':first-child').clone().appendTo($(this));
     }
   });

 }

   /* Nav tabs */
   $('.nav-tabs.accordion a[role="tab"]' ).on('click',function(e){
     e.preventDefault();
     var navtabs = $(this).closest('.nav-tabs.accordion');
     $(navtabs).children('li').each(function(){
       $(this).removeClass('active');
     })
     $(this).parent('li').addClass('active in');
     var tabSelector = $(this).attr('href');

     $(navtabs).next('.tab-content.accordion').find('>.tab-pane').each(function(){
       $(this).removeClass('active in');
       $(this).attr('style','');
     })
     $(tabSelector).addClass('active in');
     if (typeof  check_for_refresh === 'function'){
       check_for_refresh(tabSelector);
     }
   })



   /* Tab Accordions in responsive */
   $('.tab-accordion-header a[role="tab"]').click(function(e){
     e.preventDefault();
     var tabSelector = $(this).attr('href');
     var activeclass = $(tabSelector).hasClass('active')
     var navtabs = $(this).closest('.tab-content.accordion').prev('.accordion');
     $(navtabs).children('li').each(function(){
       $(this).removeClass('active');
     })
     if (activeclass){
       $(tabSelector).slideUp('300',function(){$(tabSelector).removeClass('active in')});
     }

     else {
       $('.tab-accordion-header a').each(function(index){
         var eachTabSelector = $(this).attr('href');
         if (eachTabSelector == tabSelector){
            $(eachTabSelector).slideDown('300',function(){
               $(eachTabSelector).addClass('active in');
               $(navtabs).find('[href="' + tabSelector + '"]').parent('li').addClass('active in');
               $('html, body').animate({ scrollTop: $(tabSelector).offset().top    }, 1000);
             //check_for_refresh(tabSelector);
            });
         }
         else {
           $(eachTabSelector).slideUp('300',function(){	 $(eachTabSelector).removeClass('active in'); });
         }
       });
     }


   });



 $('header ul li:first-child').each(function (i, e) {
 e=$(e);
 e.click(e.toggleClass.bind(e,'active'));
 });

 $('.script-clone').on('click',function(){
   $(this).closest('.script-clone-wrapper').find('.script-clone-trigger')[0].click();
 });

 // this is a fix to add placeholders to form-7 in footer
 $('.footer .form-group > label.sr-only ~ * > input[type="text"], .footer .form-group > label.sr-only ~ * > input[type="email"], .footer .form-group > label.sr-only ~ * > textarea').each(function (i, e) {
   var label=$(e.parentElement.parentElement).children('label.sr-only')[0];
   e.setAttribute('placeholder',label.innerHTML);
 });
 if ($('.search-header button').length > 0){
   $('.search-header button').on('click',function(e){
       // means it is closed
       var $checkClass = $(this).closest('.input-group');
       if ($checkClass.hasClass('searchbox-close') ){
         $checkClass.removeClass('searchbox-close').addClass('searchbox-open');
       }
       else {
         var $searchInput = $checkClass.find('input');
         if ($searchInput.val() !== ''){
           $(this).closest('form').submit();
           return true;
         }
         $checkClass.removeClass('searchbox-open').addClass('searchbox-close');
       }
       e.preventDefault();
       return false;
    });
 }



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


// this will remove items from global namespace by adding an init function
var fgms = (function($){
 function set_emailCloak(){
   trace.push({function: 'set_emailCloak', arguments : arguments});
   $('.mailto, a[data-domain]').each(function() {
     $(this).attr('href', 'mailto:' + $(this).attr('data-prefix') + '@' + $(this).attr('data-domain'));
     if ($(this).text().length < 2) {
       $(this).text($(this).attr('data-prefix') + '@' + $(this).attr('data-domain'));
     }
   });
 }
 function set_galleries(){
   trace.push({function: 'set_galleries', arguments : arguments});

  if (($('.script-grid-gallery').length > 0) && $().isotope ) {
    var $element = $('.script-grid-gallery');
    $element.each(function(){
      var $self = $(this);
      $self.find('img').each(function(){
        $(this).parent().css({'opacity':0})
      })
      $self.prepend('<div class="gallery-loading-indicator"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>');
      imagesLoaded($self.find('img'), function(e,msg, $this){
        var $container = $self.closest('.modular-home-gallery');
        var $grid = $self.find('ul').isotope({itemSelector: 'li', layoutMode: 'fitRows'});
        update_galleries($self);
        $grid.isotope('layout');
        $self.attr('data-loaded', 'true');
        $self.find('img').each(function(){
          $(this).parent().css({'opacity':1})
        })
        $self.find('.gallery-loading-indicator').remove();
        $container.find('.gallery-filters').on('click','button',function(){
           var filterValue = $(this).data('filter');
           $grid.isotope({filter: filterValue}) ;
           $('.gallery-filters button').removeClass('active');
           $(this).addClass('active');
        });
     })
   });

   $('body').find('*[data-filter="*"]').trigger('click');
   $(window).on('resize', function(){

     if ($('.script-grid-gallery').length > 0) {
       var $element = $('.script-grid-gallery');
       $element.each(function(){
         if ($(this).attr('data-loaded') === 'true' ){
           update_galleries($(this));
         }
       })
     }
   });

  }
   if ( (typeof $().smoothZoom  === 'function') && ( $('.script-gallery-action img').length > 0) ){
     $('.script-gallery-action img').smoothZoom({
       navigationRight: '<i class=\"fa fa-angle-right \"></i>',
       navigationLeft: '<i class=\"fa fa-angle-left\"></i>',
       navigationClose: '<i class="fa fa-times-circle" aria-hidden="true"></i>',
       zoomoutSpeed : 800,
       onImageChange: function($element){
         var $bigImageThumb = $('[data-rel="'+$element.parent().attr('id')+'"]');
         $bigImageThumb.closest('ul').find('li').removeClass('active');
         $bigImageThumb.closest('li').addClass('active');
         if ($bigImageThumb.length > 0){	$bigImageThumb.trigger('click');	}
       }
     });
   }
 }

// checks if on page gallery overflowing
 function resize_page_to_fit_gallery(gallery_height){
   if ( $('.page-sidebar-with-sidebar-gallery').length > 0) {
     var g_top = $('.page-sidebar-with-sidebar-gallery').offset().top;
     var g_height = $('.page-sidebar-with-sidebar-gallery').outerHeight();
     var g_bottom = (Math.max(g_height, gallery_height)+ g_top);

     var m_top = $('.main-content').offset().top;
     var m_height = $('.main-content').outerHeight();
     var m_bottom = (m_top + m_height);
     var diff =  m_bottom - g_bottom ;

     // this means that gallery is overflowing.
     if ( (diff < 15)  && ($('#page-sidebar-gallery-style').length == 0) ){
       var padding = 40 + Math.abs(diff);
       var style = '<style id="page-sidebar-gallery-style">@media screen and (min-width: 993px){.page-with-sidebar-gallery {padding-bottom:'+(padding)+"px;}}</style>";
       $('.article-body').append(style);
     }
   }
 }

 /* This updates the thumb sizes to ratio 0.66 */
 function update_galleries($gallery){
   if ($gallery.find('.script-feature-content a').length > 0){
     var height_feature = $gallery.find('.script-feature-content a').outerWidth() * 0.66 +'px';
     $gallery.find('.script-feature-content a').css({height: height_feature});
     $gallery.find('.__st_gallery_feature_content').css({height: height_feature });
   }
  $gallery.find('li').css({height: ($gallery.find('li').outerWidth() * 0.66) +'px'});
  // this is the best way to use these two heights to determine height
  resize_page_to_fit_gallery(($gallery.find('.script-feature-content a').outerWidth() * 0.66)+($gallery.find('li').outerWidth() * 0.66));
 }


  // this animates to hash
 function get_hash(){
   trace.push({function: 'get_hash', arguments : arguments});
   var hash = window.location.hash;
   if ((hash.length>1) && ($('a[href="' + hash + '"]').length > 0)) {
     (hash && $('a[href="' + hash + '"]').tab('show'));
     if ($('a[href="' + hash + '"]').length > 0) {
       $('html, body').animate({ scrollTop: $('a[href="' + hash + '"]').offset().top - 150   }, 2000);
     }
   }
 }
 function set_cookie(cname, cvalue, exdays){
   trace.push({function: 'set_cookie', arguments : arguments});
   var d = new Date();
   d.setTime(d.getTime() + (exdays*24*60*60*1000));
   var expires = "expires="+ d.toUTCString();
   document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
 }
 function get_cookie(cname){
   trace.push({function: 'get_cookie', arguments : arguments });
   var name = cname + "=";
   var decodedCookie = decodeURIComponent(document.cookie);
   var ca = decodedCookie.split(';');
   for(var i = 0; i <ca.length; i++) {
       var c = ca[i];
       while (c.charAt(0) == ' ') {
           c = c.substring(1);
       }
       if (c.indexOf(name) == 0) {
           return c.substring(name.length, c.length);
       }
   }
   return "";
 }
 function get_cookie_modal(obj) {
   trace.push({function: 'get_cookie_modal', arguments : arguments});
   if (obj === undefined || obj === null){
     return;
   }
   var modal;
   if ((get_cookie('cookie_modal') !== 'set') && ( $('#cookie_model').length === 0 ) ){
     modal = '<div class="modal fade '+ (typeof(options.specials.class) === 'undefined' ? '': options.specials.class)  +'" id="cookie_modal" role="dialog">';
     modal += '<div class="modal-dialog" ><div class="modal-content" style="background-position: center; background-size: auto;background-attachment:scroll;">';
     modal += '<div class="modal-header"><button type="button" class="close" data-dismiss="modal"><i class="fa fa-times-circle "></i></button></div>';
     modal += '<div class="modal-body"  ><div class="modal-content-background" ><h2>'+obj.title+'</h2>';
     if ((obj.subtitle !== undefined ) && (obj.subtitle.length > 3)){
       modal += '<div class="modal-subtitle" style="padding-bottom: 15px;"><strong>'+obj.subtitle+'</strong></div>';
     }
     modal += '<div class="modal-content-data">' +obj.content + '</div></div></div>'
     modal += '</div></div></div>';
     $('body').append(modal);


     $(window).scroll(function(){
       if ( (get_cookie('cookie_modal') !== 'set') && ($(window).scrollTop() > (options.specials.scrolltop || 250) ) ){
         $('#cookie_modal').modal();
         set_cookie('cookie_modal','set', 1);
       }
     });
   }

   var embedid = '#'+ get_value(options, 'specials.embed.id','fgms-specials-embed');
   if ( ( get_value(options,'specials.embed.enable') ) && ($(embedid).length > 0) && (get_value(options,'specials.data').length > 0 )){
    var embed = '<ul style="margin: 0 0 12px;padding:0;list-style:none;">';
    $.each(get_value(options,'specials.data'), function(i,v){
      embed += '<li>';
      embed += '<h4 style="margin-top:0;padding-bottom: 8px;">'+ v.title+'</h4>';
      embed += '<div class="embed-specials-subtitle" style="font-weight:bold;">' +v.subtitle + '</div>';
      embed += '<div class="embed-specials-content">' + v.content + '</div>';
      embed += '</li>';
    });
    embed += '</ul>'
    $(embedid).html(embed);

   }
   $('body').append('<style>body.modal-open>*:not(.modal){-webkit-filter: blur(5px);-moz-filter: blur(5px);-o-filter: blur(5px);-ms-filter: blur(5px);filter: blur(5px);}</style>')
 }
 function log(type, msg){
   type = type || 'log';
   if ((type === 'error' ) || (options.debug === true)){
     console[type](msg);
   }
 }

 function get_value(obj,_string,_default){
   if (typeof(obj) !== 'object') return _default || false
   var parts = _string.split('.');
   var newObj = obj[parts[0]];
   if(parts[1]){
       parts.splice(0,1);
       var newString = parts.join('.');
       return get_value(newObj,newString,_default);
   }
   return newObj || _default || false;
 }


 function get_init(opts){
  trace.push({function: 'get_init', arguments : arguments});
  opts = opts || {};
  if ((typeof(options.debug) === 'undefined') || !$.isEmptyObject(opts)){   options = $.extend(defaults, opts);  }
 }
 var defaults = {
   debug : false,
   specials : {
     enable : true,
     scrolltop : 250,
     class : 'fgms-specials-modal',
     embed : {
       enable: true,
       id : 'fgms-specials-embed'
     },
     data : []

   }
 }
 var options = {};
 var trace = [];
 return {
   init: function(opts){
     get_init(opts);
     if (options.debug === true ){
       log('error','Fgms System is in Debug mode.')
     }
    // log ('warn',[1,'overflow of gallery logic needs tweaking']);
     return this;
   },
   setup: function(){
     set_emailCloak();
     set_galleries();
     get_hash();
     return this;
   },

   get_specials: function(specials){
     trace.push({function: 'get_specials', arguments : arguments});
     get_init();
     options.specials.data = specials;
     if (options.specials.enable === true){
        trace.push('Specials are enabled');
       if (options.debug === true){
         set_cookie('cookie_modal','', 1);
       }
       var item = [Math.floor(Math.random()*specials.length)];
       get_cookie_modal(specials[item]);
     }
     else {
       trace.push('Specials are disable');
     }
     return this;
   },
   dump: function() {
     console.log('******** OPTIONS ************');
     console.log(options);
     console.log('******** TRACE ************');
     $.each(trace, function(index,value){
      // console.log(typeof(value), value);
       if (typeof(value) === 'string'){
         console.log(value);
       }
       else if ( typeof(this.function) !== 'undefined' ) {
         console.log(this.function, this.arguments);
       }
       else {
         console.log(value);
       }
     });
     return this;
   }
 }
})(jQuery);


function check_for_refresh(){

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
   if (c === 0) { fn(e,msg,$); }
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
           $obj.data('imageWidth', this.naturalWidth);
           $obj.data('imageUrl',image_url);
           var backgroundPos = parseInt($obj.css('background-position-y'),10);
           var checkImageFit = this.naturalHeight + backgroundPos;
           updateParallax($obj);
         });
         image.src = image_url;
       }

     }
     // this means i have already created image it is stored in data-image
     else {
       var imageHeight = ($obj.data('imageHeight') !== undefined) ? $obj.data('imageHeight'): 200;
       var imageWidth = ($obj.data('imageWidth') !== undefined) ? $obj.data('imageWidth'): 200;
       var backgroundPos = $obj.data('imagePositionY');
       var checkImageFit = imageHeight + backgroundPos;
       /* this is code that takes image ratio and finds out height for image in window
          it also adds 5 px for good measures.
          then it limits value so it should never go pass image height on parallax.
       */
       var img_ratio = imageHeight/imageWidth ;
       var max_background_y = (img_ratio * $obj.outerWidth() - $obj.outerHeight()) - 5;
       max_background_y *= -1;

       var background_y = Math.max(parallaxAdj, max_background_y );
       $obj.data('imagePositionY',parallaxAdj);
       // this is required when the window height is bigger than image height;
       if (background_y > 0 ){
         background_y = 0;
       }
       $obj.css('background-position',backgroundX + background_y +'px' );
       //console.log(img_ratio, 'image:', imageHeight,'x',imageWidth,'bP',background_y, 'MAX', max_background_y, 'container:', $obj.outerHeight(),'x', $obj.outerWidth(),(img_ratio * $obj.outerWidth()));
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
