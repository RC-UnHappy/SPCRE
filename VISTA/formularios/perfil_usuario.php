<?php 
	include_once('../CONTROL/c_perfilUsuario.php'); 
	consultar($_SESSION['vsn_user']);
?>
<div class="titulo_m">
	<h2>Mi perfil</h2>
	<i class="icon-user-circle-o"></i>
</div>

<!-- menu horizontal -->
<nav id="menu-x" class="menu-x marginB-2"> 
	<ul class="op-menu-x">
		<li id="item-menu1" class="item_menu" onclick="mostrar_formulario(0);">Datos personales</li>
		<li id="item-menu2" class="item_menu" onclick="mostrar_formulario(1);">Cambiar contraseña</li>
		<li id="item-menu3" class="item_menu" onclick="mostrar_formulario(2);">Preguntas de seguridad</li>
	</ul>
</nav>

<!-- Datos Personales -->
<section class="none contForm">
	<p class="text_gris text_size1 marginB-2">
		<b>Nota:</b> Los campos marcados con "<b class="text_azul icon-attention-circled"></b>" Son requeridos.
	</p>

	<div class="w800 centrado">
		<form name="persona" method="POST" enctype="multipart/form-data" action="../CONTROL/c_perfilUsuario.php">
			<input type="hidden" name="codPer" value="<?php echo $codPer; ?>"/>
			<input type="hidden" name="ciPer" value="<?php echo $ciPer; ?>"/>
			<input type="hidden" name="ope"/>

			<div class="cajaH_auto">
				<!-- Datos Personales -->
				<div class="caja marginB-1">
					<h3> Datos Personales </h3>
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
									<input type="file" id="input_foto" name="foto" class="none"/>
								</div>
							</div>
							<div class="col">
								<div class="row">
									<!-- cedula -->
									<div class="col col-250px">
										<div class="contInput">
											<p class="nomInput">Documento Identidad</p>
											<input type="text" name="cedula" disabled="true" value="<?php echo $ciPer; ?>" class="input">
										</div>
									</div>

									<div class="col col-250px">
										<div class="contInput">
											<p class="nomInput">Correo electrónico</p>
											<input type="text" name="email" value="<?php echo $email; ?>" placeholder="Ej: Correo electrónico" class="input"/>
											<div class="msjBox animacion1"></div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col col-250px">
										<div class="contInput">
											<p class="nomInput"><i class="icon-attention-circled"></i>Nombre</p>
											<input type="text" name="nom" value="<?php echo $nom; ?>" placeholder="Ej: Nombre" class="input"/>
											<div class="msjBox animacion1"></div>
										</div>
									</div>
									<div class="col col-250px">
										<div class="contInput">
											<p class="nomInput"><i class="icon-attention-circled"></i>Apellido</p>
											<input type="text" name="ape" value="<?php echo $ape; ?>" placeholder="Ej: Apellido" class="input"/>	
											<div class="msjBox animacion1"></div>
										</div>
									</div>
								</div>
									<div class="row">
									<div class="col col-250px">
										<div class="contInput">
											<p class="nomInput"><i class="icon-attention-circled"></i>Teléfono Personal</p>
											<input type="text" name="tlfm" placeholder="Ej: 0416-8527845" value="<?php echo $tlfm; ?>" maxlength="12" class="input"/>	
											<div class="msjBox animacion1"></div>
										</div>
									</div>
									<div class="col col-250px">
										<div class="contInput">
											<p class="nomInput">Teléfono de casa</p>
											<input type="text" name="tlff" placeholder="Ej: 0255-6532299" value="<?php echo $tlff; ?>" maxlength="12" class="input"/>	
											<div class="msjBox animacion1"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- boton -->
				<div class="right">
					<label class="btn btn_icon_split btn_normal btn_md btn_verde shadow-sm" for="envDatosPersonales">
						<i class="icon-edit"></i><p>Guardar cambios</p>
					</label>
					<input type="button" name="enviar" id="envDatosPersonales" class="none" />
				</div>
				<div class="clear"></div>
			</div>
		</form>
	</div>
</section>

<!-- Cambiar Contraseña -->
<section class="none contForm">
	<p class="marginB-1">
		La <b class="text_azul">contraseña</b> debe tener al menos 8 caractéres, entre ellos un <b>número</b> y un <b>símbolo.</b>
	</p>
	<p class="text_gris text_size1 marginB-2">
		Los campos marcados con "<b class="text_azul icon-attention-circled"></b>" Son requeridos.
	</p>
	<div class="w800 centrado">
		<form name="cambiarPass" method="POST" action="../CONTROL/c_perfilUsuario.php">
			<input type="hidden" name="codPer" value="<?php echo $codPer; ?>"/>
			<input type="hidden" name="ciPer" value="<?php echo $ciPer; ?>" />
			<input type="hidden" name="ope"/>

			<div class="cajaH_auto">
				<!-- Cambio de contraseña -->
				<div class="caja marginB-1">
					<h3> Cambiar Contraseña </h3>
					<div class="pd_LR_30px">
						<div class="row">
							<div class="col col-250px">
								<div class="contInput">
									<p class="nomInput"><i class="icon-attention-circled"></i>Contraseña actual:</p>
									<input type="password" name="pass" placeholder="Contraseña actual" class="input"/>
									<div class="msjBox animacion1"></div>
								</div>
							</div>
							<div class="col col-250px">
								<div class="contInput">
									<p class="nomInput"><i class="icon-attention-circled"></i>Nueva contraseña:</p>
									<input type="password" name="npass" placeholder="Nueva contraseña" class="input"/>
									<div class="msjBox animacion1"></div>
								</div>
							</div>
							<div class="col col-250px">
								<div class="contInput">
									<p class="nomInput"><i class="icon-attention-circled"></i>repetir contraseña:</p>
									<input type="password" name="rnpass" placeholder="Repetir contraseña" class="input"/>
									<div class="msjBox animacion1"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- boton -->
				<div class="right">
					<label class="btn btn_icon_split btn_normal btn_md btn_verde shadow-sm" for="envCambiarPass">
						<i class="icon-edit"></i><p>Cambiar contraseña</p>
					</label>
					<input type="button" name="enviar" id="envCambiarPass" class="none"/>
				</div>	
				<div class="clear"></div>
			</div>
		</form>
	</div>
</section>

<!-- Preguntas de seguridad -->
<section class="none contForm">
	<p class="marginB-1">
		<b>Importante: </b>La configuración de las <b class="text_azul">preguntas de seguridad</b> es necesaria para la recuperación de la contraseña.
	</p>
	<p class="text_gris text_size1 marginB-2">
		Los campos marcados con "<b class="text_azul icon-attention-circled"></b>" Son requeridos.
	</p>
	<div class="w800 centrado">
		<form name="preguntas" method="POST" action="../CONTROL/c_perfilUsuario.php">
			<input type="hidden" name="codPer" value="<?php echo $codPer; ?>"/>
			<input type="hidden" name="ciPer" value="<?php echo $ciPer; ?>" />
			<input type="hidden" name="ope"/>

			<div class="cajaH_auto">
				<!-- Preguntas de seguridad -->
				<div class="caja marginB-1">
					<h3> Cambiar Contraseña </h3>
					<div class="pd_LR_30px">
						<div class="row">
							<div class="col col-250px">
								<div class="contInput">
									<p class="nomInput"><i class="icon-attention-circled"></i>Pregunta 1:</p>
									<select name="preg1" class="input text_size1">
										<option value="0" <?php if($p1==0){echo 'selected';} ?>>Seleccionar</option>
										<option value="1" <?php if($p1==1){echo 'selected';} ?>>Segundo nombre de mi madre</option>
										<option value="2" <?php if($p1==2){echo 'selected';} ?>>Segundo nombre de mi padre</option>
										<option value="3" <?php if($p1==3){echo 'selected';} ?>>Color favorito</option>
										<option value="4" <?php if($p1==4){echo 'selected';} ?>>Lugar de Nacimiento</option>
										<option value="5" <?php if($p1==5){echo 'selected';} ?>>Nombre de mi primera mascota</option>
										<option value="6" <?php if($p1==6){echo 'selected';} ?>>Lugar de último viaje</option>
									</select>
									<div class="msjBox animacion1"></div>
								</div>
							</div>
							<div class="col col-250px">
								<div class="contInput">
									<p class="nomInput"><i class="icon-attention-circled"></i>Respuesta 1:</p>
									<input type="password" name="resp1" value="<?php echo $r1; ?>" placeholder="Escriba su respuesta" class="input"/>
									<div class="msjBox animacion1"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col col-250px">
								<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Pregunta 2:</p>
								<input type="text" name="preg2" value="<?php echo $p2; ?>" placeholder="Escriba su pregunta" class="input"/>
								<div class="msjBox animacion1"></div>
							</div>
							</div>
							<div class="col col-250px">
								<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Respuesta 2:</p>
								<input type="password" name="resp2" value="<?php echo $r2; ?>" placeholder="Escriba su respuesta" class="input"/>
								<div class="msjBox animacion1"></div>
							</div>
							</div>
						</div>
						<div class="row">
							<div class="col col-250px">
								<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Contraseña:</p>
								<input type="password" name="pass" placeholder="Escriba la contraseña" class="input"/>
								<div class="msjBox animacion1"></div>
							</div>
						</div>
					</div>
				</div>	
				</div>
				<!-- botón -->
				<div class="right">
					<label for="btnFormSeg" class="btn btn_icon_split btn_normal btn_md btn_verde shadow-sm">
						<i class="icon-edit"></i><p>Guardar cambios</p>
					</label>
					<input type="button" id="btnFormSeg" name="enviar" class="none">
				</div>
				<div class="clear"></div>
			</div>
		</form>
	</div>
</section>

<!-- script -->
<script type="text/javascript" src="../JAVASCRIPT/perfil_usuario.js"></script>

