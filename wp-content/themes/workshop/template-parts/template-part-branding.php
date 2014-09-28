<?php if ( ! function_exists( 'add_action' ) ) exit; ?>
<?php
/**
 * The template part branding
 *
 * @package WordPress
 * @subpackage Theme
 */
global $wp_theme;

if ( ! class_exists( 'WS_Register_Events_Controller' ) )
	return;

$event = WS_Register_Events_Controller::get_current_event();
?>

<div class="branding">
	<img src="<?php echo esc_url( $wp_theme->template_url ); ?>/assets/images/branding.svg" height="80" width="234" alt="Logo do Evento">
	<div class="info">
		<strong><?php echo esc_html( $event->edition ); ?>º Edição</strong>
		<div class="date"><?php echo esc_html( $event->get_extensive_full_date() ); ?></div>
	</div>
</div>