<?php 
	include_once('MyDB.php');

	class cls_ocupacion extends MyDB{
		private $codigo, $nombre, $filtro; # codigo auto-increment
		public $filas;

		function __construct(){
			parent::__construct(); # constructor del padre
			$this->codigo = null;
			$this->nombre = null;
			$this->filtro = null;
			$this->filas = null;
		}
		function set_datos($cod, $nom){ # pasa valor a las propiedades de la clase
			$this->codigo = $cod;
			$this->nombre = ucfirst($this->limpiarCadena($this->solo_letras($nom)));
		}
		function set_filtro($filtro){
			$this->filtro = $this->limpiarCadena($filtro);
		}
		function agregar(){ # incluye un nuevo registro
			$sql = "INSERT INTO ocupacion (nom_ocup) VALUES ('$this->nombre') ";
			$this->query($sql);
			if( $this->f_affectrows() ){ # registró?
				return true;
			}
		}
		function modificar(){ # modifica un registro
			$sql = "UPDATE ocupacion SET nom_ocup = '$this->nombre' WHERE cod_ocup = '$this->codigo'";
			if( $rs = $this->consultar() ){ # existe el nombre
				if( $rs['cod_ocup'] == $this->codigo ){ # es el mismo
					$this->query($sql);
					return true;
				}	
			}else{ # no existe
				$this->query($sql);
				return true;
			}
		}
		function consultar(){ # consulta una tupla
			$sql = "SELECT * FROM ocupacion WHERE nom_ocup = '$this->nombre'";
			if( $rs = $this->query($sql) ){
				return $this->f_array($rs);
			}
		}
		function listar(){ # recolecta todos los registos de labla
			$sql = "SELECT * FROM ocupacion ORDER BY nom_ocup ASC";
			$res = $this->query($sql);
			return $this->envia_array($res);
		}

		function listar_limit($desde=0,$mostrar=15){ # lista todas las enfermedades, con limite
			$sql = "SELECT * FROM ocupacion ORDER BY nom_ocup ASC LIMIT $desde,$mostrar";
			$sqlF = "SELECT count(cod_ocup) FROM ocupacion";
			$this->filas = $this->total_filas($sqlF); # total de registros encontrados
			$res = $this->query($sql);
			return $this->envia_array($res);
		}

		function filtrar($desde=0,$mostrar=15){ # lista las enfermedades por filtro 
			$sqlF = "SELECT count(cod_ocup) FROM ocupacion WHERE nom_ocup LIKE '$this->filtro%'";
			$this->filas = $this->total_filas($sqlF); # total de registros encontrados
			$sql = "SELECT * FROM ocupacion WHERE nom_ocup LIKE '$this->filtro%' ORDER BY nom_ocup ASC LIMIT $desde,$mostrar";
			$res = $this->query($sql);
			return $this->envia_array($res);
		}

		function envia_array($res){ # recibe la consulta cargada en memoria y la transforma en un arreglo
			$lista = array(); $cont = 0;
			while( $fila = $this->f_array($res) ){
				$lista[$cont]['cod'] = $fila['cod_ocup']; 
				$lista[$cont]['nom'] = $fila['nom_ocup'];
				$cont++;
			}
			return $lista;
		}
	}
?>