<?php

class WP_Theme_Assets
{
	public function enqueue_site_scripts()
	{
		global $wp_theme;
		
		wp_enqueue_script(
			'resuta-front',
			$wp_theme->template_url . '/assets/javascripts/script.min.js',
			array( 'jquery' ),
			filemtime( $wp_theme->template_directory . '/assets/javascripts/script.min.js' ),
			true
		);

		wp_localize_script(
			'resuta-front', 
			'WPVars', 
			array(
				'urlAjax' => admin_url( 'admin-ajax.php' ) 
			)
		);
	}

	public function enqueue_site_style()
	{

	}

	public function enqueue_admin_scripts()
	{

	}
	
	public function enqueue_admin_style()
	{

	}
}