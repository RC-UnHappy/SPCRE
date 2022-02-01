<?php 
include_once('../MODELO/m_personal.php');
$objPersonal = new cls_personal();

include_once('../MODELO/m_usuario.php');
$objUsu = new cls_usuario;


function listar_usuarios(){ # Consulta a la BD 
	global $objUsu;
	$rs = $objUsu->listar();
	crea_lista($rs);
}

function crea_lista($rs){
	# imprime la tabla con los resultados, recibe como parametro la consulta de la BD
	if( count($rs)>0 ){ 
		$fila = 'fila1';
		$num = 1;
		for( $i=0; $i<count($rs); $i++ ){
			# VARIABLES
			$codper = $rs[$i]['codper'];
			$ced = $rs[$i]['ced'];
			$nomape = str_replace('%', "'", $rs[$i]['nom_ape']);
			$cargo = $rs[$i]['car'];
			$funcion = $rs[$i]['fun'];
			$cod_nvl = $rs[$i]['cod_nvl'];
			$nivel = $rs[$i]['nvl'];
			$estatus = $rs[$i]['sta'];

			switch($estatus){ # estatus
				case 'I':
					$sta = '<div class="td_rojo">Inactivo</div>';
					break;

				case 'A':
					$sta = '<div class="td_verde">Activo</div>';
					break;

				case 'B':
					$sta = '<div class="td_verde">Bloqueado</div>';
					break;
			}

			$eveMod	= "modificar($num ,$cod_nvl,'".$estatus."')";

			# CELDAS
			echo '<tr class="clsTr fila '.$fila.'" id="fila-'.$num.'">';
				echo '<td class="text_bold">'.$num.'</td>';
				echo '<td>'.$ced.'</td>';
				echo '<td class="text_left">'.$nomape.'</td>';
				echo '<td>'.$cargo.'</td>';
				echo '<td>'.$funcion.'</td>';
				echo '<td>'.$nivel.'</td>';
				echo '<td>'.$sta.'</td>';

				echo '<td align="center"><div onclick="'.$eveMod.'" class="acciones"><i class="icon-edit"></i><div class="info">Modificar</div></div></td>';
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

if( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
	// if( isset($_POST['ope']) ){

	// 	switch ($_POST['ope']) {

	// 		case 'reg':
	// 			if( $rsPer = consultar_personal($_POST['tipo_doc'],$_POST['cedula']) ){
	// 				$objReposo->set($rsPer['cod_per'],$_POST['fecha_desde'],$_POST['fecha_hasta'],$_POST['descripcion']);
	// 				if( $objReposo->incluir() ){
	// 					header('location: ../VISTA/?ver=reposo_personal&ope=inc');
	// 				}
	// 				else{
	// 					header('location: ../VISTA/?ver=reposo_personal&error=inc');
	// 				}
	// 			}
	// 			else{
	// 				echo 'no existe el personal';
	// 			}
	// 			break;
			
	// 		case 'mod':
	// 			if( $rsPer = consultar_personal($_POST['tipo_doc'],$_POST['cedula']) ){
	// 				$objReposo->set_codigo($_POST['codrep']);
	// 				$objReposo->set($rsPer['cod_per'],$_POST['fecha_desde'],$_POST['fecha_hasta'],$_POST['descripcion']);
	// 				$objReposo->modificar();
	// 				header('location: ../VISTA/?ver=reposo_personal&ope=mod');
	// 			}
	// 			break;

	// 		// case 'elm':
	// 		// 	# code...
	// 		// 	break;
	// 	}
	// }
	if( isset($_POST['listar']) ){
		$rs = $objUsu->listar( $_POST['desde'],$_POST['mostrar'],$_POST['nivel'],$_POST['estatus']);
		echo $objUsu->filas.'%'; 
		crea_lista($rs);
	}

	if( isset($_POST['filtrar']) ){
		$objUsu->set_filtro( $_POST['filtro'] );
		$rs = $objUsu->filtrar($_POST['desde'],$_POST['mostrar'],$_POST['nivel'],$_POST['estatus']);
		echo $objUsu->filas.'%'; 

		if( $objUsu->filas > 0 && !$rs ){ 
			$rs = $objUsu->filtrar( 0, $_POST['mostrar']); # desde = 0;
			crea_lista($rs, true);
		}
		else{
			crea_lista($rs);
		}
	}

	if( isset($_POST['ajax_personal']) ){ # para completar los datos del formulario reposo
		if( $rs = consultar_personal( $_POST['tipo_doc'], $_POST['cedula'])){
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

function listar_niveles_usuario(){
	include_once('../MODELO/m_rol.php');		
	$obj = new cls_rol();
	$obj->comboRoles();
}

function javascript($arg){
	echo '<script type="text/javascript">'.$arg.'</script>';
}

function paginas($pag_actual=1, $limite=15){ # Numeracion de páginas
	global $objUsu;
	$total = $objUsu->filas; # Ej: 30 filas
	$paginas = ceil($total/$limite); # redondea hacia arriba
	$items = 5; # cantidad de items_pag a mostrar

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
	javascript("total_filas=parseInt(".$total.")");
}


