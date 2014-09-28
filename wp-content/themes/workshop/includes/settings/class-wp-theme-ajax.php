<?php

class WP_Theme_Ajax
{
	private $_ajax_url;

	public function __construct()
	{
		$this->_ajax_url = admin_url( 'admin-ajax.php' );
	}

	public function get_ajax_url()
	{
		return $this->_ajax_url;
	}
}
