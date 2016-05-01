/****** theme.js *****/

/* install specific ****/

jQuery(function($) {    
    imagesLoaded($('.feature-carousel img'),
        function(e, msg) {
            if (msg.length > 0 ) {
                console.log('Error loading images.', msg);
            }
            $('.script-navigation').fgStickyComponent({
                topoffset: 0,
                triggertop: $('.feature-carousel').offset().top + $('.feature-carousel').outerHeight()
            })            
        }
    ); 
    
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