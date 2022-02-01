<?php 
	include_once('MyDB.php');

	class cls_lugarNacimiento extends MyDB{
		private $parroquia, $lugar;

		function __construct(){
			parent::__construct(); # constructor del padre
			$this->parroquia = null;
			$this->lugar = null;
		}

		function getCodigo($parr,$lugar){ # devuelve codigo de un lugar de nacimiento
			$this->parroquia = $parr;
			$this->lugar = mb_strtoupper($this->limpiarCadena($lugar)); # limpia espacios en blanco

			if( $rs = $this->consultar() ){
				return $rs['cod_lugar_nac'];
			}
			else{ # si no existe incluye, y retorna el código
				$this->incluir();
				$rs = $this->consultar();
				return $rs['cod_lugar_nac'];	
			}
		}

		function incluir(){
			$sql = "INSERT INTO lugar_nacimiento (cod_parr,desc_lugar) VALUES ('$this->parroquia','$this->lugar')";
			$this->query($sql);
		}

		function consultar(){
			$sql = "SELECT * FROM lugar_nacimiento WHERE cod_parr = '$this->parroquia' AND desc_lugar = '$this->lugar'";
			return $this->f_array($this->query($sql)); # devuelve la tupla en un array
		}
	}
?>