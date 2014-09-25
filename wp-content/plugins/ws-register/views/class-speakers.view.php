<?php
/**
 * View Speakers
 *
 * @package WS Register
 * @subpackage Speakers View
 * @version 1.0
 */
class WS_Register_Speakers_View
{
	public static function render_social_control( $post )
	{
		$model = new WS_Register_Speaker( $post->ID );

		?>
		<p>
			<label for="ws-field-facebook">Facebook</label>
			<input type="text" class="large-text" id="ws-field-facebook"
			       name="ws-metas[<?php echo esc_attr( WS_Register_Speaker::POST_META_FACEBOOK ); ?>]" 
			       value="<?php echo esc_url( $model->facebook ); ?>">
		</p>
		<p>
			<label for="ws-field-twitter">Twitter</label>
			<input type="text" class="large-text" id="ws-field-twitter"
			       name="ws-metas[<?php echo esc_attr( WS_Register_Speaker::POST_META_TWITTER ); ?>]" 
			       value="<?php echo esc_url( $model->twitter ); ?>">
		</p>
		<p>
			<label for="ws-field-linkedin">Linkedin</label>
			<input type="text" class="large-text" id="ws-field-linkedin"
			       name="ws-metas[<?php echo esc_attr( WS_Register_Speaker::POST_META_LINKEDIN ); ?>]" 
			       value="<?php echo esc_url( $model->linkedin ); ?>">
		</p>
		<?php

		wp_nonce_field(
			WS_Register_Speakers_Controller::NONCE_SPEAKER_SOCIAL_ACTION,
			WS_Register_Speakers_Controller::NONCE_SPEAKER_SOCIAL_NAME
		);
	}

	public static function render_datetime_speech_control( $post )
	{
		$model = new WS_Register_Speaker( $post->ID );

		?>
		<p>
			<input type="text" class="large-text datetimepicker" id="ws-field-datetime-speech" data-mask="00/00/0000 00:00"
			       name="ws-metas[<?php echo esc_attr( WS_Register_Speaker::POST_META_DATETIME_SPEECH ); ?>]" 
			       value="<?php echo esc_attr( $model->get_format_datetime_speech() ); ?>">
		</p>
		<?php

		wp_nonce_field(
			WS_Register_Speakers_Controller::NONCE_SPEAKER_DATETIME_SPEECH_ACTION,
			WS_Register_Speakers_Controller::NONCE_SPEAKER_DATETIME_SPEECH_NAME
		);
	}

	public static function render_topic_control( $post )
	{
		$model = new WS_Register_Speaker( $post->ID );

		?>
		<p>
			<input type="text" class="large-text" id="ws-field-datetime-speech" placeholder="Ex.: Palestra sobre MySQL, Començando com o NodeJS"
			       name="ws-metas[<?php echo esc_attr( WS_Register_Speaker::POST_META_TOPIC ); ?>]" 
			       value="<?php echo esc_attr( $model->topic ); ?>">
		</p>
		<?php

		wp_nonce_field(
			WS_Register_Speakers_Controller::NONCE_SPEAKER_TOPIC_ACTION,
			WS_Register_Speakers_Controller::NONCE_SPEAKER_TOPIC_NAME
		);
	}
}
