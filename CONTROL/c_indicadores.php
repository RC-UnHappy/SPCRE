<?php 
# Seguridad >>>>>>>
include_once('seguridadDinamica.php');
$rsMetodo = consultarMetodos('Indicador');
$sI = '1';
$sM = '1';
$sC = '1';
$sE = '1';
# Es diferente al administrador central
if( $_SESSION['vsn_nivel'] != 1 ){ 
	$sI = $rsMetodo['inc'];
	$sM = $rsMetodo['modf'];
	$sC = $rsMetodo['cons'];
	$sE = $rsMetodo['elm'];
}
# Seguridad <<<<<<

# Instancias
include_once('../MODELO/m_seccion.php');
include_once('../MODELO/m_pa.php');
include_once('../MODELO/m_indicador.php');
include_once('../MODELO/m_lapso.php');

$objSec = new cls_seccion();
$objPA = new cls_PA();

$objLapso = new cls_lapso();
$objInd = new cls_indicador();

# Año escolar
$AESC = '';
$codAESC = '';

# >>>>> Seccion
$codSec = '';
$grado = '';
$letra = '';
$cod_periodo = '';
$periodoEsc = '';

$GETlapso = '';
$codLapso = '';
$eLapso = ''; # estatus del lapso

# PA
$codPA = '';
$nomPA = '';
$tiempoPA = '';
$ePA = ''; # estatus PA

$modoInput = '';

# DOCENTE:
$codDoc = '';
$secDoc = ''; # seccion del docente


# >>>> POST
if( isset($_POST['opePA']) && !empty($_POST['opePA'])){
	$codLapso = $_POST['codLapso'];
	$codPA = $_POST['codPA'];
	$nomPA = $_POST['nomPA'];
	$tiempoPA = $_POST['tiempoPA'];
	$seccion = $_POST['seccion'];
	$objPA->setPA($nomPA,$seccion,$codLapso,$tiempoPA);

	if($_POST['opePA'] == 'reg'){
		$objPA->incluirPA();
		header('location: ../VISTA/?ver=indicadores&seccion='.$seccion.'&lapso='.$_POST['lapso'].'&opePA=reg');
	}
	else{
		$objPA->setCodigo($_POST['codPA']);
		$objPA->modificarPA();
		header('location: ../VISTA/?ver=indicadores&seccion='.$seccion.'&lapso='.$_POST['lapso'].'&opePA=mod');
	}	
}

else if( isset($_POST['opeIND']) && !empty($_POST['opeIND']) ){
	$codInd = $_POST['codIND'];
	$nomInd = $_POST['hNameInd'];
	$codPA = $_POST['codPA'];
	$seccion = $_POST['seccion'];
	$objInd->setDatos($codInd,$nomInd,$codPA);

	switch ($_POST['opeIND']) {
		case 'reg':
			$objInd->incluir();
			header('location: ../VISTA/?ver=indicadores&seccion='.$seccion.'&lapso='.$_POST['lapso'].'&opeInd=reg');
			break;
		
		case 'mod':
			$objInd->modificar();
			header('location: ../VISTA/?ver=indicadores&seccion='.$seccion.'&lapso='.$_POST['lapso'].'&opeInd=mod');
			break;

		case 'elm':
			$objInd->eliminar();
			header('location: ../VISTA/?ver=indicadores&seccion='.$seccion.'&lapso='.$_POST['lapso'].'&opeInd=elm');
			break;
	}	
}
else if( isset($_POST['cerrarIndH']) ){
	$objPA->setCodigo($_POST['codPA']);
	$objPA->CerrarInd($_POST['cerrarIndH']);
	header('location: ../VISTA/?ver=indicadores&seccion='.$_POST['seccion'].'&lapso='.$_POST['lapso'].'&opePA=modEstatus');
}

# AJAX
else if( isset($_POST['listarSec']) ){
	imprimir_sec($_POST['codPeriodo']);
}

if( $_SERVER['REQUEST_METHOD'] == 'GET' ){
	# Es docente
	if( $_SESSION['vsn_nivel'] == 4){ 
		include_once('../MODELO/m_a_escolar.php');
		$objAesc = new cls_a_escolar();
		$rs = $objAesc->ultimo_aesc();
		if( $rs['estatus'] == 'A' ) { # año escolar abierto
			# consulta la seccion del docente
			$objSec->set_periodo($rs['cod_periodo']);
			$objSec->set_docente($_SESSION['vsn_codPer']);
			# existe la seccion en el año escolar?
			if ( $rsSec = $objSec->buscar_docente_seccion() ){
				$codDoc = $rsSec['docente'];
				$secDoc = $rsSec['cod_seccion'];
			}
		}
	}

	# redireccion a la seccion del docente 
	if( $_SESSION['vsn_nivel'] == 4 && !isset($_GET['seccion']) ){
		if( !isset($_GET['error']) && $_GET['error'] != '2' ){
			if($secDoc != ''){
				header('location: ?ver=indicadores&seccion='.$secDoc.'&lapso=1');
			}
			else{
				header('location: ?ver=indicadores&error=2');
			}
		}	
	}

	if( isset($_GET['seccion']) && !empty($_GET['seccion']) && isset($_GET['lapso']) && !empty($_GET['lapso']) ){
		$codSec = $_GET['seccion'];
		$GETlapso = $_GET['lapso'];

		if( $_SESSION['vsn_nivel'] == 4 ){
			if( $codSec != $secDoc ){ 
				header('location: ?ver=indicadores');
			}
		}

		if($GETlapso > 0 && $GETlapso <= 3){
			# >>>> CONSULTAS
			consultar_seccion($codSec);
			consultar_lapso($GETlapso, $cod_periodo);
			consultar_PA();
		}
		
		else{
			header('location: ?ver=indicadores');
		}
	}

	$rsAESC = consultar_ultAESC();
	$codAESC = $rsAESC['cod_periodo'];
	$AESC = $rsAESC['periodo'];	
}

function consultar_ultAESC(){ # ultimo año escolar
	include_once('../MODELO/m_a_escolar.php');
	$obj = new cls_a_escolar();
	$rs = $obj->ultimo_aesc();	
	return $rs;
}

function consultar_seccion($codigo){
	global $objSec;
	global $codSec, $grado, $letra, $cod_periodo, $periodoEsc;
	$objSec->set_codigo($codigo); # pasa el codigo de la seccion

	if( $rs = $objSec->consultar() ){
		$codSec = $rs['cod_seccion'];
		$grado = $rs['grado'];
		$letra = $rs['letra'];
		$cod_periodo = $rs['cod_periodo'];
		$periodoEsc = $rs['periodo'];
	}
}
function consultar_lapso($lapso, $aesc){
	global $objLapso, $codLapso, $eLapso;
	$objLapso->setLapso($lapso, $aesc);
	$rs = $objLapso->consultar();
	$codLapso = $rs['cod_lapso'];
	$eLapso = '';
	if( $rs['estatus']=='C' || $rs['estatus'] == 'N' ){
		$eLapso = 'C';
		global $modoInput;
		$modoInput = 'disabled';
	}
}
function consultar_PA(){
	global $objPA;
	global $codSec, $codPA, $nomPA, $tiempoPA, $ePA, $codLapso;
	$objPA->setPA('',$codSec,$codLapso,'');
	if($rs = $objPA->consultarPA() ){
		$codPA = $rs['cod_proyecto'];
		$nomPA = $rs['nom_pa'];
		$tiempoPA = $rs['duracion'];
		$ePA = $rs['estatus'];
		if($ePA == 'C'){
			global $modoInput;
			$modoInput = 'disabled';
		}
	}
}

function listar_indicadores(){
	global $objInd, $codPA, $ePA, $eLapso;
	global $sI,$sM,$sC,$sE;
	$objInd->setDatos('','',$codPA);
	$rs = $objInd->listar();
	$num = 1;
	for ($i=0; $i<count($rs); $i++) { 
		echo '<tr>';
		echo '<td class="align_left">'.$num.'</td>';
		echo '<td id="nomInd'.$rs[$i]['cod'].'" class="align_left">'.$rs[$i]['nom'].'</td>';

		if($ePA == 'C'){
			echo '<td>---</td>';
		}
		else if($eLapso == 'C'){
			echo '<td>---</td>';
		}
		else{
			echo '<td align="center">';
			if( $sM == '1' ){
				echo '<div onclick=W_OpenMod('.$rs[$i]['cod'].') class="acciones" ><i class="icon-edit"></i><div class="info">Modificar</div></div>';
			}
			if( $sE == '1' ){
				echo '<div onclick="W_eliminar('.$rs[$i]['cod'].')" class="acciones" ><i class="icon-trash-empty"></i><div class="info">Eliminar</div></div>';
			}
			if( $sM == '0' && $sE == '0' ){
				echo '---';
			}		
			echo '</td>';
		}
		$num++;
	}
}

# Imprime las secciones de un año escolar en un combo select
function imprimir_sec($codAesc=null){ #codAesc toma valor si se esta ejecutando una consulta con AJAX
	global $objAesc, $objSec;
	if( $codAesc == null){ 
		$rs = $objAesc->ultimo_aesc(); # Consulta el ultimo año escolar
		$objSec->set_periodo($rs['cod_periodo']);
	}
	else{
		$objSec->set_periodo($codAesc);
	}

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
	else{
		echo '<option value="0"> SIN RESULTADOS </option>';
	}
}

function url404(){
	header('location: ?error=404');
	//header('location: ../VISTA/partials/error404.html');
}
?>