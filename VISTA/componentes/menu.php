<?php 
	include_once('../MODELO/m_seguridad.php'); 
	$objModulo = new cls_modulo();
	$objServicio = new cls_servicio();
	$objMetodo = new cls_metodo();
?>
<div id="m_sistema">
	<i class="icon-graduation-cap"></i>
	<div>
		<p>U.E.N.B</p>
		<span>Samuel Robinson</span>
	</div>
</div>
<!-- inicio -->
<a href="?inicio" class="parent_menu" id="mP_inicio"><i class="icon-home"></i>Inicio</a>

<?php 
	if( $_SESSION['vsn_nivel'] != 1 ){ # no es administrador central
		$objMetodo->setNivel($_SESSION['vsn_nivel']);
		$modulo = $objModulo->listar_menu($_SESSION['vsn_nivel']);
		
		# Lista los modulos
		for ($i=0; $i<count($modulo); $i++) { 
			echo '<div class="parent_menu"><i class="icon-'.$modulo[$i]['ico'].'"></i>'.$modulo[$i]['nom'].'<span class="icon-right-open"></span>';
			echo '<div class="child_menu">';	
			
			# Lista los servicios
			$servicio = $objServicio->listar_menu($_SESSION['vsn_nivel'], $modulo[$i]['cod']);
			for ($j=0; $j<count($servicio); $j++){ 
				# lista solo los servicios que tengan la columna mostrar_menu en "S"
				if( $servicio[$j]['mostrar_menu'] == 'S' ){
					echo '<a href="?'.$servicio[$j]['link'].'">'.$servicio[$j]['nom'].'</a>';
				}
			}
			echo '</div></div>';
		}
	}

	else if( $_SESSION['vsn_nivel'] == 1 ){
		$rsMod = $objModulo->listar_todo();
		$rsSer = $objServicio->listar();
		#$rsMet = $objMetodo->listar_2();

		for ($i=0; $i<count($rsMod); $i++){

			#Imprime los modulos
			echo '<div class="parent_menu"><i class="icon-'.$rsMod[$i]['ico'].'"></i>'.$rsMod[$i]['nom'].'<span class="icon-right-open"></span>';
			echo '<div class="child_menu">';

			# Imprime los servicios	
			for ($j=0; $j<count($rsSer); $j++){ 
				# Si el modulo del servicio corresponde al codigo del modulo[i] Imprime dentro del módulo
				if( $rsSer[$j]['mdlo'] == $rsMod[$i]['cod'] ){
					# lista solo los servicios que tengan la columna mostrar_menu en "S"
					if( $rsSer[$j]['mostrar_menu'] == 'S' ){
						echo '<a href="?'.$rsSer[$j]['link'].'">'.$rsSer[$j]['nom'].'</a>';
					}
				}
			}
			echo '</div></div>';
		}
	}	
?>
<!-- Seguridad-->
<?php if( $_SESSION['vsn_nivel'] == 1 ){ ?>
<div class="parent_menu">
	<i class="icon-shield"></i>Seguridad<span class="icon-right-open"></span>
	<div class="child_menu">
		<a href="?ver=modulo">Módulo</a>
		<a href="?ver=servicio">Servicio</a>
		<a href="?ver=metodo">Método</a>
		<a href="?ver=rol">Rol</a>
	</div>
</div>
<?php } ?>

<script type="text/javascript" src="../JAVASCRIPT/menu_y.js"></script>
<br/><br/><br/><br/><br/><br/><br/><br/>

