<?php 
	include_once('MyDB.php');

	class cls_aula extends MyDB{
		private $codigo, $nombre, $estatus, $filtro; # codigo: int Auto-increment
		public $filas; 

		function __construct(){
			parent::__construct(); # constructor del padre
			$this->codigo = null;
			$this->nombre = null;
			$this->estatus = null;
			$this->filtro = null;
			$this->filas = null;
		}

		function set_datos($cod, $nom){ # pasa valor a las propiedades de la clase
			$this->codigo = $cod;
			$this->nombre = ucfirst($this->limpiarCadena(htmlentities($nom)));
		}

		function set_estatus( $cod, $sta ){
			$this->codigo = $cod;
			$this->estatus = $sta;
		}

		function set_filtro($filtro){
			$this->filtro = $this->limpiarCadena($filtro);
		}

		function agregar(){ # incluye un nuevo registro
			$sql = "INSERT INTO aula (nom_aula) VALUES ('$this->nombre') ";
			$this->query($sql);
			if( $this->f_affectrows() ){ # registró?
				return true;
			}
		}

		function modificar(){ # modifica un registro
			$sql = "UPDATE aula SET nom_aula = '$this->nombre' WHERE cod_aula = '$this->codigo'";
			if( $rs = $this->consultar() ){ # existe el nombre
				if( $rs['cod_aula'] == $this->codigo ){ # es el mismo
					$this->query($sql);
					return true;
				}
			}
			else{ # no existe el nombre, modifica
				$this->query($sql);
				return true;
			} 
		}

		function eliminar(){ # Elimina las aulas solamente disponibles
			$sql = "DELETE FROM aula WHERE cod_aula = '$this->codigo' AND estatus = 'D'";
			$this->query($sql);
			# ocurrirán cambios solo si hay una relación con la tabla sección
			if( $this->f_affectrows() ){
				return true;
			}
		}
		
		function modificar_estatus(){ # modifica el estatus de un aula
			$sql = "UPDATE aula SET estatus = '$this->estatus' WHERE cod_aula = '$this->codigo'";
			$this->query($sql);
		}

		function consultar(){ # busca nombre de el aula
			$sql = "SELECT * FROM aula WHERE nom_aula = '$this->nombre'";
			if( $rs = $this->query($sql) ){
				return $this->f_array($rs);
			} 
		}

		function listar(){ # recolecta todos los registos de labla
			$sql = "SELECT * FROM aula ORDER BY nom_aula ASC";
			$res = $this->query($sql);
			return $this->envia_array($res);
		}

		function listar_disponibles(){ # consulta alulas disponibles
			$sql = "SELECT * FROM aula WHERE estatus = 'D'";
			$res = $this->query($sql);
			return $this->envia_array($res);
		}

		function listar_limit($desde=0, $limite=15){
			$sqlF = "SELECT count(cod_aula) FROM aula";
			$this->filas = $this->total_filas($sqlF); # total de registros encontrados
			$sql = "SELECT * FROM aula ORDER BY nom_aula ASC LIMIT $desde,$limite";
			$res = $this->query($sql);
			return $this->envia_array($res);
		}

		function filtrar($desde=0, $limite=15){ # consulta los registros por filtro
			$sqlF = "SELECT count(cod_aula) FROM aula WHERE nom_aula LIKE '$this->filtro%'";
			$this->filas = $this->total_filas($sqlF); # total de registros encontrados
			$sql = "SELECT * FROM aula WHERE nom_aula LIKE '$this->filtro%' ORDER BY nom_aula ASC LIMIT $desde,$limite";
			$res = $this->query($sql);
			return $this->envia_array($res);
		}

		function envia_array($res){ # recibe la consulta cargada en memoria y la transforma en un arreglo
			$lista = array(); $cont = 0;
			while( $fila = $this->f_array($res) ){
				$lista[$cont]['cod'] = $fila['cod_aula']; 
				$lista[$cont]['nom'] = $fila['nom_aula'];
				$lista[$cont]['sta'] = $fila['estatus'];
				$cont++;
			}
			return $lista;
		}
	}
?>