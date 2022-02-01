<?php

# consultar año escolar
function consultar_AESC($periodo){
	include_once('../MODELO/m_a_escolar.php');
	$obj = new cls_a_escolar();
	$obj->set_periodo($periodo);
	$rs = $obj->consultar();
}
function consultar_conf($codAesc){
	include_once('../MODELO/m_a_escolar.php');
	$obj = new cls_a_escolar();
	$obj->set_codigo( $codAesc );
	$rs = $obj->consultar_configuracion();
	return $rs;
}
# SILENCIAR ERRORES
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

if( isset($_GET['cod_seccion']) && !empty($_GET['cod_seccion']) ){	

	$codigo = $_GET['cod_seccion'];
	include_once('../MODELO/m_seccion.php');
	$objSec = new cls_seccion();
	$objSec->set_codigo($codigo);
	
	if( $rs = $objSec->consultar_seccion_reporte() ) # Existe la sección
	{
		$rsAesc = consultar_AESC($rs['periodo']);

		# DATOS DE LA SECCIÓN
		setlocale(LC_TIME, 'spanish'); setlocale(LC_TIME, 'es_ES.UTF-8');
		$ciDocente = $rs['ciDocente'];
		$docente = $rs['docente'];
		$V = $rs['V']; $H = $rs['H']; 
		$total = $rs['V']+$rs['H'];
		$lta = $rs['letra'];
		$gdo = $rs['grado'];
		$aesc = $rs['periodo'];
		$mes = '';
		$tdmes = '';

		switch ($gdo) {
			case '1': $gdo = '1ERO'; break;
			case '2': $gdo = '2DO'; break;
			case '3': $gdo = '3ERO'; break;
			case '4': $gdo = '4TO'; break;
			case '5': $gdo = '5TO'; break;
			case '6': $gdo = '6TO'; break;
		}

		# TIPO DE REPORTE  >>>>
		switch ($_GET['tipo']) {
			case 'I':
				$titulo = 'MATRICULA INICIAL DE ESTUDIANTES DEL AÑO ESCOLAR '.$aesc;
				$rsConf = consultar_conf($rsAesc['cod_periodo']);
				$rsInsc = $objSec->matricula_seccion('I',$rsConf['fmi_desde'],$rsConf['fmi_hasta']);	
				$rsRep = $objSec->nomina_representantes('I',$rsConf['fmi_desde'],$rsConf['fmi_hasta']);
				break;
			
			case 'M':
				$rsInsc = $objSec->matricula_seccion('M','','',$_GET['mes'],$rsAesc['periodo']);
				$rsRep = $objSec->nomina_representantes('M','','',$_GET['mes'],$rsAesc['periodo']);
				switch ($_GET['mes']) {
					case '01': $tdmes = '<td align="center"><b>MES: </b>ENERO</td>'; $mes='ENERO'; break;
					case '02': $tdmes = '<td align="center"><b>MES: </b>FEBRERO</td>'; $mes='FEBRERO'; break;
					case '03': $tdmes = '<td align="center"><b>MES: </b>MARZO</td>'; $mes='MARZO'; break;
					case '04': $tdmes = '<td align="center"><b>MES: </b>ABRIL</td>'; $mes='ABRIL'; break;
					case '05': $tdmes = '<td align="center"><b>MES: </b>MAYO</td>'; $mes='MAYO'; break;
					case '06': $tdmes = '<td align="center"><b>MES: </b>JUNIO</td>'; $mes='JUNIO'; break;
					case '07': $tdmes = '<td align="center"><b>MES: </b>JULIO</td>'; $mes='JULIO'; break;
					case '08': $tdmes = '<td align="center"><b>MES: </b>AGOSTO</td>'; $mes='AGOSTO'; break;
					case '09': $tdmes = '<td align="center"><b>MES: </b>SEPTIEMBRE</td>'; $mes='SEPTIEMBRE'; break;
					case '10': $tdmes = '<td align="center"><b>MES: </b>OCTUBRE</td>'; $mes='OCTUBRE'; break;
					case '11': $tdmes = '<td align="center"><b>MES: </b>NOVIEMBRE</td>'; $mes='NOVIEMBRE'; break;
					case '12': $tdmes = '<td align="center"><b>MES: </b>DICIEMBRE</td>'; $mes='DICIEMBRE'; break;
				}
				$titulo = 'MATRICULA DEL MES DE '.$mes.' DE ESTUDIANTES DEL AÑO ESCOLAR '.$aesc;
				break;

			case 'F':
				$titulo = 'MATRICULA FINAL DE ESTUDIANTES DEL AÑO ESCOLAR '.$aesc;
				$rsInsc = $objSec->matricula_seccion('F');
				$rsRep = $objSec->nomina_representantes('F');
				break;
		}
		
		# TCPDF
		include_once('../MODELO/my_tcpdf.php');
		$pdf = new MYPDF('L', PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->SetMargins(12, 30, 12); 
		$pdf->AddPage();
		$pdf->getPlantel();

		#TITULO
		$pdf->Ln(3);
		$pdf->SetFont('helvetica','B',16); 
		$pdf->Cell(0,6,$titulo,0,0,'C');
		$pdf->Ln(15);

		#DATOS DE LA SECCIÓN
		$pdf->SetFont('helvetica','',12);
		$table = '
		<table cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="250"><b>DOCENTE: </b>'.$docente.'</td>
				<td width="110"><b>GRADO: </b>"'.$gdo.'"</td>
				<td width="100"><b>SECCIÓN: </b>"'.$lta.'"</td>
				<td width="100"><b>MATRICULA:</b></td>
				<td width="40"><b>V: </b>'.$V.'</td>
				<td width="40"><b>H: </b>'.$H.'</td>
				<td width="40"><b>T: </b>'.$total.'</td>
				'.$tdmes.'
			</tr>
		</table>';
		

        $pdf->writeHTML($table, false, false, true, false, ''); 
        $pdf->Ln(3);

		$pdf->SetFont('helvetica','',9);
		# Cabecera
		$th = '<tr nobr="true">
			<td align="center" rowspan="2" width="24"><b>N°</b></td>
			<td align="center" rowspan="2" width="80"><b>C.I / C.E</b></td>
			<td align="center" rowspan="2" width="260"><b>NOMBRES Y APELLIDOS</b></td>
			<td align="center" rowspan="2" width="30"><b>SEXO</b></td>
			<td align="center" rowspan="2" width="74"><b>F.NACIMIENTO</b></td>
			<td align="center" rowspan="2" width="30"><b>EDAD</b></td>
			<td align="center" rowspan="2" width="120"><b>LUGAR DE NACIMIENTO</b></td>
			<td align="center" rowspan="2" width="120"><b>ENTIDAD FEDERAL</b></td>
			<td align="center" rowspan="2" width="120"><b>C.I REPRESENTANTE</b></td>
			<td align="center" colspan="2" width="80"><b>REPITE</b></td>
		</tr>
		<tr>
			<td align="center">SI</td>
			<td align="center">NO</td>
		</tr>';
	
		$datos = ''; # string en donde se almacenan las celdas con los datos de los estudiantes
		$num = 1;
		for ($i=0; $i<count($rsInsc); $i++){
			$repiteS = '';
			$repiteN = 'X';
			if( $rsInsc[$i]['condicion'] == 'R' ){
				$repiteS = 'X';
				$repiteN = '';
			}
			$datos = $datos.'<tr>
			<td align="center">'.$num.'</td>
			<td align="center"><b>'.$rsInsc[$i]['CE'].'</b></td>
			<td>  '.$rsInsc[$i]['nom'].' '.$rsInsc[$i]['ape'].'</td>
			<td align="center">'.$rsInsc[$i]['sexo'].'</td>
			<td align="center">'.$rsInsc[$i]['fnac'].'</td>
			<td align="center">'.$rsInsc[$i]['edad'].'</td>
			<td align="center">'.$rsInsc[$i]['desc_lugar'].'</td>
			<td align="center">'.$rsInsc[$i]['nom_edo'].'</td>
			<td align="center">'.$rsInsc[$i]['ci_rep'].'</td>
			<td align="center">'.$repiteS.'</td>
			<td align="center">'.$repiteN.'</td>
			</tr>';
			$num++;
		}

		$table = '<style>td{line-height:6px;}</style>
		<table cellspacing="0" cellpadding="0" border="1">'.$th.$datos.'</table>';
		$pdf->writeHTML($table, true, false, true, false, ''); 

		# continuación de los datos de los estudiantes
		$th2 = '<tr nobr="true">
		<td align="center" width="24"><b>N°</b></td>
		<td align="center" width="80"><b>C.I / C.E</b></td>
		<td align="center" width="250"><b>PROCEDENCIA</b></td>
		<td align="center" width="350"><b>DIRECCIÓN</b></td>
		</tr>';
		$datos2='';
		$num2 = 1;
		for ($i=0; $i<count($rsInsc); $i++){ 
			$datos2 = $datos2.'<tr>
			<td align="center">'.$num2.'</td>
			<td align="center"><b>'.$rsInsc[$i]['CE'].'</b></td>
			<td align="left">   '.$rsInsc[$i]['escuela_proc'].'</td>
			<td align="left">   '.$rsInsc[$i]['direccion_est'].'</td>
			</tr>';
			$num2++;
		}

		$pdf->Ln(10);
		$table = '<style>td{line-height:6px;}</style>
		<table cellspacing="0" cellpadding="0" border="1">'.$th2.$datos2.'</table>';
		$pdf->writeHTML($table, true, false, true, false, ''); 
		
		# Firmas
		$sexoDir = 'Director: ';
		if( $pdf->sexo_dir == 'F'){
			$sexoDir = 'Directora: ';
		}
		$pdf->SetFont('helvetica','',11);
		$pdf->Ln(20);
		$pdf->setX(75);
		$pdf->Cell(100,6,'__________________________________',0,0,'C');
		$pdf->Cell(100,6,'__________________________________',0,1,'C');
		$pdf->setX(75);
		$pdf->Cell(100,6,'Docente: '.$docente,0,0,'C');
		$pdf->Cell(100,6,$sexoDir.$pdf->nom_director,0,1,'C');
		$pdf->setX(75);
		$pdf->Cell(100,6,'C.I: '.$ciDocente,0,0,'C');
		$pdf->Cell(100,6,'C.I: '.$pdf->ci_director,0,1,'C');
		# <<<<<<<<<

		# aqui empieza la nomina de representantes
		$pdf->AddPage();
		#TITULO
		$pdf->Ln(3);
		$pdf->SetFont('helvetica','B',16); 
		$pdf->Cell(0,6,'NÓMINA DE REPRESENTANTES (GRADO: '.$gdo.' SECCIÓN: "'.$lta.'" AÑO ESCOLAR: '.$aesc.')',0,0,'C');
		$pdf->Ln(20);

		# Cabecera
		$th = '<tr nobr="true">
		<td align="center" width="24"><b>N°</b></td>
		<td align="center" width="70"><b>CÉDULA</b></td>
		<td align="center" width="180"><b>NOMBRE Y APELLIDO</b></td>
		<td align="center" width="32"><b>SEXO</b></td>
		<td align="center" width="220"><b>DIRECCIÓN DE DOMICILIO</b></td>
		<td align="center" width="70"><b>TELÉFONO</b></td>
		<td align="center" width="170"><b>CORREO ELECTRÓNICO</b></td>
		<td align="center" width="170"><b>OCUPACIÓN</b></td>
		</tr>';

		$datosRep = '';
		$numRep = 1;
		for ($i=0; $i<count($rsRep); $i++){ 
			$datosRep = $datosRep.'<tr>
			<td align="center">'.$numRep.'</td>
			<td align="center"><b>'.$rsRep[$i]['cedula'].'</b></td>
			<td align="left">   '.$rsRep[$i]['nombre'].'</td>
			<td align="center">'.$rsRep[$i]['sexo'].'</td>
			<td align="left">   '.$rsRep[$i]['direccion'].'</td>
			<td align="center">'.$rsRep[$i]['telefono'].'</td>
			<td align="left">   '.$rsRep[$i]['email'].'</td>
			<td align="center">   '.$rsRep[$i]['ocup'].'</td>
			</tr>';
			$numRep++;
		}

		$pdf->SetFont('helvetica','',9);
		$table = '<style>td{line-height:6px;}</style>
		<table cellspacing="0" cellpadding="0" border="1">'.$th.$datosRep.'</table>';
		$pdf->writeHTML($table, true, false, true, false, ''); 
		# Firmas
		$pdf->SetFont('helvetica','',11);
		$pdf->Ln(20);
		$pdf->setX(75);
		$pdf->Cell(100,6,'__________________________________',0,0,'C');
		$pdf->Cell(100,6,'__________________________________',0,1,'C');
		$pdf->setX(75);
		$pdf->Cell(100,6,'Docente: '.$docente,0,0,'C');
		$pdf->Cell(100,6,$sexoDir.$pdf->nom_director,0,1,'C');
		$pdf->setX(75);
		$pdf->Cell(100,6,'C.I: '.$ciDocente,0,0,'C');
		$pdf->Cell(100,6,'C.I: '.$pdf->ci_director,0,1,'C');

		ob_end_clean();
		$pdf->Output();
	}
	else{
		header('location: ../VISTA/?error=404');
	}
}
else{
	header('location: ../VISTA/?error=404');
}
?>