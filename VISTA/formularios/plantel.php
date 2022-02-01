<?php 
	include_once('../CONTROL/c_plantel.php'); 
?>
<div class="titulo_m">
	<h2>Plantel</h2><i class="icon-bank"></i>
</div>

<form name="plantel" method="POST" enctype="multipart/form-data" action="../CONTROL/c_plantel.php">
	<input type="hidden" name="ope"/>
	<input type="button" id="enviar" name="enviar" class="none"/>
	<input type="file" id="image" name="image" class="none"/>

	<div class="cajaH_auto">	
		<div class="caja marginB-1">
			<h3>Datos del Plantel</h3>
			<div class="pd_LR_30px">
				<div class="row">
					<div class="col col-250px">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Código del Plantel</p>
							<input type="text" name="codplantel" class="input" value="<?php echo $codplantel; ?>"/>
						</div>
					</div>
					<div class="col col-250px">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Código DEA</p>
							<input type="text" name="coddea" class="input" value="<?php echo $coddea; ?>"/>
						</div>
					</div>
					<div class="col col-250px">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Código Estadístico</p>
							<input type="text" name="codestco" class="input" value="<?php echo $codestco; ?>"/>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col col-250px">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Zona Educativa</p>
							<input type="text" name="zonaeduc" class="input" value="<?php echo $zonaeduc; ?>"/>
						</div>
					</div>
					<div class="col col-250px">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Estado</p>
							<input type="text" name="edo" class="input" value="<?php echo $edo; ?>"/>
						</div>
					</div>
					<div class="col col-250px">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Municipio</p>
							<input type="text" name="mun" class="input" value="<?php echo $mun; ?>"/>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col col-250px">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Director(a)</p>
							<select name="director" class="input">
								<option value="0">SELECCIONAR DIRECTOR</option>
								<?php op_directores($director); ?>
							</select>
						</div>
					</div>
					<div class="col col-500px">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Nombre de la unidad educativa</p>
							<input type="text" name="nombre" class="input" value="<?php echo $nombre; ?>"/>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col col100">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Dirección de la unidad educativa</p>
							<input type="text" name="direccion" class="input" value="<?php echo htmlspecialchars($direccion); ?>"/>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col col-250px">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Teléfono</p>
							<input type="text" class="input" name="tlfn" value="<?php echo $telefono; ?>" />
						</div>
					</div>
					<div class="col col-250px">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Correo electrónico</p>
							<input type="text" class="input" name="correo" value="<?php echo $correo; ?>" />
						</div>
					</div>
				</div>
				<div class="row padding_LR_05">
					<div class="col col100 s_n">
						<b class="nomInput"><i class="icon-attention-circled"></i>Encabezado</b>
						<label for="image">
							<div style="height: 60px; border: solid 1px var(--gris4); cursor: pointer;">
								<img id="imgPrev" src="../IMG/cintillo.jpg" style="width: 100%" height="58px"/>
							</div>
						</label>
					</div>
					<div class="clear"></div>
				</div>
				<div class="row padding_LR_05">
					<p class="msj_error tx_sm none animacion1" style="width: 100%"></p>
				</div>
			</div>
		</div>
		<?php if($sM=='1'){ ?>
		<label for="enviar" class="btn_icon_split btn_verde btn_normal btn_md shadow-sm right">
			<i class="icon-check"></i>
			<p>Guardar cambios</p>
		</label>
		<?php } ?>
	</div>
</form>
<script type="text/javascript" src="../JAVASCRIPT/plantel.js"></script>
<br><br>
