;(function($){
  $.fn.byData = function(dataAttr) {
    return $(this).find( "[data-" + dataAttr + "]" );
  };

  $.fn.isExist = function(selector, callback) {
    var element = $(this).find( selector );

    if ( element.length && typeof callback == 'function' ) {
      callback.call( null, element, $(this) );
    }

    return element.length;
  };
})(jQuery);