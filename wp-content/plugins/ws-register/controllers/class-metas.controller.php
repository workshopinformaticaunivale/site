<?php
/**
 * Controller Metas
 *
 * @package WS Plugin Template Manager
 * @subpackage Metas
 * @since 1.0
 */
class WS_Plugin_Template_Metas_Controller
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
		add_action( 'save_post', array( &$this, 'save_post_meta' ), 11, 2 );
	}

	public function save_post_meta( $post_id, $post )
	{
		$this->_proxy_save( $post_id, $post );
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

	private function _proxy_save( $post_id, $post )
	{
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			return;

		if ( in_array( $post->post_status, array( 'auto-draft', 'revision', 'trash' ) ) )
			return;

		if ( wp_is_post_revision( $post_id ) )
			return;

		if ( ! apply_filters( 'ws_metas_' . $post->post_type . '_is_valid_save_post', false, $post_id ) )
			return;

		$this->_save( $post_id, $post );
	}

	private function _save( $post_id, $post )
	{
		$elements = WS_Plugin_Template_Utils_Helper::post_method_params( 'ws-metas', false );

		if ( ! $elements )
			return;

		do_action( 'ws_metas_' . $post->post_type . '_before_save_metas', $elements, $post_id );

		foreach ( $elements as $key => $value ) :
			update_post_meta( $post_id, $key, $this->_filter_value( $value, $key, $post->post_type ) );
		endforeach;

		do_action( 'ws_metas_' . $post->post_type . '_after_save_metas', $elements, $post_id );
	}

	private function _filter_value( $value, $key, $post_type )
	{
		return esc_html( apply_filters( 'ws_metas_' . $post_type . '_save_value', $value, $key ) );
	}
}
