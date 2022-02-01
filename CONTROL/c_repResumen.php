<?php 
# AÑO ESCOLAR
function consultar_ultAESC(){ # ultimo año escolar
	include_once('../MODELO/m_a_escolar.php');
	$obj = new cls_a_escolar();
	$rs = $obj->ultimo_aesc();	
	return $rs;
}
function consultar_AESC($periodo){
	include_once('../MODELO/m_a_escolar.php');
	$obj = new cls_a_escolar();
	$obj->set_periodo($periodo);
	if( $rs = $obj->consultar() ){
		return $rs;
	}
}
function consultar_conf($codAesc){
	include_once('../MODELO/m_a_escolar.php');
	$obj = new cls_a_escolar();
	$obj->set_codigo( $codAesc );
	$rs = $obj->consultar_configuracion();
	return $rs;
}
# ESTADISTICA DE SESCCIONES
function consultar_secciones($aesc){
	include_once('../MODELO/m_seccion.php');
	$obj = new cls_seccion();
	$obj->set_periodo($aesc);
	$rs = $obj->listar_sm();
	return $rs;
}
function matricula_seccion_estadistica($tipo,$aesc){
	# tipo = inicial, final, mensual
	include_once('../MODELO/m_seccion.php');
	$obj = new cls_seccion();
	$obj->set_periodo($aesc);
	# en construcción...
	// switch ($tipo) {
	// 	case 'I':
	// 		$rs = $obj->
	// 		break;
		
	// 	case 'I':
	// 		# code...
	// 		break;

	// 	case 'F':
	// 		# code...
	// 		break;
	// }
}
# RESUMEN DEL RENDIMIENTO
function consultar_seccion($codSeccion){
	include_once('../MODELO/m_seccion.php');
	$objSec = new cls_seccion();
	$objSec->set_codigo($codSeccion);
	$rsSec = $objSec->consultar();
	return $rsSec;
}

function consultar_inscritos($codSeccion){
	include_once('../MODELO/m_seccion.php');
	$objSec = new cls_seccion();
	$objSec->set_codigo($codSeccion);
	$rsSec = $objSec->matricula_seccion();
	return $rsSec;
}

function consultar_nota($codInsc){
	include_once('../MODELO/m_notas.php');
	$obj = new cls_notas();
	$obj->setInsc($codInsc);
	return $obj->consultar_notaFinal();
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

function sinResultados($error='1'){
	header('Location: ../VISTA/?ver=resumen&error='.$error);
}

function javascript( $expresion ){ # ejecuta funciones de javascript
	echo "<script type='text/javascript'>".$expresion."</script>";
}

# FUNCIONES DE EXTRACCIÓN EN REGISTROS DE ESTADISTICA
# extrae datos de un arreglo (sencilla)
function extraer($arr,$edad=null,$sexo='all',$grado=null){
	$cont = 0;
	for ($i=0; $i<count($arr); $i++) { 
		if( $arr[$i]['edad']==$edad && $arr[$i]['sexo']==$sexo && $arr[$i]['grado']==$grado ){
			$cont++;
		}
	}
	if( $cont > 0 ){
		return $cont;
	}
}
# sub total de sexos por grados
function extraer_subTotalSexo($arr,$sexo,$grado){
	$cont = 0;
	for ($i=0; $i<count($arr); $i++) { 
		if( $arr[$i]['sexo']==$sexo && $arr[$i]['grado']==$grado ){
			$cont++;
		}
	}
	return $cont;
}
# sub total de sexos por edad
function extraer_subTotalEdad($arr,$sexo,$edad){
	$cont = 0;
	for ($i=0; $i<count($arr); $i++) { 
		if( $arr[$i]['sexo']==$sexo && $arr[$i]['edad']==$edad ){
			$cont++;
		}
	}
	return $cont;
}
function extraer_totalSexo($arr,$sexo){
	$cont = 0;
	for ($i=0; $i<count($arr); $i++) { 
		if( $arr[$i]['sexo']==$sexo ){
			$cont++;
		}
	}
	return $cont;
}
# total de estudiantes por grado
function extraer_total($arr, $grado){
	$cont = 0;
	for ($i=0; $i<count($arr); $i++) { 
		if( $arr[$i]['grado']==$grado ){
			$cont++;
		}
	}
	return $cont;
}
function extraer_extranjeros($arr,$grado){
	$cont = 0;
	for ($i=0; $i<count($arr); $i++){ 
		if( substr($arr[$i]['CE'],0,1)=='E' && $arr[$i]['grado']==$grado ){
			$cont++;
		}
	}
	return $cont;
}
function extraer_subTotalExt($arr){
	$cont = 0;
	for ($i=0; $i<count($arr); $i++) { 
		if( substr($arr[$i]['CE'],0,1)=='E' ){
			$cont++;
		}
	}
	return $cont;
}
function extraer_seccion($arr,$grado){
	$cont = 0;
	for ($i=0; $i<count($arr); $i++) { 
		if( substr($arr[$i]['gdo'],0,1)==$grado ){
			$cont++;
		}
	}
	return $cont;
}

# AJAX
if( isset($_POST['listarSec']) ){
	opSecciones($_POST['periodo']);
}

# VISTA f_repResumen.php
$AESC = '';
$codAESC = '';
$rsAESC = consultar_ultAESC();
$codAESC = $rsAESC['cod_periodo'];
$AESC = $rsAESC['periodo'];
$mesActual = date('m');
# >>>


#SILENCIAR ERRORES
// ob_start();
// error_reporting(E_ALL & ~E_NOTICE);
// ini_set('display_errors', 0);
// ini_set('log_errors', 1);

# >>>>>>>>
if( isset($_GET['tipo']) ){
	switch ($_GET['tipo']) {
		// resumen Estadistico
		case '1':
			if( $rsAesc = consultar_AESC( $_GET['aesc'] ) ){
				$tipoResumen = $_GET['tipoResumen1'];
				$aesc = $_GET['aesc'];
				$rsSec = consultar_secciones($rsAesc['cod_periodo']);

				include_once('../MODELO/my_tcpdf.php');
				$pdf = new MYPDF('L', PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
				$pdf->SetMargins(9, 30, 9); 
				$pdf->getPlantel();

				switch ($tipoResumen) {
					case 'I':
						$pdf->AddPage(); # pag 1
						$rsConf = consultar_conf($rsAesc['cod_periodo']);

						# fechas de configuracion de matricula inicial
						$fmiD = $rsConf['fmi_desde'];
						$fmiH = $rsConf['fmi_hasta'];

						include_once('../MODELO/m_inscripcion.php');
						$objIncs = new cls_inscripcion();
						$objIncs->set_Periodo($rsAesc['cod_periodo']);
						# con condicion promovido y traslado / con condicion repitientes
						$resN = $objIncs->estadistica_inicial($fmiD, $fmiH);
						#$resR = $objIncs->estadistica_inicial($fmiD, $fmiH,'R');

						$estilos = 
						'<style type="text/css">
							.bold{ font-weight:bold;}
							.gris1{ background-color: #dddfeb; }
							.gris2{ background-color: #b7b9cc;}
							.gris3{ background-color: #858796;}
						</style>';

						$TxTplantel = $estilos.'
						<table align="center" border="1" cellpadding="2" cellspacing="0">
						<tr>
							<th class="gris1 bold" width="460px">Nombre del Plantel ó Servicio</th>
							<th class="gris1 bold" width="140px">Código Estadístico</th>
							<th class="gris1 bold" width="140px">Código DEA</th>
							<th class="gris1 bold" width="140px">Año escolar</th>
						</tr>
						<tr>
							<td>'.$pdf->nom_escuela.'</td>
							<td>'.$pdf->codestco.'</td>
							<td>'.$pdf->coddea.'</td>
							<td>'.$rsAesc["periodo"].'</td>
						</tr>
						</table>';

						$cabecera = $estilos.
						'<tr>
							<td class="gris1 bold" align="center" colspan="21">Educación Primaria: Matrícula Inicial de Estudiantes por Grado de Estudios, Número de Secciones, Sexo y Edad. Años escolar ('.$rsAesc["periodo"].')</td>
							<td class="gris1 bold" rowspan="3" align="center">N° de Docentes por Grado</td>
						</tr>
						<tr>
							<td align="center" width="40" class="bold gris1" rowspan="2">Grados</td>
							<td align="center" width="50" class="bold gris1" rowspan="2">N° de Secciones</td>
							<td align="center" width="40" class="bold gris1" rowspan="2">Sexo</td>
							<td align="center" class="bold gris1" colspan="15">Edad (Años)</td>
							<td align="center" class="bold gris1" rowspan="2">Sub Total</td>
							<td align="center" width="37" class="bold gris1" rowspan="2">Total</td>
							<td align="center" width="50" class="bold gris1" rowspan="2"> N° de extranjeros</td>
						</tr>
						<tr>	
							<td align="center" class="gris1 bold">6</td>
							<td align="center" class="gris1 bold">7</td>
							<td align="center" class="gris1 bold">8</td>
							<td align="center" class="gris1 bold">9</td>
							<td align="center" class="gris1 bold">10</td>
							<td align="center" class="gris1 bold">11</td>
							<td align="center" class="gris1 bold">12</td>
							<td align="center" class="gris1 bold">13</td>
							<td align="center" class="gris1 bold">14</td>
							<td align="center" class="gris1 bold">15</td>
							<td align="center" class="gris1 bold">16</td>
							<td align="center" class="gris1 bold">17</td>
							<td align="center" class="gris1 bold">18</td>
							<td align="center" class="gris1 bold">19</td>
							<td align="center" class="gris1 bold">20 o más</td>	
						</tr>'; 

						$datos = '';
						$grado = 1;
						$t = 'ro';
						#in_array(needle, haystack)
						for ($i=0; $i<7; $i++){ 
							if( $i >= 3){
								$t = 'to';
							}
							if( $i == 6 ){
								# Subtotales
								$datos = $datos.
								'<tr>
									<td rowspan="2" colspan="2" align="center" class="gris3 bold">Sub Totales</td>
									<td align="center" class="gris2 bold">Mas</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',6).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',7).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',8).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',9).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',10).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',11).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',12).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',13).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',14).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',15).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',16).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',17).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',18).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',19).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',20).'</td>
									<td class="gris1" align="center">'.extraer_totalSexo($resN,'M').'</td>
									<td class="gris1" align="center" rowspan="2">'.count($resN).'</td>
									<td class="gris1" align="center" rowspan="2">'.extraer_subTotalExt($resN).'</td>
									<td class="gris1" align="center" rowspan="2">'.count($rsSec).'</td>
								</tr>
								<tr>
									<td align="center" class="gris2 bold">Fem</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',6).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',7).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',8).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',9).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',10).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',11).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',12).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',13).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',14).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',15).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',16).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',17).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',18).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',19).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',20).'</td>
									<td class="gris1" align="center">'.extraer_totalSexo($resN,'F').'</td>
								</tr>';
							}
							else{
								$datos = $datos.
								'<tr>
									<td rowspan="2" align="center" class="bold">'.$grado.$t.'</td>
									<td rowspan="2" align="center">'.extraer_seccion($rsSec, $grado).'</td>
									<td align="center" class="gris2 bold">Mas</td>
									<td align="center">'.extraer($resN,6,'M',$grado).'</td>
									<td align="center">'.extraer($resN,7,'M',$grado).'</td>
									<td align="center">'.extraer($resN,8,'M',$grado).'</td>
									<td align="center">'.extraer($resN,9,'M',$grado).'</td>
									<td align="center">'.extraer($resN,10,'M',$grado).'</td>
									<td align="center">'.extraer($resN,11,'M',$grado).'</td>
									<td align="center">'.extraer($resN,12,'M',$grado).'</td>
									<td align="center">'.extraer($resN,13,'M',$grado).'</td>
									<td align="center">'.extraer($resN,14,'M',$grado).'</td>
									<td align="center">'.extraer($resN,15,'M',$grado).'</td>
									<td align="center">'.extraer($resN,16,'M',$grado).'</td>
									<td align="center">'.extraer($resN,17,'M',$grado).'</td>
									<td align="center">'.extraer($resN,18,'M',$grado).'</td>
									<td align="center">'.extraer($resN,19,'M',$grado).'</td>
									<td align="center">'.extraer($resN,20,'M',$grado).'</td>
									<td align="center">'.extraer_subTotalSexo($resN,'M',$grado).'</td>
									<td align="center" rowspan="2">'.extraer_total($resN, $grado).'</td>
									<td rowspan="2" align="center">'.extraer_extranjeros($resN,$grado).'</td>
									<td rowspan="2" align="center">'.extraer_seccion($rsSec, $grado).'</td>
								</tr>
								<tr>
									<td align="center" class="gris2 bold">Fem</td>
									<td align="center">'.extraer($resN,6,'F',$grado).'</td>
									<td align="center">'.extraer($resN,7,'F',$grado).'</td>
									<td align="center">'.extraer($resN,8,'F',$grado).'</td>
									<td align="center">'.extraer($resN,9,'F',$grado).'</td>
									<td align="center">'.extraer($resN,10,'F',$grado).'</td>
									<td align="center">'.extraer($resN,11,'F',$grado).'</td>
									<td align="center">'.extraer($resN,12,'F',$grado).'</td>
									<td align="center">'.extraer($resN,13,'F',$grado).'</td>
									<td align="center">'.extraer($resN,14,'F',$grado).'</td>
									<td align="center">'.extraer($resN,15,'F',$grado).'</td>
									<td align="center">'.extraer($resN,16,'F',$grado).'</td>
									<td align="center">'.extraer($resN,17,'F',$grado).'</td>
									<td align="center">'.extraer($resN,18,'F',$grado).'</td>
									<td align="center">'.extraer($resN,19,'F',$grado).'</td>
									<td align="center">'.extraer($resN,20,'F',$grado).'</td>
									<td align="center">'.extraer_subTotalSexo($resN,'F',$grado).'</td>
								</tr>';
							}
							$grado++;
						}

						$tabla = '<table border="1" cellspacing="0" cellpadding="2">'.$cabecera.$datos.'</table>';

						$pdf->Ln(10);
						$pdf->SetFont('Helvetica','',10);
						$pdf->writeHTML($TxTplantel, true, false, false, false, '');
						$pdf->SetFont('Helvetica','',9);
						$pdf->writeHTML($tabla, true, false, false, false, '');
						
						$pdf->SetFont('Helvetica','',12);
						$firma = $estilos.'<table border="0" cellspacing="1" cellpadding="2">
							<tr>
								<td class="bold" width="50px">Fecha: </td>
								<td width="90px">'.date('d/m/Y').'</td>
								<td width="70px" class="bold">Director(a):</td>
								<td>'.$pdf->nom_director.'</td>
								<td width="25px" class="bold">C.I:</td>
								<td>'.$pdf->ci_director.'</td>
								<td width="50px" class="bold">Firma:</td>
								<td width="250px" class="b">___________________</td>
								<td width="110px" class="bold">Sello del Plantel</td>
							</tr>
						</table>';
						$pdf->Ln(25);
						$pdf->writeHTML($firma, true, false, false, false, '');

						# PAG 2 ---------------------------
						// $pdf->AddPage(); # pag 2
		
						// $cabecera = $estilos.
						// '<tr>
						// 	<td class="gris1 bold" align="center" colspan="21">Educación Primaria: Matrícula Inicial de Estudiantes Repitientes por Grado de Estudios, Número de Secciones, Sexo y Edad. Años escolar ('.$rsAesc["periodo"].')</td>
						// 	<td class="gris1 bold" rowspan="3" align="center">N° de Docentes por Grado</td>
						// </tr>
						// <tr>
						// 	<td align="center" width="40" class="bold gris1" rowspan="2">Grados</td>
						// 	<td align="center" width="50" class="bold gris1" rowspan="2">N° de Secciones</td>
						// 	<td align="center" width="40" class="bold gris1" rowspan="2">Sexo</td>
						// 	<td align="center" class="bold gris1" colspan="15">Edad (Años)</td>
						// 	<td align="center" class="bold gris1" rowspan="2">Sub Total</td>
						// 	<td align="center" width="37" class="bold gris1" rowspan="2">Total</td>
						// 	<td align="center" width="50" class="bold gris1" rowspan="2"> N° de extranjeros</td>
						// </tr>
						// <tr>	
						// 	<td align="center" class="gris1 bold">6</td>
						// 	<td align="center" class="gris1 bold">7</td>
						// 	<td align="center" class="gris1 bold">8</td>
						// 	<td align="center" class="gris1 bold">9</td>
						// 	<td align="center" class="gris1 bold">10</td>
						// 	<td align="center" class="gris1 bold">11</td>
						// 	<td align="center" class="gris1 bold">12</td>
						// 	<td align="center" class="gris1 bold">13</td>
						// 	<td align="center" class="gris1 bold">14</td>
						// 	<td align="center" class="gris1 bold">15</td>
						// 	<td align="center" class="gris1 bold">16</td>
						// 	<td align="center" class="gris1 bold">17</td>
						// 	<td align="center" class="gris1 bold">18</td>
						// 	<td align="center" class="gris1 bold">19</td>
						// 	<td align="center" class="gris1 bold">20 o más</td>	
						// </tr>'; 

						// $datos = '';
						// $grado = 1;
						// $t = 'ro';
						// #in_array(needle, haystack)
						// for ($i=0; $i<7; $i++){ 
						// 	if( $i >= 3){
						// 		$t = 'to';
						// 	}
						// 	if( $i == 6 ){
						// 		# Subtotales
						// 		$datos = $datos.
						// 		'<tr>
						// 			<td rowspan="2" colspan="2" align="center" class="gris3 bold">Sub Totales</td>
						// 			<td align="center" class="gris2 bold">Mas</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',6).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',7).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',8).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',9).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',10).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',11).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',12).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',13).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',14).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',15).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',16).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',17).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',18).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',19).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',20).'</td>
						// 			<td class="gris1" align="center">'.extraer_totalSexo($resR,'M').'</td>
						// 			<td class="gris1" align="center" rowspan="2">'.count($resR).'</td>
						// 			<td class="gris1" align="center" rowspan="2">'.extraer_subTotalExt($resR).'</td>
						// 			<td class="gris1" align="center" rowspan="2">'.count($rsSec).'</td>
						// 		</tr>
						// 		<tr>
						// 			<td align="center" class="gris2 bold">Fem</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',6).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',7).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',8).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',9).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',10).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',11).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',12).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',13).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',14).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',15).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',16).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',17).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',18).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',19).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',20).'</td>
						// 			<td class="gris1" align="center">'.extraer_totalSexo($resR,'F').'</td>
						// 		</tr>';
						// 	}
						// 	else{
						// 		$datos = $datos.
						// 		'<tr>
						// 			<td rowspan="2" align="center" class="bold">'.$grado.$t.'</td>
						// 			<td rowspan="2" align="center">'.extraer_seccion($rsSec, $grado).'</td>
						// 			<td align="center" class="gris2 bold">Mas</td>
						// 			<td align="center">'.extraer($resR,6,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,7,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,8,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,9,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,10,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,11,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,12,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,13,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,14,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,15,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,16,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,17,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,18,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,19,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,20,'M',$grado).'</td>
						// 			<td align="center">'.extraer_subTotalSexo($resR,'M',$grado).'</td>
						// 			<td align="center" rowspan="2">'.extraer_total($resR, $grado).'</td>
						// 			<td rowspan="2" align="center">'.extraer_extranjeros($resR,$grado).'</td>
						// 			<td rowspan="2" align="center">'.extraer_seccion($rsSec, $grado).'</td>
						// 		</tr>
						// 		<tr>
						// 			<td align="center" class="gris2 bold">Fem</td>
						// 			<td align="center">'.extraer($resR,6,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,7,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,8,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,9,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,10,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,11,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,12,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,13,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,14,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,15,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,16,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,17,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,18,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,19,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,20,'F',$grado).'</td>
						// 			<td align="center">'.extraer_subTotalSexo($resR,'F',$grado).'</td>
						// 		</tr>';
						// 	}
						// 	$grado++;
						// }

						// $tabla = '<table border="1" cellspacing="0" cellpadding="2">'.$cabecera.$datos.'</table>';

						// $pdf->Ln(10);
						// $pdf->SetFont('Helvetica','',10);
						// $pdf->writeHTML($TxTplantel, true, false, false, false, '');
						// $pdf->SetFont('Helvetica','',9);
						// $pdf->writeHTML($tabla, true, false, false, false, '');
						
						// $pdf->SetFont('Helvetica','',12);
						// $firma = $estilos.'<table border="0" cellspacing="1" cellpadding="2">
						// 	<tr>
						// 		<td class="bold" width="50px">Fecha: </td>
						// 		<td width="90px">'.date('d/m/Y').'</td>
						// 		<td width="70px" class="bold">Director(a):</td>
						// 		<td>'.$pdf->nom_director.'</td>
						// 		<td width="25px" class="bold">C.I:</td>
						// 		<td>'.$pdf->ci_director.'</td>
						// 		<td width="50px" class="bold">Firma:</td>
						// 		<td width="250px" class="b">___________________</td>
						// 		<td width="110px" class="bold">Sello del Plantel</td>
						// 	</tr>
						// </table>';
						// $pdf->Ln(25);
						// $pdf->writeHTML($firma, true, false, false, false, '');
					break;
					
					case 'M':
						$pdf->AddPage(); # pag 1

						include_once('../MODELO/m_inscripcion.php');
						$objIncs = new cls_inscripcion();
						$objIncs->set_Periodo($rsAesc['cod_periodo']);

						$mes = $_GET['mes'];
						switch ($mes) {
							case '01': $lMes = 'Enero'; break;
							case '02': $lMes = 'Febrero'; break;
							case '03': $lMes = 'Marzo'; break;
							case '04': $lMes = 'Abril'; break;
							case '05': $lMes = 'Mayo'; break;
							case '06': $lMes = 'Junio'; break;
							case '07': $lMes = 'Julio'; break;
							case '08': $lMes = 'Agosto'; break;
							case '09': $lMes = 'Septiembre'; break;
							case '10': $lMes = 'Octubre'; break;
							case '11': $lMes = 'Noviembre'; break;
							case '12': $lMes = 'Diciembre'; break;
							default: sinResultados(); break;
						}

						$resN = $objIncs->estadistica_mensual($mes,$rsAesc['periodo']);
						#$resR = $objIncs->estadistica_mensual($mes,$rsAesc['periodo'],'R');
						
						$estilos = 
						'<style type="text/css">
							.bold{ font-weight:bold;}
							.gris1{ background-color: #dddfeb; }
							.gris2{ background-color: #b7b9cc;}
							.gris3{ background-color: #858796;}
						</style>';

						$TxTplantel = $estilos.'
						<table align="center" border="1" cellpadding="2" cellspacing="0">
						<tr>
							<th class="gris1 bold" width="460px">Nombre del Plantel ó Servicio</th>
							<th class="gris1 bold" width="140px">Código Estadístico</th>
							<th class="gris1 bold" width="140px">Código DEA</th>
							<th class="gris1 bold" width="140px">Año escolar</th>
						</tr>
						<tr>
							<td>'.$pdf->nom_escuela.'</td>
							<td>'.$pdf->codestco.'</td>
							<td>'.$pdf->coddea.'</td>
							<td>'.$rsAesc["periodo"].'</td>
						</tr>
						</table>';

						$cabecera = $estilos.
						'<tr>
							<td class="gris1 bold" align="center" colspan="21">Educación Primaria: Matrícula del mes de ('.$lMes.') de Estudiantes por Grado de Estudios, Número de Secciones, Sexo y Edad. Años escolar ('.$rsAesc["periodo"].')</td>
							<td class="gris1 bold" rowspan="3" align="center">N° de Docentes por Grado</td>
						</tr>
						<tr>
							<td align="center" width="40" class="bold gris1" rowspan="2">Grados</td>
							<td align="center" width="50" class="bold gris1" rowspan="2">N° de Secciones</td>
							<td align="center" width="40" class="bold gris1" rowspan="2">Sexo</td>
							<td align="center" class="bold gris1" colspan="15">Edad (Años)</td>
							<td align="center" class="bold gris1" rowspan="2">Sub Total</td>
							<td align="center" width="37" class="bold gris1" rowspan="2">Total</td>
							<td align="center" width="50" class="bold gris1" rowspan="2"> N° de extranjeros</td>
						</tr>
						<tr>	
							<td align="center" class="gris1 bold">6</td>
							<td align="center" class="gris1 bold">7</td>
							<td align="center" class="gris1 bold">8</td>
							<td align="center" class="gris1 bold">9</td>
							<td align="center" class="gris1 bold">10</td>
							<td align="center" class="gris1 bold">11</td>
							<td align="center" class="gris1 bold">12</td>
							<td align="center" class="gris1 bold">13</td>
							<td align="center" class="gris1 bold">14</td>
							<td align="center" class="gris1 bold">15</td>
							<td align="center" class="gris1 bold">16</td>
							<td align="center" class="gris1 bold">17</td>
							<td align="center" class="gris1 bold">18</td>
							<td align="center" class="gris1 bold">19</td>
							<td align="center" class="gris1 bold">20 o más</td>	
						</tr>'; 

						$datos = '';
						$grado = 1;
						$t = 'ro';
						#in_array(needle, haystack)
						for ($i=0; $i<7; $i++){ 
							if( $i >= 3){
								$t = 'to';
							}
							if( $i == 6 ){
								# Subtotales
								$datos = $datos.
								'<tr>
									<td rowspan="2" colspan="2" align="center" class="gris3 bold">Sub Totales</td>
									<td align="center" class="gris2 bold">Mas</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',6).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',7).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',8).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',9).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',10).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',11).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',12).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',13).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',14).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',15).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',16).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',17).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',18).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',19).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',20).'</td>
									<td class="gris1" align="center">'.extraer_totalSexo($resN,'M').'</td>
									<td class="gris1" align="center" rowspan="2">'.count($resN).'</td>
									<td class="gris1" align="center" rowspan="2">'.extraer_subTotalExt($resN).'</td>
									<td class="gris1" align="center" rowspan="2">'.count($rsSec).'</td>
								</tr>
								<tr>
									<td align="center" class="gris2 bold">Fem</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',6).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',7).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',8).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',9).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',10).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',11).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',12).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',13).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',14).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',15).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',16).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',17).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',18).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',19).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',20).'</td>
									<td class="gris1" align="center">'.extraer_totalSexo($resN,'F').'</td>
								</tr>';
							}
							else{
								$datos = $datos.
								'<tr>
									<td rowspan="2" align="center" class="bold">'.$grado.$t.'</td>
									<td rowspan="2" align="center">'.extraer_seccion($rsSec, $grado).'</td>
									<td align="center" class="gris2 bold">Mas</td>
									<td align="center">'.extraer($resN,6,'M',$grado).'</td>
									<td align="center">'.extraer($resN,7,'M',$grado).'</td>
									<td align="center">'.extraer($resN,8,'M',$grado).'</td>
									<td align="center">'.extraer($resN,9,'M',$grado).'</td>
									<td align="center">'.extraer($resN,10,'M',$grado).'</td>
									<td align="center">'.extraer($resN,11,'M',$grado).'</td>
									<td align="center">'.extraer($resN,12,'M',$grado).'</td>
									<td align="center">'.extraer($resN,13,'M',$grado).'</td>
									<td align="center">'.extraer($resN,14,'M',$grado).'</td>
									<td align="center">'.extraer($resN,15,'M',$grado).'</td>
									<td align="center">'.extraer($resN,16,'M',$grado).'</td>
									<td align="center">'.extraer($resN,17,'M',$grado).'</td>
									<td align="center">'.extraer($resN,18,'M',$grado).'</td>
									<td align="center">'.extraer($resN,19,'M',$grado).'</td>
									<td align="center">'.extraer($resN,20,'M',$grado).'</td>
									<td align="center">'.extraer_subTotalSexo($resN,'M',$grado).'</td>
									<td align="center" rowspan="2">'.extraer_total($resN, $grado).'</td>
									<td rowspan="2" align="center">'.extraer_extranjeros($resN,$grado).'</td>
									<td rowspan="2" align="center">'.extraer_seccion($rsSec, $grado).'</td>
								</tr>
								<tr>
									<td align="center" class="gris2 bold">Fem</td>
									<td align="center">'.extraer($resN,6,'F',$grado).'</td>
									<td align="center">'.extraer($resN,7,'F',$grado).'</td>
									<td align="center">'.extraer($resN,8,'F',$grado).'</td>
									<td align="center">'.extraer($resN,9,'F',$grado).'</td>
									<td align="center">'.extraer($resN,10,'F',$grado).'</td>
									<td align="center">'.extraer($resN,11,'F',$grado).'</td>
									<td align="center">'.extraer($resN,12,'F',$grado).'</td>
									<td align="center">'.extraer($resN,13,'F',$grado).'</td>
									<td align="center">'.extraer($resN,14,'F',$grado).'</td>
									<td align="center">'.extraer($resN,15,'F',$grado).'</td>
									<td align="center">'.extraer($resN,16,'F',$grado).'</td>
									<td align="center">'.extraer($resN,17,'F',$grado).'</td>
									<td align="center">'.extraer($resN,18,'F',$grado).'</td>
									<td align="center">'.extraer($resN,19,'F',$grado).'</td>
									<td align="center">'.extraer($resN,20,'F',$grado).'</td>
									<td align="center">'.extraer_subTotalSexo($resN,'F',$grado).'</td>
								</tr>';
							}
							$grado++;
						}

						$tabla = '<table border="1" cellspacing="0" cellpadding="2">'.$cabecera.$datos.'</table>';

						$pdf->Ln(10);
						$pdf->SetFont('Helvetica','',10);
						$pdf->writeHTML($TxTplantel, true, false, false, false, '');
						$pdf->SetFont('Helvetica','',9);
						$pdf->writeHTML($tabla, true, false, false, false, '');
						
						$pdf->SetFont('Helvetica','',12);
						$firma = $estilos.'<table border="0" cellspacing="1" cellpadding="2">
							<tr>
								<td class="bold" width="50px">Fecha: </td>
								<td width="90px">'.date('d/m/Y').'</td>
								<td width="70px" class="bold">Director(a):</td>
								<td>'.$pdf->nom_director.'</td>
								<td width="25px" class="bold">C.I:</td>
								<td>'.$pdf->ci_director.'</td>
								<td width="50px" class="bold">Firma:</td>
								<td width="250px" class="b">___________________</td>
								<td width="110px" class="bold">Sello del Plantel</td>
							</tr>
						</table>';
						$pdf->Ln(25);
						$pdf->writeHTML($firma, true, false, false, false, '');

						# PAG 2 ---------------------------
						// $pdf->AddPage(); # pag 2
		
						// $cabecera = $estilos.
						// '<tr>
						// 	<td class="gris1 bold" align="center" colspan="21">Educación Primaria: Matrícula del mes de ('.$lMes.') de Estudiantes Repitientes por Grado de Estudios, Número de Secciones, Sexo y Edad. Años escolar ('.$rsAesc["periodo"].')</td>
						// 	<td class="gris1 bold" rowspan="3" align="center">N° de Docentes por Grado</td>
						// </tr>
						// <tr>
						// 	<td align="center" width="40" class="bold gris1" rowspan="2">Grados</td>
						// 	<td align="center" width="50" class="bold gris1" rowspan="2">N° de Secciones</td>
						// 	<td align="center" width="40" class="bold gris1" rowspan="2">Sexo</td>
						// 	<td align="center" class="bold gris1" colspan="15">Edad (Años)</td>
						// 	<td align="center" class="bold gris1" rowspan="2">Sub Total</td>
						// 	<td align="center" width="37" class="bold gris1" rowspan="2">Total</td>
						// 	<td align="center" width="50" class="bold gris1" rowspan="2"> N° de extranjeros</td>
						// </tr>
						// <tr>	
						// 	<td align="center" class="gris1 bold">6</td>
						// 	<td align="center" class="gris1 bold">7</td>
						// 	<td align="center" class="gris1 bold">8</td>
						// 	<td align="center" class="gris1 bold">9</td>
						// 	<td align="center" class="gris1 bold">10</td>
						// 	<td align="center" class="gris1 bold">11</td>
						// 	<td align="center" class="gris1 bold">12</td>
						// 	<td align="center" class="gris1 bold">13</td>
						// 	<td align="center" class="gris1 bold">14</td>
						// 	<td align="center" class="gris1 bold">15</td>
						// 	<td align="center" class="gris1 bold">16</td>
						// 	<td align="center" class="gris1 bold">17</td>
						// 	<td align="center" class="gris1 bold">18</td>
						// 	<td align="center" class="gris1 bold">19</td>
						// 	<td align="center" class="gris1 bold">20 o más</td>	
						// </tr>'; 

						// $datos = '';
						// $grado = 1;
						// $t = 'ro';
						// #in_array(needle, haystack)
						// for ($i=0; $i<7; $i++){ 
						// 	if( $i >= 3){
						// 		$t = 'to';
						// 	}
						// 	if( $i == 6 ){
						// 		# Subtotales
						// 		$datos = $datos.
						// 		'<tr>
						// 			<td rowspan="2" colspan="2" align="center" class="gris3 bold">Sub Totales</td>
						// 			<td align="center" class="gris2 bold">Mas</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',6).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',7).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',8).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',9).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',10).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',11).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',12).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',13).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',14).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',15).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',16).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',17).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',18).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',19).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'M',20).'</td>
						// 			<td class="gris1" align="center">'.extraer_totalSexo($resR,'M').'</td>
						// 			<td class="gris1" align="center" rowspan="2">'.count($resR).'</td>
						// 			<td class="gris1" align="center" rowspan="2">'.extraer_subTotalExt($resR).'</td>
						// 			<td class="gris1" align="center" rowspan="2">'.count($rsSec).'</td>
						// 		</tr>
						// 		<tr>
						// 			<td align="center" class="gris2 bold">Fem</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',6).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',7).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',8).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',9).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',10).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',11).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',12).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',13).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',14).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',15).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',16).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',17).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',18).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',19).'</td>
						// 			<td class="gris1" align="center">'.extraer_subTotalEdad($resR,'F',20).'</td>
						// 			<td class="gris1" align="center">'.extraer_totalSexo($resR,'F').'</td>
						// 		</tr>';
						// 	}
						// 	else{
						// 		$datos = $datos.
						// 		'<tr>
						// 			<td rowspan="2" align="center" class="bold">'.$grado.$t.'</td>
						// 			<td rowspan="2" align="center">'.extraer_seccion($rsSec, $grado).'</td>
						// 			<td align="center" class="gris2 bold">Mas</td>
						// 			<td align="center">'.extraer($resR,6,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,7,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,8,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,9,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,10,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,11,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,12,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,13,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,14,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,15,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,16,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,17,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,18,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,19,'M',$grado).'</td>
						// 			<td align="center">'.extraer($resR,20,'M',$grado).'</td>
						// 			<td align="center">'.extraer_subTotalSexo($resR,'M',$grado).'</td>
						// 			<td align="center" rowspan="2">'.extraer_total($resR, $grado).'</td>
						// 			<td rowspan="2" align="center">'.extraer_extranjeros($resR,$grado).'</td>
						// 			<td rowspan="2" align="center">'.extraer_seccion($rsSec, $grado).'</td>
						// 		</tr>
						// 		<tr>
						// 			<td align="center" class="gris2 bold">Fem</td>
						// 			<td align="center">'.extraer($resR,6,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,7,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,8,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,9,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,10,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,11,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,12,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,13,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,14,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,15,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,16,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,17,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,18,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,19,'F',$grado).'</td>
						// 			<td align="center">'.extraer($resR,20,'F',$grado).'</td>
						// 			<td align="center">'.extraer_subTotalSexo($resR,'F',$grado).'</td>
						// 		</tr>';
						// 	}
						// 	$grado++;
						// }

						// $tabla = '<table border="1" cellspacing="0" cellpadding="2">'.$cabecera.$datos.'</table>';

						// $pdf->Ln(10);
						// $pdf->SetFont('Helvetica','',10);
						// $pdf->writeHTML($TxTplantel, true, false, false, false, '');
						// $pdf->SetFont('Helvetica','',9);
						// $pdf->writeHTML($tabla, true, false, false, false, '');
						
						// $pdf->SetFont('Helvetica','',12);
						// $firma = $estilos.'<table border="0" cellspacing="1" cellpadding="2">
						// 	<tr>
						// 		<td class="bold" width="50px">Fecha: </td>
						// 		<td width="90px">'.date('d/m/Y').'</td>
						// 		<td width="70px" class="bold">Director(a):</td>
						// 		<td>'.$pdf->nom_director.'</td>
						// 		<td width="25px" class="bold">C.I:</td>
						// 		<td>'.$pdf->ci_director.'</td>
						// 		<td width="50px" class="bold">Firma:</td>
						// 		<td width="250px" class="b">___________________</td>
						// 		<td width="110px" class="bold">Sello del Plantel</td>
						// 	</tr>
						// </table>';
						// $pdf->Ln(25);
						// $pdf->writeHTML($firma, true, false, false, false, '');
					break;

					case 'F':
						$pdf->AddPage(); # pag 1

						include_once('../MODELO/m_inscripcion.php');
						$objIncs = new cls_inscripcion();
						$objIncs->set_Periodo($rsAesc['cod_periodo']);

						$resN = $objIncs->estadistica_final();
						
						$estilos = 
						'<style type="text/css">
							.bold{ font-weight:bold;}
							.gris1{ background-color: #dddfeb; }
							.gris2{ background-color: #b7b9cc;}
							.gris3{ background-color: #858796;}
						</style>';

						$TxTplantel = $estilos.'
						<table align="center" border="1" cellpadding="2" cellspacing="0">
						<tr>
							<th class="gris1 bold" width="460px">Nombre del Plantel ó Servicio</th>
							<th class="gris1 bold" width="140px">Código Estadístico</th>
							<th class="gris1 bold" width="140px">Código DEA</th>
							<th class="gris1 bold" width="140px">Año escolar</th>
						</tr>
						<tr>
							<td>'.$pdf->nom_escuela.'</td>
							<td>'.$pdf->codestco.'</td>
							<td>'.$pdf->coddea.'</td>
							<td>'.$rsAesc["periodo"].'</td>
						</tr>
						</table>';

						$cabecera = $estilos.
						'<tr>
							<td class="gris1 bold" align="center" colspan="21">Educación Primaria: Matrícula Final de Estudiantes por Grado de Estudios, Número de Secciones, Sexo y Edad. Años escolar ('.$rsAesc["periodo"].')</td>
							<td class="gris1 bold" rowspan="3" align="center">N° de Docentes por Grado</td>
						</tr>
						<tr>
							<td align="center" width="40" class="bold gris1" rowspan="2">Grados</td>
							<td align="center" width="50" class="bold gris1" rowspan="2">N° de Secciones</td>
							<td align="center" width="40" class="bold gris1" rowspan="2">Sexo</td>
							<td align="center" class="bold gris1" colspan="15">Edad (Años)</td>
							<td align="center" class="bold gris1" rowspan="2">Sub Total</td>
							<td align="center" width="37" class="bold gris1" rowspan="2">Total</td>
							<td align="center" width="50" class="bold gris1" rowspan="2"> N° de extranjeros</td>
						</tr>
						<tr>	
							<td align="center" class="gris1 bold">6</td>
							<td align="center" class="gris1 bold">7</td>
							<td align="center" class="gris1 bold">8</td>
							<td align="center" class="gris1 bold">9</td>
							<td align="center" class="gris1 bold">10</td>
							<td align="center" class="gris1 bold">11</td>
							<td align="center" class="gris1 bold">12</td>
							<td align="center" class="gris1 bold">13</td>
							<td align="center" class="gris1 bold">14</td>
							<td align="center" class="gris1 bold">15</td>
							<td align="center" class="gris1 bold">16</td>
							<td align="center" class="gris1 bold">17</td>
							<td align="center" class="gris1 bold">18</td>
							<td align="center" class="gris1 bold">19</td>
							<td align="center" class="gris1 bold">20 o más</td>	
						</tr>'; 

						$datos = '';
						$grado = 1;
						$t = 'ro';
						#in_array(needle, haystack)
						for ($i=0; $i<7; $i++){ 
							if( $i >= 3){
								$t = 'to';
							}
							if( $i == 6 ){
								# Subtotales
								$datos = $datos.
								'<tr>
									<td rowspan="2" colspan="2" align="center" class="gris3 bold">Sub Totales</td>
									<td align="center" class="gris2 bold">Mas</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',6).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',7).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',8).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',9).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',10).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',11).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',12).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',13).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',14).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',15).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',16).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',17).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',18).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',19).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'M',20).'</td>
									<td class="gris1" align="center">'.extraer_totalSexo($resN,'M').'</td>
									<td class="gris1" align="center" rowspan="2">'.count($resN).'</td>
									<td class="gris1" align="center" rowspan="2">'.extraer_subTotalExt($resN).'</td>
									<td class="gris1" align="center" rowspan="2">'.count($rsSec).'</td>
								</tr>
								<tr>
									<td align="center" class="gris2 bold">Fem</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',6).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',7).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',8).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',9).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',10).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',11).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',12).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',13).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',14).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',15).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',16).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',17).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',18).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',19).'</td>
									<td class="gris1" align="center">'.extraer_subTotalEdad($resN,'F',20).'</td>
									<td class="gris1" align="center">'.extraer_totalSexo($resN,'F').'</td>
								</tr>';
							}
							else{
								$datos = $datos.
								'<tr>
									<td rowspan="2" align="center" class="bold">'.$grado.$t.'</td>
									<td rowspan="2" align="center">'.extraer_seccion($rsSec, $grado).'</td>
									<td align="center" class="gris2 bold">Mas</td>
									<td align="center">'.extraer($resN,6,'M',$grado).'</td>
									<td align="center">'.extraer($resN,7,'M',$grado).'</td>
									<td align="center">'.extraer($resN,8,'M',$grado).'</td>
									<td align="center">'.extraer($resN,9,'M',$grado).'</td>
									<td align="center">'.extraer($resN,10,'M',$grado).'</td>
									<td align="center">'.extraer($resN,11,'M',$grado).'</td>
									<td align="center">'.extraer($resN,12,'M',$grado).'</td>
									<td align="center">'.extraer($resN,13,'M',$grado).'</td>
									<td align="center">'.extraer($resN,14,'M',$grado).'</td>
									<td align="center">'.extraer($resN,15,'M',$grado).'</td>
									<td align="center">'.extraer($resN,16,'M',$grado).'</td>
									<td align="center">'.extraer($resN,17,'M',$grado).'</td>
									<td align="center">'.extraer($resN,18,'M',$grado).'</td>
									<td align="center">'.extraer($resN,19,'M',$grado).'</td>
									<td align="center">'.extraer($resN,20,'M',$grado).'</td>
									<td align="center">'.extraer_subTotalSexo($resN,'M',$grado).'</td>
									<td align="center" rowspan="2">'.extraer_total($resN, $grado).'</td>
									<td rowspan="2" align="center">'.extraer_extranjeros($resN,$grado).'</td>
									<td rowspan="2" align="center">'.extraer_seccion($rsSec, $grado).'</td>
								</tr>
								<tr>
									<td align="center" class="gris2 bold">Fem</td>
									<td align="center">'.extraer($resN,6,'F',$grado).'</td>
									<td align="center">'.extraer($resN,7,'F',$grado).'</td>
									<td align="center">'.extraer($resN,8,'F',$grado).'</td>
									<td align="center">'.extraer($resN,9,'F',$grado).'</td>
									<td align="center">'.extraer($resN,10,'F',$grado).'</td>
									<td align="center">'.extraer($resN,11,'F',$grado).'</td>
									<td align="center">'.extraer($resN,12,'F',$grado).'</td>
									<td align="center">'.extraer($resN,13,'F',$grado).'</td>
									<td align="center">'.extraer($resN,14,'F',$grado).'</td>
									<td align="center">'.extraer($resN,15,'F',$grado).'</td>
									<td align="center">'.extraer($resN,16,'F',$grado).'</td>
									<td align="center">'.extraer($resN,17,'F',$grado).'</td>
									<td align="center">'.extraer($resN,18,'F',$grado).'</td>
									<td align="center">'.extraer($resN,19,'F',$grado).'</td>
									<td align="center">'.extraer($resN,20,'F',$grado).'</td>
									<td align="center">'.extraer_subTotalSexo($resN,'F',$grado).'</td>
								</tr>';
							}
							$grado++;
						}

						$tabla = '<table border="1" cellspacing="0" cellpadding="2">'.$cabecera.$datos.'</table>';

						$pdf->Ln(10);
						$pdf->SetFont('Helvetica','',10);
						$pdf->writeHTML($TxTplantel, true, false, false, false, '');
						$pdf->SetFont('Helvetica','',9);
						$pdf->writeHTML($tabla, true, false, false, false, '');
						
						$pdf->SetFont('Helvetica','',12);
						$firma = $estilos.'<table border="0" cellspacing="1" cellpadding="2">
							<tr>
								<td class="bold" width="50px">Fecha: </td>
								<td width="90px">'.date('d/m/Y').'</td>
								<td width="70px" class="bold">Director(a):</td>
								<td>'.$pdf->nom_director.'</td>
								<td width="25px" class="bold">C.I:</td>
								<td>'.$pdf->ci_director.'</td>
								<td width="50px" class="bold">Firma:</td>
								<td width="250px" class="b">___________________</td>
								<td width="110px" class="bold">Sello del Plantel</td>
							</tr>
						</table>';
						$pdf->Ln(25);
						$pdf->writeHTML($firma, true, false, false, false, '');
					break;
				}
			}
			else{
				sinResultados(); # no existe el año escolar
			}
		break;

		// resumen de matrícula
		// case '2':
		// 	if( $rsAesc = consultar_AESC( $_GET['aesc'] ) ){

		// 	}
		// 	else{
		// 		sinResultados(); # no existe el año escolar
		// 	}
		// break;

		// resumen del rendimiento
		case '3':
			if( isset($_GET['aesc']) ){
				# consultamos la seccion

				if( $rsSec = consultar_seccion($_GET['seccion']) ){

					# consultamos la matricula
					$rsInsc = consultar_inscritos($_GET['seccion']);

					setlocale(LC_TIME, 'spanish'); setlocale(LC_TIME, 'es_ES.UTF-8');
					$fecha = ucfirst(strftime('%d de %B del %Y'));

					# TCPDF
					include_once('../MODELO/my_tcpdf.php');
					$pdf = new MYPDF('H', PDF_UNIT,'A4', true, 'UTF-8', false);
					$pdf->SetMargins(8, 30, 8); 
					$pdf->AddPage();
					$pdf->getPlantel(); 

					$pdf->SetFont('Helvetica','B',10);
					$pdf->Cell(0,1,'RESUMEN FINAL DEL RENDIMIENTO ESTUDIANTIL',0,1,'C');
					$pdf->Cell(0,1,'PRIMARIA',0,1,'C');
					$pdf->SetFont('Helvetica','B',8);
					$pdf->Cell(90,1,'Plan de Estudio:',0,0,'R'); $pdf->Cell(46,1,'Nivel de Educación Primaria','B',1,'C');
					$pdf->Cell(0,1,'Año Escolar: '.$rsSec['periodo'].' Mes y año de la evaluación: '.ucwords(strftime('%B')).' '.date('Y'),0,1,'C');
					$pdf->Ln(8);

					$pdf->SetFont('Helvetica','',8);
					$estilos = 
					'<style type="text/css">
						.bold{ font-weight:bold;}
						.gris1{ background-color: #dddfeb; }
						.gris2{ background-color: #b7b9cc;}
						.gris3{ background-color: #858796;}
						.cv{height: 20px;line-height:10px;}
					</style>';

					$ptxt = '<p style="line-height:5px;"><b>Datos del Plantel:</b><br>Cód.DEA: <b><u> '.$pdf->coddea.' </u></b> Nombre: <b><u> '.$pdf->nom_escuela.' </u></b>
					<br>Dirección: <b><u> '.$pdf->direccion.' </u></b> Teléfono: <b><u> '.$pdf->telefono.' </u></b>
					<br>Municipio: <b><u> '.$pdf->mun.' </u></b> Ent.Federal: <b><u> '.$pdf->edo.' </u></b> Zona Educativa: <b><u> '.$pdf->edo.' </u></b></p>
					<p style="line-height:5px";><b>Identificación del curso:</b> <br>Nivel o Grado: <b><u> '.$rsSec['grado'].'° </u></b> Sección: <b><u> '.$rsSec['letra'].' </u></b> N° de Alumnos de la sección: <b><u> '.count($rsInsc).' </u></b> </p>';
					
					$pdf->writeHTML($ptxt, true, false, false, false, '');
					$pdf->Ln(3);

					$datos = '';
					$datos2 = ''; 
					$num = 1;
					$totalA = 0;
					$totalB = 0;
					$totalC = 0;
					$totalD = 0;
					$totalE = 0;
					$totalP = 0;

					for ($i=0; $i<count($rsInsc); $i++){
						$nA = ''; $nB = ''; $nC = ''; $nD = ''; $nE = ''; $nP = '';
						$rsNota = consultar_nota($rsInsc[$i]['codInsc']);
						switch ($rsNota['literal']) {
							case 'A': $nA = 'X'; $totalA++; break;
							case 'B': $nB = 'X'; $totalB++; break;
							case 'C': $nC = 'X'; $totalC++; break;
							case 'D': $nD = 'X'; $totalD++; break;
							case 'E': $nE = 'X'; $totalE++; break;
						}
						if( $rsNota['promovido'] == 'S' ){
							$nP = 'X';
							$totalP++;
						}

						$datos = $datos.'<tr>
							<td align="center">'.$num.'</td>
							<td align="center">'.$rsInsc[$i]['CE'].'</td>
							<td align="center"> '.$rsInsc[$i]['desc_lugar'].'</td>
							<td align="center">'.$rsInsc[$i]['nom_edo'].'</td>
							<td align="center">'.substr($rsInsc[$i]['fnac'], 0, 2).'</td>
							<td align="center">'.substr($rsInsc[$i]['fnac'], 3, 2).'</td>
							<td align="center">'.substr($rsInsc[$i]['fnac'], 6, 4).'</td>
							<td align="center">'.$nA.'</td>
							<td align="center">'.$nB.'</td>
							<td align="center">'.$nC.'</td>
							<td align="center">'.$nD.'</td>
							<td align="center">'.$nE.'</td>
							<td align="center">'.$nP.'</td>
						</tr>';
						$datos2 = $datos2.'<tr>
							<td align="center">'.$num.'</td>
							<td align="center">'.$rsInsc[$i]['ape'].'</td>
							<td align="center">'.$rsInsc[$i]['nom'].'</td>
							<td align="center">'.$rsInsc[$i]['sexo'].'</td>
						</tr>';
						$num++;
					}
					$tabla = '
					<p><b>Resumen Final del Rendimiento</b></p>
					<table border="1" cellpadding="3">
						<tr>
							<th  align="center" rowspan="2" width="20" align="center"><b>N°</b></th>
							<th  align="center" rowspan="2"><b>Cédula de Identidad o Cédula Escolar</b></th>
							<th  align="center" rowspan="2" width="118"><b>Lugar de Nacimiento</b></th>
							<th  align="center" rowspan="2" width="100"><b>E.F</b></th>
							<th  align="center" width="90"><b>Fecha de Nacimiento</b></th>
							<th  align="center" width="132"><b>E.Básica</b></th>
						</tr>
						<tr>
							<th  align="center" width="30"><b>Día</b></th>
							<th  align="center" width="30"><b>Mes</b></th>
							<th  align="center" width="30"><b>Año</b></th>
							<th  align="center" width="22"><b>A</b></th>
							<th  align="center" width="22"><b>B</b></th>
							<th  align="center" width="22"><b>C</b></th>
							<th  align="center" width="22"><b>D</b></th>
							<th  align="center" width="22"><b>E</b></th>
							<th  align="center" width="22"><b>P</b></th>
						</tr>
						'.$datos.'
						<tr>
							<td align="center" colspan="7"><b>TOTALES</b></td>
							<td align="center"><b>'.$totalA.'</b></td>
							<td align="center"><b>'.$totalB.'</b></td>
							<td align="center"><b>'.$totalC.'</b></td>
							<td align="center"><b>'.$totalD.'</b></td>
							<td align="center"><b>'.$totalE.'</b></td>
							<td align="center"><b>'.$totalP.'</b></td>
						</tr>
					</table>';
					$pdf->writeHTML($estilos.$tabla, true, false, false, false, '');
					$tabla2 = '
					<table border="1" cellpadding="3" >
						<tr>
							<td align="center" width="20" ><b>N°</b></td>
							<td width="200" align="center" ><b>Apellidos</b></td>
							<td width="200" align="center" ><b>Nombres</b></td>
							<td width="50" align="center" ><b>Sexo</b></td>
						</tr>
						'.$datos2.'
					</table>';
					$pdf->writeHTML($estilos.$tabla2, true, false, false, false, '');

					$tabla3 = '<table border="1" cellpadding="4" style="margin-left:300px;">
						<tr>
							<td align="center" width="120" ><b>CARGO</b></td>
							<td align="center" width="120" ><b>DOCENTE</b></td>
							<td align="center" width="120" ><b>DIRECTOR(A)</b></td>
						</tr>
						<tr>
							<td align="center"><b>NOMBRE</b></td>
							<td align="center"><b>'.$rsSec['nom_docente'].'</b></td>
							<td align="center"><b>'.$pdf->nom_director.'</b></td>
						</tr>
						<tr>
							<td align="center"><b>CI.</b></td>
							<td align="center"><b>'.$rsSec['ced_docente'].'</b></td>
							<td align="center"><b>'.$pdf->ci_director.'</b></td>
						</tr>
						<tr>
							<td align="center" height="50"><b>FIRMA</b></td>
							<td align="center"></td>
							<td align="center"></td>
						</tr>
					</table>';
					$pdf->Ln(5);
					$pdf->SetX(40,0);
					$pdf->writeHTML($estilos.$tabla3, true, false, false, false, '');
				}
				else{
					#sinResultados();
				}
			}
			else{
				#sinResultados();
			}
			break;

		default:
			sinResultados();
			break;
	}
#TCPDF
ob_end_clean();
$pdf->Output();
}
?>