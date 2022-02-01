<?php 
	# cambiar el nombre de este archivo por retiro
	include_once('MyDB.php');
	class cls_egreso extends MyDB{
		
		private $codigo, $cod_insc, $seccion, $CE, $fecha, $causa, $obs, $cod_periodo, $periodo;
		function __construct(){
			parent::__construct();
			$this->codigo = null; # codigo de retiro
			$this->cod_est = null; # codigo del estudiante
			$this->cod_insc = null; # codigo de estudiante
			$this->seccion = null; # codigo de la sección
			$this->CE = null; 
			$this->fecha = null;
			$this->causa = null;
			$this->obs = null; 
			$this->cod_periodo = null;
			$this->periodo = null;
		}

		# codigo de egreso
		function set_codigo($a){
			$this->codigo = $a;
		}

		function set_codPeriodo($c){
			$this->cod_periodo = $c;
		}

		function setPeriodo($a){
			$this->periodo = $a;
		}

		function setCedula($ced){
			$this->CE = $ced;
		}

		function set_retiro($a,$b,$c,$d,$e,$f){
			$this->cod_est = $a;
			$this->cod_insc = $b;
			$this->seccion = $c;
			$this->fecha = $d;
			$this->causa = $e;
			$this->obs = $this->limpiarCadena($f);
		}

		function incluir(){ # incluir retiro
			$this->empezar_op();
			$op = false;

			# Estatus de estudiante: 1 = nuevo; 2 = regular; 3 = retirado; 4 = graduado;
			# Modifica el estatus del estudiante a 3 = retirado
			$sql = "UPDATE estudiante SET estatus = '3' WHERE cod_per = '$this->cod_est'";
			$this->query($sql);

			if( $this->f_affectrows() ){
	
				# Modifica el estatus de la inscripcion del estudiante a I (inactiva) y la fecha de retiro
				$sql = "UPDATE inscripcion SET estatus = 'I', fecha_retiro = '$this->fecha' WHERE cod_insc = '$this->cod_insc'";
				$this->query($sql);

				# reestablece el cupo de su sección a la que pertenecía
				$sql = "UPDATE seccion SET cupos = cupos+1 WHERE cod_seccion = '$this->seccion'";
				$this->query($sql);
				
				# Registra en la tabla retiro
				$sql = "INSERT INTO retiro (cod_insc,fecha,causa,observacion) VALUES ('$this->cod_insc','$this->fecha','$this->causa','$this->obs')";
				$this->query($sql);

				if( $this->f_affectrows() ){
					$op = true;
				}
			}
			if($op == true){
				$this->finalizar_op();
				return true;
			}
			else{
				$this->deshacer_op();
			}
		}

		function modificar(){
			if( $rs = $this->consultar() ){
				$sql = "UPDATE retiro SET fecha='$this->fecha', causa='$this->causa', observacion='$this->obs' WHERE cod_retiro = '$this->codigo'";
				$this->query($sql);
				#echo $sql;

				$cod_insc = $rs['cod_insc'];
				$sql = "UPDATE inscripcion SET fecha_retiro = '$this->fecha' WHERE cod_insc = '$cod_insc'";
				$this->query($sql);
			}
			return true;
		}
		
		function eliminar(){
			$this->empezar_op();
			$op = false;
			// $sql = "UPDATE retiro SET estatus = 'C' WHERE cod_retiro = '$this->codigo'";
			// $this->query($sql);
			if( $rs = $this->consultar() ){

				# Eliminar retiro de ultimo
				$sql = "DELETE FROM retiro WHERE cod_retiro = '$this->codigo'";
				$this->query($sql);

				if( $this->f_affectrows() ){
					# cambia el estatus del estudiante
					$cod_est = $rs['cod_est'];
					if( $rs['modalidad'] == 'R' ){
						$estatus = '2';
					}
					else if( $rs['modalidad'] == 'N' ){
						$estatus = '1';
					}
					$sql = "UPDATE estudiante SET estatus = '$estatus' WHERE cod_per = '$cod_est'";
					$this->query($sql);
				}

				if( $this->f_affectrows() ){	
					# vuelve a activar la inscripción
					$cod_insc = $rs['cod_insc'];
					$sql = "UPDATE inscripcion SET estatus = 'A', fecha_retiro = '$this->fecha' WHERE cod_insc = '$cod_insc'";
					$this->query($sql);
				}

				if( $this->f_affectrows() ){
					# resta el cupo de su sección a la que pertenecía
					$cod_seccion = $rs['seccion'];
					$sql = "UPDATE seccion SET cupos = cupos-1 WHERE cod_seccion = '$cod_seccion'";
					$this->query($sql);
					$op = true;
				}				
			}
			if($op == true){
				$this->finalizar_op();
				return true;
			}
			else{
				$this->deshacer_op();
			}
		}

		# consulta por codigo
		function consultar(){
			$sql = "SELECT cod_retiro, retiro.cod_insc, inscripcion.cod_est, modalidad, retiro.fecha, causa, observacion, retiro.estatus, seccion FROM retiro 
			INNER JOIN inscripcion ON retiro.cod_insc = inscripcion.cod_insc INNER JOIN seccion ON inscripcion.seccion = seccion.cod_seccion
			WHERE cod_retiro = '$this->codigo'";
			#echo $sql;
			$rs = $this->query($sql);
			if( $this->f_numrows($rs) ){
				return $this->f_array($rs);
			}
		}

		# consulta para reporte de estudiante
		function consultarRetiroReporte(){
			# consulta el último retiro con su ultima inscripcion
			$sql = "SELECT cedula,ced_esc,estudiante.cod_per,cod_retiro,retiro.fecha,causa,retiro.observacion,periodo,retiro.estatus
			FROM retiro
			INNER JOIN inscripcion ON retiro.cod_insc = inscripcion.cod_insc
			INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per
			INNER JOIN persona ON estudiante.cod_per = persona.cod_per
			INNER JOIN seccion ON inscripcion.seccion = seccion.cod_seccion 
			INNER JOIN periodo_escolar ON seccion.periodo_esc = periodo_escolar.cod_periodo
			WHERE ced_esc = '$this->CE' AND retiro.estatus = 'A' OR CONCAT(tipo_documento,'-',cedula) = '$this->CE' 
			AND retiro.estatus = 'A' 
			ORDER BY cod_retiro DESC LIMIT 1";
			#echo $sql;
			$rs = $this->query($sql);
			if( $this->f_numrows($rs) ){
				return $this->f_array($rs);
			}
		}

		function listar(){
			$sql = "SELECT cod_retiro, retiro.cod_insc, grado, letra, CONCAT(tipo_documento,'-',cedula) AS cedula, estudiante.ced_esc, retiro.fecha, retiro.causa, retiro.observacion, CONCAT(nom1,' ',nom2) AS nom, CONCAT(ape1,' ',ape2) AS ape
			FROM retiro 
			INNER JOIN inscripcion ON retiro.cod_insc = inscripcion.cod_insc
			INNER JOIN seccion ON inscripcion.seccion = seccion.cod_seccion
			INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per
			INNER JOIN persona ON estudiante.cod_per = persona.cod_per
			WHERE retiro.estatus='A' AND seccion.periodo_esc = '$this->cod_periodo' ORDER BY retiro.fecha ASC";
			#echo $sql;
			$rs = $this->query($sql);
			return $this->enviar_array($rs);
		}

		function filtrar($grado){
			$sql = "SELECT cod_retiro, retiro.cod_insc, grado, letra, CONCAT(tipo_documento,'-',cedula) AS cedula, estudiante.ced_esc, retiro.fecha, retiro.causa, retiro.observacion, CONCAT(nom1,' ',nom2) AS nom, CONCAT(ape1,' ',ape2) AS ape
			FROM retiro 
			INNER JOIN inscripcion ON retiro.cod_insc = inscripcion.cod_insc
			INNER JOIN seccion ON inscripcion.seccion = seccion.cod_seccion
			INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per
			INNER JOIN persona ON estudiante.cod_per = persona.cod_per
			WHERE retiro.estatus='A' AND grado='$grado' AND seccion.periodo_esc = '$this->cod_periodo' ORDER BY retiro.fecha ASC";
			#echo $sql;
			$rs = $this->query($sql);
			return $this->enviar_array($rs);
		}

		function enviar_array($rs){
			$lista = array(); $cont = 0;
			while( $fila = $this->f_array($rs) ){
				$lista[$cont]['cod_retiro'] = $fila['cod_retiro'];
				$lista[$cont]['cod_insc'] = $fila['cod_insc'];
				if( strlen($fila['cedula']) > 3 ){
					$lista[$cont]['cedula'] = $fila['cedula'];
				}
				else{
					$lista[$cont]['cedula'] = $fila['ced_esc'];
				}
				$lista[$cont]['grado'] = $fila['grado'];
				$lista[$cont]['letra'] = $fila['letra'];
				$lista[$cont]['nom'] = $fila['nom'];
				$lista[$cont]['ape'] = $fila['ape'];
				$lista[$cont]['fecha'] = $fila['fecha'];
				$lista[$cont]['causa'] = $fila['causa'];
				$lista[$cont]['observacion'] = $fila['observacion'];	
				$cont++;
			}
			return $lista;
		}
	}
?>