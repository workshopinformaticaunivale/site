<?php

class WP_Theme_Features extends WP_Theme_Base
{
	public $assets;
	public $wp_admin;
	public $address;
	public $routes;
	public $ajax;
	public $contact_form;

	public function __construct()
	{
		parent::__construct();

		$this->assets       = new WP_Theme_Assets();
		$this->wp_admin     = new WP_Theme_Admin();
		$this->routes       = new WP_Theme_Routes();
		$this->contact_form = new WP_Theme_Contact_Form();
		$this->address      = new WP_Theme_Address();
		//$this->ajax         = new WP_Theme_Ajax();
	}

	public function setup_theme()
	{
		//remove meta tags
		$this->_remove_meta_tags();
		$this->_remove_admin_bar();

		//Theme support
		add_theme_support( 'post-thumbnails' );

		//Menus of site
		// register_nav_menus(
		// 	array(
		// 		'menu-header' => 'Menu do Cabeçalho',
		// 	)
		// );

		//Admin Hooks
		add_action( 'after_switch_theme', array( &$this->wp_admin, 'action_active_theme' ) );

		//Scripts Hooks
		add_action( 'wp_enqueue_scripts', array( &$this->assets, 'enqueue_site_scripts' ) );		

		//Sidebars
		//add_action( 'widgets_init', array( &$this, 'create_default_sidebar' ) );

		//Logout
		add_action( 'wp_logout', array( &$this, 'redirect_logout_home' ) );
	}

	public function redirect_logout_home()
	{
		wp_redirect( $this->site_url, 302 );
		exit;
	}

	public function get_page_by_slug( $post_name )
	{
		$object_page = get_page_by_path( $post_name );

		if ( empty( $object_page ) )
			return false;

		return $object_page;
	}
}
