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

$controller    = WS_Register_Courses_Controller::get_instance();
$list          = $controller->get_list_group_datetime_start();
$link_register = '<a class="btn-sec" data-action="login" href="#login">Inscreva-se</a>';

if ( is_user_logged_in() ) :
	$link_admin    = admin_url( 'edit.php?post_type=' . WS_Register_Course::POST_TYPE . '&page=students-courses' );
	$link_register = "<a class=\"btn-sec\" href=\"{$link_admin}\">Inscreva-se</a>";
endif;

if ( ! $list )
	return;
?>

<section class="time-line" id="minicursos">
	<h2 class="title-section" style="-webkit-transform: translateY(10px); transform: translateY(10px);">Minicursos</h2>			
	
	<div class="list-time-line">	
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
					<span class="laboratory"><?php echo esc_html( $model->get_laboratory_name() ); ?></span>
				</div>
				<?php echo $link_register; ?>					
			</div>
			<?php endforeach; ?>
		</div>
		<?php endforeach; ?>
	</div>
</section>