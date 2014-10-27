<?php
/**
 * Students View
 *
 * @package WS Register
 * @subpackage Students
 */
class WS_Register_Students_View
{
	public static function render_profile( $user_id )
	{
		$model = new WS_Register_Student( $user_id );

		?>
			<h3>Sobre o aluno</h3>
			<table class="form-table" data-column="student">
				<tbody>
					<tr>
						<th scope="row">
							<label for="ws-display-name">Nome de exibição</label>
						</th>
						<td>
							<input type="text" name="display_name"
							       id="ws-display-name" value="<?php echo esc_attr( $model->display_name ); ?>"
							       class="large-text" data-not-remove>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="ws-email">Email (obrigatório)</label>
						</th>
						<td>
							<input type="text" name="email"
							       id="ws-email" value="<?php echo esc_attr( $model->email ); ?>"
							       class="regular-text ltr" data-not-remove>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="ws-code-enrollment">Nº Matrícula</label>
						</th>
						<td>
							<input type="number" name="<?php echo esc_attr( WS_Register_Student::USER_META_CODE_ENROLLMENT ); ?>"
								   id="ws-code-enrollment" value="<?php echo esc_attr( $model->code_enrollment ); ?>"
								   class="medium-text">
							<br>
							<span class="description ws-attention"><i class="dashicons dashicons-info"></i> Preencha o campo acima somente com números.</span>	   
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="ws-course">Curso</label>
						</th>
						<td>
							<input type="text" name="<?php echo esc_attr( WS_Register_Student::USER_META_COURSE ); ?>"
								   id="ws-course" value="<?php echo esc_attr( $model->course ); ?>" placeholder="Ex.: Sistema de informação, Nutrição, Odontologia"
								   class="large-text">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="ws-period">Período</label>
						</th>
						<td>
							<input type="number" name="<?php echo esc_attr( WS_Register_Student::USER_META_PERIOD ); ?>"
								   id="ws-period" value="<?php echo esc_attr( $model->period ); ?>"
								   class="medium-text">
							<br>
							<span class="description ws-attention"><i class="dashicons dashicons-info"></i> Preencha o campo acima somente com números.</span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label>Sua ou Foto</label>
						</th>
						<td>
							<button class="ws-avatar-profile" type="button"
					        		data-component-upload-file
							        data-attr-button-text="Selecionar imagem"
							        data-attr-image-position="inner"
							        data-attr-image-src="<?php echo esc_url( $model->get_avatar_url_small() ); ?>"
							        data-attr-hidden-name="<?php echo esc_attr( WS_Register_Student::USER_META_AVATAR ); ?>"
							        data-attr-hidden-value="<?php echo esc_attr( $model->avatar_id ); ?>">
					    		<span class="dashicons dashicons-format-image"></span>
								<span class="description">Imagem 150x150</span>
							</button>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label>Cursos</label>
						</th>
						<td>
							<?php WS_Register_Courses_View::render_list_courses_by_users( $model->ID ); ?>
						</td>
					</tr>
				</tbody>
			</table>
		<?php
	}

	public static function render_filter_code_enrollment()
	{
		$current_code = WS_Utils_Helper::get_method_params( WS_Register_Student::USER_META_CODE_ENROLLMENT, false );

		?>
		<div class="ws-filter-users">
			<input type="number" name="<?php echo esc_attr( WS_Register_Student::USER_META_CODE_ENROLLMENT ); ?>"
				   id="ws-code-enrollment" value="<?php echo esc_attr( $current_code ); ?>"
				   class="medium-text">			

			<input type="hidden" value="<?php echo esc_attr( WS_Register_Student::ROLE ); ?>" name="role">

			<?php submit_button( 'Pesquisar Matrícula', 'button', '', false ); ?>
		</div>
		<?php
	}

	public static function render_excel_table()
	{
		$query = new WP_User_Query( array( 'role' => WS_Register_Student::ROLE ) );

		if ( empty( $query->results ) )
			return;

		$html =
			'<table>
				<thead>
					<tr>
						<th>Nome</th>
						<th>Email</th>
						<th>Matricula</th>
						<th>Curso</th>
						<th>Periodo</th>
					</tr>
				</thead>
				<tbody>';

				foreach ( $query->results as $user ) :
					$model = new WS_Register_Student( $user->ID );
					$html .= sprintf(
						'<tr>
							<td>%s</td>
							<td>%s</td>
							<td>%d</td> 
							<td>%s</td>
							<td>%s</td>  
						</tr>', 
						remove_accents( $model->display_name ), 
						$model->email, 
						$model->code_enrollment,
						remove_accents( $model->course ),
						$model->period 
					);

					//do_action( 'ws_renovation_register_courses', $model );
				endforeach;
				
		$html .= '</tbody>
			</table>';

		return $html;
	}
}