<?php 
	include_once('../CONTROL/c_personal.php'); # controlador 
?>

<div id="tt" class="titulo_m">
	
</div>

<!-- CONSULTAR PERSONAL -->
<section class="secciones none">
	<!-- mensaje -->
	<div class="marginB-2">
		<p>Estimado usuario, si desea consultar debe llenar el campo con la <b class="text_bold text_azul">Cédula</b> del Personal.	
		 Si desea registrar un nuevo Personal, presione el botón verde <b class="text_bold text_azul">Registrar Personal.</b></p>
	</div>

	<form method="GET" name="fbuscarper" action="../CONTROL/c_personal.php">
		<div class="marginB-3">
			<div class="left">
				<?php if( $sC == '1' ){ ?>
				<!-- Buscar Personal -->
				<div class="input_and_btn">
					<p>Cédula</p>
					<div class="contInput2Item">
						<div class="in_left">
							<select name="tipo_doc">
								<option value="V">V</option>
								<option value="E">E</option>
							</select>
							<i class="icon-down-dir"></i>
						</div>
						<!-- cedula y boton search -->
						<div class="in_right w300">
							<input type="text" name="cedula" placeholder="Ej: 99999999" maxlength="8" size="13"/>
							<label for="btnBuscarPer" class="btn btn_gris2">
								<i class="icon-search"></i>
								<input type="button" name="btnCons" id="btnBuscarPer" class="none">
							</label>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
			<!-- botonera a la derecha -->
			<div class="right">
				<?php if($sI=='1'){ ?>
				<a href="?Personal=registrar" class="btn_icon_split btn_verde btn_normal btn_lg shadow-sm">
					<i class="icon-user-plus"></i>
					<p> Registrar Personal </p>
				</a>
				<?php } ?>
			</div>
			<div class="clear"></div>
		</div>
	</form>
</section>

<section class="secciones none">
<div class="cajaH_auto">
	<p class="mgB05_rem text_size1 text_gris"><b>Nota:</b> Los campos marcados con "<b class="text_azul icon-attention-circled"></b>" Son requeridos.</p>
	
	<!-- FORMULARIO PERSONA -->
	<form name="f_personal" method="POST" action="../CONTROL/c_personal.php">	
		<input type="hidden" name="codPer" value="<?php echo $codPer; ?>">
		<input type="hidden" name="ciPer" value="<?php echo $ciPer; ?>">
		<input type="hidden" name="ope" />
		<input type="hidden" name="fechaActual" value="<?php echo date('Y-m-d'); ?>">
		
		<div class="caja marginB-1">
			<h3>Personal</h3>
			<div class="pd_LR_30px">
				<div class="row">
					<!-- cedula de identidad -->
					<div class="col col-250px">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Cédula de identidad</p>
							<div class="contInput2Item">
								<div class="in_left" style="width: 60px;">
									<select name="tdoc">
										<option value="V" <?php if($tdoc=='V'){echo 'selected';}?>>V</option>
										<option value="E" <?php if($tdoc=='E'){echo 'selected';}?>>E</option>
									</select>
									<i class="icon-down-dir"></i>
								</div>
								<div class="in_right">
									<input type="text" name="ced" value="<?php echo $ced; ?>" placeholder="Ej: 9999999" maxlength="8" class="input"/>
								</div>
							</div>
							<div class="msjBox animacion1"></div>
						</div>
					</div>
					<!-- nombre -->
					<div class="col col-250px">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Primer Nombre:</p>
							<input type="text" name="nom" value="<?php echo $nom; ?>" placeholder="Escriba el nombre" class="input"/>
							<div class="msjBox animacion1"></div>
						</div>
					</div>
					<!-- apellido -->
					<div class="col col col-250px">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Primer Apellido:</p>
							<input type="text" name="ape" value="<?php echo $ape; ?>" placeholder="Escriba el apellido" class="input"/>	
							<div class="msjBox animacion1"></div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col col-250px">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Sexo</p>
							<select name="sexo" class="input">
								<option value="0">SELECCIONAR</option>
								<option value="M" <?php if($sexo=='M') echo 'selected';?>>MASCULINO</option>
								<option value="F" <?php if($sexo=='F') echo 'selected';?>>FEMENINO</option>
							</select>
						</div>
						<div class="msjBox animacion1"></div>
					</div>
					<!-- nombre2 -->
					<div class="col col-250px">
						<div class="contInput">
							<p class="nomInput">Segundo Nombre:</p>
							<input type="text" name="nom2" placeholder="Escriba el nombre" class="input" value="<?php echo $nom2; ?>"/>
							<div class="msjBox animacion1"></div>
						</div>
					</div>
					<!-- apellido2 -->
					<div class="col col col-250px">
						<div class="contInput">
							<p class="nomInput">Segundo Apellido:</p>
							<input type="text" name="ape2"  placeholder="Escriba el apellido" class="input" value="<?php echo $ape2; ?>"/>	
							<div class="msjBox animacion1"></div>
						</div>
					</div>
				</div>

				<div class="row">
					<!-- fecha de nacimiento -->
					<div class="col col col-250px">
						<div class="contInput">
							<p class="nomInput">Fecha de nacimiento:</p>
							<input type="date" name="fnac" class="input" value="<?php echo $fnac;?>"/>	
						</div>
					</div>
					<!-- edad -->
					<div class="col col col-250px">
						<div class="contInput">
							<p class="nomInput">Edad:</p>
							<input type="text" name="edad" class="input text_center" readonly="true" value="<?php echo $edad; ?>"/>	
						</div>
					</div>
					<!-- nivel -->
					<div class="col col-250px">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Nivel</p>
							<select name="nivel" class="input">
								<option value="0">SELECCIONAR</option>	
								<option value="2" <?php if($nivel=='2'){echo 'selected';} ?>>PRIMARIA</option>
							</select>
							<div class="msjBox animacion1"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<!-- cargo -->
					<div class="col col-250px">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Cargo</p>
							<select name="cargo" class="input">
								<option value="0">SELECCIONAR</option>
								<?php listar_cargos($cargo); ?>
							</select>
							<div class="msjBox animacion1"></div>
						</div>
					</div>
					<!-- función -->
					<div class="col col-250px">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Función</p>
							<select name="funcion" class="input">
								<option value="0">SELECCIONAR</option>
								<?php listar_funciones($funcion); ?>
							</select>
							<div class="msjBox animacion1"></div>
						</div>
					</div>
					<!-- estatus del personal -->
					<div class="col col-250px">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Estatus del Personal</p>
							<select name="estatus" class="input">
								<option value="A" <?php if($sta=='A'){echo 'selected';} ?>>ACTIVO</option>	
								<option value="I" <?php if($sta=='I'){echo 'selected';} ?>>INACTIVO</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<!-- telefono personal -->
					<div class=" col col-250px">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Teléfono Personal:</p>
							<input type="text" name="tlfm" placeholder="Ej: 0000-0000000" value="<?php echo $tlfm; ?>" maxlength="12" class="input"/>	
							<div class="msjBox animacion1"></div>
						</div>
					</div>
					<!-- correo -->
					<div class="col col-250px">
						<div class="contInput">
							<p class="nomInput">Correo electrónico:</p>
							<input type="text" name="email" value="<?php echo $email; ?>" placeholder="Escriba el correo" class="input"/>	
							<div class="msjBox animacion1"></div>
						</div>
					</div>
					<!-- crear usuario? -->
					<div class="col col-250px none" id="crear_usuario">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>¿Desea crear un usuario?</p>
							<center>
							Si <input type="radio" name="crear_usuario" value="si" style="margin-right: 5px;" />
							No <input type="radio" name="crear_usuario" value="no" checked="true" />
							</center>		
						</div>
					</div>
				</div>
				<div class="row">
					<!-- nivel del usuario -->
					<div class="col col-250px none" id="nivel_usuario">
						<div class="contInput">
							<p class="nomInput"><i class="icon-attention-circled"></i>Nivel del usuario:</p>
							<select name="nivel_usuario" class="input">
								<option value="0">SELECCIONAR</option>	
								<?php listar_niveles_usuario(); ?>
							</select>
							<div class="msjBox animacion1"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	
		<!-- botones -->
		<div class="left">
			<a href="index.php?ver=personal" class="btn btn_icon_split btn_normal btn_gris2 btn_md">
				<i class="icon-left-big"></i><p>Lista de Personal</p>
			</a>
		</div>
		<div class="right">
			<div class="btnP none">
				<?php if( $sI=='1' ){ ?>
				<label class="btn btn_icon_split btn_verde btn_normal btn_md shadow-sm" for="btnEnvReg">
					<i class="icon-plus"></i><p>Registrar Personal</p>
				</label>
				<input type="button" name="btnEnvReg" id="btnEnvReg" class="none"/>
				<?php } ?>
			</div>
			
			<div class="btnP none">
				<?php if( $sM == '1' ){ ?>
				<label class="btn btn_icon_split btn_verde btn_normal btn_md shadow-sm" for="btnEnvMod">
					<i class="icon-edit"></i><p>Guardar Cambios</p>
				</label>
				<input type="button" name="btnEnvMod" id="btnEnvMod" class="none"/>
				<?php } ?>
			</div>
		</div>
	</form>
</div>
</section>


