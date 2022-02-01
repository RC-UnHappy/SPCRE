<?php 
	include_once('MyDB.php');

	class cls_tipo_vivienda extends MyDB{
		private $codigo, $nombre;

		function __construct(){
			parent::__construct(); # constructor del padre
			$this->codigo = null;
			$this->nombre = null;
		}

		function get_datos($cod, $nom){
			$this->codigo = trim(strtoupper($cod));
			$this->nombre = trim(ucfirst($nom));
		}

		function incluir(){
			$sql = "INSERT INTO tipo_vivienda (cod_tipo_vda, nom_tipo_vda ) VALUES ('$this->codigo','$this->nombre') ";
			$this->query($sql);
			if( $this->f_affectrows() ){
				return true;
			}
		}

		function modificar(){
			$sql = "UPDATE tipo_vivienda SET nom_tipo_vda = '$this->nombre' WHERE cod_tipo_vda = '$this->codigo'";
			$this->query($sql);
		}

		function listar(){
			$lista = array(); $cont = 0;
			$sql = "SELECT * FROM tipo_vivienda";
			$res = $this->query($sql);
			while ($fila = $this->f_array($res)) {
				$lista[$cont]['cod'] = $fila['cod_tipo_vda']; 
				$lista[$cont]['nom'] = $fila['nom_tipo_vda'];
				$cont++;
			}
			return $lista;
		}
	}
?>