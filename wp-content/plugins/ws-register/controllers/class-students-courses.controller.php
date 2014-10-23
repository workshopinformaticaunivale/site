<?php
/**
 * Controller Students Courses
 *
 * @package WS Register
 * @subpackage Students Courses
 * @since 1.0
 */
class WS_Register_Students_Courses_Controller
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
		add_action( 'admin_menu', array( &$this, 'menu' ) );
		add_action( 'proxy_ajax_set_courses_by_user/POST', array( &$this, 'ajax_set_courses_by_user' ) );
	}

	public function ajax_set_courses_by_user()
	{
		$course_id = WS_Utils_Helper::post_method_params( 'course_id', false, 'intval' );

		if ( ! $course_id ) :
			http_response_code( 500 );
			WS_Utils_Helper::error_server_json( 'course_id_is_empty', 'O minicurso não foi definido, por favor tente novamente.' );
			exit(0);
		endif;

		$model = new WS_Register_Course( $course_id );

		if ( ! $model->is_publish() ) :
			http_response_code( 500 );
			WS_Utils_Helper::error_server_json( 'course_id_not_publish', 'Esse minicurso não foi publicado.' );
			exit(0);
		endif;

		if ( ! $this->can_schedule_course( $model ) ) :
			http_response_code( 500 );
			WS_Utils_Helper::error_server_json( 'schedule_course', 'Você já possui um minicurso para esse dia, por favor confira a sua agenda.' );
			exit(0);
		endif;

		$model->set_user_participant( get_current_user_id() );

		WS_Utils_Helper::object_server_json(
			array(
				'message' => 'Sua inscrição foi realizada com sucesso!',
				'course'  => $course_id,
			)
		);
		exit(1);
	}

	public function can_schedule_course( WS_Register_Course $model )
	{
		$controller = WS_Register_Courses_Controller::get_instance();
		$list       = $controller->get_list_by_user();
		$days       = $model->get_dates_days();

		if ( ! $list )
			return true;

		foreach ( $list as $course ) :
			if ( array_intersect( $days, $course->get_dates_days() ) )
				return false;
		endforeach;

		return true;
	}

	public function menu()
	{
		add_submenu_page(
			'edit.php?post_type='. WS_Register_Course::POST_TYPE,
			'Meus Minicursos',
			'Meus Minicursos',
			'read_' . WS_Register_Course::POST_TYPE,
			self::get_slug(),
			array( 'WS_Register_Courses_View', 'render_register_students' )
		);
	}

	public function get_list()
	{
		$controller = WS_Register_Courses_Controller::get_instance();

		$args = array(
			'author__not_in' => array( get_current_user_id() ),
		);

		$list = $controller->get_list_current_event( $args );
		$list = array_filter( $list, array( &$this, 'filter_list_for_register' ) );

		return array_map( array( &$this, 'map_list_for_register' ), $list );		
	}

	public function filter_list_for_register( $model )
	{
		if ( in_array( get_current_user_id(), $model->users_participants ) )
			return false;

		return true;
	}

	public function map_list_for_register( $model )
	{
		$item = array(
			'value'      => $model->ID,
			'text'       => $model->title,
			'workload'   => $model->workload,
			'laboratory' => $model->get_laboratory_name(),
			'dates'      => $model->get_dates(),
			'excerpt'    => $model->excerpt,
			'author'     => $model->author->display_name,
		);

		return (object)$item;
	}

	public static function get_slug()
	{
		return 'students-courses';
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
