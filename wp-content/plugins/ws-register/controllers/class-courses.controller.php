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
	 * Nonce Action
	 *
	 * @since 1.0
	 * @var object
	 */
	const NONCE_SPEAKER_REQUIREMENTS_ACTION = '_ws_courses_speaker_requirements_action';

	/**
	 * Nonce Name
	 *
	 * @since 1.0
	 * @var object
	 */
	const NONCE_SPEAKER_REQUIREMENTS_NAME = '_ws_courses_speaker_requirements_name';

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
	}

	public function nonce_valid_save_post( $is_valid )
	{
		$requirements_nonce = WS_Utils_Helper::post_method_params( self::NONCE_SPEAKER_REQUIREMENTS_NAME, false );

		if ( ! $requirements_nonce || ! wp_verify_nonce( $requirements_nonce, self::NONCE_SPEAKER_REQUIREMENTS_ACTION ) )
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
