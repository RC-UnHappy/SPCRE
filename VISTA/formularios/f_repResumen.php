<?php include_once('../CONTROL/c_repResumen.php'); ?>
<div class="titulo_m">
	<h2>Reportes de Resumen</h2><i class="icon-docs"></i>
</div>

<!-- Resumen de estadiscica -->
<div class="titulo_m_md">
	<h2>Resumen Estadístico</h2><i class="icon-doc"></i>
</div>
<p class="marginB05rem">
	Estimado usuario, por favor ingrese el <b class="text_azul">Año escolar</b> y seleccione el <b class="text_azul">Tipo de resumen</b> el <b class="text_azul">Mes</b> y el <b class="text_rosa">año escolar</b>. 
</p>
<form name="resumenEstadistico" method="GET" action="../CONTROL/c_repResumen.php">
	<input type="hidden" name="tipo" value="1"/>
	<div class="contInput w200 left">
		<b>Tipo de resumen</b>
		<select name="tipoResumen1" class="input">
			<option value="0">SELECCIONAR</option>
			<option value="I">Inicial</option>
			<option value="M">Mensual</option>
			<option value="F">Final</option>
		</select>
	</div>
	<div class="contInput w200 left inputMes">
		<b>Mes</b>
		<select name="mes" class="input" disabled="true">
			<option value="0">SELECCIONAR</option>
			<option value="01" <?php if($mesActual=='01'){echo 'selected';} ?>>ENERO</option>
			<option value="02" <?php if($mesActual=='02'){echo 'selected';} ?>>FEBRERO</option>
			<option value="03" <?php if($mesActual=='03'){echo 'selected';} ?>>MARZO</option>
			<option value="04" <?php if($mesActual=='04'){echo 'selected';} ?>>ABRIL</option>
			<option value="05" <?php if($mesActual=='05'){echo 'selected';} ?>>MAYO</option>
			<option value="06" <?php if($mesActual=='06'){echo 'selected';} ?>>JUNIO</option>
			<option value="07" <?php if($mesActual=='07'){echo 'selected';} ?>>JULIO</option>
			<option value="08" <?php if($mesActual=='08'){echo 'selected';} ?>>AGOSTO</option>
			<option value="09" <?php if($mesActual=='09'){echo 'selected';} ?>>SEPTIEMBRE</option>
			<option value="10" <?php if($mesActual=='10'){echo 'selected';} ?>>OCTUBRE</option>
			<option value="11" <?php if($mesActual=='11'){echo 'selected';} ?>>NOVIEMBRE</option>
			<option value="12" <?php if($mesActual=='12'){echo 'selected';} ?>>DICIEMBRE</option>
		</select>
	</div>
	<div class="contInput w200 left">
		<b>Año Escolar</b>
		<input type="text" name="aesc" class="input" maxlength="9" placeholder="Ej: 0000-0000" value="<?php echo $AESC; ?>" />
	</div>
	<div class="contInput left">
		<p class="nomImput" style="height: 22px;"></p>
		<label class="btn btn_icon_split btn_normal btn_azul btn_lg" for="enviar1">
			<p>Generar reporte</p>
			<i class="icon-export"></i>
			<input type="button" id="enviar1" name="enviar" class="none"/>
		</label>
	</div>
	<div class="clear"></div>
	<div class="msj_error margin5rem animacion1 none">
		<i class="icon-attention tx_rojo"></i>Todos los campos son <b>requeridos</b>.
	</div>
</form>
<br>
<div class="division"></div>
<br>

<!-- Resumen de matrículas -->
<!-- <div class="titulo_m_md">
	<h2>Resumen de Matrículas</h2><i class="icon-doc"></i>
</div>
<p class="marginB05rem">
	Estimado usuario, por favor ingrese el <b class="text_azul">Año escolar</b> y seleccione el <b class="text_azul">Tipo de resumen</b> el <b class="text_azul">Mes</b> y el <b class="text_rosa">año escolar</b>
</p>
<form name="resumenMatricula" method="GET" action="../CONTROL/c_repResumen.php">
	<input type="hidden" name="tipo" value="2"/>
	<div class="contInput w200 left">
		<b>Tipo de resumen</b>
		<select name="tipoResumen2" class="input">
			<option value="0">SELECCIONAR</option>
			<option value="I">Inicial</option>
			<option value="M">Mensual</option>
			<option value="F">Final</option>
		</select>
	</div>
	<div class="contInput w200 left inputMes">
		<b>Mes</b>
		<select name="mes" class="input" disabled="true">
			<option value="01" <?php if($mesActual=='01'){echo 'selected';} ?>>ENERO</option>
			<option value="02" <?php if($mesActual=='02'){echo 'selected';} ?>>FEBRERO</option>
			<option value="03" <?php if($mesActual=='03'){echo 'selected';} ?>>MARZO</option>
			<option value="04" <?php if($mesActual=='04'){echo 'selected';} ?>>ABRIL</option>
			<option value="05" <?php if($mesActual=='05'){echo 'selected';} ?>>MAYO</option>
			<option value="06" <?php if($mesActual=='06'){echo 'selected';} ?>>JUNIO</option>
			<option value="07" <?php if($mesActual=='07'){echo 'selected';} ?>>JULIO</option>
			<option value="08" <?php if($mesActual=='08'){echo 'selected';} ?>>AGOSTO</option>
			<option value="09" <?php if($mesActual=='09'){echo 'selected';} ?>>SEPTIEMBRE</option>
			<option value="10" <?php if($mesActual=='10'){echo 'selected';} ?>>OCTUBRE</option>
			<option value="11" <?php if($mesActual=='11'){echo 'selected';} ?>>NOVIEMBRE</option>
			<option value="12" <?php if($mesActual=='12'){echo 'selected';} ?>>DICIEMBRE</option>
		</select>
	</div>
	<div class="contInput w200 left">
		<b>Año Escolar</b>
		<input type="text" name="aesc" class="input" maxlength="9" placeholder="Ej: 0000-0000" value="<?php echo $AESC; ?>" />
	</div>
	<div class="contInput left">
		<p class="nomImput" style="height: 22px;"></p>
		<label class="btn btn_icon_split btn_normal btn_azul btn_lg" for="enviar2">
			<p>Generar reporte</p>
			<i class="icon-export"></i>
			<input type="button" id="enviar2" name="enviar" class="none"/>
		</label>
	</div>
	<div class="clear"></div>
	<div class="msj_error margin5rem animacion1 none">
		<i class="icon-attention tx_rojo"></i>Todos los campos son <b>requeridos</b>.
	</div>
</form>
<br>
<div class="division"></div>
<br> -->

<!-- Resumen del rendimiento escolar -->
<br>
<div class="titulo_m_md">
	<h2>Resumen del Rendimiento Estudiantil</h2><i class="icon-doc"></i>
</div>
<p class="marginB05rem">
	Estimado usuario, por favor ingrese el <b class="text_azul">Año escolar</b> y seleccione la <b class="text_rosa">sección</b>. 
</p>
<form name="repResumen" method="GET" action="../CONTROL/c_repResumen.php">
	<input type="hidden" name="tipo" value="3">
	<div class="contInput w200 left">
		<b>Año Escolar</b>
		<input type="text" name="aesc" class="input" maxlength="9" placeholder="Ej: 0000-0000" value="<?php echo $AESC; ?>" />
	</div>

	<div class="contInput w200 left">
		<b>Sección</b>
		<select name="seccion" class="input">
			<option value="0">SELECCIONAR</option>
			<?php opSecciones($codAESC); ?>
		</select>
	</div>

	<div class="contInput left">
		<p class="nomImput" style="height: 22px;"></p>
		<label class="btn btn_icon_split btn_normal btn_azul btn_lg" for="enviar3">
			<p>Generar reporte</p>
			<i class="icon-export"></i>
			<input type="button" id="enviar3" name="enviar" class="none" />
		</label>
	</div>
	<div class="clear"></div>

	<div class="msj_error margin5rem animacion1 none">
		<i class="icon-attention tx_rojo"></i>Todos los campos son <b>requeridos</b>.
	</div>
</form>
<script type="text/javascript" src="../JAVASCRIPT/f_repResumen.js"></script>