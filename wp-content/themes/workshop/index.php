<?php if ( ! function_exists( 'add_action' ) ) exit; ?>
<?php
/**
 * The index template file.
 * 
 * @package WordPress
 * @subpackage Theme
 */
global $wp_theme;

get_header();
?>
	<div class="container">
		<?php get_template_part( 'template-parts/template-part', 'navigation-fixed' ); ?>
		
		<?php get_template_part( 'template-parts/template-part', 'speakers' ); ?>
	</div>

	<?php get_template_part( 'template-parts/template-part', 'register' ); ?>
	
	<?php get_template_part( 'template-parts/template-part', 'partners' ); ?>	
	
	<?php get_template_part( 'template-parts/template-part', 'maps' ); ?>
	
	<?php get_template_part( 'template-parts/template-part', 'contributors' ); ?>

<?php get_footer(); ?>
