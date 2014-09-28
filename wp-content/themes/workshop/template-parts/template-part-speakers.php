<?php if ( ! function_exists( 'add_action' ) ) exit; ?>
<?php
/**
 * The template part speakers
 *
 * @package WordPress
 * @subpackage Theme
 */
if ( ! class_exists( 'WS_Register_Speakers_Controller' ) )
	return;

$controller = WS_Register_Speakers_Controller::get_instance();
$list       = $controller->get_list_current_event();

if ( ! $list )
	return;
?>

<section class="speakers" id="palestrantes" data-component-speakers>
	<h2 class="title-section">Palestrantes</h2>
	
	<div class="list-speakers">
		<?php foreach ( $list as $model ) : ?>
		<div class="card-speaker" data-action="open" data-attr-text='<?php echo esc_attr( $model->content ); ?>'>
			<img class="avatar" src="<?php echo esc_url( $model->thumbnail_url ); ?>" alt="Imagem do Palestrante">
			
			<div class="name"><?php echo esc_html( $model->title ); ?></div>
			<h3 class="lecture"><?php echo esc_html( $model->topic ); ?></h3>
			
			<time class="date" datetime="<?php echo esc_attr( $model->get_format_datetime_speech( 'Y-m-d H:i' ) ); ?>">
				<?php echo esc_attr( $model->get_format_datetime_speech( 'd/m H:i' ) ); ?>
			</time>
		</div>
		<?php endforeach; ?>		
	</div>
	<div class="info-name" data-attr-modal></div>
</section>
