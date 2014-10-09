<?php
/**
 * Controller Students
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
	 * Nonce Actions
	 *
	 * @since 1.0
	 * @var object
	 */
	const NONCE_STUDENT_REGISTER = '_ws_students_register';

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
		add_action( 'restrict_manage_users', array( &$this, 'add_export_excel_button' ) );
		add_action( 'ws_admin_head_page_users', array( &$this, 'addicional_page_css' ) );
		add_action( 'pre_get_users', array( &$this, 'custom_query_code_enrollment' ) );
		add_filter( 'user_row_actions', array( &$this, 'custom_user_row_actions' ), 10 ,2 );
		add_action( 'wp_ajax_set_new_user', array( &$this, 'set_new_user_json' ) );
		add_action( 'wp_ajax_nopriv_set_new_user', array( &$this, 'set_new_user_json' ) );
		add_action( 'admin_init', array( &$this, 'export_to_excel' ) );
	}

	public function set_new_user_json()
	{
		if ( ! WS_Utils_Helper::is_request_ajax() )
			return;

		$display_name    = WS_Utils_Helper::post_method_params( 'display_name', false );
		$email           = WS_Utils_Helper::post_method_params( 'email', false, 'sanitize_email' );
		$nonce           = WS_Utils_Helper::post_method_params( '_wpnonce', false );
		$code_enrollment = WS_Utils_Helper::post_method_params( WS_Register_Student::USER_META_CODE_ENROLLMENT, false, 'intval' );
		$period          = WS_Utils_Helper::post_method_params( WS_Register_Student::USER_META_PERIOD, false, 'intval' );
		$course          = WS_Utils_Helper::post_method_params( WS_Register_Student::USER_META_COURSE, false );

		if ( ! $nonce || ! wp_verify_nonce( $nonce, self::NONCE_STUDENT_REGISTER ) ) :
			http_response_code( 500 );
			WS_Utils_Helper::error_server_json( 'not_permission_nonce', 'Sem permissões criar seu usuário no momento.' );
			exit(0);
		endif;

		if ( ! $display_name || ! $email || ! $code_enrollment || ! $period || ! $course ) :
			http_response_code( 500 );
			WS_Utils_Helper::error_server_json( 'empty_any_fields', 'Todos os campos são necessários.' );
			exit(0);
		endif;		

		$this->create_user_json( $display_name, $email, $code_enrollment, $period, $course );
	}

	public function create_user_json( $display_name, $email, $code_enrollment, $period, $course )
	{
		$user 				   = new WS_Register_Student();
		$user->display_name    = $display_name;
		$user->email           = $email;
		$user->code_enrollment = $code_enrollment;
		$user->period          = $period;
		$user->course          = $course;
		$inserted              = $user->insert();

		if ( is_wp_error( $inserted ) ) :
			http_response_code( 500 );
			WS_Utils_Helper::error_server_json( 'error_inserted', $inserted->get_error_message() );
			exit(0);	
		endif;

		WS_Utils_Helper::success_server_json( 'success_inserted', 'Usuário inserido com sucesso' );
		exit(1);
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

	public function export_to_excel()
	{
		if ( ! isset( $_GET['ws-export'] ) || $_GET['ws-export'] != true )
			return;

		$html = WS_Register_Students_View::render_excel_table();

		if ( empty( $html ) )
			return;

		header( 'Content-type: application/vnd.ms-excel;' );
		header( 'Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, charset=utf-8;' );
		header( 'Content-Disposition: attachment; filename=export-'. date_i18n( 'Y-m-d' ) . '.xls' );

		print $html;

		exit();
	}

	public function add_export_excel_button()
	{
		if ( $this->_is_filter_role_valid() )
			printf( '<a href="%s" style="margin-top:2px; margin-left:5px" class="button-primary">Exportar para .xls</a>', add_query_arg( array( 'ws-export' => 'true' ) ) );
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
		add_role( WS_Register_Student::ROLE, 'Aluno' );

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