<?php 
	include_once('../MODELO/m_seguridad.php');
	$objModulo = new cls_modulo();
	$objServicio = new cls_servicio();
	$objMetodo = new cls_metodo();

	# VARIABLES
	$txtSer = ''; # texto de concatenacion en el título
	$moduloA = '0'; # codigo del modulo actual
	$nomSerA = ''; # nombre del servicio actual
	$thNomSerA = ''; # nombre del servicio en la tabla
	$servicioA = ''; # codigo del servico actual
	$rsRol = ''; # array con roles
	$btnAgregar = 0;

	# Arrays
	$arrRoles = array();
	$arrInc = array();
	$arrMod = array();
	$arrElm = array();
	$arrCons = array();

	if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
		# Solicitudes con ajax:
		if( isset($_POST['ajax']) ){
			$objServicio->set_modulo( $_POST['modulo'] );
			$objServicio->combo_option(true);
		}

		else{
			switch ($_POST['ope']) {
				case 'add':
					$objMetodo->set($_POST['rol'],$_POST['servicio']);
					if( $objMetodo->incluir() ){
						header('location: ../VISTA/index.php?ver=metodo&ope=1&modulo='.$_POST['modulo'].'&servicio='.$_POST['servicio']);
					}else{
						header('location: ../VISTA/index.php?ver=metodo&error=1&modulo='.$_POST['modulo'].'&servicio='.$_POST['servicio']);
					}
					break;

				case 'elm':
					$objMetodo->set($_POST['rol'],$_POST['servicio']);
					$objMetodo->eliminar();
					header('location: ../VISTA/index.php?ver=metodo&ope=2&modulo='.$_POST['modulo'].'&servicio='.$_POST['servicio']);
					break;
				
				case 'metodos':
					# convierte los strings en arreglos
					$arrRoles = explode(',', $_POST['arrRoles']);
					$arrInc = explode(',', $_POST['arrInc']);
					$arrMod = explode(',', $_POST['arrMod']);
					$arrElm = explode(',', $_POST['arrElm']);
					$arrCons = explode(',', $_POST['arrCons']);

					for ($i=0; $i<count($arrRoles); $i++) { 
						$objMetodo->set($arrRoles[$i],$_POST['servicio'],$arrInc[$i],$arrMod[$i],$arrElm[$i],$arrCons[$i]);
						$objMetodo->modificar().'<br>';
					}
					header('location: ../VISTA/index.php?ver=metodo&ope=2&modulo='.$_POST['modulo'].'&servicio='.$_POST['servicio']);
					break;
			}
		}
	}

	else if( $_SERVER['REQUEST_METHOD'] == 'GET' ){
		if( isset($_GET['modulo']) && !empty($_GET['modulo']) && isset($_GET['servicio']) && !empty($_GET['servicio']) ){
			$btnAgregar = 1; # para mostrar el boton agregar rol

			$objServicio->set_codigo($_GET['servicio']);
			$rs = $objServicio->consultar();
			$moduloA = $_GET['modulo'];
			$servicioA = $_GET['servicio'];
			$nomSerA = $rs['nom_servicio'];
			$txtSer = '/ Servicio: <b class="text_rosa">'.$nomSerA.'</b>';
			$thNomSerA = '('.$nomSerA.')';

			# lista roles que tienen asociado este servicio:
			$objMetodo->set('',$_GET['servicio']); # pasa el servicio
			
			if( $rsRol = $objMetodo->listar() ){ # Existen datos
				for($i=0; $i<count($rsRol); $i++) { 
					array_push($arrRoles, $rsRol[$i]['codN']);
					array_push($arrInc, $rsRol[$i]['inc']);
					array_push($arrMod, $rsRol[$i]['mod']);
					array_push($arrElm, $rsRol[$i]['elm']);
					array_push($arrCons, $rsRol[$i]['con']);
				}
				$arrRoles = implode($arrRoles, ',');
				$arrInc = implode($arrInc, ',');
				$arrMod = implode($arrMod, ',');
				$arrElm = implode($arrElm, ',');
				$arrCons = implode($arrCons, ',');
			}
		}
	}

	function crear_tabla(){
		global $rsRol;
		$num = 1;
		if( $rsRol != '' ){
			for ($i=0; $i<count($rsRol); $i++){
				$codN = $rsRol[$i]['codN'];
				$codS = $rsRol[$i]['codS'];
				$cInc = checked($rsRol[$i]['inc']);
				$cMod = checked($rsRol[$i]['mod']);
				$cElm = checked($rsRol[$i]['elm']);
				$cCons = checked($rsRol[$i]['con']);
				echo '<tr>';
					echo '<td>'.$num.'</td>';
					echo '<td id="tdRol'.$codN.'" class="text_left"><b>'.$rsRol[$i]['nomN'].'</b></td>';
					echo '<td><input type="checkbox" onclick="chekeo('.$codN.','.$codS.',1)" '.$cInc.'></td>';
					echo '<td><input type="checkbox" onclick="chekeo('.$codN.','.$codS.',2)" '.$cMod.'></td>';
					echo '<td><input type="checkbox" onclick="chekeo('.$codN.','.$codS.',4)" '.$cCons.'></td>';
					echo '<td><input type="checkbox" onclick="chekeo('.$codN.','.$codS.',3)" '.$cElm.'></td>';
					echo '<td align="center"><div onclick="W_eliminar('.$codN.','.$codS.')" class="acciones"><i class="icon-trash-empty"></i></div>';
				echo '</tr>';
				$num++;
			}
		}
	}

	function checked( $v ){
		$x = '';
		if( $v == '1' ){
			$x = 'checked';
		}
		return $x;
	}
?>