<?php 
	include_once('m_persona.php'); # modelo de la clase madre(persona)

	class cls_usuario extends MyDB{ 
		# codPer -> codigo clase persona
		# propiedades de la clase
		private $codPer, $usuario, $clave, $nivel, $p1, $r1, $p2, $r2, $estatus;
		public  $filtro, $filas;

		function __construct(){
			parent::__construct();
			$this->filtro = null;
			$this->filas = null;
		}

		# FUNCIONES SET
		function set_filtro($filtro){ # pasa valor a la propiedad filtro
			$this->filtro = str_replace("'", "%", $this->limpiarCadena($filtro));
		}

		function set_usuario($usu){
			$this->usuario = trim($usu);
		}

		function set_datos_usuario($codper,$usu,$nvl,$sta){
			$this->codPer = $codper;
			$this->usuario = $usu; #ej: V00000000
			$this->nivel = $nvl;
			$this->estatus = $sta;
		}

		function set_Pass($cod,$clv){ # codigo de la persona / nueva clave
			$this->codPer = $cod;
			$this->clave = trim($clv);
		}

		function set_pregSeg($p1,$r1,$p2,$r2){ # preguntas de seguridad
			$this->preg1 = ucfirst($this->limpiarCadena($p1));
			$this->resp1 = $this->limpiarCadena($r1);
			$this->preg2 = ucfirst($this->limpiarCadena($p2));
			$this->resp2 = $this->limpiarCadena($r2);
		}

		function registrar_usuario(){
			$pass_str = substr($this->usuario, 1);
			$pass = password_hash($pass_str, PASSWORD_BCRYPT); # encripta la clave, cuando es primera vez su clave es su misma cedula
			$sql = "INSERT INTO usuario (cod_per,cod_usu,clave,cod_nivel,estatus) 
			VALUES ('$this->codPer','$this->usuario','$pass','$this->nivel','$this->estatus')";

			$this->query($sql);
			if( $this->f_affectrows() ){
				$this->desconectar();
				return true;
			}
		}

		# CONSULTAS
		function consultar_usuario(){ 
			$sql = "SELECT * FROM usuario INNER JOIN persona ON persona.cod_per = usuario.cod_per WHERE cod_usu = '$this->usuario'";
			if( $rs = $this->query($sql) ){
				$this->desconectar();
				return $this->f_array($rs);
			}
		}

		# MODIFICACIONES
		function ult_conexion(){ # modifica la ultima conexión de un usuario
			$ult_conex = date('Y-m-d H:i:s');
			$sql = "UPDATE usuario SET ult_conex = '$ult_conex' WHERE cod_usu = '$this->usuario'";
			$this->query($sql);	
			$this->desconectar();	
		}

		function cambiar_clave(){ # permite modificar la clave del usuario/personal
			$pass = password_hash($this->clave, PASSWORD_BCRYPT);
			$sql = "UPDATE usuario SET clave = '$pass' WHERE cod_per = '$this->codPer'";
			$this->query($sql);
			$this->desconectar();	
			#echo $sql;
		}

		function modificar_preguntasSeg(){ # modifica las preguntas de seguridad del usuario
			$sql = "UPDATE usuario SET preg1='$this->preg1',resp1='$this->resp1',preg2='$this->preg2',resp2='$this->resp2'
			WHERE cod_per = '$this->codPer'";
			$this->query($sql);
			$this->desconectar();	
		}

		# intentos de contraseña
		function inscluir_intento_clv(){
			$fecha = date('Y-m-d H:i:s');
			$sql = "INSERT INTO intentos_clv (cod_usu,fecha_int) VALUES ('$this->usuario','$fecha')";
			$this->query($sql);
			$this->desconectar();	
		}
		function consultar_intentos_clv(){
			$actual = date('Y-m-d H:i:s');
			$restar = strtotime('-1 hour', strtotime($actual) );
			$diferencia = date('Y-m-d H:i:s', $restar); # diferencia de 1 hora hacia atrás

			$sql = "SELECT * FROM intentos_clv WHERE cod_usu = '$this->usuario' AND fecha_int BETWEEN '$diferencia' AND '$actual'";
			$rs = $this->query($sql);
			$this->desconectar();	
			return mysqli_num_rows($rs);
		}
		function eliminar_intentos(){
			$sql = "DELETE FROM intentos_clv WHERE cod_usu = '$this->usuario'";
			$this->query($sql);
			$this->desconectar();	
		}
		function bloquear(){
			$sql = "UPDATE usuario SET estatus = 'B' WHERE cod_usu = '$this->usuario'";
			$this->query($sql);
			$this->desconectar();	
		}

		# selecciona todos los registros de la tabla
		function listar($desde=0,$limite=15,$nivel='All',$estatus='A'){ 
			if( $nivel == 'All' ){
				$condicion = "usuario.estatus='$estatus'";
			}
			else{
				$condicion = "usuario.cod_nivel='$nivel' AND usuario.estatus='$estatus'";
			}

			$sqlF = "SELECT count(cod_per) FROM usuario WHERE $condicion";
			$this->filas = $this->total_filas($sqlF); # total de registros encontrados

			$sql = "SELECT persona.cod_per,tipo_documento,cedula,nom1,ape1,usuario.cod_nivel,nom_nivel,nom_cargo,nom_funcion,usuario.estatus FROM usuario 
			INNER JOIN persona ON usuario.cod_per = persona.cod_per
			INNER JOIN nivel ON usuario.cod_nivel = nivel.cod_nivel
			INNER JOIN personal ON personal.cod_per = persona.cod_per
			INNER JOIN cargo ON personal.cod_cargo = cargo.cod_cargo
			INNER JOIN funcion ON personal.funcion = funcion.cod_funcion
			WHERE $condicion LIMIT $desde,$limite";

			#echo $sql;
			$rs = $this->query($sql);
			$this->desconectar();
			return $this->envia_array($rs);	
		}

		# selecciona los personales por filtro
		function filtrar($desde=0,$limite=15,$nivel='All',$estatus='A'){ 
			if( $nivel == 'All' ){
				$condicion = "cedula LIKE '$this->filtro%' AND usuario.estatus='$estatus'
				OR nom1 LIKE '$this->filtro%' AND usuario.estatus='$estatus' 
				OR ape1 LIKE '$this->filtro%' AND usuario.estatus='$estatus'
				OR CONCAT(nom1,' ',ape1) LIKE '$this->filtro%' AND usuario.estatus='$estatus' 
				OR CONCAT(ape1,' ',nom1) LIKE '$this->filtro%' AND usuario.estatus='$estatus'";
			}
			else{
				$condicion = "cedula LIKE '$this->filtro%' AND usuario.estatus='$estatus' AND usuario.cod_nivel='$nivel'
				OR nom1 LIKE '$this->filtro%' AND usuario.estatus='$estatus' AND usuario.cod_nivel='$nivel'
				OR ape1 LIKE '$this->filtro%' AND usuario.estatus='$estatus' AND usuario.cod_nivel='$nivel'
				OR CONCAT(nom1,' ',ape1) LIKE '$this->filtro%' AND usuario.estatus='$estatus' AND usuario.cod_nivel='$nivel' 
				OR CONCAT(ape1,' ',nom1) LIKE '$this->filtro%' AND usuario.estatus='$estatus' AND usuario.cod_nivel='$nivel'";
			}

			$sqlF = "SELECT count(personal.cod_per) FROM personal 
			INNER JOIN persona ON personal.cod_per = persona.cod_per
			WHERE $condicion";
			$this->filas = $this->total_filas($sqlF); # total de registros encontrados

			$sql = "SELECT persona.cod_per,tipo_documento,cedula,nom1,ape1,nom_nivel,nom_cargo,nom_funcion,usuario.estatus FROM usuario 
			INNER JOIN persona ON usuario.cod_per = persona.cod_per
			INNER JOIN nivel ON usuario.cod_nivel = nivel.cod_nivel
			INNER JOIN personal ON personal.cod_per = persona.cod_per
			INNER JOIN cargo ON personal.cod_cargo = cargo.cod_cargo
			INNER JOIN funcion ON personal.funcion = funcion.cod_funcion
			WHERE $condicion LIMIT $desde,$limite";

			#echo $sql;

			$rs = $this->query($sql);
			$this->desconectar();
			return $this->envia_array($rs);
		}

		function envia_array($rs){
			$lista = array(); $cont = 0; # Array en donde se almacenará cada registro
			while ( $fila = $this->f_array($rs) ) { 
				$lista[$cont]['codper'] = $fila['cod_per'];
				$lista[$cont]['ced'] = $fila['tipo_documento'].'-'.$fila['cedula'];
				$lista[$cont]['nom_ape'] = $fila['nom1'].' '.$fila['ape1'];
				$lista[$cont]['car'] = $fila['nom_cargo'];
				$lista[$cont]['fun'] = $fila['nom_funcion'];
				$lista[$cont]['cod_nvl'] = $fila['cod_nivel'];
				$lista[$cont]['nvl'] = $fila['nom_nivel'];
				$lista[$cont]['sta'] = $fila['estatus'];
				$cont++;
			}
			return $lista; # devuelve el arreglo
		}
	}
?>