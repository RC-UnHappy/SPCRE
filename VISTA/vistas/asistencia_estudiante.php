<?php 
include_once('../CONTROL/c_asistencia_estudiante.php');
?>

<div class="titulo_m">
	<h2>Asistencia de Estudiantes<i class="icon-th-list"></i>
	<?php if( $codSec != '' ){ ?>
	<b class="text_azul"><?php echo $grado.'"'.$letra.'"'; ?></b> (<?php echo $periodoEsc; ?>)</h2>
	<?php } ?>
</div>

<?php 
if( $_SESSION['vsn_nivel'] != 4 && !isset($_GET['seccion'])){ # el usuario no es nivel docente
?>
<p class="marginB05rem">
	Estimado usuario, por favor ingrese el <b class="text_azul">Año escolar</b> y seleccione la <b class="text_rosa">sección</b>. 
</p>
<!-- Buscar sección  -->
<section>
	<form name="form_buscar" method="GET">
		<div class="contInput w200 left">
			<b>Año Escolar</b>
			<input type="text" name="aesc" class="input" maxlength="9" placeholder="Ej: 0000-0000" value="<?php echo $AESC; ?>" disabled/>
		</div>

		<div class="contInput w200 left">
			<b>Sección</b>
			<select name="seccion" class="input">
				<option value="0">SELECCIONAR</option>
				<?php opSecciones($codAESC); ?>
			</select>
		</div>
		<div class="contInput left">
			<p class="nomImput" style="height: 22px;"></p>
			<label class="btn btn_icon_split btn_normal btn_azul btn_lg" for="enviar">
				<p>Listar Estudiantes</p>
				<i class="icon-export"></i>
				<input type="button" id="enviar" name="enviar" class="none"/>
			</label>
		</div>
		<div class="clear"></div>

		<div id="mensaje" class="msj_error margin5rem animacion1 none">
			<i class="icon-attention tx_rojo"></i>Todos los campos son <b>requeridos</b>.
		</div>
	</form>
</section>
<?php 
	}if( $errorSeccion == true ){ 
?>

<!-- No se encontraron resultados -->
<div class="msj_lg">
	<i class="icon-attention rojo"></i><h3>Sin resultados.</h3>
</div>

<p class="msj_error s_n2"><i class="icon-cancel-circled2"></i>En éste momento no es posible obtener la lista de estudiantes en la <b>sección</b>. Usted debe tener asignada una <b>sección</b></p>


<?php 
	}if( $docenteSeccion == true ){ 
?>

<!-- 
<p class="marginB-2">
	Mostrando <b class="text_rosa">nómina</b> de estudiantes.
</p>
 -->
 <p class="marginB-2">
	Estimado usuario, si desea registrar las asistencias seleccione el <b>mes</b> y el <b>día hábil</b>, luego proceda a seleccionar las asistencias de los estudiantes y por último presione el botón <b class="text_azul">Guardar Cambios</b> para registrar las asistencias.
</p>

<div class="marginB05rem">
	<div class="left">
		<a class="btn btn_icon_split btn_normal btn_md"><p><b>Docente:</b> <?php echo $nom_docente.' <b>CI</b>: '.$ced_docente; ?></p></a>
	</div>
	<div class="right">
		<!-- buscar mes y dia habil -->
		<div class="input_and_btn">
			<div class="contInput2Item md" style="width: 180px;">
				<select class="input" id="mes_buscar" style="padding: 0px 5px;">
					<option value="0"> SELECCIONAR MES </option>
					<?php comboSelect_meses(); ?>
				</select>
			</div>
		</div>
		<div class="input_and_btn">
			<div class="contInput2Item md" style="width: 180px;">
				<select class="input" id="diahbl_buscar" style="padding: 0px 5px;">
					<option value="0"> DIA HABIL </option>
					<?php comboSelect_diasHabiles(); ?>
				</select>
			</div>
		</div>
		<input type="hidden" id="codSec" class="none" value="<?php echo $codSec; ?>">
	</div>
	<div class="clear"></div>
</div>

<form name="form_asistencia" method="POST" action="../CONTROL/c_asistencia_estudiante.php"> 
	<input type="hidden" name="mes">
	<input type="hidden" name="dia_habil">
	<input type="hidden" name="datos">
	<input type="hidden" name="seccion" value="<?php echo $codSec; ?>">
	<input type="button" class="none" id="enviar_form_asistencia">
</form>

<div class="caja marginB-2">
	<!-- tabla -->
	<table class="tabla3" cellspacing="0" id="resultados" width="96%">
		<tr id="trtotal">
			<th colspan="4">Estudiantes Inscritos: <?php echo $total; ?></th>	
		</tr>
		<tr id="thead">
			<th width="40px">#</th>
			<th width="180px">CI / CE</th>
			<th>NOMBRES Y APELLIDOS</th>
			<th width="200px">ASISTENCIA</th>
		</tr>
		<?php imprimir_lista( $listaEst ); ?>
	</table>
</div>


<label for="enviar_form_asistencia" class="btn_icon_split btn_verde btn_normal btn_md shadow-sm right">
	<i class="icon-edit"></i>
	<p>Registrar Asistencias</p>
</label>
<div class="clear"></div>
<?php 
	}
?>
<script type="text/javascript" src="../JAVASCRIPT/asistencia_estudiante.js"></script>