<?php
/*
Plugin Name: Name Example
Plugin URI: http://example.com.br/
Description: Description Example
Version: 1.0
Author: Workshop de Informática
Author URI: http://example.com.br/
License: MIT
*/

spl_autoload_register( '__autoload_ws_manager' );

function __autoload_ws_manager( $class_name )
{		
	if ( strpos( $class_name, 'WS_Manager' ) === false )
		return false;

	if ( $class_name == 'WS_Manager' ) :
		require_once sprintf( '%s/class-%s.php', dirname( __FILE__ ), str_replace( '_', '-', strtolower( $class_name ) ) );
		return false;
	endif;

	$class_name = strtolower( str_replace( 'WS_Manager_', '', $class_name ) );
	$class_name = explode( '_', $class_name );
	$class_type = ( isset( $class_name[1] ) ) ? $class_name[1] : 'model';

	switch ( $class_type ) :
	case 'controller' :
		require_once sprintf( '%s/controllers/class-%s.controller.php', dirname( __FILE__ ), $class_name[0] );
		break;
	case 'view' :
		require_once sprintf( '%s/views/class-%s.view.php', dirname( __FILE__ ), $class_name[0] );
		break;
	case 'model' :
		require_once sprintf( '%s/models/class-%s.php', dirname( __FILE__ ), $class_name[0] );
		break;
	case 'helper' :
		require_once sprintf( '%s/helpers/class-%s.helper.php', dirname( __FILE__ ), $class_name[0] );
		break;
	case 'widget' :
		require_once sprintf( '%s/widgets/class-%s.widget.php', dirname( __FILE__ ), $class_name[0] );
		break;
	endswitch;
}

//Plugin Active
register_activation_hook( __FILE__, array( 'WS_Manager', 'activate' ) );
//Plugins loaded
add_action( 'plugins_loaded', array( 'WS_Manager', 'get_instance' ) );