var sizePostfix = 'lg';
if (window.innerWidth <= 1200) {
  sizePostfix = 'md';
}
if (window.innerWidth <= 768) {
  sizePostfix = 'sm';
}

jQuery(function($) {
  /*** Email cloaking ***/
  var lastWindowTopLocation = $(window).scrollTop();
  $('.mailto, a[data-domain]').each(function() {
    $(this).attr('href', 'mailto:' + $(this).attr('data-prefix') + '@' + $(this).attr('data-domain'));
    if ($(this).text().length < 2) {
      $(this).text($(this).attr('data-prefix') + '@' + $(this).attr('data-domain'));
    }
  });

  /*** Updates all background images according to screen size; */
  $('*[data-background-' + sizePostfix + ']').each(function() {
    $(this).css({
      'background-image': 'url(' + $(this).data('background-' + sizePostfix) + ')'
    });
  });

  /*** Updates all  images according to screen size;  */
  //$('img[data-image-'+sizePostfix+']')

  $('img').each(function() {
    $(this).attr('src', $(this).attr('data-image-' + sizePostfix));
    $(this).attr('alt', $(this).attr('data-image-' + sizePostfix));

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

  if (($('.script-grid-gallery').length > 0) && ($().fgGallery) && $().isotope && $().imagesLoaded) {
    $('.script-grid-gallery').fgGallery({
      thumbpadding: $(this).data('thumbpadding'),
      thumbsperrow: $(this).data('thumbsperrow'),
      options: {
        feature: false
      },
      debug: false,
      onInit: function($element) {
        var $grid = $element.find('ul').isotope({
          itemSelector: 'li',
          layoutMode: 'fitRows'
        });
        $grid.imagesLoaded().progress(function() {
          $grid.isotope('layout');
          $element.find('.script-feature img').fadeIn();
        });
        $('.gallery-filters').on('click', 'button', function() {
          var filterValue = $(this).data('filter');
          $grid.isotope({
            filter: filterValue
          });
          $('.gallery-filters button').removeClass('active');

          $(this).addClass('active');
        });
      },
      onClickThumb: function(plugin, $thumb, event) {
        return true;
      }
    });
  }
  if (($('.script-sticky-traveler').length > 0) && $().fgStickyComponent) {
    $('.script-sticky-traveler').fgStickyComponent({
      debug: true,
      stopSelector: 'footer',
      topoffset: 100,
      bottomoffset: 112,
      removeSize: 990,
      onInit: function() {

      }
    });
  }

  $(window).scroll(function() {
    didScroll = true;

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

});
