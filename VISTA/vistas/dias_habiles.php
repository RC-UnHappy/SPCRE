<?php include_once('../CONTROL/c_dias_habiles.php'); ?>
<div class="titulo_m">
	<h2>Días Hábiles (<?php echo $periodo; ?>) </h2><i class="icon-calendar"></i> 
</div>

<?php if( $aesc_activo == false ){ ?> 
<!-- No existe un año escolar activo -->
<div class="msj_lg">
	<i class="icon-attention rojo"></i><h3>Año escolar cerrado.</h3>
</div>
<p class="msj_error s_n2"><i class="icon-cancel-circled2"></i>Disculpe, En éste momento no es posible realizar ningún tipo de <b>operación</b> mientras no se encuentre un año escolar activo.</p>
<?php exit(); } ?>

<p class="marginB-2">
	Estimado usuario, si desea registrar los Días Hábiles proceda a seleccionar día por día las celdas en el calendario del año escolar y luego presione el botón <b class="text_azul">Guardar Cambios</b>. Una vez <b class="text_rosa">Finalizado</b> el año escolar no puede agregar ni modificar días hábiles.
</p>

<?php calendario(); ?>

<form name="formulario" method="POST" action="../CONTROL/c_dias_habiles.php">
	<input type="hidden" name="arrDias" value="<?php echo $DiasString; ?>">
	<input type="button" id="enviar" name="enviar" class="none">
	<div class="calendario">
	</div>
	<div class="right">
		<label for="enviar" class="btn btn_icon_split btn_normal btn_verde btn_md"><i class="icon-edit"></i><p>Guardar Cambios</p></label>
	</div>
	<div class="clear"></div>
</form>

<script type="text/javascript" src="../JAVASCRIPT/dias_habiles.js"></script>


