<?php 
	include_once('MyDB.php');
	# Pais, estado, municipio, parroquia
	class cls_pais extends MyDB{
		private $codigo, $nombre;

		function __construct(){
			parent::__construct(); # constructor del padre
			$this->codigo = null;
			$this->nombre = null;
		}
		
		function listar_paises(){
			$lista = array(); $cont = 0;
			$sql = "SELECT cod_pais,nom_pais FROM paises";
			$res = $this->query($sql);
			while($fila = $this->f_array($res)){
				$lista[$cont]['cod'] = $fila['cod_pais'];
				$lista[$cont]['nom'] = $fila['nom_pais'];
				$cont++;
			}
			return $lista;
		}
	}

	class cls_estado extends MyDB{
		private $codigo, $nombre, $pais;

		function __construct(){
			parent::__construct(); # constructor del padre
			$this->codigo = null;
			$this->nombre = null;
			$this->pais = null;
		}

		function set_pais($pais){
			$this->pais = $pais;
		}

		function listar_estados(){ # lista los estados y devuelve un array
			$lista = array(); $cont = 0;
			$sql = "SELECT cod_edo,nom_edo FROM estado WHERE pais = '$this->pais'";
			$res = $this->query($sql);
			while($fila = $this->f_array($res)){
				$lista[$cont]['cod'] = $fila['cod_edo'];
				$lista[$cont]['nom'] = $fila['nom_edo'];
				$cont++;
			}
			return $lista;
		}
	}

	class cls_municipio extends MyDB{
		private $codigo, $nombre, $estado;

		function __construct(){
			parent::__construct(); # constructor del padre
			$this->codigo = null;
			$this->nombre = null;
			$this->estado = null;
		}

		function set_estado($edo){
			$this->estado = $edo;
		}

		function listar_municipios(){ # lista los municipios y devuelve un array
			$lista = array(); $cont = 0;
			$sql = "SELECT cod_mun,nom_mun FROM municipio WHERE estado = '$this->estado'";
			$res = $this->query($sql);
			while($fila = $this->f_array($res)){
				$lista[$cont]['cod'] = $fila['cod_mun'];
				$lista[$cont]['nom'] = $fila['nom_mun'];
				$cont++;
			}
			return $lista;
		}
	}

	class cls_parroquia extends MyDB{
		private $codigo, $nombre, $municipio;

		function __construct(){
			parent::__construct(); # constructor del padre
			$this->codigo = null;
			$this->nombre = null;
			$this->municipio = null;
		}

		function set_municipio($mun){
			$this->municipio = $mun;
		}

		function listar_parroquias(){
			$lista = array(); $cont = 0;
			$sql = "SELECT cod_parr,nom_parr FROM parroquia WHERE municipio = '$this->municipio'";
			$res = $this->query($sql);
			while($fila = $this->f_array($res)){
				$lista[$cont]['cod'] = $fila['cod_parr'];
				$lista[$cont]['nom'] = $fila['nom_parr'];
				$cont++;
			}
			return $lista;
		}
	}
?>
