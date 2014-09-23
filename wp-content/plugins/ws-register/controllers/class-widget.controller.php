<?php
/**
 * Controller Widgets
 *
 * @package WS Register
 * @subpackage Widgets
 * @since 1.0
 */
class WS_Register_Widget_Controller
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
	 * Adds needed actions to create submenu and page
	 *
	 * @since 1.0
	 * @return void
	 */
	public function __construct()
	{
		add_action( 'widgets_init', array( &$this, 'register_widgets' ) );
	}

	/**
	 * Register Widgets
	 *
	 * Register all Widgets for the theme.
	 * Add your widget file in /widgets folder.
	 * Before call this function call the _load_widgets() function
	 * to load the files for the Widgets.
	 *
	 * @since 0.1
	 * @return void
	 */
	public function register_widgets()
	{
		// $available_widgets = array(
		// 	'CLass_Name_Widget',			
		// );

		// array_map( 'register_widget', $available_widgets );
	}
}
