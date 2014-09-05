<?php
/**
 * Views Dashboard
 *
 * @package WS Plugin Template Manager
 * @subpackage Dashboard Views
 * @version 1.0
 */
class WS_Manager_Dashboard_view
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
		<div class="custom-welcome-panel-content welcome-panel-content">
			<img class="branding-large" src="<?php echo esc_url( WS_Manager::get_url_assets( 'images/branding_large.svg' ) ); ?>">
			<div class="welcome-panel-column-container">
				<div class="welcome-panel-column">
					<h4>Vamos começar, clique no botão abaixo</h4>
					<a class="button button-primary button-hero load-customize" href="#">Enviar minicurso</a>
				</div>
				<div class="welcome-panel-column welcome-panel-last">
					<h4>Mais ações</h4>
					<ul>
						<li>
							<a href="#" class="welcome-icon dashicons-admin-users">Mantenha seus dados atualizados</a>
						</li>
						<li>
							<a href="#" class="welcome-icon dashicons-admin-network">Alterar senha</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<?php
	}
}