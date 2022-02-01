<?php 
include_once('../MODELO/m_inscripcion.php');
include_once('../MODELO/m_seccion.php');
$objSec = new cls_seccion();

# datos de la seccion a consultar
$GETseccion = '';
$codSec = '';
$grado = '';
$letra = '';
$periodoEsc = '';
$ced_docente = '';
$nom_docente = '';
# inscripciones
$total = '';
$rsInsc = '';
# Docente
$codDoc = ''; # seccion correspondiente
$secDoc = '';

# año escolar
$AESC = '';
$codAESC = '';

if( $_SERVER['REQUEST_METHOD'] == 'GET' ){
	$rsAESC = consultar_ultAESC();
	$codAESC = $rsAESC['cod_periodo'];
	$AESC = $rsAESC['periodo'];

	if( $_SESSION['vsn_nivel'] == 4 ){
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
		if( !isset($_GET['resultados']) && $_GET['resultados'] != '0' ){
			if($secDoc != ''){
				header('location: ?ver=lista_matricula&seccion='.$secDoc);
			}
			else{
				header('location: ?ver=lista_matricula&resultados=0');
			}
		}	
	}

	if( isset($_GET['seccion']) ){
		$GETseccion = $_GET['seccion'];
		consultar_seccion( $_GET['seccion'] );
		if( $_SESSION['vsn_nivel'] == 4 ){
			if( $codSec != $secDoc){
				header('location: ?ver=lista_matricula');
			}
		}
	}
}

# AJAX
if( isset($_POST['listarSec']) ){
	opSecciones($_POST['periodo']);
}

function consultar_seccion($codigo){
	global $objSec;
	global $codSec, $grado, $letra, $periodoEsc,$ced_docente,$nom_docente, $total, $rsInsc;
 
	$objSec->set_codigo($codigo); # pasa el codigo de la seccion
	if( $rs = $objSec->consultar() ){
		$codSec = $rs['cod_seccion'];
		$grado = $rs['grado'];
		$letra = $rs['letra'];
		$periodoEsc = $rs['periodo'];
		$ced_docente = $rs['ced_docente'];
		$nom_docente = $rs['nom_docente'];

		# consulta las inscripciones
		include_once('../MODELO/m_inscripcion.php');
		$objInsc = new cls_inscripcion();
		$rsInsc = $objInsc->listar_matricula($codSec);
		$total = count($rsInsc);
	}
	else{
		header('location: ?error=404'); # sin resultados
	}
}

function listar_estudiantes(){
	global $rsInsc, $GETseccion;
	$num = 1;
	for ($i=0; $i<count($rsInsc); $i++) { 
		echo '<tr>';
		echo '<td>'.$num.'</td>';
		echo '<td class="align_left">'.$rsInsc[$i]['ced'].'</td>';
		echo '<td class="align_left">'.$rsInsc[$i]['nom_ape'].'</td>';
		echo '<td>'.$rsInsc[$i]['edad'].'</td>';
		echo '<td><a href="?Estudiante=visualizar&cedEscolar='.$rsInsc[$i]['ced'].'" target="_blank"><div class="acciones"><i class="icon-eye"></i></div></a></td>';
		echo '<td><a href="?ver=notas&est='.$rsInsc[$i]['ced'].'&lapso=1&seccion='.$GETseccion.'" target="_blank" class="acciones"><i class="icon-edit"></i></a></td>';
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


?>