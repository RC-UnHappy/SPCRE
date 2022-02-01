<?php 
	include_once('../MODELO/m_a_escolar.php');
	$objAesc = new cls_a_escolar();

	# consulta los ultimos 6 años escolares
	function imprimir_aesc(){
		global $objAesc;
		$rs = $objAesc->ultimos_6_aesc(); 
		for ($i=0; $i<count($rs); $i++) { 
			echo '<option value="'.$rs[$i]['cod'].'">'.$rs[$i]['periodo'].'</option>';
		}
	}

	if( isset($_GET['periodo']) ){
		include_once('../MODELO/m_seccion.php');
		$objSec = new cls_seccion();
		$objSec->set_periodo($_GET['periodo']);
		$rsSec = $objSec->cantidad_secciones_grado();	
		$mesSEL = '';
		switch ($_GET['mes']) {
			case '01':
				$mesSEL = 'Enero';
				break;
			
			case '02':
				$mesSEL = 'Febrero';
				break;

			case '03':
				$mesSEL = 'Marzo';
				break;

			case '04':
				$mesSEL = 'Abril';
				break;

			case '05':
				$mesSEL = 'Mayo';
				break;

			case '06':
				$mesSEL = 'Junio';
				break;

			case '07':
				$mesSEL = 'Julio';
				break;

			case '08':
				$mesSEL = 'Agosto';
				break;

			case '09':
				$mesSEL = 'Septiembre';
				break;

			case '10':
				$mesSEL = 'Octubre';
				break;

			case '11':
				$mesSEL = 'Noviembre';
				break;

			case '12':
				$mesSEL = 'Diciembre';
				break;
		}

		# Cantidad de secciones
		$nSEC1 = 0;
		$nSEC2 = 0;
		$nSEC3 = 0;
		$nSEC4 = 0;
		$nSEC5 = 0;
		$nSEC6 = 0;

		for ($i=0; $i <count($rsSec) ; $i++) { 
			switch ($rsSec[$i]['gdo']) {
				case '1': $nSEC1++; break;
				case '2': $nSEC2++; break;
				case '3': $nSEC3++; break;
				case '4': $nSEC4++; break;
				case '5': $nSEC5++; break;
				case '6': $nSEC6++; break;
			}
		}
		
		include_once('../MODELO/m_inscripcion.php');
		$objInsc = new cls_inscripcion();
		$objInsc->set_Periodo($_GET['periodo']);
		$mes = $_GET['mes'];

	
		// $nVMAS1 = $objInsc->consulta_estadistica_tdoc(1,'V','M',$mes);;  
		// $nVFEM1 = $objInsc->consulta_estadistica_tdoc(1,'V','F',$mes);; 
		// $nVMAS2 = $objInsc->consulta_estadistica_tdoc(2,'V','M',$mes);; 
		// $nVFEM2 = $objInsc->consulta_estadistica_tdoc(2,'V','F',$mes);;
		// $nVMAS3 = $objInsc->consulta_estadistica_tdoc(1,'V','M',$mes);;  
		// $nVFEM3 = $objInsc->consulta_estadistica_tdoc(1,'V','F',$mes);; 
		// $nVMAS4 = $objInsc->consulta_estadistica_tdoc(1,'V','M',$mes);;
		// $nVFEM4 = $objInsc->consulta_estadistica_tdoc(1,'V','F',$mes);;
		// $nVMAS5 = $objInsc->consulta_estadistica_tdoc(1,'V','M',$mes);;
		// $nVFEM5 = $objInsc->consulta_estadistica_tdoc(1,'V','F',$mes);; 
		// $nVMAS6 = $objInsc->consulta_estadistica_tdoc(1,'V','M',$mes);;
		// $nVFEM6 = $objInsc->consulta_estadistica_tdoc(1,'V','F',$mes);; 

		# >>> PDF
		#TCPDF
		include_once('../MODELO/my_tcpdf.php');
		$pdf = new MYPDF('L', PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->SetMargins(8, 30, 8); 
		$pdf->AddPage();

		#TITULO
		$pdf->Ln(3);
		$pdf->SetFont('helvetica','B',12); 
		$pdf->Cell(150,0,'Nombre del Plantel o Servicio',1,0,'C');
		$pdf->Cell(50,0,'Código Estadístico',1,0,'C');
		$pdf->Cell(50,0,'Código Estadístico',1,1,'D');
		#$pdf->Cell(50,0,'Año Escolar',1,1,'C');
		$pdf->SetFont('helvetica','',12); 
		$pdf->Cell(150,0,'UNIDAD EDUCATIVA NACIONAL "SAMUEL ROBINSON"',1,0,'C');
		$pdf->Cell(50,0,'181416',1,0,'C');
		$pdf->Cell(50,0,'OD04052802',1,0,'C');
		#$pdf->Cell(50,0,$_GET['periodo'],1,1,'C');

		$pdf->SetFont('helvetica','B',12); 
		$pdf->Ln(10);
		$pdf->Cell(0,0,'Educación Primaria: Matrícula del Mes de '.$mesSEL.' por Grado de Estudio, Numero de Secciones, Sexo y Edad.',1,1,'C');
		$pdf->Cell(18,13,'Grados',1,0,'C');
		$pdf->Cell(18,13,'N° Sec.',1,0,'C');
		$pdf->Cell(18,13,'Sexo',1,0,'C');
		$pdf->Cell(12,13,'5',1,0,'C');
		$pdf->Cell(12,13,'6',1,0,'C');
		$pdf->Cell(12,13,'7',1,0,'C');
		$pdf->Cell(12,13,'8',1,0,'C');
		$pdf->Cell(12,13,'9',1,0,'C');
		$pdf->Cell(12,13,'10',1,0,'C');
		$pdf->Cell(12,13,'11',1,0,'C');
		$pdf->Cell(12,13,'12',1,0,'C');
		$pdf->Cell(12,13,'13',1,0,'C');
		$pdf->Cell(12,13,'14',1,0,'C');
		$pdf->Cell(12,13,'15',1,0,'C');
		$pdf->Cell(12,13,'16',1,0,'C');
		$pdf->Cell(12,13,'17',1,0,'C');
		$pdf->Cell(12,13,'18',1,0,'C');
		$pdf->Cell(12,13,'20',1,0,'C');
		$pdf->Cell(28,13,'21 y más',1,0,'C');
		$pdf->Cell(25,13,'Sub. Total',1,0,'C');
		$pdf->Cell(20,13,'Total',1,0,'C');
		$pdf->Cell(33,13,'N° Extranjeros',1,1,'C');
		// $nSEC1 = 0;
		// $nSEC2 = 0;
		// $nSEC3 = 0;
		// $nSEC4 = 0;
		// $nSEC5 = 0;
		// $nSEC6 = 0;
		$pdf->SetFont('helvetica','',12); 
		$pdf->Cell(18,14,'1ero',1,0,'C');
		$pdf->Cell(18,14,$nSEC1,1,0,'C');
		$pdf->Cell(18,7,'Mas',1,1,'C');
		$pdf->Cell(18,7,'Fem',1,0,'C');

		#$pdf->Ln(10); 	$pdf->Cell(18,7,'Fem',1,0,'C');

		ob_end_clean();
		$pdf->Output();
		# PDF <<<
	}

?>