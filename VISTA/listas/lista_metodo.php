<?php 
	include_once('../CONTROL/c_metodo.php'); 
?>
<div class="titulo_m">
	<h2>Método <?php echo $txtSer; ?></h2><i class="icon-codeopen"></i> 
</div>

<p class="marginB-2">
	Lista de <b>Métodos</b> <b class="text_azul">(Acciones)</b> que tienen los usuarios sobre los <b class="text_rosa">servicios</b> del sistema. Por favor, seleccione el <b class="text_azul">módulo</b> y <b class="text_azul">servicio</b> antes de realizar alguna <b>operación</b>.
</p>

<div class="marginB05rem">
	<?php if( $btnAgregar == 1){ ?>
	<div class="left">
		<!-- agregar -->
		<div class="btn btn_icon_split btn_normal btn_verde btn_md" id="open-W-form"><i class="icon-plus"></i><p>Agregar Rol</p></div>
	</div>
	<?php } ?>
	<div class="right">
		<!-- buscar -->
		<div class="input_and_btn">
			<p>Módulo:</p>
			<div class="contInput2Item md" style="width: 180px; margin-right: 5px;">
				<select class="input" id="modulo_buscar" style="padding: 0px 5px;">
					<option value="0">Seleccionar</option>
					<?php $objModulo->combo_option($moduloA); ?>
				</select>
			</div>
		</div>
		<div class="input_and_btn">
			<p>Servicio:</p>
			<div class="contInput2Item md" style="width: 180px; margin-right: 5px;">
				<select class="input" id="servicio_buscar" style="padding: 0px 5px;">
					<?php 
						$objServicio->set_modulo($moduloA);
						$objServicio->combo_option(true, $servicioA); 
					?>
				</select>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>

<form name="f_metodo" method="POST" action="../CONTROL/c_metodo.php">
	<input id="enviarM" name="enviarM" type="button" class="none"/>
	<input id="enviarR" name="enviarR" type="button" class="none"/>
	<input type="hidden" name="ope"/>
	<input type="hidden" name="modulo" value="<?php echo $moduloA; ?>"/>
	<input type="hidden" name="servicio" value="<?php echo $servicioA; ?>"/>
	<input type="hidden" name="arrRoles" value="<?php echo $arrRoles; ?>" />
	<input type="hidden" name="arrInc" value="<?php echo $arrInc; ?>" />
	<input type="hidden" name="arrMod" value="<?php echo $arrMod; ?>" />
	<input type="hidden" name="arrElm" value="<?php echo $arrElm; ?>" />
	<input type="hidden" name="arrCons" value="<?php echo $arrCons; ?>" />
	<!-- :/ se hizo asi porque no quizo firulai: -->
	<input type="hidden" name="rol">

	<!-- ventanita -->
	<div id="div_servicio" class="W-form">
		<!-- head -->
		<div class="W-top">
			 <h3 class="W-nom" id="W-nom">Agregar <b class="text_azul">Rol</b> al servicio</h3>
			 <label for="close-W-form" class="icon-cancel"></label>
			 <input type="button" id="close-W-form" class="none" />
			 <div class="clear"></div>
		</div>
		<!-- cuerpo -->
		<div class="W-body">
			<div class="row">
				<div class="col col-200">
					<!-- Roles-->
					<div class="contInput" style="width: 240px;">
						<div class="nomInput"><i class="icon-attention-circled"></i> Rol: </div>
						<select id="selRol" class="input">
							<option value="0">Seleccionar</option>
							<?php 
								$objMetodo->set('',$servicioA);
								$objMetodo->combo_rol_servicio(); 
							?>
						</select>
						<div class="msjBox animacion1"></div>
					</div>
				</div>
			</div>
		</div>
		<!-- botones -->
		<div class="W-bottom">
			<label for="enviarR" class="btn btn_icon_split btn_normal btn_verde btn_md"><i class="icon-plus"></i><p>Agregar</p></label>
			<label for="close-W-form" class="btn btn_icon_split btn_normal btn_gris2 btn_md"><p>Cancelar</p></label>
		</div>
	</div>

	<div class="caja">
		<table class="tabla3" cellspacing="0" id="resultados" width="96%">
			<tr id="thead">
		 		<th width="50px" rowspan="2">#</th>
		 		<th rowspan="2">ROL (NIVEL DE USUARIO)</th>
		 		<th colspan="4" style="padding: 2px !important;">SERVICIO <b class="text_azul"><?php echo $thNomSerA; ?></b></th>
		 		<th rowspan="2" width="0">ACCIÓN</th>
		 	</tr>
			<tr>
				<th width="100px" style="font-size: 12px; padding: 5px !important;">INCLUIR</th>
		 		<th width="100px" style="font-size: 12px; padding: 5px !important;">MODIFICAR</th>
		 		<th width="100px" style="font-size: 12px; padding: 5px !important;">CONSULTAR</th>
		 		<th width="100px" style="font-size: 12px; padding: 5px !important;">ELIMINAR</th>
			</tr>
		 	<?php crear_tabla(); ?>
		 </table>
	</div>
	<label for="enviarM" class="marginTB1 right btn btn_icon_split btn_normal btn_verde btn_md"><i class="icon-check"></i><p>Guardar Cambios</p></label>
</form>
<div class="clear"></div>
<script type="text/javascript" src="../JAVASCRIPT/l_metodo.js"></script>