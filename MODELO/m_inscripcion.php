<?php 
	include_once('MyDB.php');
	class cls_inscripcion extends MyDB{

		private $codigo, $est, $rep, $parentesco, $seccion, $modo, $cond, $fecha, $procedencia, $motivo;
		private $periodo; 

		function __construct(){
			parent::__construct(); # constructor del padre
			$this->codigo = null; # codigo de inscripcion
			$this->est = null; # cedula o cedula escolar
			$this->rep = null;
			$this->parentesco = null;
			$this->seccion = null;
			$this->modo = null; # modalidad (Nuevo Ingreso, Prosecucion)
			$this->cond = null; # condicion (Promovido, Repitiente, Traslado
			$this->fecha = null;
			$this->procedencia = null;
			$this->motivo = null;
			$this->periodo = null;
		}
		# funciones SET
		function set_codigo($cod){
			$this->codigo = $cod;
		}

		function set_codEst($cod){
			$this->est = $cod;
		}

		function set_Periodo($per){
			$this->periodo = $per;
		}

		function set_CE($ce){ #>> agregar cedula tambien
			$this->est = $ce;
		}

		function set_datos_insc($est,$rep,$par,$sec,$modo,$cond,$fc,$proc,$motivo,$periodo){
			$this->est = $est;
			$this->rep = $rep;
			$this->parentesco = $par;
			$this->seccion = $sec;
			$this->modo = $modo;
			$this->cond = $cond;
			$this->fecha = $fc;
			$this->procedencia =  mb_strtoupper($this->limpiarCadena($proc));
			$this->motivo =  mb_strtoupper($this->limpiarCadena($motivo));
			$this->periodo = $periodo; # codigo periodo
		}

		function incluir_insc(){ # inscluye la inscripcion
			$this->empezar_op();
			$operacion = false;
		
			# modifica el cupo e la sección
			$sql = "UPDATE seccion SET cupos = cupos-1 WHERE cod_seccion = '$this->seccion'
			AND cupos > 0";
			$this->query($sql); 

			if( $this->f_affectrows() ){
				# incluye la inscripcion
				$sql = "INSERT INTO inscripcion (cod_est,cod_rep,parentesco,seccion,modalidad,condicion,fecha,escuela_proc,motivo) 
				VALUES ('$this->est','$this->rep','$this->parentesco','$this->seccion','$this->modo','$this->cond','$this->fecha','$this->procedencia','$this->motivo')";
				$this->query($sql); 

				if( $this->f_affectrows() ){
					# modifica el estatus del estudiante
					$estatus = '';
					switch ($this->modo) {
						case 'N':
							$estatus = '1';
							break;
						
						case 'R':
							$estatus = '2';
							break;
					}
					$sql = "UPDATE estudiante SET estatus = '$estatus' WHERE cod_per = '$this->est' AND estatus != '4'"; # Estudiante Activo, no puede inscribir a estudiantes graduados
					$this->query($sql);
					
					$operacion = true;
				}
			}
			if($operacion == true){
				$this->finalizar_op();
				return true;
			}
			else{
				$this->deshacer_op();
			}
		}

		function modificar_insc($secAntigua=''){ # parametro antigua seccion, si se realiza un cambio de sección
			if( $secAntigua != '' ){
				# modifica el cupo de la sección
				$sql = "UPDATE seccion SET cupos = cupos-1 WHERE cod_seccion = '$this->seccion'
				AND cupos > 0";
				$this->query($sql); 

				$sql = "UPDATE seccion SET cupos = cupos+1 WHERE cod_seccion = '$secAntigua'";
				$this->query($sql); 
			}

			# Modifica en la tabla inscripcion
			$sql = "UPDATE inscripcion SET cod_rep='$this->rep',parentesco='$this->parentesco',
			seccion='$this->seccion', modalidad='$this->modo', condicion='$this->cond', fecha='$this->fecha',
			escuela_proc='$this->procedencia', motivo='$this->motivo' WHERE cod_insc='$this->codigo'";
			$this->query($sql); 

			# Modifica en la tabla estudiante
			$estatus = '';
			switch ($this->modo) {
				case 'N':
					$estatus = '1';
					break;
				
				case 'R':
					$estatus = '2';
					break;
			}
			$sql = "UPDATE estudiante SET estatus = '$estatus' WHERE cod_per = '$this->est' AND estatus != '4'"; # Estudiante Activo, no puede inscribir a estudiantes graduados
			$this->query($sql);
		}

		function listar_historial_est(){ # selecciona los registros de un estudiante
			$lista = array(); $cont = 0;
			$sql = "SELECT CONCAT(tipo_documento,'-',cedula) AS rep, grado, letra ,modalidad, condicion, fecha, escuela_proc, motivo, periodo FROM inscripcion
			INNER JOIN seccion ON inscripcion.seccion = seccion.cod_seccion
			INNER JOIN periodo_escolar ON seccion.periodo_esc = periodo_escolar.cod_periodo
			INNER JOIN representante ON inscripcion.cod_rep = representante.cod_per 
			INNER JOIN persona ON representante.cod_per = persona.cod_per
			WHERE inscripcion.cod_est = '$this->est' ORDER BY cod_insc DESC";
			$rs = $this->query($sql);
			while( $fila = $this->f_array($rs) ){
				$lista[$cont]['rep'] = $fila['rep'];
				$lista[$cont]['gdo'] = $fila['grado'];
				$lista[$cont]['lta'] = $fila['letra'];
				$lista[$cont]['modo'] = $fila['modalidad'];
				$lista[$cont]['cond'] = $fila['condicion'];
				$lista[$cont]['fecha'] = $fila['fecha'];
				$lista[$cont]['proc'] = $fila['escuela_proc'];
				$lista[$cont]['motivo'] = $fila['motivo'];
				$lista[$cont]['periodo'] = $fila['periodo'];
				$cont++;
			}
			return $lista;
		}

		# ----------------------- MATRICULA ------------------:

		# selecciona la cantidad de estudiantes inscritos en una sección
		function cons_estudiantes_seccion($seccion){
			$sql = "SELECT count(cod_est) FROM inscripcion WHERE seccion = '$seccion' AND estatus = 'A'";
			$rs = $this->query($sql);
			return $this->f_array($rs)[0]; # devuelve la cantidad
		}

		# selecciona todas las inscripciones del año escolar
		function cons_estudiantes_aesc($aesc){
			$sql = "SELECT count(cod_insc) FROM inscripcion
			INNER JOIN seccion ON inscripcion.seccion = seccion.cod_seccion
			INNER JOIN periodo_escolar ON seccion.periodo_esc = periodo_escolar.cod_periodo
			WHERE cod_periodo = '$aesc' AND inscripcion.estatus = 'A'";
			$rs = $this->query($sql);
			return $this->f_array($rs)[0]; # devuelve la cantidad
		}

		# contador de representados en el año escolar
		function cons_representantes_aesc($aesc){
			$sql="SELECT DISTINCT representante.cod_per FROM representante
			INNER JOIN persona ON representante.cod_per = persona.cod_per
			INNER JOIN inscripcion ON inscripcion.cod_rep = representante.cod_per 
			INNER JOIN seccion ON inscripcion.seccion = seccion.cod_seccion
			INNER JOIN periodo_escolar ON seccion.periodo_esc = periodo_escolar.cod_periodo
			WHERE cod_periodo = '$aesc' AND inscripcion.estatus = 'A'";
			$rs = $this->query($sql);
			$lista = array(); $cont=0;
			while( $fila = $this->f_array($rs) ){
				$cont++;
			}
			return $cont;
		}

		# Consulta la inscripcion de un estudiante por codigo de persona y año escolar
		function consultar(){
			$sql = "SELECT cod_insc,cod_est,cod_rep,parentesco,seccion,modalidad,condicion,fecha,escuela_proc,motivo,grado,letra FROM inscripcion 
			INNER JOIN seccion ON inscripcion.seccion = seccion.cod_seccion
			INNER JOIN periodo_escolar ON seccion.periodo_esc = periodo_escolar.cod_periodo
			WHERE cod_est = '$this->est' AND cod_periodo = '$this->periodo' AND inscripcion.estatus='A'";
			#echo $sql;
			if( $rs = $this->query($sql) ){
				return $this->f_array($rs);
			}
		}

		# Consulta datos de la inscripcion, datos personales, seccion y año escolar
		function consultar2(){
			$sql = "SELECT cod_insc,cod_est,ced_esc,CONCAT(tipo_documento,'-',cedula) AS cedula,CONCAT(nom1,' ',nom2) AS nombres, 
			CONCAT(ape1,' ',ape2) AS apellidos, seccion, grado, letra, cod_periodo, periodo FROM inscripcion 
			INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per
			INNER JOIN persona ON estudiante.cod_per = persona.cod_per 
			INNER JOIN seccion ON inscripcion.seccion = seccion.cod_seccion
			INNER JOIN periodo_escolar ON seccion.periodo_esc = periodo_escolar.cod_periodo 
			WHERE estudiante.ced_esc = '$this->est' AND cod_periodo = '$this->periodo'OR CONCAT(tipo_documento,'-',cedula) = '$this->est' AND cod_periodo = '$this->periodo'";
			
			if( $rs = $this->query($sql) ){
				return $this->f_array($rs);
			}
		}

		# NORMALMENTE UTILIZADO PARA REPORTES DE ESTUDIANTE pero puede servir para otras cosas
		function consultar_inscripcion(){
			$sql = "SELECT CONCAT(tipo_documento,'-',cedula) AS cedula ,ced_esc,CONCAT(nom1,' ',nom2) AS nombres, CONCAT(ape1,' ',ape2) AS apellidos, TIMESTAMPDIFF(YEAR, fecha_nac, CURDATE()) AS edad, grado, letra, periodo, desc_lugar, nom_mun, nom_edo, cod_pais, estudiante.estatus AS estatus_est
			FROM inscripcion INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per
			INNER JOIN persona ON estudiante.cod_per = persona.cod_per
			INNER JOIN lugar_nacimiento ON estudiante.lugar_nac = lugar_nacimiento.cod_lugar_nac
			INNER JOIN parroquia ON lugar_nacimiento.cod_parr = parroquia.cod_parr
			INNER JOIN municipio ON parroquia.municipio = municipio.cod_mun
			INNER JOIN estado ON municipio.estado = estado.cod_edo
			INNER JOIN paises ON estado.pais = paises.cod_pais
			INNER JOIN seccion ON inscripcion.seccion = seccion.cod_seccion
			INNER JOIN periodo_escolar ON seccion.periodo_esc = periodo_escolar.cod_periodo
			WHERE ced_esc = '$this->est' AND cod_periodo = '$this->periodo'
			OR CONCAT(tipo_documento,'-',cedula) = '$this->est' AND cod_periodo = '$this->periodo'";
			
			if( $rs = $this->query($sql) ){ # Existen datos
				return $this->f_array($rs);
			}
		}

		# consulta la ultima inscripcion de un estudiante esté activa o inactiva
		function consultar_ult(){
	
			$sql = "SELECT cod_insc, cod_est, seccion, grado, letra, periodo, periodo_escolar.estatus AS estatus_p FROM inscripcion 
			INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per
			INNER JOIN persona ON estudiante.cod_per = persona.cod_per
			INNER JOIN seccion ON inscripcion.seccion = seccion.cod_seccion
			INNER JOIN periodo_escolar ON seccion.periodo_esc = periodo_escolar.cod_periodo
			WHERE ced_esc = '$this->est' OR CONCAT(tipo_documento,'-',cedula) = '$this->est' ORDER BY cod_insc DESC LIMIT 1";
			#echo $sql;
			if( $rs = $this->query($sql) ){
				return $this->f_array($rs);
			}
		}

		# Consultar ultima inscripción activa
		function consultar_ult_A(){
			$sql = "SELECT cod_insc, grado, letra, periodo, seccion FROM inscripcion
			INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per
			INNER JOIN persona ON estudiante.cod_per = persona.cod_per
			INNER JOIN seccion ON inscripcion.seccion = seccion.cod_seccion
			INNER JOIN periodo_escolar ON seccion.periodo_esc = periodo_escolar.cod_periodo
			WHERE ced_esc = '$this->est' AND inscripcion.estatus = 'A' 
			OR CONCAT(tipo_documento,'-',cedula) = '$this->est' AND inscripcion.estatus = 'A' 
			ORDER BY cod_insc DESC LIMIT 1";
			#echo $sql;
			if( $rs = $this->query($sql) ){
				return $this->f_array($rs);
			}
		}

		# Ultima inscripcion Inactiva
		function consultar_ult_I(){
			$sql = "SELECT cod_insc, grado, letra, periodo FROM inscripcion
			INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per
			INNER JOIN persona ON estudiante.cod_per = persona.cod_per
			INNER JOIN seccion ON inscripcion.seccion = seccion.cod_seccion
			INNER JOIN periodo_escolar ON seccion.periodo_esc = periodo_escolar.cod_periodo
			WHERE ced_esc = '$this->est' AND inscripcion.estatus = 'I' 
			OR CONCAT(tipo_documento,'-',cedula) = '$this->est' AND inscripcion.estatus = 'I' 
			ORDER BY cod_insc DESC LIMIT 1";
			#echo $sql;
			if( $rs = $this->query($sql) ){
				return $this->f_array($rs);
			}
		}

		# USADA EN: lista_matricula.php 
		function listar_matricula($codSec){	
			$sql = "SELECT tipo_documento, cedula, ced_esc, CONCAT(nom1,' ',nom2,' ',ape1,' ',ape2) AS nom_ape, fecha_nac
			FROM inscripcion INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per 
			INNER JOIN persona ON estudiante.cod_per = persona.cod_per
			WHERE inscripcion.estatus = 'A' AND seccion = '$codSec'";
			$rs = $this->query($sql);

			$lista = array(); $cont=0;
			while( $fila = $this->f_array($rs) ){
				# acomodar esto cuando se modifique la dinamica de las cedulas y CE (si el estudiante tiene cedula la sustituya por la CE en la tabla estudiante)
				if( $fila['cedula'] == '' ){
					$lista[$cont]['ced'] = $fila['ced_esc'];
				}else{
					$lista[$cont]['ced'] = $fila['tipo_documento'].'-'.$fila['cedula'];
				}
				$lista[$cont]['nom_ape'] = $fila['nom_ape'];
				$lista[$cont]['edad'] = $this->calcular_edad($fila['fecha_nac']);
				$cont++;
			}
			return $lista;
		}

		# >>> reporte de estadistica

		function estadistica_inicial($fd, $fh, $cond='N'){
			# cond = condicion(P,R,T) promovido,repitiente,traslado
			if( $cond == 'R' ){ $condicion = "condicion = 'R' ";}else{$condicion = "condicion != 'xd' ";}
			$sql = "SELECT estudiante.cod_per, CONCAT(tipo_documento,'-',cedula) AS cedula, ced_esc, nom1, ape1 ,TIMESTAMPDIFF(YEAR, fecha_nac, CURDATE()) AS edad, sexo, cod_insc, grado, letra, modalidad, condicion, fecha 
			FROM inscripcion INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per INNER JOIN persona ON estudiante.cod_per = persona.cod_per
			INNER JOIN seccion ON inscripcion.seccion = seccion.cod_seccion INNER JOIN periodo_escolar ON seccion.periodo_esc = periodo_escolar.cod_periodo
			WHERE inscripcion.estatus = 'A' AND $condicion AND cod_periodo = '$this->periodo' AND fecha BETWEEN '$fd' AND '$fh'";
			#echo $sql;
			$rs = $this->query($sql);
			$lista = array(); $cont = 0;
			while( $fila = $this->f_array($rs) ){
				if( strlen($fila['cedula'])>2 ){
					$lista[$cont]['CE'] = $fila['cedula'];
				}else{
					$lista[$cont]['CE'] = $fila['ced_esc'];
				}
				$lista[$cont]['edad'] = $fila['edad'];
				$lista[$cont]['sexo'] = $fila['sexo'];
				$lista[$cont]['grado'] = $fila['grado'];
				$cont++;
			}
			return $lista;
		}	

		function estadistica_mensual($mes, $aesc, $cond='N'){
			$ai = substr($aesc, 0,4); 
			$af = date('Y'); # año actual 
			$fd = $ai.'-01-01'; 
			$fh = $af.'-'.$mes.'-31';
			
			if( $cond == 'R' ){ $condicion = "condicion = 'R' ";}else{$condicion = "condicion != 'xd' ";} # todos xd

			if( $mes<date('m') ){ 
				# Muestra todas las inscripciones que ocurrieron hasta $mes. sin embargo, habrán fallas si un estudiante se retira
				# y se inscribe en el mismo año (aunque realmente no ocurre), encontrando 2 o más registros y esto alterará los datos mostrados en el reporte.
				$sql = "SELECT estudiante.cod_per, CONCAT(tipo_documento,'-',cedula) AS cedula, ced_esc, nom1, ape1 ,TIMESTAMPDIFF(YEAR, fecha_nac, CURDATE()) AS edad, sexo, cod_insc, grado, letra, modalidad, condicion, fecha, fecha_retiro,inscripcion.estatus,
				IF (inscripcion.estatus = 'A' OR inscripcion.estatus = 'I' AND fecha_retiro > '$fh','si','no') AS mostrar 
				FROM inscripcion INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per INNER JOIN persona ON estudiante.cod_per = persona.cod_per
				INNER JOIN seccion ON inscripcion.seccion = seccion.cod_seccion INNER JOIN periodo_escolar ON seccion.periodo_esc = periodo_escolar.cod_periodo
				WHERE $condicion AND cod_periodo = '$this->periodo' AND fecha BETWEEN '$fd' AND '$fh' ORDER BY cod_insc DESC"; # Importantisimo traerlo en ese orden!
			}
			else{
				# consulta solo actuales
				$sql = "SELECT estudiante.cod_per, CONCAT(tipo_documento,'-',cedula) AS cedula, ced_esc, nom1, ape1 ,TIMESTAMPDIFF(YEAR, fecha_nac, CURDATE()) AS edad, sexo, cod_insc, grado, letra, modalidad, condicion, fecha, inscripcion.estatus 
				FROM inscripcion INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per INNER JOIN persona ON estudiante.cod_per = persona.cod_per
				INNER JOIN seccion ON inscripcion.seccion = seccion.cod_seccion INNER JOIN periodo_escolar ON seccion.periodo_esc = periodo_escolar.cod_periodo
				WHERE inscripcion.estatus = 'A' AND $condicion AND cod_periodo = '$this->periodo' AND fecha BETWEEN '$fd' AND '$fh'";
			}	
			#echo $sql;	

			$rs = $this->query($sql);
			$lista = array(); $cont = 0;

			if( $mes<date('m') ){
				while( $fila = $this->f_array($rs) ){
					if( $fila['mostrar'] == 'si' ) {
						if( strlen($fila['cedula'])>2 ){
							$lista[$cont]['CE'] = $fila['cedula'];
						}else{
							$lista[$cont]['CE'] = $fila['ced_esc'];
						}
						$lista[$cont]['cod_per'] = $fila['cod_per'];
						$lista[$cont]['cod_insc'] = $fila['cod_insc'];
						$lista[$cont]['edad'] = $fila['edad'];
						$lista[$cont]['sexo'] = $fila['sexo'];
						$lista[$cont]['grado'] = $fila['grado'];
						$cont++;
					}
				}
			}
			else{
				while( $fila = $this->f_array($rs) ){	
					if( strlen($fila['cedula'])>2 ){
						$lista[$cont]['CE'] = $fila['cedula'];
					}else{
						$lista[$cont]['CE'] = $fila['ced_esc'];
					}
					$lista[$cont]['cod_per'] = $fila['cod_per'];
					$lista[$cont]['cod_insc'] = $fila['cod_insc'];
					$lista[$cont]['edad'] = $fila['edad'];
					$lista[$cont]['sexo'] = $fila['sexo'];
					$lista[$cont]['grado'] = $fila['grado'];
					$cont++;				
				}
			}

			# Solución a la falla por el momento, buscar en el arreglo si existe el estudiante mas de una vez
			# si es asi, elimina las inscripciones antiguas dejando la actual
			# Otra solución podría ser, no consultar datos únicos como la fecha y codigo de inscripcion etc, en el query y hacer un SELECT DISTINTIC
			if( $mes<date('m') ){
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
				return $lista;
			}
		}

		function estadistica_final(){
			$sql = "SELECT estudiante.cod_per, CONCAT(tipo_documento,'-',cedula) AS cedula, ced_esc, nom1, ape1 ,TIMESTAMPDIFF(YEAR, fecha_nac, CURDATE()) AS edad, sexo, cod_insc, grado, letra, modalidad, condicion, fecha, inscripcion.estatus 
			FROM inscripcion INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per INNER JOIN persona ON estudiante.cod_per = persona.cod_per
			INNER JOIN seccion ON inscripcion.seccion = seccion.cod_seccion INNER JOIN periodo_escolar ON seccion.periodo_esc = periodo_escolar.cod_periodo
			WHERE inscripcion.estatus = 'A' AND cod_periodo = '$this->periodo'";
			#echo $sql;
			$rs = $this->query($sql);
			$lista = array(); $cont = 0;
			while( $fila = $this->f_array($rs) ){
				if( strlen($fila['cedula'])>2 ){
					$lista[$cont]['CE'] = $fila['cedula'];
				}else{
					$lista[$cont]['CE'] = $fila['ced_esc'];
				}
				$lista[$cont]['cod_per'] = $fila['cod_per'];
				$lista[$cont]['cod_insc'] = $fila['cod_insc'];
				$lista[$cont]['edad'] = $fila['edad'];
				$lista[$cont]['sexo'] = $fila['sexo'];
				$lista[$cont]['grado'] = $fila['grado'];
				$cont++;
			}
			return $lista;
		}


		# reporte para boletines
		function boletines(){
			$tipoDoc = substr($this->est, 0, 1);
			$cedula = substr($this->est, 2);
			$sql = "SELECT CONCAT(tipo_documento,'-',cedula) AS cedula,cod_insc,cod_est,ced_esc,CONCAT(nom1,' ',nom2) AS nombres, CONCAT(ape1,' ',ape2) AS apellidos, TIMESTAMPDIFF(YEAR, fecha_nac, CURDATE()) AS edad, sexo, seccion, fecha_nac, grado, letra, periodo_escolar.cod_periodo, periodo, desc_lugar, nom_mun, nom_edo, cod_pais, nom_pais, inscripcion.cod_rep, docente 
			FROM inscripcion 
			INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per 
			INNER JOIN persona ON estudiante.cod_per = persona.cod_per
			INNER JOIN seccion ON inscripcion.seccion = seccion.cod_seccion
			INNER JOIN periodo_escolar ON seccion.periodo_esc = periodo_escolar.cod_periodo
			INNER JOIN lugar_nacimiento ON estudiante.lugar_nac = lugar_nacimiento.cod_lugar_nac
			INNER JOIN parroquia ON lugar_nacimiento.cod_parr = parroquia.cod_parr
			INNER JOIN municipio ON parroquia.municipio = municipio.cod_mun
			INNER JOIN estado ON municipio.estado = estado.cod_edo
			INNER JOIN paises ON estado.pais = paises.cod_pais
			WHERE tipo_documento='$tipoDoc' AND cedula='$cedula' AND periodo = '$this->periodo' 
			OR ced_esc = '$this->est' AND periodo = '$this->periodo'";	
			
			$rs = $this->query($sql);
			if($this->f_numrows($rs)){
				return $this->f_array($rs);
			}		
		}	
	}
?>