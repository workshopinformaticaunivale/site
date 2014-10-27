<?php
/**
 * View Courses
 *
 * @package WS Register
 * @subpackage Course View
 * @version 1.0
 */
class WS_Register_Courses_View
{
	public static function render_speaker_requirements_control( $post )
	{
		$model = new WS_Register_Course( $post->ID );

		?>
		<textarea rows="4" id="ws-field-requirements" class="large-text" name="ws-metas[<?php echo esc_attr( WS_Register_Course::POST_META_SPEAKER_REQUIREMENTS ); ?>]"><?php echo esc_html( $model->speaker_requirements ); ?></textarea>
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
		<input type="number" id="ws-field-workload" class="large-text" min="0" name="ws-metas[<?php echo esc_attr( WS_Register_Course::POST_META_WORKLOAD ); ?>]" value="<?php echo esc_html( $model->workload ); ?>">
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
				<tr valign="top">
					<th scope="col">Resumo</th>
					<td><?php echo $model->excerpt; ?></td>
				</tr>
			</tbody>
		</table>
		<?php
	}

	public static function render_register_students( $post )
	{
		?>
		<div class="wrap">
			<h2>Meus Minicursos</h2>
			
			<div class="ws-wrap-register" data-component-register-courses>
				<?php self::render_select_courses(); ?>
				
				<p class="description">
					<span class="dashicons dashicons-lightbulb"></span>
					Fique atento aos horarios de cada minicurso
				</p>

				<div class="ws-container" data-attr-excerpt>
					<div class="ws-message ws-warning"><p>Selecione um minicurso para ver sua descrição.</p></div>
				</div>
				
				<?php self::render_list_courses_by_users( get_current_user_id() ); ?>				
			</div>			
		</div>

		<script id="template-preview-course" type="text/x-handlebars-template">
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="col">Minicurso</th>
						<td>{{text}}</td>
					</tr>
					<tr valign="top">
						<th scope="col">Descrição</th>
						<td>{{excerpt}}</td>
					</tr>
					<tr valign="top">
						<th scope="col">Ministrante</th>
						<td>{{author}}</td>
					</tr>
					<tr valign="top">
						<th scope="col">Carga Horário</th>
						<td>{{workload}}</td>
					</tr>
					<tr valign="top">
						<th scope="col">Laboratório</th>
						<td>{{laboratory}}</td>
					</tr>
					<tr valign="top">
						<th scope="col">Data(s)</th>
						<td>
							{{#each dates}}
								{{this}}<br>
							{{/each}}
						</td>
					</tr>
					<tr valign="top">
						<th scope="col">Acões</th>
						<td class="ws-form-actions">
							<button type="button" data-action="register" data-attr-course="{{value}}" class="button button-primary">Quero participar</button>
							<span class="spinner"></span>
						</td>
					</tr>
				</tbody>
			</table>	
		</script>
		<?php
	}

	public static function render_select_courses()
	{
		$controller = WS_Register_Students_Courses_Controller::get_instance();
		$list       = $controller->get_list();

		?>
		<select class="ws-form-select" data-attr-select data-placeholder="Escolha o seu minicurso">
			<option value></option>
			<?php
				foreach ( $list as $option ) :
					printf(
						'<option value="%1$s" data-attr-item=\'%3$s\'>%2$s</option>',
						$option->value,
						$option->text,
						json_encode( $option )
					);
				endforeach;	
			?>
		</select>
		<?php
	}

	public static function render_header_list_courses_by_users()
	{
		?>
		<tr>					
			<th scope="col" class="manage-column column-posts num">Nome</th>
			<th scope="col" class="manage-column column-posts num">Data(s)</th>
			<th scope="col" class="manage-column column-posts num">Laboratório</th>
			<th scope="col" class="manage-column column-posts num">Ministrante</th>
			<th scope="col" class="manage-column column-posts num">Ações</th>
		</tr>
		<?php
	}

	public static function render_list_courses_by_users( $user_id )
	{
		?>
		<style>
			.wp-list-table .ws-form-actions .spinner {
				left: 151px;
				top: 10px;
			}
			.wp-list-table .ws-form-actions {
				width: 170px;
			}
			.ws-wrap-table .ws-error {
				margin-top: 15px;
			}
		</style>

		<div class="ws-wrap-table">
			<table class="wp-list-table widefat" cellspacing="0" data-component-list-courses-user data-attr-user-id="<?php echo intval( $user_id ); ?>">
				<thead>
					<?php self::render_header_list_courses_by_users(); ?>
				</thead>

				<tfoot>
					<?php self::render_header_list_courses_by_users(); ?>
				</tfoot>

				<tbody data-attr-body>
					<tr class="left-column-list">
						<td colspan="5">Aguarde...</td>
					</tr>
				</tbody>
			</table>
		</div>

		<script id="template-list-courses-user" type="text/x-handlebars-template">
  			{{#if list}}
	  			{{#each list}}
	  			<tr class="{{column_class @index}}" valign="top">
					<td class="column-title">
						{{text}}
					</td>
					<td class="column-title">
						{{#each dates}}
							{{this}}<br>
						{{/each}}
					</td>
					<td class="column-title">
						<strong>{{laboratory}}</strong>
					</td>
					<td class="column-title">
						{{author}}
					</td>
					<td class="ws-form-actions column-title">
						<button type="button" data-action="delete" data-attr-course="{{value}}" class="button button-secondary">Deixar de participar</button>
						<span class="spinner"></span>
					</td>
				</tr>
				{{/each}}
			{{else}}
				<tr class="left-column-list">
					<td colspan="4">Sem minicursos no momento</td>
				</tr>
			{{/if}}
		</script>
		<?php
	}
}
