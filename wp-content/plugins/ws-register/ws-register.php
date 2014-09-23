<?php
/*
	Plugin Name: Plugin Gerenciador dos Alunos
	Plugin URI: http://workshop.univale.br/
	Description: Control dos eventos, minicursos e alunos
	Version: 1.0
	Author: Workshop de Informática
	Author URI: http://univale.br/
	License: MIT
*/

function loaders_ws_register( $loaders )
{
	$loaders[] = array(
		'base_class' => 'WS_Register',
		'base_file'  => dirname( __FILE__ ),
	);

	return $loaders;
}

add_filter( 'ws_add_loaders', 'loaders_ws_register' );

register_activation_hook( __FILE__, array( 'WS_Register', 'activate' ) );

add_action( 'plugins_loaded', array( 'WS_Register', 'get_instance' ) );