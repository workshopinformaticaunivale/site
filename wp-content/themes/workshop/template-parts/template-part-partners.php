<?php if ( ! function_exists( 'add_action' ) ) exit; ?>
<?php
/**
 * The template part partners
 *
 * @package WordPress
 * @subpackage Theme
 */
if ( ! class_exists( 'WS_Register_Partners_Controller' ) )
	return;

$controller = WS_Register_Partners_Controller::get_instance();
$list       = $controller->get_list();

if ( ! $list )
	return;
?>

<section class="partners container" id="parceiros">
	<h2 class="title-section">Parceiros</h2>
	<ul class="list-partners">
		<?php foreach ( $list as $model ) : ?>
		<li>
			<a href="<?php echo esc_url( $model->site ); ?>" title="<?php echo esc_attr( $model->title ); ?>" target="_blank">
				<img src="<?php echo esc_url( $model->thumbnail_url ); ?>" alt="Imagem do Parceiro">
			</a>
		</li>
		<?php endforeach; ?>
	</ul>
</section>