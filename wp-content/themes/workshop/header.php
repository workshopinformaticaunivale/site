<?php if ( ! function_exists( 'add_action' ) ) exit; ?>
<?php
/**
 * The header template file.
 *
 * @package WordPress
 * @subpackage Theme
 */
global $wp_theme;
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php $wp_theme->the_page_title(); ?></title>

	<!--[if lt IE 9]>
		<script src="<?php echo esc_url( $wp_theme->template_url ); ?>/assets/javascripts/html5.js"></script>
		<script src="<?php echo esc_url( $wp_theme->template_url ); ?>/assets/javascripts/augment.min.js"></script>
	<![endif]-->

	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-55262703-1', 'auto');
	  ga('send', 'pageview');
	</script>

	<link rel="stylesheet" href="<?php echo esc_url( get_stylesheet_uri() ) ?>?v=<?php echo esc_attr( filemtime( $wp_theme->template_directory  . '/style.css' ) ); ?>">
	<?php wp_head(); ?>	
</head>
<body <?php $wp_theme->routes->module_js(); ?>>

	<div class="wrapper">
		<header class="header">
			<div class="container">
				<?php get_template_part( 'template-parts/template-part', 'branding' ); ?>
							
				<?php get_template_part( 'template-parts/template-part', 'form-login' ); ?>
			</div>
		</header>
