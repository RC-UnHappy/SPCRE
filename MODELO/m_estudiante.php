<?php 
	include_once('m_persona.php');
	include_once('m_lugarNacimiento.php');

	class cls_estudiante extends cls_persona{
		# Estudiante, lugar de nacimiento y medidas antropométricas
		private $cod_madre, $cod_padre;
		private $cedEscolar, $parrNac, $lugarNac, $estatura, $peso, $camisa, $pantalon, $calzado;  
		# Antecedentes pre y pos natales
		private $embarazo,$parto,$mad_neu,$alm,$sno,$dent,$edadp,$lenguaje,$cEsfin,$observacion;
		# Datos socieconómicos
		private $tipo_vnda, $cond_vnda, $cond_infra_vnda, $num_hab, $num_prnsH, $num_prnsT, $num_Herm, $num_HermEsc, $ingreso_fam, $beca, $s_canaima;
		# Datos de Salud
		private $enfermedadesP, $enfermedadesAE, $alergico, $vacunas, $discapacidad;
		private $fecha_ingreso, $estatus; # Estatus = A(Activo), S(Sin inscribir), R(Retirado), G(Graduado)
		
		# constructor de la clase
		function __construct(){
			parent::__construct(); # constructor del padre
			# Representantes
			$this->cod_madre = null;
			$this->cod_padre = null;
			# Estudiante y medidas antropométricas
			$this->cedEscolar = null;
			$this->estatura = null;
			$this->peso = null;
			$this->camisa = null;
			$this->pantalon = null;
			$this->calzado = null;
			# lugar de nacimiento
			$this->objLugarNac = new cls_lugarNacimiento();
			$this->parrNac = null;
			$this->lugarNac = null;
			# Antecedentes pre y pos natales
			$this->embarazo = null;
			$this->parto = null;
			$this->mad_neu = null;
			$this->alm = null;
			$this->sno = null;
			$this->dent = null;
			$this->edadp = null;
			$this->lenguaje = null;
			$this->cEsfin = null;
			$this->observacion = null;
			# datos socio-Económicos
			$this->tipo_vnda = null;
			$this->cond_vnda = null;
			$this->cond_infra_vnda = null;
			$this->num_hab = null;
			$this->num_prnsH = null;
			$this->num_prnsT = null;
			$this->num_Herm = null;
			$this->num_HermEsc = null;
			$this->ingreso_fam = null;
			$this->beca = null;
			$this->s_canaima = null;
			# Datos de Salud
			$this->enfermedadesP = null; 
			$this->enfermedadesAE = null; 
			$this->alergico = null; 
			$this->vacunas = null; 
			$this->discapacidad = null;
			# -
			$this->estatus = null;
			$this->fecha_ingreso = null;
		}

		function set_cedEsc($tipo_doc,$cedEsc){ # pasa valor a la cedEscolar
			$this->tipo_documento = $tipo_doc;
			$this->cedula = $cedEsc;
			$this->cedEscolar = $tipo_doc.'-'.$cedEsc;
		}

		# Estudiante, lugar de nacimiento y medidas antropométricas.
		function set_DatosEst($cMadre,$cPadre,$tipo_doc,$ced,$cedEsc,$nom1,$nom2,$ape1,$ape2,$sexo,$nac,$fnac,$e,$p,$c,$pl,$z,$parrNac,$lnac){ # pasa valor a las propiedades
			$this->cod_madre = $cMadre;
			$this->cod_padre = $cPadre;
			$this->tipo_documento = $tipo_doc;
			$this->cedula = trim($ced);
			$this->cedEscolar = trim($cedEsc);
			$this->nom1 = mb_strtoupper($this->limpiarCadena($nom1)); # limpia cadenas en blanco, y todo mayuscula
			$this->nom2 = mb_strtoupper($this->limpiarCadena($nom2));
			$this->ape1 = mb_strtoupper($this->limpiarCadena($ape1));
			$this->ape2 = mb_strtoupper($this->limpiarCadena($ape2));
			$this->sexo = $sexo;
			$this->nac = $nac;
			$this->fnac = $fnac;
			$this->estatura = trim($e);
			$this->peso = trim($p);
			$this->camisa = mb_strtoupper(trim($c));
			$this->pantalon = mb_strtoupper(trim($pl));
			$this->calzado = trim($z);
			# lugar de nacimiento
			$this->parrNac = $parrNac;
			$this->lugarNac = $lnac;
		}

		function set_Estatus($codEst, $estatus){
			$this->codPer = $codEst;
			$this->estatus = $estatus;
		}

		# Antecedentes pre y post natales
		function setAntecedentesPP($a,$b,$c,$d,$e,$f,$g,$h,$i,$j){
			$this->embarazo = ucfirst($this->limpiarCadena($a)); 
			$this->parto = $b; 
			$this->mad_neu = ucfirst($this->limpiarCadena($c)); 
			$this->alm = ucfirst($this->limpiarCadena($d)); 
			$this->sno = ucfirst($this->limpiarCadena($e)); 
			$this->dent = ucfirst($this->limpiarCadena($f)); 
			$this->edadp = ucfirst($this->limpiarCadena($g)); 
			$this->lenguaje = ucfirst($this->limpiarCadena($h)); 
			$this->cEsfin = $i; 
			$this->observacion = ucfirst($this->limpiarCadena($j)); 
		}
		# Datos socieconómicos
		function set_DatosSoc($tipoV,$condV,$condI,$numH,$numP,$numPT,$numHer,$numHerE,$Ing,$B,$C){
			$this->tipo_vnda = $tipoV;
			$this->cond_vnda = $condV;
			$this->cond_infra_vnda = $condI;
			$this->num_hab = $numH;
			$this->num_prnsH = $numP;
			$this->num_prnsT = $numPT;
			$this->num_Herm = $numHer;
			$this->num_HermEsc = $numHerE;
			$this->ingreso_fam = trim($Ing);
			$this->beca = $B;
			$this->s_canaima = trim($C);
		}
		# Datos de Salud
		function setDatosSalud($a,$b,$c,$d,$e){
			$this->enfermedadesP = $a; 
			$this->enfermedadesAE = ucfirst($this->limpiarCadena($b)); 
			$this->alergico = ucfirst($this->limpiarCadena($c)); 
			$this->vacunas = $d; 
			$this->discapacidad = $e;
		}
	
		// ESTUDIANTE
		function registrar_estudiante(){ # registra un nuevo estudiante
			$listo = false;
			$this->empezar_op();
			$this->registrar_persona(); # registra en la tabla persona

			if( $this->f_affectrows() ){ 
				$this->set_codigoPersona( $this->ultimo_id() ); # pasa el codigo a la clase persona
				$codLugarNac = $this->objLugarNac->getCodigo($this->parrNac,$this->lugarNac); # se obtiene el codigo de lugar de nacimiento
				$f_ing = date('Y-m-d');
				$sql = "INSERT INTO estudiante (cod_per,ced_esc,cod_madre,cod_padre,lugar_nac,estatura,peso,camisa,pantalon,calzado,antp_embarazo,antp_parto,m_neuromotriz,antp_alimentacion,antp_sueno,antp_dentincion,antp_eppasos,antp_lenguaje,antp_esfinteres,observacion,alergico,enf_afeccion_aesp,discapacidad,fecha_ingreso)
				VALUES ('$this->codPer','$this->cedEscolar','$this->cod_madre','$this->cod_padre','$codLugarNac','$this->estatura','$this->peso','$this->camisa','$this->pantalon','$this->calzado','$this->embarazo','$this->parto','$this->mad_neu','$this->alm','$this->sno','$this->dent','$this->edadp','$this->lenguaje','$this->cEsfin','$this->observacion',
				'$this->alergico','$this->enfermedadesAE','$this->discapacidad','$f_ing')";
				#echo $sql;
				$this->query($sql); # registra en la tabla estudiante
				$this->subir_foto();
			}
			if( $this->f_affectrows() ){
				$this->registrar_DatosSoc();  # registra en la tabla datos socio-economicos
			}
			if( $this->f_affectrows() ){
				$this->registrar_enfermedades(); # registra en la tabla estudiante_enfermedad
				$this->registrar_vacunas(); # registra en la tabla estudiante_vacuna
				$listo = true;
			}
			if( $listo == true ){
				$this->finalizar_op();
				return true;
			}else{
				$this->deshacer_op();
			}
		}

		function modificar_estudiante(){ 
			$codLugarNac = $this->objLugarNac->getCodigo($this->parrNac,$this->lugarNac);
			$sql = "UPDATE estudiante SET 
			ced_esc = '$this->cedEscolar',
			cod_madre = '$this->cod_madre',
			cod_padre = '$this->cod_padre',
			lugar_nac='$codLugarNac',
			estatura='$this->estatura',
			peso='$this->peso',
			camisa='$this->camisa',
			pantalon='$this->pantalon',
			calzado='$this->calzado',
			antp_embarazo = '$this->embarazo',
			antp_parto = '$this->parto',
			m_neuromotriz = '$this->mad_neu',
			antp_alimentacion = '$this->alm',
			antp_sueno = '$this->sno',
			antp_dentincion = '$this->dent',
			antp_eppasos = '$this->edadp',
			antp_lenguaje = '$this->lenguaje',
			antp_esfinteres = '$this->cEsfin',
			observacion = '$this->observacion',
			alergico = '$this->alergico',
			enf_afeccion_aesp = '$this->enfermedadesAE',
			discapacidad = '$this->discapacidad'
			WHERE cod_per='$this->codPer'";

			$this->modificar_persona(); # modifica en la tabla persoa
			$this->subir_foto();
			$this->query($sql); # modifica en la tabla estudiante
			$this->modificar_DatosSoc(); # modifica en la tabla datos_socieconómicos
			$this->modificar_enfermedades();
			$this->modificar_vacunas();
		}

		// DATOS SOCIO-ECONOMICOS
		function registrar_DatosSoc(){
			$sql = "INSERT INTO datos_socioeconomicos (cod_est,tipo_vnda,condicion_vnda,infraestructura_vnda,num_habitaciones,num_personas,num_personas_trbj,num_hermanos,num_hermanos_esc,ingreso_familiar,tiene_beca,serial_canaima) 
			VALUES ('$this->codPer','$this->tipo_vnda','$this->cond_vnda','$this->cond_infra_vnda','$this->num_hab','$this->num_prnsH','$this->num_prnsT','$this->num_Herm','$this->num_HermEsc','$this->ingreso_fam','$this->beca','$this->s_canaima')";
			$this->query($sql);
		}	
		function modificar_DatosSoc(){
			$sql = "UPDATE datos_socioeconomicos SET
			cod_est = '$this->codPer',
			tipo_vnda = '$this->tipo_vnda',
			condicion_vnda = '$this->cond_vnda',
			infraestructura_vnda = '$this->cond_infra_vnda',
			num_habitaciones = $this->num_hab,
			num_personas = '$this->num_prnsH',
			num_personas_trbj = '$this->num_prnsT',
			num_hermanos = '$this->num_Herm',
			num_hermanos_esc = '$this->num_HermEsc',
			ingreso_familiar = '$this->ingreso_fam',
			tiene_beca = '$this->beca',
			serial_canaima = '$this->s_canaima' 
			WHERE cod_est = '$this->codPer'";
			$this->query($sql);
		}

		# Modifica el estatus del estudiante
		function cambiar_estatus(){
			$sql = "UPDATE estudiante SET estatus = '$this->estatus' WHERE cod_per = '$this->codPer'";
			$this->query($sql);
		}
		// DOCUMENTOS PRESENTADOS
		function registrar_documentosP($d1,$d2,$d3,$d4,$d5,$d6,$d7,$d8,$d9,$d10,$otros){ 
			$sql = "INSERT INTO documentos (cod_est,ficha_vacunas,p_nacimiento,copia_ciRep,copia_ciEst,fotos_est,foto_rep,constancia_prom,inf_desc,boleta_retiro,inf_medico,otros)
			VALUES ('$this->codPer','$d1','$d2','$d3','$d4','$d5','$d6','$d7','$d8','$d9','$d10','$otros')";
			$this->query($sql);
		}
		function modificar_documentos($d1,$d2,$d3,$d4,$d5,$d6,$d7,$d8,$d9,$d10,$otros){
			$sql = "UPDATE documentos SET ficha_vacunas='$d1', p_nacimiento='$d2', copia_ciRep='$d3', copia_ciEst='$d4', fotos_est='$d5', foto_rep='$d6',
			constancia_prom='$d7', inf_desc='$d8', boleta_retiro='$d9', inf_medico='$d10', otros='$otros'
			WHERE cod_est = '$this->codPer'";
			echo $sql;
			$this->query($sql);
		}
		function consultar_documentosP(){ # consulta los documentos presentados de un estudiante
			$sql = "SELECT * FROM documentos WHERE cod_est = '$this->codPer'";
			if( $rs = $this->query($sql) ){
				return $this->f_array($rs);
			}
		}	
		
		// CONSULTAS DE ESTUIANTE
		# devuelve la consulta en un arreglo
		function consultar_estudiante(){ # consulta por cedula escolar
			$sql = "SELECT * FROM estudiante 
			INNER JOIN persona ON estudiante.cod_per = persona.cod_per 
			INNER JOIN lugar_nacimiento ON estudiante.lugar_nac = lugar_nacimiento.cod_lugar_nac
			INNER JOIN parroquia ON lugar_nacimiento.cod_parr = parroquia.cod_parr
			INNER JOIN municipio ON parroquia.municipio = municipio.cod_mun
			INNER JOIN estado ON municipio.estado = estado.cod_edo
			INNER JOIN paises ON estado.pais = paises.cod_pais
			INNER JOIN datos_socioeconomicos ON datos_socioeconomicos.cod_est = estudiante.cod_per
			WHERE ced_esc = '$this->cedEscolar' OR tipo_documento = '$this->tipo_documento' AND cedula = '$this->cedula'";
			
			#echo $sql;
			$rs = $this->query($sql);
			if( $this->f_numrows($rs) ){
				return $this->f_array($rs);
			}
		}
		#consulta por codigo de persona
		function consultar_estudiante2(){ 
			$sql = "SELECT * FROM estudiante 
			INNER JOIN persona ON estudiante.cod_per = persona.cod_per 
			INNER JOIN lugar_nacimiento ON estudiante.lugar_nac = lugar_nacimiento.cod_lugar_nac
			INNER JOIN municipio ON lugar_nacimiento.mun_nac = municipio.cod_mun
			INNER JOIN estado ON municipio.estado = estado.cod_edo
			INNER JOIN paises ON estado.pais = paises.cod_pais
			INNER JOIN datos_socioeconomicos ON datos_socioeconomicos.cod_est = estudiante.cod_per
			WHERE estudiante.cod_per ='$this->codPer'";
			return $this->f_array( $this->query($sql) );
		}

		# devuelve el codigo de persoana del estudiante
		function consultarCedulaEscolar(){ 
			$sql = "SELECT ced_esc,cod_per FROM estudiante WHERE ced_esc = '$this->cedEscolar'";
			$rs = $this->query($sql);
			if( $rs ){
				return $this->f_array($rs);
			}
		}
		
		# ESTUDIANTE ENFERMEDAD
		function registrar_enfermedades(){
			# Ej: enfermedades = '1,5,10,11,12' # codigos de las enfermedades
			$enfermedades = substr($this->enfermedadesP, 1); # eliminamos la primera coma
			$enfermedades = explode(',', $enfermedades); # convertimos el strin en un array
			for($i=0; $i<count($enfermedades);$i++){ # recorremos el array para ir agregando 1 por 1
				$this->agregar_enfermedad($enfermedades[$i]);
			}
		}
		function modificar_enfermedades(){
			$rsEnf = $this->consultar_enfermedades(); # consulta las enfermedades que posee
			$enfermedades = substr($this->enfermedadesP, 1); 
			$enfermedades = explode(',',$enfermedades);
			$enfBD = [];
			for($i=0;$i<count($rsEnf);$i++){
				array_push($enfBD, $rsEnf[$i]['cod_enf']);
			}
			# Para agregar
			for ($i=0;$i<count($enfermedades);$i++) { 
				if( !in_array($enfermedades[$i], $enfBD)){
					$this->agregar_enfermedad($enfermedades[$i]);
				}
			}
			# Para eliminar
			for ($i=0; $i<count($enfBD); $i++) { 
				if( !in_array($enfBD[$i], $enfermedades)){
					$this->eliminar_enfermedad($enfBD[$i]);
				}
			}
		}
		function agregar_enfermedad($codEnf){
			$sql = "INSERT INTO enf_padecida (cod_est,cod_enf) VALUES ('$this->codPer','$codEnf')";
			$this->query($sql);
		}
		function eliminar_enfermedad($codEnf){
			$sql = "DELETE FROM enf_padecida WHERE cod_est = '$this->codPer' AND cod_enf = '$codEnf'";
			$this->query($sql);
		}
		function consultar_enfermedades(){
			$lista = array(); $cont=0;
			$sql = "SELECT * FROM enf_padecida INNER JOIN enfermedad ON enf_padecida.cod_enf = enfermedad.cod_enf WHERE cod_est = '$this->codPer'";
			$rs = $this->query($sql);
			while ($fila = $this->f_array($rs)) {
				$lista[$cont]['cod_est'] = $fila['cod_est'];
				$lista[$cont]['cod_enf'] = $fila['cod_enf'];
				$lista[$cont]['nom_enf'] = $fila['nom_enf'];
				$cont++;
			}
			return $lista;
		}
		# ESTUDIANTE VACUNA
		function registrar_vacunas(){
			# Ej: vacunas = '1,5,10,11,12' # codigos de vacunas
			$vacunas = substr($this->vacunas, 1); # eliminamos la primera coma
			$vacunas = explode(',',$vacunas); # convertimos el strin en un array
			for($i=0; $i<count($vacunas);$i++){ # recorremos el array para ir agregando 1 por 1
				$this->agregar_vacuna($vacunas[$i]);
			}
		}
		function modificar_vacunas(){
			$rsVcna = $this->consultar_vacunas(); # consulta las vacunas recibidas
			$vacunas = substr($this->vacunas, 1); 
			$vacunas = explode(',',$vacunas);
			$vcnaBD = [];
			for($i=0;$i<count($rsVcna);$i++){
				array_push($vcnaBD, $rsVcna[$i]['cod_vcna']);
			}
			# Para agregar
			for ($i=0;$i<count($vacunas);$i++) { 
				if( !in_array($vacunas[$i], $vcnaBD)){
					$this->agregar_vacuna($vacunas[$i]);
				}
			}
			# Para eliminar
			for ($i=0; $i<count($vcnaBD); $i++) { 
				if( !in_array($vcnaBD[$i], $vacunas)){
					$this->eliminar_vacuna($vcnaBD[$i]);
				}
			}
		}
		function agregar_vacuna($codVcna){
			$sql = "INSERT INTO est_vacuna (cod_est,cod_vcna) VALUES ('$this->codPer','$codVcna')";
			$this->query($sql);
		}
		function eliminar_vacuna($codVcna){
			$sql = "DELETE FROM est_vacuna WHERE cod_est = '$this->codPer' AND cod_vcna = '$codVcna'";
			$this->query($sql);
		}
		function consultar_vacunas(){
			$lista = array(); $cont=0;
			$sql = "SELECT * FROM est_vacuna INNER JOIN vacuna ON est_vacuna.cod_vcna = vacuna.cod_vcna WHERE cod_est = '$this->codPer'";
			$rs = $this->query($sql);
			while ($fila = $this->f_array($rs)) {
				$lista[$cont]['cod_est'] = $fila['cod_est'];
				$lista[$cont]['cod_vcna'] = $fila['cod_vcna'];
				$lista[$cont]['nom_vcna'] = $fila['nom_vcna'];
 				$cont++;
			}
			return $lista;
		}
	}
?>