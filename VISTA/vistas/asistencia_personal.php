<?php include_once('../CONTROL/c_asistencia_personal.php'); ?>

<div class="titulo_m">
	<h2>Asistencia del Personal </h2><i class="icon-th-list"></i> 
</div>

<?php if( $cargar_asistencia == false ){ ?> 
<!-- No existe un año escolar activo -->
<div class="msj_lg">
	<i class="icon-attention rojo"></i><h3>No es posible cargar asistencias</h3>
</div>
<p class="msj_error s_n2"><i class="icon-cancel-circled2"></i>Disculpe, En éste momento no es posible cargar las <b>asistencias</b> de los personales debido a que no se encuentran registrados <b>días hábiles</b> en el año escolar.</p>
<?php exit(); } ?>

<p class="marginB-2">
	Estimado usuario, si desea registrar las asistencias seleccione el <b>mes</b> y el <b>día hábil</b>, luego proceda a seleccionar las asistencias del personal y por último presione el botón <b class="text_azul">Cargar asistencia.</b>
</p>

<form name="formulario" method="POST" action="../CONTROL/c_asistencia_personal.php"> 
	<input type="hidden" name="mes">
	<input type="hidden" name="dia_habil">
	<input type="hidden" name="datos">
	<input type="hidden" name="marcacion">
</form>

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
	</div>
	
	<div class="right">
		<!-- buscar -->
		<div class="input_and_btn">
			<div class="contInput2Item md" style="width: 120px;">
				<select class="input" id="marcacion" style="padding: 0px 5px;">
					<option value="E" <?php if( isset($_GET['marcacion']) && !empty($_GET['marcacion']) && $_GET['marcacion'] == 'E' ){echo 'selected';} ?>>ENTRADA</option>
					<option value="S" <?php if( isset($_GET['marcacion']) && !empty($_GET['marcacion']) && $_GET['marcacion'] == 'S' ){echo 'selected';} ?>>SALIDA</option>
				</select>
			</div>
		</div>
		<div class="input_and_btn">
			<div class="contInput2Item md" style="width: 120px;">
				<select class="input" id="mes_buscar" style="padding: 0px 5px;">
					<option value="0"> MES </option>
					<?php comboSelect_meses(); ?>
				</select>
			</div>
		</div>
		<div class="input_and_btn">
			<div class="contInput2Item md" style="width: 120px;">
				<select class="input" id="diahbl_buscar" style="padding: 0px 5px;">
					<option value="0"> DIA HABIL </option>
					<?php comboSelect_diasHabiles(); ?>
				</select>
			</div>
		</div>
		<div class="input_and_btn">
			<div class="contInput2Item md" style="width: 120px;">
				<select class="input" id="cargo_buscar" style="padding: 0px 5px;">
					<option value="All"> TODOS LOS CARGOS </option>
					<?php comboSelect_cargos(); ?>
				</select>
			</div>
		</div>
		<div class="input_and_btn">
			<div class="contInput2Item md">
				<div class="in_right">
					<input type="text" id="txt_buscar" placeholder="Buscar" style="width: 120px;"/>
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

<!-- Data table -->
<div class="caja">
	<table class="tabla3" cellspacing="0" id="resultados" width="96%">
		<tr id="thead">
	 		<th width="30px">#</th>
	 		<th width="105px;">CEDULA</th>
	 		<th>NOMBRE Y APELLIDO</th>
	 		<th>CARGO</th>
	 		<!-- <th>FUNCIÓN</th>
	 		<th>NIVEL</th> -->
	 		<th width="170px;">ASISTENCIA</th>
	 		<th width="120px;" id="th_hora">HORA</th>
	 		<th>OBSERVACIÓN</th>
	 		<th>ACCIONES</th>
	 	</tr>
	 	<?php listar_personal(); ?>
	 </table>
</div>

<div id="mostrando" class="left margin5rem text_gris" style="padding: 10px 0px"></div>
<div id="paginas" class="right margin5rem">
	<?php paginas(); ?>
</div>
<div class="clear"></div>


<script type="text/javascript" src="../JAVASCRIPT/asistencia_personal.js"></script>
