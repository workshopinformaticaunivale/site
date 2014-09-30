<?php
/**
 * Controller Partners
 *
 * @package WS Register
 * @subpackage Partners
 * @since 1.0
 */
class WS_Register_Partners_Controller
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
	const NONCE_SITE_ACTION = '_ws_partners_site_action';

	/**
	 * Nonce Name
	 *
	 * @since 1.0
	 * @var object
	 */
	const NONCE_SITE_NAME = '_ws_partners_site_name';

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
		add_filter( 'post_updated_messages', array( &$this, 'set_post_updated_messages' ) );
		add_filter( 'ws_metas_' . WS_Register_Partner::POST_TYPE . '_is_valid_save_post', array( &$this, 'nonce_valid_save_post' ) );
	}

	public function define_metaboxes()
	{
		add_meta_box(
			'ws-metabox-partners-site',
			'Site',
			array( 'WS_Register_Partners_View', 'render_site_control' ),
			WS_Register_Partner::POST_TYPE,
			'normal',
			'high'
		);
	}

	public function get_list( $args = array() )
	{
		$defaults = array(
			'post_type' => WS_Register_Partner::POST_TYPE,
			'order'		=> 'ASC',
			'orderby'	=> 'menu_order',
		);

		$args = wp_parse_args( $args, $defaults );

		return $this->_parse_list( WS_Utils_Helper::get_query( $args ) );
	}

	public function register_post_type()
	{
		register_post_type(
			WS_Register_Partner::POST_TYPE,
			array(
				'labels' => array(
					'name'               => 'Parceiros',
					'singular_name'      => 'Parceiro',
					'all_items'          => 'Todos os parceiros',
					'add_new'            => 'Adicionar novo',
					'add_new_item'       => 'Adicionar novo parceiro',
					'edit_item'          => 'Editar parceiro',
					'new_item'           => 'Novo parceiro',
					'view_item'          => 'Visualizar parceiro',
					'search_items'       => 'Pesquisar parceiros',
					'not_found'          => 'Nenhum parceiro encontrado',
					'not_found_in_trash' => 'Nenhum parceiro encontrado na lixeira',
				),
				'public'        	=> false,
				'show_ui'			=> true,
				'menu_position' 	=> 5,
				'supports'      	=> array( 'title', 'thumbnail', 'page-attributes' ),
				'menu_icon'			=> 'dashicons-groups',
				'capability_type'   => WS_Register_Partner::POST_TYPE,
			)
		);
	}

	public function nonce_valid_save_post( $is_valid )
	{
		$site_nonce = WS_Utils_Helper::post_method_params( self::NONCE_SITE_NAME, false );

		if ( ! $site_nonce || ! wp_verify_nonce( $site_nonce, self::NONCE_SITE_ACTION ) )
			return false;

		return true;
	}

	public function define_image_sizes()
	{
		//controller image
		$controller_image = WS_Images_Library::get_instance();

		$controller_image->define(
			WS_Register_Partner::POST_TYPE,
			array(
				WS_Register_Partner::IMAGE_SIZE_SMALL => array( 150, 150, true ),
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

		$messages[ WS_Register_Partner::POST_TYPE ] = array(
			0  => '',
			1  => 'Parceiro atualizado.',
			2  => 'Campo personalizado atualizado.',
			3  => 'Campo personalizado atualizado.',
			4  => 'Parceiro atualizado.',
			5  => isset( $_GET['revision'] ) ? sprintf( 'Parceiro restaurado para revisão às %s.', wp_post_revision_title( (int)$_GET['revision'], false ) ) : false,
			6  => 'Parceiro criado.',
			7  => 'Parceiro salvo.',
			8  => 'Parceiro submetido.',
			9  => sprintf( 'Parceiro agendado para: <strong>%1$s</strong>.', date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
			10 => 'Rascunho do parceiro atualizada.',
		);

		return $messages;
	}

	/**
	 * Create custom capabilities for this post type.
	 *
	 * @return void
	 */
	public static function add_post_type_capabilities()
	{
		$capability_type = WS_Register_Partner::POST_TYPE;

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
		WS_Utils_Helper::add_custom_capabilities( WS_Register_Moderators_Controller::ROLE, $caps );
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
			$list[] = new WS_Register_Partner( $featured->ID );

		return $list;
	}
}
