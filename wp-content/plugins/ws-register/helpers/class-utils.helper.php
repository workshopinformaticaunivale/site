<?php

class WS_Utils_Helper
{
	/**
	* Retrieves the database charset do create new tables.
	*
	* @global type $wpdb
	* @return type
	*/
	public static function get_charset()
	{
		global $wpdb;

		$charset_collate = '';

		if ( ! $wpdb->has_cap( 'collation' ) )
			return;

		if ( ! empty( $wpdb->charset ) )
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";

		if ( ! empty( $wpdb->collate ) )
			$charset_collate .= "\nCOLLATE $wpdb->collate";

		return $charset_collate;
	}

	/**
	 * Get Ip Host Machine Acess
	 *
	 * Use this function for get ip
	 *
	 * @since 0.1
	 * @return string
	 */
	public static function get_ipaddress()
	{
		$ip_address = false;

		if ( isset( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) )
			$ip_address = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];

		if ( empty( $ip_address ) )
			$ip_address = $_SERVER[ 'REMOTE_ADDR' ];

		if ( strpos( $ip_address, ',' ) !== false ) :
			$ip_address = explode( ',', $ip_address );
			$ip_address = $ip_address[0];
		endif;

		return esc_attr( $ip_address );
	}

	public static function is_request_ajax()
	{
		if ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) )
			$request_ajax = $_SERVER['HTTP_X_REQUESTED_WITH'];

		return ( ! empty( $request_ajax ) && strtolower( $request_ajax ) == 'xmlhttprequest' );
	}

	public static function convert_date_for_sql( $origin_date )
	{
		$origin_date = date( 'Y-m-d H:i', strtotime( str_replace( '/', '-', $origin_date ) ) );

		return $origin_date;
	}

	public static function convert_float_for_sql( $value )
	{
		$value = str_replace( '.', '', $value );
		$value = str_replace( ',', '.', $value );

		return $value;
	}

	public static function get_user_agent()
	{
		if ( ! isset( $_SERVER ) )
			return 'none';

		return $_SERVER[ 'HTTP_USER_AGENT' ];
	}

	public static function get_term_field( $post_id, $taxonomy, $field )
	{
		$terms = get_the_terms( $post_id, $taxonomy );

		if ( ! is_array( $terms ) || is_wp_error( $terms ) )
			return false;

		$term_first = array_shift( $terms );
		return $term_first->$field;
	}

	public static function get_query( $args = array() )
	{
		$defaults = array();

		$args = wp_parse_args( $args, $defaults );

		return new WP_Query( $args );
	}

	public static function get_method_params( $key, $default = '', $sanitize = 'esc_html' )
	{
		if ( ! isset( $_GET[ $key ] ) OR empty( $_GET[ $key ] ) )
			return $default;

		return self::sanitize_type( $_GET[ $key ], $sanitize );
	}

	public static function request_method_params( $key, $default = '', $sanitize = 'esc_html' )
	{
		if ( ! isset( $_REQUEST[ $key ] ) OR empty( $_REQUEST[ $key ] ) )
			return $default;

		return self::sanitize_type( $_REQUEST[ $key ], $sanitize );
	}

	public static function post_method_params( $key, $default = '', $sanitize = 'esc_html' )
	{
		if ( ! isset( $_POST[ $key ] ) OR empty( $_POST[ $key ] ) )
			return $default;

		if ( is_array( $_POST[ $key ] ) )
			return $_POST[ $key ];

		return self::sanitize_type( $_POST[ $key ], $sanitize );
	}

	public static function sanitize_type( $value, $name_function )
	{
		if ( ! is_callable( $name_function ) )
			return esc_html( $value );

		return call_user_func( $name_function, $value );
	}

	public static function get_post_type()
	{
		$post_type = null;

		if ( isset( $_GET['post_type'] ) ) :
			$post_type = esc_html( $_GET['post_type'] );
		endif;

		if ( isset( $_GET['action'], $_GET['post'] ) && 'edit' == $_GET['action']  ) :
			$post_type = get_post_type( $_GET['post'] );
		endif;

		if ( isset( $_POST['post_type'] ) ) :
			$post_type = esc_html( $_POST['post_type'] );
		endif;

		return $post_type;
	}

	public static function maybe_create_term( $term, $taxonomy, $args = array() )
	{
		$obj_term = get_term_by( 'name', $term, $taxonomy );

		if ( ! empty( $obj_term ) )
			return;

		$response = wp_insert_term( $term, $taxonomy, $args );
	}

	public static function maybe_create_page( $post_name, $postdata = array() )
	{
		$defaults = array(
			'post_status' => 'publish',
			'post_type'   => 'page',
			'post_title'  => isset( $postdata['title'] ) ? $postdata['title'] : $post_name,
			'post_name'   => $post_name,
		);
		$args = wp_parse_args( $postdata, $defaults );

		$obj_page = get_page_by_path( $post_name );

		if ( ! empty( $obj_page ) )
			return false;

		$new_page = wp_insert_post( $args );

		if ( is_wp_error( $new_page ) )
			return false;

		return true;
	}

	public static function error_server_json( $code, $message = 'Generic Message Error', $echo = true )
	{
		$response = json_encode(
			array(
				'status' 	=> 'error',
				'code'   	=> $code,
				'message'	=> $message,
			)
		);

		if ( ! $echo )
			return $response;

		echo $response;
	}

	public static function success_server_json( $code, $message = 'Generic Message Success', $echo = true )
	{
		$response = json_encode(
			array(
				'status' 	=> 'success',
				'code'   	=> $code,
				'message'	=> $message,
			)
		);

		if ( ! $echo )
			return $response;

		echo $response;
	}

	public static function object_server_json( $args = array(), $echo = true )
	{
		$response = json_encode( $args );

		if ( ! $echo )
			return $response;

		echo $response;
	}

	public static function add_custom_capabilities( $role, array $caps )
	{
		$current_role = get_role( $role );

		foreach ( $caps as $cap ) {
			$current_role->add_cap( $cap );
		}
	}

	public static function transform_columns_in_where( $columns = array() )
	{
		$where = array();

		foreach ( $columns as $key => $value )
			$where[] = esc_sql( $key ) . ' = ' . esc_sql( $value );

		return implode( ' AND ', $where );
	}
}
