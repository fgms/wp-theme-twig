# FG Wordpress Template With Twig

## Installation
* Install wordpress
* Install Timber  plugin (twig)
* Install Metabox plugin (http://metabox.io)
* run ```composer update``` on the root of the theme (this install symfony yaml parser for reading settings)
* Select theme.
* On local machine run npm install to install grunt task runner.
* Select google fonts, update Gruntifile.js,  and run ```grunt googlefonts```
* Run grunt watch and update css or js to upload assets.

## File Structure
```
├── 404.php
├── composer.json
├── functions.php
├── include
│   ├── shortcodes
│   │   ├── accordion.php
│   │   ├── ajax.php
│   │   ├── carousel.php
│   │   ├── custom-template.php
│   │   ├── email.php
│   │   ├── tabs.php
│   │   ├── twig.php
│   │   ├── gallery.php
│   │   └── link.php
|   |    
│   ├── shortcodes.php
│   └── theme-settings.php
├── index.php
├── page-templates
│   ├── blank.php
│   ├── contact.php
│   ├── full-width.php
│   ├── landingpage.php
│   ├── listings.php
│   └── modular.php
├── README.md
├── screenshot.png
├── style.css
└── twig-templates
    ├── base.twig
    ├── footer.twig
    ├── header.twig
    ├── partials
    │   ├── article-list-element.twig
    │   ├── article.twig
    │   ├── awards.twig
    │   ├── blog-sidebar.twig
    │   ├── blog-tags-cats.twig
    │   ├── bootstrap-carousel.twig
    │   ├── breadcrumbs.twig
    │   ├── contact-sidebar.twig
    │   ├── gallery.twig
    │   ├── modal-inner.twig
    │   ├── navigation.twig
    │   ├── realtor-directory.twig
    │   ├── responsive-list.twig
    │   ├── social-sharing.twig
    │   └── specials.twig
    └── wp
        ├── archive.twig
        ├── index.twig
        ├── page-404.twig
        ├── page-blank.twig
        ├── page-contact.twig
        ├── page-full-width.twig
        ├── page-landing-twig
        ├── page-listings.twig
        ├── page-modular.twig
        ├── page.twig
        └── single.twig


```


## Shortcodes

### [accordion] & [panel]

Creates [Bootstrap accordions](http://getbootstrap.com/javascript/#collapse).

The [accordion] shortcode does not accept any attributes.  All content of an [accordion] shortcode which is not a [panel] shortcode (or content nested therein) is ignored.

The **[panel]** shortcode accepts the following attributes:

| Attribute | Description |
| --------- | ----------- |
| id        | An HTML ID which shall be used for the panel (one will be automatically generated if this is not supplied) |
| title     | A title for the panel |
| active    | default false, opens accordion if true |

The content of the [panel] shortcode shall be used as the content of the panel in the accordion.


### [collapse] & [panel]

Creates [Bootstrap collapse](http://getbootstrap.com/javascript/#collapse).

The [collapse] shortcode accepts these attributes.
All content of an [collapse] shortcode which is not a [panel] shortcode (or content nested therein) is ignored.

| Attribute | Description |
| --------- | ----------- |
| iconup        | the icon that is shown next to text to indicate up, the down arrow is same but rotated 90deg |
| fontfamily     | for the icon default is FontAwsome |



The **[panel]** shortcode accepts the following attributes:

| Attribute | Description |
| --------- | ----------- |
| id        | An HTML ID which shall be used for the panel (one will be automatically generated if this is not supplied) |
| title     | A title for the panel |
| active    | default false, opens accordion if true |

The content of the [panel] shortcode shall be used as the content of the panel in the accordion.


### [carousel] & [slide]

Creates [Bootstrap carousels](http://getbootstrap.com/javascript/#carousel).

The **[carousel]** shortcode accepts the following attributes:

| Attribute | Default | Description |
| --------- | ------- | ----------- |
| controls  | true    | If "false" then the carousel shall not have controls for moving between the slides |
| id        | myCarousel | An HTML ID which shall be used for the carousel (one will be automatically generated if this is not supplied) |
| indicators | true   | If "false" then the carousel shall not have indicators indicating which slide is currently active (and how many slides there are) |
| innerclass | | An HTML class (or list of classes) which shall be applied to the inner container of the generated Bootstrap carousel|
| outerclass | | An HTML class (or list of classes) which shall be applied to the outer container of the generated Bootstrap carousel |

All content of a [carousel] shortcode which is not a [slide] shortcode is ignored.

The **[slide]** shortcode accepts the following attributes:

| Attribute | Description |
| --------- | ----------- |
| alt       | The alt text for the image which appears on the slide |
| caption   | A caption which shall appear on the slide (defaults to no caption) |
| title     | The title of the slide (defaults to no title) |
| url       | The URL of the image which shall appear on the slide |

All content of a [slide] shortcode is ignored.

### [custom-template] & [custom-item]

Allows a specified Twig template to be rendered.

The **[custom-template]** shortcode accepts one attribute explicitly:

| Attribute | Description |
| --------- | ----------- |
| template  | Specifies the file name of the template which shall be rendered |

Any other attributes of the shortcode have their values passed through as variables in the template.

Note that if you specify the "data" attribute it will be overwritten and its value will therefore be inaccessible (see below).

The [custom-item] shortcode creates an entry in the array passed through to the rendered template as the "data" variable.  The entry shall have a property corresponding to each attribute of the [custom-item] shortcode in addition to a "content" property which contains a string containing the contents of the shortcode.  Note that because of this if you use the "content" attribute of a [custom-item] shortcode it will be overwritten and therefore its value will be inaccessible.

### [tabs] & [tab]

Creates [Bootstrap tabs](http://getbootstrap.com/javascript/#tabs).

The [tabs] shortcode does not accept any attributes.  All content of a [tabs] shortcode which is not a [tab] shortcode (or content nested therein) is ignored.

The **[tab]** shortcode accepts the following attirbutes:

| Attribute | Description |
| --------- | ----------- |
| active    | If "true" then the tab-in-question shall be the default active tab, if no tab is marked as active the first tab shall be active by default |
| id        | An HTML ID which shall be used for the panel (one will be automatically generated if this is not supplied) |
| title     |A title for the tab |

The content of the [tab] shortcode shall be used as the content of the tab.


### [ajax]

The [ajax] shortcode is used to load page content via ajax.  The page that the ajax code is on requires you to use No Template or the blank template.

The **[ajax]** shortcode accepts the following attribute.

| Attribute | Description |
| --------- | ----------- |
| template  | is used to load the twig template it is going to wrap the content around |


### [fgallery]

The **[fgallery]** shortcode accepts the following attribute.

| Attribute | Default | Description |
| --------- | ------- | ----------- |
| width     |  100px  | width of thumb |
| height    |  auto   | height of thumb |
| class     |         | adds class attribute |
| alt       |         | adds alt tag |
| thumb     |         | uses a different image as the thumb |
| group     |         | this adds group to gallery to link images to one group |

[fgallery alt="this is the alttext" thumb="absolute/path/to/image" width="150px" height="100%" class="my-class" ]<imc src="path/to/image" />[/fgallery]


### [link]

The **[link]** shortcode accepts the following attribute.

| Attribute | Description |
| --------- | ----------- |
| id        | The post id which will convert it to url (preferred method)|
| href      | The complete url |
| attr      | use any attribute ie class="myclass" target="_blank"

[link id="2"]contact us[/link] --> <a href="http://example.com/contact-us">contact us</a>

[link href="https://google.com" attr="target=_blank" ]Search Engine[/link]  --> <a href="https://google.com" target="_blank">Search Engine</a>
