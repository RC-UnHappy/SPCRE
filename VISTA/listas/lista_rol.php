<?php 
	include_once('../CONTROL/c_rol.php');
?>
<div class="titulo_m">
	<h2>Rol de Usuarios</h2><i class="icon-users"></i>
</div>

<p class="marginB-2">
	Lista de <b class="text_azul">Roles de Usuario</b> en el sistema.
</p>

<div class="marginB05rem">
	<div class="left">
		<!-- agregar -->
		<div class="btn btn_icon_split btn_normal btn_verde btn_md" id="open-W-form"><i class="icon-plus"></i><p>Agregar Rol</p></div>
	</div>
	<div class="clear"></div>
</div>


<div class="caja">
	<table class="tabla3" cellspacing="0" id="resultados" width="96%">
		<tr id="thead">
	 		<th width="50px">#</th>
	 		<th>NOMBRE</th>
	 		<th>DESCRIPCIÓN</th>
	 		<th width="100px">ACCIÓN</th>
	 	</tr>
	 	<?php listar_roles(); ?>
	 </table>
	
	<!-- Ventana Formulario se agrega a id="window-cont-formulario" de index.php ver: ventanas.js -->
	<div id="div_rol" class="W-form">
		<!-- formulario enfermedad -->
		<form name="f_rol" method="POST" action="../CONTROL/c_rol.php">
			<input type="hidden" name="ope"/>
			<input type="hidden" name="cod"/>

			<div class="W-top">
				 <h3 class="W-nom" id="W-nom"><i class="icon-plus"></i>Agregar Rol</h3>
				 <label for="close-W-form" class="icon-cancel"></label>
				 <input type="button" id="close-W-form" class="none" />
				 <div class="clear"></div>
			</div>

			<div class="W-body">
				<div class="row">
					<div class="col col-300px">
						<!-- nombre -->
						<div class="contInput">
							<div class="nomInput"><i class="icon-attention-circled"></i> Nombre: </div>
							<input type="text" name="nom" class="input" placeholder="Escriba el nombre"/>
							<div class="msjBox animacion1"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col col-300px">
						<!-- descripcion -->
						<div class="contInput">
							<div class="nomInput"><i class="icon-attention-circled"></i> Descripción: </div>
							<input type="text" name="desc" class="input" placeholder="Escriba la descripción"/>
							<div class="msjBox animacion1"></div>
						</div>
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
<div id="mostrando" class="left margin5rem text_gris" style="padding: 10px 0px"><b>Mostrando: </b><?php echo $total.' de '.$total.' resultados'; ?></div>
<div class="clear"></div>
<script type="text/javascript" src="../JAVASCRIPT/l_rol.js"></script>