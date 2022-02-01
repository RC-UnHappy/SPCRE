<?php 
	session_start();
	#header('Content-Type: text/html; charset=utf-8'); 
	if( isset( $_SESSION['vsn_user'] ) ){ # la sesion se encuentra abierta
		include_once('CONTROL/logout.php');
		destruirSesion(); # cierra las sesiones (ver controlador logout.php)
	}
	# recuperar contraseña:
	$varRecPass = 'false';
	$codPer = '';
	$preg1 = '';
	$preg2 = '';
	if( isset($_SESSION['recPass']) ){
		$codPer  = $_SESSION['codPer'];
		$preg1 = '<p style="font-size:14px;"><b>¿'.$_SESSION['p1'].'?</b></p>';
		$preg2 = '<p style="font-size:14px;"><b>¿'.$_SESSION['p2'].'?</b></p>';
		$varRecPass = 'true';
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1 user-scalable=no" />
	<link rel="stylesheet" type="text/css" href="CSS/estilos.css" />
	<script type="text/javascript" src="JAVASCRIPT/funciones.js"></script>
	<script type="text/javascript" src="JAVASCRIPT/homepage.js"></script>
	<title> U.E.N.B Samuel Robinson </title>
</head>
<body>
	<header>
		<div class="contenedor">
			<img src="IMG/bandera.png">
			<div id="gob">
				Gobierno <b>Bolivariano</b> <br/> de Venezuela
			</div>

			<div class="parte2">
				Ministerio del Poder Popular <br/> para la <b>Educación</b>
			</div>

			<div class="parte2">
				Zona Educativa <b>Estado Portuguesa</b>
			</div>

			<div class="parte2" id="sm">
				U.E.N.B <b>"Samuel Robinson"</b>
			</div>
		</div>
	</header>
	<!-- caja contenedor del formulario login -->
	<div id="banner">
		<img src="IMG/banner.jpg" class="imgBanner" />
		<img src="IMG/banner2.jpg" class="imgBanner hidden"/>
		<img src="IMG/banner3.jpg" class="imgBanner hidden"/>
		<img src="IMG/banner4.jpg" class="imgBanner hidden"/>

		<div class="contenedor">
			<div id="titulo">
				<h1>
					SOFTWARE PARA EL CONTROL DE ACTIVIDADES ACADÉMICAS Y ADMINISTRATIVAS EN LA U.E.N.B <span>“SAMUEL ROBINSON”</span>  Araure – Portuguesa 
				</h1>
			</div>
			<!-- formulario iniciar sesion -->
			<div id="box_login" class="box">
				<form name="form_login" method="POST" action="CONTROL/login.php"/>
					<h2>Iniciar Sesión</h2>
					<div class="cont_input">
						<i class="icon-user left-icon"></i>
						<div style="overflow: hidden; width: 20px;">
							<select name="d_ID" class="input" style="width: 50px;">
								<option value="V">V</option>
								<option value="E">E</option>
							</select>
						</div>
						<i class="guion">-</i>
						<input type="text" name="user" placeholder="Usuario" class="input" maxlength="8"/>
						<div class="msjBox animacion1"></div>
					</div>
					<div class="cont_input">
						<i class="icon-key left-icon"></i><input type="password" name="pass" placeholder="Contraseña" class="input" />
						<label id="ojo" onclick="verPass(this)" class="icon-eye none" style="color: gray;"></label>
						<div class="msjBox animacion1"></div>
					</div>
					<label for="enviar" class="btn_enviar">
						<i class="icon-login"></i>
						<p>Ingresar</p>
					</label>
					<input type="button" name="enviar" id="enviar" class="none" />
					<a href="#" class="text_azul" onclick="abrir_ventana()"><b>¿Olvidaste tu contraseña?</b></a>
				</form>
			</div>
			<div class="clear"></div>
			<div id="nota">
				<b>Importante:</b> Si es primera vez que inicia sesión, su usuario y contraseña es la cédula.
				<br/>
				Debe cambiar la contraseña y las preguntas de seguridad cuando inicie sesión por primera vez. 
			</div>
		</div>
		<div id="fondo"></div>
	</div>

	<!-- <section id="contenido">
		<div class="p_tb">
			<div id="escuela" class="contenedor">
				<h2>Escuela Samuel Robinson<i class="icon-down-open" onclick="mostrar_escuela(this)"></i></h2>
				<div id="textos_escuela">
					<div class="left">
						<img src="IMG/logotipo.png">
					</div>
					<div class="right">

						<div class="texto_escuela">
							<h3>Misión</h3><br/>
							<p>
								La Unidad Educativa Nacional Bolivariana “Samuel Robinson”, se define a sí misma como Institución Educativa de carácter público, dedicada a la formación integral de los niños, niñas y adolescentes sujetos de derecho y protagonistas activos de su propio aprendizaje, brindándoles un servicio desde maternal hasta el Liceo Bolivariano. Educamos mediante un proceso de interacción entre la familia, la escuela y la comunidad en un ambiente plenamente democrático, participativo y de compromisos; identificando y desarrollando las potencialidades de los alumnos y alumnas, respondiendo a sus necesidades y respetando sus características individuales, para así brindarles una educación de calidad con enfoque humanista. Proceso que tiene como finalidad, contribuir al pleno desarrollo de su personalidad y la integración de estos, como ciudadanos y ciudadanas a una sociedad en constante evolución. Propiciar un espacio abierto que estimule la participación ciudadana para impulsar y promover proyectos educativos integrales comunitarios, estrechamente relacionado con la formación académica basada en el cooperativismo con responsabilidad asertiva que permita alcanzar la meta de la excelencia educativa.
							</p>
							<br/>
							<h3>Visión</h3><br/>
							<p>
								La Unidad Educativa Nacional Bolivariana “Samuel Robinsón”, asume como visión llegar a ser una Institución Educativa innovadora, vinculada a la participación activa de la familia y la comunidad, que aporta a la sociedad un modelo de educación integral dirigido a niños, niñas y adolescentes; basada en el desarrollo ético, académico, tecnológico, deportivo, ambiental y socio – cultural de los mismos. Asimismo, que contribuya a la formación del ciudadano con habilidades para diagnosticar, planificar y administrar los proyectos, autogestiones con resultados de un alto impacto socioeconómico y cultural, donde se impulse el Desarrollo Endógeno como línea de acción estratégica de seguridad alimentaria que satisfaga las necesidades propias de la comunidad de manera sustentable y permanente.
							</p>
							<br/>
						</div>

						<div class="texto_escuela">
							<h3>Reseña Histórica</h3><br/>
							<p>La Unidad Educativa Nacional Bolivariana “Samuel Robinson “, forma parte de un Proyecto enmarcado en la ideal bandera del Presidente Hugo Rafael Chávez Frías de Escuelas Bolivarianas. Su construcción se inicia como una necesidad de Liceo Bolivariano en la Comunidad, en virtud a que en la misma existía la Escuela Básica. Es la Asociación de Vecinos, para ese entonces en la persona del Señor Marcos Jaime, Jimy Vásquez y otras personas que promueven el interés de la construcción ante los entes gubernamentales, siendo F.O.N.D.U.R. el organismo receptor de todas las peticiones comunitarias para la construcción del liceo.  Esta obra comienza a cristalizarse a mediados del mes de septiembre de 2004, surge entonces la necesidad de una infraestructura que albergue la demanda de estudiantes, cuyos padres y representantes se les asignaba vivienda en la urbanización: la Profesora Amalia Stasik, Jefa del Municipio Escolar en ese momento, con la colaboración  de docentes, representantes, padres, vecinos, entre otros, organizan un censo para conocer con exactitud la cantidad de niños que requerían cupos, en Educación Inicial, I y II etapa; aunque  la infraestructura fue diseñada para servir como liceo, se autoriza la apertura de un censo general para  fundamentar la solicitud de una Escuela Básica. F.O.N.D.U.R.  Hace entrega de la infraestructura el 14 de Diciembre del año 2004, a las autoridades educativas municipales y regionales.</p>
							<p>Su nombre (Samuel Robinson) surge por iniciativa de la Profesora Hilda Elena Jiménez, Jefa de Zona Educativa, en homenaje a Simón Rodríguez, maestro de maestros. Inicia sus actividades académicas el 14 de marzo del 2005 con una matrícula de 530 alumnos, actualmente atiende 1057 estudiantes distribuidos entre los subsistemas de: Inicial, Primaria, y Secundaria (Liceo Bolivariano).</p>
							<p>El 07 de marzo del año 2006, fue juramentada como Escuela Bolivariana por las autoridades educativas regionales y municipales.    Esta casa de estudios es un centro del quehacer Educativo – Comunitario, donde se crean las condiciones necesarias para lograr el bienestar individual y colectivo, mediante el desarrollo de actividades, estrategias y recursos que satisfagan las expectativas, necesidades y cambios socioculturales bajo la organización del personal Robinsoniano y la participación de la comunidad en general.</p>
							<br/>
						</div>

						<div class="texto_escuela">
							<h3>Objetivos Estratégicos</h3><br/>
							<p><i class="icon-right-dir"></i>Gestionar los procesos de inscripción de los estudiantes.</p>
							<p><i class="icon-right-dir"></i>Fortalecer a la institución como foco de oportunidades de estudio.</p>
							<p><i class="icon-right-dir"></i>Estimular a cada estudiante para obtener sus metas a nivel académico.</p>
							<p><i class="icon-right-dir"></i>Inculcar valores de Liderazgo, Optimismo, Honestidad y Responsabilidad en acciones como ser Humano.</p>
							<p><i class="icon-right-dir"></i>Fortalecer la integración Familia-Escuela-Comunidad.</p>
							<p><i class="icon-right-dir"></i>Consolidar la formación integral del estudiante.</p>
							<p><i class="icon-right-dir"></i>Preparar a los estudiantes para los retos en el desarrollo del contexto social.</p>
							<br/>
						</div>
					
						<div class="texto_escuela">
							<img src="IMG/org.png" width="95%">
						</div>
					</div>
				</div>
			</div>
		</div>
	</section> -->

	<footer>
		<div class="contenedor">
			Desarrollo de Software Web - Estudiantes de Informática sección 432 - 2018-2019 UPTP "Juan de Jesús Montilla"
		</div>
	</footer>

	<!-- ventana formulario recuperar contraseña -->
	<div class="cont_window hidden">
		<!-- buscar usuario -->
		<div class="box mg_auto" style="top: 80px;">
			<i class="icon-cancel" onclick="cerrar_ventana()"></i>

			<form name="recuperar_pass" method="POST" action="CONTROL/recuperar_pass.php">
				<input type="hidden" name="varRecPass" value="<?php echo $varRecPass; ?>">
				<input type="hidden" name="ope">
				<h3>Recuperar Contraseña</h3>
				<div class="cont_input">
					<i class="icon-user left-icon"></i>
					<div style="overflow: hidden; width: 20px;">
						<select name="doc_id" class="input" style="width: 50px;">
							<option value="V">V</option>
							<option value="E">E</option>
						</select>
					</div>
					<i class="guion">-</i>
					<input type="text" name="user" placeholder="Cédula" class="input" maxlength="8" />
					<div class="msjBox animacion1"></div>
				</div>
				<!-- boton -->
				<label for="enviarRecPass" class="btn_enviar">
					<p>Recuperar</p>
				</label>
				<input type="button" name="enviar" id="enviarRecPass" class="none" />
			</form>
			
			<!-- preguntas de seguridad -->
			<form name="preguntasSeg" method="POST" action="CONTROL/recuperar_pass.php" class="none">
				<input type="hidden" name="varRecPass" value="<?php echo $varRecPass; ?>">
				<input type="hidden" name="ope" />
				<h3>Preguntas de Seguridad</h3>
				<?php echo $preg1; ?>
				<div class="cont_input">
					</i><input type="text" name="r1" placeholder="Escriba su respuesta" class="input" maxlength="13" />
					<div class="msjBox animacion1"></div>
				</div>
				<?php echo $preg2; ?>
				<div class="cont_input">
					</i><input type="text" name="r2" placeholder="Escriba su respuesta" class="input" maxlength="13" />
					<div class="msjBox animacion1"></div>
				</div>
				<label for="enviarPregSeg" class="btn_enviar">
					<p>Comprobar</p>
				</label>
				<input type="button" name="enviar" id="enviarPregSeg" class="none" />
			</form>

			<!-- nueva contraseña -->
			<form name="nuevaPass" method="POST" action="CONTROL/recuperar_pass.php" class="none">
				<input type="hidden" name="varRecPass" value="<?php echo $varRecPass; ?>">
				<input type="hidden" name="ope" />
				<h3>Nueva Contraseña</h3>
				<div class="cont_input">
					<i class="icon-lock left-icon"></i><input type="password" name="pass" placeholder="Nueva Contraseña" class="input" />
					<div class="msjBox animacion1"></div>
				</div>
				<div class="cont_input">
					<i class="icon-pencil left-icon"></i><input type="password" name="rpass" placeholder="Repetir Contraseña" class="input" />
					<div class="msjBox animacion1"></div>
				</div>
				<label for="enviarChangePass" class="btn_enviar">
					<p>Cambiar Contraseña</p>
				</label>
				<input type="button" name="enviar" id="enviarChangePass" class="none" />
			</form>
		</div>
	</div>
</body>
</html>