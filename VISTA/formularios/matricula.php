<?php 
	# REPORTE MATRICULA DE SECCIÓN
	include_once('../CONTROL/c_matricula.php'); 
?>
<div class="titulo_m">
	<h2>Matrícula</h2><i class="icon-th-list"></i>
</div>

<p class="marginB05rem">
	Estimado usuario, si desea consultar una <b>matrícula</b> seleccione 
	el año escolar, el grado y la sección.
</p>

<form name="repMatricula" method="GET" action="../CONTROL/c_reporteMatricula.php">
	
	<div class="contInput w150 left">
		<b>Tipo de matrícula</b>
		<select name="tipo" class="input">
			<option value="0">SELECCIONAR</option>
			<option value="I">INICIAL</option>
			<option value="M" selected="true">MENSUAL</option>
			<option value="F">FINAL</option>
		</select>
	</div>

	<div class="contInput w150 left inputMes">
		<b>Mes</b>
		<select name="mes" class="input">
			<option value="0">SELECCIONAR</option>
			<option value="01" <?php if( $mesActual=='01' ){echo 'selected';} ?>>ENERO</option>
			<option value="02" <?php if( $mesActual=='02' ){echo 'selected';} ?>>FEBRERO</option>
			<option value="03" <?php if( $mesActual=='03' ){echo 'selected';} ?>>MARZO</option>
			<option value="04" <?php if( $mesActual=='04' ){echo 'selected';} ?>>ABRIL</option>
			<option value="05" <?php if( $mesActual=='05' ){echo 'selected';} ?>>MAYO</option>
			<option value="06" <?php if( $mesActual=='06' ){echo 'selected';} ?>>JUNIO</option>
			<option value="07" <?php if( $mesActual=='07' ){echo 'selected';} ?>>JULIO</option>
			<option value="08" <?php if( $mesActual=='08' ){echo 'selected';} ?>>AGOSTO</option>
			<option value="09" <?php if( $mesActual=='09' ){echo 'selected';} ?>>SEPTIEMBRE</option>
			<option value="10" <?php if( $mesActual=='10' ){echo 'selected';} ?>>OCTUBRE</option>
			<option value="11" <?php if( $mesActual=='11' ){echo 'selected';} ?>>NOVIEMBRE</option>
			<option value="12" <?php if( $mesActual=='12' ){echo 'selected';} ?>>DICIEMBRE</option>
		</select>
	</div>

	<div class="contInput w150 left">
		<b>Año Escolar</b>
		<input type="text" name="aesc" class="input text_center" maxlength="9" placeholder="Ej: 0000-0000" value="<?php echo $AESC; ?>" />
	</div>

	<div class="contInput w150 left">
		<b>Sección</b>
		<select name="cod_seccion" class="input">
			<option value="0">SELECCIONAR</option>
			<?php opSecciones($codAESC); ?>
		</select>
	</div>

	<div class="contInput w150 left">
		<p class="nomImput" style="height: 22px;"></p>
		<label class="btn btn_icon_split btn_normal btn_azul btn_lg" for="enviar">
			<p>Reporte</p>
			<i class="icon-export"></i>
			<input type="button" id="enviar" name="enviar" class="none"/>
		</label>
	</div>
	<div class="clear"></div>

	<div id="mensaje" class="msj_error margin5rem animacion1 none">
		<i class="icon-attention tx_rojo"></i>Todos los campos son <b>requeridos</b>.
	</div>
</form>
<script type="text/javascript" src="../JAVASCRIPT/f_repMatricula.js"></script>
