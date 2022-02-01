<?php include_once('../CONTROL/c_reporteNominaRep.php'); ?>
<div class="titulo_m">
	<h2>Nómina de Representantes</h2><i class="icon-docs"></i>
</div>

<p class="marginB05rem">
	Estimado usuario, por favor ingrese el <b class="text_azul">Año escolar</b>.
</p>

<form name="repResumen" method="GET" action="../CONTROL/c_reporteNominaRep.php">
	<div class="contInput w200 left">
		<b>Año Escolar</b>
		<input type="text" name="aesc" class="input" maxlength="9" placeholder="Ej: 0000-0000" value="<?php echo $AESC; ?>" />
	</div>

	<div class="contInput left">
		<p class="nomImput" style="height: 22px;"></p>
		<label class="btn btn_icon_split btn_normal btn_azul btn_lg" for="enviar">
			<p>Generar reporte</p>
			<i class="icon-export"></i>
			<input type="button" id="enviar" name="enviar" class="none"/>
		</label>
	</div>
	<div class="clear"></div>

	<div id="mensaje" class="msj_error margin5rem animacion1 none">
		<i class="icon-attention tx_rojo"></i>El año escolar es <b>requerido</b>.
	</div>
</form>
<script type="text/javascript" src="../JAVASCRIPT/f_reporteNominaRep.js"></script>