<?php
/**
 * WS API
 *
 * @package WS API
 * @version 1.0
 */
class WS_API
{
	protected $version = '1.0';

	protected static $instance = null;

	/**
	 * Slug Plugin
	 *
	 * @since 1.0
	 */
	const PLUGIN_SLUG = 'ws-api';

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since 1.0
	 */
	private function __construct()
	{
		// Load public-facing style sheet and JavaScript.
		//add_action( 'admin_enqueue_scripts', array( &$this, 'scripts_admin' ) );
		//add_action( 'admin_enqueue_scripts', array( &$this, 'styles_admin' ) );

		WS_Images_Library::get_instance();
		WS_Metas_Library::get_instance();
	}

	/**
	 * Register Script Admin
	 *
	 * @since 1.0
	 * @return void.
	 */
	public function scripts_admin()
	{

	}

	public function styles_admin()
	{
	
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

	public static function activate()
	{
		
	}
}
