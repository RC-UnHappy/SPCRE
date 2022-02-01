<?php 
	include_once('MyDB.php');

	class cls_vacuna extends MyDB{
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
			$this->nombre = ucfirst($this->limpiarCadena(htmlentities($nom)));
		}

		function set_filtro($filtro){
			$this->filtro = $this->limpiarCadena($filtro);
		}

		function agregar(){ # incluye un nuevo registro
			$sql = "INSERT INTO vacuna (nom_vcna) VALUES ('$this->nombre')";
			$this->query($sql);
			if( $this->f_affectrows() ){ # registró?
				return true;
			}
		}

		function modificar(){ # modifica un registro
			$sql = "UPDATE vacuna SET nom_vcna = '$this->nombre' WHERE cod_vcna = '$this->codigo'";
			if( $rs = $this->consultar() ){ # existe el nombre de la vacuna
				if( $rs['cod_vcna'] == $this->codigo ){ # es el mismo
					$this->query($sql);
					return true;
				}	
			}else{
				$this->query($sql);
				return true;
			}
		}

		# Elimina una vacuna
		function eliminar(){
			$sql = "DELETE FROM vacuna WHERE cod_vcna = '$this->codigo'";
			$this->query($sql); 
			if( $this->f_affectrows() ){
				return true;
			}
		}	

		function consultar(){ # consulta una vacuna
			$sql = "SELECT * FROM vacuna WHERE nom_vcna = '$this->nombre'";
			if( $rs = $this->query($sql) ){
				return $this->f_array($rs);
			}
		}

		function listar(){ # recolecta todos los registos de labla
			$sql = "SELECT * FROM vacuna ORDER BY nom_vcna ASC";
			$res = $this->query($sql);
			return $this->envia_array($res);
		}

		function listar_limit($desde=0,$mostrar=15){ # lista todas las vacunas, con limite
			$sql = "SELECT * FROM vacuna ORDER BY nom_vcna ASC LIMIT $desde,$mostrar";
			$sqlF = "SELECT count(cod_vcna) FROM vacuna";
			$this->filas = $this->total_filas($sqlF); # total de registros encontrados
			$res = $this->query($sql);
			return $this->envia_array($res);
		}

		function filtrar($desde=0,$mostrar=15){ # lista las vacunas por filtro 
			$sqlF = "SELECT count(cod_vcna) FROM vacuna WHERE nom_vcna LIKE '$this->filtro%'";
			$this->filas = $this->total_filas($sqlF); # total de registros encontrados
			$sql = "SELECT * FROM vacuna WHERE nom_vcna LIKE '$this->filtro%' ORDER BY nom_vcna ASC LIMIT $desde,$mostrar";
			$res = $this->query($sql);
			return $this->envia_array($res);
		}

		function envia_array($res){ # recibe la consulta cargada en memoria y la transforma en un arreglo
			$lista = array(); $cont = 0;
			while( $fila = $this->f_array($res) ){
				$lista[$cont]['cod'] = $fila['cod_vcna']; 
				$lista[$cont]['nom'] = $fila['nom_vcna'];
				$cont++;
			}
			return $lista;
		}
	}
?>