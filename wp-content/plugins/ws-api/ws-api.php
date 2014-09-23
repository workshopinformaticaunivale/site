<?php
/*
	Plugin Name: Plugin Workshop | API
	Plugin URI: http://workshop.univale.br/
	Description: Autoload, Libraries and Generic Functions
	Version: 1.0
	Author: Workshop de Informática
	Author URI: http://univale.br/
	License: MIT
*/
class ClassAutoloader
{
	/**
	 * Instance of this class.
	 *
	 * @since 1.0
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Initialize the class auto loader
	 *
	 * @since 1.0
	 */
	public function __construct()
	{
		spl_autoload_register( array( $this, '_loader' ) );
	}

	/**
	 * Add custom loaders usage for outher plugins
	 *
	 * @since 1.0
	 * @param array $defaults
	 */
	public function add_custom_loaders( $defaults = array() )
	{
		return apply_filters( 'ws_add_loaders', $defaults );
	}

	public function require_config( $item, $key, $pattern_lower )
	{
		$plugin_slug = $this->_replace_name( $item['base_class'] );

		if ( strpos( $pattern_lower, $plugin_slug ) === false )
			return;

		if ( $pattern_lower == $plugin_slug ) :
			require_once sprintf( '%s/class-%s.php', $item['base_file'], $pattern_lower );
			return;
		endif;

		$pattern_lower = str_replace( $plugin_slug . '-', '', $pattern_lower );
		$partial       = $this->_slip( $pattern_lower );

		if ( ! isset( $partial[1] ) ) :
			require_once sprintf( '%1$s/%3$ss/class-%2$s.php', $item['base_file'], $partial[0], 'model' );
			return;
		endif;

		require_once sprintf( '%1$s/%3$ss/class-%2$s.%3$s.php', $item['base_file'], $partial[0], $partial[1] );
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

	private function _loader( $class_name )
	{
		$pattern_lower = $this->_replace_name( $class_name );

		$loaders = $this->add_custom_loaders(
			array(
				array(
					'base_class' => 'WS_API',
					'base_file'  => dirname( __FILE__ ),
				),
			)
		);

		$this->_dependencies( $class_name, $pattern_lower );

		array_walk( $loaders, array( $this, 'require_config' ), $pattern_lower );
	}

	private function _replace_name( $name )
	{
		return str_replace( '_', '-', strtolower( $name ) );
	}

	private function _slip( $class_name )
	{
		return preg_split( '/-(controller|view|helper|abstract)/',  $class_name, 2, PREG_SPLIT_DELIM_CAPTURE );
	}

	private function _dependencies( $class_name, $pattern_lower )
	{
		if ( $class_name == 'WS_Metas_Library' and ! class_exists( 'WS_Metas_Library' ) ) :
			require_once sprintf( '%s/includes/class-metas.library.php', dirname( __FILE__ ) );
			return false;
		endif;

		if ( $class_name == 'WS_Images_Library' and ! class_exists( 'WS_Images_Library' ) ) :
			require_once sprintf( '%s/includes/class-images.library.php', dirname( __FILE__ ) );
			return false;
		endif;

		if ( $class_name == 'WS_Utils_Helper' and ! class_exists( 'WS_Utils_Helper' ) ) :
			require_once sprintf( '%s/helpers/class-utils.helper.php', dirname( __FILE__ ) );
			return false;
		endif;
	}
}

ClassAutoloader::get_instance();

add_action( 'plugins_loaded', array( 'WS_API', 'get_instance' ) );