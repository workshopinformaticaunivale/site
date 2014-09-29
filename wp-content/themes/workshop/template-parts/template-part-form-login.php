<?php if ( ! function_exists( 'add_action' ) ) exit; ?>

<div class="box-login">
	<button class="login" data-action="open">Entrar</button>	
	<a class="btn" data-action="register" href="#cadastre-se">Cadastre-se</a>

	<div class="form-login">
		<form action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ) ?>" method="post">
			<ul>
				<li>
					<label for="ws-form-log">Usuario</label>
					<input placeholder="Digite seu email" type="text" id="ws-form-log" name="log">
				</li>
				<li>
					<label for="ws-form-pwd">Senha</label>
					<input placeholder="Senha" type="password" id="ws-form-pwd" name="pwd">
				</li>
				<li>
					<input type="hidden" name="redirect_to" value="<?php echo esc_url( admin_url( 'index.php' ) ); ?>" />
					<input class="btn-sec" type="submit" value="Login">
				</li>
			</ul>
		</form>
	</div>
</div>
