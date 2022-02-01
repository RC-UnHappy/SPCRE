<?php 
	require_once('m_persona.php');
	
	class cls_representante extends cls_persona{
		private $grdIns, $ocup, $obs;

		# constructor
		function __construct(){
			parent::__construct(); # constructor del padre (cls_persona)
			$this->grdIns = null;
			$this->ocup = null;
			$this->obs = null;
		}

		# pasa valor a la propiedad codi
		function setCodigo($a){ # kesesto?
			$this->codigo = $a;
		}

		# pasa valor a las propiedades
		function set_datosRep($tipo_doc,$ced,$nom,$ape,$sex,$fnac,$nac,$ema,$grdo,$ocup,$obs){
			$this->tipo_documento = $tipo_doc;
			$this->cedula = trim($ced);
			$this->nom1 = mb_strtoupper($this->limpiarCadena($nom)); # limpia los espacios en blanco sobrantes y todo mayuscula
			$this->ape1 = mb_strtoupper($this->limpiarCadena($ape));
			$this->sexo = $sex;
			$this->fnac = $fnac;
			$this->nac = $nac;
			$this->email = strtolower(trim($ema));
			$this->grdIns = $grdo;
			$this->ocup = $ocup;
			$this->obs = trim($this->limpiarCadena($obs));
		}

		# incluye en la tabla representante
		function registrar_representante(){ # registra nueva persona y representante
			$this->registrar_persona(); # registra en la tabla persona
			$codPer = $this->ultimo_id(); 
			$sql = "INSERT INTO representante (cod_per,grado_instr,ocupacion,observacion) VALUES ('$codPer','$this->grdIns','$this->ocup','$this->obs')";
			$this->query($sql);
			$this->set_codigoPersona($codPer); # pasa el codigo a la clase persona
			$this->subir_foto();
		}

		# registra solo representante si ya existe la persona y modifica en la tabla persona
		function registrar_modificar(){ 
			$sql = "INSERT INTO representante (cod_per,grado_instr,ocupacion,observacion) 
			VALUES ('$this->codPer','$this->grdIns','$this->ocup','$this->obs')";
			$this->query($sql);
			$this->modificar_persona();
			#echo $sql;
		}

		# modifica una tupla
		function modificar_representante(){
			$this->modificar_persona(); # modifica en la tabla persona
			$sql = "UPDATE representante SET grado_instr='$this->grdIns',ocupacion='$this->ocup',observacion='$this->obs'
			WHERE cod_per='$this->codPer'";
			$this->query($sql);
		}	

		# consulta los datos del representante
		function consultar_representante(){
			$sql = "SELECT * FROM representante 
			INNER JOIN persona ON representante.cod_per = persona.cod_per
			INNER JOIN grado_instruccion ON representante.grado_instr = grado_instruccion.cod_ginst
			INNER JOIN ocupacion ON representante.ocupacion = ocupacion.cod_ocup
			WHERE tipo_documento='$this->tipo_documento' AND cedula = '$this->cedula'";
			
			if( $res = $this->query($sql) ){
				return $this->f_array($res);
			}
		}

		# una consulta mas pequeña
		function consultar_representante2(){
			$sql = "SELECT representante.cod_per AS cod_rep, tipo_documento, cedula, CONCAT(nom1,' ',ape1) AS nombre FROM representante INNER JOIN persona ON representante.cod_per = persona.cod_per 
			WHERE tipo_documento = '$this->tipo_documento' AND cedula = '$this->cedula' OR representante.cod_per = '$this->codPer' AND representante.cod_per != '2' ";
			$rs = $this->query($sql);
			#echo $sql;
			if($rs){
				return $this->f_array($rs);
			}
		}

		# devuelve el codigo de la persona
		function get_codigoRep(){
			$sql = "SELECT representante.cod_per FROM representante 
			INNER JOIN persona ON representante.cod_per = persona.cod_per
			WHERE tipo_documento='$this->tipo_documento' AND cedula = '$this->cedula'";	
			$rs = $this->f_array($this->query($sql));
			return $rs['cod_per'];
		}

		# consulta datos de los estudiantes a los que representa
		function representados($codPer,$codPeriodo){
			$lista = array(); $cont = 0;
			$sql="SELECT grado, letra, CONCAT(tipo_documento,'-',cedula) AS cedula, ced_esc, CONCAT(nom1,' ',nom2,' ',ape1,' ',ape2) AS nom_ape, estudiante.estatus FROM inscripcion 
			INNER JOIN estudiante ON estudiante.cod_per=inscripcion.cod_est
			INNER JOIN persona ON estudiante.cod_per=persona.cod_per
			INNER JOIN seccion ON inscripcion.seccion = seccion.cod_seccion
			INNER JOIN periodo_escolar ON seccion.periodo_esc = periodo_escolar.cod_periodo
			WHERE inscripcion.cod_rep='$codPer' AND cod_periodo = '$codPeriodo'";
			
			$rs = $this->query($sql);
			while($fila = $this->f_array($rs) ){
				if( strlen($fila['cedula'])>2 ){
					$lista[$cont]['ced'] = $fila['cedula'];
				}
				else{
					$lista[$cont]['ced'] = $fila['ced_esc'];
				}
				$lista[$cont]['nom_ape'] = $fila['nom_ape'];
				$lista[$cont]['grado_sec'] = $fila['grado'].'°  "'.$fila['letra'].'"';
				$lista[$cont]['estatus'] = $fila['estatus'];
				$cont++;
			}
			return $lista;
		}

		# Para reporte de nomina
		function nomina_representantes($aesc){
			$sql = "SELECT DISTINCT representante.cod_per,CONCAT(tipo_documento,'-',cedula) AS cedula, CONCAT(nom1,' ',ape1) AS nombre,
			sexo, email, nom_ginst, nom_ocup,

			(SELECT CONCAT(sector,' ',calle,' ',nro) FROM direccion
				INNER JOIN direccion_persona ON direccion_persona.cod_dir = direccion.cod_dir
				INNER JOIN persona ON direccion_persona.cod_per = persona.cod_per
				WHERE persona.cod_per = representante.cod_per AND direccion_persona.tipo_dir='D'
			) AS direccion,

			( SELECT telefono.numero FROM telefono 
				INNER JOIN telefono_persona ON telefono_persona.cod_tlf = telefono.cod_tlf
				INNER JOIN persona ON telefono_persona.cod_per = persona.cod_per
				WHERE telefono_persona.cod_per = representante.cod_per AND tipo = 'M'
			) AS telefono

			FROM representante
			INNER JOIN persona ON representante.cod_per = persona.cod_per
			INNER JOIN inscripcion ON inscripcion.cod_rep = representante.cod_per 
			INNER JOIN seccion ON inscripcion.seccion = seccion.cod_seccion
			INNER JOIN periodo_escolar ON seccion.periodo_esc = periodo_escolar.cod_periodo
			INNER JOIN grado_instruccion ON representante.grado_instr = grado_instruccion.cod_ginst
			INNER JOIN ocupacion ON representante.ocupacion = ocupacion.cod_ocup
			WHERE cod_periodo = '$aesc' AND inscripcion.estatus = 'A' ORDER BY ape1 ASC, nom1 ASC"; 
			
			$rs = $this->query($sql);
			$lista = array(); $cont=0;
			while( $fila = $this->f_array($rs) ){
				$lista[$cont]['cod_per'] = $fila['cod_per'];
				$lista[$cont]['cedula'] = $fila['cedula'];
				$lista[$cont]['nombre'] = $fila['nombre'];
				$lista[$cont]['sexo'] = $fila['sexo'];
				$lista[$cont]['email'] = $fila['email'];
				$lista[$cont]['direccion'] = $fila['direccion'];
				$lista[$cont]['telefono'] = $fila['telefono'];
				$lista[$cont]['email'] = $fila['email'];
				$lista[$cont]['ocup'] = $fila['nom_ocup'];
				$cont++;
			}
			return $lista;
		}
	}
?>