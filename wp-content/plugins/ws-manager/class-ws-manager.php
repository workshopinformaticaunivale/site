<?php
/**
 * WS Plugin Template
 *
 * @package WS Plugin Template Manager
 * @version 1.0
 */
class WS_Manager
{
	protected $version = '1.0';

	protected static $instance = null;

	/**
	 * Slug Plugin
	 *
	 * @since 1.0
	 */
	const PLUGIN_SLUG = 'ws-plugin-template';

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since 1.0
	 */
	private function __construct()
	{
		// Load public-facing style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( &$this, 'scripts_admin' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'styles_admin' ) );

		WS_Images_Library::get_instance();
		WS_Metas_Library::get_instance();
		//WS_Manager_Widget_Controller::get_instance();
		//WS_Manager_Featured_Controller::get_instance();
	}

	/**
	 * Register Script Admin
	 *
	 * @since 1.0
	 * @return void.
	 */
	public function scripts_admin()
	{
		// wp_enqueue_script(
		// 	self::PLUGIN_SLUG . '-admin-script',
		// 	plugins_url( 'assets/javascripts/admin.script.min.js', __FILE__ ),
		// 	array( 'jquery' ),
		// 	filemtime( plugin_dir_path(  __FILE__  ) . 'assets/javascripts/admin.script.min.js' ),
		// 	true
		// );

		// wp_localize_script(
		// 	self::PLUGIN_SLUG . '-admin-script',
		// 	'WPAdminVars',
		// 	array(
		// 		'ajaxUrl' => admin_url( 'admin-ajax.php' )
		// 	)
		// );
	}

	public function styles_admin()
	{
		wp_enqueue_style(
			self::PLUGIN_SLUG . '-admin-style',
			plugins_url( 'style.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path(  __FILE__  ) . 'style.css' )
		);
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

	public static function get_url_assets( $path = '' )
	{
		return plugins_url( 'assets/' . $path, __FILE__ );
	}

	public static function activate()
	{
		//is code active plugin
	}
}
