<?php
/*
Plugin Name: Plugin Gerenciador dos Alunos
Plugin URI: http://example.com.br/
Description: Control dos eventos, minicursos e alunos
Version: 1.0
Author: Workshop de Informática
Author URI: http://example.com.br/
License: MIT
*/

spl_autoload_register( '__autoload_ws_register' );

//Plugin Active
register_activation_hook( __FILE__, array( 'WS_Register', 'activate' ) );

//Plugins loaded
add_action( 'plugins_loaded', array( 'WS_Register', 'get_instance' ) );

function __autoload_ws_register( $class_name )
{
	$plugin_class  = 'WS_Register';
	$plugin_slug   = str_replace( '_', '-', strtolower( $plugin_class ) );
	$pattern_lower = str_replace( '_', '-', strtolower( $class_name ) );	

	__autoload_dependencies( $class_name, $pattern_lower );

	if ( $class_name == $plugin_class ) :
		require_once sprintf( '%s/class-%s.php', dirname( __FILE__ ), $pattern_lower );
		return false;
	endif;

	if ( strpos( $class_name, $plugin_class ) === false )
		return false;

	$class_name = str_replace( $plugin_slug . '-', '', $pattern_lower );
	$class_name = preg_split( '/-(controller|view|helper|abstract)/',  $class_name, 2, PREG_SPLIT_DELIM_CAPTURE );

	if ( ! isset( $class_name[1] ) ) :
		require_once sprintf( '%1$s/%3$ss/class-%2$s.php', dirname( __FILE__ ), $class_name[0], 'model' );
		return false;
	endif;

	require_once sprintf( '%1$s/%3$ss/class-%2$s.%3$s.php', dirname( __FILE__ ), $class_name[0], $class_name[1] );
}

function __autoload_dependencies( $class_name, $pattern_lower )
{
	if ( $class_name == 'WS_Metas_Library' and ! class_exists( 'WS_Metas_Library' ) ) :
		require_once sprintf( '%s/includes/class-metas.library.php', dirname( __FILE__ ) );
		return false;
	endif;

	if ( $class_name == 'WS_Images_Library' and ! class_exists( 'WS_Images_Library' ) ) :
		require_once sprintf( '%s/includes/class-images.library.php', dirname( __FILE__ ) );
		return false;
	endif;

	if ( $class_name == 'WS_Utils_Helper' and ! class_exists( 'WS_Utils_Helper' ) ) :
		require_once sprintf( '%s/helpers/class-utils.helper.php', dirname( __FILE__ ) );
		return false;
	endif;
}