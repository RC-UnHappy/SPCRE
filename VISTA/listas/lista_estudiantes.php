<!-- este archivo no se está utilizando -->
<div class="caja-top"> 
<div id="left">
	<div id="mostrar_filas" class="botones">
		<select>
			<option>Mostrar 15</option>
			<option>Mostrar 25</option>
			<option>Mostrar 40</option>
		</select>
	</div>
	<!-- boton agregar -->
	<a href="index.php?Registrar=Estudiante" class="botones" id="agregar">
		<i class="icon-plus"> </i>
		Registrar Estudiante
	</a>
</div>
<div id="rigth">
	<!-- buscar -->
	<div id="busqueda" class="cont_search">
		<input type="text" id="txt_buscar" placeholder="Buscar...">
		<label class="icon-search" for="btn_buscar">
		</label>
		<input type="button" id="btn_buscar" class="input_none">
	</div>	
</div>
<div id="clear"></div>
</div>

<div class="caja">
<h3 class="titulo_caja"><i class="icon-users"></i>Lista de Estudiantes</h3>

<table id="resultados" class="type-table1" cellspacing="0">
	<tr id="thead">
		<th style="width:40px">#</th>
		<th>Cédula</th>
		<th>Nombre</th>
		<th>Apellido</th>
		<th>Correo</th>
		<th>Teléfono</th>
		<th>Acciones</th>
	</tr>
</table>
</div>