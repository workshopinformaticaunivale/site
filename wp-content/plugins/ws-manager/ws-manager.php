<?php
/*
	Plugin Name: Plugin Gerenciador
	Plugin URI: http://workshop.univale.br/
	Description: Control do site Workshop de Informática da Univale
	Version: 1.0
	Author: Workshop de Informática
	Author URI: http://univale.br/
	License: MIT
*/

function loaders_ws_manager( $loaders )
{
	$loaders[] = array(
		'base_class' => 'WS_Manager',
		'base_file'  => dirname( __FILE__ ),
	);

	return $loaders;
}

add_filter( 'ws_add_loaders', 'loaders_ws_manager' );

register_activation_hook( __FILE__, array( 'WS_Manager', 'activate' ) );

add_action( 'plugins_loaded', array( 'WS_Manager', 'get_instance' ) );