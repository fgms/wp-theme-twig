# FG Wordpress Template With Twig

## Shortcodes

### [accordion] & [panel]

Creates [Bootstrap accordions](http://getbootstrap.com/javascript/#collapse).

The [accordion] shortcode does not accept any attributes.  All content of an [accordion] shortcode which is not a [panel] shortcode (or content nested therein) is ignored.

The [panel] shortcode accepts the following attributes:

-	id: An HTML ID which shall be used for the panel (one will be automatically generated if this is not supplied)
-	title: A title for the panel

The content of the [panel] shortcode shall be used as the content of the panel in the accordion.

### [carousel] & [slide]

Creates [Bootstrap carousels](http://getbootstrap.com/javascript/#carousel).

The [carousel] shortcode accepts the following attributes:

-	controls: If "false" then the carousel shall not have controls for moving between the slides
-	id: An HTML ID which shall be used for the carousel (one will be automatically generated if this is not supplied)
-	indicators: If "false" then the carousel shall not have indicators indicating which slide is currently active (and how many slides there are)
-	innerclass: An HTML class (or list of classes) which shall be applied to the inner container of the generated Bootstrap carousel
-	outerclass: An HTML class (or list of classes) which shall be applied to the outer container of the generated Bootstrap carousel

All content of a [carousel] shortcode which is not a [slide] shortcode is ignored.

The [slide] shortcode accepts the following attributes:

-	alt: The alt text for the image which appears on the slide
-	caption: A caption which shall appear on the slide (defaults to no caption)
-	title: The title of the slide (defaults to no title)
-	url: The URL of the image which shall appear on the slide

All content of a [slide] shortcode is ignored.

### [tabs] & [tab]

Creates [Bootstrap tabs](http://getbootstrap.com/javascript/#tabs).

The [tabs] shortcode does not accept any attributes.  All content of a [tabs] shortcode which is not a [tab] shortcode (or content nested therein) is ignored.

The [tab] shortcode accepts the following attirbutes:

-	active: If "true" then the tab-in-question shall be the default active tab, if no tab is marked as active the first tab shall be active by default
-	id: An HTML ID which shall be used for the panel (one will be automatically generated if this is not supplied)
-	title: A title for the tab

The content of the [tab] shortcode shall be used as the content of the tab.