<?php
/**
 * View Courses
 *
 * @package WS Plugin Template Manager
 * @subpackage Course View
 * @version 1.0
 */
class WS_Register_Courses_View
{
	public static function render_speaker_requirements_control( $post )
	{
		$model = new WS_Register_Course( $post->ID );

		?>
		<textarea class="large-text" name="ws-metas[<?php echo esc_attr( WS_Register_Course::POST_META_SPEAKER_REQUIREMENTS ); ?>]"><?php echo esc_html( $model->speaker_requirements ); ?></textarea>
		<p class="description">Insira aqui os requisitos mínimos de software ou hardware para a execução do minicurso.</p>
		<?php

		wp_nonce_field(
			WS_Register_Courses_Controller::NONCE_SPEAKER_REQUIREMENTS_ACTION,
			WS_Register_Courses_Controller::NONCE_SPEAKER_REQUIREMENTS_NAME
		);
	}

	public static function render_workload_control( $post )
	{
		$model = new WS_Register_Course( $post->ID );

		?>
		<input type="number" min="0" name="ws-metas[<?php echo esc_attr( WS_Register_Course::POST_META_WORKLOAD ); ?>]" value="<?php echo esc_html( $model->workload ); ?>">
		<p class="description">Insira aqui a carga horária em minutos.</p>
		<?php

		wp_nonce_field(
			WS_Register_Courses_Controller::NONCE_WORKLOAD_ACTION,
			WS_Register_Courses_Controller::NONCE_WORKLOAD_NAME
		);
	}
}
