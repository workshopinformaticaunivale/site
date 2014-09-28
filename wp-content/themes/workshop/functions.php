<?php
if ( ! function_exists( 'add_action' ) ) exit;

spl_autoload_register( 'autoload_required_files' );

function autoload_required_files( $class_name ) 
{
	if ( strpos( $class_name, 'WP_Theme' ) !== false ) :
		$to_file_name = str_replace( '_', '-', strtolower( $class_name ) );
		require_once dirname(__FILE__) . "/includes/settings/class-$to_file_name.php";
	endif;
}

global $wp_theme;
$wp_theme = new WP_Theme_Features();
add_action( 'after_setup_theme', array( &$wp_theme, 'setup_theme' ) );
