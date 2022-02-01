<?php 
	include_once('../MODELO/m_a_escolar.php');
	$obj = new cls_a_escolar();
	$rs = $obj->ultimo_aesc();
	$aesc = $rs['cod_periodo']; # ultimo año escolar
	$periodo = $rs['periodo'];
?>
<div id="tt" class="titulo_m">
	<h2 id="tltModulo">Reportes</h2><i class="icon-doc-text"></i>
</div>

<p class="marginB05rem">
	Estimado usuario, por favor seleccione el <b class="text_azul">tipo de solicitud</b> e ingrese la <b class="text_azul">cédula escolar</b> del estudiante.
</p>

<form name="solicitud" method="POST" action="../CONTROL/c_solicitud.php">
	<input type="hidden" name="aesc" value="<?php echo $aesc; ?>"/>
	<input type="hidden" name="periodo" value="<?php echo $periodo; ?>"/>

	<div class="contInput w250 left">
		<b>Tipo de solicitud</b>
		<select name="reporte" class="input">
			<option value="0">SELECCIONAR</option>
			<option value="1">Constancia de Inscripción</option>
			<option value="2">Constancia de Estudio</option>
			<option value="3">Constancia de Conducta</option>
			<option value="4">Constancia de Retiro</option>
			<option value="5">Constancia de prosecución</option>
			<option value="6">Certificado de promoción</option>
		</select>
	</div>

	<div class="contInput w250 left">
		<b>CI / Cédula Escolar</b>
		<div class="contInput2Item">
			<div class="in_left">
				<select name="tipo_doc">
					<option value="V">V</option>
					<option value="E">E</option>
				</select>
				<i class="icon-down-dir"></i>
			</div>
			<!-- cedula escolar -->
			<div class="in_right">
				<input type="text" name="ced" placeholder="Ej: 12299999999" maxlength="11" size="18"/>
			</div>
		</div>
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
<script type="text/javascript" src="../JAVASCRIPT/f_solicitud.js"></script>