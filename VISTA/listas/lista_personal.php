<?php 
	include_once('../CONTROL/c_personal.php');
?>

<div class="titulo_m">
	<h2>Personal</h2> <i class="icon-users"></i>
</div>

<p class="marginB-2">
	Estimado usuario, si desea registrar un nuevo personal, presione el botón <b class="text_azul">Registrar Personal</b> y a continuación rellene los datos del formulario.
</p>

<div class="marginB05rem">
	<div class="left">
		<?php if( $sC == '1' ){ ?>
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
		<?php } if($sI == '1'){ ?>
		<!-- agregar -->
		<a href="?Personal=registrar" class="btn btn_icon_split btn_normal btn_verde btn_md" id="btn-registrar"><i class="icon-plus"></i><p>Registrar Personal</p></a>
		<?php } ?>
	</div>
	
	<div class="right">
		<?php if($sC == '1'){ ?>
		<div class="input_and_btn">
			<div class="contInput2Item md" style="width: 180px;">
				<select class="input" id="estatus_buscar" style="padding: 0px 5px;">
					<option value="A"> ACTIVO </option>
					<option value="I"> INACTIVO </option>
				</select>
			</div>
		</div>
		
		<div class="input_and_btn">
			<div class="contInput2Item md" style="width: 180px;">
				<select class="input" id="cargo_buscar" style="padding: 0px 5px;">
					<option value="All"> TODOS LOS CARGOS </option>
					<?php listar_cargos(); ?>
				</select>
			</div>
		</div>
		<!-- buscar -->
		<div class="right contInput2Item md">
			<div class="in_right">
				<input type="text" id="text_buscar" placeholder="Buscar"/>
				<label for="btn_buscar" class="btn btn_gris2">
					<i class="icon-search"></i>
					<input type="button" id="btn_buscar" class="none">
				</label>
			</div>				
		</div>
		<?php } ?>
	</div>
	<div class="clear"></div>
</div>

<div id="xd">
	
</div>

<div class="caja">
	<table class="tabla3" cellspacing="0" id="resultados" width="96%" style="font-size: 13px;">
	 	<tr id="thead">
	 		<th style="padding: 10px;">#</th>
	 		<th>CÉDULA</th>
	 		<th>NOMBRE Y APELLIDO</th>
	 		<th>NIVEL</th>
	 		<th>CARGO</th>
	 		<th>FUNCION</th>
	 		<th>ESTATUS</th>
	 		<th>ACCIONES</th>
	 	</tr>
	 	<?php listar_personal();?>
	</table>
</div>
<?php if($sC == '1'){ ?>
<div id="mostrando" class="left margin5rem text_gris" style="padding: 10px 0px"></div>
<div id="paginas" class="right margin5rem">
	<?php paginas(); ?>
</div>
<?php } ?>
<div class="clear"></div>
