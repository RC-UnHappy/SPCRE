<?php 
	include_once('MyDB.php');

	class cls_grado_instruccion extends MyDB{
		// private $codigo, $nombre;

		function __construct(){
			parent::__construct(); # constructor del padre
		}

		function listar(){ # devuelve array asiciativo 
			$lista = array(); $cont = 0;
			$sql = "SELECT * FROM grado_instruccion";
			$res = $this->query($sql);
			while ($fila = $this->f_array($res)) {
				$lista[$cont]['cod'] = $fila['cod_ginst']; 
				$lista[$cont]['nom'] = $fila['nom_ginst'];
				$cont++;
			}
			return $lista;
		}
	}
?>