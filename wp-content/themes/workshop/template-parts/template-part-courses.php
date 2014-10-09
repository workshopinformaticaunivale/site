<?php if ( ! function_exists( 'add_action' ) ) exit; ?>
<?php
/**
 * The template part branding
 *
 * @package WordPress
 * @subpackage Theme
 */
global $wp_theme;

if ( ! class_exists( 'WS_Register_Courses_Controller' ) )
	return;

$controller = WS_Register_Courses_Controller::get_instance();
$list       = $controller->get_list_group_datetime_start();
$tooltip    = 'É necessário que você esteja logado!';

if ( ! $list )
	return;

if ( is_user_logged_in() )
	$tooltip = 'É necessário realizar o pagamento, para informações entre contato marcos.univale@gmail.com';
?>

<section class="time-line" id="minicursos">
	<h2 class="title-section" style="-webkit-transform: translateY(10px); transform: translateY(10px);">Minicursos</h2>
	<p class="info">Para a realização de minicursos é necessário ter um cadastro e realizar o pagamento do setor da tesouraria da UNIVALE.</p>
		
	<div class="list-time-line" style="margin-top: 30px;">
		
		<?php foreach ( $list as $key => $items ) : ?>
		<div class="box-day">
			<time class="day"><?php echo date_i18n( 'd/m \- l' ,strtotime( $key ) ); ?></time>
			
			<?php foreach ( $items as $model ) : ?>
			<div class="item-time-line">
				<time class="date" datatime="<?php echo esc_attr( $model->get_datetime_start( 1, 'H:i' ) ); ?>">
					<?php echo esc_attr( $model->get_datetime_start( 1, 'H\hi' ) ); ?>
				</time>
				<div class="info">
					<h3 class="course"><?php echo esc_html( $model->title ); ?></h3>
					<?php echo apply_filters( 'the_content', $model->excerpt ); ?>
				</div>
				
				<button data-tooltip="<?php echo esc_attr( $tooltip ); ?>" class="btn-sec">Inscreva-se</button>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endforeach; ?>
	</div>
</section>