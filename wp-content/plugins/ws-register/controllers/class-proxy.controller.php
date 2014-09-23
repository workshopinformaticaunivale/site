<?php
/**
 * Proxy Controller
 *
 * @package WS Register
 * @subpackage Proxy
 */
class WS_Register_Proxy_Controller
{
	/**
	 * Instance of this class.
	 *
	 * @since 1.0
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0
	 * @return object A single instance of this class.
	 */
	public static function get_instance()
	{
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Adds needed actions after plugin in enabled
	 *
	 * @since 1.0
	 * @return void
	 */
	public function __construct()
	{		
		add_filter( 'manage_users_custom_column', array( &$this, 'manage_users_columns' ),  10, 3 );
		add_action( 'admin_head', array( &$this, 'admin_head_per_page' ) );
	}

	public function manage_users_columns( $empty = '', $column_name, $user_id )
	{		
		return apply_filters( "ws_manage_users_column_{$column_name}", $user_id );
	}

	public function admin_head_per_page()
	{
		global $pagenow;

		$pagenow = str_replace( '.php', '', $pagenow );

		do_action( "ws_admin_head_page_{$pagenow}" );
	}
}
