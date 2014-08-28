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
		add_action( 'add_meta_boxes', array( &$this, 'define_metaboxes' ) );
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
			'ws-metabox-speaker-requirements',
			'Requisitos do curso',
			array( 'WS_Register_Courses_View', 'render_speaker_requirements_control' ),
			WS_Register_Course::POST_TYPE,
			'normal',
			'low'
		);

		add_meta_box(
			'ws-metabox-workload',
			'Carga Horária',
			array( 'WS_Register_Courses_View', 'render_workload_control' ),
			WS_Register_Course::POST_TYPE,
			'side',
			'low'
		);
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
}
