<?php
function consultar_estudiante( $ced ){
	include_once('../MODELO/m_estudiante.php');
 	$obj = new cls_estudiante();
 	$obj->set_cedEsc(substr($ced,0,1),substr($ced, 2));
 	if( $rs = $obj->consultar_estudiante() ){	
 		return $rs;
 	}
}
function consultar_inscripcion($periodo,$ced){
	include_once('../MODELO/m_inscripcion.php');
	$objInsc = new cls_inscripcion();
	$objInsc->set_Periodo($periodo);
	$objInsc->set_CE($ced);
	if( $rs = $objInsc->consultar_inscripcion() ){
		return $rs;
	}
}

function consultar_ult_insc($ced){
	# ultima isncripción activa o inactiva
	include_once('../MODELO/m_inscripcion.php');
	$objInsc = new cls_inscripcion();
	$objInsc->set_CE($ced);
	if( $rs = $objInsc->consultar_ult() ){
		return $rs;
	}
}

function consultar_ult_inscA($ced){
	include_once('../MODELO/m_inscripcion.php');
	$objInsc = new cls_inscripcion();
	$objInsc->set_CE($ced);
	if( $rs = $objInsc->consultar_ult_A() ){
		return $rs;
	}
}
function consultar_ult_inscI($ced){ # ultima inscripcion inactiva
	include_once('../MODELO/m_inscripcion.php');
	$objInsc = new cls_inscripcion();
	$objInsc->set_CE($ced);
	if( $rs = $objInsc->consultar_ult_I() ){
		return $rs;
	}
}
function consultar_retiro($periodo, $ced){
	include_once('../MODELO/m_egreso.php');
	$obj = new cls_egreso();
	$obj->setCedula($ced);
	$obj->setPeriodo($periodo);
	if( $rs = $obj->consultarRetiroReporte() ){
		return $rs;
	}
}
function notaFinal($codInsc){
	include_once('../MODELO/m_notas.php');
	$obj = new cls_notas();
	$obj->setInsc($codInsc);
	$rs = $obj->consultar_notaFinal();
	if( $rs['promovido'] == 'S' ){
		return $rs;
	}
}
function notaFinal_anterior($ced, $aesc){
	include_once('../MODELO/m_notas.php');
	$obj = new cls_notas();
	$obj->setCE($ced);
	$obj->setPeriodo($aesc);
	if( $rs = $obj->consultar_notaFinal_2() ){
		return $rs;
	}
}
function transformar_grado($grado){
	$gt = '';
	switch ($grado) {
		case $grado >= 4 : $gt = $grado.'TO'; break;
		case $grado <= 3: $gt = $grado.'ER'; break;
	}
	return $gt;
}
function calcular_edad($edad){
	include_once('../MODELO/m_persona.php');
	$obj = new cls_persona();
	return $obj->calcular_edad($edad);
}
function sin_resultados(){ // no se encuentron resultados
	header('location: ../VISTA/?ver=solicitud&datos=false');
}

function h404(){
	header('location: ../VISTA/?error=404');
}

if( isset($_GET['reporte']) && !empty($_GET['cedEsc']) && !empty($_GET['aesc']) ){

	ob_start();
	error_reporting(E_ALL & ~E_NOTICE);
	ini_set('display_errors', 0);
	ini_set('log_errors', 1);

	# Existe el estudiante
	if( $rsEst = consultar_estudiante($_GET['cedEsc']) ){
		# Datos del estudiante
		if( $rsEst['cedula'] != '' ){
			$CE = $rsEst['tipo_documento'].'-'.$rsEst['cedula'];
		}
		else{
			$CE = $rsEst['ced_esc'];
		}
		$nombres = $rsEst['nom1'].' '.$rsEst['nom2'];
		$apellidos = $rsEst['ape1'].' '.$rsEst['ape2'];
		$fnac = date('d-m-Y', strtotime($rsEst['fecha_nac']));
		$edad = calcular_edad($rsEst['fecha_nac']);
		$LugarNac = $rsEst['desc_lugar'];
		$estatus = $rsEst['estatus'];

		# Estudiante Activo
		if( $estatus == '1' || $estatus == '2' ){
			# PDF
			setlocale(LC_TIME, 'spanish'); setlocale(LC_TIME, 'es_ES.UTF-8');
			include_once('../MODELO/my_tcpdf.php');
			$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false);
			$pdf->SetMargins(20, 20, 20); 
			$pdf->getPlantel();
			$pdf->sinPag(); # quitar la numeracion de paginas
			$pdf->AddPage();

			switch ($_GET['reporte']) {
				case '1': # CONSTANCIA DE INSCRIPCIÓN
					# Existe la inscripción en el año escolar activo
					if( $rs = consultar_inscripcion($_GET['aesc'],$_GET['cedEsc']) ){
						# Datos de la inscripcion
						$gdo = $rs['grado'];
						$lta = $rs['letra'];
						$aesc = $rs['periodo'];

						$pdf->Ln(10);
						$pdf->Image('../IMG/escudo_venezuela.jpg', '90', '', 30, 30, '', '', '', false, 300, '', false, false, 0, false, false, false);
						$pdf->Ln(35);
						$pdf->SetFont('helvetica','B', 18);
						$pdf->Cell(0,6,'CONSTANCIA DE INSCRIPCIÓN',0,1,'C');
						$pdf->Ln(15);
						$pdf->SetFont('helvetica','', 12);

						$txt = '<p align="justify" style="line-height:2; text-indent:35px;">Quien suscribe, <b><u> '.$pdf->nom_director.' </u></b> titular de La Cédula De Identidad Nº <b><u> '.$pdf->ci_director.' </u></b>, en su condición de Director(a) (E) de la <b><u> '.$pdf->nom_escuela.' </u></b>, ubicado en el municipio <b><u> '.$pdf->mun.' </u></b> de la parroquia <b><u> Araure </u></b>
						adscrito a la Zona Educativa del Estado <b><u> '.$pdf->zonaeduc.' </u></b> certifica por medio de la presente que el (la) estudiante: <b><u> '.$nombres.' '.$apellidos.' </u></b> titular de la cédula de identidad Nº: <b><u> '.$CE.' </u></b>, está inscrito (a) en éste plantel y cursa el
						<b><u> '.transformar_grado($gdo). ' GRADO</u></b>, Sección: <b><u> "'.$lta.'" </u></b> del Nivel de Educación Primaria, durante el año escolar <b><u> '.$aesc.' </u></b>.</p>
						<p align="justify" style="line-height:2;"> Constancia que se expide en <b>'.$pdf->mun.'</b> a los <b>'.Date('d').'</b> días del mes de <b>'.ucfirst(strftime('%B')).'</b> del año <b>'.Date('Y').'</b>.</p>';
						
						$pdf->writeHTML($txt, true, false, true, false, ''); 
						$pdf->Ln(15);

						$txt = 
						'<table border="1" cellspacing="0" cellpadding="3" width="400px">
							<tr><td align="center"><b>Autoridad Educativa</b></td><td rowspan="4" align="center"><b><br><br><br><br><br>SELLO DEL PLANTEL</b></td></tr>
							<tr><td>Director (a): <b>'.$pdf->nom_director.'</b></td></tr>
							<tr><td>Número de C.I: <b>'.$pdf->ci_director.'</b></td></tr>
							<tr><td>Firma: <br><br><br><br><br><br></td></tr>
						</table>';

						$pdf->SetX(37);
						$pdf->SetFont('helvetica','', 10);
						$pdf->writeHTML($txt, true, false, true, false, ''); 
					}
					# No existe la inscripcion
					else{
						sin_resultados();
					}
					break;

				case '2': # CONSTANCIA DE ESTUDIO
					# Existe la inscripción en el año escolar activo
					if( $rs = consultar_inscripcion($_GET['aesc'],$_GET['cedEsc']) ){
						# Datos de la inscripcion
						$gdo = $rs['grado'];
						$lta = $rs['letra'];
						$aesc = $rs['periodo'];

						$pdf->Ln(10);
						$pdf->Image('../IMG/escudo_venezuela.jpg', '90', '', 30, 30, '', '', '', false, 300, '', false, false, 0, false, false, false);
						$pdf->Ln(35);
						$pdf->SetFont('helvetica','B', 18);
						$pdf->Cell(0,6,'CONSTANCIA DE ESTUDIO',0,1,'C');
						$pdf->Ln(15);
						$pdf->SetFont('helvetica','', 12);

						$txt = '<p align="justify" style="line-height:2; text-indent:35px;">Quien suscribe, <b><u> '.$pdf->nom_director.' </u></b> titular de La Cédula De Identidad Nº <b><u> '.$pdf->ci_director.' </u></b>, en su condición de Director(a) (E) de la <b><u> '.$pdf->nom_escuela.' </u></b>, ubicado en el municipio <b><u> '.$pdf->mun.' </u></b> de la parroquia <b><u> Araure </u></b>
						adscrito a la Zona Educativa del Estado <b><u> '.$pdf->zonaeduc.' </u></b> certifica por medio de la presente que el (la) estudiante: <b><u> '.$nombres.' '.$apellidos.' </u></b> titular de la cédula de identidad Nº: <b><u> '.$CE.' </u></b>, cursa el
						<b><u> '.transformar_grado($gdo). ' GRADO</u></b>, Sección: <b><u> "'.$lta.'" </u></b> del Nivel de Educación Primaria, durante el año escolar <b><u> '.$aesc.' </u></b>.</p>
						<p align="justify" style="line-height:2;"> Constancia que se expide en <b>'.$pdf->mun.'</b> a los <b>'.Date('d').'</b> días del mes de <b>'.ucfirst(strftime('%B')).'</b> del año <b>'.Date('Y').'</b>.</p>';	

						$pdf->writeHTML($txt, true, false, true, false, ''); 
						$pdf->Ln(15);

						$txt = 
						'<table border="1" cellspacing="0" cellpadding="3" width="400px">
							<tr><td align="center"><b>Autoridad Educativa</b></td><td rowspan="4" align="center"><b><br><br><br><br><br>SELLO DEL PLANTEL</b></td></tr>
							<tr><td>Director (a): <b>'.$pdf->nom_director.'</b></td></tr>
							<tr><td>Número de C.I: <b>'.$pdf->ci_director.'</b></td></tr>
							<tr><td>Firma: <br><br><br><br><br><br></td></tr>
						</table>';

						$pdf->SetX(37);
						$pdf->SetFont('helvetica','', 10);
						$pdf->writeHTML($txt, true, false, true, false, ''); 
					}
					# No existe la inscripcion
					else{
						sin_resultados();
					}
					break;

				case '5': # CONSTANCIA DE PROSECUCIÓN
					if( $rsInsc = consultar_ult_insc($_GET['cedEsc']) ){
						# Datos de la inscripcion
						$gdo = $rsInsc['grado'];
						$lta = $rsInsc['letra'];
						$aesc = $rsInsc['periodo'];
						$arr = explode('-', $aesc);
						$arr[0]--; $arr[1]--;
						$aescAnterior=$arr[0].'-'.$arr[1];
						if( $rsNota = notaFinal_anterior($_GET['cedEsc'], $aescAnterior) ){
							if( $rsNota['promovido'] == 'S' ){
								$pdf->Ln(10);
								$pdf->Image('../IMG/escudo_venezuela.jpg', '90', '', 30, 30, '', '', '', false, 300, '', false, false, 0, false, false, false);
								$pdf->Ln(35);
								$pdf->SetFont('helvetica','B', 18);
								$pdf->Cell(0,6,'CONSTANCIA DE PROSECUCIÓN',0,1,'C');
								$pdf->Cell(0,6,'EN EL NIVEL DE EDUCACIÓN PRIMARIA',0,0,'C');
								$pdf->Ln(20);
								$pdf->SetFont('helvetica','', 12);

								$txt = '<p align="justify" style="line-height:2; text-indent:35px;">Quien suscribe, <b><u> '.$pdf->nom_director.' </u></b> titular de La Cédula De Identidad Nº <b><u> '.$pdf->ci_director.' </u></b>, en su condición de Director(a) (E) de la <b><u> '.$pdf->nom_escuela.' </u></b>, ubicado en el municipio <b><u> '.$pdf->mun.' </u></b> de la parroquia <b><u> Araure </u></b>
								adscrito a la Zona Educativa del Estado <b><u> '.$pdf->zonaeduc.' </u></b>  certifica por medio de la presente que el (la) estudiante: <b><u> '.$nombres.' '.$apellidos.' </u></b> titular de la cédula de identidad Nº: <b><u> '.$CE.' </u></b>, nacido (a) en: <b><u> '.$LugarNac.' </u></b> en fecha: <b><u> '.$fnac.' </u></b>, 
								cursó el <b><u> '.transformar_grado($rsNota['grado']). ' GRADO</u></b> correspondiendole el literal: <b><u> "'.$rsNota['literal'].'" </u></b> durante el periodo escolar: <b><u> '.$rsNota['periodo'].' </u></b>, <b>siendo promovido (a) al <u> '.transformar_grado($gdo).' GRADO </u> del Nivel de Educación Primaria.</b></p>
								<p align="justify" style="line-height:2;"> Constancia que se expide en <b>'.$pdf->mun.'</b> a los <b>'.Date('d').'</b> días del mes de <b>'.ucfirst(strftime('%B')).'</b> del año <b>'.Date('Y').'</b>.</p>';
								
								$pdf->writeHTML($txt, true, false, true, false, ''); 
								$pdf->Ln(15);

								$txt = 
								'<table border="1" cellspacing="0" cellpadding="3" width="400px">
									<tr><td align="center"><b>Autoridad Educativa</b></td><td rowspan="4" align="center"><b><br><br><br><br><br>SELLO DEL PLANTEL</b></td></tr>
									<tr><td>Director (a): <b>'.$pdf->nom_director.'</b></td></tr>
									<tr><td>Número de C.I: <b>'.$pdf->ci_director.'</b></td></tr>
									<tr><td>Firma: <br><br><br><br><br><br></td></tr>
								</table>';
								
								$pdf->SetFont('helvetica','', 10);
								$pdf->SetX(37);
								$pdf->writeHTML($txt, true, false, true, false, ''); 
							}	
							else{
								sin_resultados(); # el año anterior no fue promovido
							}
						}
						else{
							sin_resultados();
						}
					}
					else{
						sin_resultados();
					}
					break;

				default:
					sin_resultados();
					break;
			}
		}

		# Estudiante Retirado
		else if( $estatus == '3' ){
			# existe el retiro
			if( $rs = consultar_retiro($_GET['aesc'],$_GET['cedEsc']) ){
				if( $rsI = consultar_ult_inscI($_GET['cedEsc']) ){
					$gdo = $rsI['grado'];
					$lta = $rsI['letra'];
					$aesc = $rsI['periodo'];
					
					# PDF
					setlocale(LC_TIME, 'spanish'); setlocale(LC_TIME, 'es_ES.UTF-8');
					include_once('../MODELO/my_tcpdf.php');
					$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false);
					$pdf->SetMargins(20, 20, 20); 
					$pdf->getPlantel();
					$pdf->sinPag(); # quitar la numeracion de paginas
					$pdf->AddPage();

					switch ($_GET['reporte']) {
						case '3': # CONSTANCIA DE CONDUCTA
							$pdf->Ln(10);
							$pdf->Image('../IMG/escudo_venezuela.jpg', '90', '', 30, 30, '', '', '', false, 300, '', false, false, 0, false, false, false);
							$pdf->Ln(35);
							$pdf->SetFont('helvetica','B', 18);
							$pdf->Cell(0,6,'CONSTANCIA DE CONDUCTA',0,1,'C');
							$pdf->Ln(15);
							$pdf->SetFont('helvetica','', 12);

							$txt = '<p align="justify" style="line-height:2; text-indent:35px;">Quien suscribe, <b><u> '.$pdf->nom_director.' </u></b> titular de La Cédula De Identidad Nº <b><u> '.$pdf->ci_director.' </u></b>, en su condición de Director(a) (E) de la <b><u> '.$pdf->nom_escuela.' </u></b>, ubicado en el municipio <b><u> '.$pdf->mun.' </u></b> de la parroquia <b><u> Araure </u></b>
							adscrito a la Zona Educativa del Estado <b><u> '.$pdf->zonaeduc.' </u></b>  certifica por medio de la presente que el (la) estudiante: <b><u> '.$nombres.' '.$apellidos.' </u></b> titular de la cédula de identidad Nº: <b><u> '.$CE.' </u></b>, nacido (a) en: <b><u> '.$LugarNac.' </u></b> en fecha: <b><u> '.$fnac.' </u></b>, 
							cursante del <b><u> '.transformar_grado($gdo). ' GRADO</u></b>, Sección: <b><u> "'.$lta.'" </u></b> del Nivel de Educación Primaria, se observó durante su permanencia en ésta intitución una conducta: <b><u> Buena </u></b>.</p>
							<p align="justify" style="line-height:2;"> Constancia que se expide en <b>'.$pdf->mun.'</b> a los <b>'.Date('d').'</b> días del mes de <b>'.ucfirst(strftime('%B')).'</b> del año <b>'.Date('Y').'</b>.</p>';

							$pdf->writeHTML($txt, true, false, true, false, ''); 
							$pdf->Ln(15);

							$txt = 
							'<table border="1" cellspacing="0" cellpadding="3" width="400px">
								<tr><td align="center"><b>Autoridad Educativa</b></td><td rowspan="4" align="center"><b><br><br><br><br><br>SELLO DEL PLANTEL</b></td></tr>
								<tr><td>Director (a): <b>'.$pdf->nom_director.'</b></td></tr>
								<tr><td>Número de C.I: <b>'.$pdf->ci_director.'</b></td></tr>
								<tr><td>Firma: <br><br><br><br><br><br></td></tr>
							</table>';

							$pdf->SetFont('helvetica','', 10);
							$pdf->SetX(37);
							$pdf->writeHTML($txt, true, false, true, false, ''); 
							break;
						
						case '4':
							$causa = $rs['causa'];
							switch ($causa) {
								case '1': $causa = 'Cambio de domicilio'; break;
								case '2': $causa = 'Enfermedad'; break;
								case '3': $causa = 'Defunción'; break;
							}
							$pdf->Ln(10);
							$pdf->Image('../IMG/escudo_venezuela.jpg', '90', '', 30, 30, '', '', '', false, 300, '', false, false, 0, false, false, false);
							$pdf->Ln(35);
							$pdf->SetFont('helvetica','B', 18);
							$pdf->Cell(0,6,'CONSTANCIA DE CONDUCTA',0,1,'C');
							$pdf->Ln(15);
							$pdf->SetFont('helvetica','', 12);

							$txt = '<p align="justify" style="line-height:2; text-indent:35px;">Quien suscribe, <b><u> '.$pdf->nom_director.' </u></b> titular de La Cédula De Identidad Nº <b><u> '.$pdf->ci_director.' </u></b>, en su condición de Director(a) (E) de la <b><u> '.$pdf->nom_escuela.' </u></b>, ubicado en el municipio <b><u> '.$pdf->mun.' </u></b> de la parroquia <b><u> Araure </u></b>
							adscrito a la Zona Educativa del Estado <b><u> '.$pdf->zonaeduc.' </u></b>  certifica por medio de la presente que el (la) estudiante: <b><u> '.$nombres.' '.$apellidos.' </u></b> titular de la cédula de identidad Nº: <b><u> '.$CE.' </u></b>, nacido (a) en: <b><u> '.$LugarNac.' </u></b> en fecha: <b><u> '.$fnac.' </u></b>, 
							cursante del <b><u> '.transformar_grado($gdo). ' GRADO</u></b>, Sección: <b><u> "'.$lta.'" </u></b> del Nivel de Educación Primaria, se retira de la institución por: <b><u> '.$causa.' </u></b>.</p>
							<p align="justify" style="line-height:2;"> Constancia que se expide en <b>'.$pdf->mun.'</b> a los <b>'.Date('d').'</b> días del mes de <b>'.ucfirst(strftime('%B')).'</b> del año <b>'.Date('Y').'</b>.</p>';

							$pdf->writeHTML($txt, true, false, true, false, ''); 
							$pdf->Ln(15);

							$txt = 
							'<table border="1" cellspacing="0" cellpadding="3" width="400px">
								<tr><td align="center"><b>Autoridad Educativa</b></td><td rowspan="4" align="center"><b><br><br><br><br><br>SELLO DEL PLANTEL</b></td></tr>
								<tr><td>Director (a): <b>'.$pdf->nom_director.'</b></td></tr>
								<tr><td>Número de C.I: <b>'.$pdf->ci_director.'</b></td></tr>
								<tr><td>Firma: <br><br><br><br><br><br></td></tr>
							</table>';

							$pdf->SetFont('helvetica','', 10);
							$pdf->SetX(37);
							$pdf->writeHTML($txt, true, false, true, false, ''); 
							break;
					}	
				}
				else{ # no existe la ulstima inscripcion
					sin_resultados();
				}
			}
			else{ #  No existe el retiro
				sin_resultados();
			}
		}

		# Estudiante Graduado
		else if( $estatus == '4' ){
			# en construccion
			setlocale(LC_TIME, 'spanish'); setlocale(LC_TIME, 'es_ES.UTF-8');
			include_once('../MODELO/my_tcpdf.php');
			$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false);
			$pdf->SetMargins(20, 20, 20); 
			$pdf->getPlantel();
			$pdf->sinPag(); # quitar la numeracion de paginas
			$pdf->AddPage();

			if( $rsInsc = consultar_ult_inscA($CE) ){
				#print_r($rsInsc);
				if( $_GET['reporte'] == '6' ){
					if( $rsNota = notaFinal($rsInsc['cod_insc']) ){
						#print_r($rsNota);
						// Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
						$pdf->Ln(10);
						$pdf->Image('../IMG/escudo_venezuela.jpg', '90', '', 30, 30, '', '', '', false, 300, '', false, false, 0, false, false, false);
						$pdf->Ln(35);
						$pdf->SetFont('helvetica','B', 18);
						$pdf->Cell(0,6,'CERTIFICADO DE EDUCACIÓN PRIMARIA',0,0,'C');
						$pdf->Ln(15);
						$pdf->SetFont('helvetica','', 12);

						$txt = '<p align="justify" style="line-height:2; text-indent:35px;">Quien suscribe, <b><u> '.$pdf->nom_director.' </u></b> titular de La Cédula De Identidad Nº <b><u> '.$pdf->ci_director.' </u></b>, en su condición de Director(a) (E) de la <b><u> '.$pdf->nom_escuela.' </u></b>, ubicado en el municipio <b><u> '.$pdf->mun.' </u></b> de la parroquia <b><u> Araure </u></b>
						adscrito a la Zona Educativa del Estado <b><u> '.$pdf->zonaeduc.' </u></b>  certifica por medio de la presente que el (la) estudiante: <b><u> '.$nombres.' '.$apellidos.' </u></b> titular de la cédula de identidad Nº: <b><u> '.$CE.' </u></b>, nacido (a) en: <b><u> '.$LugarNac.' </u></b> en fecha: <b><u> '.$fnac.' </u></b>, 
						cursó el <b><u> '.transformar_grado($rsInsc['grado']). ' GRADO</u></b> correspondiendole el literal: <b><u> "'.$rsNota['literal'].'" </u></b> durante el periodo escolar: <b><u> '.$rsInsc['periodo'].' </u></b>, <b>siendo promovido (a) al <u> 1ER AÑO </u> del Nivel de Educación Media</b>, previo cumplimiento a los requisitos establecidos 
						en la Normativa Legal vigente.</p><p align="justify" style="line-height:2;"> Constancia que se expide en <b>'.$pdf->mun.'</b> a los <b>'.Date('d').'</b> días del mes de <b>'.ucfirst(strftime('%B')).'</b> del año <b>'.Date('Y').'</b>.</p>';

						$pdf->writeHTML($txt, true, false, true, false, ''); 
						$pdf->Ln(10);

						$txt = 
						'<table border="1" cellspacing="0" cellpadding="3" width="400px">
							<tr><td align="center"><b>Autoridad Educativa</b></td><td rowspan="4" align="center"><b><br><br><br><br><br>SELLO DEL PLANTEL</b></td></tr>
							<tr><td>Director (a): <b>'.$pdf->nom_director.'</b></td></tr>
							<tr><td>Número de C.I: <b>'.$pdf->ci_director.'</b></td></tr>
							<tr><td>Firma: <br><br><br><br><br><br></td></tr>
						</table>';

						$pdf->SetFont('helvetica','', 10);
						$pdf->SetX(37);
						$pdf->writeHTML($txt, true, false, true, false, ''); 
						
						$pdf->Ln(6);
						$txt='<p><b>Certificado válido a nivel nacional e internacional</b></p>';
						$pdf->writeHTML($txt, true, false, true, false, ''); 
					}
					else{
						sin_resultados();
					}
				}
			}
			else{
				sin_resultados();
			}
		}
		ob_end_clean();
		$pdf->Output();
	}
	# No existe el estudiante
	else{
		sin_resultados();
	}
}
else{
	h404();
}

?>