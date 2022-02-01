<?php 
	include_once('../CONTROL/c_reposo_personal.php');  # controlador seccion
?>

<div class="titulo_m">
	<h2 id="tlt_seccion">Reposo <?php #echo '('.$perAesc.')'; ?></h2><i class="icon-th-list"></i>
</div>

<p class="marginB-2">
	Estimado usuario, si desea agregar una nuevo <b>reposo</b> presione el botón <b class="text_azul">Nuevo reposo</b> y proceda a llenar los datos del personal en el formulario.
</p>

<div class="marginB05rem">
	<div class="left">
		<!-- agregar -->
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
		<div class="btn btn_icon_split btn_normal btn_verde btn_md" id="open-W-form"><i class="icon-plus"></i><p>Nuevo Reposo</p></div>
	</div>

	<div class="right">
		<!-- buscar -->
		<?php #if( $sC == '1' ){ ?>
		<div class="right contInput2Item md">
			<div class="in_right">
				<input type="text" id="text_buscar" placeholder="Buscar"/>
				<label for="btn_buscar" class="btn btn_gris2">
					<i class="icon-search"></i>
					<input type="button" id="btn_buscar" class="none">
				</label>
			</div>				
		</div>
		<?php #} ?>
	</div>
	<div class="clear"></div>
</div>

<div class="caja">
	<!-- tabla -->
	<table class="tabla3" cellspacing="0" id="resultados" width="96%">
		<tr id="thead">
			<th style="width: 40px;">#</th>
			<th>CÉDULA</th>
			<th>NOMBRE Y APELLIDO</th>
			<th>CARGO</th>
			<th>DESDE</th>
			<th>HASTA</th>
			<th>DESCRIPCIÓN</th>
			<th>ESTADO</th>
			<th>ACCIONES</th>
		</tr>
		<?php listar_reposos(); ?>
	</table>

	<!-- Formulario se agrega a la ventana ver: ventanas.js -->
	<div id="ventana_formulario" class="W-form">
		<form name="formulario" method="POST" action="../CONTROL/c_reposo_personal.php">
			<input type="hidden" name="ope" value="reg" />
			<input type="hidden" name="codrep" />

			<div class="W-top">
				 <h3 class="W-nom" id="W-nom"><i class="icon-th-list"></i>Reposo</h3>
				 <label for="close-W-form" class="icon-cancel"></label>
				 <input type="button" id="close-W-form" class="none" />
				 <div class="clear"></div>
			</div>

			<div class="W-body">
				<p><b>Importante: </b>Los campos marcados con <i class="icon-attention-circled"></i> son requeridos.</p>
				<div class="row">
					<div class="col col-250px">
						<div class="contInput">
							<div class="nomInput"><i class="icon-attention-circled"></i>Cédula de Identidad</div>
							<div class="contInput2Item">
								<div class="in_left" style="width: 60px;">
									<select name="tipo_doc">
										<option value="V">V</option>
										<option value="E">E</option>
									</select>
									<i class="icon-down-dir"></i>
								</div>
								<div class="in_right">
									<input type="text" name="cedula"  maxlength="8" placeholder="Ej: 00000000" class="input"/>
								</div>
							</div>
							<div class="msjBox anm1"></div>
						</div>
					</div>

					<div class="col col-250px">		
						<!-- nombre y apellido -->
						<div class="contInput">
							<div class="nomInput">Nombre y apellido</div>
							<input type="text" name="nom_ape" class="input" readonly="true"/>
							<div class="msjBox anm1"></div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col col-250px">
						<!-- cargo -->
						<div class="contInput">
							<div class="nomInput">Cargo</div>
							<input type="text" name="cargo" class="input" readonly="true"/>
						</div>
					</div>
					<div class="col col-250px">
						<!-- cantidad de dias -->
						<div class="contInput">
							<div class="nomInput"><i class="icon-attention-circled"></i>Cantidad de Dias</div>
							<input type="text" name="dias" class="input text_center" maxlength="3" />
							<div class="msjBox anm1"></div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col col-250px">
						<!-- fecha desde  -->
						<div class="contInput">
							<div class="nomInput"><i class="icon-attention-circled"></i>Desde</div>
							<input type="date" name="fecha_desde" class="input"/>
							<div class="msjBox anm1"></div>
						</div>
					</div>
					<div class="col col-250px">
						<!-- fecha hasta -->
						<div class="contInput">
							<div class="nomInput"><i class="icon-attention-circled"></i>Hasta</div>
							<input type="date" name="fecha_hasta" class="input"/>
							<div class="msjBox anm1"></div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col col100">
						<div class="nomInput"><i class="icon-attention-circled"></i>Descripcion o Motivo</div>
							<input type="text" name="descripcion" class="input"/>
						<div class="msjBox anm1"></div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<!-- botones -->
			<div class="W-bottom">
				<label for="enviar" class="btn btn_icon_split btn_normal btn_verde btn_md" id="boton_enviar"><i class="icon-plus"></i><p>Registrar</p></label>
				<label for="close-W-form" class="btn btn_icon_split btn_normal btn_gris2 btn_md"><p>Cancelar</p></label>
				<input type="button" id="enviar" name="enviar" class="none" />
			</div>
		</form>
	</div>
</div>
<script type="text/javascript" src="../JAVASCRIPT/reposo_personal.js"></script>
