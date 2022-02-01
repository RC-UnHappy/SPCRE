<?php 
	include_once('../MODELO/m_a_escolar.php');
	$objAesc = new cls_a_escolar();
	$rsAesc = $objAesc->aesc_actual(); # consula el año escolar actual
	
	if( !$rsAesc ){
		$periodoEsc = '<label class="text_azul">(No iniciado)</label>';
	}
	
	else{
		$codPeriodo = $rsAesc['cod_periodo']; # codigo del año escolar
		$periodoEsc = $rsAesc['periodo']; # periodo ej: 2018-2019
	}
	
	# CONSULTAS
	if( isset($codPeriodo) ){
		$estudiantes = estudiantes($codPeriodo);
		$representantes = representantes($codPeriodo);
		$secciones = secciones($codPeriodo);

	}
	else{
		$estudiantes = 0;
		$secciones = 0;
		$representantes = 0;
	}
	
	$docentes = docentes();

	#foto del usuario
	$url_foto = '../IMG/avatar.jpg';
	if( $_SESSION['vsn_foto'] != '' ){
		$url_foto = '../upload/'.$_SESSION['vsn_foto'];
	}

	date_default_timezone_set('America/Caracas');
	setlocale(LC_TIME, 'spanish'); setlocale(LC_TIME, 'es_ES.UTF-8');
	$replace = array('a','e','i','o','u','n');
	$search = array('á','é','í','ó','ú','ñ');

	$diaL = strftime("%A");
	if(substr($diaL,0,1) == 's'){
		$diaL = 'Sábado';
	}
	if( substr($diaL, 0,2) == 'mi'){
		$diaL = 'Miércoles';
 	}
	$diaN = strftime("%d");
	$mesL = strftime("%B");
	$year = strftime("%Y");

	$horaActual = date('h:i:s a'); 
	// php5
	// $nuevaHora = strtotime('-30 minute', strtotime($horaActual));
	// $nuevaHora = date('h:i:s a', $nuevaHora);
	// #$nuevaHora resta 30min
	
	$hora = substr($horaActual, 0,2);
	$min = substr($horaActual, 3,2);
	$seg = substr($horaActual, 6,2);
	$a = substr($horaActual, 9,2);

	# FUNCIONES 
	function estudiantes($aesc){ # consulta la cantidad de estudiantes inscritos en el año escolar
		include_once('../MODELO/m_inscripcion.php');
		$obj = new cls_inscripcion();
		return $obj->cons_estudiantes_aesc($aesc);
	}

	function representantes($aesc){
		include_once('../MODELO/m_inscripcion.php');
		$obj = new cls_inscripcion();
		return $obj->cons_representantes_aesc($aesc);
	}

	function docentes(){ # consulta la cantidad de docentes activos
		include_once('../MODELO/m_personal.php');
		$obj= new cls_personal();
		return $obj->cantidad_docentes();
	}

	function secciones($aesc){ # consulta la cantidad de secciones en el año escolar
		include_once('../MODELO/m_seccion.php');
		$obj = new cls_seccion();
		$obj->set_periodo($aesc);
		return $obj->cantidad_secciones();
	}
?>