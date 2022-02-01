<?php 
	include_once('../MODELO/m_lugares.php'); # incluye las clases pais, estado, municipio, parroquia

	function listar_Op($res,$valor){ # listar opciones en combo select
		for($i=0; $i<count($res); $i++){
			# se imprimen los resultados
			if( $valor == $res[$i]['cod'] ){ # si el valor es igual al codigo lo selecciona
				echo '<option value="'.$res[$i]['cod'].'" selected>'.$res[$i]['nom'].'</option>';
			}
			else{
				echo '<option value="'.$res[$i]['cod'].'">'.$res[$i]['nom'].'</option>';
			}
		}
	}

	if( isset($_POST['listarEst']) ){  # muestra los estados
		estadosOp($_POST['cod_pais'],'');
	}

	if( isset($_POST['listarMun']) ){ # muestra los munipios
		municipiosOp($_POST['cod_edo'],'');
	}

	if( isset($_POST['listarParr']) ){ # muestra las parroquias 
		parroquiasOp($_POST['cod_mun'],'');
	}

	# FUNCIONES
	function paisesOp($valor){
		$objPais = new cls_pais();
		$res = $objPais->listar_paises();
		listar_Op($res, $valor);
	}
	function estadosOp($pais, $valor){
		$objEst = new cls_estado();
		$objEst->set_pais($pais); # venezuela
		$res = $objEst->listar_estados();
		listar_Op($res, $valor);
	}
	function municipiosOp($edo, $valor){
		$objMun = new cls_municipio();
		$objMun->set_estado($edo);
		$res = $objMun->listar_municipios();
		listar_Op($res, $valor);
	}
	function parroquiasOp($mun, $valor){
		$objParr = new cls_parroquia();
		$objParr->set_municipio($mun);
		$res = $objParr->listar_parroquias();
		listar_Op($res, $valor);
	}
?>