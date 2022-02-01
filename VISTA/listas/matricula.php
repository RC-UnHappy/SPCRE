<?php 
	include_once('../CONTROL/c_matricula.php'); 
?>
<div class="titulo_m">
	<h2>Matrícula</h2><i class="icon-th-list"></i>
</div>

<p class="marginB05rem">
	Estimado usuario, si desea consultar una <b>matrícula</b> seleccione 
	el año escolar, el grado y la sección.
</p>

<!-- Formulario Para consultar matrícula -->
<form method="GET" name="f_consultar" class="s_n3">
	<div class="contInput w200 left">
		<b>Año escolar</b>
		<select name="periodo" class="input">
			<?php 
				imprimir_aesc();
			?>
		</select>
	</div>

	<div class="contInput w200 left">
		<b>Grado y Sección</b>
		<select name="seccion" class="input">
			<?php imprimir_sec(); ?>
		</select>
	</div>

	<div class="contInput left">
		<p class="nomImput" style="height: 22px;"></p>
		<label class="btn btn_icon_split btn_normal btn_azul btn_lg" for="btn_cons_matricula">
			<p>Generar reporte</p>
			<i class="icon-export"></i>
			<input type="button" id="btn_cons_matricula" name="enviar" class="none"/>
		</label>
	</div>
	<div class="clear"></div>
	<div class="mensaje msj_error margin5rem none animacion1">
		Todos los campos son <b>requeridos</b>.
	</div>
</form>
