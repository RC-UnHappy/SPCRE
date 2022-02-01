<?php 
	include_once('../MODELO/m_grado_instruccion.php');
	$objGrdInstr = new cls_grado_instruccion();

	function listarOpGrdInst($valor){
		global $objGrdInstr;
		$rs = $objGrdInstr->listar();
		for($i=0;$i<count($rs);$i++){
			if( $valor == $rs[$i]['cod'] ){
				echo '<option value="'.$rs[$i]['cod'].'" selected>'.$rs[$i]['nom'].'</option>';
			}
			else{
				echo '<option value="'.$rs[$i]['cod'].'">'.$rs[$i]['nom'].'</option>';
			}
		}
	}
?>