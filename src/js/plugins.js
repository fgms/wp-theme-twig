

(function($) {
    $.fgStickyComponent = function(el, options) {
        var defaults = {
			topoffset : 75,
			bottomoffset: 75,
			removeSize : 990,
			stopSelector : 'footer',
			onInit: function() {}
           
        };
        var plugin = this;		
        plugin.settings = {};
		plugin.globals = {
			parentSize : 0,
			parentTop: 0
			
		};

        var $element = $(el), // reference to the jQuery version of DOM element
             element = el;    // reference to the actual DOM element			

        // the "constructor" method that gets called when the object is created
        plugin.init = function() {             
            plugin.settings = $.extend({}, defaults, options, $element.data());		
			plugin.globals.parentSize = $element.parent().innerWidth();
			plugin.globals.parentTop = $element.parent().offset().top;
			plugin.globals.height = $element.outerHeight();
			$element.parent().css({position: 'relative'});
			plugin._resize();
			
			//window resize
			$(window).resize(function(event){ plugin._resize();	});
			
			$(window).scroll(function(){plugin._resize();})	;		
			plugin.settings.onInit($element);    
            
        };		
		plugin.is_touch_device = function() {  
			try {  
				document.createEvent("TouchEvent");  
				return true;  
			} catch (e) {  
				return false;  
			}  
		};
		
		plugin._resize = function() {
			// checking if functoin is enabled
			if (window.innerWidth >= plugin.settings.removeSize) {
				$element.css({width : $element.parent().innerWidth()+'px', height : '100%', position : 'absolute'});
				//checking if top of page is passed trigger, go to scrolling down mode
				if ($element.parent().offset().top  <= $(window).scrollTop() + plugin.settings.topoffset ) {
					var windowScrollTopLimit = $(window).scrollTop() + (plugin.globals.height + plugin.settings.topoffset + plugin.settings.bottomoffset );				
					
					var stickyAbsolutePosition = ($(plugin.settings.stopSelector).offset().top - $element.parent().offset().top ) -(plugin.settings.bottomoffset + plugin.globals.height);
					// this means it has at its lower limits
					if ($(plugin.settings.stopSelector).offset().top < windowScrollTopLimit)  {
						$element.css({top: stickyAbsolutePosition +'px', position: 'absolute'})	;
					}
					// this means scrolling down.
					else {						
						// testing if touch device use absolute instead of fixed as fixed is clitch on touch devices.
						if (!plugin.is_touch_device()){
							$element.css({top :  plugin.settings.topoffset+'px', position: 'fixed'});
						} else {
							var topForMobile = $(window).scrollTop() - $element.parent().offset().top+ plugin.settings.topoffset;							
							$element.css({top : topForMobile +'px', postion : 'absolute'});
						}
					}
				}
				else{
					$element.css({top: 0, position: 'relative'});
				}				
			}
			// don't do anything due to size of screen 
			else {
				$element.css({top: 0,position: 'relative',width : $element.parent().innerWidth()+'px', height : '100%'});
			}
		};		
        var _debug = function(title,msg) {
			if (plugin.settings.debug){
            	console.log(new Date(),title+  ' : ',msg);
			}
        };
        plugin.init();
    };
    // add the plugin to the jQuery.fn object
    $.fn.fgStickyComponent = function(options) {
        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {
            // if plugin has not already been attached to the element	
            if (undefined === $(this).data('fgStickyComponent')) {
                var plugin = new $.fgStickyComponent(this, options);
                $(this).data('fgStickyComponent', plugin);
            }
        });
    };
})(jQuery); 
