<?php 
	include_once('../CONTROL/c_representante.php');
?>
<!-- Titulo de página -->
<div class="titulo_m">
	<h2>Representante</h2>
	<i class="icon-user"></i>
</div>

<!-- SECCION 1 CONSULTAR REPRESENTANTE -->
<section class="marginB-3 none">
	<div class="marginB-2">
		<p>Estimado usuario, si desea consultar debe llenar el campo con la <b class="text_azul">cédula de identidad</b>
		de la persona. Si desea registrar un nuevo representante, presione el botón <b class="text_azul">Registrar Representante.</b></p>
	</div>

	<div class="marginB-1">
		<div class="left">
			<?php if( $sC == '1' ){ ?>
			<!-- Formulario consultar representante -->
			<div class="input_and_btn">
				<p>Tipo de documento</p>
				<div class="contInput2Item">
					<!-- tipo de documento -->
					<div class="in_left">
						<select id="tipo_doc">
							<option value="V">V</option>
							<option value="E">E</option>
						</select>
						<i class="icon-down-dir"></i>
					</div>
					<!-- cedula y boton search -->
					<div class="in_right w250">
						<input type="text" id="buscar" placeholder="Ej: 99999999" maxlength="8" size="13" />
						<label for="btnBuscarRep" class="btn btn_gris2">
							<i class="icon-search"></i>
							<input type="button" id="btnBuscarRep" class="none">
						</label>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
		<!-- boton verde (Registrar Representante) -->
		<?php if( $sI == '1' ){ ?>
		<div id="btn_registrar" class="right">
			<a href="?Representante=registrar" class="btn_icon_split btn_verde btn_normal btn_lg shadow-sm">
				<i class="icon-user-plus"></i>
				<p> Registrar Representante </p>
			</a>
		</div>
		<?php } ?>
		<div class="clear"></div>
	</div>
</section>

<!-- SECCION 2 FORMULARIO REPRESENTANTE -->
<section class="none">
	<p id="p1" class="marginB-3 none">
		Antes de <b class="text_azul">registrar a la persona</b> por favor asegúrese de que la <b>cédula</b> y el <b>tipo de documento</b> es el mismo que introdújo anteriormente.
		Los datos deben ser reales.
	</p>
	
	<form name="representante" method="POST" enctype="multipart/form-data" action="../CONTROL/c_representante.php">
		<input type="hidden" name="opeRep"/>
		<input type="hidden" name="codPer" value="<?php echo $codPer; ?>" />
		<input type="hidden" name="ciPer" value="<?php echo $ciPer; ?>" />
		<input type="hidden" name="personal" value="<?php echo $personal; ?>" />

		<div class="cajaH_auto">
			<p class="text_gris text_size1 marginB05rem">
				<b>Nota:</b> Los campos marcados con "<b class="text_azul icon-attention-circled"></b>" Son requeridos.
			</p>
		
			<!-- Datos Personales -->
			<div class="caja marginB-1">
				<h3> Identidad del Representante </h3>
				<div class="pd_LR_30px">
					<div class="row">
						<div class="col col-250px">
							<div class="contInput foto">
								<div class="contFoto">
									<img id="foto_previa" src="<?php echo $url_foto; ?>">
								</div>
								<!-- boton subir foto -->
								<label for="input_foto" id="btn_subirFoto">
									<div class="btn btn_icon_split_w100 btn_gris1 btn_md">
										<i class="icon-camera"></i>
										<p>Seleccionar</p>
									</div>
								</label>
								<input type="file" id="input_foto" name="foto" class="none">
							</div>
						</div>
					
						<div class="col">
							<div class="row">
								<!-- cedula -->
								<div class="col col-250px">
									<div class="contInput">
										<div class="nomInput"><i class="icon-attention-circled"></i>Cédula de Identidad</div>
										<div class="contInput2Item">
											<div class="in_left" style="width: 60px;">
												<select name="tipo_doc">
													<option value="V"<?php if($tipoDoc == 'V'){echo 'selected';} ?>>V</option>
													<option value="E"<?php if($tipoDoc == 'E'){echo 'selected';} ?>>E</option>
												</select>
												<i class="icon-down-dir"></i>
											</div>
											<div class="in_right">
												<input type="text" name="ced"  maxlength="8" value="<?php echo $ced; ?>" placeholder="Ej: 00000000" class="input"/>
											</div>
										</div>
										<div class="msjBox anm1"></div>
									</div>
								</div>
								<!-- nacionalidad -->
								<div class="col col-250px">
									<div class="contInput">
										<p class="nomInput"><i class="icon-attention-circled"></i>Nacionalidad</p>
										<select name="nac" class="input">
											<option value="V" <?php if($nac=='V'){echo 'selected';}?>>VENEZOLANA</option>
											<option value="E" <?php if($nac=='E'){echo 'selected';}?>>EXTRANJERA</option>
										</select>
									</div>
								</div>
								<div class="clear"></div>
							</div>
							<div class="row">
								<!-- Nombre -->
								<div class="col col-250px">
									<div class="contInput">
										<p class="nomInput"><i class="icon-attention-circled"></i>Nombre</p>
										<input type="text" name="nom" placeholder="Escriba el nombre" value="<?php echo $nom; ?>" class="input"/>
										<div class="msjBox animacion1"></div>
									</div>
								</div>
								
								<!-- apellido -->
								<div class="col col-250px">
									<div class="contInput">
										<p class="nomInput"><i class="icon-attention-circled"></i>Apellido</p>
										<input type="text" name="ape" placeholder="Escriba el apellido" value="<?php echo $ape; ?>"/ class="input">
										<div class="msjBox animacion1"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<!-- Sexo -->
								<div class="col-250px">
									<div class="contInput">
										<p class="nomInput"><i class="icon-attention-circled"></i>Género</p>
										<select name="sex" class="input">
											<option value="0"> SELECCIONAR </option>
											<option value="M" <?php if($sex=='M'){echo 'selected';}?>>MASCULINO</option>
											<option value="F" <?php if($sex=='F'){echo 'selected';}?>>FEMENINO</option>	
										</select>
										<div class="msjBox animacion1"></div>
									</div>
								</div>
								<div class="col col-250px">
									<div class="contInput">
										<p class="nomInput"><i class="icon-attention-circled"></i>Fecha de Nacimiento</p>
										<input type="date" name="fnac" value="<?php echo $fnac; ?>" class="input"/>
										<div class="msjBox animacion1"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">	
						<!-- Email -->			
						<div class="col-250px">
							<div class="contInput">
								<p class="nomInput">Email</p>
								<input type="text" name="email" value="<?php echo $email; ?>" class="input" placeholder="Ej: ejemplo@gmail.com"/>
								<div class="msjBox animacion1"></div>
							</div>
						</div>
						<!-- telefono personal -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Teléfono Personal</p>
								<input type="text" name="tlfm" value="<?php echo $tlfm; ?>" class="input" placeholder="Ej: 0000-0000000" maxlength="12"/>
								<div class="msjBox animacion1"></div>
							</div>
						</div>

						<!-- telefono de casa -->
						<div class="col col-250px">	
							<div class="contInput">
								<p class="nomInput">Teléfono de Casa</p>
								<input type="text" name="tlff" value="<?php echo $tlff; ?>" class="input" placeholder="Ej: 0000-0000000" maxlength="12"/>
								<div class="msjBox animacion1"></div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Datos de Ubicación domiciliaria -->
			<div class="caja marginB-1">
				<h3>Datos de Ubicación Domiciliaria</h3>
				<div class="pd_LR_30px">
					<div class="row">
						<!-- Estado -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Estado</p>
								<select name="domEdo" class="input">
									<option value="0"> SELECCIONAR </option>
									<?php estadosOp(232, $edoDom); # Estados de venezuela ?>
								</select>
							</div>
						</div>
						<div class="col col-250px">
							<!-- Municipio -->
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Municipio</p>
								<select name="domMun" class="input">
									<option value="0"> SELECCIONAR </option>
									<?php municipiosOp($edoDom, $munDom); # Municipios de Portuguesa ?>
								</select>
							</div>	
						</div>
						<!-- Parroquia -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Parroquia</p>
								<select name="domParr" class="input">
									<option value="0"> SELECCIONAR </option>	
									<?php parroquiasOp($munDom, $parrDom); ?>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<!-- Setor/Urbanizacion/Barrio -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Sector/Barr/Urb</p>
								<input type="text" name="sector" value="<?php echo $sector; ?>" class="input" placeholder=""/>	
							</div>
						</div>
						<!-- Av/Calle/Vereda -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Av/Calle/Vrda</p>
								<input type="text" name="calle" value="<?php echo $calle; ?>" class="input" placeholder=""/>
							</div>
						</div>
						<div class="col col-250px">
							<!-- Nro/Dpto/Casa -->
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Nro/Dpto/Casa</p>
								<input type="text" name="nroCasa" value="<?php echo $nroCasa; ?>" class="input" placeholder=""/>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col100">
							<div id="msjErrorDir" class="msj_error none marginT-1 animacion1">
								<p><i class="icon-attention text_amarrilo text_bold"></i> Los campos no pueden estar vacíos.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		
			<!-- Otros Datos -->
			<div class="caja marginB-1">
				<h3>Otros Datos</h3>
				<div class="pd_LR_30px">
					<div class="row">
						<div class="col col50">
							<!--  grado de instrucion -->
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Grado de Instrucción</p>
								<select name="grdIns" class="input">
									<option value="0"> SELECCIONAR </option>
									<?php listarOpGrdInst($grdIns); ?>
								</select>
								<div class="msjBox animacion1"></div>
							</div>
						</div>
						<div class="col col50">
							<!-- Ocupación -->
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Ocupación</p>
								<select name="ocup" class="input">
									<option value="0" > SELECCIONAR </option>
									<?php listarOpOcup($ocup); ?>
								</select>
								<div class="msjBox animacion1"></div>
							</div>
						</div>
						<!-- observación -->
						<div class="col col100">
							<div class="contInput">
								<p class="nomInput">Observación</p>
								<input type="text" class="input" name="obs" value="<?php echo $obs; ?>">
							</div>
						</div>
						<div class="clear"></div>
					</div>

					<hr>

					<div class="col100">
						<!-- trabaja? -->
						<div class="contInput">
							<p class="nomInput">¿Trabaja?</p>
							Si <input type="radio" name="trabaja" value="si" checked="true" onclick="mostrar('trabajo');"/>
							No <input type="radio" name="trabaja" value="no" <?php if($trabaja=='no'){echo 'checked';} ?> onclick="ocultar('trabajo');"/>
						</div>
					</div>
					<div id="trabajo">
						<div class="row">
							<div class="col col50">
								<!-- estado -->
								<div class="contInput">
									<p class="nomInput"><i class="icon-attention-circled"></i>Estado</p>
									<select name="tEdo" class="input">
										<option value="0"> SELECCIONAR </option>
										<?php estadosOp(232, $edoTrbjo); # Estados de venezuela ?>
									</select>
								</div>
							</div>
							<div class="col col50">
								<!-- municipio -->
								<div class="contInput">
									<p class="nomInput"><i class="icon-attention-circled"></i>Municipio</p>
									<select name="tMun" class="input">
										<option value="0"> SELECCIONAR </option>
										<?php municipiosOp($edoTrbjo, $munTrbjo); # Municipios de Portuguesa ?>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col col50">
								<!-- parroquia -->
								<div class="contInput">
									<p class="nomInput"><i class="icon-attention-circled"></i>Parroquia</p>
									<select name="tParr" class="input">
										<option value="0"> SELECCIONAR </option>
										<?php parroquiasOp($munTrbjo, $parrTrbjo); ?>
									</select>
								</div>
							</div>
							<div class="col col50">
								<!-- telefono de trabajo -->
								<div class="contInput">
									<p class="nomInput">Teléfono de trabajo</p>
									<input type="text" name="tlft" value="<?php echo $tlft; ?>" class="input" maxlength="12" placeholder="Ej: 0255-0000000"/>
									<div class="msjBox animacion1"></div>
								</div>
							</div>
						</div>	
						<!-- direccion de trabajo -->
						<div class="col col100">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Dirección de Trabajo</p>
								<input type="text" name="dirTrbjo" value="<?php echo $dirTrbjo; ?>" class="input" placeholder="Especifique la Dirección"/>
							</div>
						</div>
						<div class="clear"></div>
						<div id="msjErrorDirTrabajo" class="msj_error marginT-1 animacion1 none">
							<p><i class="icon-attention text_amarrilo text_bold"></i> Los campos no pueden estar vacíos.</p>
						</div>
					</div>
				</div>
			</div>
			<?php if($vget=='visualizar'){ ?>
			<div>
				<div class="titulo_m_md">
					<h2>Representados en el Año Escolar</h2><i class="icon-graduation-cap"></i>
				</div>
				<div class="caja s_n3">
					<table width="96%" class="tabla3" cellspacing="0">
						<tr>
							<th style="width: 40px;">#</th>
							<th width="140px">CI/CE</th>
							<th align="left">Nombres y apellidos</th>
							<th width="40px">Grado</th>
							<th>Estatus</th>
							<th width="40px">Ver</th>
						</tr>
						<?php consultar_representados(); ?>
					</table>
				</div>
			</div>
			<?php } ?>
			<!-- BOTONES-->
			<div class="division s_n"></div>
			<div>
				<a id="btn_back" href="?Representante=consultar" class="btn_icon_split btn_gris2 btn_normal btn_md shadow-sm">
					<i class="icon-left-big"></i>
					<p>Consultar Representante</p>
				</a>
				<div class="btnsRep none right">
					<?php if( $sI == '1' ){ ?>
					<label for="enviarFormReg" class="btn_icon_split btn_verde btn_normal btn_md shadow-sm">
						<i class="icon-check"></i>
						<p id="btn_verde_enviar">Registrar Representante</p>
						<input type="button" name="enviarFormReg" id="enviarFormReg" class="none"/>
					</label>
					<?php } ?>
				</div>
				<div class="btnsRep none right">
					<?php if( $sM == '1' ){ ?>
					<label for="enviarFormMod" class="btn_icon_split btn_verde btn_normal btn_md shadow-sm">
						<i class="icon-check"></i>
						<p id="btn_verde_enviar">Guardar Cambios</p>
						<input type="button" name="enviarFormMod" id="enviarFormMod" class="none"/>
					</label>
					<?php } ?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</form>
</section>
<?php
	# ver c_representante.php # - VARIABLES - SESIONES
	if( isset($REG_REP) ){
		if( $REG_REP == 1 ){
			javascript('bloquearPersonal()');
		}

		else if( $REG_REP == 2 ){
			javascript('bloquearCedula()');
		}
	}
?>

