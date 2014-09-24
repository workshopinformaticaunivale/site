<?php
/**
 * Moderators Controller
 *
 * @package WS Register
 * @subpackage Moderators
 */
class WS_Register_Moderators_Controller
{
	/** 
	* Default role for this class
	*
	* @var string
	*/
	const ROLE = 'ws-moderator';

	/**
	 * Instance of this class.
	 *
	 * @since 1.0
	 * @var object
	 */
	protected static $instance = null;	

	/**
	 * The constructor of this class.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function __construct()
	{
		
	}

	public static function add_default_capabilities()
	{
		$capability_type = WS_Register_Course::POST_TYPE;

		$caps = array(
			'read',
			'upload_files',
		);

		WS_Utils_Helper::add_custom_capabilities( self::ROLE, $caps );
	}

	public static function create_role()
	{
		add_role( self::ROLE, 'Moderador' );

		//avaliable capabilities
		self::add_default_capabilities();
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0
	 * @return object A single instance of this class.
	 */
	public static function get_instance()
	{
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}