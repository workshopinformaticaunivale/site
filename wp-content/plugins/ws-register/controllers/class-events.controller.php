<?php
/**
 * Controller Events
 *
 * @package WS Register
 * @subpackage Events
 * @since 1.0
 */
class WS_Register_Events_Controller
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
	 * @var string
	 */
	const NONCE_DATE_ACTION    				= '_ws_event_date_action';
	const NONCE_EDITION_ACTION 				= '_ws_event_edition_action';
	const NONCE_EMAILS_NOTIFICATIONS_ACTION = '_ws_event_emails_notifications_action';

	/**
	 * Nonce Name
	 *
	 * @since 1.0
	 * @var string
	 */
	const NONCE_DATE_NAME                 = '_ws_event_date_name';
	const NONCE_EDITION_NAME              = '_ws_event_edition_name';
	const NONCE_EMAILS_NOTIFICATIONS_NAME = '_ws_event_emails_notifications_name';

	/**
	 * Transient Key
	 *
	 * @since 1.0
	 * @var string
	 */
	const TRANSIENT_EVENT_ID = '_ws_current_event_id';

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
		add_filter( 'ws_metas_' . WS_Register_Event::POST_TYPE . '_is_valid_save_post', array( &$this, 'nonce_valid_save_post' ) );
		add_filter( 'ws_metas_' . WS_Register_Event::POST_TYPE . '_save_value', array( &$this, 'format_save_post' ), 10, 2 );
		add_action( 'save_post', array( &$this, 'set_default_event_in_posts' ), 10, 2 );
	}

	public function set_default_event_in_posts( $post_id, $post )
	{
		if ( ! WS_Utils_Helper::is_valid_save_post( $post ) )
			return;

		do_action( "ws_set_default_event_{$post->post_type}", $post, self::get_current_event() );
	}

	public function define_metaboxes()
	{
		add_meta_box(
			'ws-metabox-events-date',
			'Data',
			array( 'WS_Register_Events_View', 'render_date_control' ),
			WS_Register_Event::POST_TYPE,
			'side',
			'low'
		);		

		add_meta_box(
			'ws-metabox-events-edition',
			'Edição',
			array( 'WS_Register_Events_View', 'render_edition_control' ),
			WS_Register_Event::POST_TYPE,
			'side',
			'low'
		);

		add_meta_box(
			'ws-metabox-events-emails-notifications',
			'Emails de Notificações',
			array( 'WS_Register_Events_View', 'render_emails_notifications' ),
			WS_Register_Event::POST_TYPE,
			'normal',
			'low'
		);
	}

	public function get_list( $args = array() )
	{
		$defaults = array(
			'post_type' => WS_Register_Event::POST_TYPE,
		);

		$args = wp_parse_args( $args, $defaults );

		return $this->_parse_list( WS_Utils_Helper::get_query( $args ) );
	}

	public function register_post_type()
	{
		register_post_type(
			WS_Register_Event::POST_TYPE,
			array(
				'labels' => array(
					'name'               => 'Eventos',
					'singular_name'      => 'Evento',
					'all_items'          => 'Todos os eventos',
					'add_new'            => 'Adicionar novo',
					'add_new_item'       => 'Adicionar novo evento',
					'edit_item'          => 'Editar evento',
					'new_item'           => 'Novo evento',
					'view_item'          => 'Visualizar evento',
					'search_items'       => 'Pesquisar eventos',
					'not_found'          => 'Nenhum evento encontrado',
					'not_found_in_trash' => 'Nenhum evento encontrado na lixeira',
				),
				'public'        	=> false,
				'show_ui'			=> true,
				'menu_position' 	=> 5,
				'supports'      	=> array( 'title', 'excerpt' ),
				'menu_icon'			=> 'dashicons-images-alt2',
				'capability_type'   => WS_Register_Event::POST_TYPE,
			)
		);
	}

	public function nonce_valid_save_post( $is_valid )
	{
		$date    = WS_Utils_Helper::post_method_params( self::NONCE_DATE_NAME, false );
		$emails  = WS_Utils_Helper::post_method_params( self::NONCE_EMAILS_NOTIFICATIONS_NAME, false );
		$edition = WS_Utils_Helper::post_method_params( self::NONCE_EDITION_NAME, false );
		
		if ( ! $date || ! wp_verify_nonce( $date, self::NONCE_DATE_ACTION ) )
			return false;

		if ( ! $emails || ! wp_verify_nonce( $emails, self::NONCE_EMAILS_NOTIFICATIONS_ACTION ) )
			return false;

		if ( ! $edition || ! wp_verify_nonce( $edition, self::NONCE_EDITION_ACTION ) )
			return false;

		return true;
	}

	public function format_save_post( $value, $key )
	{
		if ( $key == WS_Register_Event::POST_META_DATE_INITIAL )
			return WS_Utils_Helper::convert_date_for_sql( $value );

		if ( $key == WS_Register_Event::POST_META_DATE_FINAL )
			return WS_Utils_Helper::convert_date_for_sql( $value );

		return $value;
	}

	public static function get_current_event()
	{
		$event_id = intval( get_transient( self::TRANSIENT_EVENT_ID ) );

		if ( ! $event_id )
			$event_id = self::get_current_event_id();
		
		set_transient( self::TRANSIENT_EVENT_ID, $event_id, 12 * HOUR_IN_SECONDS );

		return new WS_Register_Event( $event_id );
	}

	public static function get_current_event_id()
	{
		$args = array(
			'post_type'      => WS_Register_Event::POST_TYPE,
			'meta_key'       => WS_Register_Event::POST_META_EDITION,
			'order'          => 'DESC',
			'orderby'        => 'meta_value_num',
			'posts_per_page' => 1,
		);

		$query = WS_Utils_Helper::get_query( $args );

		if ( ! $query->have_posts() )
			return false;

		return $query->post->ID;
	}

	/**
	 * Create custom capabilities for this post type.
	 *
	 * @return void
	 */
	public static function add_post_type_capabilities()
	{
		$capability_type = WS_Register_Event::POST_TYPE;

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
			$list[] = new WS_Register_Event( $featured->ID );

		return $list;
	}
}
