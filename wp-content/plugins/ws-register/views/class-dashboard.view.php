<?php
/**
 * Views Dashboard
 *
 * @package WS Register
 * @subpackage Dashboard Views
 * @version 1.0
 */
class WS_Register_Dashboard_view
{
	/**
	 * Renders the welcome panel HTML
	 *
	 * @since 1.0
	 * @return void
	 */
	public static function render_welcome_panel()
	{
		?>
		<div class="ws-dashboard welcome-panel-content">
			<img class="ws-branding-large" src="<?php echo esc_url( WS_Register::get_url_assets( 'images/branding_large.svg' ) ); ?>">
			<div class="welcome-panel-column-container">
				<div class="welcome-panel-column">
					<h4>Vamos começar, clique no botão abaixo</h4>
					<a class="button button-primary button-hero load-customize" href="javascript:void(0);">Cadastar Evento</a>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Renders dashboard branding
	 *
	 * @since 1.0
	 * @return void
	 */
	public static function render_dashboard_branding()
	{
		?>
		<div class="ws-dashboard dashboard-branding">
			<img class="ws-branding-large" src="<?php echo esc_url( WS_Register::get_url_assets( 'images/branding_large.svg' ) ); ?>">
		</div>
		<?php
	}

	/**
	 * Renders dashboard students information
	 *
	 * @since 1.0
	 * @return void
	 */
	public static function render_dashboard_students_information()
	{
		?>
		<div class="ws-dashboard dashboard-students-information">
			<h4>Envio de Minicursos</h4>
			<p>Preencha todas as informações do seu minicurso: <strong>título, descrição, requisitos de software e carga horária</strong>. Sua proposta será enviada para revisão e quando aprovada você receberá um e-mail.</p>
			<a class="button button-primary button-hero load-customize" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=' . WS_Register_Course::POST_TYPE ) ); ?>">
			 	<span class="dashicons dashicons-welcome-write-blog"></span>Enviar proposta de minicurso
			</a>
		</div>
		<?php
	}

	/**
	 * Renders dashboard students actions
	 *
	 * @since 1.0
	 * @return void
	 */
	public static function render_dashboard_students_actions()
	{
		?>
		<div class="ws-dashboard dashboard-students-actions">
			<h4>Opções</h4>
			<ul>
				<li>
					<a href="<?php echo esc_url( admin_url( 'profile.php#4' ) ); ?>" class="welcome-icon dashicons-admin-users">Mantenha seus dados atualizados</a>
				</li>
				<li>
					<a href="<?php echo esc_url( admin_url( 'profile.php#3' ) ); ?>" class="welcome-icon dashicons-admin-network">Alterar senha</a>
				</li>
			</ul>			
		</div>
		<?php
	}
}