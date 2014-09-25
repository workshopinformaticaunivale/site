<?php
/**
 * Controller Speakers
 *
 * @package WS Register
 * @subpackage Speakers
 * @since 1.0
 */
class WS_Register_Speakers_Controller
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
	const NONCE_SPEAKER_SOCIAL_ACTION 	       = '_ws_speakers_social_action';
	const NONCE_SPEAKER_DATETIME_SPEECH_ACTION = '_ws_speakers_datetime_speech_action';
	const NONCE_SPEAKER_TOPIC_ACTION 		   = '_ws_speakers_topic_action';

	/**
	 * Nonce Names
	 *
	 * @since 1.0
	 * @var object
	 */
	const NONCE_SPEAKER_SOCIAL_NAME 	     = '_ws_speakers_social_name';
	const NONCE_SPEAKER_DATETIME_SPEECH_NAME = '_ws_speakers_datetime_speech_name';
	const NONCE_SPEAKER_TOPIC_NAME 		     = '_ws_speakers_topic_name';

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
		add_filter( 'ws_metas_' . WS_Register_Speaker::POST_TYPE . '_is_valid_save_post', array( &$this, 'nonce_valid_save_post' ) );
		add_filter( 'ws_metas_' . WS_Register_Speaker::POST_TYPE . '_save_value', array( &$this, 'format_save_post' ), 10, 2 );
	}

	public function define_metaboxes()
	{
		add_meta_box(
			'ws-metabox-speakers-topic',
			'Assunto ou Tema',
			array( 'WS_Register_Speakers_View', 'render_topic_control' ),
			WS_Register_Speaker::POST_TYPE,
			'normal',
			'high'
		);

		add_meta_box(
			'ws-metabox-speakers-social',
			'Informações Adicionais',
			array( 'WS_Register_Speakers_View', 'render_social_control' ),
			WS_Register_Speaker::POST_TYPE,
			'normal',
			'high'
		);

		add_meta_box(
			'ws-metabox-speakers-datetime-speech',
			'Data da Palestra',
			array( 'WS_Register_Speakers_View', 'render_datetime_speech_control' ),
			WS_Register_Speaker::POST_TYPE,
			'side',
			'low'
		);		
	}

	public function register_post_type()
	{
		register_post_type(
			WS_Register_Speaker::POST_TYPE,
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
				'capability_type'   => WS_Register_Speaker::POST_TYPE,
			)
		);
	}

	public function define_image_sizes()
	{
		//controller image
		$controller_image = WS_Images_Library::get_instance();

		$controller_image->define(
			WS_Register_Speaker::POST_TYPE,
			array(
				WS_Register_Speaker::IMAGE_SIZE_SMALL => array( 100, 100, true ),
			)
		);
	}

	public function nonce_valid_save_post( $is_valid )
	{
		$social          = WS_Utils_Helper::post_method_params( self::NONCE_SPEAKER_SOCIAL_NAME, false );
		$topic           = WS_Utils_Helper::post_method_params( self::NONCE_SPEAKER_TOPIC_NAME, false );
		$datetime_speech = WS_Utils_Helper::post_method_params( self::NONCE_SPEAKER_DATETIME_SPEECH_NAME, false );		

		if ( ! $social || ! wp_verify_nonce( $social, self::NONCE_SPEAKER_SOCIAL_ACTION ) )
			return false;

		if ( ! $topic || ! wp_verify_nonce( $topic, self::NONCE_SPEAKER_TOPIC_ACTION ) )
			return false;

		if ( ! $datetime_speech || ! wp_verify_nonce( $datetime_speech, self::NONCE_SPEAKER_DATETIME_SPEECH_ACTION ) )
			return false;

		return true;
	}

	public function format_save_post( $value, $key )
	{
		if ( $key == WS_Register_Speaker::POST_META_DATETIME_SPEECH )
			return WS_Utils_Helper::convert_date_for_sql( $value );

		return $value;
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

		$messages[ WS_Register_Speaker::POST_TYPE ] = array(
			0  => '',
			1  => 'Palestra atualizada.',
			2  => 'Campo personalizado atualizado.',
			3  => 'Campo personalizado atualizado.',
			4  => 'Palestra atualizada.',
			5  => isset( $_GET['revision'] ) ? sprintf( 'Palestra restaurada para revisão às %s.', wp_post_revision_title( (int)$_GET['revision'], false ) ) : false,
			6  => 'Palestra criada.',
			7  => 'Palestra salva.',
			8  => 'Palestra submetida.',
			9  => sprintf( 'Palestra agendada para: <strong>%1$s</strong>.', date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
			10 => 'Rascunho do minicurso atualizada.',
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
		$capability_type = WS_Register_Speaker::POST_TYPE;

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
