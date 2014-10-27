<?php
/**
 * Emails Controller
 *
 * @package WS Register
 * @subpackage Emails
 */
class WS_Register_Emails_Controller
{
	/**
	 * Instance of this class.
	 *
	 * @since 1.0
	 * @var object
	 */
	protected static $instance = null;

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

	/**
	 * Adds needed actions after plugin in enabled
	 *
	 * @since 1.0
	 * @return void
	 */
	public function __construct()
	{
		add_action( 'ws_create_new_user_student', array( &$this, 'send_email_register_student' ), 10, 2 );
		add_action( 'ws_renovation_register_courses', array( &$this, 'send_renovation_register_courses' ) );
	}

	public function send_email_register_student( $user_id, $args )
	{
		$model         = new WS_Register_Student( $user_id );
		$email_default = self::get_default_email();

		$subject = 'Cadastro | Dados de Acesso';
		$headers = "from: Workshop de Infomática <{$email_default}>\ncontent-type: text/html; charset=UTF-8";
		$message = WS_Register_Emails_View::render_email_password( $model, $args['user_pass'] );

		wp_mail( $model->email, $subject, $message, $headers );
	}

	public function send_renovation_register_courses( WS_Register_Student $model )
	{
		$email_default = self::get_default_email();

		$subject = 'Renovação | Inscrição em Minicurso';
		$headers = "from: Workshop de Infomática <{$email_default}>\ncontent-type: text/html; charset=UTF-8";
		$message = WS_Register_Emails_View::render_renovation_register_courses( $model );

		//wp_mail( $model->email, $subject, $message, $headers );
		wp_mail( 'accacio@apiki.com', $subject, $message, $headers );
	}

	public static function get_default_email()
	{
		return get_option( 'admin_email' );
	}
}
