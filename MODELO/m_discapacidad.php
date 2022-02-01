<?php 
	include_once('MyDB.php');

	class cls_discapacidad extends MyDB{
		function __construct(){
			parent::__construct();
		}
		function listar(){ # lista todas las discapacidades
			$lista = array(); $cont = 0;
			$sql = "SELECT * FROM discapacidad";
			$rs = $this->query($sql);
			while( $fila = $this->f_array($rs) ){
				$lista[$cont]['cod'] =  $fila['cod_discpd'];
				$lista[$cont]['nom'] =  $fila['nom_discpd'];
				$cont++;
			}
			return $lista;
		}
	}
?>