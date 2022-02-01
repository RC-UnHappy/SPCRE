<?php  
	include_once('../CONTROL/c_notas.php');
?>
<div class="titulo_m">
	<h2>Notas</h2>
	<i class="icon-doc-text-inv"></i>
	<small>hola mundo</small><h2 style="margin-left: 10px;"><?php echo $gradoLetra; ?></h2>
</div>

<?php
	# No existe el año escolar o el año escolar está cerrado 
	if( $cod_AESC == '' || $sta_AESC == 'C' ){ 
?>
<div class="msj_lg">
	<i class="icon-attention rojo"></i><h3>No es posible cargar notas.</h3>
</div>
<p class="msj_error s_n2"><i class="icon-cancel-circled2"></i>Disculpe, en éste momento no es posible cargar <b>notas</b> mientras no se encuentre un año escolar activo.</p>
<?php 
	}else if( $aperturaNotas == false ){ 
?>
<div class="msj_lg">
	<i class="icon-attention rojo"></i><h3>No es posible cargar notas.</h3>
</div>
<p class="msj_error s_n2"><i class="icon-cancel-circled2"></i>Disculpe, en éste momento no es posible cargar <b>notas.</b> La apertura para la carga de notas se encuentra cerrada.
<?php 
	}else{ 
?>

<?php if( $lapso!='F' ){  # notas por lapso ?>

<?php  if( $eLapso == 'C'){ # Lapso cerrado ?>
<div id="msjEstatusLapso" class="msj_error margin5rem">
	<i class="icon-attention tx_rojo"></i> <b>Lapso</b> cerrado. Disculpe, en éste momento no es posible <b>cargar notas.</b>
</div>
<br/>

<?php }else if( $eLapso == 'N' ){ # Lapso no iniciado ?>
<div id="msjEstatusLapso" class="msj_error margin5rem">
	<i class="icon-attention tx_rojo"></i> <b>Lapso</b> no iniciado. Disculpe, en éste momento no es posible <b>cargar notas.</b>
</div>
<br/>	

<?php }if( $ePA == 'A' && $lapso != 'F' ){ # configuración de boletín aún abierta ?>
<div id="msjEstatusPA" class="msj_aviso margin5rem">
	<i class="icon-attention tx_rojo"></i> <b>Proyecto de aprendizaje</b> no iniciado. Disculpe, en éste momento no es posible <b>cargar notas</b> debido a que aún se encuentra abierta la configuración del <b>proyecto de aprendizaje</b> e <b>Indicadores</b>.
</div>
<br/>
<?php } ?>

<form name="form_notas" method="POST" action="../CONTROL/c_notas.php">
	<input type="hidden" name="fn"/>
	<input type="hidden" name="codInsc" value="<?php echo $codInsc; ?>" />
	<input type="hidden" name="cedEst" value="<?php echo $cedEst; ?>">
	<input type="hidden" name="ope"/>
	<input type="hidden" name="codLapso" value="<?php echo $codLapso; ?>"/>
	<input type="hidden" name="arrInd" value="<?php echo $arrInd; ?>"/>
	<input type="hidden" name="arrNota" value="<?php echo $arrNota; ?>"/>
	<input type="hidden" name="pm" value="<?php echo $pm; ?>"/>

	<div class="marginB05rem">
		<div>
			<h3>Proyecto de Aprendizaje: <b><?php echo $nomPA; ?></b></h3>
		</div>
		<br/>

		<div class="left">
			<div class="btn btn_icon_split btn_normal btn_md"><p><b>CI/CE:</b> <?php echo $cedEst.'<br> <b> Estudiante: </b>'.$nomEst.' '.$apeEst; ?></p></div>
		</div>
		<div class="right">
			<!-- buscar -->
			<div class="input_and_btn">
				<p>Notas:</p>
				<div class="contInput2Item md">
					<select id="lapso" class="text_center" name="lapso" style="width: 160px;border: none;">
						<option value="1" <?php if($lapso==1){echo 'selected';} ?>> Lapso 1 </option>
						<option value="2" <?php if($lapso==2){echo 'selected';} ?>> Lapso 2 </option>
						<option value="3" <?php if($lapso==3){echo 'selected';} ?>> Lapso 3 </option>
						<option value="F"<?php if($lapso=='F'){echo 'selected';} ?>> Final </option>
					</select>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>

	<div class="caja marginB05rem">
		<!-- tabla -->
		<table class="tabla3" cellspacing="0" id="resultados" width="96%">
			<tr id="thead">
				<th width="40px">#</th>
				<th>Indicador de Evaluación</th>
				<th width="120px">Nota</th>
			</tr>
			<?php crear_lista(); ?>
		</table>
	</div>

	<div>
		<div class="left">
			<div class="input_and_btn">
				<p>Promedio:</p>
				<select name="promedio" class="input text_center" style="width: 120px; padding: 5px; margin-right: 8px;" disabled>
					<option value="0" selected>---</option>
					<option value="A" <?php if($promedio == 'A'){echo 'selected';} ?>>A</option>
					<option value="B" <?php if($promedio == 'B'){echo 'selected';} ?>>B</option>
					<option value="C" <?php if($promedio == 'C'){echo 'selected';} ?>>C</option>
					<option value="D" <?php if($promedio == 'D'){echo 'selected';} ?>>D</option>
					<option value="E" <?php if($promedio == 'E'){echo 'selected';} ?>>E</option>
				</select>
				<?php  if( $operarNL == true ){ ?>
				<label id="btnPM" class="btn_icon_split btn_gris1 btn_normal btn_md shadow-sm" onclick="activarPM()">
					<i class="icon-edit"></i>
				</label>
				<?php } ?>
			</div>
		</div>
		<div class="right">
			<?php  
			if( $operarNL == true ){ 
				if( $sI == '1' && $sM == '1' ){
			?>
			<label for="btnEnviar" class="btn_icon_split btn_verde btn_normal btn_md shadow-sm">
				<i class="icon-edit"></i>
				<p>Guardar cambios</p>
			</label>
			<?php 
				} 
			}
			?>
			<input type="button" id="btnEnviar" name="enviar" class="none">
		</div>
		<div class="clear"></div>
	</div>
</form>
<?php } ?>

<?php if($lapso=='F'){ # Boletín Final ?>

<form name="form_notaFinal" method="POST" action="../CONTROL/c_notas.php">
	<input type="hidden" name="codInsc" value="<?php echo $codInsc; ?>" />
	<input type="hidden" name="codEst" value="<?php echo $codEst; ?>"/>
	<input type="hidden" name="cedEst" value="<?php echo $cedEst; ?>">
	<input type="hidden" name="aesc" value="<?php echo $cod_AESC; ?>" />
	<input type="hidden" name="opeBF"/>
	
	<div class="marginB05rem">
		<div class="left">
			<div class="btn btn_icon_split btn_normal btn_md"><p><b>CI/CE:</b> <?php echo $cedEst.'<br> <b> Estudiante: </b>'.$nomEst.' '.$apeEst; ?></p></div>
		</div>
		<div class="right">
			<!-- buscar -->
			<div class="input_and_btn">
				<p>Notas:</p>
				<div class="contInput2Item md">
					<select id="lapso" class="text_center" name="lapso" style="width: 160px;border: none;">
						<option value="1" <?php if($lapso==1){echo 'selected';} ?>> Lapso 1 </option>
						<option value="2" <?php if($lapso==2){echo 'selected';} ?>> Lapso 2 </option>
						<option value="3" <?php if($lapso==3){echo 'selected';} ?>> Lapso 3 </option>
						<option value="F" <?php if($lapso=='F'){echo 'selected';} ?>> Final </option>
					</select>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	
	<!-- aqui -->
	<?php if( $staLAPSOS == 'false' ){ ?>
		<br/>
		<div id="msjEstatusLapso" class="msj_error margin5rem">
			<i class="icon-attention tx_rojo"></i> Disculpe, en éste momento no es posible <b>cargar notas.</b> Antes deben finalizar los 3 <b>lapsos</b>.
		</div>
	<?php 
		}else{
	?>
	<div class="cajaH_auto" style="margin-top: 30px;">
		<p class="text_size1 text_gris s_n "><b class="text_bold">Nota: </b> Los campos marcados con "<b class="text_bold text_azul icon-attention-circled"></b>" Son requeridos.</p>
		<div class="caja margin5rem">
			<h3>Boletín Descriptivo</h3>
			<div id="msjfnf" class="msj_error margin5rem none">
				<i class="icon-attention tx_rojo"></i> <b>Error:</b> Los campos marcados con "<b class="text_bold text_azul icon-attention-circled"></b>" son <b>requeridos.</b></p>
			</div>
			<!-- Descripcion -->
			<div class="contInput" style="width: 800px;">
				<p class="nomInput"><i class="icon-attention-circled"></i>Descripción</p>
				<textarea rows="4" cols="66" name="desc" class="input" style="min-height: 400px; max-height: 400px; justify-content: left;"><?php echo $desc; ?></textarea>
			</div>
			<!-- Recomendación -->
			<div class="contInput" style="width: 800px;">
				<p class="nomInput"><i class="icon-attention-circled"></i>Recomendación</p>
				<textarea name="rec" class="input" style="min-height: 120px; max-height: 120px; justify-content: left;"><?php echo $reco; ?></textarea>
			</div>
		</div>

		<!-- ABAJO -->
		<div>
			<div class="left">
				<div class="input_and_btn">
					<i class="icon-attention-circled" style="color: var(--azul1);"></i>
					<p>Literal:</p>
					<div class="contInput2Item md" style="margin-right: 20px;">
						<select name="literal" class="input" style="width: 120px;">
							<option value="0">---</option>
							<option value="A" <?php if($literal=='A'){echo 'selected';}?>>A</option>
							<option value="B" <?php if($literal=='B'){echo 'selected';}?>>B</option>
							<option value="C" <?php if($literal=='C'){echo 'selected';}?>>C</option>
							<option value="D" <?php if($literal=='D'){echo 'selected';}?>>D</option>
							<option value="E" <?php if($literal=='E'){echo 'selected';}?>>E</option>
						</select>
					</div>
				</div>
				Promovido <input type="radio" name="prom" value="S" <?php echo $promoSI; ?> disabled>
				No promovido <input type="radio" name="prom" value="N" <?php echo $promoNO; ?> disabled>
			</div>
			<div class="right">
				<?php 
				if( $operarNF == true ){ 
					if( $poseeBF==0 ){ 
						if( $sI == '1' ){
				?>
				<!-- boton registrar -->
				<label for="enviarReg" class="btn btn_icon_split btn_normal btn_verde btn_md" for="btn_cons_matricula">
					<i class="icon-check"></i>
					<p>Registrar boletín</p>
					<input type="submit" id="btn_cons_matricula" name="enviar" class="none"/>
				</label>
				<input type="button" id="enviarReg" name="enviarReg" class="none" />
				<?php 
						}
					}
					else{ 
						if( $sM == '1' ){
				?>
				<!-- boton modificar -->
				<label for="enviarMod" class="btn btn_icon_split btn_normal btn_verde btn_md" for="btn_cons_matricula">
					<i class="icon-edit"></i>
					<p>Guardar cambios</p>
					<input type="submit" id="btn_cons_matricula" name="enviar" class="none"/>
				</label>
				<input type="button" id="enviarMod" name="enviarMod" class="none" />
				<?php
						}
					} 
				}
				?>
			</div>
			<div class="clear"></div>
		</div>		
	</div>
	<?php } ?>
</form>
<?php 
	}
}
?>
<script type="text/javascript" src="../JAVASCRIPT/f_notas.js"></script>