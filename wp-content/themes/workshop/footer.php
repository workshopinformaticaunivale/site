<?php if ( ! function_exists( 'add_action' ) ) exit; ?>
<?php
/**
 * The footer template file.
 *
 * @package WordPress
 * @subpackage Theme
 */
global $wp_theme;
?>

	<footer class="footer container">
		<div class="branding-footer">
			<img src="<?php echo esc_url( $wp_theme->template_url ); ?>/assets/images/branding-footer.svg" height="40" width="37" alt="Logo do Evento">
			<h3 class="work-shop">workshop de informática</h3>
		</div>

		<div class="support">
			<span>apoio</span>
			<a href="http://www.resuta.com.br" target="_blank" title="Agência Resuta. Solução em desenvolvimento web">
				<img src="<?php echo esc_url( $wp_theme->template_url ); ?>/assets/images/resuta.svg" height="40" width="40" alt="Agência Resuta">
			</a>
		</div>
	</footer>
	<div class="overlay"></div>
</div>
<?php wp_footer(); ?>	
</body>
</html>