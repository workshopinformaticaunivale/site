<?php
/**
 * Views Event
 *
 * @package WS Register
 * @subpackage Event Views
 * @version 1.0
 */
class WS_Register_Event_View
{
	public static function render_link_control( $post )
	{
		$model = new WS_Register_Event( $post->ID );

		?>
			<p>
				<input id="ws-field-link" type="text" class="large-text" placeholder="http://"
				       name="ws-metas[<?php echo esc_attr( WS_Register_Event::POST_META_LINK ); ?>]"
				       value="<?php echo esc_url( $model->link ); ?>">

			</p>
		<?php

		wp_nonce_field(
			WS_Register_Event_Controller::NONCE_LINK_ACTION,
			WS_Register_Event_Controller::NONCE_LINK_NAME
		);
	}

	public static function render_date_initial_control( $post )
	{
		$model = new WS_Register_Event( $post->ID );

		?>
			<p>
				<input id="ws-field-date-initial" type="date"
				       name="ws-metas[<?php echo esc_attr( WS_Register_Event::POST_META_DATE_INITIAL ); ?>]"
				       value="<?php echo esc_attr( $model->date_initial ); ?>">

			</p>
		<?php

		wp_nonce_field(
			WS_Register_Event_Controller::NONCE_LINK_ACTION,
			WS_Register_Event_Controller::NONCE_LINK_NAME
		);
	}

	public static function render_date_final_control( $post )
	{
		$model = new WS_Register_Event( $post->ID );

		?>
			<p>
				<input id="ws-field-date-final" type="date"
				       name="ws-metas[<?php echo esc_attr( WS_Register_Event::POST_META_DATE_FINAL ); ?>]"
				       value="<?php echo esc_attr( $model->date_final ); ?>">

			</p>
		<?php

		wp_nonce_field(
			WS_Register_Event_Controller::NONCE_LINK_ACTION,
			WS_Register_Event_Controller::NONCE_LINK_NAME
		);
	}


	public static function render_edition_control( $post )
	{
		$model = new WS_Register_Event( $post->ID );

		?>
			<p>
				<input id="ws-field-edition" type="input"
				       name="ws-metas[<?php echo esc_attr( WS_Register_Event::POST_META_EDITION ); ?>]"
				       value="<?php echo esc_attr( $model->edition ); ?>">

			</p>
		<?php

		wp_nonce_field(
			WS_Register_Event_Controller::NONCE_LINK_ACTION,
			WS_Register_Event_Controller::NONCE_LINK_NAME
		);
	}


	public static function render_email_courses_control( $post )
	{
		$model = new WS_Register_Event( $post->ID );

		?>
			<p>
				<input id="ws-field-email-course" type="text" class="large-text"
				       name="ws-metas[<?php echo esc_attr( WS_Register_Event::POST_META_EMAIL_COURSE ); ?>]"
				       value="<?php echo esc_attr( $model->email_course ); ?>">

			</p>
		<?php

		wp_nonce_field(
			WS_Register_Event_Controller::NONCE_LINK_ACTION,
			WS_Register_Event_Controller::NONCE_LINK_NAME
		);
	}

	public static function render_email_requeriments_control( $post )
	{
		$model = new WS_Register_Event( $post->ID );

		?>
			<p>
				<input id="ws-field-email-requirements" type="text" class="large-text"
				       name="ws-metas[<?php echo esc_attr( WS_Register_Event::POST_META_EMAIL_REQUIREMENTS ); ?>]"
				       value="<?php echo esc_attr( $model->email_requirements ); ?>">

			</p>
		<?php

		wp_nonce_field(
			WS_Register_Event_Controller::NONCE_LINK_ACTION,
			WS_Register_Event_Controller::NONCE_LINK_NAME
		);
	}	

}
