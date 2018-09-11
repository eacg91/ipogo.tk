	<header>
		<div class="menu_bar" align="center" style="left: -15%;">
			<a href="#" class="bt-menu">
				<small><small>
				<?php
					$o = "<img src='$dirPokebola' height='18'>";
					echo "ip".$o."g".$o."<small>.tk</small>";
				?>
				</small></small>
			<span class="icon-list2"></span>
			</a>
		</div>

		<nav>
			<ul>
				<li><a href="<?php echo $subedir; ?>"> Incursiones </a></li>
				<li><a href="<?php echo $subedir; ?>Gimnasio.php"> Gimnasios </a></li>
				<li><a href="<?php echo $subedir; ?>registrar/Pokemon.php">Pokedex</a></li>
				<!--
				<li><a href="<?php echo $subedir; ?>incursiones/disponibles.php">Raids avistados</a></li>
				<li><a href="<?php echo $subedir; ?>">Agenda tu trayectoria de Incursiones</a></li>
				<li><a href="<?php echo $subedir; ?>">Chatea con otros jugadores agendados</a></li>
				-->
				<li><a href="<?php echo $subedir; ?>registrar/Usuario.php">Usuario</a></li>
				<li class="submenu">
					<a href="#"><!--<span class="icon-mail"></span>--> @Desarrollador <span class="caret icon-arrow-down6"></span></a>
					<ul class="children">
                        <li><a href="https://www.facebook.com/computaccion"><img src="http://computaccion.com//img/facebook-icon-preview.png" height="30">Siguenos en Facebook</a></li>
                        <li><a href="https://www.messenger.com/t/computaccion"><img src="http://computaccion.com//img/facebook-messenger-logo.png" height="30">Comunicate por Messenger</a></li>
                        <li><a href="intent://send/5213312286701#Intent;scheme=smsto;package=com.whatsapp;action=android.intent.action.SENDTO;end"><img src="http://computaccion.com//img/whatsapp-logo-3.png" height="30">Comunicate por WhatsApp (si estás usando Android)</a></li>
                        <li><a href="https://www.paypal.me/computaccion"><img src="https://www.paypalobjects.com/webstatic/paypalme/images/social/pplogo384.png" height="30">Donar con Paypal</a></li>
					</ul>
				<li>
			</ul>
		</nav>
	</header>
