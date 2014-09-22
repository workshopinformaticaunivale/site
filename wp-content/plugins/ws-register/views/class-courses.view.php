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
		<input type="number" class="large-text" min="0" name="ws-metas[<?php echo esc_attr( WS_Register_Course::POST_META_WORKLOAD ); ?>]" value="<?php echo esc_html( $model->workload ); ?>">
		<p class="description">Insira aqui a carga horária em minutos.</p>
		<?php

		wp_nonce_field(
			WS_Register_Courses_Controller::NONCE_WORKLOAD_ACTION,
			WS_Register_Courses_Controller::NONCE_WORKLOAD_NAME
		);
	}

	public static function render_laboratory_control( $post )
	{
		$terms        = get_terms( WS_Register_Course::TAXONOMY_LABORATORY, array( 'hide_empty' => 0 ) );
		$post_terms   = wp_get_object_terms( $post->ID, WS_Register_Course::TAXONOMY_LABORATORY );
		$current_term = ( ! empty( $post_terms ) ) ? array_shift( $post_terms ) : '';

		if ( empty( $terms ) ):
			?>
			<span>Nenhum laboratório cadastrado.</span>
			<a class="button-secondary" href="<?php echo esc_url( 'edit-tags.php?taxonomy=' . WS_Register_Course::TAXONOMY_LABORATORY. '&post_type='. WS_Register_Course::POST_TYPE ); ?>">Adicionar novo</a>
			<?php
		else :
			?>
			<select class="chosen-select" name="tax_input[ws-course-taxonomy-laboratory][]">
				<option value="-1">Selecione um laboratório</option>
				<?php 
					foreach ( $terms as $term ) :
						$selected = ( ! empty( $current_term ) ) ? selected( $current_term->term_id, $term->term_id, false ) : '';
						printf( '<option %s value="%s">%s</option>', $selected, $term->term_id, $term->name );
					endforeach;
				?>
			</select>
			<?php
		endif;	
	}

	public static function render_date_item_control( $datetime_start = '', $datetime_end = '' )
	{
		?>
		<li class="day">
			<div>
				<label>Data e horário inicial:
					<input type="text" data-mask="00/00/0000 00:00" class="datetimepicker" name="<?php echo esc_attr( WS_Register_Course::POST_META_DATETIME_START ); ?>[]" value="<?php echo esc_attr( $datetime_start ); ?>">
				</label>
			</div>
			<div>
				<label>Horário final:
					<input type="text" data-mask="00:00" class="timepicker" name="<?php echo esc_attr( WS_Register_Course::POST_META_DATETIME_END ); ?>[]" value="<?php echo esc_attr( $datetime_end ); ?>">
				</label>
			</div>
			<div class="wrap-buttons">
				<input type="button" class="button-primary add" name="add_new" value="Adicionar novo dia">
				<input type="button" class="button-secondary remove" name="remove_item" value="Remover dia">
			</div>
		</li>
		<?php
	}

	/**
	 * Renders classes metabox Date and Time
	 *
	 * @since 1.0
	 * @return void
	 */
	public static function render_date_control( $post )
	{
		$controller = WS_Register_Courses_Controller::get_instance();
		?>
		<ul class="container-day">
			<li class="blank">
				<div>
					<label>Data e horário inicial:
						<input type="text" data-mask="00/00/0000 00:00" class="datetimepicker" data-no-plugin name="<?php echo esc_attr( WS_Register_Course::POST_META_DATETIME_START ); ?>[]" value="">
					</label>
				</div>

				<div>
					<label>Horário final:
						<input type="text" data-mask="00:00" class="timepicker" data-no-plugin name="<?php echo esc_attr( WS_Register_Course::POST_META_DATETIME_END ); ?>[]" value="">
					</label>
				</div>

				<div class="wrap-buttons">
					<input type="button" class="button-primary add" name="add_new" value="Adicionar novo dia">
					<input type="button" class="button-secondary remove" name="remove_item" value="Remover dia">
				</div>
			</li>
			<?php $controller->iterate_date( $post->ID ); ?>
		</ul>
		<?php
	}

	public static function render_course_data_control( $post )
	{
		$model = new WS_Register_Course( $post->ID );

		?>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="col">Título</th>
					<td><?php echo esc_html( $model->title ); ?></td>
				</tr>
				<tr valign="top">
					<th scope="col">Proposta</th>
					<td><?php echo $model->content; ?></td>
				</tr>
			</tbody>
		</table>
		<?php
	}
}
