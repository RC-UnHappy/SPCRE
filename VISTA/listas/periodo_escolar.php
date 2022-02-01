<?php 
	include_once('../CONTROL/c_a_escolar.php');  # controlador año escolar 
?>

<div class="titulo_m">
	<h2>Año Escolar</h2><i class="icon-calendar"></i>
</div>

<p class="marginB-2">
	Estimado usuario, antes de <b class="text_azul">registrar</b> un nuevo año escolar debe finalizar el año escolar actual. Para ello, haga click en el botón <b>Cerrar</b> del año escolar con estatus activo. A continuación presione el botón <b class="text_azul">Nuevo año escolar</b> y presione <b class="text_azul">Registrar.</b>
</p>

<div class="left marginB05rem">
	<?php if( $sI == '1' ){ ?>
	<!-- agregar -->
	<div class="btn btn_icon_split btn_normal btn_verde btn_md" id="open-W-form"><i class="icon-plus"></i><p>Nuevo año escolar</p></div>
	<?php } ?>
</div>
<div class="clear"></div>

<div class="caja">
	<!-- tabla -->
	<table class="tabla3" cellspacing="0" id="resultados" width="96%">
		<tr id="thead">
			<th style="width: 1">#</th>
			<th>PERIODO</th>
			<th>INICIA</th>
			<th>FINALIZA</th>
			<th>FECHA DE CIERRE</th>
			<th>ESTATUS</th>
			<th>ACCIONES</th>
		</tr>
		<?php listar(); ?>
	</table>

	<!-- Formulario se agrega a la ventana ver: ventanas.js -->
	<div id="form-aesc" class="W-form">
		<form name="f_aesc" method="POST" action="../CONTROL/c_a_escolar.php">
			<input type="hidden" name="ope"/>
			<input type="hidden" name="cod"/>
			<input type="hidden" name="sta"/>
			<input type="hidden" name="h_periodo"/>
			<input type="hidden" name="p_sig" value="<?php op_aesc_siguiente(); ?>">

			<div class="W-top">
				 <h3 class="W-nom" id="W-nom"><i class="icon-plus"></i>Año escolar</h3>
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
								<div class="nomInput"><i class="icon-attention-circled"></i><b id="periodo">Año escolar siguiente</b></div>
								<input type="text" name="periodo" class="input text_center"/>
								<div class="mjB mjBr anm1"></div>
							</div>
							<div class="contInput">
								<div class="nomInput"><i class="icon-attention-circled"></i>Inicia</div>
								<input type="date" name="f_ini" class="input"/>
								<div class="mjB mjBr anm1"></div>
							</div>
							<div class="contInput">
								<div class="nomInput"><i class="icon-attention-circled"></i>Finaliza</div>
								<input type="date" name="f_fin" class="input"/>
								<div class="mjB mjBr anm1"></div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="formularios none">
				<div class="scroll-y" style="width: 950px; padding: 20px;">
					<div style="border:solid 1px var(--gris5);border-radius: 5px;padding: 10px;">
						<b style="font-size: 16px; margin-bottom: 8px; display: block;"><i class="icon-cog"> </i>Apertura de Inscripciones Nuevo Ingreso</b>
						
						<p> Por favor, introdúzca las fechas, horas y minutos para el correcto funcionamiento del sistema. </p>
						<br/>
						<table border="0" cellspacing="5" style="margin: auto;">
							<tr>
								<th colspan="4" style="background: var(--gris3)">Desde</th>
								<th width="20"></th>
								<th colspan="4" style="background: var(--gris3)">Hasta</th>
							</tr>
							<tr>
								<td><b>Fecha </b><input type="date" name="fnuevoD" class="input_sm text_center"></td>
								<td><b>Hora </b><input type="input" name="hnuevoD" size="2" maxlength="2" placeholder="00" class="input_sm text_center"></td>
								<td><b>Minutos </b><input type="input" name="mnuevoD" size="2" maxlength="2" placeholder="00" class="input_sm text_center"></td>
								<td><select name="tiemponuevoD" class="input_sm text_center"><option value="am">am</option><option value="pm">pm</option></select></td>
								<td width="20"></td>
								<td><b>Fecha</b> <input type="date" name="fnuevoH" class="input_sm text_center"></td>
								<td><b>Hora</b> <input type="input" name="hnuevoH" size="2" placeholder="00" maxlength="2" class="input_sm text_center"></td>
								<td><b>Minutos</b> <input type="input" name="mnuevoH" size="2" placeholder="00" maxlength="2" class="input_sm text_center"></td>
								<td><select name="tiemponuevoH" class="input_sm text_center"><option value="am">am</option><option value="pm">pm</option></select></td>
							</tr>
						</table>
						<div class="msj_error margin5rem animacion1 none mensajes"></div>
					</div>

					<br><br>

					<div style="border:solid 1px var(--gris5);border-radius: 5px;padding: 10px;">
						<b style="font-size: 16px; margin-bottom: 8px; display: block;"><i class="icon-cog"> </i>Apertura de Inscripciones Regulares</b>
						
						<p> Por favor, introdúzca las fechas, horas y minutos para el correcto funcionamiento del sistema. </p>
						<br/>
						<table border="0" cellspacing="5" style="margin: auto;">
							<tr>
								<th colspan="4" style="background: var(--gris3)">Desde</th>
								<th width="20"></th>
								<th colspan="4" style="background: var(--gris3)">Hasta</th>
							</tr>
							<tr>
								<td><b>Fecha </b><input type="date" name="fregularD" class="input_sm text_center"></td>
								<td><b>Hora </b><input type="input" name="hregularD" size="2" maxlength="2" placeholder="00" class="input_sm text_center"></td>
								<td><b>Minutos </b><input type="input" name="mregularD" size="2" maxlength="2" placeholder="00" class="input_sm text_center"></td>
								<td><select name="tiemporegularD" class="input_sm text_center"><option value="am">am</option><option value="pm">pm</option></select></td>
								<td width="20"></td>
								<td><b>Fecha</b> <input type="date" name="fregularH" class="input_sm text_center"></td>
								<td><b>Hora</b> <input type="input" name="hregularH" size="2" placeholder="00" maxlength="2" class="input_sm text_center"></td>
								<td><b>Minutos</b> <input type="input" name="mregularH" size="2" placeholder="00" maxlength="2" class="input_sm text_center"></td>
								<td><select name="tiemporegularH" class="input_sm text_center"><option value="am">am</option><option value="pm">pm</option></select></td>
							</tr>
						</table>
						<div class="msj_error margin5rem animacion1 none mensajes"></div>
					</div>

					<br/><br/>
					
					<div style="border:solid 1px var(--gris5);border-radius: 5px;padding: 10px;">
						<b style="font-size: 16px; margin-bottom: 8px; display: block;"><i class="icon-cog"> </i>Configuración de fecha de Matrícula Inicial</b>
						
						<p> Por favor, introdúzca las fechas para el correcto funcionamiento del sistema. Éstas fechas son necesarias para la generación de reportes de <b>matrícula inicial</b>.</p>
						<br/>
						<table border="0" cellspacing="5" style="margin: auto;">
							<tr>
								<th style="background: var(--gris3)">Desde</th>
								<th width="20"></th>
								<th style="background: var(--gris3)">Hasta</th>
							</tr>
							<tr>
								<td><b>Fecha </b><input type="date" name="fmatriculaD" class="input_sm text_center"></td>
								<td width="20"></td>
								<td><b>Fecha</b> <input type="date" name="fmatriculaH" class="input_sm text_center"></td>	
							</tr>
						</table>
						<div class="msj_error margin5rem animacion1 none mensajes"></div>
					</div>
				</div>
			</div>

			<!-- botones -->
			<div class="W-bottom">
				<label for="enviar" class="btn btn_icon_split btn_normal btn_verde btn_md" id="boton_enviar"><i class="icon-plus"></i><p>Agregar</p></label>
				<label for="close-W-form" class="btn btn_icon_split btn_normal btn_gris2 btn_md"><p>Cancelar</p></label>
				<input type="button" id="enviar" name="enviar" class="none" />
			</div>

		</form>
	</div>
</div>

<div id="w-claveSeguridad" class="w-float">
	<div class="box-w-float">
		<form method="POST" name="f_claveSeg" action="../CONTROL/c_a_escolar.php">
			<input type="hidden" name="usu" value="<?php echo $_SESSION['vsn_user']; ?>" />
			<input type="hidden" name="codAEscolar"/>
			<input type="hidden" name="h_periodo"/>
			<input type="hidden" name="sta"/>
			<div class="w600">
				<div class="msj_aviso">
					<b>Importante:</b><br>
					<p id="msj_f_ClaveSeg">
						Usted está apunto de cerrar el <b>año escolar</b>. Tenga en cuenta que una vez el año escolar esté cerrado no podrá abrirlo.
						Si desea continuar por favor introdúzca la <b>clave de seguridad</b>.
					</p>
				</div>
			</div>
			<div class="w400 centrado">
				<div class="contInput">
					<input type="password" name="passSeg" class="input" placeholder="Escriba la clave de seguridad"/>
				</div>
				<div class="right">
					<label for="envCv" class="btn btn_icon_split btn_normal btn_amarillo btn_md shadow-sm">
						<i class="icon-attention"></i>
						<p>Continuar</p>
					</label>
					<label for="canCv" class="btn btn_icon_split btn_normal btn_gris2 btn_md shadow-sm">
						<p>Cancelar</p>
					</label>
				</div>
			</div>
			<input type="button" name="cancelar" class="none" id="canCv" />
			<input type="button" name="enviar" class="none" id="envCv" />
		</form>
	</div>
</div>
<script type="text/javascript" src="../JAVASCRIPT/f_periodoEscolar.js"></script>
