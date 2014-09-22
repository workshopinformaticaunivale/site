<?php
/**
 * Controller Students Events
 *
 * @package WS Plugin Template Manager
 * @subpackage Students Events
 * @since 1.0
 */
class WS_Register_Students_Events_Controller
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
		// $inserted = WS_Utils_Helper::get_method_params( 'inserted_se', false );

		// if ( $inserted ) {
		// 	$model = new WS_Register_Student_Event();

		// 	$model->student_id = 2;
		// 	$model->event_id   = 11;

		// 	$model->insert();
		// }

		// $this->get_list(
		// 	array(
		// 		'where' => array(
		// 			'student_id' => 2					
		// 		)
		// 	)
		// );
		// exit;

		add_action( 'wp_ajax_get_events_by_user', array( &$this, 'get_events_by_user_json' ) );
	}

	public function get_events_by_user_json()
	{
		if ( ! WS_Utils_Helper::is_request_ajax() )
			return;
	}

	public function get_list( $args = array() )
	{
		$defaults = array(
			'order' 	=> 'DESC',
			'orderby'	=> 'registered',
			'where'     => array()
		);

		$args          = wp_parse_args( $args, $defaults );
		$args['where'] = WS_Utils_Helper::transform_columns_in_where( $args['where'] );

		return $this->_select_list( (object)$args );
	}

	/**
	 * Create all tables used by plugin.
	 *
	 * @global type $wpdb
	 */
	public static function create_table()
	{
		global $wpdb;

		$charset               = WS_Utils_Helper::get_charset();
		$students_events_table = WS_Register_Student_Event::get_table_name();		

		$create_table = "
			CREATE TABLE IF NOT EXISTS $students_events_table (
				student_event_id        INT(11)   UNSIGNED	NOT NULL	AUTO_INCREMENT			 ,
				student_id         		INT(11)   UNSIGNED	NOT NULL                  			 ,
				event_id                INT(11)   UNSIGNED	NOT NULL                  			 ,
				is_payment              BOOLEAN   		    NOT NULL    DEFAULT FALSE 			 ,
				is_print_certificate    BOOLEAN   	        NOT NULL    DEFAULT FALSE            ,
				registered              TIMESTAMP           NOT NULL    DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY ( student_event_id )
			) $charset ENGINE = MYISAM;";

		include_once ABSPATH . '/wp-admin/includes/upgrade.php';
		dbDelta( $create_table );
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

	private function _select_list( $args, $is_parse = true )
	{
		global $wpdb;
		
		$table_name = WS_Register_Student_Event::get_table_name();		

		$query =
			"SELECT student_event_id
			FROM $table_name
			WHERE 1=1
			AND $args->where
			ORDER BY $args->orderby $args->order";

		$results = $wpdb->get_results( $query );

		if ( ! $is_parse )
			return $results;

		return $this->_parse_list( $results );
	}

	private function _parse_list( $results )
	{
		if ( ! $results )
			return false;

		$list = array();

		foreach ( $results as $result )
			$list[] = new WS_Register_Student_Event( $result->student_event_id );

		return $list;
	}
}