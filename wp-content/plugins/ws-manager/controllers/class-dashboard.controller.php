<?php
/**
 * Controller Dashboard
 *
 * @package WS Plugin Template Manager
 * @subpackage Dashboard
 * @since 1.0
 */
class WS_Manager_Dashboard_Controller
{
	/**
	 * Instance of this class.
	 *
	 * @since 1.0
	 * @var object
	 */
	protected static $instance = null;	

	/**
	 * Adds needed actions after plugin in enabled
	 *
	 * @since 1.0
	 * @return void
	 */
	public function __construct()
	{
		//add_action( 'admin_head', array( &$this, 'favicon' ) );
		add_action( 'welcome_panel',  array( 'WS_Manager_Dashboard_view', 'render_welcome_panel' ) );
		add_action( 'wp_dashboard_setup', array( &$this, 'widgets_remove' ) );
	}

	public function widgets_remove()
	{
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	}

	public function favicon()
	{
		printf( '<link rel="Shortcut Icon" type="image/x-icon" href="%s" />', WS_Manager::get_url_assets( 'images/favicon.ico' ) );
	}

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
}