<?php 
	require_once('MyDB.php');

	class cls_indicador extends MyDB{
		 private $codigo, $nombre, $codPA; 

		function __construct(){
			parent::__construct();
			$this->codigo = null;
			$this->nombre = null;
			$this->codPA = null;
		}

		function setDatos($cod,$nom,$codPA){
			$this->codigo = $cod;
			$this->nombre = $this->limpiarCadena($nom);
			$this->codPA = $codPA;
		}

		function incluir(){
			$sql = "INSERT INTO indicador (nom_ind, cod_proyecto) VALUES ('$this->nombre','$this->codPA')";
			$this->query($sql);
			
			if( $this->f_affectrows() ){
				return true;
			}
		}

		function modificar(){
			$sql = "UPDATE indicador SET nom_ind = '$this->nombre' WHERE cod_ind = '$this->codigo'";
			$this->query($sql);
		}

		function eliminar(){
			$sql = "DELETE FROM indicador WHERE cod_ind = '$this->codigo'";
			$this->query($sql);
		}

		function listar(){
			$sql = "SELECT * FROM indicador WHERE cod_proyecto = '$this->codPA'";
			
			$rs = $this->query($sql);
			$lista = array(); $i=0;

			while($fila=$this->f_array($rs)){
				$lista[$i]['cod'] = $fila['cod_ind'];
				$lista[$i]['nom'] = $fila['nom_ind'];
				$lista[$i]['codPA'] = $fila['cod_proyecto'];
				$i++;
			}
			return $lista;
		}
	}
 ?>