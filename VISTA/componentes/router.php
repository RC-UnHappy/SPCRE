<?php 
	# Este archivo compara la variable GET archivo y dependiendo de ello incluye el archivo externo al contenido de la página
	$archivo = '';
	$script = '';

	if( isset($_GET['ver']) ){ # Existe la variable GET archivo

		switch ($_GET['ver']){ # se compara el valos de la variable

			case 'plantel':
				$archivo = 'formularios/plantel.php';
				break;

			case 'a_escolar':
				$archivo = 'listas/periodo_escolar.php';
				break;

			case 'lapsos':
				$archivo = 'listas/lista_lapsos.php'; 
				break;

			case 'seccion':
				$archivo = 'listas/lista_seccion.php';
				break;

			case 'aulas':
				$archivo = 'listas/lista_aulas.php';
				break;

			case 'retiro':
				$archivo = 'formularios/f_retiro.php';
				break;

			case 'notas':
				$archivo = 'formularios/f_notas.php';
				break;

			case 'ayuda':
				$archivo = 'componentes/manuales.php';
				break;

			case 'solicitud':
				$archivo = 'formularios/solicitud.php';
				break;

			case 'matricula':
				$archivo = 'formularios/matricula.php';
				break;

			case 'dias_habiles':
				$archivo = 'vistas/dias_habiles.php';
				break;

			# wat????
			case 'estadistica':
				$archivo = 'formularios/estadistica.php';
				break;
			###

			case 'nomina_rep':
				$archivo = 'formularios/f_reporteNominaRep.php';
				break;

			case 'resumen':
				$archivo = 'formularios/f_repResumen.php';
				break;

			case 'lista_matricula';
				$archivo = 'listas/lista_matricula.php';
				break;

			case 'boletin':
				$archivo = 'formularios/f_repBoletin.php';
				break;

			case 'indicadores':
				$archivo = 'listas/lista_indicadores.php';
				break;

			case 'personal':
				$archivo = 'listas/lista_personal.php'; # incluye el formulario del archivo externo
				$script = '<script type="text/javascript" src="../JAVASCRIPT/l_personal.js"></script>';
				break;

			case 'asistencia_personal':
				$archivo = 'vistas/asistencia_personal.php';
				break;

			case 'permiso_personal':
				$archivo = 'vistas/permiso_personal.php';
				break;

			case 'reposo_personal':
				$archivo = 'vistas/reposo_personal.php';
				break;

			case 'asistencia_estudiante':
				$archivo = 'vistas/asistencia_estudiante.php';
				break; 
				
			case 'enfermedad':
				$archivo = 'listas/lista_enfermedades.php';
				break;

			case 'vacunas':
				$archivo = 'listas/lista_vacunas.php';
				break;

			case 'ocupacion':
				$archivo = 'listas/lista_ocupaciones.php';
				break;

			# usuarios
			case 'usuarios':
				$archivo = 'listas/lista_usuarios.php';
				break;	

			# Seguridad Dinámica
			case 'rol':
				$archivo = 'listas/lista_rol.php';
				break;

			case 'modulo':
				$archivo = 'listas/lista_modulo.php';
				break;

			case 'servicio':
				$archivo = 'listas/lista_servicio.php';
				break;

			case 'metodo':
				$archivo = 'listas/lista_metodo.php';
				break;

			default: # inicio
				$tittle = 'Página no encontrada';
				$archivo = 'componentes/error404.html';
				break;
		}
	}

	else if( isset($_GET['configuracion']) ){
		switch ($_GET['configuracion']) {
			default: # error 404
				$tittle = 'Página no encontrada';
				$archivo = 'componentes/error404.html';
				break;
		}
	}

	else if( isset($_GET['Personal']) ){
		$tittle = 'Personal';
		$script = '<script type="text/javascript" src="../JAVASCRIPT/f_personal.js"></script>';
		$archivo = 'formularios/f_personal.php';
	}

	else if( isset($_GET['Representante']) ){
		$archivo = 'formularios/f_representante.php';
		$script = '<script type="text/javascript" src="../JAVASCRIPT/f_representante.js"></script>';
	}

	else if( isset($_GET['Estudiante']) ){
		$archivo = 'formularios/f_estudiante.php';
		$script = '<script type="text/javascript" src="../JAVASCRIPT/f_estudiante.js"></script>';
	}

	else if( isset($_GET['Perfil']) ){
		$tittle = 'Mi Perfil';
		$archivo = 'formularios/perfil_usuario.php';
	}

	else if( isset($_GET['inicio']) ){
		$tittle = 'Inicio';
		$archivo = 'componentes/inicio.php';
	}

	else if( isset($_GET['error']) ){ # error 
		if( $_GET['error']=='404'){
			$tittle = 'Página no encontrada';
			$archivo = 'componentes/error404.html';
		}
	}

	else if( isset($_GET['Reporte'])){
		$tittle = 'Reporte';
		$archivo = 'formularios/reportes.php';
	} 

	else{
		$tittle = 'Página no encontrada';
		$archivo = 'componentes/error404.html';
	}
 ?>