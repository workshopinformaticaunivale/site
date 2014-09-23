<?php
/**
 * Students Controller
 *
 * @package WS Register
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
		add_filter( 'manage_users_columns', array( &$this, 'add_custom_columns' ) );
		add_filter( 'ws_manage_users_column_' . WS_Register_Student::USER_META_CODE_ENROLLMENT, array( &$this, 'users_column_code_enrollment' ) );
		add_action( 'restrict_manage_users', array( &$this, 'add_filter_code_enrollment' ) );
		add_action( 'ws_admin_head_page_users', array( &$this, 'addicional_page_css' ) );
		add_action( 'pre_get_users', array( &$this, 'custom_query_code_enrollment' ) );
		add_filter( 'user_row_actions', array( &$this, 'custom_user_row_actions' ), 10 ,2 );
	}
	
	public function save_page_profile( $user_id )
	{
		$code_enrollment = WS_Utils_Helper::post_method_params( WS_Register_Student::USER_META_CODE_ENROLLMENT, false, 'intval' );
		$period          = WS_Utils_Helper::post_method_params( WS_Register_Student::USER_META_PERIOD, false, 'intval' );
		$course          = WS_Utils_Helper::post_method_params( WS_Register_Student::USER_META_COURSE, false );
		$avatar          = WS_Utils_Helper::post_method_params( WS_Register_Student::USER_META_AVATAR, false );		
		
		update_user_meta( $user_id, WS_Register_Student::USER_META_CODE_ENROLLMENT, $code_enrollment );
		update_user_meta( $user_id, WS_Register_Student::USER_META_PERIOD, $period );
		update_user_meta( $user_id, WS_Register_Student::USER_META_COURSE, $course );

		if ( $avatar )
			update_user_meta( $user_id, WS_Register_Student::USER_META_AVATAR, $avatar );
	}

	public function add_custom_columns( $columns )
	{
		$columns[ WS_Register_Student::USER_META_CODE_ENROLLMENT ] = 'Nº Matrícula';

		return $columns;
	}

	public function users_column_code_enrollment( $user_id )
	{
		$model = new WS_Register_Student( $user_id );

		return ( (bool)$model->code_enrollment ) ? $model->code_enrollment : '__';
	}

	public function add_filter_code_enrollment()
	{
		if ( $this->_is_filter_role_valid() )
			WS_Register_Students_View::render_filter_code_enrollment();
	}

	public function addicional_page_css()
	{
		if ( ! $this->_is_filter_role_valid() )
			return;

		?>
		<style>
			#new_role,
			#changeit {
				display: none;
			}
		</style>
		<?php
	}

	public function custom_query_code_enrollment( $query )
	{
		if ( ! is_admin() || ! $this->_is_filter_role_valid() )
			return;		

		$code_enrollment = WS_Utils_Helper::get_method_params( WS_Register_Student::USER_META_CODE_ENROLLMENT, false, 'intval' );
		
		if ( ! $code_enrollment )
			return;

		$query->query_vars['meta_key']   = WS_Register_Student::USER_META_CODE_ENROLLMENT;
		$query->query_vars['meta_value'] = $code_enrollment;
	}

	public function custom_user_row_actions( $actions, $user )
	{
		$actions[ 'show_events' ] = sprintf(
			'<a href="javascript:void(0);" data-component-events-manager-user data-attr-user="%s">%s</a>',
			$user->ID,
			'Eventos'
		);

		return $actions;
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

	private function _is_filter_role_valid()
	{
		$role_filter = WS_Utils_Helper::get_method_params( 'role' );

		return ( $role_filter == WS_Register_Student::ROLE );
	}
}