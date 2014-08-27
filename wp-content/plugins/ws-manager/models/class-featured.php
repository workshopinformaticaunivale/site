<?php
/**
 * Featured Model
 *
 * @package WS Plugin Template Manager
 * @subpackage Featured
 */
class WS_Manager_Featured
{
	/**
	 * Featured ID
	 *
	 * @since 1.0
	 * @var string
	 */
	private $ID;

	/**
	 * Featured Title
	 *
	 * @since 1.0
	 * @var string
	 */
	private $title;

	/**
	 * Featured thumbnail
	 *
	 * @since 1.0
	 * @var string
	 */
	private $thumbnail_url;

	/**
	 * Featured excerpt
	 *
	 * @since 1.0
	 * @var string
	 */
	private $excerpt;

	/**
	 * Featured link
	 *
	 * @since 1.0
	 * @var string
	 */
	private $link;

	/**
	 * Post Type name
	 *
	 * @since 1.0
	 * @var string
	 */
	const POST_TYPE = 'ws-featured';

	/**
	 * Image size large
	 *
	 * @since 1.0
	 * @var string
	 */
	const IMAGE_SIZE_LARGE = 'ws-featured-image-size-large';

	/**
	 * Post Metas
	 *
	 * @since 1.0
	 * @var string
	 */
	const POST_META_LINK = 'ws-featured-link';

	/**
     * Constructor of the class. Instantiate and incializate it.
     *
     * @since 1.0.0
     *
     * @param int $ID - The ID of the Customer
     * @return null
     */
	public function __construct( $ID = false )
	{
		if ( false != $ID ) :
			$this->ID = $ID;
		endif;
	}

	/**
	 * Magic function to retrieve the value of the attribute more easily.
	 *
	 * @since 1.0
	 * @param string $prop_name The attribute name
	 * @return mixed The attribute value
	 */
	public function __get( $prop_name )
	{
		return $this->_get_property( $prop_name );
	}

	/**
	 * Use in __get() magic method to retrieve the value of the attribute
	 * on demand. If the attribute is unset get his value before.
	 *
	 * @since 1.0
	 * @param string $prop_name The attribute name
	 * @return mixed The value of the attribute
	 */
	private function _get_property( $prop_name )
	{
		switch ( $prop_name ) {
			case 'title' :
				if ( ! isset( $this->title ) ) :
					$this->title = get_post_field( 'post_title', $this->ID );
				endif;
				break;

			case 'thumbnail_url' :
				if ( ! isset( $this->thumbnail_url ) ) :
					$this->thumbnail_url = $this->_get_thumbnail_url();
				endif;
				break;

			case 'excerpt' :
				if ( ! isset( $this->excerpt ) ) :
					$this->excerpt = get_post_field( 'post_excerpt', $this->ID );
				endif;
				break;

			case 'link' :
				if ( ! isset( $this->link ) ) :
					$this->link = get_post_meta( $this->ID, self::POST_META_LINK, true );
				endif;
				break;
		}

		return $this->$prop_name;
	}

	private function _get_thumbnail_url()
	{
		$thumbnail_id = get_post_thumbnail_id( $this->ID );
		$attachment   = wp_get_attachment_image_src( $thumbnail_id, self::IMAGE_SIZE_LARGE );

		if ( ! $attachment )
			return false;

		return $attachment[0];
	}
}
