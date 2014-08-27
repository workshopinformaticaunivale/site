<?php
/**
 * Controller Courses
 *
 * @package WS Plugin Template Manager
 * @subpackage Courses
 * @since 1.0
 */
class WS_Manager_Courses_Controller
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
	const NONCE_LINK_ACTION = '_ws_courses_link_action';

	/**
	 * Nonce Name
	 *
	 * @since 1.0
	 * @var object
	 */
	const NONCE_LINK_NAME = '_ws_courses_link_name';

	/**
	 * Adds needed actions to create submenu and page
	 *
	 * @since 1.0
	 * @return void
	 */
	public function __construct()
	{
		add_action( 'init', array( &$this, 'register_post_type' ) );
		add_action( 'after_setup_theme', array( &$this, 'define_image_sizes' ) );
		add_action( 'add_meta_boxes', array( &$this, 'define_metaboxes' ) );
		add_filter( 'ws_metas_' . WS_Manager_Featured::POST_TYPE . '_is_valid_save_post', array( &$this, 'nonce_valid_save_post' ) );
	}

	public function get_list( $args = array() )
	{
		$defaults = array(
			'post_type' => WS_Manager_Course::POST_TYPE,
			'order'		=> 'ASC',
			'orderby'	=> 'menu_order',
		);

		$args = wp_parse_args( $args, $defaults );

		return $this->_parse_list( WS_Utils_Helper::get_query( $args ) );
	}

	public function register_post_type()
	{
		register_post_type(
			WS_Manager_Featured::POST_TYPE,
			array(
				'labels' => array(
					'name'               => 'Banners de Destaque',
					'singular_name'      => 'Banner',
					'all_items'          => 'Todos os banners',
					'add_new'            => 'Adicionar novo',
					'add_new_item'       => 'Adicionar novo banner',
					'edit_item'          => 'Editar banner',
					'new_item'           => 'Novo banner',
					'view_item'          => 'Visualizar banner',
					'search_items'       => 'Pesquisar banners',
					'not_found'          => 'Nenhum banner encontrado',
					'not_found_in_trash' => 'Nenhum banner encontrado na lixeira',
				),
				'public'        	=> false,
				'show_ui'			=> true,
				'menu_position' 	=> 5,
				'supports'      	=> array( 'title', 'excerpt', 'thumbnail', 'page-attributes' ),
				'menu_icon'			=> 'dashicons-images-alt2',
				'capability_type'   => WS_Manager_Featured::POST_TYPE,
			)
		);
	}

	public function nonce_valid_save_post( $is_valid )
	{
		$link_nonce = WS_Utils_Helper::post_method_params( self::NONCE_LINK_NAME, false );

		if ( ! $link_nonce || ! wp_verify_nonce( $link_nonce, self::NONCE_LINK_ACTION ) )
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
		$capability_type = WS_Manager_Course::POST_TYPE;

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

	private function _parse_list( $wp_query )
	{
		if ( ! $wp_query->have_posts() )
			return false;

		$list = array();

		foreach ( $wp_query->posts as $featured )
			$list[] = new WS_Manager_Course( $featured->ID );

		return $list;
	}
}
