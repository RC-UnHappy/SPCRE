<?php
include_once('../CONTROL/c_seccion.php');  # controlador seccion
?>

<div class="titulo_m">
	<h2 id="tlt_seccion">Niveles <?php echo '(' . $perAesc . ')'; ?></h2><i class="icon-th-list"></i>
</div>
<p class="marginB-2">
	Estimado usuario, si desea agregar un nuevo nivel presione el botón <b class="text_azul">Nuevo nivel</b> y proceda a llenar los datos del formulario. <b>Importante: </b>Una vez <b class="text_rosa">Finalizado</b> el año escolar no puede agregar un nuevo nivel ni modificar datos.
</p>
<?php
if ($estatusAesc == 'C') {
?>
	<!-- mensaje -->
	<div class="msj_lg">
		<i class="icon-attention rojo"></i>
		<h3>Año escolar finalizado</h3>
	</div>
<?php
}
?>
<div class="marginB05rem">
	<div class="left">
		<!-- agregar -->
		<?php
		if ($estatusAesc == 'A') {
			if ($sI == '1') {
		?>
				<div class="btn btn_icon_split btn_normal btn_verde btn_md" id="open-W-form"><i class="icon-plus"></i>
					<p>Nuevo Nivel</p>
				</div>
		<?php }
		} ?>
	</div>
	<div class="right">
		<!-- buscar -->
		<?php if ($sC == '1') { ?>
			<div class="input_and_btn">
				<p>Año escolar</p>
				<div class="contInput2Item md">
					<div class="in_right">
						<input type="text" id="txt_buscar" placeholder="Ej: 2018-2019" class="text_center" size="13" maxlength="9" />
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
			<th style="width: 40px;">#</th>
			<th>GRADO</th>
			<th>NIVEL</th>
			<th>ESTUDIANTES</th>
			<th>DOCENTE</th>
			<th>AULA</th>
			<th>CUPOS</th>
			<th>ACCIONES</th>
		</tr>
		<?php crea_lista(); ?>
	</table>

	<!-- Formulario se agrega a la ventana ver: ventanas.js -->
	<div id="form-seccion" class="W-form">
		<form name="f_seccion" method="POST" action="../CONTROL/c_seccion.php">
			<input type="hidden" name="ope" />
			<input type="hidden" name="codAesc" value="<?php echo $codAesc; ?>" />
			<input type="hidden" name="codDoc" />
			<input type="hidden" name="codSec" />
			<input type="hidden" name="codAula" />

			<div class="W-top">
				<h3 class="W-nom" id="W-nom"><i class="icon-plus"></i>Nuevo Nivel</h3>
				<label for="close-W-form" class="icon-cancel"></label>
				<input type="button" id="close-W-form" class="none" />
				<div class="clear"></div>
			</div>

			<div class="W-body">
				<p><b>Importante:</b> Todos los campos son requeridos.</p>
				<div class="row">
					<div class="col col-250px">
						<!-- año escolar -->
						<div class="contInput">
							<div class="nomInput"><i class="icon-attention-circled"></i>Año Escolar</div>
							<input type="text" name="aesc" value="<?php echo $perAesc; ?>" class="input text_center" disabled="true" />
							<div class="msjBox animacion1"></div>
						</div>
					</div>

					<div class="col col-250px">
						<!-- docente -->
						<div class="contInput">
							<div class="nomInput"><i class="icon-attention-circled"></i>Docente de Aula</div>
							<select name="docente" class="input">
								<option value="0">SELECCIONAR</option>
								<?php listar_docentesDisp(); ?>
							</select>
							<div class="msjBox animacion1"></div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col col-250px">
						<!-- grado  -->
						<div class="contInput">
							<div class="nomInput"><i class="icon-attention-circled"></i>Grado</div>
							<select name="grado" class="input">
								<option value="0">SELECCIONAR</option>
								<option value="1">1er Grado</option>
								<option value="2">2do Grado</option>
								<option value="3">3er Grado</option>
								<option value="4">4to Grado</option>
								<option value="5">5to Grado</option>
								<option value="6">6to Grado</option>
							</select>
							<div class="msjBox animacion1"></div>
						</div>
					</div>
					<div class="col col-250px">
						<!-- seccion -->
						<div class="contInput">
							<div class="nomInput"><i class="icon-attention-circled"></i>Nivel</div>
							<select name="seccion" class="input">
								<option value="0">SELECCIONAR</option>
								<option value="A">Nivel A</option>
								<option value="B">Nivel B</option>
								<option value="C">Nivel C</option>
								<option value="D">Nivel D</option>
								<option value="E">Nivel E</option>
								<option value="F">Nivel F</option>
								<option value="G">Nivel G</option>
								<option value="H">Nivel H</option>
								<option value="I">Nivel I</option>
							</select>
							<div class="msjBox animacion1"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col col-250px">
						<!-- Aula -->
						<div class="contInput">
							<div class="nomInput"><i class="icon-attention-circled"></i>Aula</div>
							<select name="aula" class="input">
								<option value="0">SELECCIONAR</option>
								<?php aulas_op(); ?>
							</select>
							<div class="msjBox animacion1"></div>
						</div>
					</div>
					<div class="col col-250px">
						<!-- Cupos -->
						<div class="contInput">
							<div class="nomInput"><i class="icon-attention-circled"></i>Cupos</div>
							<input type="text" maxlength="2" name="cupos" class="input text_center" />
							<div class="msjBox animacion1"></div>
						</div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<!-- botones -->
			<div class="W-bottom">
				<label for="enviar" class="btn btn_icon_split btn_normal btn_verde btn_md" id="boton_enviar"><i class="icon-plus"></i>
					<p>Agregar</p>
				</label>
				<label for="close-W-form" class="btn btn_icon_split btn_normal btn_gris2 btn_md">
					<p>Cancelar</p>
				</label>
				<input type="button" id="enviar" name="enviar" class="none" />
			</div>
		</form>
	</div>
</div>
<div class="none" id="DOM_aulasD">
	<?php aulas_op(); ?>
</div>
<script type="text/javascript" src="../JAVASCRIPT/l_seccion.js"></script>