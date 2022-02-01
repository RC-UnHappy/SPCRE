<?php 
	include_once('../MODELO/m_cargo.php');	
	function listar_cargos($sel){
		$obj = new cls_cargo();
		$rs = $obj->consultar();
		for ($i=0; $i<count($rs); $i++){
			if($sel == $rs[$i]['cod']){
				echo '<option value="'.$rs[$i]['cod'].'" selected>'.$rs[$i]['nom'].'</option>';
			} 
			echo '<option value="'.$rs[$i]['cod'].'">'.$rs[$i]['nom'].'</option>';
		}
	}
?>