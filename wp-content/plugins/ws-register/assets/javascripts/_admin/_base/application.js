Module('WS.Application', function(Application) {
	//loader in all area site
	Application.init = function(container) {
		Application.setChosen();
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

	Application.setChosen = function() {
		jQuery('.chosen-select').chosen({width: '100%'});
	}
});
