<?php 
	session_start();
	header('Content-Type: text/html; charset=utf-8'); 

	if( isset($_SESSION['vsn_user'] ) ){ # El usuario inicia sesión
		
		include_once('componentes/router.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>UENB Samuel Robinson</title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1 user-scalable=no"/>
	<link rel="stylesheet" type="text/css" href="../CSS/aplicacion/estilos.css"/>
	<script type="text/javascript" src="../JAVASCRIPT/funciones.js"></script>
	<script type="text/javascript" src="../JAVASCRIPT/ventanas.js"></script>
	<script type="text/javascript" src="../JAVASCRIPT/window_click.js"></script>
	<?php echo $script; ?>
</head>

<body>
	<div id="principal">	
		<!-- menu -->
		<div id="menu">
			<?php include_once('componentes/menu.php');?>
		</div>

		<div id="contenedor">
			<!-- header nav -->
			<?php include_once('componentes/header.php'); ?>

			<div id="contenido">
				<?php include_once($archivo); ?>
			</div>

			<div class="footer">
				Desarrollo de Software Web - Estudiantes de Informática sección 631 - 2019-2020 UPTP "Juan de Jesús Montilla"
			</div>
		</div>
	</div>
	<div class="clear"></div>

	<div id="cont-windows">
		<!--  Ventanas en el documento/ se agrega contenido a las ventanas con js -->
		<div id="window-cont-formulario">
			
		</div>
		<!-- ventana cerrar sesion -->
		<div id="window-logout">
			<h2>¿Desea cerrar la sesión?</h2>
			<div id="logout-si"> Si </div>
			<div id="logout-no"> Cancelar </div>
		</div>
		<!-- ventana de notificaciones -->
		<div id="window-not">
			<h4 id="tlt">Mensaje: </h4>
			<div id="msj"> 
				<!-- muestra mensaje -->
			</div>
				<!-- botones -->
			<div id="bot"> 
				<label for="ok" class="btn btn_icon_split btn_normal btn_rojo btn_lg" id="b2">
					<p>Cancelar</p>
				</label>
				<input type="button" id="ok"/>
				<label for="ok" class="btn btn_icon_split btn_normal btn_gris2 btn_lg" id="b1">
					<p>Aceptar</p>				
				</label>
			</div>
		</div>
	</div>
	<!-- capa de color negro que aparece debajo de las ventanas -->
	<div id="background-black"></div>
</body>
<!-- <script type="text/javascript" src="../JAVASCRIPT/inactividad.js">></script> -->
</html>
<?php 
	}else{
		# no existe la sesión: vuelve atrás
		header('Location: ../index.php?error=0');
	}
?>
