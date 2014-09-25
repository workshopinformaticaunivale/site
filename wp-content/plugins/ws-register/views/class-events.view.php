<?php
/**
 * Views Events
 *
 * @package WS Register
 * @subpackage Events Views
 * @version 1.0
 */
class WS_Register_Events_View
{
	public static function render_date_control( $post )
	{
		$model = new WS_Register_Event( $post->ID );

		?>
		<p>
			<label for="ws-field-date-initial">Data Inicial</label>
			<input type="text" class="large-text datepicker" id="ws-field-date-initial" data-mask="00/00/0000"
			       name="ws-metas[<?php echo esc_attr( WS_Register_Event::POST_META_DATE_INITIAL ); ?>]" 
			       value="<?php echo esc_attr( $model->get_format_date_initial() ); ?>">
		</p>
		<p>
			<label for="ws-field-date-final">Data Final</label>
			<input type="text" class="large-text datepicker" id="ws-field-date-final" data-mask="00/00/0000"
			       name="ws-metas[<?php echo esc_attr( WS_Register_Event::POST_META_DATE_FINAL ); ?>]" 
			       value="<?php echo esc_attr( $model->get_format_date_final() ); ?>">
		</p>
		<?php

		wp_nonce_field(
			WS_Register_Events_Controller::NONCE_DATE_ACTION,
			WS_Register_Events_Controller::NONCE_DATE_NAME
		);
	}

	public static function render_edition_control( $post )
	{
		$model = new WS_Register_Event( $post->ID );

		?>
		<p>
			<input type="number" class="large-text" id="ws-field-edition"
			       name="ws-metas[<?php echo esc_attr( WS_Register_Event::POST_META_EDITION ); ?>]" 
			       value="<?php echo intval( $model->edition ); ?>">
		</p>
		<?php

		wp_nonce_field(
			WS_Register_Events_Controller::NONCE_EDITION_ACTION,
			WS_Register_Events_Controller::NONCE_EDITION_NAME
		);
	}

	public static function render_emails_notifications( $post )
	{
		$model = new WS_Register_Event( $post->ID );

		?>
		<p>
			<label for="ws-field-email-courses">Email de Notificações de Minicursos</label>
			<input type="text" class="large-text" id="ws-field-email-courses"
			       name="ws-metas[<?php echo esc_attr( WS_Register_Event::POST_META_EMAIL_COURSE ); ?>]" 
			       value="<?php echo esc_attr( $model->email_course ); ?>">
		</p>
		<p>
			<label for="ws-field-email-requirements">Email de Notificações de Requisitos</label>
			<input type="text" class="large-text" id="ws-field-email-requirements"
			       name="ws-metas[<?php echo esc_attr( WS_Register_Event::POST_META_EMAIL_REQUIREMENTS ); ?>]" 
			       value="<?php echo esc_attr( $model->email_requirements ); ?>">
		</p>
		<?php

		wp_nonce_field(
			WS_Register_Events_Controller::NONCE_EMAILS_NOTIFICATIONS_ACTION,
			WS_Register_Events_Controller::NONCE_EMAILS_NOTIFICATIONS_NAME
		);
	}
}
