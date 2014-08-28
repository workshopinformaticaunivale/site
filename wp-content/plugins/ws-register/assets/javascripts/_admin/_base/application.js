Module('WS.Application', function(Application) {
	//loader in all area site
	Application.init = function(container) {
		
	};

	Application['ws-course'] = {
		action : function( container ) {
			WS.Datepicker( container );
		}
	};
});
