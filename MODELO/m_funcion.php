<?php 
include_once('MyDB.php');

class cls_funcion extends MyDB{
	
	function __construct(){
		parent::__construct();
	}

	function listar(){
		$sql = "SELECT * FROM funcion ORDER BY cod_funcion ASC";
		$rs = $this->query($sql);

		$lista = array(); $cont = 0;
		while( $fila = $this->f_array($rs) ){
			$lista[$cont]['cod'] = $fila['cod_funcion'];
			$lista[$cont]['nom'] = $fila['nom_funcion'];
			$cont++;
		}
		$this->desconectar();
		return $lista;
	}
}	
?>