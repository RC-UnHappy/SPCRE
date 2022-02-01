<?php 
include_once('../MODELO/m_inscripcion.php');
include_once('../MODELO/m_seccion.php');
$objSec = new cls_seccion();
include_once('../MODELO/m_asistencia.php');
$objAsis = new cls_asistencia();

# datos de la seccion a consultar
$GETseccion = '';
$codSec = '';
$grado = '';
$letra = '';
$periodoEsc = '';
$ced_docente = '';
$nom_docente = '';
# inscripciones
$listaEst = '';
$total = '';
# Docente
$codDoc = ''; # seccion correspondiente
$secDoc = '';

# variables de control
$errorSeccion = false; # error que da cuando un usuario nivel docente no posee una seccion designada
$docenteSeccion = false; # docente posee seccion

# CONSULTA DEL ULTIMO AÑO ESCOLAR >>>>
$rsAESC = consultar_ultAESC();
$codAESC = $rsAESC['cod_periodo'];
$AESC = $rsAESC['periodo'];
$staAESC = $rsAESC['estatus'];  
# <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<


if( $_SERVER['REQUEST_METHOD'] == 'GET' ){
	if( $_SESSION['vsn_nivel'] == 4 ){ # usuario nivel Docente
		$objSec->set_periodo($codAESC);
		$objSec->set_docente($_SESSION['vsn_codPer']);
		if( !$rsSec = $objSec->buscar_docente_seccion() ){ # El docente tiene designada una seccion
			$errorSeccion = true;
		}
		else{
			#print_r($rsSec);
			consultar_seccion($rsSec['cod_seccion']);
			listar_estudiantes();
		}
	}

	else if( $_SESSION['vsn_nivel'] != 4 && isset($_GET['seccion']) && !empty($_GET['seccion']) ){
		consultar_seccion($_GET['seccion']);
		listar_estudiantes();
	}
}

function consultar_seccion($codigo){ # consulta los datos de la seccion
	global $objSec, $docenteSeccion;
	global $codSec, $grado, $letra, $periodoEsc,$ced_docente,$nom_docente;
 
	$objSec->set_codigo($codigo); # pasa el codigo de la seccion
	if( $rs = $objSec->consultar() ){
		$codSec = $rs['cod_seccion'];
		$grado = $rs['grado'];
		$letra = $rs['letra'];
		$periodoEsc = $rs['periodo'];
		$ced_docente = $rs['ced_docente'];
		$nom_docente = $rs['nom_docente'];
		$docenteSeccion = true;
	}
	else{
		header('location: ?error=404'); # sin resultados
	}
}

function listar_estudiantes($codSec=null, $diaHbl=null){
	global $listaEst, $total;
	if( $codSec == null && $diaHbl == null ){
		if( isset($_GET['seccion']) && !empty($_GET['seccion']) ){
			$codSec = $_GET['seccion'];
		}
		if( isset($_GET['dia']) && !empty($_GET['dia']) ){
			$diaHbl = $_GET['dia'];
		}
	}
	include_once('../MODELO/m_inscripcion.php');
	$objAsis = new cls_asistencia();
	$objAsis->set_dia_habil($diaHbl);
	$listaEst = $objAsis->listar_estudiantes($codSec);
	$total = count($listaEst);
}

function imprimir_lista( $rs ){
	$num = 1;
	for ($i=0; $i<count($rs); $i++){
		$asisA = '';
		$asisI = '';
		$asisJ = '' ;

		switch ( $rs[$i]['asis'] ) {
			case 'A': $asisA = 'selected'; break;
			case 'I': $asisI = 'selected'; break;
			case 'J': $asisJ = 'selected'; break;
		}

		echo '<tr id="per-'.$rs[$i]['cod_per'].'" class="fila_est" >';
		echo '<td>'.$num.'</td>';
		echo '<td class="text_center">'.$rs[$i]['ced'].'</td>';
		echo '<td class="align_left">'.$rs[$i]['nom_ape'].'</td>';
		echo '<td>
		<select class="input input_asis">
			<option value="0">SELECCIONAR</option>
			<option value="A" '.$asisA.'>ASISTENTE</option>
			<option value="I" '.$asisI.'>INASISTENTE</option>
			<option value="J" '.$asisJ.'>JUSTIFICADO</option>
		</select>
		</td>';
		echo '</tr>';
		$num++;
	}
}

function consultar_ultAESC(){ # ultimo año escolar
	include_once('../MODELO/m_a_escolar.php');
	$obj = new cls_a_escolar();
	$rs = $obj->ultimo_aesc();	
	return $rs;
}

function opSecciones($codAesc){
	include_once('../MODELO/m_seccion.php');
	$objSec = new cls_seccion();
	$rsSec = $objSec->set_periodo($codAesc);

	if($rsSec = $objSec->listar_sm()){
		for ($i=0;$i<count($rsSec);$i++) { 
			switch ($rsSec[$i]['gdo']) {
				case '1':$grado = '1ero';break;				
				case '2':$grado = '2do';break;
				case '3':$grado = '3ero';break;
				case '4':$grado = '4to';break;
				case '5':$grado = '5to';break;
				case '6':$grado = '6to';break;
			}
			echo '<option value="'.$rsSec[$i]['cod'].'">'.$grado.' - "'.$rsSec[$i]['lta'].'"</option>';
		}
	}
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
		echo '<option value="'.$meses['numero'][$i].'" '.$sel.'>'.$meses['mes'][$i].'</option>';
	}
}

function comboSelect_diasHabiles($mes=null){
	include_once('../MODELO/m_dias_habiles.php');
	$objDias = new cls_dia_habil();
	global $codAESC;
	$objDias->set('',$codAESC);

	if( $mes == null ){
		if( isset($_GET['mes']) && !empty($_GET['mes']) ){
			$mes = $_GET['mes'];
		}
	}

	$rs = $objDias->consultar_dias_mes($mes);
	for ($i=0; $i <count($rs); $i++) { 
		$sel = '';
		$dia = date('d-m-Y', strtotime($rs[$i]['cod_diahbl']));
		if( isset($_GET['dia']) && !empty($_GET['dia']) ){
			# $mes = $_GET['mes'];
			if( $_GET['dia'] == $dia ){
				$sel = 'selected';
			}
		}
		echo "<option value='{$dia}' ".$sel.">{$dia}</option>";
	}
}

# METHOD POST
if( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
	if( isset($_POST['ajax'])){
		if(isset($_POST['dias_habiles'])){
			comboSelect_diasHabiles($_POST['mes']);
		}
		else if(isset($_POST['listar'])){
			listar_estudiantes($_POST['seccion'],$_POST['dia']);
			imprimir_lista( $listaEst );
		}
	}
	else{
		$objAsis->set_dia_habil($_POST['dia_habil']);
		$datos = json_decode($_POST['datos'], true);
		// echo '<pre>';
		// print_r($datos);
		// echo '</pre>';
		for ($i=0; $i < count($datos); $i++) { 
			$objAsis->set( $datos['codper'][$i], $datos['sta'][$i], '', '' );
			$objAsis->operar();
		}
		header('location: ../VISTA/?ver=asistencia_estudiante&seccion='.$_POST['seccion'].'&mes='.$_POST['mes'].'&dia='.$_POST['dia_habil'].'&ope=true');
	}
}


?>