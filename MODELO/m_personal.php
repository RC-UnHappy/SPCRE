<?php 
	include_once('m_persona.php'); # modelo de la clase madre(persona)

	class cls_personal extends cls_persona{ 
		#codPer -> codigo clase persona
		private $cargo, $funcion, $nivel, $estatus;
		public $filas, $filtro;

		function __construct(){
			parent::__construct(); 
		}

		function set_datosPersonal($tdoc,$ced,$nom,$nom2,$ape,$ape2,$sexo,$fnac,$cargo,$funcion,$nivel,$email,$tlfm,$sta){ 
			// clase madre (cls_persona)
			$this->tipo_documento = $tdoc;
			$this->cedula = trim($ced);
			$this->nom1 = mb_strtoupper($this->limpiarCadena($nom)); # limpia los espacios en blanco sobrantes y todo mayuscula
			$this->nom2 = mb_strtoupper($this->limpiarCadena($nom2));
			$this->ape1 = mb_strtoupper($this->limpiarCadena($ape));
			$this->ape2 = mb_strtoupper($this->limpiarCadena($ape2));	
			$this->sexo = $sexo;
			$this->fnac = $fnac;
			$this->email = strtolower(trim($email));	
			// clase cls_personal
			$this->cargo = $cargo;
			$this->funcion = $funcion;
			$this->nivel = $nivel;	
			$this->tlfm = trim($tlfm);
			$this->estatus = $sta;
			# importante para no incluir nombres o apellidos con comillas simples
			$this->nom1 = str_replace("'", '%', $this->nom1);
			$this->nom2 = str_replace("'", '%', $this->nom2);
			$this->ape1 = str_replace("'", '%', $this->ape1);
			$this->ape2 = str_replace("'", '%', $this->ape2);
		}

		function set_filtro($filtro){
			$this->filtro = $this->limpiarCadena($filtro); 
		}

		# Registra en la tabla persona y personal
		function registrar_personal(){

			# registra en la tabla persona
			$this->registrar_persona();
			$codPer = $this->ultimo_id(); 
			$this->desconectar(); # desconecta de la tabla persona

			$this->set_codigoPersona($codPer); # pasa codigo a la clase persona
			$this->agregar_telefono($this->tlfm,'M');
				
			# registra en la tabla personal
			$sql = "INSERT INTO personal (cod_per,cod_cargo,funcion,nivel,estatus) VALUES ('$codPer','$this->cargo','$this->funcion','$this->nivel','$this->estatus')";
			$this->query($sql);

			if( $this->f_affectrows() ){
				$this->desconectar(); # desconecta de la tabla personal
				return true;
			}

			// if( $this->f_affectrows() ){
			
			// 	$usu = $this->tipo_documento.$this->cedula;
			// 	$pass = password_hash($this->cedula, PASSWORD_BCRYPT); # encripta la clave, cuando es primera vez su clave es su misma cedula
			// 	$sql = "INSERT INTO usuario (cod_per,cod_usu,clave,cod_nivel,estatus) 
			// 	VALUES ('$this->codPer','$usu','$pass','$this->nivel','$this->estatus_u')";
			// 	$this->query($sql);
			// 	#echo $sql;
				
			// 	if( $this->f_affectrows() ){
			// 		$this->desconectar();
			// 		return true;
			// 	}
			// }	
		}
 
		function modificar_personal(){ # modifica el personal, tambien cambia el nivel
			$sqlPersona = "UPDATE persona SET 
			tipo_documento='$this->tipo_documento', 
			cedula='$this->cedula',nom1='$this->nom1',nom2='$this->nom2',
			ape1='$this->ape1',ape2='$this->ape2',
			sexo='$this->sexo',
			fecha_nac='$this->fnac',
			email='$this->email' 
			WHERE cod_per = '$this->codPer'";
			#echo $sqlPersona;

			$sqlPersonal = "UPDATE personal SET cod_cargo = '$this->cargo', funcion='$this->funcion',nivel='$this->nivel', estatus='$this->estatus' WHERE cod_per = '$this->codPer'";

			if( $rs = $this->consultar_persona() ){ # busca si la cedula ya existe
				if( $this->codPer == $rs['cod_per'] ){ # es el mismo puede modificar
					$this->query($sqlPersona); # datos de persona
					$this->desconectar();

					$this->query($sqlPersonal); # datos de personal
					$this->desconectar();
					#$this->subir_foto();
					return true;
				}
			}
			else{
				$this->query($sqlPersona);
				$this->desconectar();

				$this->query($sqlPersonal); # modifica
				$this->desconectar();
				#$this->subir_foto();
				return true;
			}
		}

		// function modificar_miPerfil(){ # EL usuario (Personal) modifica su propio perfil, no cambia el nivel
		// 	$usuario = $this->tipo_documento.'-'.$this->cedula; 
		// 	$sql = "UPDATE personal SET usuario='$usuario', WHERE cod_per = '$this->codPer'";
		// 	$sqlPersona = "UPDATE persona SET nom1='$this->nom1',ape1='$this->ape1',email='$this->email' 
		// 	WHERE cod_per = '$this->codPer'";
		// 	$this->query($sqlPersona);
		// 	$this->query($sql); # modifica
		// 	$this->subir_foto();
		// 	return true;
		// }

		// function modificar_preguntasSeg(){ # modifica las preguntas de seguridad del usuario
		// 	$sql = "UPDATE personal SET preg1='$this->preg1',resp1='$this->resp1',preg2='$this->preg2',resp2='$this->resp2'
		// 	WHERE cod_per = '$this->codPer'";
		// 	$this->query($sql);
		// }


		function consultar_personal2(){ # consulta al personal por tipo de documento y cedula
			$sql = "SELECT * FROM personal INNER JOIN persona ON personal.cod_per = persona.cod_per
			WHERE tipo_documento = '$this->tipo_documento' AND cedula = '$this->cedula'";
			if( $this->f_numrows($this->query($sql) ) ){
				$this->desconectar();
				return true;
			}	
		}

		# Dejar solo esta
		function consultarPersonal(){ # consulta al personal por tipo de documento y cedula
			$sql = "SELECT * FROM personal INNER JOIN persona ON personal.cod_per = persona.cod_per
			INNER JOIN cargo ON personal.cod_cargo = cargo.cod_cargo
			INNER JOIN funcion ON personal.funcion = funcion.cod_funcion
			WHERE tipo_documento = '$this->tipo_documento' AND cedula = '$this->cedula'";
			if( $rs = $this->query($sql) ){
				$this->desconectar();
				return $this->array_assoc($rs);
			}
		}

		// function buscar_docente(){ # consulta solo personal docente
		// 	$sql = "SELECT * FROM personal WHERE usuario = '$this->usuario' AND nivel = 1";
		// 	if( $rs = $this->query($sql) ){
		// 		return $this->f_array($rs);
		// 	} 
		// }
		
		
		
		# selecciona todos los registros de la tabla
		function listar_personal($desde=0,$limite=15,$cargo='All',$estatus='A'){ 
			if( $cargo == 'All' ){
				$condicion = "estatus='$estatus'";
			}
			else{
				$condicion = "personal.cod_cargo='$cargo' AND estatus='$estatus'";
			}

			$sqlF = "SELECT count(cod_per) FROM personal WHERE $condicion";
			$this->filas = $this->total_filas($sqlF); # total de registros encontrados

			$sql = "SELECT tipo_documento,cedula,nom1,ape1,nivel,nom_cargo,nom_funcion,estatus FROM personal 
			INNER JOIN persona ON personal.cod_per = persona.cod_per
			INNER JOIN cargo ON personal.cod_cargo = cargo.cod_cargo
			INNER JOIN funcion ON personal.funcion = funcion.cod_funcion
			WHERE $condicion LIMIT $desde,$limite";

			#echo $sql;

			$rs = $this->query($sql);
			$this->desconectar();
			return $this->envia_array($rs);	
		}

		# selecciona los personales por filtro
		function filtrar($desde=0,$limite=15,$cargo='All',$estatus='A'){ 
			if( $cargo == 'All' ){
				$condicion = "cedula LIKE '$this->filtro%' AND estatus='$estatus'
				OR nom1 LIKE '$this->filtro%' AND estatus='$estatus' 
				OR ape1 LIKE '$this->filtro%' AND estatus='$estatus'
				OR CONCAT(nom1,' ',ape1) LIKE '$this->filtro%' AND estatus='$estatus' 
				OR CONCAT(ape1,' ',nom1) LIKE '$this->filtro%' AND estatus='$estatus'";
			}
			else{
				$condicion = "cedula LIKE '$this->filtro%' AND estatus='$estatus' AND personal.cod_cargo='$cargo'
				OR nom1 LIKE '$this->filtro%' AND estatus='$estatus' AND personal.cod_cargo='$cargo'
				OR ape1 LIKE '$this->filtro%' AND estatus='$estatus' AND personal.cod_cargo='$cargo'
				OR CONCAT(nom1,' ',ape1) LIKE '$this->filtro%' AND estatus='$estatus' AND personal.cod_cargo='$cargo' 
				OR CONCAT(ape1,' ',nom1) LIKE '$this->filtro%' AND estatus='$estatus' AND personal.cod_cargo='$cargo'";
			}

			$sqlF = "SELECT count(personal.cod_per) FROM personal 
			INNER JOIN persona ON personal.cod_per = persona.cod_per
			WHERE $condicion";
			$this->filas = $this->total_filas($sqlF); # total de registros encontrados

			$sql = "SELECT tipo_documento,cedula,nom1,ape1,nivel,nom_cargo,nom_funcion,estatus FROM personal 
			INNER JOIN persona ON personal.cod_per = persona.cod_per
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
				$lista[$cont]['ced'] = $fila['tipo_documento'].'-'.$fila['cedula'];
				$lista[$cont]['nom_ape'] = $fila['nom1'].' '.$fila['ape1'];
				$lista[$cont]['nvl'] = $fila['nivel'];
				$lista[$cont]['car'] = $fila['nom_cargo'];
				$lista[$cont]['fun'] = $fila['nom_funcion'];
				$lista[$cont]['sta'] = $fila['estatus'];
				$cont++;
			}
			return $lista; # devuelve el arreglo
		}

		# consulta los datos personales y telefono
		function consultar_datosPersonales(){ 
			$sql = "SELECT tipo_documento,cedula,persona.cod_per AS codPer,nom1,nom2,ape1,ape2,sexo,fecha_nac,email,nivel,personal.cod_cargo AS cargo,funcion,estatus,CONCAT(tipo,numero) AS tlfns 
			FROM personal INNER JOIN persona ON personal.cod_per = persona.cod_per 
			INNER JOIN cargo ON personal.cod_cargo = cargo.cod_cargo
			INNER JOIN funcion ON personal.funcion = funcion.cod_funcion
			INNER JOIN telefono_persona ON telefono_persona.cod_per = persona.cod_per
			INNER JOIN telefono ON telefono_persona.cod_tlf = telefono.cod_tlf
			WHERE tipo_documento='$this->tipo_documento' AND cedula = '$this->cedula'";
			$rs = $this->query($sql);
			#echo $sql;

			# VER AQUI POR QUE ENVIA NULL E.E
			if( $this->f_numrows($rs) ){ # existen datos
				$this->desconectar();
				return $this->f_array($rs); # devuelve el arreglo
			}
		}

		function cantidad_docentes(){ # consulta la cantidad de personal docentes activos
			$sql = "SELECT count(personal.cod_per) FROM personal
			INNER JOIN persona ON personal.cod_per = persona.cod_per
			INNER JOIN usuario ON usuario.cod_per = persona.cod_per
			WHERE cod_cargo = 5 AND usuario.estatus != 'I'";
			$arr = $this->f_array($this->query($sql));
			$this->desconectar();
			#echo $sql;
			return $arr[0];
		}

		# Selecciona los personales de cargo
		function listar_personalCargo($cod_cargo){
			$sql = "SELECT persona.cod_per AS cod_doc, CONCAT(tipo_documento,'-',cedula,' - ',nom1,' ',ape1) AS docente FROM personal 
			INNER JOIN persona ON personal.cod_per = persona.cod_per
			INNER JOIN cargo ON personal.cod_cargo = cargo.cod_cargo
			WHERE personal.cod_cargo = '$cod_cargo'";
			#echo $sql;
			$rs = $this->query($sql);
			$lista = array(); $cont=0;
			while( $fila = $this->f_array($rs) ){
				$lista[$cont]['cod'] = $fila['cod_doc'];
				$lista[$cont]['director'] = $fila['docente'];
				$cont++;
			}
			$this->desconectar();
			return $lista;
		}

		# Selecciona los personales con cargo -> Docente
		function listar_docentes(){	
			$sql = "SELECT persona.cod_per AS cod_doc, CONCAT(tipo_documento,cedula,' - ',nom1,' ',ape1) AS docente FROM personal 
			INNER JOIN persona ON personal.cod_per = persona.cod_per
			INNER JOIN cargo ON personal.cod_cargo = cargo.cod_cargo
			INNER JOIN usuario ON usuario.cod_per = persona.cod_per
			WHERE nom_cargo = 'Docente' AND usuario.estatus != 'I'";
			$rs = $this->query($sql);
			$lista = array(); $cont=0;
			while( $fila = $this->f_array($rs) ){
				$lista[$cont]['cod'] = $fila['cod_doc'];
				$lista[$cont]['docente'] = $fila['docente'];
				$cont++;
			}
			$this->desconectar();
			return $lista;
		}

		function docente_ocupado($codDoc,$periodo){
			$sql = "SELECT * FROM seccion WHERE docente = '$codDoc' AND periodo_esc = '$periodo'";
			$rs = $this->query($sql);
			if( $this->f_numrows($rs) ){
				$this->desconectar();
				return true; # ya esta ocupado el docente
			}
		}
	}
# fin
?>