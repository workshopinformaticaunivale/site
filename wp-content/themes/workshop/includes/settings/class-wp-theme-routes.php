<?php

class WP_Theme_Routes
{
	public function module_js()
	{
		echo sprintf( 'data-route="%s"', $this->_is_route_by_page() );
	}

	private function _is_route_by_page()
	{
		if ( is_home() )
			return 'home';		
	}
}
