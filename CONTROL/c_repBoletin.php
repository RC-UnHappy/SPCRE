<?php 
	# AÑO escolar
	function consultar_AESC($aesc){ # ultimo año escolar
		include_once('../MODELO/m_a_escolar.php');
		$obj = new cls_a_escolar();
		$obj->set_periodo($aesc);
		$rs = $obj->consultar();	
		return $rs;
	}

	function consultar_ultAESC(){ # ultimo año escolar
		include_once('../MODELO/m_a_escolar.php');
		$obj = new cls_a_escolar();
		$rs = $obj->ultimo_aesc();	
		return $rs;
	}

	function consultar_lapso($lapso, $aesc){
		include_once('../MODELO/m_lapso.php');
		$obj = new cls_lapso();
		$obj->setLapso($lapso,$aesc);	
		$rs = $obj->consultar();
		return $rs;
	}

	function consultar_PA($lapso, $seccion){
		include_once('../MODELO/m_PA.php');
		$obj = new cls_PA();
		$obj->setPA('',$seccion,$lapso,'');
		$rs = $obj->consultarPA();
		return $rs;
	}

	function consultar_persona_sm($codPer){
		include_once('../MODELO/m_persona.php');
		$obj = new cls_persona();
		$obj->set_codigoPersona($codPer);
		$rs = $obj->consultar_persona2();
		return $rs;
	}

	function consultar_indicadores($codPA){
		include_once('../MODELO/m_indicador.php');
		$obj = new cls_indicador();
		$obj->setDatos('','',$codPA);
		$rs = $obj->listar(); 
		return $rs;
	}

	function consultar_nota($ind,$insc){
		include_once('../MODELO/m_notas.php');
		$obj = new cls_notas();
		$obj->set($insc,$ind,'');
		$rs = $obj->consultar();
		return $rs;
	}

	function consultar_promedio($insc, $lapso){
		include_once('../MODELO/m_notas.php');
		$obj = new cls_notas();
		$obj->setPromedio($insc,'',$lapso);
		$rs = $obj->consultar_promedio();
		return $rs;
	}

	# SILENCIAR ERRORES
	ob_start();
	error_reporting(E_ALL & ~E_NOTICE);
	ini_set('display_errors', 0);
	ini_set('log_errors', 1);

	// VISTA f_repBoletin.php
	if( isset($_GET['aesc']) ){
		$rsAESC = consultar_AESC($_GET['aesc']);
		$codAESC = $rsAESC['cod_periodo'];
		$AESC = $rsAESC['periodo'];
	}

	else{
		$rsAESC = consultar_ultAESC();
		$codAESC = $rsAESC['cod_periodo'];
		$AESC = $rsAESC['periodo'];
	}
	
	if( isset($_GET['ced']) ){
		
		#setlocale(LC_TIME, "C");
		setlocale(LC_TIME, 'spanish'); setlocale(LC_TIME, 'es_ES.UTF-8');
		$fecha = ucfirst(strftime('%d de %B del %Y'));

		include_once('../MODELO/m_inscripcion.php');
		$obj = new cls_inscripcion();
		$obj->set_CE($_GET['tipo_doc'].'-'.$_GET['ced']);
		$obj->set_Periodo($_GET['aesc']);
		
		if( $rs = $obj->boletines() ){ # existe la inscripcion
			# cedula de estudiante
			if( strlen($rs['cedula']) > 2 ){
				$CE = $rs['cedula'];
			}
			else{
				$CE = $rs['ced_esc'];
			}

			$rsRep = consultar_persona_sm($rs['cod_rep']);
			$rsDoc = consultar_persona_sm($rs['docente']);
			# representante
			$ciRep = $rsRep['tipo_documento'].'-'.$rsRep['cedula'];
			$nomRep = $rsRep['nom1'].' '.$rsRep['ape1'];
			# docente
			$nomDoc = $rsDoc['nom1'].' '.$rsDoc['ape1'];
			$ciDoc = $rsDoc['tipo_documento'].'-'.$rsDoc['cedula'];
			
			# TCPDF
			include_once('../MODELO/my_tcpdf.php');
			$pdf = new MYPDF('H', PDF_UNIT,'A4', true, 'UTF-8', false);
			$pdf->SetMargins(8, 30, 8); 
			$pdf->AddPage();
			$pdf->getPlantel();

			if( $_GET['lapso'] != 'F' ){
				$rsLAP = consultar_lapso($_GET['lapso'],$codAESC);
				$rsPA = consultar_PA($rsLAP['cod_lapso'],$rs['seccion']);

				if( $rsPROM = consultar_promedio($rs['cod_insc'],$rsLAP['cod_lapso']) ){

					$pdf->SetFont('Times','B',10); 
					$pdf->Cell(50,1,'Fecha: '.$fecha,0,0,'L');
					$pdf->SetFont('Times','B',9); 
					$pdf->SetX(150,0);
					$pdf->Cell(50,1,'Código DEA: '.$pdf->coddea,1,1,'C');
					$pdf->SetX(150,0);
					$pdf->Cell(50,1,'Año Escolar: '.$rs['periodo'],1,1,'C','');

					$pdf->SetFont('Times','',11); 

					$pdf->SetFont('Times','B',11);
					$pdf->Cell(0,1,'BOLETÍN INFORMATIVO',0,1,'C');
					$pdf->Ln(4);

					$pdf->SetFont('Times','',11);
					
					$tabla = '<table border="1" cellpadding="4">
						<tr>
							<td width="58%"><b>Estudiante:</b> '.$rs['nombres'].''.$rs['apellidos'].'</td>
							<td width="12%" align="center"><b>Edad:</b> '.$rs['edad'].'</td>
							<td width="29%"><b>Grado y sección:</b> '.$rs["grado"].' "'.$rs["letra"].'"</td>
						</tr>
						<tr>
							<td colspan="2" width="65%"><b>Nombre del PA:</b> '.$rsPA['nom_pa'].'</td>
							<td width="34%"><b>Tiempo de Ejecución:</b> '.$rsPA['duracion'].'</td>
						</tr>
						<tr>
							<td width="35%"><b>Docente:</b> '.$nomDoc.'</td>
							<td width="24%"><b>Año Escolar:</b> '.$AESC.'</td>
							<td width="40%"><b>Representante:</b> '.$nomRep.'</td>
						</tr>
					</table>';
					$pdf->writeHTML($tabla, true, false, false, false, '');

					$rsInd = consultar_indicadores($rsPA['cod_proyecto']);
					$datos = '';
					$num=1;

					for ($i=0; $i<count($rsInd); $i++) { 
						$nota = consultar_nota($rsInd[$i]['cod'],$rs['cod_insc']);
						#echo $nota;
						$nA = ''; $nB = ''; $nC = ''; $nD = ''; $nE = '';
						switch ($nota) {
							case 'A': $nA='X'; break;
							case 'B': $nB='X'; break;
							case 'C': $nC='X'; break;
							case 'D': $nD='X'; break;
							case 'E': $nE='X'; break;
						}
						
						$datos = $datos.'<tr>
							<td width="22" align="center">'.$num.'</td>
							<td width="382">'.$rsInd[$i]['nom'].'</td>
							<td width="28" align="center">'.$nA.'</td>
							<td width="28" align="center">'.$nB.'</td>
							<td width="28" align="center">'.$nC.'</td>
							<td width="28" align="center">'.$nD.'</td>
							<td width="28" align="center">'.$nE.'</td>
						</tr>';
						$num++;	
					}

					#$pdf->SetFont('Times','B',10); 
					$tabla = '<table cellpadding="4" border="1"> 
						<tr>
							<td rowspan="2" align="center" width="22"><b>#</b></td>
							<td width="382"></td>
							<td align="center" width="84"><b>Consolidado</b></td>
							<td align="center" width="28"><b>EP</b></td>
							<td align="center" width="28"><b>I</b></td>
						</tr>
						<tr>
							<td><b>INDICADORES DE EVALUACIÓN</b></td>
							<td width="28" align="center"><b>A</b></td>
							<td width="28" align="center"><b>B</b></td>
							<td width="28" align="center"><b>C</b></td>
							<td width="28" align="center"><b>D</b></td>
							<td width="28" align="center"><b>E</b></td>
						</tr>
						'.$datos.'
					</table>';
					$pdf->SetFont('Times','',10);
					$pdf->writeHTML($tabla, true, false, false, false, '');

					$pdf->SetFont('Times','B',11); 
					$pdf->Cell(50,0,'Expresión Literal: "'.$rsPROM['nota'].'"',0,0,'L');
					
					$pdf->SetFont('Times','BI',11);
					$pdf->Cell(0,1,'Leyenda: C: Consolidado  EP: En Proceso  I: Iniciado',0,0,'R');
					$pdf->Ln(10);

					$pdf->SetFont('Times','BI',9);
					$msjLOPNA = '<p style="line-height:5px">LOPNNA: Artículo 54: la obligación de los padres, representantes o responsables a la asistencia regular de los niños y participar activamente en el proceso…<br>
					LOE: Articulo 17: “las familias tienen el deber de formar en principios valores, creencias, actitudes, hábitos a los niños y niñas…”</p>';
					$pdf->writeHTML($msjLOPNA, true, false, false, false, '');
				}else{
					header('location: ../VISTA/?ver=boletin&error=1');
				}
			}
			else{
				# >>>>>>>>>>>>>  REPORTE BOLETIN FINAL
				include_once('../MODELO/m_notas.php');
				$objBF = new cls_notas();
				$objBF->setBoletinFinal($rs['cod_insc'],'','','','');
				if ( $rsBF = $objBF->consultarBF() ){

					$pdf->SetFont('Times','B',10); 
					$pdf->Cell(50,1,'Fecha: '.$fecha,0,0,'L');
					$pdf->SetFont('Times','B',9); 
					$pdf->SetX(150,0);
					$pdf->Cell(50,1,'Código DEA: '.$pdf->coddea,1,1,'C');
					$pdf->SetX(150,0);
					$pdf->Cell(50,1,'Código DEP: '.$pdf->codplantel,1,1,'C','');

					$pdf->SetFont('Times','B',10); 
					$pdf->Cell(0,1,'INFORME DESCRIPTIVO FINAL DEL RENDIMIENTO ESCOLAR',0,1,'L');
					$pdf->Ln(2);


					$pdf->SetFont('Times','',10); 
					# >>
					$fNac = date("d/m/Y", strtotime($rs['fecha_nac']));
					$lugarNac = $rs['desc_lugar'].' - '.strtoupper($rs['nom_edo']);
					if( $rs['nom_pais'] != 'Venezuela' ){
						$lugarNac = $rs['desc_lugar'];
					}
					#>>
					$tabla = '<table border="1" cellpadding="2">
						<tr>
							<td colspan="2"><b>Estudiante:</b> '.$rs['nombres'].''.$rs['apellidos'].'</td>
							<td><b>Grado y Sección:</b> '.$rs["grado"].' "'.$rs["letra"].'"</td>
						</tr>
						<tr>
							<td><b>C.I.:</b> '.$CE.'</td>
							<td><b>Fecha de Nacimiento: </b>'.$fNac.'</td>
							<td><b>Docente: </b> '.$nomDoc.'</td>
						</tr>
						<tr>
							<td colspan="2"><b>Lugar de Nacimiento: </b>'.$lugarNac.'</td>
							<td><b>Año Escolar: </b>'.$AESC.'</td>
						</tr>
					</table>';
					$pdf->writeHTML($tabla, true, false, false, false, '');
				
					$pdf->SetFont('Times','BI',10);
					$pdf->Cell(0,1,'LOGROS ALCANZADOS POR EL ESTUDIANTE',0,1,'C');
					#$pdf->Ln(2);

					# Descripcion
					
					$pdf->SetFont('Times','',10);
					$txtDesc = 
					'<table cellspacing="0" cellpadding="6" border="1">
					<tr>
						<td style="text-align:justify;">'.$rsBF['descripcion'].'</td>
					</tr>
					</table>';
					#$pdf->Cell(0,110,'',1,1,'L');
					$pdf->writeHTML($txtDesc, true, false, false, false, '');
		
					$pdf->SetFont('Times','BI',10);
					$pdf->Cell(0,1,'RECOMENDACIONES',0,1,'C');
					$pdf->SetFont('Times','',10);

					$txtReco = 
					'<table cellspacing="0" cellpadding="6" border="1">
					<tr>
						<td style="text-align:justify;">'.$rsBF['recomendacion'].'</td>
					</tr>
					</table>';
					$pdf->writeHTML($txtReco, true, false, false, false, '');
					$pdf->SetFont('Times','BI',10);
					$pdf->Cell(0,1,'LOGROS ALCANZADOS POR EL ESTUDIANTE',0,1,'C');
					$pdf->Ln(4);
					$pdf->SetFont('Times','B',10);
					$pdf->setX(12);

					$Psi = '';
					$Pno = '';
					$txtProm = '';
					$sexo = '';

					if($rs['sexo'] == 'M'){
						$sexo = 'El estudiante';
					}
					else{
						$sexo = 'La estudiante';
					}

					if($rsBF['promovido'] == 'S'){
						$Psi = 'X';
						$txtProm = '<p style="text-align:center;">…“('.$rsBF["literal"].'). '.$sexo.' logró adquirir las competencias mínimas requeridas para ser promovido al grado superior.”... </p>';
					}else{
						$Pno = 'X';
						$txtProm = '<p style="text-align:center;">…“('.$rsBF["literal"].'). '.$sexo.' No logró adquirir las competencias mínimas requeridas para ser promovido al grado superior.”... </p>';
					}
					$pdf->Cell(62,1,'Expresión Literal: "('.$rsBF['literal'].')"','',0,'C');
					$pdf->Cell(62,1,'Promovido: '.$Psi,'',0,'C');
					$pdf->Cell(62,1,'No Promovido: '.$Pno,'',1,'C');
					$pdf->Ln(2);
					$pdf->SetFont('Times','BI',10);
					$pdf->writeHTML($txtProm, true, false, false, false, '');
					
					$pdf->Ln(20);
					$pdf->SetFont('Times','B',10);
					$ln = '___________________';
					$pdf->Cell(62,1,$ln,'',0,'C');
					$pdf->Cell(62,1,$ln,'',0,'C');
					$pdf->Cell(62,1,$ln,'',1,'C');
					$pdf->Ln(2);
					$sexo_dir = 'DIRECTOR(E):'; # sexo del director
					if( $pdf->sexo_dir == 'F'){
						$sexo_dir = 'DIRECTORA(E):';
					}
					$pdf->Cell(62,1,$sexo_dir,'0',0,'C');
					$pdf->Cell(62,1,'DOCENTE','',0,'C');
					$pdf->Cell(62,1,'REPRESENTANTE','',1,'C');
					# nombres
					$pdf->SetFont('Times','',10);
					$pdf->Cell(62,1,$pdf->nom_director,'',0,'C');
					$pdf->Cell(62,1,$nomDoc,'',0,'C');
					$pdf->Cell(62,1,$nomRep,'',1,'C');
					# cedulas
					$pdf->Cell(62,1,'C.I.N°: '.$pdf->ci_director,'',0,'C');
					$pdf->Cell(62,1,'C.I.N°: '.$ciRep,'',0,'C');
					$pdf->Cell(62,1,'C.I.N°: '.$ciDoc,'',1,'C');
				}
				else{
					header('location: ../VISTA/?ver=boletin&error=1');
				}
			}

			ob_end_clean();
			$pdf->Output();
		}
		else{
			header('location: ../VISTA/?ver=boletin&error=1');
		}
	}

?>
