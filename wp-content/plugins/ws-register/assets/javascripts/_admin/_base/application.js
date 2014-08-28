Module('WS.Application', function(Application) {
	//loader in all area site
	Application.init = function(container) {
		
	};

	Application['ws-course'] = {
		action : function( container ) {
			WS.ControlDays(
				  container.find( '.container-day' )
				, WS.Datepicker( container )
				, WS.MaskAttribute()
			);
		}
	};
});
