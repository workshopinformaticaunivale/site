<?php
/**
 * Controller Dashboard
 *
 * @package WS Register
 * @subpackage Dashboard
 * @since 1.0
 */
class WS_Register_Dashboard_Controller
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
		add_action( 'welcome_panel',  array( 'WS_Register_Dashboard_view', 'render_welcome_panel' ) );
		add_action( 'wp_dashboard_setup', array( &$this, 'widgets_remove' ) );
		add_action( 'wp_dashboard_setup', array( &$this, 'widgets_add' ) );
		add_action( 'wp_dashboard_setup_students', array( &$this, 'widgets_add_students' ) );		
	}	

	public function widgets_remove()
	{
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	}

	public function widgets_add()
	{
		if ( is_user_admin() || is_super_admin() )
			return;

		add_meta_box(
			'dashboard_branding',
			'Workshop de Informática',
			array( 'WS_Register_Dashboard_view', 'render_dashboard_branding' ),
			'dashboard',
			'side',
			'high'
		);
	}

	public function widgets_add_students()
	{
		add_meta_box(
			'dashboard_students_information',
			'Informações',
			array( 'WS_Register_Dashboard_view', 'render_dashboard_students_information' ),
			'dashboard',
			'normal',
			'high'
		);

		add_meta_box(
			'dashboard_students_actions',
			'Mais Ações',
			array( 'WS_Register_Dashboard_view', 'render_dashboard_students_actions' ),
			'dashboard',
			'normal',
			'low'
		);
	}

	public function favicon()
	{
		printf( '<link rel="Shortcut Icon" type="image/x-icon" href="%s" />', WS_Register::get_url_assets( 'images/favicon.ico' ) );
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