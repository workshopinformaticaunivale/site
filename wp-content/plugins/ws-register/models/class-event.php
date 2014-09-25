<?php
/**
 * Featured Model
 *
 * @package WS Register
 * @subpackage Event
 */
class WS_Register_Event
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
	 * Event date_initial
	 *
	 * @since 1.0
	 * @var string
	 */
	private $date_initial;	

	/**
	 * Event date_final
	 *
	 * @since 1.0
	 * @var string
	 */
	private $date_final;		

	/**
	 * Event edition
	 *
	 * @since 1.0
	 * @var string
	 */
	private $edition;	

	/**
	 * Event email_course
	 *
	 * @since 1.0
	 * @var string
	 */
	private $email_course;	

	/**
	 * Event email_requirements
	 *
	 * @since 1.0
	 * @var string
	 */
	private $email_requirements;	

	/**
	 * Post Type name
	 *
	 * @since 1.0
	 * @var string
	 */
	const POST_TYPE = 'ws-event';

	/**
	 * Image size large
	 *
	 * @since 1.0
	 * @var string
	 */
	const IMAGE_SIZE_LARGE = 'ws-event-image-size-large';

	/**
	 * Post Metas
	 *
	 * @since 1.0
	 * @var string
	 */
	const POST_META_DATE_INITIAL       = 'ws-event-date-initial';
	const POST_META_DATE_FINAL         = 'ws-event-date-final';
	const POST_META_EDITION            = 'ws-event-edition';	
	const POST_META_EMAIL_COURSE       = 'ws-event-email-course';	
	const POST_META_EMAIL_REQUIREMENTS = 'ws-event-email-requirements';


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

	public function get_format_date_initial()
	{
		return $this->_get_format_date( 'date_initial' );
	}

	public function get_format_date_final()
	{
		return $this->_get_format_date( 'date_final' );
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

			case 'date_final' :
				if ( ! isset( $this->date_final ) ) :
					$this->date_final = get_post_meta( $this->ID, self::POST_META_DATE_FINAL, true );
				endif;
				break;

			case 'date_initial' :
				if ( ! isset( $this->date_initial ) ) :
					$this->date_initial = get_post_meta( $this->ID, self::POST_META_DATE_INITIAL, true );
				endif;
				break;				

			case 'edition' :
				if ( ! isset( $this->edition ) ) :
					$this->edition = get_post_meta( $this->ID, self::POST_META_EDITION, true );
				endif;
				break;

			case 'email_course' :
				if ( ! isset( $this->email_course ) ) :
					$this->email_course = get_post_meta( $this->ID, self::POST_META_EMAIL_COURSE, true );
				endif;
				break;

			case 'email_requirements' :
				if ( ! isset( $this->email_requirements ) ) :
					$this->email_requirements = get_post_meta( $this->ID, self::POST_META_EMAIL_REQUIREMENTS, true );
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

	private function _get_format_date( $property )
	{
		$date = strtotime( $this->_get_property( $property ) );

		if ( empty( $date ) )
			return false;

		return date_i18n( 'd/m/Y H:i', $date );
	}
}
