/****** theme.js *****/

/* install specific ****/

jQuery(function($) {
    
    $(window).resize(function(e){
        fitScreen($('#header-container'), [ $('.script-navigation')], function(resize, height){        
            var NAV_LOCATION = 100;
            
            $('#header-container').css({'height': resize+'px','overflow' : 'hidden'});
            $('#header-container').find('.carousel-indicators').css({ bottom : 'inherit', top : (resize - NAV_LOCATION)+'px'});
            
            var testimage = $('.carousel .item img').first()[0];
            var galleryAspect = testimage.naturalWidth/testimage.naturalHeight;
            var windowAspect = $(window).outerWidth()/ resize
            
            //image is not heigh enough
            if (galleryAspect >= windowAspect){               
                var ciwidth = galleryAspect * resize;
                $('.carousel-inner').css({width: ciwidth+'px'})
            }
            else {               
                $('.carousel-inner').css({width: '100%'})
            }
        });        
    });    
    imagesLoaded($('.feature-carousel img'),
        function(e, msg) {
            var navpos = 0;
            if (msg.length > 0 ) {
                console.log('Error loading images.', msg);
            }
            // this updates the window and menu location after images have been loaded.
            $(window).trigger('resize');
            $('.script-navigation').fgStickyComponent({
                topoffset: 0,
                triggertop: $('#header-container').offset().top + $('#header-container').outerHeight()
            })
            // this code is used for fading menu on scroll down and fading in menu on scroll up
            $('.script-navigation').on('fg.stickycomponent.moving',function(e, g, wt){             
                if (navpos < wt) {
                    if (!$(this).hasClass('sticky-scroll-down')) {
                        $(this).removeClass('sticky-scroll-up').addClass('sticky-scroll-down')
                    }
                }
                else {
                    if (!$(this).hasClass('sticky-scroll-up')) {
                        $(this).removeClass('sticky-scroll-down').addClass('sticky-scroll-up')
                    }
                }
                navpos = wt;                
            });
            //clears all scrolling classes when in normal mode.
            $('.script-navigation').on('fg.stickycomponent.normal',function(e, g, wt){
                $(this).removeClass('sticky-scroll-down sticky-scroll-up');
            });
        }
    );
    
    if ( ($('.awards-ticker').length > 0) && ( typeof $().newsTicker === 'function' ) ){
        $('.awards-ticker').newsTicker({
            row_height: 114,
            max_rows: 3,
            speed: 1000,
            direction: 'up',
            duration: 6000,
            autostart: 1,
            pauseOnHover: 0
        });        
    }

    if ( ($('.fg-wow').length > 0 ) && ( typeof WOW === 'function') ) {
        var wow = new WOW(
          {
            boxClass:     'fg-wow',      // animated element css class (default is wow)
            animateClass: 'animated', // animation css class (default is animated)
            offset:       0,          // distance to the element when triggering the animation (default is 0)
            mobile:       true,       // trigger animations on mobile devices (default is true)
            live:         true,       // act on asynchronously loaded content (default is true)
            callback:     function(box) {
             
            },
            scrollContainer: null // optional scroll container selector, otherwise use window
          }
        );
        wow.init();
    }
    
   
    
});




/*Google maps function*/
function set_google_map(map_object){
	/* map_object { map_id : {the html id of the container},
					lat : {lat},
					lng : {lng},
					map_type : {HYBRID | ROADMAP | SATELLITE | TERRAIN},
					fillColor : {fill color of circle},
					strokeColor : {stroke color of circle},
					info_window : {what to display in info window}
	*/
    var point = new google.maps.LatLng(map_object.lat, map_object.lng);
    var myMapOptions = {
            zoom: map_object.zoom,
            center: point,
            scrollwheel : false,
			draggable : ($('html').hasClass('no-touch')),
            mapTypeId: google.maps.MapTypeId[map_object.map_type]
        };

    // create the map
    var map;
    if ($('body').data(map_object.map_id) == undefined)  {
        map = new google.maps.Map(document.getElementById(map_object.map_id),myMapOptions);
        $('body').data(map_object.map_id,{map: map, lat : map_object.lat, lng : map_object.lng });
	}
	else {
		if (($('body').data(map_object.map_id).lat == map_object.lat)  && ($('body').data(map_object.map_id).lng) == map_object.lng  )
			return /* means it is same lat and lng, no need to put point*/
	}
    /*sets markers*/
    var marker = new google.maps.Marker({   position: point,
                                            map: $('body').data(map_object.map_id).map,
                                            draggable: false,
                                            raiseOnDrag: false,
                                            icon : {    path: google.maps.SymbolPath.CIRCLE,
                                                        fillOpacity: 1,
                                                        fillColor: '#ffffff',
                                                        strokeOpacity: 1,
                                                        strokeColor: '#d55e1f',
                                                        strokeWeight: 4.0,
                                                        scale: 6 //pixels
                                                    }
                                            });
    /* creating info window  */
   if (map_object.info_window.length > 2)  {
        var myInfoWindowOptions = { content:  map_object.info_window  };
		_dPeetz('info window '+ map_object.info_window, 'Google Maps');
        var infoWindow = new google.maps.InfoWindow(myInfoWindowOptions);
        infoWindow.open($('body').data(map_object.map_id).map,marker);
        google.maps.event.addListener(marker, 'click', function() {
            var latitude = this.position.lat();
            var longitude = this.position.lng();
            infoWindow.open($('body').data(map_object.map_id).map,marker);
            /*  window.open("http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q="+latitude+","+longitude+"&sll="+latitude+","+longitude+"&sspn=0.172749,0.4422&ie=UTF8&ll="+latitude+","+longitude+"&spn=0.162818,0.4422&z=11&iwloc=A");*/
        });
   }
	if (map_object.link == true) {
		var link_href = "http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q="+map_object.lat+","+map_object.lng+"&sll="+map_object.lat+","+map_object.lng+"&sspn=0.172749,0.4422&ie=UTF8&ll="+map_object.lat+","+map_object.lng+"&spn=0.162818,0.4422&z=11&iwloc=A";
		$('#' +map_object.map_id).after('<p style="font-size: 12px; padding: 4px 0 12px;">View on <a class="google-map-link" href="' + link_href +'" target="_blank">Google Maps</a>.</p>');
	}
    $('body').data(map_object.map_id).map.setCenter(point);
}