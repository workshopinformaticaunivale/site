<?php
/**
 * Speaker Model
 *
 * @package WS Register
 * @subpackage Speaker
 */
class WS_Register_Speaker
{
	/**
	 * Speakers ID
	 *
	 * @since 1.0
	 * @var string
	 */
	private $ID;

	/**
	 * Speakers Title
	 *
	 * @since 1.0
	 * @var string
	 */
	private $title;

	/**
	 * Speakers thumbnail
	 *
	 * @since 1.0
	 * @var string
	 */
	private $thumbnail_url;

	/**
	 * Speakers content
	 *
	 * @since 1.0
	 * @var string
	 */
	private $content;

	/**
	 * Speakers topic
	 *
	 * @since 1.0
	 * @var string
	 */
	private $topic;

	/**
	 * Speakers facebook
	 *
	 * @since 1.0
	 * @var string
	 */
	private $facebook;

	/**
	 * Speakers twitter
	 *
	 * @since 1.0
	 * @var string
	 */
	private $twitter;

	/**
	 * Speakers linkedin
	 *
	 * @since 1.0
	 * @var string
	 */
	private $linkedin;

	/**
	 * Speakers datetime speech
	 *
	 * @since 1.0
	 * @var datetime
	 */
	private $datetime_speech;

	/**
	 * Speakers event
	 *
	 * @since 1.0
	 * @var int
	 */
	private $event_id;

	/**
	 * Post Type name
	 *
	 * @since 1.0
	 * @var string
	 */
	const POST_TYPE = 'ws-speaker';

	/**
	 * Image size large
	 *
	 * @since 1.0
	 * @var string
	 */
	const IMAGE_SIZE_SMALL = 'ws-speaker-image-size-small';

	/**
	 * Post Metas
	 *
	 * @since 1.0
	 * @var string
	 */
	const POST_META_TOPIC           = 'ws-speaker-topic';
	const POST_META_FACEBOOK        = 'ws-speaker-facebook';
	const POST_META_TWITTER         = 'ws-speaker-twitter';
	const POST_META_LINKEDIN        = 'ws-speaker-linkedin';
	const POST_META_DATETIME_SPEECH = 'ws-speaker-datetime-speech';
	const POST_META_EVENT_ID        = 'ws-speaker-event-id';

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

	public function get_format_datetime_speech( $format = 'd/m/Y H:i' )
	{
		$datetime_speech = strtotime( $this->_get_property( 'datetime_speech' ) );

		if ( empty( $datetime_speech ) )
			return false;

		return date_i18n( $format, $datetime_speech );
	}

	public function set_event_id( $event_id )
	{
		update_post_meta( $this->ID, self::POST_META_EVENT_ID, intval( $event_id ) );
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

			case 'content' :
				if ( ! isset( $this->content ) ) :
					$this->content = apply_filters( 'get_the_content', get_post_field( 'post_content', $this->ID ) );
				endif;
				break;

			case 'topic' :
				if ( ! isset( $this->topic ) ) :
					$this->topic = get_post_meta( $this->ID, self::POST_META_TOPIC, true );
				endif;
				break;

			case 'facebook' :
				if ( ! isset( $this->facebook ) ) :
					$this->facebook = get_post_meta( $this->ID, self::POST_META_FACEBOOK, true );
				endif;
				break;

			case 'twitter' :
				if ( ! isset( $this->twitter ) ) :
					$this->twitter = get_post_meta( $this->ID, self::POST_META_TWITTER, true );
				endif;
				break;

			case 'linkedin' :
				if ( ! isset( $this->linkedin ) ) :
					$this->linkedin = get_post_meta( $this->ID, self::POST_META_LINKEDIN, true );
				endif;
				break;

			case 'datetime_speech' :
				if ( ! isset( $this->datetime_speech ) ) :
					$this->datetime_speech = get_post_meta( $this->ID, self::POST_META_DATETIME_SPEECH, true );
				endif;
				break;

			case 'event_id' :
				if ( ! isset( $this->event_id ) ) :
					$this->event_id = intval( get_post_meta( $this->ID, self::POST_META_EVENT_ID, true ) );
				endif;
				break;
		}

		return $this->$prop_name;
	}

	private function _get_thumbnail_url()
	{
		$thumbnail_id = get_post_thumbnail_id( $this->ID );
		$attachment   = wp_get_attachment_image_src( $thumbnail_id, self::IMAGE_SIZE_SMALL );

		if ( ! $attachment )
			return false;

		return $attachment[0];
	}
}
