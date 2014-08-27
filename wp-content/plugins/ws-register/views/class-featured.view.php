<?php
/**
 * Views Featured
 *
 * @package WS Plugin Template Manager
 * @subpackage Featured Views
 * @version 1.0
 */
class WS_Register_Featured_View
{
	public static function render_link_control( $post )
	{
		$model = new WS_Register_Featured( $post->ID );

		?>
			<p>
				<input id="ws-field-link" type="text" class="large-text" placeholder="http://"
				       name="ws-metas[<?php echo esc_attr( WS_Register_Featured::POST_META_LINK ); ?>]"
				       value="<?php echo esc_url( $model->link ); ?>">

			</p>
		<?php

		wp_nonce_field(
			WS_Register_Featured_Controller::NONCE_LINK_ACTION,
			WS_Register_Featured_Controller::NONCE_LINK_NAME
		);
	}
}
