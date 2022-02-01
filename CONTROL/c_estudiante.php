<?php
# Seguridad >>>>>>>
include_once('seguridadDinamica.php');

if( isset($_GET['modo']) ){
	if( $_GET['modo'] == 'nuevo' ){
		$rsMetodo = consultarMetodos('Inscripción Nuevo Ing.');
	}
	else if( $_GET['modo'] == 'regular' ){
		$rsMetodo = consultarMetodos('Inscripción Regular');
	}
}
else{
	$rsMetodo = consultarMetodos('Datos Personales');
}

# Para formulario estudiante / Para formulario inscripción
$sI = '1';
$sM = '1';
$sC = '1';
$sE = '1';

# Es diferente al administrador central
if( $_SESSION['vsn_nivel'] != 1 ){ 
	$sI = $rsMetodo['inc'];
	$sM = $rsMetodo['modf'];
	$sC = $rsMetodo['cons'];
	$sE = $rsMetodo['elm'];
}
# Seguridad <<<<<<

# ----------------  AÑO ESCOLAR  -----------------
include_once('../MODELO/m_a_escolar.php'); # modelo año escolar
$cod_AESC = ''; # año escolar actual activo
$per_AESC = '';
$sta_AESC = '';
$rsConf = ''; # fechas de configuracion
# apertura para inscripciones
$fechaApertura = false;

# Consulta el año escolar
$objAesc = new cls_a_escolar();
if( $rs = $objAesc->aesc_actual() ){
	$cod_AESC = $rs['cod_periodo'];
	$per_AESC = $rs['periodo'];
	$sta_AESC = $rs['estatus'];
	$objAesc->set_codigo($cod_AESC);
	$rsConf = $objAesc->consultar_configuracion();
}
else{
	$sta_AESC = 'C'; # no se encontró ningún registro
}

# ----------------   ESTUDIANTE  -------------------
include_once('../MODELO/m_representante.php');
$objRep = new cls_representante();
include_once('c_inscripcion.php'); # controlador
include_once('../MODELO/m_estudiante.php');
include_once('../MODELO/m_lugarNacimiento.php');
$objEst = new cls_estudiante();
$objLugarNac = new cls_lugarNacimiento();

# >>>>>>   VARIABLES
# Representantes
$cod_madre = ''; $tdoc_madre = ''; $ced_madre = ''; $nom_madre = '';
$cod_padre = ''; $tdoc_padre = ''; $ced_padre = ''; $nom_padre = '';

# Estudiante
$url_foto = '../IMG/avatar.jpg';
$codPer = ''; # codigo de persona del estudiante
$tdoc_est = ''; # tipo de documento de identidad
$ced_est = ''; # cedula estudiante
$ced_estH = ''; # cedula del estudiante oculta
$cedEsc = 'V-1'; # cedula escolar
$cedEscH = ''; # cedula escolar oculta ej: CE-11925935642
$ced_estAlt = '';
$nom1 = '';
$nom2 = '';
$ape1 = '';
$ape2 = '';
$fechaActual = date('Y-m-d'); # fecha actual del servidor
$edad = 0;
$fnac = ''; # fecha de nacimiento del estudiante
$sexo = '';
$nac = ''; # nacionalidad
$tlfn = '';
# Lugar de Nacimiento
$paisNac = '232'; # por defecto selecciona Venezuela
$edoNac = '17'; 
$munNac = '';
$parrNac = ''; 
$lugarNac = '';
$ordenNac = '';
$estatus = ''; # 1 = nuevo; 2 = regular; 3 = retirado; 4 = graduado;
$txtEstatus = '';
# medidas antropometricas
$estatura = '';
$peso = '';
$camisa = '';
$pantalon = '';
$calzado = '';

// DIRECCION DE DOMICILIO
$edoDom = '17'; # estado portuguesa 
$munDom = '283';
$parrDom = '';
$sector = '';
$calle = '';
$nroCasa = '';

// ANTECEDENES PRE Y POST NATALES
$embarazo = '';
$parto = '';
$mad_neu = '';
$alm = '';
$sno = '';
$dent = '';
$edadp = '';
$lenguaje = '';
$cEsfin = '1';
$observacion = '';

// DATOS SOCIECONOMICOS
$tipo_vnda = '';
$cond_vnda = '';
$cond_infra_vnda = '';
$num_hab = '';
$num_prnsH = '';
$num_prnsT = '';
$num_Herm = '';
$num_HermEsc = '';
$ingreso_fam = '';
$beca = 0;
$s_canaima = '';

// DATOS DE SALUD
$enfermedadesP = ''; # string con los codigos
$enfermedadesAE = ''; 
$alergico = ''; 
$vacunas = ''; # string con los codigos
$discapacidad = '';
$divItemsBox_enf = '';
$divItemsBox_vcna = '';

// DOCUMENTOS PRESENTADOS
$d1 = '0'; $d2 = '0'; $d3 = '0'; $d4 = '0'; $d5 = '0'; $d6 = '0'; $d7 = '0'; $d8 = '0'; $d9 = '0'; $d10 = '0';
$otros_doc = '';

# Madre y Padre
$btn_addPadre = '';
$btn_addMadre = '';

// EGRESO 
$fechaEgr = '';
$tipoEgr = '';
$descEgr = '';

$modoINSC = '';

# Rendimiento escolar
$rsPA1 = '';
$rsPA2 = '';
$rsPA3 = '';
$rsInd1 = ''; 
$rsInd2 = '';
$rsInd3 = '';
$rsProm1 = '';
$rsProm2 = '';
$rsProm3 = '';

$URL_est = ''; # captura el dato de la variable Estudiante de la url actual
if( isset($_GET['Estudiante']) ){
	# >>> Seguridad Dinamica
	include_once('seguridadDinamica.php');
	$rsMetodos = consultarMetodos('Datos Personales');
	# <<<
	
	$URL_est = $_GET['Estudiante'];

	if( $URL_est!='visualizar' && $URL_est != 'registrar' && $URL_est != 'representante' && $URL_est != 'consultar' ){
		back();
	}

	# modo de la inscripcion
	if( isset($_GET['modo']) ){
		$modoINSC = $_GET['modo'];

		if( $modoINSC == 'nuevo' ){
			if( fecha_aperturada($modoINSC) ){
				$fechaApertura = true;
			}
		}

		else if( $modoINSC == 'regular' ){
			if( fecha_aperturada($modoINSC) ){
				$fechaApertura = true;
			}
		}
	}
	else{
		# no existe modo = Datos Personales (se está realizando una consulta).
		$fechaApertura = true;
	}
	
	# Visulizar(Consultar)
	if( $URL_est == 'visualizar' &&  isset($_GET['cedEscolar']) ){
		# cedEscolar o cedula
		$arrCed = explode('-', $_GET['cedEscolar']);
		$getTP = $arrCed[0]; # tipo de documento
		$getCE = $arrCed[1]; # cedula escolar
		$objEst->set_cedEsc($getTP,$getCE);

		if( $rs = $objEst->consultar_estudiante() ){ # existen datos
			#foto
			if( $rs['foto'] != '' ){
				$url_foto = '../upload/'.$rs['foto'];
			}

			$codPer = $rs['cod_per'];
			$tdoc_est = $rs['tipo_documento'];
			$ced_est = $rs['cedula'];
			$ced_estH = $tdoc_est.'-'.$ced_est;
			$cedEsc = $rs['ced_esc'];
			$cedEscH = $rs['ced_esc'];
			$ordenNac = substr($cedEsc,2,1);
			
			// cedula alterna CI o CE
			if( $ced_est == '' ){
				$ced_estAlt = $cedEsc;
			}
			else{
				$ced_estAlt = $ced_estH;
			}

			$nom1 = $rs['nom1'];
			$nom2 = $rs['nom2'];
			$ape1 = $rs['ape1'];
			$ape2 = $rs['ape2'];
			$fnac = date('Y-m-d', strtotime($rs['fecha_nac']) );
			$sexo = $rs['sexo'];
			$nac = $rs['nacionalidad']; 
			#lugar de nacimiento
			$paisNac = $rs['cod_pais']; # por defecto selecciona Venezuela
			$edoNac = $rs['cod_edo'];
			$munNac = $rs['cod_mun'];
			$parrNac = $rs['cod_parr'];
			$lugarNac = $rs['desc_lugar'];
			$estatus = $rs['estatus'];

			if( $estatus != '' ){
				switch ($estatus) {
					case '1':
						$txtEstatus = '<p class="msj_ok marginT-1"><i class="icon-ok-circled2"></i> Estado del estudiante: <b>ACTIVO</b></p>';
						break;
					
					case '2':
						$txtEstatus = '<p class="msj_ok marginT-1"><i class="icon-ok-circled2"></i> Estado del estudiante: <b>ACTIVO</b></p>';
						break;

					case '3':
						$txtEstatus = '<p class="msj_aviso marginT-1"><i class="icon-ok-circled2"></i> Estado del estudiante: <b>RETIRADO</b></p>';
						break;

					case '4':
						$txtEstatus = '<p class="msj_info marginT-1"><i class="icon-ok-circled2"></i> Estado del estudiante: <b>GRADUADO</b></p>';
						break;
				}
			}

			#domicilio
			$objEst->set_codigoPersona($codPer); # pasa el codigo de la persona a la clase persona
			$tlfn = $objEst->consultar_telefono('M'); # telefono del estudiante
			$rsDir = $objEst->consultarDirecciones(); 
			$edoDom = $rsDir[0]['codEdo'];
			$munDom = $rsDir[0]['codMun'];
			$parrDom = $rsDir[0]['codParr'];
			$sector = $rsDir[0]['sector'];
			$calle = $rsDir[0]['calle'];
			$nroCasa = $rsDir[0]['nro'];
			# Antecedenes Pre y post natales
			$embarazo = $rs['antp_embarazo'];
			$parto = $rs['antp_parto'];
			$mad_neu = $rs['m_neuromotriz'];
			$alm = $rs['antp_alimentacion'];
			$sno = $rs['antp_sueno'];
			$dent = $rs['antp_dentincion'];
			$edadp = $rs['antp_eppasos'];
			$lenguaje = $rs['antp_lenguaje'];
			$cEsfin = $rs['antp_esfinteres'];
			$observacion = $rs['observacion'];
			# Datos antropométricos
			$estatura = $rs['estatura'];
			$peso = $rs['peso'];
			$camisa = $rs['camisa'];
			$pantalon = $rs['pantalon'];
			$calzado = $rs['calzado'];
			# Datos socieconómicos
			$tipo_vnda = $rs['tipo_vnda'];
			$cond_vnda = $rs['condicion_vnda'];
			$cond_infra_vnda = $rs['infraestructura_vnda'];
			$num_hab = $rs['num_habitaciones'];
			$num_prnsH = $rs['num_personas'];
			$num_prnsT = $rs['num_personas_trbj'];
			$num_Herm = $rs['num_hermanos'];
			$num_HermEsc = $rs['num_hermanos_esc'];
			$ingreso_fam = $rs['ingreso_familiar'];
			$beca = $rs['tiene_beca'];
			$s_canaima = $rs['serial_canaima'];

			# Codigo de persona, Cedula Nombre y Apellido de los representantes
			$objRep->set_codigoPersona($rs['cod_madre']);
			$rsMadre = $objRep->consultar_representante2();
			$objRep->set_codigoPersona($rs['cod_padre']);
			$rsPadre = $objRep->consultar_representante2();
			$cod_madre = $rsMadre['cod_rep']; $tdoc_madre = $rsMadre['tipo_documento']; $ced_madre = $rsMadre['cedula']; $nom_madre = $rsMadre['nombre'];
			$cod_padre = $rsPadre['cod_rep']; $tdoc_padre = $rsPadre['tipo_documento']; $ced_padre = $rsPadre['cedula']; $nom_padre = $rsPadre['nombre'];
			
			# Documentos presentados
			$rsDoc = $objEst->consultar_documentosP();
			$d1 = $rsDoc['ficha_vacunas'];
			$d2 = $rsDoc['p_nacimiento'];
			$d3 = $rsDoc['copia_ciRep'];
			$d4 = $rsDoc['copia_ciEst'];
			$d5 = $rsDoc['fotos_est'];
			$d6 = $rsDoc['foto_rep'];
			$d7 = $rsDoc['constancia_prom'];
			$d8 = $rsDoc['inf_desc'];
			$d9 = $rsDoc['boleta_retiro'];
			$d10 = $rsDoc['inf_medico'];
			$otros_doc = $rsDoc['otros'];

			# Datos de salud
			$enfermedadesAE = $rs['enf_afeccion_aesp']; 
			$alergico = $rs['alergico']; 
			$discapacidad = $rs['discapacidad'];
			$rsEnf = $objEst->consultar_enfermedades();
			$rsVcna = $objEst->consultar_vacunas();
			# agrega valor a los input Hidden con los codigos, e imprime los inlineBox con los nombres
			for ($i=0; $i<count($rsEnf);$i++) { 
				$enfermedadesP = $enfermedadesP.','.$rsEnf[$i]['cod_enf'];
				$divItemsBox_enf = $divItemsBox_enf.'<div class="itemBox_inline" onclick="eliminar_enf('.$rsEnf[$i]['cod_enf'].',this)">'.$rsEnf[$i]['nom_enf'].'<i class="icon-cancel-circled2"></i></div>';
			}
			for ($i=0; $i<count($rsVcna);$i++) { 
				$vacunas = $vacunas.','.$rsVcna[$i]['cod_vcna'];
				$divItemsBox_vcna = $divItemsBox_vcna.'<div class="itemBox_inline" onclick="eliminar_vacuna('.$rsVcna[$i]['cod_vcna'].',this)">'.$rsVcna[$i]['nom_vcna'].'<i class="icon-cancel-circled2"></i></div>';
			}
			
			# >>>>>  Inscripción, ver: c_inscripcion.php
			consultar_inscripcion($codPer,$cod_AESC);
			$rsNotas = consultar_notas($codInsc);
		}
		else{
			if( isset($_GET['modo']) && $_GET['modo'] == 'regular' ){
				header('location: ?Estudiante=consultar&modo=regular&consulta=false');
			}
			else{
				header('location: ?Estudiante=consultar&consulta=false'); # no se encontraron datos
			}
		}
	}
}

# >>>>> POST 
if( isset($_POST['opeEst']) ){
	# Representantes
	$cod_madre = $_POST['cod_madre'];
	$cod_padre = $_POST['cod_padre'];

	if( $cod_madre == '' ){
		$cod_madre = '2';
	}
	if( $cod_padre == '' ){
		$cod_padre = '2';
	}
	# Estudiante
	$codPer = $_POST['codPer']; 
	$tdoc_est = $_POST['tdoc_est'];
	$ced_est = $_POST['ced_est'];
	$cedEsc = $_POST['cedEsc'];
	$cedEscH = $_POST['cedEscH'];
	$ced_estAlt = $_POST['ced_estAlt'];
	$nom1 = $_POST['nom1'];
	$nom2 = $_POST['nom2'];
	$ape1 = $_POST['ape1'];
	$ape2 = $_POST['ape2'];
	$fnac = $_POST['fnac'];
	$sexo = $_POST['sex'];
	$nac = $_POST['nac'];
	# Lugar de nacimiento
	$parrNac = $_POST['parrNac'];
	$lnac = $_POST['lugarNac'];
	# Direccion de domicilio
	$parrDom = $_POST['parrDom'];
	$sector = $_POST['sector'];
	$calle = $_POST['calle'];
	$nroCasa = $_POST['nroCasa'];
	# Datos antropométricos
	$e = $_POST['estatura'];
	$p = $_POST['peso'];
	$c = $_POST['camisa'];
	$pl = $_POST['pantalon'];
	$z = $_POST['calzado'];
	# Antecentes pre y post natales
	$embarazo = $_POST['embarazo'];
	$parto = $_POST['parto'];
	$mad_neu = $_POST['mad_neu'];
	$alm = $_POST['alimentacion'];
	$sno = $_POST['sueno'];
	$dent = $_POST['dentincion'];
	$edadp = $_POST['p_pasos'];
	$lenguaje = $_POST['lenguaje'];
	$cEsfin = $_POST['c_esfinteres'];
	$observacion = $_POST['obs'];
	# Datos socieconomicos
	$tipoV = $_POST['tipoVnda'];
	$condV = $_POST['condVnda'];
	$condI = $_POST['condInfraVnda'];
	$numH = $_POST['num_habitaciones'];
	$numP = $_POST['num_personas'];
	$numPT = $_POST['num_personasT'];
	$numHer = $_POST['num_hermanos'];
	$numHerE = $_POST['num_hermanosEsc'];
	$Ing = $_POST['ing_familiar'];
	$B = $_POST['beca'];
	$C = $_POST['canaima'];
	# Datos de salud
	$enfermedadesP = $_POST['enf_pd']; 
	$enfermedadesAE = $_POST['enfAAE']; 
	$alergico = $_POST['alergias']; 
	$vacunas = $_POST['vacunas']; 
	$discapacidad = $_POST['discapacidad'];
	# Documentos presentatos
	if( isset($_POST['d1']) && $_POST['d1'] == 'on' ){
		$d1 = '1';
	}
	if( isset($_POST['d2']) && $_POST['d2'] == 'on' ){
		$d2 = '1';
	}
	if( isset($_POST['d3']) && $_POST['d3'] == 'on'){
		$d3 = '1';
	}
	if( isset($_POST['d4']) && $_POST['d4'] == 'on' ){
		$d4 = '1';
	}
	if( isset($_POST['d5']) && $_POST['d5'] == 'on' ){
		$d5 = '1';
	}
	if( isset($_POST['d6']) && $_POST['d6'] == 'on'){
		$d6 = '1';
	}
	if( isset($_POST['d7']) && $_POST['d7'] == 'on' ){
		$d7 = '1';
	}
	if( isset($_POST['d8']) && $_POST['d8'] == 'on' ){
		$d8 = '1';
	}
	if( isset($_POST['d9']) && $_POST['d9'] == 'on'){
		$d9 = '1';
	}
	if( isset($_POST['d10']) && $_POST['d10'] == 'on'){
		$d10 = '1';
	}
	$otros_doc = $_POST['otros_documentos'];
	
	# >>>>>>>>>>>    INICIA LA OPERACION  
	$objEst->set_foto($_FILES['foto']);
	$objEst->set_DatosEst($cod_madre,$cod_padre,$tdoc_est,$ced_est,$cedEsc,$nom1,$nom2,$ape1,$ape2,$sexo,$nac,$fnac,$e,$p,$c,$pl,$z,$parrNac,$lnac);
	$objEst->setAntecedentesPP($embarazo,$parto,$mad_neu,$alm,$sno,$dent,$edadp,$lenguaje,$cEsfin,$observacion);
	$objEst->set_DatosSoc($tipoV,$condV,$condI,$numH,$numP,$numPT,$numHer,$numHerE,$Ing,$B,$C);
	$objEst->setDatosSalud($enfermedadesP,$enfermedadesAE,$alergico,$vacunas,$discapacidad);
	
	switch ( $_POST['opeEst'] ) {
		case 'reg':
			$modo=$_POST['modoINSC'];
			$registrar = false;
			if( $ced_est != '' ){ # posee cedula
				if( $objEst->consultar_persona() ){ # Existe ya la cédula
					header('location: ../VISTA/?Estudiante=registrar&modo='.$modo.'&error=cedula'); # ya existe la cédula
				}
				else{
					$registrar = true;
				}
			}
			else{ # Cédula Escolar 
				if( $objEst->consultarCedulaEscolar() ){ 
					header('location: ../VISTA/?Estudiante=registrar&modo='.$modo.'&error=cedulaEsc'); # cedula escolar ya existe
				}
				else{
					$registrar = true;
				}
			}
			if( $registrar == true ){
				if( $objEst->registrar_estudiante() ){
					$objEst->agregar_direccion($parrDom,$sector,$calle,$nroCasa,'D');
					$objEst->agregar_telefono($_POST['tlfn'],'M');
					$objEst->registrar_documentosP($d1,$d2,$d3,$d4,$d5,$d6,$d7,$d8,$d9,$d10,$otros_doc);
					header('location: ../VISTA/?Estudiante=visualizar&cedEscolar='.$cedEsc.'&reg=true&modo='.$modo.'&vista=2');
				}	
			}
			break;
		
		case 'mod':
			# Verificación de cédula
			$modificar = false;
			$modo='';
			if( $_POST['modoINSC'] != '' ){
				$modo='&modo='.$_POST['modoINSC'];
			}
			if( $ced_est != '' ){ # tiene cédula
				if( $rs = $objEst->consultar_persona() ){ # Existe la persona
					if( $rs['cod_per'] == $codPer ){ # Es la misma persona puede modificiar
						$modificar = true;
					}
					else{
						header('location: ../VISTA/?Estudiante=visualizar&cedEscolar='.$cedEscH.'&error=cedula'.$modo);
					}
				}
				else{
					$modificar = true; # No existe la persona con ésta cédula.
				}	
			}
			else{ # Cédula escolar
				if( $rs = $objEst->consultarCedulaEscolar() ){ # Existe el estudiante
					if( $rs['cod_per'] == $codPer ){
						$modificar = true;
					}
					else{
						header('location: ../VISTA/?Estudiante=visualizar&cedEscolar='.$cedEscH.'&error=cedulaEsc'.$modo);
					}
				}
				else{
					# no existe un estudiante con ésta cédula escolar
					$modificar = true;
				}
			}

			if( $modificar == true ){
				$objEst->set_codigoPersona($codPer);
				$objEst->modificar_estudiante();
				$objEst->modificar_direccion($parrDom,$sector,$calle,$nroCasa,'D');
				$objEst->modificar_telefono($_POST['tlfn'],'M');
				$objEst->modificar_documentos($d1,$d2,$d3,$d4,$d5,$d6,$d7,$d8,$d9,$d10,$otros_doc);
				header('location: ../VISTA/?Estudiante=visualizar&cedEscolar='.$ced_estAlt.'&mod=true'.$modo);
			}
			break;
	}
}

# AJAX >>>>>>>>>>
else if( isset($_POST['buscar_representante'])){ # con Ajax ver: buscar_representante() en f_estudiante.js
	include_once('../MODELO/m_representante.php');
	$obj = new cls_representante();
	$obj->set_identidad($_POST['tipo_doc'],$_POST['ced_rep']);
	if( $rs = $obj->consultar_representante2() ){
		echo $rs['cod_rep'].'%'.$rs['tipo_documento'].'%'.$rs['cedula'].'%'.$rs['nombre'];
	}
	else{
		echo 'false';
	}
}
else if( isset($_POST['existencia_cedula']) ){
	#echo $_POST['cedula'].'/'.$_POST['modo'].'/'.$_POST['codPer'];		
	$objEst->set_cedEsc(substr($_POST['cedula'], 0, 1), substr($_POST['cedula'], 2));	
	if( $_POST['modo'] == 'nuevo' ){ # nuevo ingreso
		
		# tipo 1 = cedula normal, tipo 2 = cedula escular

		switch ($_POST['tipo']) {
			case '1':	
				if( $rs = $objEst->consultar_persona() ){
					if( $_POST['codPer'] == '' ){
						echo '1'; # ya existe
					}
					else if( $_POST['codPer'] == $rs['cod_per'] ){
						echo '0';
					}
					else{
						echo '1';
					}
				}
				else{
					echo '0';
				}
				break;
			
			case '2':
				if( $rs = $objEst->consultarCedulaEscolar() ){
					if( $_POST['codPer'] == '' ){
						echo '1'; # ya existe
					}
					else if( $_POST['codPer'] == $rs['cod_per'] ){
						echo '0';
					}
					else{
						echo '1';
					}
				}
				else{
					echo '0'; # no existe
				}
				break;
		}
	}
	else{ # regular
		switch ($_POST['tipo']) {
			case '1':	
				if( $rs = $objEst->consultar_persona() ){
					if( $rs['cod_per'] != $_POST['codPer'] ){
						echo '1'; # ya existe
					}
				}
				break;
			
			case '2':
				if( $rs = $objEst->consultarCedulaEscolar() ){
					if( $rs['cod_per'] != $_POST['codPer'] ){
						echo '1'; # ya existe
					}
				}
				break;
		}
	}
}
# <<<<< POST


# OTRAS FUNCIONES 
function back(){
	header('location: ?Estudiante=consultar'); # back
}

function fecha_aperturada($modo){
	global $rsConf;
	if( $rsConf != '' ){
		date_default_timezone_set('America/Caracas');
		// echo date('Y-m-d H:i:s');
		$hoyA = strtotime( date('Y-m-d H:i:s') ); # hora del servidor
		// $restar = strtotime('-30 minute', $hoyA ); # restar media hora para hora oficial de venezuela
		// $hoy = date('Y-m-d H:i:s', $restar); # hora transformada
		// $hoy = strtotime($hoy);
		// echo $hoyA.'<br>';
		// echo '<br>'.$hoy;
		
		switch ($modo) {
			case 'nuevo':
				$desde = strtotime( $rsConf['apertura_insc_n'] );
				$hasta = strtotime( $rsConf['cierre_insc_n'] );
				break;
			
			case 'regular':
				$desde = strtotime( $rsConf['apertura_insc_r'] );
				$hasta = strtotime( $rsConf['cierre_insc_r'] );
				break;
		}
		if( $desde < $hasta && $hoyA > $desde){
			if( $hoyA < $hasta ){
				return true;
			}
		}
	}
}

# listar en combos select
function listar_op_vivienda($x,$v){ 
	include_once('../MODELO/m_vivienda.php');
	$objvnda = new cls_vivienda();

	switch ($x) {
		case 'tipo':
			$rs = $objvnda->listar_tipoVDA();
			break;
		
		case 'cond':
			$rs = $objvnda->listar_condVDA();
			break;

		case 'cond_infra':
			$rs = $objvnda->listar_condInfraVDA();
			break;
	}
	for ($i=0; $i<count($rs);$i++){
		if( $v == $rs[$i]['cod'] ){
			echo '<option value="'.$rs[$i]['cod'].'" selected>'.$rs[$i]['nom'].'</option>';
		}
		else{
			echo '<option value="'.$rs[$i]['cod'].'">'.$rs[$i]['nom'].'</option>';
		}
	}
}
function listar_enfermedadesOp(){
	include_once('../MODELO/m_enfermedad.php');
	$obj = new cls_enfermedad();
	$rs = $obj->listar(); # array 
	for ($i=0;$i<count($rs);$i++) { 
		$cod = $rs[$i]['cod'];
		$nom = $rs[$i]['nom'];
		echo '<option value="'.$cod.'%'.$nom.'">'.$nom.'</option>';
	}
}
function listar_vacunasOp(){
	include_once('../MODELO/m_vacuna.php');
	$obj = new cls_vacuna();
	$rs = $obj->listar(); # array 
	for ($i=0;$i<count($rs);$i++) { 
		$cod = $rs[$i]['cod'];
		$nom = $rs[$i]['nom'];
		echo '<option value="'.$cod.'%'.$nom.'">'.$nom.'</option>';
	}
}
function listar_discapacidadesOp($discapacidad){
	include_once('../MODELO/m_discapacidad.php');
	$obj = new cls_discapacidad();
	$rs = $obj->listar(); # array 
	for ($i=0;$i<count($rs);$i++) { 
		$cod = $rs[$i]['cod'];
		$nom = $rs[$i]['nom'];
		if( $discapacidad == $cod ){
			echo '<option value="'.$cod.'" selected>'.$nom.'</option>';
		}else{
			echo '<option value="'.$cod.'">'.$nom.'</option>';
		}
	}
}


function consultar_notas(){
	global $rsPA1, $rsPA2, $rsPA3, $rsInd1,  $rsInd2, $rsInd3, $rsProm1, $rsProm2, $rsProm3;
	global $cod_AESC, $codInsc, $seccion; # de: c_inscripcion.php
	include_once('../MODELO/m_lapso.php');
	include_once('../MODELO/m_PA.php');
	include_once('../MODELO/m_notas.php');
	$objLap = new cls_lapso();
	$objPa = new cls_PA();
	$objNota = new cls_notas();

	# notas de primer lapso: consulta lapso
	$objLap->setLapso(1,$cod_AESC);	
	$rsLap = $objLap->consultar();
	$objNota->setPromedio($codInsc,'',$rsLap['cod_lapso']);

	# existe promedio de este lapso?
	if( $rsProm1 = $objNota->consultar_promedio() ){
		# consulta el PA
		$objPa->setPA('',$seccion,$rsLap['cod_lapso'],''); 
		$rsPA1 = $objPa->consultarPA();
		# consulta los indicadores
		$objNota->setPA($rsPA1['cod_proyecto']);
		$rsInd1 = $objNota->consultar_notas_indicadores();
	}

	# notas de primer lapso: consulta lapso
	$objLap->setLapso(2,$cod_AESC);	
	$rsLap = $objLap->consultar();
	$objNota->setPromedio($codInsc,'',$rsLap['cod_lapso']);

	# existe promedio de este lapso?
	if( $rsProm2 = $objNota->consultar_promedio() ){
		# consulta el PA
		$objPa->setPA('',$seccion,$rsLap['cod_lapso'],''); 
		$rsPA2 = $objPa->consultarPA();
		# consulta los indicadores
		$objNota->setPA($rsPA2['cod_proyecto']);
		$rsInd2 = $objNota->consultar_notas_indicadores();
	}

	# notas de primer lapso: consulta lapso
	$objLap->setLapso(3,$cod_AESC);	
	$rsLap = $objLap->consultar();
	$objNota->setPromedio($codInsc,'',$rsLap['cod_lapso']);

	# existe promedio de este lapso?
	if( $rsProm3 = $objNota->consultar_promedio() ){
		# consulta el PA
		$objPa->setPA('',$seccion,$rsLap['cod_lapso'],''); 
		$rsPA3 = $objPa->consultarPA();
		# consulta los indicadores
		$objNota->setPA($rsPA3['cod_proyecto']);
		$rsInd3 = $objNota->consultar_notas_indicadores();
	}
}

function imprimir_notas($rsInd){
	if( count($rsInd) == 0){
		echo '<tr><td colspan="3">No se encontraron resultados</td></tr>';
	}
	else{
		$num = 1;
		for ($i=0; $i < count($rsInd); $i++) { 
			echo '<tr>';
				echo '<td>'.$num.'</td>';
				echo '<td class="text_left">'.$rsInd[$i]['nom_ind'].'</td>';
				echo '<td>'.$rsInd[$i]['nota'].'</td>';
			echo '</tr>';
			$num++;
		}
	}
}
?>