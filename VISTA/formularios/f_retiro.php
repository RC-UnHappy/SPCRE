<?php include_once('../CONTROL/c_retiro.php'); ?>
<div class="titulo_m">
	<h2>Retiro
	<i class="icon-logout"></i>
	<?php echo $txtAESC; ?>
	</h2> 
</div>

<p class="marginB-2">
	Lista de <b class="text_azul">Retiros</b> en el año escolar.
</p>

<?php if($estatus_AESC == 'C'){?>
<div id="" class="msj_error marginB-2">
<i class="icon-attention tx_rojo"></i> <b>Año escolar</b> cerrado.</b>
</div>
<?php } ?>

<div class="marginB05rem">
	<div class="left">
		<?php 
		if($estatus_AESC == 'A'){ 
			if( $sI == '1' ){
		?>
		<!-- agregar -->
		<div class="btn btn_icon_split btn_normal btn_verde btn_md" id="open-W-form"><i class="icon-plus"></i><p>Nuevo Retiro</p></div>
		<?php } } ?>
	</div>
	
	<div class="right">
		<?php if( $sC == '1' ){ ?>
		<!-- buscar -->
		<div class="input_and_btn">
			<p>Grado:</p>
			<div class="contInput2Item md" style="width: 180px;">
				<select class="input" id="buscar_grado" style="padding: 0px 5px;">
					<option value="T">Todos</option>			
					<option value="1">1er Grado</option>
					<option value="2">2do Grado</option>
					<option value="3">3er Grado</option>
					<option value="4">4to Grado</option>
					<option value="5">5to Grado</option>
					<option value="6">6to Grado</option>
				</select>
			</div>
		</div>
		<div class="input_and_btn">
			<div class="contInput2Item md">
				<div class="in_right">
					<input type="text" id="txt_buscar" placeholder="Año escolar" size="13" maxlength="9" value="<?php echo $AESC; ?>"/>
					<label for="btn_buscar" class="btn btn_gris2">
						<i class="icon-search"></i>
						<input type="button" id="btn_buscar" class="none">
					</label>
				</div>				
			</div>
		</div>
		<?php } ?>
	</div>
	<div class="clear"></div>
</div>

<div class="caja">
	<table class="tabla3" cellspacing="0" id="resultados" width="96%">
		<tr id="thead">
	 		<th width="30px">#</th>
	 		<th>C.I / C.E</th>
	 		<th>NOMBRES</th>
	 		<th>APELLIDOS</th>
	 		<th width="100px">GRADO</th>
	 		<th width="100px">FECHA</th>
	 		<th width="100px">ACCIONES</th>
	 	</tr>
	 	<?php listar_retiros(); ?>
	 </table>
	
	<!-- Ventana Formulario se agrega a id="window-cont-formulario" de index.php ver: ventanas.js -->
	<div id="div_retiro" class="W-form">
		<!-- formulario servicio -->
		<form name="f_retiro" method="POST" action="../CONTROL/c_retiro.php">
			<input type="hidden" name="codAesc" value="<?php echo $cod_AESC; ?>">
			<input type="hidden" name="eAesc" value="<?php echo $estatus_AESC; ?>">
			<input type="hidden" name="ope"/>
			<input type="hidden" name="cod"/>

			<div class="W-top">
				 <h3 class="W-nom" id="W-nom"><i class="icon-plus"></i>Titulo</h3>
				 <label for="close-W-form" class="icon-cancel"></label>
				 <input type="button" id="close-W-form" class="none" />
				 <div class="clear"></div>
			</div>

			<div class="W-body scroll-y">
				<div class="row">
					<div class="col col-250px">
						<!-- Cedula -->
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Cédula o Cédula Escolar</p>
							<div class="contInput2Item">
								<div class="in_left">
									<select name="tipo_doc">
										<option value="V">V</option>
										<option value="E">E</option>
									</select>
									<i class="icon-down-dir"></i>
								</div>
								<div class="in_right">
									<input type="text" name="ced" placeholder="Ej: 12299999999" maxlength="11" size="18"/>
								</div>
							</div>
							<div class="msjBox animacion1"></div>
						</div>
					</div>
					<div class="col col-250px">
						<!-- fecha -->
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Fecha</p>
							<input type="date" name="fecha" class="input"/>
							<div class="msjBox animacion1"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col col-250px">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Nombres</p>
							<input type="text" name="nom" class="input" disabled="true"/>
						</div>
					</div>
					<div class="col col-250px">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Apellidos</p>
							<input type="text" name="ape" class="input" disabled="true"/>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col col-250px">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Grado</p>
							<input type="text" name="gdo" class="input text_center" disabled="true"/>
						</div>
					</div>
					<div class="col col-250px">
						<!-- causa de retiro -->
						<div class="contInput">
							<p class="nomInput"><i id="icon_causa" class="icon-attention-circled none"></i>Causa de retiro</p>
							<select name="causa" class="input">
								<option value="0">SELECCIONAR</option>
								<option value="1">Cambio de domicilio</option>
								<option value="2">Enfermedad</option>
								<option value="3">Defunción</option>
							</select>
							<div class="msjBox animacion1"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col col100">
						<!-- Observacion -->
						<div class="contInput">
							<p class="nomInput">Observación</p>
							<input type="text" name="obs" class="input" placeholder="Escriba la obervación"/>
						</div>
						<div class="msjBox animacion1"></div>
					</div>
				</div>
			</div>
			<!-- botones -->
			<div class="W-bottom">
				<?php if( $sI == '1' ){ ?>
				<label for="enviarReg" class="btn btn_icon_split btn_normal btn_verde btn_md none" id="btnReg"><i class="icon-plus"></i><p>Registrar</p></label>
				<input type="button" id="enviarReg" name="enviarReg" class="none" />
				<?php } ?>
				<?php if( $sM == '1' ){ ?>
				<label for="enviarMod" class="btn btn_icon_split btn_normal btn_verde btn_md none" id="btnMod"><i class="icon-edit"></i><p>Guardar cambios</p></label>
				<input type="button" id="enviarMod" name="enviarMod" class="none" />
				<?php } ?>
				<label for="close-W-form" class="btn btn_icon_split btn_normal btn_gris2 btn_md"><p>Cerrar</p></label>
			</div>
		</form>
	</div>
</div>
<div id="mostrando" class="left margin5rem text_gris" style="padding: 10px 0px"></div>
<div id="paginas" class="right margin5rem">
	<?#php paginas(); ?>
</div>
<div class="clear"></div>
<script type="text/javascript" src="../JAVASCRIPT/f_retiro.js"></script>