<?php
/**
 * Controller Courses
 *
 * @package WS Plugin Template Register
 * @subpackage Courses
 * @since 1.0
 */
class WS_Register_Courses_Controller
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
	const NONCE_SPEAKER_REQUIREMENTS_ACTION = '_ws_courses_speaker_requirements_action';
	const NONCE_WORKLOAD_ACTION             = '_ws_courses_workload_action';

	/**
	 * Nonce Names
	 *
	 * @since 1.0
	 * @var object
	 */
	const NONCE_SPEAKER_REQUIREMENTS_NAME = '_ws_courses_speaker_requirements_name';
	const NONCE_WORKLOAD_NAME             = '_ws_courses_workload_name';

	/**
	 * Adds needed actions to create submenu and page
	 *
	 * @since 1.0
	 * @return void
	 */
	public function __construct()
	{
		add_action( 'init', array( &$this, 'register_post_type' ) );
		add_action( 'init', array( &$this, 'register_taxonomies' ) );
		add_action( 'admin_menu', array( &$this, 'remove_taxonomy_metabox' ) );
		add_action( 'add_meta_boxes', array( &$this, 'define_metaboxes' ) );
		add_action( 'save_post_' . WS_Register_Course::POST_TYPE, array( &$this, 'save_date' ), 11, 2 );
		add_filter( 'ws_metas_' . WS_Register_Course::POST_TYPE . '_is_valid_save_post', array( &$this, 'nonce_valid_save_post' ) );
		add_filter( 'post_updated_messages', array( &$this, 'set_post_updated_messages' ) );
	}

	public function get_courses( $args = array() )
	{
		$defaults = array(
			'post_type' => WS_Register_Course::POST_TYPE,
			'order'		=> 'ASC',
			'orderby'	=> 'menu_order',
		);

		$args = wp_parse_args( $args, $defaults );

		return $this->_parse_list( WS_Utils_Helper::get_query( $args ) );
	}

	public function register_post_type()
	{
		register_post_type(
			WS_Register_Course::POST_TYPE,
			array(
				'labels' => array(
					'name'               => 'Minicursos',
					'singular_name'      => 'Minicurso',
					'all_items'          => 'Todos os minicursos',
					'add_new'            => 'Adicionar novo',
					'add_new_item'       => 'Adicionar novo minicurso',
					'edit_item'          => 'Editar minicurso',
					'new_item'           => 'Novo minicurso',
					'view_item'          => 'Visualizar minicurso',
					'search_items'       => 'Pesquisar minicursos',
					'not_found'          => 'Nenhum minicurso encontrado',
					'not_found_in_trash' => 'Nenhum minicurso encontrado na lixeira',
				),
				'public'        	=> false,
				'show_ui'			=> true,
				'menu_position' 	=> 5,
				'supports'      	=> array( 'title', 'editor' ),
				'menu_icon'			=> 'dashicons-welcome-learn-more',
				'capability_type'   => WS_Register_Course::POST_TYPE,
			)
		);
	}

	/**
	 * Register all taxonomies for this class
	 *
	 * @return void
	 */
	public function register_taxonomies()
	{
		register_taxonomy(
			WS_Register_Course::TAXONOMY_LABORATORY,
			WS_Register_Course::POST_TYPE,
			$args = array(
				'labels' => array(
					'name'              => 'Laboratórios',
					'singular_name'     => 'Laboratório',
					'search_items'      => 'Pesquisar laboratório',
					'popular_items'     => 'Laboratórios populares',
					'all_items'         => 'Todos os laboratórios',
					'edit_item'         => 'Editar laboratórios',
					'update_item'       => 'Atualizar laboratórios',
					'add_new_item'      => 'Adicionar novo laboratório',
					'new_item_name'     => 'Nome do novo laboratório',
					'menu_name'         => 'Laboratórios',
				),
				'public'            => false,
				'hierarchical'      => true,
				'show_ui'           => true,
			)
		);
	}

	/**
	 * Remove taxonomy metabox
	 *
	 * @return void
	 * @since 1.0
	 */
	public function remove_taxonomy_metabox()
	{
		remove_meta_box( WS_Register_Course::TAXONOMY_LABORATORY .'div', WS_Register_Course::POST_TYPE, 'side' );
	}

	/**
	 * Sets the template updated message
	 *
	 * @since 1.0
	 * @param array $messages Messages
	 * @return array Messages
	 */
	public function set_post_updated_messages( $messages )
	{
		global $post;

		$messages[ WS_Register_Course::POST_TYPE ] = array(
			0  => '',
			1  => 'Minicurso atualizado.',
			2  => 'Campo personalizado atualizado.',
			3  => 'Campo personalizado atualizado.',
			4  => 'Minicurso atualizado.',
			5  => isset( $_GET['revision'] ) ? sprintf( 'Minicurso restaurado para revisão às %s.', wp_post_revision_title( (int)$_GET['revision'], false ) ) : false,
			6  => 'Minicurso criado.',
			7  => 'Minicurso salvo.',
			8  => 'Minicurso submetido.',
			9  => sprintf( 'Minicurso agendado para: <strong>%1$s</strong>.', date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
			10 => 'Rascunho do minicurso atualizado.',
		);

		return $messages;
	}

	public function define_metaboxes()
	{
		add_meta_box(
			'ws-course-metabox-speaker-requirements',
			'Requisitos do curso',
			array( 'WS_Register_Courses_View', 'render_speaker_requirements_control' ),
			WS_Register_Course::POST_TYPE,
			'normal',
			'low'
		);

		add_meta_box(
			'ws-course-metabox-workload',
			'Carga Horária',
			array( 'WS_Register_Courses_View', 'render_workload_control' ),
			WS_Register_Course::POST_TYPE,
			'side',
			'low'
		);

		add_meta_box(
			'ws-course-metabox-date',
			'Data e horário',
			array( 'WS_Register_Courses_View', 'render_date_control' ),
			WS_Register_Course::POST_TYPE,
			'normal',
			'low'
		);

		add_meta_box(
			'ws-course-metabox-laboratory',
			'Laboratórios',
			array( 'WS_Register_Courses_View', 'render_laboratory_control' ),
			WS_Register_Course::POST_TYPE,
			'side',
			'low'
		);
	}

	/**
	 * Saves dates
	 *
	 * @since 1.0
	 * @param int $post_id
	 * @param object $post
	 * @return void
	 */
	public function save_date( $post_id, $post )
	{
		if ( ! $this->_is_valid_save( $post ) )
			return;

		$this->_save_date( $post_id );
	}

	/**
	 * Iterates date
	 *
	 * @since 1,0
	 * @param int $post_id
	 * @return void
	 */
	public function iterate_date( $post_id )
	{
		$course         = new WS_Register_Course( $post_id );
		$datetime_count = $course->datetime_count;

		if ( empty( $datetime_count ) )
			WS_Register_Courses_View::render_date_item_control();

		for ( $index = 1; $index <= $datetime_count;  $index++ ) :
			$date_start = $course->get_datetime_start( $index, 'd/m/Y H:i' );
			$date_end   = $course->get_datetime_end( $index, 'H:i' );

			WS_Register_Courses_View::render_date_item_control( $date_start, $date_end );
		endfor;
	}

	/**
	 * Sorts dates
	 *
	 * @since 1.0
	 * @param type $a
	 * @param type $b
	 * @return type
	 */
	public function sort_dates( $a, $b )
	{
		$a = strtotime( str_replace( '/', '-', $a ) );
		$b = strtotime( str_replace( '/', '-', $b ) );

		if ( $a >= $b )
			return 1;

		return 0;
	}

	public function nonce_valid_save_post( $is_valid )
	{
		$requirements_nonce = WS_Utils_Helper::post_method_params( self::NONCE_SPEAKER_REQUIREMENTS_NAME, false );
		$workload_nonce     = WS_Utils_Helper::post_method_params( self::NONCE_WORKLOAD_NAME, false );

		if ( ! $requirements_nonce || ! wp_verify_nonce( $requirements_nonce, self::NONCE_SPEAKER_REQUIREMENTS_ACTION ) )
			return false;

		if ( ! $workload_nonce || ! wp_verify_nonce( $workload_nonce, self::NONCE_WORKLOAD_ACTION ) )
			return false;

		return true;
	}

	/**
	 * Create custom capabilities for this post type.
	 *
	 * @return void
	 */
	public static function add_post_type_capabilities()
	{
		$capability_type = WS_Register_Course::POST_TYPE;

		$caps = array(
			"edit_{$capability_type}",
			"read_{$capability_type}",
			"delete_{$capability_type}",
			"edit_{$capability_type}s",
			"edit_others_{$capability_type}s",
			"publish_{$capability_type}s",
			"read_private_{$capability_type}s",
			"delete_{$capability_type}s",
			"delete_private_{$capability_type}s",
			"delete_published_{$capability_type}s",
			"delete_others_{$capability_type}s",
			"edit_private_{$capability_type}s",
			"edit_published_{$capability_type}s",
		);

		WS_Utils_Helper::add_custom_capabilities( 'administrator', $caps );
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

	/**
	 * Save post meta date
	 *
	 * @since 1.0
	 * @return void
	 */
	private function _save_date( $post_id )
	{
		$dates_start = WS_Utils_Helper::post_method_params( WS_Register_Course::POST_META_DATETIME_START, false );
		$dates_end   = WS_Utils_Helper::post_method_params( WS_Register_Course::POST_META_DATETIME_END, false );
		$index       = 0;

		if ( ! $dates_start || ! $dates_end )
			return;

		//remove all dates after update
		$this->_remove_date( $post_id );

		//sort date
		uasort( $dates_start, array( &$this, 'sort_dates' ) );

		foreach ( $dates_start as $key => $date ) :

			if ( empty( $date ) )
				continue;

			$this->_save_post_meta_date(
				$post_id,
				$date,
				$dates_end[ $key ],
				++$index
			);

		endforeach;

		$this->_update_datetime_count( $post_id, $index );
	}

	/**
	 * remove date all index
	 *
	 * @since 1.0
	 * @return void
	 */
	private function _remove_date( $post_id )
	{
		$course         = new WS_Register_Course( $post_id );
		$datetime_count = $course->datetime_count;

		if ( empty( $datetime_count ) )
			return;

		for ( $index = 1; $index <= $datetime_count; $index++ ) :
			$course->remove_datetime_start( $index );
			$course->remove_datetime_end( $index );
		endfor;
	}

	/**
	 * update date in format
	 *
	 * @since 1.0
	 * @return void
	 */
	private function _update_datetime_count( $post_id, $index )
	{
		update_post_meta( $post_id, WS_Register_Course::POST_META_DATETIME_COUNT, $index );
	}

	/**
	 * Save Date Post Meta
	 *
	 * @since 1.0
	 * @return void
	 */
	private function _save_post_meta_date( $post_id, $date_start, $date_end, $index )
	{
		$date_save_end   = WS_Utils_Helper::convert_date_for_sql( $this->_get_date_of_closing( $date_end, $date_start ) );
		$date_save_start = WS_Utils_Helper::convert_date_for_sql( $date_start );

		update_post_meta( $post_id, WS_Register_Course::POST_META_DATETIME_START . '_' . $index, $date_save_start );
		update_post_meta( $post_id, WS_Register_Course::POST_META_DATETIME_END   . '_' . $index, $date_save_end );
	}

	/**
	 * Valid save post meta date
	 *
	 * @since 1.0
	 * @return void
	 */
	private function _is_valid_save( $post )
	{
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return false;

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			return false;

		if ( in_array( $post->post_status, array( 'auto-draft', 'revision', 'trash' ) ) )
			return false;

		if ( WS_Register_Course::POST_TYPE != $post->post_type )
			return false;

		if ( wp_is_post_revision( $post->ID ) )
			return false;

		return true;
	}

	private function _get_date_of_closing( $date_end, $date_start )
	{
		if ( empty( $date_end ) )
			return;

		if ( strlen( $date_end ) <= 5 )
			return substr( $date_start, 0, 10 ) . ' ' . $date_end;

		return str_replace( substr( $date_end, 0, 10 ), substr( $date_start, 0, 10 ), $date_end );
	}
}
