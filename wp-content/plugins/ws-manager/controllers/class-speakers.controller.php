<?php
/**
 * Controller Speakers
 *
 * @package WS Manager
 * @subpackage Speakers
 * @since 1.0
 */
class WS_Manager_Speakers_Controller
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
		add_action( 'init', array( &$this, 'register_post_type' ) );
		add_action( 'after_setup_theme', array( &$this, 'define_image_sizes' ) );
		add_action( 'add_meta_boxes', array( &$this, 'define_metaboxes' ) );
	}

	public function define_metaboxes()
	{
		// add_meta_box(
		// 	'ws-metabox-speakers-link',
		// 	'Link',
		// 	array( 'WS_Manager_Speakers_View', 'render_link_control' ),
		// 	WS_Manager_Speakers::POST_TYPE,
		// 	'normal',
		// 	'low'
		// );
	}

	public function register_post_type()
	{
		register_post_type(
			WS_Manager_Speakers::POST_TYPE,
			array(
				'labels' => array(
					'name'               => 'Palestrantes',
					'singular_name'      => 'Palestrante',
					'all_items'          => 'Todos os palestrantes',
					'add_new'            => 'Adicionar novo',
					'add_new_item'       => 'Adicionar novo palestrante',
					'edit_item'          => 'Editar palestrante',
					'new_item'           => 'Novo palestrante',
					'view_item'          => 'Visualizar palestrante',
					'search_items'       => 'Pesquisar palestrantes',
					'not_found'          => 'Nenhum palestrante encontrado',
					'not_found_in_trash' => 'Nenhum palestrante encontrado na lixeira',
				),
				'public'        	=> false,
				'show_ui'			=> true,
				'menu_position' 	=> 5,
				'supports'      	=> array( 'title', 'editor', 'thumbnail' ),
				'menu_icon'			=> 'dashicons-admin-users',
				'capability_type'   => WS_Manager_Speakers::POST_TYPE,
			)
		);
	}

	public function define_image_sizes()
	{
		//controller image
		$controller_image = WS_Images_Library::get_instance();

		$controller_image->define(
			WS_Manager_Speakers::POST_TYPE,
			array(
				WS_Manager_Speakers::IMAGE_SIZE_SMALL => array( 100, 100, true ),
			)
		);
	}

	/**
	 * Create custom capabilities for this post type.
	 *
	 * @return void
	 */
	public static function add_post_type_capabilities()
	{
		$capability_type = WS_Manager_Speakers::POST_TYPE;

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
