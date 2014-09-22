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

	Application['user-edit'] = {
		before : function(container) {
			WS.ControlTabsUsers( container );
		},
		action : function(container) {
			WS.FactoryComponent
				.create( container, 'UploadFile', '[data-component-upload-file]' )
			;
		}
	};

	Application['users'] = {
		action : function(container) {
			WS.FactoryComponent
				.create( container, 'EventsManagerUser', '[data-component-events-manager-user]' )
			;
		}
	};

	Application['profile'] = {
		before : function(container) {
			Application['user-edit'].before( container );
		},
		action : function(container) {
			Application['user-edit'].action( container );
		}
	};

	Application.setChosen = function() {
		jQuery('.chosen-select').chosen({width: '100%'});
	}
});
