<?php if ( ! function_exists( 'add_action' ) ) exit; ?>

<div class="box-login">
	<button class="login" data-action="open">Entrar</button>	
	<button class="btn">Cadastre-se</button>

	<div class="form-login">
		<form action="">
			<ul>
				<li>
					<label for="">Usuario</label>
					<input placeholder="usuario" type="text">
				</li>
				<li>
					<label for="">Senha</label>
					<input placeholder="senha" type="password">
				</li>
				<li>
					<input class="btn-sec" type="submit" value="Login">
				</li>
			</ul>
		</form>
	</div>
</div>
