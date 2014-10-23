Module( 'WS.FactoryComponent', function(FactoryComponent) {

	FactoryComponent.create = function(container, name, selector) {
		container.isExist( selector, jQuery.proxy( this, '_start', name ) );
		return this;
	};

	FactoryComponent._start = function(name, elements) {
		//set default Components object
		WS.Components = ( WS.Components || {} );

		//Component not defined return noop function
		if ( typeof WS.Components[name] != 'function' ) {
			return jQuery.noop;
		}

		this._iterator( elements, WS.Components[name] );
	};

	FactoryComponent._iterator = function(elements, constructor) {
		elements.each(function(index, element){
			constructor.call( null, jQuery( element ) );
		});
	};

}, {} );