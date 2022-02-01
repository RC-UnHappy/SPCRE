<?php 
function consultar_ultAESC(){ # ultimo año escolar
	include_once('../MODELO/m_a_escolar.php');
	$obj = new cls_a_escolar();
	$rs = $obj->ultimo_aesc();	
	return $rs;
}
function consultar_AESC($a){
	include_once('../MODELO/m_a_escolar.php');
	$obj = new cls_a_escolar();
	$obj->set_periodo($a);
	if( $rs = $obj->consultar() ){
		return $rs;
	}
}
function consultar_representantes($codAESC){
	include_once('../MODELO/m_representante.php');
	$obj = new cls_representante();
	return $obj->nomina_representantes($codAESC);
}

ob_start();
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

$AESC = '';
$codAESC = '';

$rsAESC = consultar_ultAESC();
$codAESC = $rsAESC['cod_periodo'];
$AESC = $rsAESC['periodo'];


if( !isset($_GET['ver']) ){
	if( isset($_GET['aesc']) && !empty($_GET['aesc']) ){
		if( $rs = consultar_AESC($_GET['aesc']) ){
			$rsRep = consultar_representantes($rs['cod_periodo']);

			# TCPDF
			include_once('../MODELO/my_tcpdf.php');
			$pdf = new MYPDF('L', PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
			$pdf->SetMargins(12, 30, 12); 
			$pdf->AddPage();

			#TITULO
			$pdf->Ln(3);
			$pdf->SetFont('helvetica','B',16); 
			$pdf->Cell(0,6,'NÓMINA DE REPRESENTANTES (AÑO ESCOLAR: '.$rs['periodo'].')',0,0,'C');
			$pdf->Ln(12);

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
			
			$table = '<style>td{line-height:8px;}</style>
			<table cellspacing="0" cellpadding="0" border="1">'.$th.$datosRep.'</table>';
			$pdf->SetFont('helvetica','B',10);
			$pdf->Cell(0,6,'TOTAL: '.count($rsRep),0,1,'L');
			$pdf->SetFont('helvetica','',9);
			$pdf->Ln(2);
			$pdf->writeHTML($table, true, false, true, false, ''); 

			# Firma director
			$pdf->getPlantel();
			$sexoDir = 'Director: ';
			if( $pdf->sexo_dir == 'F'){
				$sexoDir = 'Directora: ';
			}
			$pdf->SetFont('helvetica','',11);
			$pdf->Ln(30);
			$pdf->Cell(0,6,'__________________________________',0,1,'C');
			$pdf->Cell(0,6,$sexoDir.$pdf->nom_director,0,1,'C');
			$pdf->Cell(0,6,'C.I: '.$pdf->ci_director,0,1,'C');

			ob_end_clean();
			$pdf->Output();
		}
		else{
			header('location: ../VISTA/?ver=nomina_rep&error=1');
		}
	}
	else{
		header('location: ../VISTA/?ver=nomina_rep&error=1');
	}
}


?>