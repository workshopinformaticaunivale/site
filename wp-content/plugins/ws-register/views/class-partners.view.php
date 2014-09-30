<?php
/**
 * Partners Events
 *
 * @package WS Register
 * @subpackage Events Partners
 * @version 1.0
 */
class WS_Register_Partners_View
{
	public static function render_site_control( $post )
	{
		$model = new WS_Register_Partner( $post->ID );

		?>
			<p>
				<input id="ws-field-site" type="text" class="large-text" placeholder="http://"
				       name="ws-metas[<?php echo esc_attr( WS_Register_Partner::POST_META_SITE ); ?>]"
				       value="<?php echo esc_url( $model->site ); ?>">
			</p>
		<?php

		wp_nonce_field(
			WS_Register_Partners_Controller::NONCE_SITE_ACTION,
			WS_Register_Partners_Controller::NONCE_SITE_NAME
		);
	}
}
