;(function($) {
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

	$.fn.compileHandlebars = function() {
		return Handlebars.compile(this.html());
	};

	$.fn.fadeOutRemove = function(time) {
		var _self = this;

		_self.fadeOut(time , function() {
			_self.remove();
		});
	};

	$.fn.addClassFind = function(finder, className) {
		this.find( finder ).addClass( className );
	};

	$.fn.removeClassFind = function(finder, className) {
		this.find( finder ).removeClass( className );
	};
})(jQuery);