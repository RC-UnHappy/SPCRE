<?php include_once('../CONTROL/c_indicadores.php'); ?>

<?php if( isset($_GET['error']) && $_GET['error'] == '2'){ ?>
<div class="titulo_m">
	<h2>Indicadores <i class="icon-th-list"></i></h2>
</div>
<div class="msj_lg">
	<i class="icon-attention rojo"></i><h3>Sin resultados.</h3>
</div>
<p class="msj_error s_n2"><i class="icon-cancel-circled2"></i>En éste momento no es posible gestionar <b>Indicadores</b>. Usted debe tener asignada una <b>sección</b></p>
<?php }else{ ?>

<section class="secciones none">
	<div class="titulo_m">
		<h2>Indicadores <i class="icon-th-list"></i></h2>
	</div>

	<p class="marginB05rem">
		Estimado usuario, por favor ingrese el <b class="text_azul">Año escolar</b> y seleccione la <b class="text_rosa">sección</b>. 
	</p>

	<form name="fbuscar" method="GET" action="../CONTROL/c_indicadores.php">
		<div class="contInput w200 left">
			<b>Año Escolar</b>
			<input type="text" name="aesc" class="input" maxlength="9" placeholder="Ej: 0000-0000" value="<?php echo $AESC; ?>" />
		</div>

		<div class="contInput w200 left">
			<b>Sección</b>
			<select name="seccion" class="input">
				<option value="0">SELECCIONAR</option>
				<?php imprimir_sec($codAESC); ?>
			</select>
		</div>
		<div class="contInput left">
			<p class="nomImput" style="height: 22px;"></p>
			<label class="btn btn_icon_split btn_normal btn_azul btn_lg" for="enviar">
				<p>Consultar Indicadores</p>
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

<section class="secciones none">
	<div class="titulo_m">
		<h2>Indicadores <i class="icon-th-list"></i> <b class="text_azul"><?php echo $grado.'"'.$letra.'"'?></b> (<?php echo $periodoEsc; ?>) <b class="text_rosa">Lapso: "<?php echo $GETlapso;?>" </b></h2>
	</div>

	<?php  if( $eLapso == 'C'){ ?>
	<div id="msjEstatusLapso" class="msj_error margin5rem">
		<i class="icon-attention tx_rojo"></i> <b>Lapso</b> cerrado. Disculpe, en éste momento no es posible <b>agregar</b> ni <b>modificar</b> indicadores.
	</div>
	<?php } ?>
	<?php  if($ePA == 'C'){ ?>
	<div id="msjEstatusLapso" class="msj_aviso margin5rem">
		<i class="icon-attention tx_rojo"></i> <b>Boletín cerrado.</b> Disculpe, no es posible <b>agregar</b> ni <b>modificar</b> indicadores.
	</div>
	<?php } ?>

	<!-- Formulario Indicadores -->
	<form name="f_indicador" method="POST" action="../CONTROL/c_indicadores.php">
		<input type="hidden" name="seccion" value="<?php echo $codSec; ?>"/>
		<input type="hidden" name="codLapso" value="<?php echo $codLapso; ?>"/>
		<input type="hidden" name="codPA" value="<?php echo $codPA; ?>"/>
		<input type="hidden" name="opePA"/>
		<input type="hidden" name="opeIND"/>
		<input type="hidden" name="codIND"/>
		<input type="hidden" name="hNameInd"/>

		<input type="hidden" name="cerrarIndH"/>
		<input type="button" id="cerrarIndBtn" name="cerrarIndBtn" class="none" />

		<!-- LAPSO -->
		<div class="marginB-2">
			<div class="col-250px">
				<div class="contInput">
					<div class="nomInput"><i class="icon-attention-circled"></i>Seleccionar lapso</div>
					<select name="lapso" class="input">
						<option value="1" <?php if($GETlapso == '1'){echo 'selected';}?>>LAPSO 1</option>
						<option value="2" <?php if($GETlapso == '2'){echo 'selected';}?>>LAPSO 2</option>
						<option value="3" <?php if($GETlapso == '3'){echo 'selected';}?>>LAPSO 3</option>
					</select>
				</div>
			</div>	
		</div>
		<div class="clear"></div>

		<!-- PROYECTO DE APRENDIZAJE -->
		<div class="caja padding_B_05 marginB05rem">
			<h3>Proyecto de Aprendizaje</h3>
			<!-- Nombre PA -->
			<div class="row">
				<div class="col col75">
					<div class="contInput">
						<div class="nomInput"><i class="icon-attention-circled"></i>Nombre del P.A</div>
						<input type="text" name="nomPA" class="input" value="<?php echo $nomPA; ?>" <?php echo $modoInput; ?>/>
						<div class="msjBox anm1"></div>
					</div>
				</div>
				<div class="col col25">
					<div class="contInput">
						<div class="nomInput"><i class="icon-attention-circled"></i>Tiempo de Ejecución</div>
						<input type="text" name="tiempoPA" class="input" value="<?php echo $tiempoPA; ?>" <?php echo $modoInput; ?>/>
						<div class="msjBox anm1"></div>
					</div>
				</div>
			</div>
		</div>

		<div class="right">
			<?php  if( $eLapso != 'C' && $ePA != 'C' ){ ?>
				<?php 
				if( $codPA == '' ){ # No existe un registro
					if( $sI == '1' ){ # seguridad/incluir
				?>
				<label for="btnREG_PA" class="btn btn_icon_split btn_normal btn_verde btn_md"><i class="icon-check"></i><p>Registrar PA</p></label>
				<input type="button" id="btnREG_PA" name="btnREG_PA" class="none">
				<?php 
					}
				}else{
					if( $sM == '1' ){
				?>
				<label for="btnMOD_PA" class="btn btn_icon_split btn_normal btn_verde btn_md"><i class="icon-edit"></i><p>Guardar cambios</p></label>
				<input type="button" id="btnMOD_PA" name="btnMOD_PA" class="none">
				<?php } } ?>
			<?php } ?>
		</div>
		<div class="clear"></div>
		
		<!-- INDICADORES DE EVALUACIÓN -->
		<div id="ventana_indicadores" class="W-form">
			<div class="W-top">
				 <h3 class="W-nom" id="W-nom"><i class="icon-plus"></i>Indicadores de Evaluación</h3>
				 <label for="close-W-form" class="icon-cancel"></label>
				 <input type="button" id="close-W-form" class="none" />
				 <div class="clear"></div>
			</div>
			<div class="W-body">
				<div class="row">
					<div style="width: 840px;">
						<!-- nombre -->
						<div class="contInput">
							<div class="nomInput"><i class="icon-attention-circled"></i> Nombre del Indicador: </div>
							<input type="text" id="nomInd" name="nombreInd" class="input" placeholder="Escriba el nombre" >
							<div class="msjBox anm1"></div>
						</div>
					</div>
				</div>
			</div>
			<!-- botones -->
			<div class="W-bottom">
				<label id="btnINDreg" for="regIND" class="btn btn_icon_split btn_normal btn_verde btn_md"><i class="icon-plus"></i><p>Agregar</p></label>
				<label id="btnINDmod" for="modIND" class="btn btn_icon_split btn_normal btn_verde btn_md none"><i class="icon-edit"></i><p>Guardar Cambios</p></label>
				<label for="close-W-form" class="btn btn_icon_split btn_normal btn_gris2 btn_md"><p>Cancelar</p></label>
				<input type="button" id="regIND" name="regIND" class="none"/>
				<input type="button" id="modIND" name="modIND" class="none"/>
			</div>
		</div>

		<div class="division" style="margin-top: 50px; margin-bottom: 50px;"></div>

		<?php  
		if( $eLapso != 'C' && $ePA != 'C' ){ 
			if( $codPA != '' ){
				if( $sI == '1' ){
		?>
		<div class="marginB05rem right">
			<label id="open-W-form" class="btn btn_icon_split btn_normal btn_verde btn_md"><i class="icon-plus"></i><p>Agregar Indicador</p></label>
		</div>
		<div class="clear"></div>
		<?php } } }?>
		
		<!-- TABLA -->
		<div class="caja marginB05rem ">
			<!-- <h3>Indicadores de Evaluación</h3> -->
			<table class="tabla3" cellspacing="0" id="resultados" width="96%">
				<tr id="thead">
					<th style="width:5px;">#</th>
					<th>INDICADOR</th>
					<th style="width: 100px;">ACCIONES</th>
				</tr>
				<?php listar_indicadores(); ?>
			</table>
		</div>

		<?php 
		if( $codPA != ''){ # Aún no existe un proyecto de aprendizaje
			if( $eLapso != 'C' ){ # Lapso cerrado, no puede modificar
				if( $ePA != 'C'){ # Boletín cerrado, no se puede volver a abrir
					if( $sM == '1' ){
		?>
					<label for="cerrarIndBtn" class="right btn btn_icon_split btn_normal btn_amarillo btn_md"><i class="icon-block"></i><p>Cerrar Boletín del lapso</p></label>
		<?php }}}} ?>
	</form>
</section>
<script type="text/javascript" src="../JAVASCRIPT/l_indicadores.js"></script>
<?php } ?>
