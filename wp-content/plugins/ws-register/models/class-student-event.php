<?php
/**
 * Student Event Model
 *
 * @package WS Register
 * @subpackage Student Event
 */
class WS_Register_Student_Event
{
	/**
	 * ID
	 *
	 * @since 1.0
	 * @var string
	 */
	private $ID;

	/**
	 * Student ID
	 *
	 * @since 1.0
	 * @var int
	 */
	private $student_id;

	/**
	 * Student
	 *
	 * @since 1.0
	 * @var class WS_Register_Student
	 */
	private $student;

	/**
	 * Event ID
	 *
	 * @since 1.0
	 * @var int
	 */
	private $event_id;

	/**
	 * Event
	 *
	 * @since 1.0
	 * @var class WS_Register_Event
	 */
	private $event;

	/**
	 * Is Payment
	 *
	 * @since 1.0
	 * @var bool
	 */
	private $is_payment;

	/**
	 * Is Print Certificate
	 *
	 * @since 1.0
	 * @var bool
	 */
	private $is_print_certificate;

	/**
	 * Registered
	 *
	 * @since 1.0
	 * @var datetime
	 */
	private $registered;

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
			$this->_initialize();
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
	 * Magic function to set the value of the attribute more easily.
	 *
	 * @since 1.0
	 * @param string $prop_name The attribute name
	 * @param string $value Value in attribute
	 * @return mixed The attribute value
	 */
	public function __set( $prop_name, $value )
	{
		$this->$prop_name = $value;
	}	

	public function insert()
	{
		global $wpdb;

		$formats = array( '%d', '%d', '%d', '%d' );
		$columns = array(
			'student_id'           => intval( $this->student_id ),
			'event_id'             => intval( $this->event_id ),
			'is_payment'           => (bool)$this->is_payment,
			'is_print_certificate' => (bool)$this->is_print_certificate,
		);

		$inserted = $wpdb->insert( self::get_table_name(), $columns, $formats );

		return ( $inserted ) ? $wpdb->insert_id : false;
	}

	public function delete()
	{
		global $wpdb;

		$where_format = array( '%d' );
		$where        = array(
			'student_event_id' => intval( $this->ID ),
		);

		//It returns the number of rows updated, or false on error.
		return $wpdb->delete( self::get_table_name(), $where, $where_format );
	}

	public function update()
	{
		global $wpdb;

		$formats = array( '%d', '%d', '%d', '%d' );
		$columns = array(
			'student_id'           => intval( $this->student_id ),
			'event_id'             => intval( $this->event_id ),
			'is_payment'           => (bool)$this->is_payment,
			'is_print_certificate' => (bool)$this->is_print_certificate,
		);

		$where_format = array( '%d' );
		$where        = array(
			'student_event_id' => intval( $this->ID ),
		);

		return $wpdb->update( self::get_table_name(), $columns, $where, $columns_format, $where_format );
	}

	public static function get_table_name()
	{
		global $wpdb;

		return ( $wpdb->prefix . 'ws_students_events' );
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

			case 'student' :
				if ( ! isset( $this->student ) )
					$this->student = new WS_Register_Student( $this->student_id );
				break;

			case 'event' :
				if ( ! isset( $this->event ) )
					$this->event = new WS_Register_Event( $this->event_id );
				break;
		}

		return $this->$prop_name;
	}

	private function _initialize()
	{
		$result = $this->_get_columns_in_table(
			array(
				'student_event_id',
				'student_id',
				'event_id',
				'is_payment',
				'is_print_certificate',
				'registered'
			)
		);

		if ( ! $result )
			return false;

		$this->student_event_id     = $result->student_event_id;
		$this->student_id           = $result->student_id;
		$this->event_id             = $result->event_id;
		$this->is_payment           = $result->is_payment;
		$this->is_print_certificate = $result->is_print_certificate;
		$this->registered           = $result->registered;
	}

	private function _get_columns_in_table( $columns = array() )
	{
		global $wpdb;

		$columns             = implode( ',', $columns );
		$student_event_table = self::get_table_name();

		$query = $wpdb->prepare(
			"SELECT $columns
			FROM $student_event_table
			WHERE student_event_id = %d",
			$this->ID
		);

		return $wpdb->get_row( $query );
	}
}
