;(function($, window){
	var Control = function(container, attrs) {
		this.container        = container;
		this.typeEventTrigger = 'change';

		this.assign(attrs);
	};

	Control.prototype.init = function() {
		this.addEventListener();
		this.setDefaultVisible();
	};

	Control.prototype.addEventListener = function() {
		this.container
			.on(this.typeEventTrigger, '[data-action="change"]', this._onClickChange.bind(this))
		;
	};

	Control.prototype.setDefaultVisible = function() {
		var elements = this.container.find( '[data-action="change"]:checked' )
		  , _self    = this
		;

		elements.each(function(index, element) {
			_self._onActionVisible( $( element ) );
		});
	};

	Control.prototype._onActionVisible = function(target) {
		var group    = $( target.data('attr-group') || 'body' );
		var elements = group.find( target.data('attr-selector') );
		
		this._showElements(target, elements, group);
	};

	Control.prototype._onClickChange = function(event) {
		this._onActionVisible( $( event.target ) );
	};

	Control.prototype._showElements = function(target, elements, group) {
		this._hideAllElements( group, elements );

		if ( !this._checkValueByTarget( target ) ) {
			return;	
		}

		this._insertGroupByTarget( target, group );
		( elements.length && elements.show() );
	};

	Control.prototype._checkValueByTarget = function(target) {
		var compare = target.data( 'attr-compare' );

		if ( compare ) {
			return ( compare == target.val() );
		}

		return target.is( ':checked' );
	};

	Control.prototype._insertGroupByTarget = function(target, group) {
		var insert = target.data( 'attr-insert' );
		//add class active group elements
		group.addClass( 'active' );

		if ( insert == 'inner' ) {
			target.append( group );
			return;
		}

		if ( insert == 'parent' ) {
			target.parent().append( group );
			return;
		}
	};

	Control.prototype._hideAllElements = function(group, elements) {
		group.removeClass( 'active' );			 

		if ( elements.length ) {
			group.children().hide();
		}
	};

	Control.prototype.assign = function(attrs) {
		for ( var item in attrs ) {
			if ( !this.hasOwnProperty(item) || this[item] != attrs[item] ) {
				this[item] = attrs[item];
			}
		}
	};

	$.fn.visibleByTarget = function(options) {
		var instance;

		options = $.extend({
			typeEventTrigger : 'change'
		}, ( options || {}));

		this.each(function(index, element) {
			instance = new Control($(element), options);
			instance.init();
		});
	};

})(jQuery, window);