<?php if ( ! function_exists( 'add_action' ) ) exit; ?>
<?php
/**
 * The template part maps
 *
 * @package WordPress
 * @subpackage Theme
 */
global $wp_theme;
?>

<div class="map" id="local">
	<div data-component-map style="width:100%; height:400px;"
	     data-attr-lat="-18.8732072"
	     data-attr-lng="-41.9594773"
	     data-attr-title="Univale - MG"
	     data-attr-icon="<?php echo esc_url( $wp_theme->template_url ); ?>/assets/images/pointer.png">
		<span class="custom-spinner"></span>
	</div>
</div>