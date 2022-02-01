<?php 

$AESC = '';
$codAESC = '';

$rsAESC = consultar_ultAESC();
$codAESC = $rsAESC['cod_periodo'];
$AESC = $rsAESC['periodo'];
$mesActual = date('m');

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

# AJAX
if( isset($_POST['listarSec']) ){
	opSecciones($_POST['periodo']);
}
?>