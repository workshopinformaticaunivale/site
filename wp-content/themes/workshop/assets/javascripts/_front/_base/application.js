Module('WS.Application', function(Application) {
	
	Application.init = function(container) {
		container.find( '.custom-check' ).iCheck({
			labelHover : false,
			cursor     : true
		});
	};

	Application.home = {
		before : function(container) {
			var menu = container.find( '.navigation' );

			WS.CurrentPage([
				'#palestrantes',
				'#minicursos',
				'#cadastre-se',
				'#parceiros',
				'#local'				
			], menu );

			WS.MenuRetract( container );

			menu.find( 'a' )
				.add( '[data-action=register], [data-action=login]' )				
				.scrollToPage()
			;
		},
		action : function(container) {
			container.find( '.login' ).on( 'click', function() {
				jQuery( '.box-login' ).toggleClass( 'active-form' );
			});

			container.find( '[data-action=login]' ).on( 'click', function() {
				jQuery( '.box-login' ).addClass( 'active-form' );
			});

			WS.FactoryComponent
			  .create( container, 'GoogleMaps', '[data-component-map]' )
			  .create( container, 'Speakers', '[data-component-speakers]' )
			  .create( container, 'Register', '[data-component-register]' )
			;
		}
	};

}, {});