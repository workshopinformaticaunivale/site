<?php
/**
 * Course Model
 *
 * @package WS Plugin Template Manager
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
	const POST_META_WORKLOAD             = 'wp-course-workload';

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
		}

		return $this->$prop_name;
	}
}
