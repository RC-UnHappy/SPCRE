<?php 
date_default_timezone_set('America/Caracas');
setlocale(LC_TIME, 'spanish'); setlocale(LC_TIME, 'es_ES.UTF-8');

include_once('../MODELO/m_a_escolar.php');
$objAesc = new cls_a_escolar();

include_once('../MODELO/m_dias_habiles.php');
$objDias = new cls_dia_habil();

include_once('../MODELO/m_asistencia.php');
$objAsis = new cls_asistencia();

include_once('../MODELO/m_personal.php');
$objPersonal = new cls_personal();

$cargar_asistencia = false;
if( $rsAesc = $objAesc->aesc_actual() ) # Existe año escolar activo?
{
	$objDias->set('',$rsAesc['cod_periodo']);
	if( $rsDiasHbl = $objDias->consultar_dias_activos() ){ # Existen días hábiles en el año escolar
		$cargar_asistencia = true;
	}
}

# Funciones >>>>>>
function listar_personal(){
	global $objPersonal, $objAsis;
	if( isset($_GET['marcacion']) && !empty($_GET['marcacion']) ){
		$objAsis->set_marcacion($_GET['marcacion']);
	}
	if( isset($_GET['dia']) && !empty($_GET['dia']) ){
		$dia = $_GET['dia'];
	}
	else if( !isset($_GET['dia']) ){
		$dia = date('d-m-Y');
	}
	$objAsis->set_dia_habil($dia);
	$rs = $objAsis->listar_personal(); # se obtienen los registros
	crea_lista($rs,'',$dia);
}

function crea_lista($rs,$pagina=false,$dia_habil=null){ # lista la tabla
	#global $sI,$sM,$sC,$sE;
	#echo $dia_habil;
	if( count($rs) > 0){ # filas mayor a 0
		$fila = 'fila1';
		$num = 1;
		for($i=0; $i<count($rs); $i++){
			$cod_per = $rs[$i]['cod_per'];
			$ced = $rs[$i]['ced'];
			$nom_ape = str_replace('%', "'", $rs[$i]['nom_ape']); 
			$car = $rs[$i]['car'];
			$asis = $rs[$i]['asis'];
			$hora = $rs[$i]['hora'];
			$obs = $rs[$i]['obs'];
			# selected
			$selA = '';
			$selI = '';
			$selJ = '';

			if($asis == 'A'){
				$selA = 'selected';
			}
			else if($asis == 'I'){
				$selI = 'selected';
			}
			else if($asis == 'J'){
				$selJ = 'selected';
			}
			# permisos o reposos
			$boton = "<div class='acciones' onclick='registrar({$cod_per})'><i class='icon-plus'></i> Registrar</div>";
			$dsbl = ''; # campo disabled
			if( consultar_reposo($cod_per,$dia_habil) ){
				$boton = '<div class="td_amarillo">Reposo</div>';
				$dsbl = 'disabled=true';
			}
			// if( consultar_permiso($cod_per,$dia_habil) ){
			// 	$boton = '<div class="td_amarillo">Permiso</div>';
			// }

			// $status = 'Activo';
			// $cls_bg_sta = 'td_verde';

			// if( $sta == 'B' ){
			// 	$status = 'Bloqueado';
			// 	$cls_bg_sta = 'td_amarillo';
			// }
			// else if($sta=='I'){
			// 	$status = 'Inactivo';
			// 	$cls_bg_sta = 'td_rojo';
			// }
	
			echo '<tr class="fila '.$fila.'" id="fila-'.$cod_per.'">';
				echo '<td>'.$num.'</td>';
				echo '<td class="text_bold">'.$ced.'</td>';
				echo '<td class="text_left">'.$nom_ape.'</td>';
				echo '<td>'.strtoupper($car).'</td>';
				echo '<td>
					<select class="input selEstatus" id="inputEstatus-'.$cod_per.'" '.$dsbl.'>
						<option value="0">SELECCIONAR</option>
						<option value="A" '.$selA.'>Asistente</option>
						<option value="I" '.$selI.'>Inasistente</option>
					</select>
				</td>';
				echo '<td><input type="text" placeholder="00:00:00" maxlength="8" class="input text_center hora" id="inputHora-'.$cod_per.'" onkeypress="return solo_numeros()" onkeydown="escribir_hora(this)" value="'.$hora.'" '.$dsbl.'></td>';
				echo '<td><input type="text" class="input obs" id="inputObs-'.$cod_per.'" value="'.$obs.'" '.$dsbl.'></td>';
				echo "<td>{$boton}</td>";
				// else{
				// 	if($sM == '1'){
				// 		#echo $nvl;
				// 		if($nvl >= $_SESSION['vsn_nivel'] ){
				// 			echo '<td align="center"><a href="?Personal=visualizar&cedula='.$tdoc.'-'.$ced.'"><div class="acciones"><i class="icon-edit"></i><div class="info">Modificar</div></div></a></td>';
				// 		}
				// 		else{
				// 			echo '<td>---</td>';
				// 		}
				// 	}
				// 	else{
				// 		echo '<td>---</td>';
				// 	}
				// }
			echo '</tr>';
			if( $fila == 'fila1'){
				$fila = 'fila2';	
			}
			else{
				$fila = 'fila1';
			}
			$num++;
		}
	}else{
		echo'<tr><td colspan="8">Sin resultados</td></tr>';
	}
	if( $pagina == true ){
		echo '%1';
	}
}

function paginas($pag_actual=1, $limite=15){ # Numeracion de páginas
	global $objAsis;
	$total = $objAsis->filas; # Ej: 30 filas
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

function javascript($arg){
	echo '<script type="text/javascript">'.$arg.'</script>';
}

function comboSelect_meses(){
	$meses = array(
		'mes' => array('SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE','ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO'),
		'numero' => array('09','10','11','12','01','02','03','04','05','06','07','08')
	);

	for ($i=0; $i < count($meses['mes']); $i++) { 
		$sel = '';
		if( isset($_GET['mes']) && !empty($_GET['mes']) && $_GET['mes'] == $meses['numero'][$i]){ 
			$sel = 'selected';
		}
		# Mes actual
		else if( date('m') == $meses['numero'][$i] ){
			$sel = 'selected';
		}
		echo '<option value="'.$meses['numero'][$i].'" '.$sel.'>'.$meses['mes'][$i].'</option>';
	}
}

function comboSelect_diasHabiles($mes_x=null){
	# mes_x = cuando se clickea mes se ejecuta un ajax, mes_x toma ese valor para imprimirlos en el campo select de dias habiles
	global $objDias, $rsAesc;
	$objDias->set('',$rsAesc['cod_periodo']);

	if( $mes_x != null ){
		$mes = $mes_x;
	}
	else if( isset($_GET['mes']) && !empty($_GET['mes']) ){
		$mes = $_GET['mes'];
	}
	else if( !isset($_GET['mes']) ){
		# mes actual
		$mes = date('m');
	}

	$rs = $objDias->consultar_dias_mes($mes);
	for ($i=0; $i <count($rs); $i++) { 
		$sel = '';
		$dia = date('d-m-Y', strtotime($rs[$i]['cod_diahbl']));

		if( isset($_GET['dia']) && !empty($_GET['dia']) ){
			if( $_GET['dia'] == $dia ){
				$sel = 'selected';
			}
		}
		else if( !isset($_GET['dia']) && date('d-m-Y') == $dia  ){
			if( $mes_x == null ){
				$sel = 'selected';
			}
		}
		echo "<option value='{$dia}' ".$sel.">{$dia}</option>";
	}
}

function comboSelect_cargos(){
	include_once('../MODELO/m_cargo.php');
	$obj = new cls_cargo();
	$rs = $obj->consultar();
	for ($i=0; $i < count($rs) ; $i++) { 
		$nom = mb_strtoupper($rs[$i]['nom']); 
		echo "<option value='{$rs[$i]['cod']}'>{$nom}</option>";
	}
}

# METHOD POST
if( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
	if( isset($_POST['ajax'])){
		if( isset($_POST['listar']) ){
			$objAsis->set_dia_habil($_POST['dia_habil']);
			$objAsis->set_marcacion($_POST['marcacion']);
			$rs = $objAsis->listar_personal($_POST['desde'],$_POST['mostrar'],$_POST['cargo']);
			echo $objAsis->filas.'%'; 
			crea_lista($rs,'',$_POST['dia_habil']);
		}
		else if( isset($_POST['filtrar']) ){
			$objAsis->set_dia_habil($_POST['dia_habil']);
			$objAsis->set_marcacion($_POST['marcacion']);
			$objAsis->set_filtro($_POST['filtro']);
			$rs = $objAsis->filtrar_personal($_POST['desde'],$_POST['mostrar'],$_POST['cargo']);
			echo $objAsis->filas.'%'; 
			crea_lista($rs,'',$_POST['dia_habil']);
		}
		else if(isset($_POST['dias_habiles'])){
			comboSelect_diasHabiles($_POST['mes']);
		}
	}
	else{
		$objAsis->set_dia_habil($_POST['dia_habil']);
		$objAsis->set_marcacion($_POST['marcacion']);
		$datos = json_decode($_POST['datos'], true);
		$objAsis->set( $datos['codper'], $datos['sta'], $datos['hora'], $datos['obs'] );
		$objAsis->operar();
		header('location: ../VISTA/?ver=asistencia_personal&marcacion='.$_POST['marcacion'].'&mes='.$_POST['mes'].'&dia='.$_POST['dia_habil'].'&ope=true');
	}
}

function consultar_reposo($cod_per,$dia_habil){
	include_once('../MODELO/m_reposo.php');
	$obj = new cls_reposo();
	$obj->set_cod_per($cod_per);
	$obj->set_dia_habil($dia_habil);
	if( $rs = $obj->consultar_reposo() ){ # existen datos?
		return true;
	}
}

function consultar_permiso(){

}


?>