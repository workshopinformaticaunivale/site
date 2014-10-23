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
		add_action( 'wp_dashboard_setup', array( &$this, 'wp_dashboard_setup_per_role' ) );
		add_filter( 'admin_body_class', array( &$this, 'body_class_role' ) );
		add_filter( 'enter_title_here', array( &$this, 'set_placeholder_title' ), 10, 2 );
		add_action( 'wp_ajax_set-courses-by-user', array( &$this, 'ajax_verify' ) );
	}

	public function ajax_verify()
	{
		if ( ! WS_Utils_Helper::is_request_ajax() )
			return;

		$action = WS_Utils_Helper::request_method_params( 'action', false );

		if ( ! $action )
			return;

		$action = str_replace( '-', '_', $action );
		$method = $_SERVER['REQUEST_METHOD'];

		do_action( "proxy_ajax_{$action}/{$method}" );
	}

	/**
	 * Set Enter Title
	 * @since 1.0
	 * @return void
	 */
	public function set_placeholder_title( $title, $post )
	{
		return apply_filters( "placeholder_title_{$post->post_type}", $title );
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

	public function wp_dashboard_setup_per_role()
	{
		if ( current_user_can( WS_Register_Moderators_Controller::ROLE ) ) :
			do_action( 'wp_dashboard_setup_moderators' );
			return;
		endif;

		if ( current_user_can( WS_Register_Student::ROLE ) ) :
			do_action( 'wp_dashboard_setup_students' );
			return;
		endif;
	}

	public function body_class_role()
	{
		if ( current_user_can( WS_Register_Student::ROLE ) )
			return 'user-student';
	}
}
