<?php 
# Seguridad >>>>>>>
include_once('seguridadDinamica.php');
$rsMetodo = consultarMetodos('Cargar Notas');
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

# Año escolar
$cod_AESC = '';
$per_AESC = '';
$sta_AESC = '';
$aperturaNotas = false;

# Estudiante / Inscripcion
$codInsc = ''; # codigo de inscripcion
$cedEst = ''; # cedula
$nomEst = ''; # nombres
$apeEst = ''; # apellidos
$codSeccion = '';
$grado = '';
$letra = '';
$gradoLetra = '';

# Lapso
$codLapso = '';
$lapso = '';
$eLapso = '';
$operarNL = false; # variable que confirma si sa van a realizar operaciones en el formulario notas por lapso
$operarNF = false; #                       ||                   nota final 


# PA
$codPA = '';
$nomPA = ''; 
$ePA = '';

 
$rsInd = ''; 
$promedio = '';
$pm = ''; # promedio manual

$arrInd = '';
$arrNota = '';

$modoInput = ''; # enabled/disabled

###  Boletín Final
$staLAPSOS = "false";
$desc = '';
$reco = '';
$literal = '';
$promoSI = 'checked';
$promoNO = '';
$poseeBF = 0;


if( isset($_GET['est']) ){
	consultar_aesc();

	if( $sta_AESC == 'A' ){ # Año escolar activo

		consultar_inscripcion($_GET['est']);
		$lapso = $_GET['lapso'];
		# notas por lapso

		if( $lapso != 'F' ){
			if( $lapso > 3 || $lapso < 1 ){
				header('location: ../VISTA/?ver=error404');
			}

			consultar_lapso();
			consultar_proyecto_ap();
			consultar_indicadores(); # indicadores y notas
			consultar_promedio();
			
			if( $eLapso == 'A' ){
				if( $ePA == 'C' ){
					$operarNL = true;
				}
			}
		}

		# nota final
		else if( $lapso=='F' ){
			$rs = consultar_lapsosAESC();
			$cont = 0;
			for ($i=0; $i<count($rs); $i++) {
				if($rs[$i]['sta']=='C'){
					$cont++;
				}
			}
			if($cont == 3 ){
				$staLAPSOS = "true";
				$operarNF = true;
				$aperturaNotas = true;
			}
			consultar_BF();
		}
	}	
}

function consultar_inscripcion( $ced ){
	global $cod_AESC;
	global $codInsc, $cedEst, $nomEst, $apeEst, $codSeccion, $grado, $letra, $gradoLetra;
	include_once('../MODELO/m_inscripcion.php');
	$obj = new cls_inscripcion();
	$obj->set_Periodo( $cod_AESC );
	$obj->set_CE( $ced );
	if ( $rs = $obj->consultar2() ){
		$codInsc = $rs['cod_insc']; 
	
		if( strlen($rs['cedula']) > 2 ){ # posee cedula
			$cedEst = $rs['cedula'];
		}
		else{
			$cedEst = $rs['ced_esc'];
		}
		$nomEst = $rs['nombres']; 
		$apeEst = $rs['apellidos']; 
		$codSeccion = $rs['seccion'];
		$grado = $rs['grado'];
		$letra = $rs['letra'];
		$gradoLetra = 'Grado: <b class="text_rosa">'.$grado.'° </b> Sección: <b class="text_rosa">"'.$letra.'"</b>';
	}
	else{
		header('location: ../VISTA/?ver=lista_matricula&error=1');
	}
}

function consultar_lapso(){
	global $lapso, $codLapso, $eLapso, $cod_AESC, $aperturaNotas;
	include_once('../MODELO/m_lapso.php');
	$obj = new cls_lapso();
	$obj->setLapso($lapso, $cod_AESC);
	$rs = $obj->consultar();
	$codLapso = $rs['cod_lapso'];
	$eLapso = $rs['estatus'];
	
	$hoyA = strtotime( date('Y-m-d H:i:s') ); # hora del servidor
	$restar = strtotime('-30 minute', $hoyA ); # restar media hora para hora oficial de venezuela
	$hoy = date('Y-m-d H:i:s', $restar); # hora transformada
	$hoy = strtotime($hoy);

	$desde = strtotime( $rs['apertura_notas'] );
	$hasta = strtotime( $rs['cierre_notas'] );
	
	if( $desde < $hasta && $hoy > $desde){
		if( $hoy < $hasta ){
			$aperturaNotas = true;
		}
	}
}

function consultar_proyecto_ap(){
	global $codPA, $nomPA, $ePA, $codLapso, $codSeccion;
	include_once('../MODELO/m_pa.php');
	$obj = new cls_PA();
	$obj->setPA('',$codSeccion,$codLapso,'');
	if( $rs = $obj->consultarPA() ){
		$codPA = $rs['cod_proyecto'];
		$nomPA = $rs['nom_pa'];
		$ePA = $rs['estatus'];
	}
}

function consultar_indicadores(){
	global $rsInd, $lapso, $codPA;
	include_once('../MODELO/m_indicador.php');
	$obj = new cls_indicador();
	$obj->setDatos('','',$codPA);
	# rsNota almacena la consulta de los indicadores y las notas
	$rsInd = $obj->listar(); 

	$arr1 = array(); # arreglo de indicadores
	$arr2 = array(); # nota respectiva

	for ($i=0; $i<count($rsInd); $i++){
		array_push($arr1, $rsInd[$i]['cod']);
		$nota = consultar_nota($rsInd[$i]['cod']); # nota del respectivo indicador
		if( !$nota ){
			$nota = '---'; # sin nota
		}
		array_push($arr2, $nota);
	}

	global $arrInd, $arrNota;
	# convierte arreglos en string
	$arrInd = implode($arr1,','); 
	$arrNota = implode($arr2,',');
}

function consultar_nota($ind){
	global $codInsc;
	include_once('../MODELO/m_notas.php');
	$obj = new cls_notas();
	$obj->set($codInsc,$ind,'');
	$rsNota = $obj->consultar();
	return $rsNota;
}

function consultar_promedio(){ // por lapso
	global $codInsc, $lapso, $eLapso, $promedio, $pm, $codLapso;
	include_once('../MODELO/m_notas.php');
	$obj = new cls_notas();
	$obj->setPromedio($codInsc,'',$codLapso);
	$rs = $obj->consultar_promedio();
	$promedio = $rs['nota'];
	$pm = $rs['pm'];
}

function crear_lista(){
	global $rsInd, $arrNota, $eLapso, $operarNL;
	$num=1;
	
	for ($i=0; $i<count($rsInd); $i++){
		echo '<tr>';
		echo '<td>'.$num.'</td>';
		echo '<td class="text_left itemI">'.$rsInd[$i]['nom'].'</td>';

		if( $eLapso != 'C' ){
			$nA = ''; $nB = ''; $nC= ''; $nD = ''; $nE = '';
			$expArrNota = explode(',',$arrNota);

			switch ($expArrNota[$i]) {
				case 'A': $nA='selected'; break;
				case 'B': $nB='selected'; break;
				case 'C': $nC='selected'; break;
				case 'D': $nD='selected'; break;
				case 'E': $nE='selected'; break;
			}

			$disabled = '';
			if( $operarNL == false ){
				$disabled = 'disabled';
			}

			// cambiar evento a oninput u onchange
			echo '<td><select name="nota'.$rsInd[$i]['cod'].'" class="input text_center selNotas" oninput="promediar()" '.$disabled.' >';
				echo '<option value="---"> --- </option>';
				echo '<option value="A" '.$nA.'>A</option>';
				echo '<option value="B" '.$nB.'>B</option>';
				echo '<option value="C" '.$nC.'>C</option>';
				echo '<option value="D" '.$nD.'>D</option>';
				echo '<option value="E" '.$nE.'>E</option>';
			echo '</select></td>';
			echo '</tr>';
		}	
		else{
			$expArrNota = explode(',',$arrNota);
			echo '<td>'.$expArrNota[$i].'</td>';
			echo '</tr>';
		}
		$num++;
	}
}

# BOLETIN FINAL <<<<<<<<<<
function consultar_aesc(){
	global $cod_AESC, $per_AESC, $sta_AESC;
	include_once('../MODELO/m_a_escolar.php'); # modelo año escolar
	$objAesc = new cls_a_escolar();
	if( $rs = $objAesc->ultimo_aesc() ){
		$cod_AESC = $rs['cod_periodo'];
		$per_AESC = $rs['periodo'];
		$sta_AESC = $rs['estatus'];	
	}
}

function consultar_lapsosAESC(){ # lapsos del año escolar
	include_once('../MODELO/m_lapso.php');
	global $cod_AESC;
	$obj = new cls_lapso();
	$obj->set_codPeriodo($cod_AESC);
	$rs = $obj->listar();
	return $rs;
}

function consultar_BF(){
	global $codInsc, $cod_AESC;
	global $desc, $reco, $literal, $promoSI, $promoNO, $poseeBF;
	include_once('../MODELO/m_notas.php');
	$obj = new cls_notas();
	$obj->setInsc($codInsc);
	# existen datos
	if( $rs = $obj->consultarBF() ){
		$poseeBF = 1;
		$desc = $rs['descripcion'];
		$reco = $rs['recomendacion'];
		$literal = $rs['literal'];
		if($rs['promovido']=='S'){
			$promoSI = 'checked';
			$promoNO = '';
		}
		else{
			$promoSI = '';
			$promoNO = 'checked';
		}
	}
}

# >>>>>>>>>>>>>> POST <<<<<<<<<<<<<<<<<<

if( isset($_POST['fn']) ){
	include_once('../MODELO/m_notas.php');
	$obj = new cls_notas();
	$arrInd = explode(',',$_POST['arrInd']);
	$arrNota = explode(',',$_POST['arrNota']);

	for ($i=0; $i<count($arrInd); $i++) { 
		$obj->set($_POST['codInsc'],$arrInd[$i],$arrNota[$i]);
		$obj->opeNotas();
	}

	$obj->setPromedio($_POST['codInsc'],$_POST['promedio'],$_POST['codLapso']);
	$obj->opePromedio($_POST['pm']);
	header('location: ../VISTA/?ver=notas&est='.$_POST['cedEst'].'&lapso='.$_POST['lapso'].'&ope=true');
} 

if(isset($_POST['opeBF'])){
	include_once('../MODELO/m_notas.php');
	$obj = new cls_notas();
	$obj->setBoletinFinal($_POST['codInsc'],$_POST['desc'],$_POST['rec'],$_POST['literal'],$_POST['prom']);

	if($_POST['opeBF'] == 'reg'){
		$obj->incluirBF();
		header('location: ../VISTA/?ver=notas&est='.$_POST['cedEst'].'&lapso=F&opeBF=reg');
	}	
	else{
		$obj->modificarBF();
		header('location: ../VISTA/?ver=notas&est='.$_POST['cedEst'].'&lapso=F&opeBF=mod');
	}
}
	
?>