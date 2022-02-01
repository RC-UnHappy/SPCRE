<?php 
# Seguridad >>>>>>>
include_once('seguridadDinamica.php');
$rsMetodo = consultarMetodos('Plantel');
$sM = '1';

# Es diferente al administrador central
if( $_SESSION['vsn_nivel'] != 1 ){ 
	$sM = $rsMetodo['modf'];
}
# Seguridad <<<<<<

include_once('../MODELO/m_plantel.php');
$objPlantel = new cls_plantel();


$director = '';
$codplantel = '';
$codestco = '';
$coddea = '';
$zonaeduc = '';
$edo = '';
$mun = '';
$nombre = '';
$direccion = "";
$telefono = '';
$correo = '';

if( $datos = $objPlantel->consultar() ){
	$director = $datos['cod_director'];
	$codplantel = $datos['codplantel'];
	$codestco = $datos['codestco'];
	$coddea = $datos['coddea'];
	$zonaeduc = $datos['zonaeduc'];
	$edo = $datos['edo'];
	$mun = $datos['mun'];
	$nombre = htmlspecialchars($datos['nom_escuela']);
	$direccion = htmlspecialchars(($datos['direccion']));
	$telefono = $datos['telefono'];
	$correo = $datos['correo'];
}

if( isset($_POST['ope']) ){ # Existen datos	
	$objPlantel->setDatos($_POST['director'],$_POST['nombre'],$_POST['edo'],$_POST['mun'],$_POST['zonaeduc'],$_POST['codplantel'],$_POST['codestco'],$_POST['coddea'],$_POST['correo'],$_POST['tlfn'],$_POST['direccion']);
	$objPlantel->modificar();
	if( $_FILES['image']['name'] != '' ){
		$_FILES['image']['name'] = 'cintillo.jpg'; # cambiamos el nombre de la imagen
		$img = $_FILES['image']['name'];
		move_uploaded_file( $_FILES['image']['tmp_name'],'../IMG/'.$img);
	}	
	header('location: ../VISTA/index.php?ver=plantel&mod=true');
}

function op_directores($sel=0){
	include_once('../MODELO/m_personal.php');
	$obj = new cls_personal();
	$rs = $obj->listar_personalCargo(1); # cargo = Director(a)
	for ($i=0; $i<count($rs) ; $i++) { 
		if( $sel == $rs[$i]['cod'] ){
			echo '<option value="'.$rs[$i]['cod'].'" selected>'.$rs[$i]['director'].'</option>';
		}
		else{
			echo '<option value="'.$rs[$i]['cod'].'">'.$rs[$i]['director'].'</option>';
		}
	}
}	
?>