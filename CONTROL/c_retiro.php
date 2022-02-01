<?php
# Seguridad >>>>>>>
include_once('seguridadDinamica.php');
$rsMetodo = consultarMetodos('Retiro');
$sI = '1';
$sM = '1';
$sC = '1';
$sE = '1';

# Es diferente al administrador central
if( $_SESSION['vsn_nivel'] != 1 ){ 
	$sI = $rsMetodo['inc'];
	$sM = $rsMetodo['modf'];
	$sC = $rsMetodo['cons'];
	$sE = $rsMetodo['elm'];
}
# Seguridad <<<<<< 

function consultar_estudiante($ced){ 
	# consulta los datos del estudiante
	include_once('../MODELO/m_estudiante.php');
	$objEst = new cls_estudiante();
	$objEst->set_cedEsc(substr($ced, 0,1),substr($ced, 2));
	if( $rs = $objEst->consultar_estudiante() ){
		return $rs;
	}
}
function consultar_ultInsc($ced){
	# consulta última inscripción del estudiante
	include_once('../MODELO/m_inscripcion.php');
	$objInsc = new cls_inscripcion();
	$objInsc->set_CE($ced);
	if( $rs = $objInsc->consultar_ult_A() ){
		return $rs;
	}
}

# VARIABLES DE AÑO ESCOLAR
$cod_AESC = ''; 
$AESC = '';
$txtAESC = '';
$estatus_AESC = '';

if( $_SERVER['REQUEST_METHOD'] != 'POST' ){
	include_once('../MODELO/m_a_escolar.php');
	$objAesc = new cls_a_escolar();

	if( isset($_GET['periodo']) ){
		# Consulta año escolar a buscar desde el formulario
		$objAesc->set_periodo($_GET['periodo']);
		$rs = $objAesc->consultar();
		$cod_AESC = $rs['cod_periodo'];
		$AESC = $_GET['periodo'];
		$estatus_AESC = $rs['estatus'];
		$txtAESC = '<b class="text_rosa">Año escolar:</b> ('.$AESC.')';
	}
	else{
		# Consulta ultimo año escolar activo
		if( $rs = $objAesc->ultimo_aesc() ){
			$cod_AESC = $rs['cod_periodo'];
			$AESC = $rs['periodo'];
			$estatus_AESC = $rs['estatus'];
			$txtAESC = '<b class="text_rosa">Año escolar:</b> ('.$AESC.')';
		}
	}
}

else if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

	if( isset($_POST['ajax']) ){
		if( isset($_POST['ce']) ){ # Datos del estudiante
			if( $rs = consultar_estudiante($_POST['ce']) ){
				if( $rs['estatus'] == '3' || $rs['estatus'] == '4'){
					echo '2'; # error 2 = estudiante ya egresado. 3=retirado, 4=graduado
				}
				else if( $rsInsc = consultar_ultInsc($_POST['ce']) ){ # ultima inscripcion activa
					echo $rs['nom1'].' '.$rs['nom2'].'%';
					echo $rs['ape1'].' '.$rs['ape2'].'%';
					echo $rsInsc['grado'].'° "'.$rsInsc['letra'].'"';
				}
				else{
					echo '3'; # error 3 = no existe una inscripción
				}
			}
			else{
				echo '1'; # error 1 = no existe
			}
		}
		else{ # Lista de retiros
			include_once('../MODELO/m_egreso.php');
			$obj = new cls_egreso();
			$obj->set_codPeriodo($_POST['periodo']);
			if( $_POST['grado'] == 'T' ){ # todos los grados en el año escolar
				$rs = $obj->listar();
			}
			else{
				$rs = $obj->filtrar($_POST['grado']);
			}	
			crea_lista($rs,$_POST['eAesc']);
		}
	}

	else if( $_POST['ope']){
		$CE = $_POST['tipo_doc'].'-'.$_POST['ced'];
		$fecha = $_POST['fecha'];
		$causa = $_POST['causa'];
		$obs = $_POST['obs'];
		$codAesc = $_POST['codAesc'];

		include_once('../MODELO/m_estudiante.php');
		include_once('../MODELO/m_egreso.php');
		$objEst = new cls_estudiante();
		$objEgr = new cls_egreso();

		# consulta al estudiante
		$objEst->set_cedEsc($_POST['tipo_doc'],$_POST['ced']);
		if( $rs = $objEst->consultar_estudiante() ){
			$cod_est = $rs['cod_per'];

			switch ($_POST['ope']) {
				case 'reg':
					if( $rs['estatus'] == '3' || $rs['estatus'] == '4' ){ # ya se encuentra retirado
						header('location: ../VISTA/?ver=retiro&error=2');
					}
					else if( $rsInsc = consultar_ultInsc($_POST['tipo_doc'].'-'.$_POST['ced']) ){
						$objEgr->set_retiro($cod_est, $rsInsc['cod_insc'], $rsInsc['seccion'], $fecha, $causa, $obs);
						if( $objEgr->incluir() ){
							header('location: ../VISTA/index.php?ver=retiro&ope=1');
						}
					}
					else{
						header('location: ../VISTA/?ver=retiro&error=3');
					}
					break;
				
				case 'mod':
					$objEgr->set_codigo($_POST['cod']);
					$objEgr->set_retiro('','','', $fecha, $causa, $obs);
					$objEgr->modificar();
					header('location: ../VISTA/index.php?ver=retiro&ope=2');
					break;

				case 'elm':
					$objEgr->set_codigo($_POST['cod']);
					if( $objEgr->eliminar() ){
						header('location: ../VISTA/index.php?ver=retiro&ope=3');
					}
					else{
						header('location: ../VISTA/?ver=retiro&error=4');
					}
					break;
			}
		}
		else{
			header('location: ../VISTA/?ver=retiro&error=1');
		}
	}
}

function listar_retiros(){ # Consulta a la BD 
	include_once('../MODELO/m_egreso.php');
	global $cod_AESC, $estatus_AESC;
	$obj = new cls_egreso();
	$obj->set_codPeriodo($cod_AESC);
	$rs = $obj->listar();
	crea_lista($rs, $estatus_AESC);
}

function crea_lista($rs,$eAESC){
	global $sI,$sM,$sC,$sE;

	# imprime la tabla con los resultados, recibe como parametro la consulta de la BD
	if( count($rs)>0 ){ 
		$fila = 'fila1';
		$num = 1;
		for( $i=0; $i<count($rs); $i++ ){
			# variables para mostrar en tabla
			$cod = $rs[$i]['cod_retiro'];
			$codInsc = $rs[$i]['cod_insc'];
			$ced = $rs[$i]['cedula'];
			$nom = $rs[$i]['nom'];
			$ape = $rs[$i]['ape'];
			$gdo = $rs[$i]['grado'].'° "'.$rs[$i]['letra'].'"';
			$fecha = date('d/m/Y', strtotime($rs[$i]['fecha'])); 
			$causa = $rs[$i]['causa'];
			$obs = $rs[$i]['observacion'];
			# para pasar por parametro a funciones
			$fechaP = $rs[$i]['fecha']; 
			$obsP = str_replace(" ","_s_",$obs);	
			$eveMod	= "W_OpenMod(".$cod.",'".$ced."','".$fechaP."','".$causa."','".$obsP."')";
			$eveElm = "W_eliminar(".$cod.",'".$ced."')";

			# CELDAS
			echo '<tr class="clsTr fila '.$fila.'">';
				echo '<td class="text_bold">'.$num.'</td>';
				echo '<td>'.$ced.'</td>';
				echo '<td id="celNom'.$cod.'">'.$nom.'</td>';
				echo '<td id="celApe'.$cod.'">'.$ape.'</td>';
				echo '<td id="celGdo'.$cod.'">'.$gdo.'</td>';
				echo '<td>'.$fecha.'</td>';
				# acciones 
				if( $eAESC == 'C'){
					echo '<td>---</td>';
				}
				else if($eAESC == 'A'){
					echo '<td align="center">';
						if( $sC == '1' ){
							echo '<div class="acciones" onclick="'.$eveMod.'"><i class="icon-eye"></i><div class="info">Visualizar</div></div>';
						}
						if( $sE == '1' ){
							echo '<div class="acciones" onclick="'.$eveElm.'"><i class="icon-trash-empty"></i><div class="info">Eliminar</div></div>';
						}	
						if( $sC == '0' && $sE == '0' ){
							echo '---';
						}		
					echo '</td>';
				}	
			echo '</tr>';
			
			# ESTILO DE FILAS
			if( $fila == 'fila1'){
				$fila = 'fila2';	
			}
			else{
				$fila = 'fila1';
			}
			$num++;
		}
	}
	else{
		echo '<tr><td colspan="7">No se encontraron resultados</td></tr>';
	}
}

function paginas($pag_actual=1, $limite=15){ # Numeracion de páginas
	global $objServicio;
	$total = $objServicio->getFilas(); 
	$paginas = ceil($total/$limite); # redondea hacia arriba
	$items = 5; # (botones)

	if( $paginas > 1 ){ # existe mas de una página

		# El total de paginas no supera la cantidad de items a mostrar
		if( $paginas < $items ){ 
			for($i=1; $i<=$paginas; $i++){ // se recorre el total de paginas
				if( $pag_actual == $i ){
					echo '<div onclick="mostrar_pag(this,'.$i.')" class="item_pag actual">'.$i.'</div>';
				}
				else{
					echo '<div onclick="mostrar_pag(this,'.$i.')" class="item_pag">'.$i.'</div>';
				}
			}
		}
		# los 5 numeros siguientes de la pagina actual superan o es igual el total de paginas
		else if( $pag_actual+$items >= $paginas ){ 
			# solo imprime hasta el total de paginas ej: total_pag = 9, total_pag-items = 4, imprime: [4,5,6,7,8,9] llegando al limite y evita imprimir mas items
			for($i=$paginas-$items; $i<=$paginas; $i++){
				if( $pag_actual == $i ){
					echo '<div onclick="mostrar_pag(this,'.$i.')" class="item_pag actual">'.$i.'</div>';
				}
				else{
					echo '<div onclick="mostrar_pag(this,'.$i.')" class="item_pag">'.$i.'</div>';
				}
			}
		}
		# Imprime las 5 paginas siguientes de la pagina actual: ej: pag_actual = 2 imprime [2,3,4,5,6,7]
		else{ 
			for($i=$pag_actual; $i<=$pag_actual+$items; $i++){
				if( $pag_actual == $i ){
					echo '<div onclick="mostrar_pag(this,'.$i.')" class="item_pag actual">'.$i.'</div>';
				}else{
					echo '<div onclick="mostrar_pag(this,'.$i.')" class="item_pag">'.$i.'</div>';
				}
			}
		}
		# Botón Siguiente
		if( $pag_actual != $paginas ){
			echo '<label class="pag_AS" onclick="pag_siguiente()">Siguiente<i class="icon-angle-right"></i></label>';
		}
	}
	javascript("total_filas=parseInt(".$total.")"); # pasa valor a la variable de javascript
}

function javascript($arg){
	echo '<script type="text/javascript">'.$arg.'</script>';
}

?>