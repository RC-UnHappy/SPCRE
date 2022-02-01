<?php include_once('../CONTROL/c_repBoletin.php'); ?>
<div class="titulo_m">
	<h2>Boletín</h2><i class="icon-th-list"></i>
</div>

<p class="marginB05rem">
	Estimado usuario, por favor ingrese la <b class="text_azul">cédula escolar</b> del estudiante, seleccione el <b class="text_rosa">tipo de boletín</b> y el <b>Año Escolar</b>. 
</p>

<form name="repBoletin" method="GET" action="../CONTROL/c_repBoletin.php">
	<div class="contInput w200 left">
		<b>CI / Cédula Escolar</b>
		<div class="contInput2Item">
			<div class="in_left">
				<select name="tipo_doc">
					<option value="V">V</option>
					<option value="E">E</option>
				</select>
			</div>
			<!-- cedula escolar -->
			<div class="in_right">
				<input type="text" name="ced" placeholder="Ej: 12299999999" maxlength="11" size="18"/>
			</div>
		</div>
	</div>
	<div class="contInput w200 left">
		<b>Tipo de boletín</b>
		<select name="lapso" class="input">
			<option value="0">SELECCIONAR</option>
			<option value="1">LAPSO 1</option>
			<option value="2">LAPSO 2</option>
			<option value="3">LAPSO 3</option>
			<option value="F">FINAL</option>
		</select>
	</div>
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
		<i class="icon-attention tx_rojo"></i>Todos los campos son <b>requeridos</b>.
	</div>
</form>
<script type="text/javascript" src="../JAVASCRIPT/f_repBoletin.js"></script>