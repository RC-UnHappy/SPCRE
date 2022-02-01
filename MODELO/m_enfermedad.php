<?php 
	include_once('MyDB.php');

	class cls_enfermedad extends MyDB{
		private $codigo, $nombre, $filtro; # codigo auto-increment
		public $filas;

		function __construct(){
			parent::__construct();
			$this->codigo = null;
			$this->nombre = null;
			$this->filtro = null;
			$this->filas = null;
		}

		function set_datos($cod, $nom){ # pasa valor a las propiedades de la clase
			$this->codigo = $cod;
			$this->nombre = ucfirst($this->limpiarCadena(htmlentities($nom)));
		}

		function set_filtro($filtro){ # pasa valor a la propiedad filtro
			$this->filtro = $this->limpiarCadena($filtro);
		}

		function agregar(){ # registra una enfermedad
			$sql = "INSERT INTO enfermedad (nom_enf) VALUES ('$this->nombre')";
			$this->query($sql);
			if( $this->f_affectrows() ){
				return true;	
			}
		}

		function modificar(){ # modifica una enfermedad
			$sql = "UPDATE enfermedad SET nom_enf = '$this->nombre' WHERE cod_enf = '$this->codigo'";
			if( $rs = $this->consultar() ){ # existe el nombre
				if( $rs['cod_enf'] == $this->codigo ){ # es el mismo
					$this->query($sql);
					return true;
				}
			} 
			else{
				$this->query($sql);
				return true;
			}
		}
		# Elimina una enfermedad
		function eliminar(){
			$sql = "DELETE FROM enfermedad WHERE cod_enf = '$this->codigo'";
			$this->query($sql); 
			if( $this->f_affectrows() ){
				return true;
			}
		}	

		function consultar(){ # consulta una tupla
			$sql = "SELECT * FROM enfermedad WHERE nom_enf = '$this->nombre'";
			return $this->f_array($this->query($sql));
		}

		function listar(){ // listar todas las enfermedades sin limite
			$sql = "SELECT * FROM enfermedad ORDER BY nom_enf ASC";
			$res = $this->query($sql);
			return $this->envia_array($res);
		}

		function listar_limit($desde=0,$mostrar=15){ # lista todas las enfermedades, con limite
			$sql = "SELECT * FROM enfermedad ORDER BY nom_enf ASC LIMIT $desde,$mostrar";
			$sqlF = "SELECT count(cod_enf) FROM enfermedad";
			$this->filas = $this->total_filas($sqlF); # total de registros encontrados
			$res = $this->query($sql);
			return $this->envia_array($res);
		}

		function filtrar($desde=0,$mostrar=15){ # lista las enfermedades por filtro 
			$sqlF = "SELECT count(cod_enf) FROM enfermedad WHERE nom_enf LIKE '$this->filtro%'";
			$this->filas = $this->total_filas($sqlF); # total de registros encontrados
			$sql = "SELECT * FROM enfermedad WHERE nom_enf LIKE '$this->filtro%' ORDER BY nom_enf ASC LIMIT $desde,$mostrar";
			$res = $this->query($sql);
			return $this->envia_array($res);
		}

		function envia_array($res){ # recibe la consulta cargada en memoria y la transforma en un arreglo
			$lista = array(); $cont = 0;
			while( $fila = $this->f_array($res) ){
				$lista[$cont]['cod'] = $fila['cod_enf']; 
				$lista[$cont]['nom'] = $fila['nom_enf'];
				$cont++;
			}
			return $lista;
		}

	}
?>