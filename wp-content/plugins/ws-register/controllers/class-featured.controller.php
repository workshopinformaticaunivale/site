<?php
/**
 * Controller Featured
 *
 * @package WS Plugin Template Manager
 * @subpackage Featured
 * @since 1.0
 */
class WS_Register_Featured_Controller
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
	const NONCE_LINK_ACTION = '_ws_featured_link_action';

	/**
	 * Nonce Name
	 *
	 * @since 1.0
	 * @var object
	 */
	const NONCE_LINK_NAME = '_ws_featured_link_name';

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
		add_filter( 'ws_metas_' . WS_Register_Featured::POST_TYPE . '_is_valid_save_post', array( &$this, 'nonce_valid_save_post' ) );
		add_filter( 'manage_' . WS_Register_Featured::POST_TYPE . '_posts_columns', array( &$this, 'post_columns_head' ) );
		add_action( 'manage_' . WS_Register_Featured::POST_TYPE . '_posts_custom_column', array( &$this, 'post_columns_content' ), 10, 2 );
	}

	public function post_columns_head( $headers )
	{
		$headers['link'] = 'Link';

		return $headers;
	}

	public function post_columns_content( $column_name, $post_id )
	{
		if ( ! in_array( $column_name, array( 'link' ) ) )
			return;

		//instance $model this if use more columns
		$model = new WS_Register_Featured( $post_id );

		if ( property_exists( $model, $column_name ) ) :
			echo esc_html( $model->$column_name );
			return;
		endif;

		if ( method_exists( $model, $column_name ) ) :
			echo esc_html( $model->$column_name() );
			return;
		endif;

		unset($model);
	}

	public function define_metaboxes()
	{
		add_meta_box(
			'ws-metabox-featured-link',
			'Link',
			array( 'WS_Register_Featured_View', 'render_link_control' ),
			WS_Register_Featured::POST_TYPE,
			'normal',
			'low'
		);
	}

	public function get_list( $args = array() )
	{
		$defaults = array(
			'post_type' => WS_Register_Featured::POST_TYPE,
			'order'		=> 'ASC',
			'orderby'	=> 'menu_order',
		);

		$args = wp_parse_args( $args, $defaults );

		return $this->_parse_list( WS_Register_Utils_Helper::get_query( $args ) );
	}

	public function register_post_type()
	{
		register_post_type(
			WS_Register_Featured::POST_TYPE,
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
				'capability_type'   => WS_Register_Featured::POST_TYPE,
			)
		);
	}

	public function nonce_valid_save_post( $is_valid )
	{
		$link_nonce = WS_Register_Utils_Helper::post_method_params( self::NONCE_LINK_NAME, false );

		if ( ! $link_nonce || ! wp_verify_nonce( $link_nonce, self::NONCE_LINK_ACTION ) )
			return false;

		return true;
	}

	public function define_image_sizes()
	{
		//controller image
		$controller_image = WS_Register_Image_Controller::get_instance();

		$controller_image->define(
			WS_Register_Featured::POST_TYPE,
			array(
				WS_Register_Featured::IMAGE_SIZE_LARGE => array( 978, 260, true ),
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
		$capability_type = WS_Register_Featured::POST_TYPE;

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

		WS_Register_Utils_Helper::add_custom_capabilities( 'administrator', $caps );
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
			$list[] = new WS_Register_Featured( $featured->ID );

		return $list;
	}
}
