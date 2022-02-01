<?php 
require_once('MyDB.php'); 
require_once('m_aula.php');

class cls_seccion extends MyDB{
	# propiedades de la clase seccion
	private $codigo, $aesc, $grado, $seccion, $codAula, $aula, $docente, $cupos;

	function __construct(){ # constructor de la clase
		parent::__construct(); # constructor del padre
		$this->codigo = null;
		$this->aesc = null; # año escolar
		$this->grado = null;
		$this->seccion = null;
		$this->codAula = null;
		$this->aula = null;
		$this->docente = null; # codigo del docente
		$this->cupos = null;
		$this->objAula = new cls_aula();
	}

	function set_periodo($aesc){
		$this->aesc = $aesc; # codigo del año escolar
	}

	function set_codigo($cod){
		$this->codigo = $cod; # codigo de seccion
	}

	function set_grado($gdo){
		$this->grado = $gdo; # grado
	}		

	function set_docente($docente){
		$this->docente = $docente; 
	}

	function set_datos($cod,$aesc,$gdo,$sec,$codAula,$aula,$doc,$cup){  # Pasa valores a las propiedades de la clase
		$this->codigo = $cod; # codigo de la seccion
		$this->aesc = $aesc; # codigo del año escolar
		$this->grado = $gdo;
		$this->seccion = $sec;
		$this->codAula = $codAula;
		$this->aula = $aula;
		$this->docente = $doc; # codigo del docente (persona)
		$this->cupos = $cup;
	}

	function incluir(){ # Permite incluir una nueva seccion
		# incluye en la tabla sección
		$sql = "INSERT INTO seccion (periodo_esc,grado,letra,cupos,docente,aula) 
		VALUES ('$this->aesc','$this->grado','$this->seccion','36','$this->docente','$this->aula')";
		$this->query($sql);
		if( $this->f_affectrows() ){
			# modifica el estatus del aula
			$this->objAula->set_estatus( $this->aula, 'O' ); # abre el aula anterior
			$this->objAula->modificar_estatus(); # ocupa la nueva aula
			return true; 
		}
	}

	function modificar(){ # modifica una seccion
		$sql = "UPDATE seccion SET grado='$this->grado',letra='$this->seccion',cupos='$this->cupos'
		,docente='$this->docente', aula='$this->aula' WHERE cod_seccion = '$this->codigo'";
		$this->query($sql);

		if( $this->codAula != $this->aula ){ # el aula es diferente a la que tenia anteriormente
			$this->objAula->set_estatus( $this->codAula, 'D' ); # abre el aula anterior
			$this->objAula->modificar_estatus();
			$this->objAula->set_estatus( $this->aula, 'O' ); # ocupa el aula nueva
			$this->objAula->modificar_estatus(); # ocupa la nueva aula
		}
	}

	# comprueba que exista una seccion con este año,letra y grado.
	function buscar_seccion(){ 
		$sql = "SELECT cod_seccion FROM seccion WHERE grado = '$this->grado' 
		AND letra = '$this->seccion' AND periodo_esc = '$this->aesc'";
		if( $this->f_numrows($this->query($sql)) ){
			return true;
		}
	}

	# Consulta datos de la seccion por el docente en el año escolar
	function buscar_docente_seccion(){ 
		$sql = "SELECT docente, cod_seccion, grado, letra FROM seccion WHERE docente = '$this->docente' AND periodo_esc = '$this->aesc'";
		if( $rs = $this->query($sql) ){ # existe docente en una sección del año escolar vigente
			$this->desconectar();
			return $this->f_array($rs); 
		}
	}

	function listar(){ # selecciona todas las secciones de un año escolar
		$sql = "SELECT cod_seccion,periodo_esc,grado,letra,cupos,docente,tipo_documento,cedula,CONCAT(nom1,' ',ape1) AS nomDoc,aula,nom_aula FROM seccion 
		INNER JOIN personal ON seccion.docente = personal.cod_per 
		INNER JOIN persona ON personal.cod_per = persona.cod_per 
		INNER JOIN aula ON seccion.aula = aula.cod_aula 
		WHERE periodo_esc = '$this->aesc'
		ORDER BY grado ASC, letra ASC";
		$res = $this->query($sql);
		return $this->envia_array($res);
	}

	function listar_sm(){ # una consulta mas pequeña que la anterior
		$sql = "SELECT cod_seccion, grado, letra FROM seccion INNER JOIN periodo_escolar ON seccion.periodo_esc = periodo_escolar.cod_periodo WHERE 
		periodo_esc = '$this->aesc' OR periodo_escolar.periodo = '$this->aesc' ORDER BY grado ASC, letra ASC";

		$rs = $this->query($sql);
		$lista = array(); $cont=0;
		while($fila = $this->f_array($rs)){
			$lista[$cont]['cod'] = $fila['cod_seccion'];
			$lista[$cont]['gdo'] = $fila['grado'];
			$lista[$cont]['lta'] = $fila['letra'];
			$cont++;
		}
		return $lista;
	}

	function envia_array($res){ # crea un arreglo y retorna
		$lista = array(); $cont = 0;
		while ($fila = $this->f_array($res) ) {
			$lista[$cont]['cod'] = $fila['cod_seccion'];
			$lista[$cont]['aesc'] = $fila['periodo_esc'];
			$lista[$cont]['gdo'] = $fila['grado'];
			$lista[$cont]['lta'] = $fila['letra'];
			$lista[$cont]['aula'] = $fila['aula'];
			$lista[$cont]['nom_aula'] = $fila['nom_aula'];
			$lista[$cont]['codDoc'] = $fila['docente']; # codigo del docente
			$lista[$cont]['cedDoc'] = $fila['tipo_documento'].'-'.$fila['cedula'];
			$lista[$cont]['cedDoc2'] = $fila['tipo_documento'].$fila['cedula'];
			$lista[$cont]['doc'] = $fila['nomDoc'];
			$lista[$cont]['cup'] = $fila['cupos'];
			$cont++;
		}
		return $lista;
	}

	function cantidad_secciones(){ # consulta la cantidad de secciones en el año escolar
		$sql = "SELECT count(*) FROM seccion WHERE periodo_esc = $this->aesc";
		$rs = $this->f_array($this->query($sql));
		return $rs[0];
	}

	# consulta datos de la seccion, docente, y periodo escolar
	function consultar(){
		$sql = "SELECT cod_seccion, cod_periodo, periodo, grado, letra, CONCAT(tipo_documento,'-',cedula) AS ced_docente, CONCAT(nom1,' ',ape1) AS nom_docente, cupos FROM seccion
		INNER JOIN personal ON seccion.docente = personal.cod_per INNER JOIN persona ON personal.cod_per = persona.cod_per 
		INNER JOIN periodo_escolar ON  seccion.periodo_esc = periodo_escolar.cod_periodo 
		WHERE cod_seccion = '$this->codigo'";
		#echo $sql;
		$rs = $this->query($sql);
		if( $this->f_numrows($rs) ){
			return $this->f_array($rs);
		}
	}

	function consultar2(){
		$sql = "SELECT cod_seccion, cod_periodo, periodo, grado, letra, CONCAT(tipo_documento,'-',cedula) AS ced_docente, CONCAT(nom1,' ',ape1) AS nom_docente FROM seccion
		INNER JOIN personal ON seccion.docente = personal.cod_per INNER JOIN persona ON personal.cod_per = persona.cod_per 
		INNER JOIN periodo_escolar ON  seccion.periodo_esc = periodo_escolar.cod_periodo 
		WHERE grado = '$this->grado' AND letra = '$this->seccion' AND cod_periodo = '$this->aesc' AND periodo_escolar.estatus = 'A'";
		$rs = $this->query($sql);
		if( $this->f_numrows($rs) ){
			return $this->f_array($rs);
		}
	}

	# PARA REPORTE DE MATRICULA
	// Selecciona solo, nombre y apellido del docente, grado, letra, cantidad de varones y hembras
	function consultar_seccion_reporte(){  # DATOS DE LA SECCION
		$sql = "SELECT CONCAT(tipo_documento,'-',cedula) AS ciDocente,CONCAT(nom1,' ',ape1) AS docente, grado, letra, periodo, cod_periodo,
		(SELECT COUNT(ced_esc) FROM inscripcion INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per INNER JOIN persona ON estudiante.cod_per = persona.cod_per WHERE sexo = 'M' AND seccion = '$this->codigo' AND inscripcion.estatus='A') AS V,
		(SELECT COUNT(ced_esc) FROM inscripcion INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per INNER JOIN persona ON estudiante.cod_per = persona.cod_per WHERE sexo = 'F' AND seccion = '$this->codigo' AND inscripcion.estatus='A') AS H
		FROM seccion INNER JOIN personal ON seccion.docente = personal.cod_per INNER JOIN persona ON personal.cod_per = persona.cod_per INNER JOIN periodo_escolar ON seccion.periodo_esc = periodo_escolar.cod_periodo WHERE cod_seccion = '$this->codigo'";
		if( $rs = $this->query($sql) ){
			return $this->f_array($rs);
		}
	}

	# estudiantes en la sección #>>
	function matricula_seccion($tipo='F',$fd='',$fh='',$mes='',$aesc=''){
		# tipo I = inicial, M = mensual, F = final

		# Query FINAL
		if( $tipo == 'F' ){
			$sql = "SELECT cod_insc, inscripcion.cod_rep AS representante, CONCAT(tipo_documento,'-',cedula) AS ced_est, ced_esc,CONCAT(nom1,' ',nom2) AS nom, CONCAT(ape1,' ',ape2) AS ape, sexo,fecha_nac,
			TIMESTAMPDIFF(YEAR, fecha_nac, CURDATE()) AS edad,desc_lugar, nom_mun, nom_edo, nom_pais, escuela_proc, condicion,
			(SELECT CONCAT(tipo_documento,'-',cedula) FROM persona WHERE persona.cod_per = representante ) AS ci_rep,
			(SELECT CONCAT(sector,' ',calle,' ',nro) FROM direccion
				INNER JOIN direccion_persona ON direccion_persona.cod_dir = direccion.cod_dir
				INNER JOIN persona ON direccion_persona.cod_per = persona.cod_per
				WHERE persona.cod_per = estudiante.cod_per AND direccion_persona.tipo_dir='D'
			) AS direccion_est

			FROM inscripcion INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per
			INNER JOIN persona ON estudiante.cod_per = persona.cod_per 
			INNER JOIN lugar_nacimiento ON estudiante.lugar_nac = lugar_nacimiento.cod_lugar_nac 
			INNER JOIN parroquia ON lugar_nacimiento.cod_parr = parroquia.cod_parr
			INNER JOIN municipio ON parroquia.municipio = municipio.cod_mun 
			INNER JOIN estado ON municipio.estado = estado.cod_edo 
			INNER JOIN paises ON estado.pais = paises.cod_pais
			WHERE seccion = '$this->codigo' AND inscripcion.estatus = 'A' ORDER BY ape1 ASC, ape2 ASC, nom1 ASC, nom2 ASC"; 
			$res = $this->query($sql);
		}

		#Query INICIAL
		else if( $tipo == 'I' ){
			$sql = "SELECT cod_insc, inscripcion.cod_rep AS representante, CONCAT(tipo_documento,'-',cedula) AS ced_est, ced_esc,CONCAT(nom1,' ',nom2) AS nom, CONCAT(ape1,' ',ape2) AS ape, sexo,fecha_nac,
			TIMESTAMPDIFF(YEAR, fecha_nac, CURDATE()) AS edad,desc_lugar, nom_mun, nom_edo, nom_pais, escuela_proc, condicion,
			(SELECT CONCAT(tipo_documento,'-',cedula) FROM persona WHERE persona.cod_per = representante ) AS ci_rep,
			(SELECT CONCAT(sector,' ',calle,' ',nro) FROM direccion
				INNER JOIN direccion_persona ON direccion_persona.cod_dir = direccion.cod_dir
				INNER JOIN persona ON direccion_persona.cod_per = persona.cod_per
				WHERE persona.cod_per = estudiante.cod_per AND direccion_persona.tipo_dir='D'
			) AS direccion_est

			FROM inscripcion INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per
			INNER JOIN persona ON estudiante.cod_per = persona.cod_per 
			INNER JOIN lugar_nacimiento ON estudiante.lugar_nac = lugar_nacimiento.cod_lugar_nac 
			INNER JOIN parroquia ON lugar_nacimiento.cod_parr = parroquia.cod_parr
			INNER JOIN municipio ON parroquia.municipio = municipio.cod_mun 
			INNER JOIN estado ON municipio.estado = estado.cod_edo 
			INNER JOIN paises ON estado.pais = paises.cod_pais
			WHERE seccion = '$this->codigo' AND inscripcion.estatus = 'A' AND fecha BETWEEN '$fd' AND '$fh' ORDER BY ape1 ASC, ape2 ASC, nom1 ASC, nom2 ASC"; 
			$res = $this->query($sql);
			#echo $sql;
		}

		else if( $tipo == 'M' ){
			$ai = substr($aesc, 0,4); 
			$af = date('Y'); # año actual 
			$fd = $ai.'-01-01'; 
			$fh = $af.'-'.$mes.'-31';

			if( $mes<date('m') ){ 
				$sql = "SELECT estudiante.cod_per, cod_insc, inscripcion.cod_rep AS representante, CONCAT(tipo_documento,'-',cedula) AS ced_est, ced_esc,CONCAT(nom1,' ',nom2) AS nom, CONCAT(ape1,' ',ape2) AS ape, sexo,fecha_nac,
				TIMESTAMPDIFF(YEAR, fecha_nac, CURDATE()) AS edad,desc_lugar, nom_mun, nom_edo, nom_pais, escuela_proc, condicion,
				(SELECT CONCAT(tipo_documento,'-',cedula) FROM persona WHERE persona.cod_per = representante ) AS ci_rep,
				(SELECT CONCAT(sector,' ',calle,' ',nro) FROM direccion
					INNER JOIN direccion_persona ON direccion_persona.cod_dir = direccion.cod_dir
					INNER JOIN persona ON direccion_persona.cod_per = persona.cod_per
					WHERE persona.cod_per = estudiante.cod_per AND direccion_persona.tipo_dir='D'
				) AS direccion_est, 
				IF (inscripcion.estatus = 'A' OR inscripcion.estatus = 'I' AND fecha_retiro > '$fh','si','no') AS mostrar 
				FROM inscripcion INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per
				INNER JOIN persona ON estudiante.cod_per = persona.cod_per 
				INNER JOIN lugar_nacimiento ON estudiante.lugar_nac = lugar_nacimiento.cod_lugar_nac 
				INNER JOIN parroquia ON lugar_nacimiento.cod_parr = parroquia.cod_parr
				INNER JOIN municipio ON parroquia.municipio = municipio.cod_mun 
				INNER JOIN estado ON municipio.estado = estado.cod_edo 
				INNER JOIN paises ON estado.pais = paises.cod_pais
				WHERE seccion = '$this->codigo' AND fecha BETWEEN '$fd' AND '$fh' ORDER BY cod_insc DESC, ape1 ASC, ape2 ASC, nom1 ASC, nom2 ASC"; 
				$res = $this->query($sql);
			}

			else{
				# consulta actuales
				$sql = "SELECT cod_insc, inscripcion.cod_rep AS representante, CONCAT(tipo_documento,'-',cedula) AS ced_est, ced_esc,CONCAT(nom1,' ',nom2) AS nom, CONCAT(ape1,' ',ape2) AS ape, sexo,fecha_nac,
				TIMESTAMPDIFF(YEAR, fecha_nac, CURDATE()) AS edad,desc_lugar, nom_mun, nom_edo, nom_pais, escuela_proc, condicion,
				(SELECT CONCAT(tipo_documento,'-',cedula) FROM persona WHERE persona.cod_per = representante ) AS ci_rep,
				(SELECT CONCAT(sector,' ',calle,' ',nro) FROM direccion
					INNER JOIN direccion_persona ON direccion_persona.cod_dir = direccion.cod_dir
					INNER JOIN persona ON direccion_persona.cod_per = persona.cod_per
					WHERE persona.cod_per = estudiante.cod_per AND direccion_persona.tipo_dir='D'
				) AS direccion_est

				FROM inscripcion INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per
				INNER JOIN persona ON estudiante.cod_per = persona.cod_per 
				INNER JOIN lugar_nacimiento ON estudiante.lugar_nac = lugar_nacimiento.cod_lugar_nac 
				INNER JOIN parroquia ON lugar_nacimiento.cod_parr = parroquia.cod_parr
				INNER JOIN municipio ON parroquia.municipio = municipio.cod_mun 
				INNER JOIN estado ON municipio.estado = estado.cod_edo 
				INNER JOIN paises ON estado.pais = paises.cod_pais
				WHERE seccion = '$this->codigo' AND inscripcion.estatus = 'A' AND fecha BETWEEN '$fd' AND '$fh' ORDER BY ape1 ASC, ape2 ASC, nom1 ASC, nom2 ASC"; 
				$res = $this->query($sql);
			}
		}

		if( $tipo != 'M' ){
			$lista = array(); $cont = 0;
			while( $fila = $this->f_array($res) ){
				if( strlen($fila['ced_est'])>2 ){
					$lista[$cont]['CE'] = $fila['ced_est'];
				}else{
					$lista[$cont]['CE'] = $fila['ced_esc'];
				}
				$lista[$cont]['codInsc'] = $fila['cod_insc'];
				$lista[$cont]['ape'] = $fila['ape'];
				$lista[$cont]['nom'] = $fila['nom'];
				$lista[$cont]['sexo'] = $fila['sexo'];
				$lista[$cont]['fnac'] = date('d-m-Y', strtotime($fila['fecha_nac']));
				$lista[$cont]['edad'] = $fila['edad'];
				$lista[$cont]['desc_lugar'] = $fila['desc_lugar'];
				$lista[$cont]['nom_mun'] = mb_strtoupper($fila['nom_mun'],'UTF-8');
				if( $fila['nom_pais'] != 'Venezuela' ){
					$lista[$cont]['nom_edo'] = '';
				}
				else{
					$lista[$cont]['nom_edo'] = mb_strtoupper($fila['nom_edo'],'UTF-8');
				}
				$lista[$cont]['ci_rep'] = $fila['ci_rep'];
				$lista[$cont]['escuela_proc'] = $fila['escuela_proc'];
				$lista[$cont]['direccion_est'] = $fila['direccion_est'];
				$lista[$cont]['condicion'] = $fila['condicion'];
				$cont++;
			}
			return $lista;
		}

		else if( $tipo == 'M' ){
			$lista = array(); $cont = 0;

			if( $mes<date('m') ){ 
				while( $fila = $this->f_array($res) ){
					if( $fila['mostrar'] == 'si' ) {
						$lista[$cont]['cod_insc'] = $fila['cod_insc'];
						$lista[$cont]['cod_per'] = $fila['cod_per'];
						if( strlen($fila['ced_est'])>2 ){
							$lista[$cont]['CE'] = $fila['ced_est'];
						}else{
							$lista[$cont]['CE'] = $fila['ced_esc'];
						}
						$lista[$cont]['codInsc'] = $fila['cod_insc'];
						$lista[$cont]['ape'] = $fila['ape'];
						$lista[$cont]['nom'] = $fila['nom'];
						$lista[$cont]['sexo'] = $fila['sexo'];
						$lista[$cont]['fnac'] = date('d-m-Y', strtotime($fila['fecha_nac']));
						$lista[$cont]['edad'] = $fila['edad'];
						$lista[$cont]['desc_lugar'] = $fila['desc_lugar'];
						$lista[$cont]['nom_mun'] = mb_strtoupper($fila['nom_mun'],'UTF-8');
						if( $fila['nom_pais'] != 'Venezuela' ){
							$lista[$cont]['nom_edo'] = '';
						}
						else{
							$lista[$cont]['nom_edo'] = mb_strtoupper($fila['nom_edo'],'UTF-8');
						}
						$lista[$cont]['ci_rep'] = $fila['ci_rep'];
						$lista[$cont]['escuela_proc'] = $fila['escuela_proc'];
						$lista[$cont]['direccion_est'] = $fila['direccion_est'];
						$lista[$cont]['condicion'] = $fila['condicion'];
						$cont++;
					}
				}
				$nuevaLista = array();
				for ($i=0; $i<count($lista); $i++) { 
					for ($j=0; $j<count($lista); $j++) { 
						# Es el mismo estudiante pero las incripciones son diferentes
						if( $lista[$i]['cod_per'] == $lista[$j]['cod_per'] && $lista[$i]['cod_insc'] != $lista[$j]['cod_insc'] ){
							unset($lista[$j]); # lo elimina
						}
					}
				}
				return $lista;
			}
			else{
				while( $fila = $this->f_array($res) ){
					if( strlen($fila['ced_est'])>2 ){
						$lista[$cont]['CE'] = $fila['ced_est'];
					}else{
						$lista[$cont]['CE'] = $fila['ced_esc'];
					}
					$lista[$cont]['codInsc'] = $fila['cod_insc'];
					$lista[$cont]['ape'] = $fila['ape'];
					$lista[$cont]['nom'] = $fila['nom'];
					$lista[$cont]['sexo'] = $fila['sexo'];
					$lista[$cont]['fnac'] = date('d-m-Y', strtotime($fila['fecha_nac']));
					$lista[$cont]['edad'] = $fila['edad'];
					$lista[$cont]['desc_lugar'] = $fila['desc_lugar'];
					$lista[$cont]['nom_mun'] = mb_strtoupper($fila['nom_mun'],'UTF-8');
					if( $fila['nom_pais'] != 'Venezuela' ){
						$lista[$cont]['nom_edo'] = '';
					}
					else{
						$lista[$cont]['nom_edo'] = mb_strtoupper($fila['nom_edo'],'UTF-8');
					}
					$lista[$cont]['ci_rep'] = $fila['ci_rep'];
					$lista[$cont]['escuela_proc'] = $fila['escuela_proc'];
					$lista[$cont]['direccion_est'] = $fila['direccion_est'];
					$lista[$cont]['condicion'] = $fila['condicion'];
					$cont++;
				}
				return $lista;
			}
		}
	}

	#Nomina de representantes en la seccion
	function nomina_representantes($tipo='F',$fd='',$fh='', $mes='', $aesc=''){
		# tipo I = inicial, M = mensual, F = final

		if( $tipo == 'F' ){	
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
			WHERE seccion = '$this->codigo' AND inscripcion.estatus = 'A' ORDER BY ape1 ASC, nom1 ASC"; 
			
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

		else if( $tipo == 'I' ){
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
			WHERE seccion = '$this->codigo' AND inscripcion.estatus = 'A' AND inscripcion.fecha BETWEEN '$fd' AND '$fh' ORDER BY ape1 ASC, nom1 ASC"; 
			
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

		else if( $tipo == 'M' ){
			$ai = substr($aesc, 0,4); 
			$af = date('Y'); # año actual 
			$fd = $ai.'-01-01'; 
			$fh = $af.'-'.$mes.'-31';

			if( $mes<date('m') ){ 
				$sql = "SELECT DISTINCT representante.cod_per,CONCAT(tipo_documento,'-',cedula) AS cedula, CONCAT(nom1,' ',ape1) AS nombre,
				sexo, email, nom_ginst, nom_ocup, IF (inscripcion.estatus = 'A' OR inscripcion.estatus = 'I' AND fecha_retiro > '$fh','si','no') AS mostrar, 
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
				WHERE seccion = '$this->codigo' AND inscripcion.fecha BETWEEN '$fd' AND '$fh' ORDER BY inscripcion.cod_insc DESC, ape1 ASC, nom1 ASC"; 
				$rs = $this->query($sql);
				#echo $sql.'<br><br>';
				$lista = array(); $cont=0;
				while( $fila = $this->f_array($rs) ){
					if( $fila['mostrar'] == 'si' ){
						$lista[$cont]['cod_per'] = $fila['cod_per'];
						$lista[$cont]['cedula'] = $fila['cedula'];
						$lista[$cont]['nombre'] = $fila['nombre'];
						$lista[$cont]['sexo'] = $fila['sexo'];
						$lista[$cont]['email'] = $fila['email'];
						$lista[$cont]['direccion'] = $fila['direccion'];
						$lista[$cont]['telefono'] = $fila['telefono'];
						$lista[$cont]['email'] = $fila['email'];
						$lista[$cont]['ocup'] = $fila['nom_ocup'];
					}
					$cont++;
				}
				return $lista;
			}

			else{
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
				WHERE seccion = '$this->codigo' AND inscripcion.estatus = 'A' ORDER BY ape1 ASC, nom1 ASC"; 
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
	}

	# >>>> REPORTE ESTADISTICO MENSUAL
	function cantidad_secciones_grado(){
		$sql = "SELECT * FROM seccion WHERE periodo_esc = '$this->aesc'";
		$lista = array(); $i = 0;
		$rs = $this->query($sql);
		while( $fila = $this->f_array($rs) ){
			$lista[$i]['gdo'] = $fila['grado'];
			$i++;
		}
		return $lista;
	}

	# PARA REPORTES DE ESTADISTICA
	function matricula_seccion_inicial($fd, $fh){
		#$rsSec = $obj->consultar2();
	}

	function matricula_seccion_Mensual($mes){

	}

	function matricula_seccion_Final(){

	}
}
?>