<?php if ( ! function_exists( 'add_action' ) ) exit; ?>
<?php
/**
 * The template part register
 *
 * @package WordPress
 * @subpackage Theme
 */
if ( ! class_exists( 'WS_Register_Student' ) || ! class_exists( 'WS_Register_Students_Controller' ) )
	return;
?>

<section class="contact" id="cadastre-se" data-component-register>
	<div class="container">
		<h2 class="title-section">Cadastre-se</h2>
		<p class="info">Informe todos os dados abaixo para concluir o seu cadastro.</p>

		<form action="" data-attr-form>
			<ul class="box-contact">
				<li class="change-control">
					<input id="form-student-yes" name="is_student" class="custom-check" type="radio" value="1" checked="checked" data-action="student">
					<label for="form-student-yes">Sou aluno</label>
					
					<input id="form-student-no" name="is_student" class="custom-check" type="radio" value="0" data-action="student">
					<label for="form-student-no">Não sou aluno</label>
				</li>
				<li>
					<label for="form-display-name">Nome*</label>
					<input id="form-display-name" name="display_name" placeholder="Nome*" type="text" required>
				</li>
				<li>
					<label for="form-email">Email*</label>
					<input id="form-email" name="email" placeholder="Email*" type="email" required>
				</li>
				<li class="medium left">
					<label for="form-code-enrollment">Número da matrícula*</label>
					<input id="form-code-enrollment" placeholder="Número da matrícula*" type="number" min="1"
					       name="<?php echo esc_attr( WS_Register_Student::USER_META_CODE_ENROLLMENT ); ?>" required data-attr-enrollment>
				</li>
				<li class="small">
					<label for="form-period">Período*</label>
					<input id="form-period" placeholder="Período*" type="number" min="1"
					       name="<?php echo esc_attr( WS_Register_Student::USER_META_PERIOD ); ?>" required data-attr-period>
				</li>
				<li class="clear">
					<label for="form-course">Curso*</label>
					<input id="form-course" placeholder="Curso*" type="text"
					       name="<?php echo esc_attr( WS_Register_Student::USER_META_COURSE ); ?>" required data-attr-course>
				</li>
				<li>
					<input type="hidden" name="action" value="set_new_user">
					<?php wp_nonce_field( WS_Register_Students_Controller::NONCE_STUDENT_REGISTER ); ?>
					<input class="btn" type="submit" value="enviar">
				</li>
			</ul>
		</form>		

		<p class="response-error" data-attr-error></p>
		<p class="response-success">Seu cadastro foi realizado com sucesso, em breve você receberá um e-mail com seus dados de acesso.</p>
	</div>
</section>