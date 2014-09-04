<?php
/**
 * Students View
 *
 * @package WS Plugin Template Manager
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
							<label for="ws-code-enrollment">Nº Matricula</label>
						</th>
						<td>
							<input type="number" name="<?php echo esc_attr( WS_Register_Student::USER_META_CODE_ENROLLMENT ); ?>"
								   id="ws-code-enrollment" value="<?php echo intval( $model->code_enrollment ); ?>"
								   class="medium-text">
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
								   id="ws-period" value="<?php echo intval( $model->period ); ?>"
								   class="medium-text">
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
				</tbody>
			</table>
		<?php
	}
}