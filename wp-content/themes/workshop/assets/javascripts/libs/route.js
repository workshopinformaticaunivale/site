;(function(context){

	"use strict";

	function _applyFuntion(callback, args) {
		if ( typeof callback == 'function' ) {
			callback.apply(null, (args || []));
		}
	};

	function Route(application, route, args) {
		var _steps = [ 'before', 'action', 'after' ]
		  , _count = _steps.length
		;

		var controller
		  , step
		;

		//execute all application
		_applyFuntion(application['init'], args);

		if ( typeof application[route] != 'object' ) {
			return;
			//throw "Module " + route + " not object in context App";
		}

		controller = application[route];

		for (var index = 0; index <= _count; index++) {
			step = _steps[index];
			//run step in modules
			_applyFuntion(controller[step], args);
		}
	};

	context.Route = Route;

})(window);
