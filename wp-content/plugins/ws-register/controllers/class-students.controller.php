<?php
/**
 * Students Controller
 *
 * @package WS Plugin Template Manager
 * @subpackage Students
 */
class WS_Register_Students_Controller
{
	/**
	 * Instance of this class.
	 *
	 * @since 1.0
	 * @var object
	 */
	protected static $instance = null;	

	/**
	 * Adds needed actions to create submenu and page
	 *
	 * @since 1.0
	 * @return void
	 */
	public function __construct()
	{
		add_action( 'edit_user_profile', array( &$this, 'proxy_page_profile' ) );
		add_action( 'show_user_profile', array( &$this, 'proxy_page_profile' ) );
		add_action( 'edit_user_profile_update', array( &$this, 'proxy_profile' ) );
		add_action( 'personal_options_update', array( &$this, 'proxy_profile' ) );
		add_action( 'edit_user_profile_students', array( &$this, 'edit_page_profile' ) );
		add_action( 'save_user_profile_students', array( &$this, 'save_page_profile' ) );
		add_action( 'after_setup_theme', array( &$this, 'define_image_sizes' ) );
	}
	
	public function save_page_profile( $user_id )
	{
		$code_enrollment = WS_Utils_Helper::post_method_params( WS_Register_Student::USER_META_CODE_ENROLLMENT, false );
		$period          = WS_Utils_Helper::post_method_params( WS_Register_Student::USER_META_PERIOD, false );
		$course          = WS_Utils_Helper::post_method_params( WS_Register_Student::USER_META_COURSE, false );
		$avatar          = WS_Utils_Helper::post_method_params( WS_Register_Student::USER_META_AVATAR, false );		
		
		update_user_meta( $user_id, WS_Register_Student::USER_META_CODE_ENROLLMENT, intval( $code_enrollment ) );
		update_user_meta( $user_id, WS_Register_Student::USER_META_PERIOD, intval( $period ) );
		update_user_meta( $user_id, WS_Register_Student::USER_META_COURSE, $course );

		if ( $avatar )
			update_user_meta( $user_id, WS_Register_Student::USER_META_AVATAR, $avatar );
	}

	public function proxy_page_profile( $user )
	{
		if ( $user->has_cap( WS_Register_Student::ROLE ) )
			do_action( 'edit_user_profile_students', $user );
	}

	public function edit_page_profile( $user )
	{
		WS_Register_Students_View::render_profile( $user->ID );
	}

	public function proxy_profile( $user_id )
	{
		if ( user_can( $user_id, WS_Register_Student::ROLE ) )
			do_action( 'save_user_profile_students', $user_id );
	}

	public function define_image_sizes()
	{
		//controller image
		$controller_image = WS_Images_Library::get_instance();

		$controller_image->define(
			'attachment',
			array(
				WS_Register_Student::IMAGE_SIZE_AVATAR_SMALL => array( 150, 150, true ),
			)
		);
	}

	public static function add_capabilities_course()
	{
		$capability_type = WS_Register_Course::POST_TYPE;

		$caps = array(
			"edit_{$capability_type}",
			"read_{$capability_type}",
			"delete_{$capability_type}",
			"edit_{$capability_type}s",
			"publish_{$capability_type}s",
			'read',
			'upload_files',
		);

		WS_Utils_Helper::add_custom_capabilities( WS_Register_Student::ROLE, $caps );
	}

	public static function create_role()
	{
		add_role( WS_Register_Student::ROLE, 'Alunos' );

		//avaliable capabilities
		self::add_capabilities_course();
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