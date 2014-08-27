<?php
/**
 * Controller Images
 *
 * @package WS Plugin Template Manager
 * @subpackage Images Intermediate
 * @since 1.0
 */
class WS_Plugin_Template_Image_Controller
{
	/**
	 * Instance of this class.
	 *
	 * @since 1.0
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Custom Sizes
	 *
	 * @since 1.0
	 * @var array
	 */
	private $custom_sizes = array();

	/**
	 * Construct
	 *
	 * Assign hooks to class functions and initializes the plugin to run
	 *
	 * @since 0.1
	 * @return void
	 */
	public function __construct()
	{
		add_filter( 'intermediate_image_sizes', array( &$this, 'filter_image_sizes' ), 99 );
	}

	/**
	 * Filter image sizes
	 *
	 * Uses the hook intermediate_image_sizes to generate only necessaries sizes to an image upoaded.
	 *
	 * @since 0.1
	 * @param array $sizes An array with sizes registereds in WordPress
	 * @see add_filter( 'intermediate_image_sizes' )
	 * @global object The $post WP object with image post data
	 * @return array The image sizes only to CPT from the image parent
	 */
	public function filter_image_sizes( $sizes )
	{
		global $post;

		/**
		 * Se a imagem estiver sendo inserida seu identificador é enviado via $_POST
		 * Caso esteja sendo excluída, o id é recuperado pela variável global
		 */
		$post_type = 'attachment';

		if ( isset( $_POST['post_id'] ) ) :
			$post_type = get_post_type( $_POST['post_id'] );

		elseif ( isset( $post ) && isset( $post->post_parent ) && ( $post->post_parent > 0 ) ) :
			$post_type = get_post_type( $post->post_parent );
		elseif ( isset( $_REQUEST['id'] ) ) : // Regenerate Thumbnails fixes
			$id    = (int) $_REQUEST['id'];
			$image = get_post( $id );
			if ( ! empty( $image ) and 'attachment' == $image->post_type and
				'image/' == substr( $image->post_mime_type, 0, 6 ) and ! empty( $image->post_parent ) ) :
				$post_parent = get_post( $image->post_parent );
				$post_type   = $post_parent->post_type;
			endif;
		endif;

		$default_sizes = array( 'thumbnail', 'medium', 'large' );

		// Remove unused sizes. Implemented to compatibility with others functions
		// that run the same hook. The double foreach is because we can have
		// many cpts and for each cpt we can many image sizes.
		if ( ! isset( $this->custom_sizes[$post_type] ) ) :
			foreach ( $this->custom_sizes as $sizes_to_cpt ) :
				foreach ( $sizes_to_cpt as $size_name => $dimensions ) :
					$image_size_key = array_search( $size_name, $sizes );
					// array_search can return false and it can be confused with 0 in php
					if ( $image_size_key !== false )
						unset( $sizes[$image_size_key] );
				endforeach;
			endforeach;

			return $sizes;
		endif;

		$images_sizes = array_keys(  $this->custom_sizes[$post_type] );

		// Return default image sizes in built in post types
		if ( 'post' == $post_type or 'page' == $post_type or in_array( 'wp-default-sizes', $images_sizes ) )
			$images_sizes = array_merge( $images_sizes, $default_sizes );

		return $images_sizes;
	}

	/**
	 * Define Custom Images Sizes
	 *
	 * Use this function in theme to define what image sizes will be created for each cpt.
	 *
	 * @since 0.1
	 * @param string $cpt_name The name of the CPT
	 * @param array $arr_size_definitions An array, the key is the size name and the value
	 * 				is an array with the image dimensions and the crop information (xxx, xxx, false)
	 * @uses add_image_size
	 * @return void
	 */
	public function define( $cpt_name, $arr_size_definitions = array() )
	{
		foreach ( (array) $arr_size_definitions as $image_size_name => $size ) {
			$this->custom_sizes[ $cpt_name ][ $image_size_name ] = $size;
			add_image_size( $image_size_name, $size[0], $size[1], ( isset( $size[2] ) ) ? $size[2] : true );
		}
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

