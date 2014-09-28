<?php

class WP_Theme_Contact_Form
{
	public function process_send_register( $contact_form )
	{
		if ( ! is_object( $contact_form ) )
			return;

		if ( strpos( $contact_form->prop( 'additional_settings' ), 'create_user:"true"' ) === false )
			return;

		if ( ! class_exists( 'Resuta_Manager_Enterprise_Controller' ) )
			return;

		$submission = WPCF7_Submission::get_instance();

		if ( ! $submission )
			return;

		$controller  = Resuta_Manager_Enterprise_Controller::get_instance();
		$posted_data = $submission->get_posted_data();

		//mount array posted
		$args = array(
			'name'  => $posted_data['your-name'],
			'email' => $posted_data['your-email'],
			'type'  => $this->_get_type_register( $posted_data ),
		);

		$controller->create_user( $args );
	}

	private function _get_type_register( $posted_data )
	{
		$type = $posted_data['radio-701'];

		return ( isset( $type ) && is_array( $type ) ) ? $type[0] : false;
	}
}
