 /*!
 ***************** script.js ***********************
 * specific script for this install
 */
jQuery(function($) {
 	$('#footer-map-wrapper').click(function(){
	    var $mapIcon = $(this).parent().find('.map-loading-icon')
	    $mapIcon.toggle();
    	google.load('maps','3', {other_params : 'sensor=false', callback : function(){

    	  if ($('#footer-map-wrapper').length > 0 ) {
    		set_google_map(
				{
					lat_offset : 0.00,
					lng_offset : 0,
					map_id : 'footer-map-wrapper',
					lat : 28.725378,
					lng : -81.7817200,
					zoom : 9,
					map_type : 'TERRAIN',
					info_window : '<img src="{{"logo.png" | asset_url }}" style="text-align: center; width: 100%; max-width: 130px; "  alt="{{ shop.name }}"><div style="line-height: 1.3em;">2740 Rock Bay Avenue<br/>Victoria, British Columbia,<br/>Canada, V8T 4R9</div><div style="padding-bottom: 3px;"><a href="https://www.google.ca/maps/dir/%27%27/2740+Rock+Bay+Ave,+Victoria,+BC+V8T+4R9/@48.4382674,-123.3745929,16z/data=!3m1!4b1!4m8!4m7!1m0!1m5!1m1!1s0x548f737ec0420d09:0xacf8768d073af68!2m2!1d-123.3703014!2d48.4382675" target="blank">Get  Directions</a></div><div>Local: <strong>250.383.5342</strong></div><div>Toll Free: <strong>800.883.0221</strong></div>'
					});
    	  }


    	  $('#footer-map-wrapper').addClass('google-active').css({'background-image': 'none'});
    	  $mapIcon.toggle(1500)

    	}});
	})
     // if ($('html').hasClass('no-touch')){
      	$('.has-footer-map').css('margin-bottom', ($('#map-parallax').innerHeight() -2) + 'px');
      	$('#map-parallax').css('position','fixed');
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