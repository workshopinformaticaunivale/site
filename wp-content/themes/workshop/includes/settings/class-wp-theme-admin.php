<?php

class WP_Theme_Admin
{
	const PAGE_BOX_SLUG = 'box-register-page';

	/**
	 * <not defined>
	 *
	 * <not defined>
	 *
	 * @param <not defined>
	 * @return <not defined>
	 */
	public function action_active_theme()
	{
		$this->_set_page_box_register();
	}

	/**
	 * <not defined>
	 *
	 * <not defined>
	 *
	 * @param <not defined>
	 * @return <not defined>
	 */
	private function _set_page_box_register()
	{
		global $wp_theme;

		//Pagina Inicial
		$wp_theme->maybe_create_page(
			self::PAGE_BOX_SLUG,
			array(
				'title'  => 'Box Page | Configuração Restrita',
				'status' => 'private',
			)
		);
	}
}
