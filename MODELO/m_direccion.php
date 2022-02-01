<?php 
	include_once('MyDB.php');
	class cls_direccion extends MyDB{
		private $codigo, $parroquia, $sector, $calle, $nro;

		function __construct(){
			parent::__construct(); # constructor del padre
			$this->codigo = null;
			$this->parroquia = null;
			$this->sector = null;
			$this->calle = null;
			$this->nro = null;
		}

		function set_direccion($parr,$sector,$calle,$nro){ # pasa valor a las propiedades
			$this->parroquia = trim($parr);
			$this->sector = mb_strtoupper($this->limpiarCadena($sector));
			$this->calle = mb_strtoupper($this->limpiarCadena($calle));
			$this->nro = mb_strtoupper($this->limpiarCadena($nro));
		}

		function incluir(){ # agrega una nueva direccion
			$sql = "INSERT INTO direccion (parroquia,sector,calle,nro) VALUES ('$this->parroquia','$this->sector','$this->calle','$this->nro')";
			$this->query($sql);
		}

		function consultar(){ # busca una direccion
			$sql = "SELECT * FROM direccion WHERE parroquia='$this->parroquia' AND sector='$this->sector' AND calle='$this->calle' AND nro='$this->nro'";
			if( $result = $this->query($sql ) ){
				$this->desconectar();
				return $this->f_array($result);
			}
		}

		function get_codigo(){ # devuelve el codigo de la direccion
			if( $rs = $this->consultar() ){ # comprueba que exista la dirección
				return $rs['cod_dir'];
			}
			else{
				$this->incluir(); # incluye la dirección
				$id = $this->ultimo_id();
				$this->desconectar();
				return $id; # devuelve el código de la dirección
				#$rs = $this->consultar(); # busca
				#return $rs['cod_dir']; # se obtiene el código de la dirección
			}
		}
	}

?>