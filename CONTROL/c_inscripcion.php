<?php 
	include_once('../MODELO/m_inscripcion.php');
	$objInsc = new cls_inscripcion();

	$codInsc = '';
	$cod_rep = '';
	$tdoc_rep = '';
	$ced_rep = '';
	$parentesco = '';
	$nom_rep = '';
	$seccion = ''; # codigo de la sección
	$modo_insc = '';
	$condicion = '';
	$fechaIncs = '';
	$procedencia = '';
	$motivo = '';
	$getMODO = '';
	$bloqModalidad = '';

	if( isset($_GET['modo']) ){
		switch ($_GET['modo']){
			case 'nuevo':
				$getMODO = 'N';
				break;
			
			case 'regular':
				$getMODO = 'R';
				break;
		}
		$bloqModalidad = 'disabled';
	}
	
	if( isset($_POST['ope_insc']) ){
		$codPeriodo = $_POST['codPeriodo'];
		$codInsc = $_POST['codigoInsc'];
		$codEst = $_POST['Est'];
		$cedEst =  $_POST['cedEst'];
		$codRep = $_POST['Rep'];
		$parentesco = $_POST['parent_r'];
		$seccion = $_POST['seccion'];
		$modo_insc = $_POST['modo'];
		$cond = $_POST['condicion'];
		$fechaIncs = $_POST['fecha'];
		$procedencia = $_POST['procedencia'];
		$motivo = $_POST['motivo'];
		
		$objInsc->set_datos_insc($codEst,$codRep,$parentesco,$seccion,$modo_insc,$cond,$fechaIncs,$procedencia,$motivo,$codPeriodo);
		switch ($_POST['ope_insc']) {
			case 'reg':
				if( $objInsc->incluir_insc() ){ # Inscribe
					header('location: ../VISTA/?Estudiante=visualizar&cedEscolar='.$cedEst.'&insc=true');
				}
				else{
					header('location: ../VISTA/?Estudiante=visualizar&cedEscolar='.$cedEst.'&insc=false');
				}
			break;

			case 'mod':
				$objInsc->set_codigo($codInsc);
				if( $seccion != $_POST['seccionH'] ){
					if( verificar_cupos($seccion) ){
						header('location: ../VISTA/?Estudiante=visualizar&cedEscolar='.$cedEst.'&insc=false');
					}
					else{
						$objInsc->modificar_insc($_POST['seccionH']);
						header('location: ../VISTA/?Estudiante=visualizar&cedEscolar='.$cedEst.'&insc=true');
					}
				}
				else{
					$objInsc->modificar_insc();
					header('location: ../VISTA/?Estudiante=visualizar&cedEscolar='.$cedEst.'&insc=true');
				}
			break;
		}
	}

	# imprime el historial del estudiante
	function imp_historial_Insc($codEst){ 
		global $objInsc;
		$objInsc->set_codEst($codEst);
		if( $rs = $objInsc->listar_historial_est() ){
			for ($i=0; $i<count($rs) ; $i++){
				$modo = '';
				switch ($rs[$i]['modo']) {
					case 'N':$modo = 'Nuevo Ingreso';break;
					case 'R':$modo = 'Regular';break;
				}

				switch ($rs[$i]['cond']) {
					case 'P':$cond = 'Promovido';break;
					case 'R':$cond = 'Repitiente';break;
					case 'T':$cond = 'Traslado';break;
				}

				$gdoNum = $rs[$i]['gdo'];
				switch ($gdoNum) {
					case '1':$gdo = 'ero';break;
					case '2':$gdo = 'do';break;
					case '3':$gdo = 'ero';break;
					case '4':$gdo = 'to';break;
					case '5':$gdo = 'to';break;
					case '6':$gdo = 'to';break;
				}

				echo '<tr>';
					echo '<td>'.$rs[$i]['rep'].'</td>';
					echo '<td>'.$gdoNum.$gdo.' '.'"'.$rs[$i]['lta'].'"</td>';
					echo '<td>'.date('d/m/Y',strtotime($rs[$i]['fecha'])).'</td>';
					echo '<td>'.$modo.'</td>';
					echo '<td>'.$cond.'</td>';
					echo '<td>'.$rs[$i]['proc'].'</td>';
					echo '<td>'.$rs[$i]['motivo'].'</td>';
					echo '<td>'.$rs[$i]['periodo'].'</td>';
				echo '</tr>';
			}
		}
		else{
			echo '<tr><td colspan="8">No se encontraron resultados</td></tr>';
		}
	}

	function consultar_inscripcion($est, $periodo){
		global $objInsc, $codInsc,$cod_rep,$ced_rep,$nom_rep,$parentesco,$seccion,$modo_insc, $condicion, $fechaIncs, $procedencia, $motivo;
		global $getMODO, $objRep;
		$objInsc->set_codEst($est);
		$objInsc->set_Periodo($periodo);
		if( $rs = $objInsc->consultar() ){ # inscrito en el año escolar
			$codInsc = $rs['cod_insc'];
			$seccion = $rs['seccion']; # codigo de la sección
			$fechaIncs = $rs['fecha'];
			$modo_insc = $rs['modalidad'];
			$condicion = $rs['condicion'];
			$procedencia = $rs['escuela_proc'];
			$motivo = $rs['motivo'];
			$objRep->set_codigoPersona($rs['cod_rep']);
			$rsRep = $objRep->consultar_representante2();
			$cod_rep = $rs['cod_rep'];
			$tdoc_rep = $rsRep['tipo_documento'];
			$ced_rep = $rsRep['cedula'];
			$nom_rep = $rsRep['nombre'];
			$parentesco = $rs['parentesco'];
		}
		else{
			$modo_insc = $getMODO;
		}
	}

	function listar_secciones($seccion){
		global $cod_AESC;
		include_once('../MODELO/m_seccion.php');
		$obj = new cls_seccion();
		$obj->set_periodo($cod_AESC);
		$secDoc = ''; # seccion del docente

		if( $_SESSION['vsn_nivel'] == 4  ){ # El usuario es un docente
			$obj->set_docente($_SESSION['vsn_codPer']); # pasa el codigo de la persona
			$rsDoc = $obj->buscar_docente_seccion(); # consulta la seccion del docente
			$secDoc = $rsDoc['cod_seccion'];
		}

		$rs = $obj->listar_sm();
		for ($i=0; $i<count($rs); $i++){
			switch ($rs[$i]['gdo']) {
			 	case '1': $gdo = '1ERO'; break;
			 	case '2': $gdo = '2DO'; break;
			 	case '3': $gdo = '3ERO'; break;
			 	case '4': $gdo = '4TO'; break;
			 	case '5': $gdo = '5TO'; break;
			 	case '6': $gdo = '6TO'; break;
			 }
			if( $seccion != '' && $seccion == $rs[$i]['cod'] ){ # estudiante inscrito en una sección, selecciona el valor
				echo '<option value="'.$rs[$i]['cod'].'" selected>'.$gdo.' - "'.$rs[$i]['lta'].'"</option>';
			}
			else if( $seccion == '' && $secDoc == $rs[$i]['cod'] ){ # selecciona la seccion del docente
				echo '<option value="'.$rs[$i]['cod'].'" selected>'.$gdo.' - "'.$rs[$i]['lta'].'"</option>';
			}
			else{
				echo '<option value="'.$rs[$i]['cod'].'">'.$gdo.' - "'.$rs[$i]['lta'].'"</option>';
			} 
			#echo '<option value="'.$rs[$i]['cod'].'">'.$gdo.' - "'.$rs[$i]['lta'].'"</option>';
		}
	}

	function verificar_cupos($codSec){
		include_once('../MODELO/m_seccion.php');
		$obj = new cls_seccion();
		$obj->set_codigo($codSec);
		$rs = $obj->consultar();
		if( $rs['cupos'] <= 0 ){ # sin cupos
			return true;
		}
	}
?>