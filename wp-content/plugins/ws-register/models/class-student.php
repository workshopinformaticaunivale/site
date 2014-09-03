<?php
/**
 * Student Model
 *
 * @package WS Plugin Template Manager
 * @subpackage Student
 */
class Resuta_Manager_Student
{
	/**
	 * Student ID
	 *
	 * @since 1.0
	 * @var string
	 */
	private $ID;

	/**
	 * Student Display Name
	 *
	 * @since 1.0
	 * @var string
	 */
	private $display_name;

	/**
	 * Student Description
	 *
	 * @since 1.0
	 * @var string
	 */
	private $description;

	/**
	 * Student Email
	 *
	 * @since 1.0
	 * @var string
	 */
	private $email;

	/**
	 * Student Avatar ID
	 *
	 * @since 1.0
	 * @var string
	 */
	private $avatar_id;

	/**
	 * Student Avatar ID
	 *
	 * @since 1.0
	 * @var string
	 */
	private $code_enrollment;

	/**
	 * Student Course
	 *
	 * @since 1.0
	 * @var string
	 */
	private $course;

	/**
	 * Student Period
	 *
	 * @since 1.0
	 * @var string
	 */
	private $period;

	/**
	 * Role Enterprises
	 *
	 * @since 1.0
	 * @var string
	 */
	const ROLE = 'ws-students';

	/**
	 * User Metas
	 *
	 * @since 1.0
	 * @var string
	 */
	const USER_META_CODE_ENROLLMENT = 'ws-students-code-enrollment';

	const USER_META_COURSE = 'ws-students-course';

	const USER_META_PERIOD = 'ws-students-period';	
	
	const USER_META_AVATAR = 'ws-students-avatar';	

	/**
	 * Image Size Avatar
	 *
	 * @since 1.0
	 * @var string
	 */
	const IMAGE_SIZE_AVATAR_SMALL = 'ws-students-avatar-small';

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

	public function get_avatar_url_small()
	{
		return $this->get_image( self::IMAGE_SIZE_AVATAR_SMALL );
	}

	public function get_image( $image_size, $default = '' )
	{
		$avatar_id  = $this->_get_property( 'avatar_id' );
		$attachment = wp_get_attachment_image_src( $avatar_id, $image_size );

		if ( $attachment )
			return $attachment[0];

		return $default;
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
				return $this->ID;

			case 'display_name' :
				if ( ! isset( $this->display_name ) ) :
					$this->display_name = get_the_author_meta( 'display_name', $this->ID );
				endif;
				break;

			case 'description' :
				if ( ! isset( $this->description ) ) :
					$this->description = get_the_author_meta( 'description', $this->ID );
				endif;
				break;

			case 'email' :
				if ( ! isset( $this->email ) ) :
					$this->email = get_the_author_meta( 'user_email', $this->ID );
				endif;
				break;

			case 'code_enrollment' :
				if ( ! isset( $this->code_enrollment ) ) :
					$this->code_enrollment = get_the_author_meta( self::USER_META_CODE_ENROLLMENT, $this->ID );
				endif;
				break;

			case 'course' :
				if ( ! isset( $this->course ) ) :
					$this->course = get_the_author_meta( self::USER_META_COURSE, $this->ID );
				endif;
				break;

			case 'period' :
				if ( ! isset( $this->period ) ) :
					$this->period = get_the_author_meta( self::USER_META_PERIOD, $this->ID );
				endif;
				break;

			case 'avatar_id' :
				if ( ! isset( $this->avatar_id ) ) :
					$this->avatar_id = get_the_author_meta( self::USER_META_AVATAR, $this->ID );
				endif;
				break;

			default :
				return false;
				break;
		}

		return $this->$prop_name;
	}
}
