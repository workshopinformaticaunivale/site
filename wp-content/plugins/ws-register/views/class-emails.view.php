<?php
/**
 * View Courses
 *
 * @package WS Register
 * @subpackage Course View
 * @version 1.0
 */
class WS_Register_Emails_View
{
	public static function render_email_password( WS_Register_Student $model, $password )
	{		
		ob_start()
		
		?>
		<p>Olá, <?php echo esc_html( $model->display_name ); ?></p>
		<p>Segue abaixo seus dados de acesso:</p>
		<ul>
			<li>
				<strong>Email </strong>
				<?php echo esc_html( $model->email ); ?>
			</li>
			<li>
				<strong>Senha </strong>
				<?php echo esc_html( $password ); ?>
			</li>
		</ul>
		<p>Clique no botão <strong>"Entrar"</strong> e informe os dados acima para acessar o sistema.</p>
		<?php
		
		$content = ob_get_contents();
		ob_end_clean();

		return self::render_wrapper( $content );
	}

	public static function render_header()
	{
		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br" xml:lang="pt-br">
			<head>
				<title>Workshop de Informática | Univale</title>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			</head>
			<body style="font-family: Arial, Helvetica, Sans-serif; color: #524747;">			
				<table align="center">
					<thead style="background: #0074a2;">
						<tr>
							<td align="center">
								<img src="<?php echo esc_url( WS_Register::get_url_assets( 'images/branding.png' ) ); ?>" alt="Workshop de Informática" style="display: block;">
							</td>
						</tr>
					</thead>
		<?php
	}

	public static function render_footer()
	{
		$admin_email = WS_Register_Emails_Controller::get_default_email();

		?>
					<tfoot>
						<tr>						
							<td style="padding: 12px 0 10px;">
								<p>Workshop de Informática | <?php echo site_url(); ?> | <a href="mailto:<?php echo esc_attr( $admin_email ); ?>"><?php echo esc_attr( $admin_email ); ?></a></p>
							</td>
						</tr>
					</tfoot>
				</table>
			</body>
			</html>
		<?php
	}

	public static function render_wrapper( $container = '' )
	{
		ob_start();

		self::render_header();

		?>
		<tbody>
			<tr>
				<td style="padding: 20px; border-bottom: 1px solid #ddd; border-left: 1px solid #ddd; border-right: 1px solid #ddd;">
					<?php echo $container; ?>
				</td>
			</tr>
		</tbody>
		<?php

		self::render_footer();

		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}
}