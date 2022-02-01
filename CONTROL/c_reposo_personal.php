<?php 
include_once('../MODELO/m_personal.php');
$objPersonal = new cls_personal();
include_once('../MODELO/m_reposo.php');
$objReposo = new cls_reposo();
date_default_timezone_set('America/Caracas');

if( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
	if( isset($_POST['ope']) ){

		switch ($_POST['ope']) {

			case 'reg':
				if( $rsPer = consultar_personal($_POST['tipo_doc'],$_POST['cedula']) ){
					$objReposo->set($rsPer['cod_per'],$_POST['fecha_desde'],$_POST['fecha_hasta'],$_POST['descripcion']);
					if( $objReposo->incluir() ){
						header('location: ../VISTA/?ver=reposo_personal&ope=inc');
					}
					else{
						header('location: ../VISTA/?ver=reposo_personal&error=inc');
					}
				}
				else{
					echo 'no existe el personal';
				}
				break;
			
			case 'mod':
				if( $rsPer = consultar_personal($_POST['tipo_doc'],$_POST['cedula']) ){
					$objReposo->set_codigo($_POST['codrep']);
					$objReposo->set($rsPer['cod_per'],$_POST['fecha_desde'],$_POST['fecha_hasta'],$_POST['descripcion']);
					$objReposo->modificar();
					header('location: ../VISTA/?ver=reposo_personal&ope=mod');
				}
				break;

			// case 'elm':
			// 	# code...
			// 	break;
		}
	}

	if( isset($_POST['ajax_personal']) ){ # para completar los datos del formulario reposo
		$objPersonal->set_identidad( $_POST['tipo_doc'], $_POST['cedula'] );
		if( $rs = $objPersonal->consultarPersonal() ){
			$tags = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE;
			$r = str_replace('%', "'", json_encode($rs, $tags)); 
			print_r($r);
		}
		else{
			echo 'false';
		}
	}

}

function consultar_personal($tipo_doc,$cedula){
	global $objPersonal;
	$objPersonal->set_identidad($tipo_doc,$cedula);
	if( $rs = $objPersonal->consultarPersonal() ){
		return $rs;
	}
}

function listar_reposos(){ # Consulta a la BD 
	global $objReposo;
	$rs = $objReposo->listar();
	crea_lista($rs);
}

function crea_lista($rs){
	# imprime la tabla con los resultados, recibe como parametro la consulta de la BD
	if( count($rs)>0 ){ 
		$fila = 'fila1';
		$num = 1;
		for( $i=0; $i<count($rs); $i++ ){

			// switch($rs[$i]['sta']){ # estatus
			// 	case 'I':
			// 		$estatus = '<div class="td_rojo">Inhabilitado</div>';
			// 		break;

			// 	case 'A':
			// 		$estatus = '<div class="td_verde">Habilitado</div>';
			// 		break;
			// }

			# VARIABLES
			$codper = $rs[$i]['cod_per'];
			$ced = $rs[$i]['cedula'];
			$nomape = $rs[$i]['nom_ape'];
			$cargo = $rs[$i]['nom_cargo'];
			$codrep = $rs[$i]['cod_reposo'];
			$fecha = $rs[$i]['fecha'];
			$desde = $rs[$i]['desde'];
			$hasta = $rs[$i]['hasta'];
			$desc = $rs[$i]['descripcion'];
			$estado = $sta = '<div class="td_verde">Activo</div>'; # estado del reposo
			$hoy = strtotime(date('d-m-Y'));
			#echo 'hoy: '.$hoy.' desde:' .strtotime($desde).' hasta: '.strtotime($hasta);
			
			if( $hoy < strtotime($desde) ){
				$estado = '<div class="td_amarillo">No iniciado</div>';
			}
			else if( $hoy > strtotime($hasta) ){
				$estado = '<div class="td_rojo">finalizado</div>';
			}

			# CELDAS
			echo '<tr class="clsTr fila '.$fila.'" id="reposo-'.$codrep.'">';
				echo '<td class="text_bold">'.$num.'</td>';
				echo '<td>'.$ced.'</td>';
				echo '<td class="text_left">'.$nomape.'</td>';
				echo '<td>'.$cargo.'</td>';
				echo '<td>'.$desde.'</td>';
				echo '<td>'.$hasta.'</td>';
				echo '<td>'.$desc.'</td>';
				echo '<td>'.$estado.'</td>';
				echo '<td align="center"><div onclick="modificar('.$codrep.')" class="acciones"><i class="icon-edit"></i><div class="info">Modificar</div></div>
				<div onclick="eliminar('.$codrep.')" class="acciones"><i class="icon-trash-empty"></i><div class="info">Eliminar</div></div></td>';
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
		echo '<tr><td colspan="8">Sin resultados</td></tr>';
	}
}

// function paginas($pag_actual=1, $limite=15){ # Numeracion de páginas
// 	global $objServicio;
// 	$total = $objServicio->getFilas(); 
// 	$paginas = ceil($total/$limite); # redondea hacia arriba
// 	$items = 5; # (botones)

// 	if( $paginas > 1 ){ # existe mas de una página

// 		# El total de paginas no supera la cantidad de items a mostrar
// 		if( $paginas < $items ){ 
// 			for($i=1; $i<=$paginas; $i++){ // se recorre el total de paginas
// 				if( $pag_actual == $i ){
// 					echo '<div onclick="mostrar_pag(this,'.$i.')" class="item_pag actual">'.$i.'</div>';
// 				}
// 				else{
// 					echo '<div onclick="mostrar_pag(this,'.$i.')" class="item_pag">'.$i.'</div>';
// 				}
// 			}
// 		}
// 		# los 5 numeros siguientes de la pagina actual superan o es igual el total de paginas
// 		else if( $pag_actual+$items >= $paginas ){ 
// 			# solo imprime hasta el total de paginas ej: total_pag = 9, total_pag-items = 4, imprime: [4,5,6,7,8,9] llegando al limite y evita imprimir mas items
// 			for($i=$paginas-$items; $i<=$paginas; $i++){
// 				if( $pag_actual == $i ){
// 					echo '<div onclick="mostrar_pag(this,'.$i.')" class="item_pag actual">'.$i.'</div>';
// 				}
// 				else{
// 					echo '<div onclick="mostrar_pag(this,'.$i.')" class="item_pag">'.$i.'</div>';
// 				}
// 			}
// 		}
// 		# Imprime las 5 paginas siguientes de la pagina actual: ej: pag_actual = 2 imprime [2,3,4,5,6,7]
// 		else{ 
// 			for($i=$pag_actual; $i<=$pag_actual+$items; $i++){
// 				if( $pag_actual == $i ){
// 					echo '<div onclick="mostrar_pag(this,'.$i.')" class="item_pag actual">'.$i.'</div>';
// 				}else{
// 					echo '<div onclick="mostrar_pag(this,'.$i.')" class="item_pag">'.$i.'</div>';
// 				}
// 			}
// 		}
// 		# Botón Siguiente
// 		if( $pag_actual != $paginas ){
// 			echo '<label class="pag_AS" onclick="pag_siguiente()">Siguiente<i class="icon-angle-right"></i></label>';
// 		}
// 	}
// 	javascript("total_filas=parseInt(".$total.")"); # pasa valor a la variable de javascript
// }

function javascript($arg){
	echo '<script type="text/javascript">'.$arg.'</script>';
}

?>
