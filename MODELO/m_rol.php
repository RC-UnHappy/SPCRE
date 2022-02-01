<?php 
	include_once('MyDB.php');
	# clase rol (nivel de usuarios)
	class cls_rol extends MyDB{
		private $codigo,$nombre,$descripcion;

		function __construct(){
			parent::__construct(); # clase madre
			$this->codigo = null;
			$this->nombre = null;
			$this->descripcion = null;
		}

		function set($cod,$nom,$desc){
			$this->codigo = $cod;
			$this->nombre = $this->limpiarCadena(ucwords($nom));
			$this->descripcion = $this->limpiarCadena($desc);
		}

		function incluir(){
			$sql = "INSERT INTO nivel (nom_nivel,descripcion) VALUES ('$this->nombre','$this->descripcion')";
			$this->query($sql);
			if( $this->f_affectrows() ){
				$this->desconectar();
				return true;
			}
		}

		function modificar(){
			$sql = "UPDATE nivel SET nom_nivel='$this->nombre',descripcion='$this->descripcion' WHERE cod_nivel = '$this->codigo'";
			
			if( $rs = $this->consultar() ){ # consulta si existe ya el nombre
				if($rs['cod_nivel'] == $this->codigo){ # es el mismo
					$this->query($sql);
					$this->desconectar();
					return true;
				}
				else{
					return false;
				}
			}
			else{
				$this->query($sql);
				$this->desconectar();
				return true;	
			}
		}

		function eliminar(){
			$sql = "DELETE FROM nivel WHERE cod_nivel = '$this->codigo'";
			$this->query($sql);
			if( $this->f_affectrows() ){
				$this->desconectar();
				return true;
			}
		}

		function consultar(){ # consulta por nombre
			$sql = "SELECT * FROM nivel WHERE nom_nivel = '$this->nombre' AND nom_nivel != 'ADMINISTRADOR CENTRAL'";
			$rs = $this->query($sql);
			$this->desconectar();
			if( $this->f_numrows($rs) ){
				return $this->f_array($rs);
			}
		}

		function listar(){
			$sql = "SELECT * FROM nivel WHERE nom_nivel != 'ADMINISTRADOR CENTRAL'";
			$res = $this->query($sql);

			$lista = array(); $cont = 0;
			while( $fila = $this->f_array($res) ){
				$lista[$cont]['cod'] = $fila['cod_nivel']; 
				$lista[$cont]['nom'] = $fila['nom_nivel'];
				$lista[$cont]['desc'] = $fila['descripcion'];
				$cont++;
			}

			$this->desconectar();
			return $lista;
		}

		#  IMPRIMIR HTML
		function comboRoles(){
			$rs = $this->listar();
			for ($i=0; $i<count($rs); $i++) { 
				echo '<option value="'.$rs[$i]['cod'].'">'.$rs[$i]['nom'].'</option>';
			}
		}
	}
 ?>