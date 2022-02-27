<?php
include_once('../CONTROL/c_inicio.php');
?>
<div class="titulo_m">
	<h2><i class="icon-home"></i> Inicio</h2>
</div>

<div class="row marginB05rem">
	<!-- tarjeta estudiantes -->
	<div class="col col33 padding1">
		<div class="tarjeta tb-l tb-verde shadow1">
			<div>
				<p class="cantidad"><?php echo $estudiantes; ?></p>
				<p class="texto">Estudiantes</p>
			</div>
			<div>
				<i class="icon-graduation-cap"></i>
			</div>
		</div>
	</div>
	<div class="col col33 padding1">
		<div class="tarjeta tb-l tb-rojo shadow1">
			<div>
				<p class="cantidad"><?php echo $representantes; ?></p>
				<p class="texto">Representantes</p>
			</div>
			<div>
				<i class="icon-users"></i>
			</div>
		</div>
	</div>
	<!-- tarjeta secciones -->
	<div class="col col33 padding1">
		<div class="tarjeta tb-l tb-azul shadow1">
			<div>
				<p class="cantidad"><?php echo $secciones; ?></p>
				<p class="texto">Secciones</p>
			</div>
			<div>
				<i class="icon-list"></i>
			</div>
		</div>
	</div>
	<!-- tarjeta docentes -->
	<div class="col col33 padding1">
		<div class="tarjeta tb-l tb-ama shadow1">
			<div>
				<p class="cantidad"><?php echo $docentes; ?></p>
				<p class="texto">Docentes</p>
			</div>
			<div>
				<i class="icon-users"></i>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>


<div id="bienvenido">
	<h1 style="font-size:2.5rem;"><i class="icon-graduation-cap"></i> Centro de Educación Inicial <b class="text_rosa"> "La Medinera"</b></h1>
	<h1>SOFTWARE PARA EL CONTROL DE ACTIVIDADES ACADÉMICAS Y ADMINISTRATIVAS</h1>
	<p>Año escolar: <b><?php echo $periodoEsc; ?></b></p>
</div>


<div class="row marginB05rem">
	<!-- Usuario -->
	<div class="col col50">
		<div id="cont_usuario">
			<div id="img_usuario">
				<img src="<?php echo $url_foto; ?>" class="w250 centrado" />
			</div>
			<div id="nombre">
				<b><?php echo $_SESSION['vsn_nombre'] . ' ' . $_SESSION['vsn_apellido']; ?></b>
			</div>
			<div id="ult_acceso">
				<p>Ultimo Acceso:
					<?php
					$fecha = new DateTime($_SESSION['vsn_ultconex']);
					echo $fecha->format('d/m/Y \a \l\a\s g:ia');
					?>
				</p>
			</div>
		</div>
	</div>
	<!-- Hora y calendario -->
	<div class="col col50">
		<div id="hora_calendario">
			<div id="calendario">
				<div id="calendar_top">
					<?php echo ucwords($diaL); ?>
				</div>
				<div id="calendar_mid">
					<p class="mes"><?php echo ucwords($mesL); ?></p>
					<p class="dia"><?php echo $diaN; ?></p>
					<p class="year"><?php echo $year; ?></p>
				</div>
				<div id="calendar_bot">

				</div>
			</div>
			<div id="reloj">
				<label id="hora"><?php echo $hora; ?></label>:
				<label id="minuto"><?php echo $min; ?></label>:
				<label id="segundo"><?php echo $seg; ?></label>
				<label id="am_pm"><?php echo $a; ?></label>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	window.onload = function() {
		if (getVariable('seg')) {
			// muestra mensaje importante al usuario para configurar datos de seguridad
			document.getElementById('b1').style.display = 'none';
			msj = '<b>Importante </b><i class="icon-attention-circled"></i>';
			p = '<p class="msj_aviso">Por seguridad se recomienda cambiar la <b>contraseña</b> y elegir <b>preguntas de seguridad</b>. Por favor, haga click <a href="?Perfil&form=2" class="text_azul text_bold">aquí</a> para ir configuración de usuario.</p>';
			OpenWindowNot(msj + p);
		}
		reloj();
	}

	function reloj() {
		hora = document.getElementById('hora');
		min = document.getElementById('minuto');
		seg = document.getElementById('segundo');
		ampm = document.getElementById('am_pm');

		objReloj = setInterval(function() {
			seg.innerHTML = parseFloat(seg.innerHTML) + 1;
			if (parseFloat(seg.innerHTML) < 10) {
				seg.innerHTML = '0' + seg.innerHTML;
			}
			if (parseFloat(seg.innerHTML) == 60) {
				seg.innerHTML = '00';
				min.innerHTML = parseFloat(min.innerHTML) + 1;

				if (parseFloat(min.innerHTML) < 10) {
					min.innerHTML = '0' + min.innerHTML;
				}

				if (parseFloat(min.innerHTML) == 60) {
					min.innerHTML = '00';
					hora.innerHTML = parseFloat(hora.innerHTML) + 1;

					if (parseFloat(hora.innerHTML) < 10) {
						hora.innerHTML = '0' + hora.innerHTML;
					}
					if (parseFloat(hora.innerHTML) == 12 && ampm.innerHTML == 'am') {
						ampm.innerHTML = 'pm';
					} else if (parseFloat(hora.innerHTML) == 12 && ampm.innerHTML == 'pm') {
						ampm.innerHTML = 'am';
					}
				}
			}
		}, 1000);
	}
</script>