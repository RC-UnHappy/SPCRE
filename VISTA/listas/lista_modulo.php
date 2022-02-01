<?php 
	include_once('../CONTROL/c_modulo.php');
?>
<div class="titulo_m">
	<h2>Módulo</h2><i class="icon-codeopen"></i>
</div>

<p class="marginB-2">
	Lista de <b class="text_azul">Módulos</b> del sistema.
</p>

<div class="marginB05rem">
	<div class="left">
		<div class="parent_dropdown_menu btn btn_icon_split btn_normal btn_md btn_gris2" onclick="dropdown_menu(this)">
			<i class="icon-down-dir parent_dropdown_menu"></i>
			<p class="parent_dropdown_menu">Mostrar</p>
			<div class="dropdown_menu animacion1 dropdown_menu_left">
				<ul>
					<li class="item" onclick="mostrar_filas(15)"><i class="icon-right-dir"></i>Mostrar 15</li>
					<li class="item" onclick="mostrar_filas(25)"><i class="icon-right-dir"></i>Mostrar 25</li>
					<li class="item" onclick="mostrar_filas(40)"><i class="icon-right-dir"></i>Mostrar 40</li>
					<li class="item" onclick="mostrar_filas(50)"><i class="icon-right-dir"></i>Mostrar 50</li>
				</ul>
			</div>
		</div>
		<!-- agregar -->
		<div class="btn btn_icon_split btn_normal btn_verde btn_md" id="open-W-form"><i class="icon-plus"></i><p>Agregar Módulo</p></div>
	</div>
	
	<div class="right">
		<!-- buscar -->
		<div class="input_and_btn">
			<div class="contInput2Item md">
				<div class="in_right">
					<input type="text" id="txt_buscar" placeholder="Buscar..." size="13"/>
					<label for="btn_buscar" class="btn btn_gris2">
						<i class="icon-search"></i>
						<input type="button" id="btn_buscar" class="none">
					</label>
				</div>				
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>

<div class="caja">
	<table class="tabla3" cellspacing="0" id="resultados" width="96%">
		<tr id="thead">
	 		<th width="50px">#</th>
	 		<th>NOMBRE</th>
	 		<th width="100px">ICONO</th>
	 		<th width="100px">ESTATUS</th>
	 		<th width="100px">ACCIÓN</th>
	 	</tr>
	 	<?php listar_modulos(); ?>
	 </table>
	
	<!-- Ventana Formulario se agrega a id="window-cont-formulario" de index.php ver: ventanas.js -->
	<div id="div_modulo" class="W-form">
		<!-- formulario enfermedad -->
		<form name="f_modulo" method="POST" action="../CONTROL/c_modulo.php">
			<input type="hidden" name="ope"/>
			<input type="hidden" name="cod"/>

			<div class="W-top">
				 <h3 class="W-nom" id="W-nom"><i class="icon-plus"></i>Agregar Módulo</h3>
				 <label for="close-W-form" class="icon-cancel"></label>
				 <input type="button" id="close-W-form" class="none" />
				 <div class="clear"></div>
			</div>

			<div class="W-body">
				<div class="row">
					<div class="col col-200">
						<!-- nombre -->
						<div class="contInput">
							<div class="nomInput"><i class="icon-attention-circled"></i> Nombre: </div>
							<input type="text" name="nom" class="input" placeholder="Escriba el nombre"/>
							<div class="msjBox animacion1"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col col-200">
						<!-- icono -->
						<div class="contInput">
							<div class="nomInput"><i class="icon-attention-circled"></i> Icono: </div>
							<input type="text" name="icono" class="input" placeholder="Escriba el código de icono"/>
							<div class="msjBox animacion1"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col col-200">
						<!-- estatus -->
						<div class="contInput">
							<div class="nomInput"><i class="icon-attention-circled"></i> Estatus: </div>
							<select name="estatus" class="input" style="width:200px;">
								<option value="A">Habilitado</option>
								<option value="I">Inhabilitado</option>
							</select>	
							<div class="msjBox animacion1"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col col-200">
						<!-- posicion -->
						<div class="contInput">
							<div class="nomInput">Posición: </div>
							<input type="text" name="pos" class="input text_center" placeholder="Posición del servicio en el menú" maxlength="2" />
						</div>
					</div>
				</div>
				<p id="aviso" class="msj_aviso tx_sm none"><b>Advertencia: </b> Si elimina éste módulo  también se eliminará<br/>la relación con los servicios. ¿Desea continuar?</p>
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
<div id="mostrando" class="left margin5rem text_gris" style="padding: 10px 0px"></div>
<div id="paginas" class="right margin5rem">
	<?php paginas(); ?>
</div>
<div class="clear"></div>
<script type="text/javascript" src="../JAVASCRIPT/l_modulo.js"></script>