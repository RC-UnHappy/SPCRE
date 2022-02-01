<?php 
include_once('../CONTROL/c_lista_matricula.php');
?>

<?php 
if( $_SESSION['vsn_nivel'] != 4 && !isset($_GET['seccion'])){ # no es docente
?>
<!-- Buscar matrícula -->
<div class="titulo_m">
	<h2>Matrícula <i class="icon-th-list"></i></h2>
</div>
<p class="marginB05rem">
	Estimado usuario, por favor ingrese el <b class="text_azul">Año escolar</b> y seleccione la <b class="text_rosa">sección</b>. 
</p>

<form name="matricula" method="GET">
	<div class="contInput w200 left">
		<b>Año Escolar</b>
		<input type="text" name="aesc" class="input" maxlength="9" placeholder="Ej: 0000-0000" value="<?php echo $AESC; ?>" />
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
			<p>Consultar Matrícula</p>
			<i class="icon-export"></i>
			<input type="button" id="enviar" name="enviar" class="none"/>
		</label>
	</div>
	<div class="clear"></div>

	<div id="mensaje" class="msj_error margin5rem animacion1 none">
		<i class="icon-attention tx_rojo"></i>Todos los campos son <b>requeridos</b>.
	</div>
</form>
<script type="text/javascript" src="../JAVASCRIPT/l_matricula.js"></script>
<?php } ?>

<?php if( isset($_GET['resultados']) && $_GET['resultados'] == '0'){ ?>
<div class="titulo_m">
	<h2>Matrícula <i class="icon-th-list"></i></h2>
</div>
<div class="msj_lg">
	<i class="icon-attention rojo"></i><h3>Sin resultados.</h3>
</div>

<p class="msj_error s_n2"><i class="icon-cancel-circled2"></i>En éste momento no es posible obtener la lista de estudiantes en la <b>sección</b>. Usted debe tener asignada una <b>sección</b></p>
<?php }else if(!isset($_GET['resultados']) && isset($_GET['seccion'])){ ?>

<div class="titulo_m">
	<h2>Matrícula <i class="icon-th-list"></i> <b class="text_azul"><?php echo $grado.'"'.$letra.'"'; ?></b> (<?php echo $periodoEsc; ?>)</h2>
</div>

<p class="marginB-2">
	Mostrando <b class="text_rosa">nómina</b> de estudiantes.
</p>

<div class="marginB05rem">
	<div class="left">
		<a class="btn btn_icon_split btn_normal btn_md"><p><b>Docente:</b> <?php echo $nom_docente.' <b>CI</b>: '.$ced_docente; ?></p></a>
	</div>
	<div class="right">
		<!-- agregar indicadores -->
		<a href="?ver=indicadores&seccion=<?php echo $codSec; ?>&lapso=1" >
			<div class="btn btn_icon_split btn_normal btn_verde btn_md"><i class="icon-th-list"></i><p>Lista de Indicadores</p></div>
		</a>
	</div>
	<div class="clear"></div>
</div>

<div class="caja">
	<!-- tabla -->
	<table class="tabla3" cellspacing="0" id="resultados" width="96%">
		<tr>
			<th colspan="6">
				Estudiantes Inscritos: <?php echo $total; ?>	
			</th>	
		</tr>
		<tr id="thead">
			<th style="width: 1">#</th>
			<th style="width: 150px;">CI / CE</th>
			<th>NOMBRES Y APELLIDOS</th>
			<th width="50px">EDAD</th>
			<th width="50px">VER</th>
			<th width="50px">NOTAS</th>
		</tr>
		<?php listar_estudiantes(); ?>
	</table>
</div>
<?php 
	}
?>