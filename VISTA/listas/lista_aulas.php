<?php include_once('../CONTROL/c_aula.php'); ?>
<div class="titulo_m">
	<h2>Aulas de Clase</h2><i class="icon-home"></i> 
</div>

<p class="marginB-2">
	Estimado usuario, si desea agregar una nueva aula de clases persione <b class="text_azul">Agregar Aula</b>.
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
		<?php if($sI == '1'){ ?>
		<div class="btn btn_icon_split btn_normal btn_verde btn_md" id="open-W-form"><i class="icon-plus"></i><p>Agregar Aula</p></div>
		<?php } ?>
	</div>
	
	<div class="right">
		<!-- buscar -->
		<?php if( $sM == '1' ){ ?>
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
		<?php } ?>
	</div>
	<div class="clear"></div>
</div>


<div class="caja">
	<!-- tabla -->
	<table class="tabla3" cellspacing="0" id="resultados" width="96%">
		<tr id="thead">
			<th width="1">#</th>
			<th>NOMBRE</th>
			<th>ESTATUS</th>
			<th width="100">ACCIÓN</th>
		</tr>
		<?php listar(); ?>
	</table>

	<!-- Formulario se agrega a la ventana ver: ventanas.js -->
	<div id="form-aulas" class="W-form">
		<form name="f_aula" method="POST" action="../CONTROL/c_aula.php">
			<input type="hidden" name="ope"/>
			<input type="hidden" name="cod"/>
			<div class="W-top">
				 <h3 class="W-nom" id="W-nom"><i class="icon-plus"></i>Agregar Aula</h3>
				 <label for="close-W-form" class="icon-cancel"></label>
				 <input type="button" id="close-W-form" class="none" />
				 <div class="clear"></div>
			</div>
			<div class="W-body">
				<div class="row">
					<div class="col-200">
						<!-- nombre -->
						<div class="contInput">
							<div class="nomInput"><i class="icon-attention-circled"></i> Nombre: </div>
							<input type="text" name="nom" class="input" placeholder="Escriba el nombre"/>
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
<div id="mostrando" class="left margin5rem text_gris" style="padding: 10px 0px"></div>
<div id="paginas" class="right margin5rem">
	<?php paginas(); ?>
</div>
<div class="clear"></div>
<script type="text/javascript" src="../JAVASCRIPT/l_aula.js"></script>
