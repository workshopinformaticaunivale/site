<?php

class WP_Theme_Utils
{
	public static function get_query( $args = array() ) 
	{
		$defaults = array();

		$args = wp_parse_args( $args, $defaults );

		return new WP_Query( $args );
	}

	public static function get_method_params( $key, $default = '' )
	{
		if ( ! isset( $_GET[ $key ] ) OR empty( $_GET[ $key ] ) )
			return $default;

		return esc_html( $_GET[ $key ] );
	}

	public static function post_method_params( $key, $default = '' )
	{
		if ( ! isset( $_POST[ $key ] ) OR empty( $_POST[ $key ] ) )
			return $default;

		return esc_html( $_POST[ $key ] );
	}

	public static function url_page( $slug )
	{
		$object_page = get_page_by_path( $slug );

		if ( empty( $object_page ) )
			return site_url();

		return get_permalink( $object_page->ID );
	}
}