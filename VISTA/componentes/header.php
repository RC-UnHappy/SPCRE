<?php 
	$n = '<i class="icon-user"></i> Mi Perfil';
	if( $_SESSION['vsn_nivel'] == 1 ){ # administrador central
		$n = '<i class="icon-lock"></i><label style="font-size:12px;">Cambiar contraseña</label>';
	}
?>

<header>
	<div class="left">
		<img src="../IMG/logotipo.png" width="50px"/>
		<h2>U.E.N.B "Samuel Robinson"</h2>
	</div>
	<div class="right">
		<div id="hdr_user" class="parent_dropdown_menu">
			<i class="icon-user-circle"></i><?php echo $_SESSION['vsn_nombre'].' '.$_SESSION['vsn_apellido']; ?> 
			<i id="f_down-op" class="icon-down-dir"> </i>
			<div class="dropdown_menu dropdown_menu_right animacion1">
				<ul>
					<a href="index.php?Perfil&form=1"><li class="item"><?php echo $n; ?></li></a>
					<li id="li-logout" class="item"><i class="icon-off"> </i> Cerrar sesión</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</header>
<script type="text/javascript" src="../JAVASCRIPT/header.js"></script>