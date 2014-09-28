Module('WS.Utils', function(Utils) {

	Utils.now = ( Date.now || function() {
		return new Date().getTime();
  	});

	//copy underscore.js _.debounce
	Utils.debounce = function(func, wait, immediate) {
	    var timeout, args, context, timestamp, result;

	    var later = function() {
			var last = Utils.now() - timestamp;

			if (last < wait && last >= 0) {
				timeout = setTimeout(later, wait - last);
			} else {
				timeout = null;
				if (!immediate) {
					result = func.apply(context, args);
					if (!timeout) context = args = null;
				}
			}
	    };

	    return function() {
			context = this;
			args = arguments;
			timestamp = Utils.now();
			var callNow = immediate && !timeout;
			if (!timeout) timeout = setTimeout(later, wait);
			
			if (callNow) {
				result = func.apply(context, args);
				context = args = null;
			}

			return result;
	    };
	};

}, {});