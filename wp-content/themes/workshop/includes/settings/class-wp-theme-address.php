<?php

class WP_Theme_Address
{
	public function the_states()
	{
		if ( ! class_exists( 'Resuta_Manager_Address_Controller' ) )
			return;

		$current    = WP_Theme_Utils::get_method_params( 'state', false );
		$controller = Resuta_Manager_Address_Controller::get_instance();
		$list       = $controller->get_list_state_property();


		$this->_render_select( $list, 'state', $current, 'Todos' );
	}

	public function the_cities()
	{
		if ( ! class_exists( 'Resuta_Manager_Address_Controller' ) )
			return;

		$current = WP_Theme_Utils::get_method_params( 'city', false );
		$state   = WP_Theme_Utils::get_method_params( 'state', false );
		
		if ( empty( $state ) ) :
			$this->_render_select( array(), 'city' );
			return;
		endif;

		$controller = Resuta_Manager_Address_Controller::get_instance();
		$list       = $controller->get_list_cities_property( $state );
		
		$this->_render_select( $list, 'city', $current, 'Todas as cidades' );
	}

	public function the_district()
	{
		if ( ! class_exists( 'Resuta_Manager_Property_Controller' ) )
			return;
		
		$current    = WP_Theme_Utils::get_method_params( 'district', false );
		$controller = Resuta_Manager_Property_Controller::get_instance();
		$list       = $controller->get_list_district();
		
		$this->_render_select( $list, 'district', $current, 'Todos os bairros' );
	}

	public function the_type()
	{
		if ( ! class_exists( 'Resuta_Manager_Property_Controller' ) )
			return;
		
		$current    = WP_Theme_Utils::get_method_params( 'type', false );
		$controller = Resuta_Manager_Property_Controller::get_instance();
		$list       = $controller->get_list_type();
		
		$this->_render_select( $list, 'type', $current, 'Todos os tipos' );
	}

	public function the_transaction()
	{
		if ( ! class_exists( 'Resuta_Manager_Property_Controller' ) )
			return;
		
		$current    = WP_Theme_Utils::get_method_params( 'transaction', Resuta_Manager_Property::OPTION_SALE );
		$controller = Resuta_Manager_Property_Controller::get_instance();
		$list       = $controller->get_list_transaction();
		
		$this->_render_radio( $list, 'transaction', $current );
	}

	private function _render_radio( $list, $name, $current = 0 )
	{
		foreach ( $list as $key => $item ) :
			?>	
				<div class="choice">
					<input class="radio-icheck" type="radio"
					       id="<?php echo $key; ?>"
					       name="<?php echo esc_attr( $name ); ?>"
					       value="<?php echo esc_attr( $item['value'] ); ?>"
					       <?php checked( $item['value'], $current ); ?>>
					<label for="<?php echo $key; ?>"><?php echo esc_html( $item['text'] ); ?></label>
				</div>
			<?php
		endforeach;
	}

	private function _render_select( $list, $name, $current = 0, $default = '-' )
	{
		?>
			<select class="select-chosen select-<?php echo esc_attr( $name ); ?>"
			        name="<?php echo esc_attr( $name ); ?>"
			        <?php echo ( ! $list ) ? 'disabled="disabled"' : ''; ?>>
			        <option value><?php echo esc_attr( $default ); ?></option>
			        <?php $this->_render_options_select( $list, $current ); ?>
			</select>
		<?php
	}

	private function _render_options_select( $list, $current )
	{
		foreach ( $list as $item ) :
			?>
				<option value="<?php echo esc_attr( $item['value'] ) ?>" <?php selected( $item['value'], $current ); ?>><?php echo esc_html( $item['text'] ) ?></option>
			<?php
		endforeach;
	}
}