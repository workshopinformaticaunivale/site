<?php
/**
 * Course Model
 *
 * @package WS Register
 * @subpackage Course
 */
class WS_Register_Course
{
	/**
	 * Course ID
	 *
	 * @since 1.0
	 * @var string
	 */
	private $ID;

	/**
	 * Course Title
	 *
	 * @since 1.0
	 * @var string
	 */
	private $title;

	/**
	 * Course Content
	 *
	 * @since 1.0
	 * @var string
	 */
	private $content;

	/**
	 * Course excerpt
	 *
	 * @since 1.0
	 * @var string
	 */
	private $excerpt;

	/**
	 * Course Speaker Requirements
	 *
	 * @since 1.0
	 * @var string
	 */
	private $speaker_requirements;

	/**
	 * Course Workload
	 *
	 * @since 1.0
	 * @var string
	 */
	private $workload;

	/**
	 * Course Datetime Count
	 *
	 * @since 1.0
	 * @var string
	 */
	private $datetime_count;

	/**
	 * Course Event
	 *
	 * @since 1.0
	 * @var int
	 */
	private $event_id;

	/**
	 * Users Participants
	 *
	 * @since 1.0
	 * @var array int
	 */
	private $users_participants;

	/**
	 * User Author
	 *
	 * @since 1.0
	 * @var WS_Register_Student
	 */
	private $author;

	/**
	 * Status
	 *
	 * @since 1.0
	 * @var string
	 */
	private $status;

	/**
	 * Post Type name
	 *
	 * @since 1.0
	 * @var string
	 */
	const POST_TYPE = 'ws-course';

	/**
	 * Post Metas
	 *
	 * @since 1.0
	 * @var string
	 */
	const POST_META_SPEAKER_REQUIREMENTS = 'ws-course-speaker-requirements';
	const POST_META_WORKLOAD             = 'ws-course-workload';
	const POST_META_DATETIME_START       = 'ws-course-datetime-start';
	const POST_META_DATETIME_END         = 'ws-course-datetime-end';
	const POST_META_DATETIME_COUNT       = 'ws-course-datetime-count';
	const POST_META_EVENT_ID             = 'ws-course-event-id';
	const POST_META_USERS_PARTICIPANTS   = 'ws-course-users-participants';

	/**
	 * Taxonomies
	 *
	 * @since 1.0
	 * @var string
	 */
	const TAXONOMY_LABORATORY = 'ws-course-taxonomy-laboratory';

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
	 * Function to post meta datetime start, index param 0, index init 1
	 *
	 * @since 1.0
	 * @param int $index index datetime start
	 * @return mixed The post meta value
	 */
	public function get_datetime_start( $index = 1, $format = '' )
	{
		$date = get_post_meta( $this->ID, self::POST_META_DATETIME_START . '_' . $index, true );

		return ( empty( $format ) ? $date : mysql2date( $format, $date ) );
	}

	/**
	 * Function to post meta datetime end, index param 0, index init 1
	 *
	 * @since 1.0
	 * @param int $index index datetime end
	 * @return mixed The post meta value
	 */
	public function get_datetime_end( $index = 1, $format = '' )
	{
		$date = get_post_meta( $this->ID, self::POST_META_DATETIME_END . '_' . $index, true );

		return ( empty( $format ) ? $date : mysql2date( $format, $date ) );
	}

	/**
	 * Function to remove post meta datetime end, index param 0, index init 1
	 *
	 * @since 1.0
	 * @param int $index index datetime end
	 * @return void
	 */
	public function remove_datetime_end( $index = 1 )
	{
		delete_post_meta( $this->ID, self::POST_META_DATETIME_END . '_' . $index, $this->get_datetime_end( $index ) );
	}

	/**
	 * Function to remove post meta datetime start, index param 0, index init 1
	 *
	 * @since 1.0
	 * @param int $index index datetime end
	 * @return void
	 */
	public function remove_datetime_start( $index = 1 )
	{
		delete_post_meta( $this->ID, self::POST_META_DATETIME_START . '_' . $index, $this->get_datetime_start( $index ) );
	}

	/**
	 * Gets dates
	 *
	 * @since 1.0
	 * @return array
	 */
	public function get_dates()
	{
		$datetime_count = $this->_get_property( 'datetime_count' );
		$dates_array    = array();

		if ( empty( $datetime_count ) )
			return $dates_array;

		for ( $index = 1; $index <= $datetime_count; $index++ ) :
			$single_date = $this->get_datetime_start( $index, 'd/m/Y H:i' ) . ' às ' . $this->get_datetime_end( $index, 'H:i' );
			array_push( $dates_array, $single_date );
		endfor;

		return $dates_array;
	}

	public function get_dates_days()
	{
		$dates = $this->get_dates();
		$list  = array();

		foreach ( $dates as $date ) :
			$list[] = substr( $date, 0, 2 );
		endforeach;
		
		return $list;
	}

	public function set_event_id( $event_id )
	{
		update_post_meta( $this->ID, self::POST_META_EVENT_ID, intval( $event_id ) );
	}

	public function get_laboratory_name()
	{
		return WS_Utils_Helper::get_term_field(
			$this->ID,
			self::TAXONOMY_LABORATORY,
			'name'
		);
	}

	public function is_publish()
	{
		$status = $this->_get_property( 'status' );

		return ( $status == 'publish' );
	}

	public function set_user_participant( $user_id )
	{
		$users_participants = $this->_get_property( 'users_participants' );

		if ( in_array( $user_id, $users_participants ) )
			return;

		update_post_meta( $this->ID, self::POST_META_USERS_PARTICIPANTS, $user_id );
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
			case 'ID' :
				$this->ID;
				break;
				
			case 'title' :
				if ( ! isset( $this->title ) ) :
					$this->title = get_post_field( 'post_title', $this->ID );
				endif;
				break;

			case 'content' :
				if ( ! isset( $this->content ) ) :
					$this->content = get_post_field( 'post_content', $this->ID );
				endif;
				break;

			case 'excerpt' :
				if ( ! isset( $this->excerpt ) ) :
					$this->excerpt = get_post_field( 'post_excerpt', $this->ID );
				endif;
				break;

			case 'speaker_requirements' :
				if ( ! isset( $this->speaker_requirements ) ) :
					$this->speaker_requirements = get_post_meta( $this->ID, self::POST_META_SPEAKER_REQUIREMENTS, true );
				endif;
				break;

			case 'workload' :
				if ( ! isset( $this->workload ) ) :
					$this->workload = get_post_meta( $this->ID, self::POST_META_WORKLOAD, true );
				endif;
				break;

			case 'datetime_count' :
				if ( ! isset( $this->datetime_count ) ):
					$this->datetime_count = intval( get_post_meta( $this->ID, self::POST_META_DATETIME_COUNT, true ) );
				endif;
				break;

			case 'event_id' :
				if ( ! isset( $this->event_id ) ) :
					$this->event_id = intval( get_post_meta( $this->ID, self::POST_META_EVENT_ID, true ) );
				endif;
				break;

			case 'users_participants' :
				if ( ! isset( $this->users_participants ) ) :
					$this->users_participants = get_post_meta( $this->ID, self::POST_META_USERS_PARTICIPANTS, false );
				endif;
				break;

			case 'status' :
				if ( ! isset( $this->status ) ) :
					$this->status = get_post_field( 'post_status', $this->ID );
				endif;
				break;

			case 'author' :
				if ( ! isset( $this->author ) ) :
					$this->author = new WS_Register_Student( get_post_field( 'post_author', $this->ID ) );
				endif;
				break;
		}

		return $this->$prop_name;
	}
}
