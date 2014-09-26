Module('WS.ControlTabsUsers', function(ControlTabsUsers) {
	ControlTabsUsers.fn.initialize = function(container) {
		this.form = container.find( '#your-profile' );
		this.init();
	};

	ControlTabsUsers.fn.init = function() {
		this._hideBoxIsNotEnterprisesUser();
		this._onAfterShow( this.getHashIndex() );
		this.setPluginTabs();
	};

	ControlTabsUsers.fn.setPluginTabs = function() {
		this.form.tabFormsAdmin({
			activeIndex       : this.getHashIndex(),
			afterShowCallback : this._onAfterShow.bind(this)
		});
	};

	ControlTabsUsers.fn._onAfterShow = function(index) {
		var action = this.form.attr( 'action' );
		this.form.attr('action', action.replace(/#[0-9]{1,}|$/, '#' + index));
	};

	ControlTabsUsers.fn._hideBoxIsNotEnterprisesUser = function() {
		if ( this.isColumnStudent() ) {
			this.form.find( 'table:has([name=email]:not([data-not-remove]))' ).remove();
			this.form.find( 'tr:has([name=display_name]:not([data-not-remove]))' ).remove();
		}
	};

	ControlTabsUsers.fn.isColumnStudent = function() {
		return ( this.form.find( '[data-column=student]' ).length );
	};

	ControlTabsUsers.fn.getHashIndex = function() {
		return ( window.location.hash.replace('#', '') || 4 );
	};
});
