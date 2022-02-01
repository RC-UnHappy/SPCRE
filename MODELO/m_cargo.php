<?php 
	include_once('MyDB.php');

	class cls_cargo extends MyDB{
		
		function __construct(){
			parent::__construct();
		}

		function consultar(){
			$sql = "SELECT * FROM cargo";
			$rs = $this->query($sql);
			$lista = array(); $cont = 0;
			while( $fila = $this->f_array($rs) ){
				$lista[$cont]['cod'] = $fila['cod_cargo'];
				$lista[$cont]['nom'] = $fila['nom_cargo'];
				$cont++;
			}
			
			$this->desconectar();
			return $lista;
		}
	}	
?>