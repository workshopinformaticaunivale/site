jQuery(function() {
	var _body = jQuery( 'body' );
	//app, route, args
	Route( WS.Application, _body.data( 'route' ), [_body] );
});
