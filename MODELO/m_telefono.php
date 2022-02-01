<?php 
	class cls_telefono extends MyDB{
		private $num_telefono; # codigo auto-increment

		function __construct(){
			parent::__construct(); # constructor del padre
			$this->num_telefono = null;
		}

		# pasa valor a las propiedades
		function set_telefono($num_tlf){
			$this->num_telefono = $num_tlf;
		}

		function incluir_tlf(){ # registra un nuevo telefono
			$sql = "INSERT INTO telefono (numero) VALUES ('$this->num_telefono')";
			$this->query($sql);
			$this->desconectar(); 
		}

		function consultar_tlf(){ # selecciona un numero de telefono
			$sql = "SELECT * FROM telefono WHERE numero = '$this->num_telefono'";
			$arr = $this->f_array($this->query($sql));
			$this->desconectar(); 
			return $arr; # devuelve un array con los datos
		}

		function get_codigoTlf(){ # envia el codigo del telefono, en caso de que no exista... incluye
			if( $res = $this->consultar_tlf() ){
				return $res['cod_tlf'];  # devuelve el codigo
			}
			else{
				$this->incluir_tlf(); # incluye
				$res = $this->consultar_tlf();
				return $res['cod_tlf']; # devuelve el codigo
			}
		}
	}
?>