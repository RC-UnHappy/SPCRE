<?php 
	include_once('../CONTROL/c_lapso.php'); 
?>

<div class="titulo_m">
	<h2>Lapsos (<?php echo $perAesc; ?>)</h2><i class="icon-calendar-check-o"></i>
</div>

<p class="marginB-2">
	Lista de <b>Lapsos</b> en el <b class="text_azul">año escolar</b>.
</p>

<?php 
	if($estatusAesc == 'C'){
?>
	<!-- mensaje -->
	<div class="msj_lg">
		<i class="icon-attention rojo"></i><h3>Año escolar finalizado</h3>
	</div>
<?php 
	}
?>

<div class="marginB05rem">
	<div class="right">
		<!-- buscar -->
		<?php if( $sC == '1' ){ ?>
		<div class="input_and_btn">
			<p>Año escolar</p>
			<div class="contInput2Item md">
				<div class="in_right">
					<input type="text" id="txt_buscar" placeholder="Buscar" class="text_center" size="13" maxlength="9"/>
					<label for="btn_buscar" class="btn btn_gris2">
						<i class="icon-search"></i>
						<input type="button" id="btn_buscar" class="none">
					</label>
				</div>				
			</div>
		</div>
		<?php  } ?>
	</div>
	<div class="clear"></div>
</div>

<div class="caja">
	<!-- tabla -->
	<table class="tabla3" cellspacing="0" id="resultados" width="96%">
		<tr id="thead">
			<th>LAPSO</th>
			<th>FECHA DE INICIO</th>
			<th>FECHA DE CIERRE</th>
			<th>ESTATUS</th>
			<th>ACCIONES</th>
		</tr>
		<?php crea_lista(); ?>
	</table>
</div>

<div id="vf_lapso" class="W-form">
	<!-- formulario enfermedad -->
	<form name="f_lapso" method="POST" action="../CONTROL/c_lapso.php">
		<input type="hidden" name="ope"/>
		<input type="hidden" name="cod"/>

		<div class="W-top">
			 <h3 class="W-nom" id="W-nom"><i class="icon-edit"></i>Modificar Lapso</h3>
			 <label for="close-W-form" class="icon-cancel"></label>
			 <input type="button" id="close-W-form" class="none" />
			 <div class="clear"></div>
		</div>

		<div class="formularios none">
			<div class="W-body">
				<div class="row">
					<div class="col-200">
						<!-- año escolar -->
						<div class="contInput">
							<div class="nomInput"><i class="icon-attention-circled"></i><b>Lapso</b></div>
							<input type="text" name="lapso" class="input text_center" disabled />
						</div>
						<div class="contInput">
							<div class="nomInput"><i class="icon-attention-circled"></i>Fecha de inicio</div>
							<input type="date" name="f_ini" class="input"/>
							<div class="mjB mjBr anm1">El campo es requerido</div>
						</div>
						<div class="contInput">
							<div class="nomInput"><i class="icon-attention-circled"></i>Fecha de cierre</div>
							<input type="date" name="f_fin" class="input"/>
							<div class="mjB mjBr anm1">El campo es requerido</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="formularios none">
			<div class="scroll-y" style="width: 950px; padding: 20px;">
				<div style="border:solid 1px var(--gris5);border-radius: 5px;padding: 10px;">
					<b style="font-size: 16px; margin-bottom: 8px; display: block;"><i class="icon-cog"> </i>Apertura de Carga de Notas</b>
					
					<p> Por favor, introdúzca las fechas, horas y minutos para el correcto funcionamiento del sistema. </p>
					<br/>
					<table border="0" cellspacing="5" style="margin: auto;">
						<tr>
							<th colspan="4" style="background: var(--gris3)">Desde</th>
							<th width="20"></th>
							<th colspan="4" style="background: var(--gris3)">Hasta</th>
						</tr>
						<tr>
							<td><b>Fecha </b><input type="date" name="fnotasD" class="input_sm text_center"></td>
							<td><b>Hora </b><input type="input" name="hnotasD" size="2" maxlength="2" placeholder="00" class="input_sm text_center"></td>
							<td><b>Minutos </b><input type="input" name="mnotasD" size="2" maxlength="2" placeholder="00" class="input_sm text_center"></td>
							<td><select name="tiemponotasD" class="input_sm text_center"><option value="am">am</option><option value="pm">pm</option></select></td>
							<td width="20"></td>
							<td><b>Fecha</b> <input type="date" name="fnotasH" class="input_sm text_center"></td>
							<td><b>Hora</b> <input type="input" name="hnotasH" size="2" placeholder="00" maxlength="2" class="input_sm text_center"></td>
							<td><b>Minutos</b> <input type="input" name="mnotasH" size="2" placeholder="00" maxlength="2" class="input_sm text_center"></td>
							<td><select name="tiemponotasH" class="input_sm text_center"><option value="am">am</option><option value="pm">pm</option></select></td>
						</tr>
					</table>
					<div class="msj_error margin5rem animacion1 none mensajes"></div>
				</div>	
			</div>
		</div>

		<!-- botones -->
		<div class="W-bottom">
			<label for="enviar" class="btn btn_icon_split btn_normal btn_verde btn_md" id="boton_enviar"><i class="icon-plus"></i><p>Modificar</p></label>
			<label for="close-W-form" class="btn btn_icon_split btn_normal btn_gris2 btn_md"><p>Cancelar</p></label>
			<input type="button" id="enviar" name="enviar" class="none" />
		</div>
		
	</form>
</div>

<script type="text/javascript" src="../JAVASCRIPT/l_lapsos.js"></script>
