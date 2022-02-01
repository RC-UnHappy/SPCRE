<?php 
	include_once('../CONTROL/c_estudiante.php');
	include_once('../CONTROL/c_lugares.php');
?>
<div class="titulo_m">
	<h2>Estudiante</h2><i class="icon-graduation-cap"></i>
	<h2 id="tt" style="margin-left: 15px; color: var(--rosa1)" ></h2>
</div>

<?php 
# AÑO ESCOLAR CERRADO, NO MUESTRA NADA
if( $sta_AESC == 'C' ){
?>
<div class="msj_lg">
	<i class="icon-attention rojo"></i><h3>Año escolar cerrado.</h3>
</div>
<p class="msj_error s_n2"><i class="icon-cancel-circled2"></i>Disculpe, En éste momento no es posible realizar ningún tipo de <b>operación</b> mientras no se encuentre un año escolar activo.</p>

<?php }else if( $fechaApertura == false ){ ?>
<div class="msj_lg">
	<i class="icon-attention rojo"></i><h3>No es posible realizar una Inscripción Escolar.</h3>
</div>
<p class="msj_error s_n2"><i class="icon-cancel-circled2"></i>Disculpe, En éste momento no es posible realizar una <b>inscripción</b>. La fecha de <b>apertura</b> para inscripción se encuentra <b>cerrada</b>.</p>
<?php }else{ ?>

<!-- SECCION 1 - CONSULTAR ESTUDIANTE -->
<!-- div que contiene todo -->
<div id="principal_estudiante">
<section class="secciones none">
	<!-- mensaje consultar-->
	<div class="marginB-2 msjModo none">
		<p>Estimado usuario, si desea consultar debe llenar el campo con la <b class="text_bold text_azul">Cédula Escolar</b> del estudiante ó el <b class="text_bold text_azul">Documento de Identidad</b>.</p>
	</div>
	<!-- mensaje inscripción regular-->
	<div class="marginB-2 msjModo none">
		<p>Estimado usuario, para continuar con la inscripción regular debe llenar el campo con la <b class="text_bold text_azul">Cédula Escolar</b> del estudiante ó el <b class="text_bold text_azul">Documento de Identidad</b>.</p>
	</div>

	<div class="marginB-3">
		<div class="left">
			<!-- Buscar estudiante -->
			<div class="input_and_btn">
				<p>CI / Cédula Escolar</p>
				<div class="contInput2Item">
					<div class="in_left">
						<select id="tipo_doc">
							<option value="V">V</option>
							<option value="E">E</option>
						</select>
						<i class="icon-down-dir"></i>
					</div>
					<!-- cedula y boton search -->
					<div class="in_right w300">
						<input type="text" id="buscar" placeholder="Ej: 12299999999" maxlength="11" size="13"/>
						<label for="btnBuscarEst" class="btn btn_gris2">
							<i class="icon-search"></i>
							<input type="button" id="btnBuscarEst" class="none">
						</label>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</section>

<!-- SECCION 2 MENSAJE DE NO REALIZAR INSCRIPCION -->
<section class="secciones none">
	<?php if($sta_AESC == 'C'){ ?>
	<div class="msj_lg">
		<i class="icon-attention rojo"></i><h3>No es posible realizar una Inscripción Escolar.</h3>
	</div>
	<p class="msj_error s_n2"><i class="icon-cancel-circled2"></i>En éste momento no es posible realizar una <b>inscripción</b> mientras no se encuentre un año escolar activo.</p>
	<?php }else{ 
	?>
	<p class="marginB-2">
		Por favor, seleccione el tipo de documento e introdúzca el <b class="text_azul">documento de identidad</b> del representante y presione continuar.<br/>
	</p>
	<?php } ?>
</section>

<!-- SECCION 3 - FORMULARIO ESTUDIANTE -->
<section class="secciones none">
	<!-- Menu Horizontal -->
	<nav id="menu-x" class="menu-x marginB-2 none"> 
		<ul class="op-menu-x">
			<li id="item-menu1" style="padding-left:20px;padding-right:20px;" class="selected" onclick="mostrar_formulario(0,this)">Datos del Estudiante</li>
			<li id="item-menu2" style="padding-left:20px;padding-right:20px;" onclick="mostrar_formulario(1,this);">Inscripción Escolar</li>
			<li id="item-menu3" style="padding-left:20px;padding-right:20px;" onclick="mostrar_formulario(2,this);">Rendimiento Escolar</li>
			<li id="item-menu4" style="padding-left:20px;padding-right:20px;" onclick="mostrar_formulario(3,this);">Reportes Académicos</li>
		</ul>
	</nav>

	<!-- 1.- ESTUDIANTE -->
	<form name="estudiante" method="POST" enctype="multipart/form-data" action="../CONTROL/c_estudiante.php" class="caja_contForm none">
		<!-- modo -->
		<input type="hidden" name="modoINSC" value="<?php echo $modoINSC; ?>">
		<!-- hidden representantes -->
		<input type="hidden" name="cod_madre" value="<?php echo $cod_madre; ?>" />
		<input type="hidden" name="cod_padre" value="<?php echo $cod_padre; ?>" />
		<!-- hidden estudiante -->
		<input type="hidden" name="opeEst" value=""/>
		<input type="hidden" name="codPer" value="<?php echo $codPer; ?>" />
		<input type="hidden" name="ced_estH" value="<?php echo $ced_estH; ?>" />
		<input type="hidden" name="cedEscH" value="<?php echo $cedEscH; ?>" />
		<input type="hidden" name="fechaActual" value="<?php echo $fechaActual; ?>"/>
		<input type="hidden" name="ced_estAlt" value="<?php echo $ced_estAlt; ?>" />

		<!-- Representantes -->
		<div class="cajaH_auto">
			<p class="text_size1 text_gris s_n "><b class="text_bold">Nota: </b> Los campos marcados con "<b class="text_bold text_azul icon-attention-circled"></b>" Son requeridos.</p>
			<div class="caja marginB-3">
				<h3>Padres / Representantes</h3>
				<div class="pd_LR_30px">
					<!-- Madre -->
					<div class="row">
						<!-- cedula de la madre -->
						<div class="col col-250px">
							<div class="contInput">
								<div class="nomInput"><i class="icon-attention-circled"></i>Cédula de la madre</div>
								<div class="contInput2Item">
									<div class="in_left" style="width: 60px;">
										<select name="tdoc_madre">
											<option value="V" <?php if($tdoc_madre == 'V'){echo 'selected';} ?>>V</option>
											<option value="E" <?php if($tdoc_madre == 'E'){echo 'selected';} ?>>E</option>
										</select>
										<i class="icon-down-dir"></i>
									</div>
									<div class="in_right">
										<input type="text" name="ced_madre" value="<?php echo $ced_madre; ?>" placeholder="Ej: 9999999" maxlength="8" class="input"/>
									</div>
								</div>
								<div class="msjBox anm1"></div>
							</div>
						</div>
						<!-- nombre y apellido de la madre -->
						<div class="col col-400px">
							<div class="contInput">
								<div class="nomInput">Nombre y Apellido</div>
								<input type="text" name="nom_madre" class="input" value="<?php echo $nom_madre; ?>" />
							</div>
						</div>
						<div class="clear"></div>
					</div>
					<!-- Padre -->
					<div class="row">
						<!-- cedula del padre -->
						<div class="col col-250px">
							<div class="contInput">
								<div class="nomInput">Cédula del Padre</div>
								<div class="contInput2Item">
									<div class="in_left" style="width: 60px;">
										<select name="tdoc_padre">
											<option value="V" <?php if($tdoc_padre=='V'){echo 'selected';} ?>>V</option>
											<option value="E" <?php if($tdoc_padre=='E'){echo 'selected';} ?>>E</option>
										</select>
										<i class="icon-down-dir"></i>
									</div>
									<div class="in_right">
										<input type="text" name="ced_padre" value="<?php echo $ced_padre; ?>" placeholder="Ej: 9999999" maxlength="8" class="input"/>
									</div>
								</div>
								<div class="msjBox anm1"></div>
							</div>
						</div>
						<!-- nombre y apellido del padre -->
						<div class="col col-400px">
							<div class="contInput">
								<div class="nomInput">Nombre y Apellido</div>
								<input type="text" name="nom_padre" class="input" value="<?php echo $nom_padre; ?>" />
							</div>
						</div>
						<div class="clear"></div>
					</div>
				</div>
			</div>

			<!-- IDENTIDAD DEL ESTUDIANTE -->
			<div class="caja marginB-3">
				<h3>Identidad del Estudiante</h3>
				<div class="pd_LR_30px">
					<?php echo $txtEstatus; ?>
					<div class="row">
						<div class="col col-250px">
							<!-- foto del estudiante -->
							<div class="col">
								<div class="foto">
									<div class="contFoto">
										<img id="foto_previa" src="<?php echo $url_foto; ?>">
									</div>
									<label for="input_foto">
										<div class="btn btn_icon_split_w100 btn_gris1 btn_md">
											<i class="icon-camera"></i>
											<p>Seleccionar</p>
										</div>
									</label>
									<input type="file" id="input_foto" name="foto" class="none">
								</div>
							</div>
						</div>
						<div class="col">
							<div class="row">
								<!-- cedula del Estudiante -->
								<div class="col col-250px">
									<div class="contInput">
										<div class="nomInput">Cédula del Estudiante</div>
										<div class="contInput2Item">
											<div class="in_left" style="width: 60px;">
												<select name="tdoc_est">
													<option value="V">V</option>
													<option value="E">E</option>
												</select>
												<i class="icon-down-dir"></i>
											</div>
											<div class="in_right">
												<input size="8" type="text" name="ced_est" maxlength="8" placeholder="Ej: 99999999" value="<?php echo $ced_est; ?>" class="input"/>
											</div>
										</div>
										<div class="msjBox anm1"></div>
									</div>
								</div>
								<!-- Nacionalidad -->
								<div class="col-250px">
									<div class="contInput">
										<p class="nomInput"><i class="icon-attention-circled"></i>Nacionalidad</p>
										<select name="nac" class="input">
											<option value="V" <?php if($nac=='V'){echo 'selected';} ?>>VENEZOLANA</option>
											<option value="E" <?php if($nac=='E'){echo 'selected';} ?>>EXTRANJERA</option>
										</select>	
									</div>
								</div>
							</div>
							<div class="row">
								<!-- primer nombre -->
								<div class="col col-250px">
									<div class="contInput">
										<p class="nomInput"><i class="icon-attention-circled"></i>Primer Nombre</p>
										<input size="12" type="text" name="nom1" value="<?php echo $nom1; ?>" placeholder="Primer Nombre" class="input"/>
										<div class="msjBox anm1"></div>
									</div>
								</div>
								<!-- segundo nombre -->
								<div class="col col-250px">
									<div class="contInput">
										<p class="nomInput">Segundo Nombre</p>
										<input size="12" type="text" name="nom2" value="<?php echo $nom2; ?>" placeholder="Segundo Nombre" class="input"/>
										<div class="msjBox anm1"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<!-- primer apellido -->
								<div class="col col-250px">
									<div class="contInput">
										<p class="nomInput"><i class="icon-attention-circled"></i>Primer Apellido</p>
										<input size="12" type="text" name="ape1" value="<?php echo $ape1; ?>" class="input" placeholder="Primer Apellido"/>
										<div class="msjBox anm1"></div>
									</div>
								</div>	
								<!-- segundo apellido -->
								<div class="col col-250px">
									<div class="contInput">
										<p class="nomInput">Segundo Apellido</p>
										<input size="12" type="text" name="ape2" value="<?php echo $ape2; ?>" class="input" placeholder="Segundo Apellido"/>
										<div class="msjBox anm1"></div>
									</div>
								</div>	
							</div>
						</div>
					</div>

					<div class="row">
						<!-- genero -->	
						<div class="col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Género</p>
								<select name="sex" class="input">
									<option value="0">SELECCIONAR</option>
									<option value="M" <?php if($sexo=='M'){echo 'selected';} ?>>MASCULINO</option>
									<option value="F" <?php if($sexo=='F'){echo 'selected';} ?>>FEMENINO</option>	
								</select>
								<div class="msjBox anm1"></div>
							</div>
						</div>
						<!-- fecha de nacimiento -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Fecha de Nacimiento</p>
								<input type="date" name="fnac" value="<?php echo $fnac; ?>" class="input"/>
								<div class="msjBox anm1"></div>
							</div>
						</div>
						<!-- edad -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput">Edad (Años)</p>
								<input type="text" name="edad" class="input text_center"  value="<?php echo $edad; ?>" disabled="true">
							</div>
						</div>
					</div>

					<div class="row">
						<!-- telefono estudiante -->
						<div class="col-250px">
							<div class="contInput">
								<p class="nomInput">Teléfono</p>
								<input type="text" name="tlfn" class="input" placeholder="Ej: 0000-0000000" maxlength="12" value="<?php echo $tlfn; ?>"/>
								<div class="msjBox anm1"></div>
							</div>
						</div>
						<!-- Orden de naciminento en la fecha -->
						<div class="col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Orden de nacimiento</p>
								<select name="ordenNac" class="input">
									<option value="1" <?php if($ordenNac=='1'){echo 'selected';} ?>>Primero Nacido en ésta fecha</option>
									<option value="2" <?php if($ordenNac=='2'){echo 'selected';} ?>>Segundo Nacido en ésta fecha</option>
									<option value="3" <?php if($ordenNac=='3'){echo 'selected';} ?>>Tercero Nacido en ésta fecha</option>
									<option value="4" <?php if($ordenNac=='4'){echo 'selected';} ?>>Cuarto Nacido en ésta fecha</option>
									<option value="5" <?php if($ordenNac=='5'){echo 'selected';} ?>>Quinto Nacido en ésta fecha</option>
									<option value="6" <?php if($ordenNac=='6'){echo 'selected';} ?>>Sexto Nacido en ésta fecha</option>		
								</select>
							</div>
						</div>
						<!-- Cédula Escolar -->
						<div class="col col-250px">
							<div class="contInput">
								<div class="nomInput"><i class="icon-attention-circled"></i>Cédula Escolar</div>
								<input type="text" name="cedEsc" maxlength="11" value="<?php echo $cedEsc; ?>" class="input" disabled="true"/>
								<div class="msjBox anm1"></div>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>

			<!-- LUGAR DE NACIMIENTO -->
			<div class="caja marginB-3">
				<h3>Lugar de Nacimiento</h3>
				<div class="pd_LR_30px">
					<div class="row">
						<!-- Pais de nacimiento -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>País de nacimiento</p>
								<select name="paisNac" class="input">
									<?php paisesOp($paisNac); ?>
								</select>
							</div>
						</div>
						<!-- estado de nacimiento -->
						<div class="col col-250px">
							<div class="contInput">		
								<p class="nomInput"><i class="icon-attention-circled"></i>Estado de nacimiento</p>
								<select name="edoNac" class="input">
									<option value="0">SELECCIONAR</option>
									<?php estadosOp($paisNac, $edoNac); ?>
								</select>
							</div>
						</div>
						<!-- Municipio de nacimiento -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Municipio de nacimiento</p>
								<select name="munNac" class="input">
									<option value="0">SELECCIONAR</option>
									<?php municipiosOp($edoNac, $munNac); ?>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<!-- parroquia de nacimiento -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Parroquia de nacimiento</p>
								<select name="parrNac" class="input">
									<option value="0">SELECCIONAR</option>
									<?php parroquiasOp($munNac, $parrNac); ?>
								</select>
							</div>
						</div>
						<div class="col col-250px">
							<div class="contInput">
								<div class="nomInput"><i class="icon-attention-circled"></i>Lugar de Nacimiento</div>
								<input type="text" name="lugarNac" value="<?php echo $lugarNac; ?>" placeholder="Lugar de nacimiento" class="input"/>
							</div>	
						</div>
					</div>

					<div class="row">
						<div class="col col100">
							<div id="msjErrorLugarNac" class="none msj_error anm1">
								<p><i class="icon-attention text_amarrilo text_bold"></i><b>Los campos no pueden estar vacíos</b>.</p>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- UBICACION DOMICILIARIA -->
			<div class="caja marginB-3">
				<h3>Datos de Ubicación Domiciliaria</h3>
				<div class="pd_LR_30px">
					<div class="row">
						<!-- Estado -->
						<div class="col col-250px">
							<div class="contInput">	
								<p class="nomInput"><i class="icon-attention-circled"></i>Estado</p>
								<select name="edoDom" class="input">
									<?php estadosOp(232, $edoDom); ?>
								</select>
							</div>
						</div>
						<!-- Municipio -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Municipio</p>
								<select name="munDom" class="input">
									<option value="0">SELECCIONAR</option>
									<?php municipiosOp($edoDom, $munDom); ?>
								</select>
							</div>
						</div>
						<!-- Parroquia -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Parroquia</p>
								<select name="parrDom" class="input">
									<option value="0">SELECCIONAR</option>
									<?php parroquiasOp($munDom, $parrDom); ?>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<!-- Sector/calle/Urb -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Sector/Barr/Urb</p>
								<input type="text" name="sector" value="<?php echo $sector; ?>" class="input" placeholder="Ej: VILLAS DEL PILAR"/>	
							</div>
						</div>
						<!-- avenida/calle/vereda -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Av/Calle/Vrda</p>
								<input type="text" name="calle" value="<?php echo $calle; ?>" class="input" placeholder="Ej: AVENIDA 2, CALLE 5"/>
							</div>
						</div>
						<!-- NroDpto / Casa -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Nro/Dpto/Casa</p>
								<input type="text" name="nroCasa" value="<?php echo $nroCasa; ?>" class="input" placeholder="Ej: 65-55"/>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col col100">
							<div id="msjErrorDir" class="none msj_error anm1">
								<p><i class="icon-attention text_amarrilo text_bold"></i><b>Los campos no pueden estar vacíos</b>.</p>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- DATOS ANTROPOMETRICOS -->
			<div class="caja marginB-3">
				<h3>Datos Antropométricos</h3>
				<div class="pd_LR_30px">
					<div class="row">
						<!-- estatura -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput">Estatura(m)</p>
								<input type="text" name="estatura" placeholder="Ej: 0.00" value="<?php echo $estatura; ?>" maxlength="4" class="input text_center"/>
							</div>
						</div>
						<!-- peso -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput">Peso(kg)</p>
								<input type="text" name="peso" value="<?php echo $peso; ?>" placeholder="Ej: 00.00" maxlength="5" class="input text_center"/>
							</div>
						</div>
						<!-- Camisa -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput">Camisa</p>
								<input type="text" name="camisa" placeholder="Ej: 00" value="<?php echo $camisa; ?>" maxlength="5" class="input text_center" />
							</div>
						</div>
					</div>
					<div class="row">
						<!-- Pantalon -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput">Pantalón</p>
								<input type="text" name="pantalon" placeholder="Ej: 12" value="<?php echo $pantalon; ?>" maxlength="4" class="input text_center"/>
							</div>
						</div>
						<!-- Calzado -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput">Calzado</p>
								<input type="text" name="calzado" placeholder="Ej: 30" value="<?php echo $calzado; ?>" maxlength="2" class="input text_center"/>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- DATOS PRE Y POST NATALES -->
			<div class="caja marginB-3">
				<h3>Antecedentes Pre y Post Natales</h3>
				<div class="pd_LR_30px">
					<div class="row">
						<!-- embarazo -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput">Embarazo</p>
								<input type="text" name="embarazo" class="input" value="<?php echo $embarazo; ?>" />
							</div>
						</div>
						<!-- tipo de parto -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput">Parto</p>
								<select name="parto" class="input">
									<option value="N" <?php if($parto=='N'){echo 'selected';} ?>>Parto natural</option>
									<option value="V" <?php if($parto=='V'){echo 'selected';} ?>>Parto vaginal instrumental</option>
									<option value="C" <?php if($parto=='C'){echo 'selected';} ?>>Parto abdominal o cesárea</option>
								</select>
							</div>
						</div>
						<!-- Madurez neuromotriz -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput">Madurez Neuromotríz</p>
								<input type="text" name="mad_neu" class="input" value="<?php echo $mad_neu; ?>" />
							</div>
						</div>
					</div>
					<hr/>
					<p class="text_gris text_size1">Primeros años de vida</p>
					
					<div class="row">
						<!-- alimentacion -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput">Alimentación</p>
								<input type="text" name="alimentacion" class="input" value="<?php echo $alm; ?>" />
							</div>
						</div>
						<!-- sueño -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput">Sueño</p>
								<input type="text" name="sueno" class="input" value="<?php echo $sno; ?>" />
							</div>
						</div>
						<!-- dentincion -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput">Dentinción</p>
								<input type="text" name="dentincion" class="input" value="<?php echo $dent; ?>" />
							</div>
						</div>
					</div>

					<div class="row">
						<!-- Edad primeros pasos -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput">Edad primeros pasos</p>
								<input type="text" name="p_pasos" placeholder="Ej: 10 meses" class="input" value="<?php echo $edadp; ?>"/>
							</div>
						</div>
					
						<!-- Lenuaje -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput">Lenguaje</p>
								<input type="text" name="lenguaje" class="input" value="<?php echo $lenguaje; ?>" />
							</div>
						</div>
						<!-- control de Esfínteres -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput">Control de Esfínteres</p>
								<div class="text_center">
									Si <input type="radio" style="margin-right: 6px;" name="c_esfinteres" value="1" <?php if($cEsfin=='1'){echo 'checked';} ?>/>
									No <input type="radio" style="margin-right: 6px;" name="c_esfinteres" value="0" <?php if($cEsfin=='0'){echo 'checked';} ?>/>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<!-- observación -->
						<div class="col col100">
							<div class="contInput">
								<p class="nomInput">Observación</p>
								<input type="text" name="obs" class="input" value="<?php echo $observacion; ?>" />
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- DATOS SOCIECONÓMICOS -->
			<div class="caja marginB-3">
				<h3>Datos Socieconómicos</h3>
				<div class="pd_LR_30px">
					<div class="row">
						<!-- tipo vivienda -->
						<div class="col col-250px">	
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Tipo de vivienda</p>
								<select name="tipoVnda" class="input">
									<option value="0">SELECCIONAR</option>
									<?php listar_op_vivienda('tipo',$tipo_vnda); ?>
								</select>
								<div class="msjBox anm1"></div>
							</div>
						</div>
						<!-- condicion de vivienda -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Condición de vivienda</p>
								<select name="condVnda" class="input">
									<option value="0">SELECCIONAR</option>
									<?php listar_op_vivienda('cond',$cond_vnda); ?>
								</select>
								<div class="msjBox anm1"></div>
							</div>
						</div>
						<!-- condicion de infraestructura -->
						<div class="contInput">
							<p class="nomInput" ><i class="icon-attention-circled"></i>Condición de Infraestructura</p>
							<select name="condInfraVnda" class="input">
								<option value="0">SELECCIONAR</option>
								<?php listar_op_vivienda('cond_infra',$cond_infra_vnda); ?>
							</select>
							<div class="msjBox anm1"></div>
						</div>
					</div>

					<div class="row">
						<!-- numero de habitaciones -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>N° de habitaciones</p>
								<input type="text" name="num_habitaciones" class="input text_center" placeholder="Ej: 0" maxlength="2" value="<?php echo $num_hab; ?>">
								<div class="msjBox anm1"></div>
							</div>
						</div>
						<!-- numero de personas que viven en el hogar -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput" style="line-height: 1;"><i class="icon-attention-circled"></i>N° de personas que viven en el hogar</p>
								<input type="text" name="num_personas" class="input text_center" placeholder="Ej: 0" maxlength="2" value="<?php echo $num_prnsH; ?>">
								<div class="msjBox anm1"></div>
							</div>
						</div>
						<!-- nomero de personas que trabajan en el hogar -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput" style="line-height: 1;"><i class="icon-attention-circled"></i>N° de personas que trabajan en el hogar</p>
								<input type="text" name="num_personasT" class="input text_center" placeholder="Ej: 0" maxlength="2" value="<?php echo $num_prnsT; ?>">
								<div class="msjBox anm1"></div>
							</div>
						</div>
					</div>

					<div class="row">
						<!-- numero de hermanos -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput">N° de Hermanos</p>
								<input type="text" name="num_hermanos" placeholder="Ej: 0" class="input text_center" maxlength="2" value="<?php echo $num_Herm; ?>">
							</div>
						</div>
						<!-- numero de hermanos en la escuela -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput">N° de Hermanos en la escuela</p>
								<input type="text" name="num_hermanosEsc" placeholder="Ej: 0" class="input text_center" maxlength="2" value="<?php echo $num_HermEsc; ?>">
							</div>
						</div>
						<!-- Ingreso familiar -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Ingreso Familiar (BsS)</p>
								<input type="text" name="ing_familiar" class="input text_center" placeholder="Ej: 00000" value="<?php echo $ingreso_fam; ?>"/>
								<div class="msjBox anm1"></div>
							</div>
						</div>
					</div>

					<div class="row">
						<!-- tiene beca -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>¿Tiene Beca?</p>
								<div class="text_center">
									Si <input type="radio" style="margin-right: 6px;" name="beca" value="1" <?php if($beca==1){echo 'checked';}?>/>
									No <input type="radio" style="margin-right: 6px;" name="beca" value="0" <?php if($beca==0){echo 'checked';}?>/>
								</div>
							</div>
						</div>
						<!-- serial de canaima -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput">Serial de Canaima</p>
								<input type="text" name="canaima" class="input" value="<?php echo $s_canaima; ?>"/>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- DATOS DE SALUD -->
			<div class="caja marginB-3">
				<h3>Datos de salud</h3>
				<!-- inputs hidden en donde se almacenan los codigos -->
				<input type="hidden" name="enf_pd" value="<?php echo $enfermedadesP; ?>"/>
				<input type="hidden" name="vacunas" value="<?php echo $vacunas; ?>"/>
				
				<div class="pd_LR_30px">
					<div class="row">
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Discapacidad</p>
								<select name="discapacidad" class="input">
									<?php listar_discapacidadesOp($discapacidad); ?>
								</select>		
							</div>
						</div>
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput">Es alérgico a:</p>
								<input type="text" name="alergias" class="input" value="<?php echo $alergico; ?>" />
							</div>
						</div>
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput" style="line-height: 1">Enfermedades (Afección) de Atención Especial</p>
								<input type="text" name="enfAAE" class="input" value="<?php echo $enfermedadesAE; ?>" />
							</div>
						</div>
					</div>	

					<div class="row">
						<!-- Enfermedades -->
						<div class="col col50">
							<div class="contInput">
								<p class="nomInput">Enfermedades Padecidas</p>
								<select name="enf_sel" class="input" style="width: 86%;">
									<option value="0">SELECCIONAR</option>
									<?php listar_enfermedadesOp(); ?>
								</select>
								<i class="btn_add icon-plus" onclick="agregar_enf()"></i>
								<div id="div_enf" class="contItemBox">
									<?php echo $divItemsBox_enf; ?>
								</div>
							</div>
						</div>
						<!-- Vacunas -->
						<div class="col col50">
							<div class="contInput">
								<p class="nomInput">Vacunación Recibida</p>
								<select name="vcna_sel" class="input" style="width: 86%;">
									<option value="0">SELECCIONAR</option>
									<?php listar_vacunasOp(); ?>
								</select>
								<i class="btn_add icon-plus" onclick="agregar_vacuna()"></i>
								<div id="div_vcna" class="contItemBox">
									<?php echo $divItemsBox_vcna; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- DOCUMENTOS PRESENTADOS -->
			<div>
				<p class="marginB05rem"><i class="icon-attention-circled text_azul"></i><b>Documentos Presentados</b></p>
				<div class="marginB05rem">
					<ul>
						<li><i class="icon-right-dir"></i> <input type="checkbox" name="d1" <?php if($d1 == '1'){echo 'checked';} ?>/> Una (1) Copia de la Ficha de Control de Vacunas.</li>
						<li><i class="icon-right-dir"></i> <input type="checkbox" name="d2" <?php if($d2 == '1'){echo 'checked';} ?>/> Una (1) Copia de la Partida de Nacimiento del niño.</li>
						<li><i class="icon-right-dir"></i> <input type="checkbox" name="d3" <?php if($d3 == '1'){echo 'checked';} ?>/> Una (1) Copia de C.I del Representante.</li>
						<li><i class="icon-right-dir"></i> <input type="checkbox" name="d4" <?php if($d4 == '1'){echo 'checked';} ?>/> Una (1) Copia de C.I del niño(a).</li>
						<li><i class="icon-right-dir"></i> <input type="checkbox" name="d5" <?php if($d5 == '1'){echo 'checked';} ?>/> Cuatro (4) Fotos tamaño carnet del niño.</li>
						<li><i class="icon-right-dir"></i> <input type="checkbox" name="d6" <?php if($d6 == '1'){echo 'checked';} ?>/> Una (1) Foto tamaño carnet del Representante.</li>
						<li><i class="icon-right-dir"></i> <input type="checkbox" name="d7" <?php if($d7 == '1'){echo 'checked';} ?>/> Expediente: Constancia de Promovido .</li>
						<li><i class="icon-right-dir"></i> <input type="checkbox" name="d8" <?php if($d8 == '1'){echo 'checked';} ?>/> Informe Descriptivo.</li>
						<li><i class="icon-right-dir"></i> <input type="checkbox" name="d9" <?php if($d9 == '1'){echo 'checked';} ?>/> Boleta de retiro.</li>
						<li><i class="icon-right-dir"></i> <input type="checkbox" name="d10" <?php if($d10 == '1'){echo 'checked';} ?>/> Informe médico.</li>
					</ul>
				</div>
				<p>
					<i class="icon-doc-text text_gris" style="margin-right: 8px;"></i><input type="text" name="otros_documentos" placeholder="Otros Documentos" style="width: 300px; border:solid 1px var(--gris4); padding: 5px;" value="<?php echo $otros_doc; ?>">
				</p>
			</div>
			<div class="division s_n"></div>
			<!-- botones -->
			<div class="marginB-2">
				<!-- Botón Regresar -->
				<div class="left">
					<a href="?Estudiante=consultar" class="btn_icon_split btn_gris2 btn_normal btn_md shadow-sm">
						<i class="icon-left-big"></i>
						<p>Consultar Estudiante</p>
					</a>
				</div>
				<!-- botones Estudiante-->
				<div class="show right btnsEst none">
					<?php if( $sI == '1' ){ ?>
					<label for="enviarRegistrar" id="btn_enviar" class="btn_icon_split btn_verde btn_normal btn_md shadow-sm">
						<i class="icon-check"></i>
						<p>Registrar Estudiante</p>
					</label>
					<input type="button" id="enviarRegistrar" class="none" name="enviarRegistrar" />
					<?php } ?>
				</div>	
				<div class="show right btnsEst none">
					<?php
					if( $sM == '1' ){ ?>
					<label for="enviarModificar" id="btn_enviar" class="btn_icon_split btn_verde btn_normal btn_md shadow-sm">
						<i class="icon-edit"></i>
						<p>Guardar cambios</p>
					</label>
					<input type="button" id="enviarModificar" class="none" name="enviarModificar" />
					<?php } ?>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</form>

	<!-- 2.- INSCRIPCION ESCOLAR  -->
	<form name="f_inscripcion" method="POST" action="../CONTROL/c_inscripcion.php" class="caja_contForm none">
		<input type="hidden" name="modoINSC" value="<?php echo $modoINSC; ?>">
		<?php if( $sta_AESC == 'C' ){ # año escolar cerrado ?>
		<div class="msj_lg">
			<i class="icon-attention rojo"></i><h3>No es posible realizar una Inscripción Escolar.</h3>
		</div>
		<p class="msj_error s_n2"><i class="icon-cancel-circled2"></i>En éste momento no es posible realizar una <b>inscripción</b> mientras no se encuentre un año escolar activo.</p>
		<?php }else{ ?>
		<!-- modo -->
		<input type="hidden" name="modoINSC" value="<?php echo $modoINSC; ?>">
		<!-- codigo de las personas y cedula Escolar -->
		<input type="hidden" name="ope_insc" value=""/>
		<input type="hidden" name="codPeriodo" value="<?php echo $cod_AESC; ?>"/>
		<input type="hidden" name="Est" value="<?php echo $codPer; ?>" />
		<input type="hidden" name="Rep" value="<?php echo $cod_rep; ?>" />
		<input type="hidden" name="codigoInsc" value="<?php echo $codInsc; ?>" />
		<input type="hidden" name="seccionH" value="<?php echo $seccion; ?>">
		
		<div class="cajaH_auto">
			<p class="text_size1 text_gris s_n "><b class="text_bold">Nota: </b> Los campos marcados con "<b class="text_bold text_azul icon-attention-circled"></b>" Son requeridos.</p>
			<div class="caja marginB-1">
				<h3>Inscripción Escolar <i class="icon-book"></i></h3>
				<div class="pd_LR_30px">
					
					<?php echo $txtEstatus; ?>

					<!-- Representante -->
					<div class="row">
						<!-- cedula representante -->
						<div class="col col-250px">
							<div class="contInput">
								<div class="nomInput"><i class="icon-attention-circled"></i>Cédula del Representante</div>
								<div class="contInput2Item">
									<div class="in_left" style="width: 60px;">
										<select name="tdoc_rep">
											<option value="V" <?php if($tdoc_rep == 'V'){echo 'selected';} ?>>V</option>
											<option value="E" <?php if($tdoc_rep == 'E'){echo 'selected';} ?>>E</option>
										</select>
										<i class="icon-down-dir"></i>
									</div>
									<div class="in_right">
										<input type="text" name="ced_rep" value="<?php echo $ced_rep; ?>" placeholder="Ej: 9999999" maxlength="8" class="input"/>
									</div>
								</div>
								<div class="msjBox anm1"></div>
							</div>
						</div>
						<!-- nombre representante -->
						<div class="col col-250px">
							<div class="contInput">
								<div class="nomInput"><i class="icon-attention-circled"></i>Nombre y Apellido</div>
								<input type="text" name="nom_rep" class="input" value="<?php echo $nom_rep; ?>"/>
							</div>
						</div>
						<!-- Parentesco con el estudiante -->
						<div class="col col-250px" >
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Parentesco</p>
								<select class="input" name="parent_r">
									<option value="0">SELECCIONAR</option>
									<option value="1" <?php if($parentesco == '1'){echo 'selected';}?>>MADRE</option>
									<option value="2" <?php if($parentesco == '2'){echo 'selected';}?>>PADRE</option>
									<option value="3" <?php if($parentesco == '3'){echo 'selected';}?>>ABUELA</option>
									<option value="4" <?php if($parentesco == '4'){echo 'selected';}?>>ABUELO</option>
									<option value="5" <?php if($parentesco == '5'){echo 'selected';}?>>TÍA</option>
									<option value="6" <?php if($parentesco == '6'){echo 'selected';}?>>TÍO</option>
									<option value="7" <?php if($parentesco == '7'){echo 'selected';}?>>HERMANO</option>
									<option value="8" <?php if($parentesco == '8'){echo 'selected';}?>>HERMANA</option>
									<option value="9" <?php if($parentesco == '9'){echo 'selected';}?>>PRIMA</option>
									<option value="10" <?php if($parentesco == '10'){echo 'selected';}?>>PRIMO</option>
									<option value="11" <?php if($parentesco == '11'){echo 'selected';}?>>OTRO</option>
								</select>
							</div>
						</div>
						<div class="clear"></div>
					</div>
					<div class="row">
						<div class="col col-250px">
							<div class="contInput">
								<div class="nomInput"><i class="icon-attention-circled"></i>CI / CE</div>
								<input type="text" name="cedEst" value="<?php echo $ced_estAlt; ?>" class="input" disabled/>
							</div>
						</div>
						<div class="col col-250px">
							<div class="contInput">
								<div class="nomInput"><i class="icon-attention-circled"></i>Nombres</div>
								<input type="text" name="nomEst" value="<?php echo $nom1.' '.$nom2; ?>" class="input" disabled/>
							</div>
						</div>
						<div class="col col-250px">
							<div class="contInput">
								<div class="nomInput"><i class="icon-attention-circled"></i>Apellidos</div>
								<input type="text" name="apeEst" value="<?php echo $ape1.' '.$ape2; ?>" class="input" disabled/>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col col-250px">
							<div class="contInput">
								<div class="nomInput"><i class="icon-attention-circled"></i>Año Escolar</div>
								<input type="text" name="apeEst" value="<?php echo $per_AESC; ?>" class="input" disabled/>
							</div>
						</div>
						<div class="col col-250px">
							<!-- seccion -->
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Grado y Sección</p>
								<select name="seccion" class="input" <?php if($_SESSION['vsn_nivel'] == 4){echo 'disabled';} ?>>
									<option value="0">SELCCIONAR</option>
									<?php listar_secciones($seccion); # pasa de parametro el codigo de la seccion, ver las variables de c_inscripcion.php ?>
								</select>
							</div>
						</div>
						<div class="col col-250px">
							<div class="contInput">
								<div class="nomInput"><i class="icon-attention-circled"></i>Modalidad</div>
								<select name="modo" class="input" <?php echo $bloqModalidad; ?>>
									<option value="0">SELECCIONAR</option>
									<option value="N" <?php if($modo_insc == 'N'){echo "selected";} ?>>NUEVO INGRESO</option>
									<option value="R" <?php if($modo_insc == 'R'){echo "selected";} ?>>REGULAR</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col col-250px">
							<div class="contInput">
								<div class="nomInput"><i class="icon-attention-circled"></i>Condición</div>
								<select name="condicion" class="input">
									<option value="0">SELECCIONAR</option>
									<option value="P" <?php if($condicion == 'P'){echo 'selected';}?> >PROMOVIDO</option>
									<option value="R" <?php if($condicion == 'R'){echo 'selected';}?> >REPITIENTE</option>
								</select>
							</div>
						</div>
						<!-- fecha -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Fecha</p>
								<input type="date" name="fecha" value="<?php echo $fechaIncs; ?>" class="input"/>
							</div>
						</div>
						<!-- traslado -->
						<div class="col col-250px">
							<div class="contInput">
								<p class="nomInput"><i class="icon-attention-circled"></i>Traslado</p>
								SI <input type="radio" name="traslado" value="si"/>
								NO <input type="radio" name="traslado" value="no" checked="true"/>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col col50">
							<div class="contInput">
								<p class="left nomInput"><i class="icon-attention-circled"></i>Procedencia</p>
								<div class="right nomInput">Marcar para esta Inst. <input type="checkbox" name="marca" onclick="marcaProcedencia(this)" style="margin-left: 5px;"></div>
								<input type="text" name="procedencia" class="input" placeholder='Ej: U.E.N.B "SAMUEL ROBINSON"' value='<?php echo $procedencia; ?>'/>
							</div>
						</div>
						<div class="col col50">
							<div class="contInput">
								<p class="nomInput"><i id="icon_motivo" class="icon-attention-circled none"></i>Motivo</p>
								<input type="text" name="motivo" class="input" value="<?php echo $motivo; ?>" disabled/>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col col100">
							<div id="msjErrorInsc" class="none msj_error anm1">
								<p><i class="icon-attention text_amarrilo text_bold"></i><b>Los campos marcados con <i class="icon-attention-circled text_azul"></i> son requeridos</b>.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- boton registrar/modificar inscripción -->
			<?php 
			# No existe la inscripción en el año escolar y el estatus no es graduado(4)
			if( $codInsc == '' && $estatus != '4'){ 
				if( $sI == '1' ){ # Seguridad / Incluir
			?>
			<div class="right">
				<label for="btnRegInsc" class="btn_icon_split btn_verde btn_normal btn_md shadow-sm">
					<i class="icon-check"></i>
					<p>Registrar Inscripción</p>
				</label>
				<input type="button" name="btnRegInsc" id="btnRegInsc" class="none" />
			</div>
			<?php 
				}
			}
			# Existe la isncripcíon y el estatus no es graduado(4)
			else if( $codInsc != '' && $estatus != '4' ){ 
				if( $sM == '1' ){ # seguridad / modificar
			?>
			<div class="right">
				<label for="btnModInsc" class="btn_icon_split btn_verde btn_normal btn_md shadow-sm">
					<i class="icon-edit"></i>
					<p>Guardar cambios</p>
				</label>
				<input type="button" name="btnModInsc" id="btnModInsc" class="none" />
			</div>
			<?php 
				}
			} 
			?>
		</div>
		<?php } ?>	

		<div class="titulo_m_md">
			<h2>Historial Académico</h2><i class="icon-history"></i>
		</div>
		<div class="caja s_n3">
			<table width="96%" class="tabla3" cellspacing="0" style="font-size: 12px;">
				<tr>
					<th>Representante</th>
					<th>Grado y Sección</th>
					<th>Fecha</th>
					<th>Modalidad</th>
					<th>Condición</th>
					<th>Procedencia</th>
					<th>Motivo</th>
					<th>Año escolar</th>
				</tr>
				<?php imp_historial_Insc($codPer); ?>
			</table>
		</div>

		<?php if( !isset($_GET['modo']) ){ ?>
		<div class="division s_n"></div>
		<div class="marginB-2">
			<!-- Botón Regresar -->
			<a href="?Estudiante=consultar" class="btn_icon_split btn_gris2 btn_normal btn_md shadow-sm">
				<i class="icon-left-big"></i>
				<p>Consultar Estudiante</p>
			</a>
		</div>
		<?php } ?>
	</form>

	<!-- 3.- RENDIMIENDO -->
	<div class="caja_contForm none">
		<?php 
			if( $rsProm1 == '' && $rsProm2 == '' && $rsProm3 == '' ){
		?>
		<div class="msj_lg">
			<i class="icon-attention rojo"></i><h3>No se encontraron resultados.</h3>
		</div>
		<?php  
		}else{
		?>

		<?php if( $rsProm1 != '' ){ ?>
		<div class="caja marginB-3">
			<div class="dsp_boton" onclick="desplegar_rendimiento(this);">
				<div class="dsp_texto">PRIMER LAPSO</div>
				<div class="dsp_icono"><i class="icon-down-open"></i></div>
			</div>
			<div class="dsp_hijo">
				<table class="tabla3" cellspacing="0" id="resultados" width="96%">
					<tr>
						<th colspan="3" class="text_left"><b class="text_rosa">Nombre del P.A:</b> <?php echo $rsPA1['nom_pa']; ?></th>
					</tr>
					<tr>
						<th width="40px">#</th>
						<th>Indicador de Evaluación</th>
						<th width="80px">Literal</th>
					</tr>
					<?php imprimir_notas($rsInd1); ?>
					<tr>
						<td colspan="3" ><b class="text_rosa">Promedio:</b> <b>"<?php echo $rsProm1['nota']; ?>"</b></td>
					</tr>
				</table>
			</div>
		</div>
		<?php } ?>
		<?php if( $rsProm2 != '' ){ ?>
		<div class="caja marginB-3">
			<div class="dsp_boton" onclick="desplegar_rendimiento(this);">
				<div class="dsp_texto">SEGUNDO LAPSO</div>
				<div class="dsp_icono"><i class="icon-down-open"></i></div>
			</div>
			<div class="dsp_hijo">
				<table class="tabla3" cellspacing="0" id="resultados" width="96%">
					<tr>
						<th colspan="3" class="text_left"><b class="text_rosa">Nombre del P.A:</b> <?php echo $rsPA2['nom_pa']; ?></th>
					</tr>
					<tr>
						<th width="40px">#</th>
						<th>Indicador de Evaluación</th>
						<th width="80px">Literal</th>
					</tr>
					<?php imprimir_notas($rsInd2); ?>
					<tr>
						<td colspan="3" ><b class="text_rosa">Promedio:</b> <b>"<?php echo $rsProm2['nota']; ?>"</b></td>
					</tr>
				</table>
			</div>
		</div>
		<?php } ?>
		<?php if( $rsProm3 != '' ){ ?>
		<div class="caja marginB-3">
			<div class="dsp_boton" onclick="desplegar_rendimiento(this);">
				<div class="dsp_texto">TERCER LAPSO</div>
				<div class="dsp_icono"><i class="icon-down-open"></i></div>
			</div>
			<div class="dsp_hijo">
				<table class="tabla3" cellspacing="0" id="resultados" width="96%">
					<tr>
						<th colspan="3" class="text_left"><b class="text_rosa">Nombre del P.A:</b> <?php echo $rsPA3['nom_pa']; ?></th>
					</tr>
					<tr>
						<th width="40px">#</th>
						<th>Indicador de Evaluación</th>
						<th width="80px">Literal</th>
					</tr>
					<?php imprimir_notas($rsInd3); ?>
					<tr>
						<td colspan="3" ><b class="text_rosa">Promedio:</b> <b>"<?php echo $rsProm3['nota']; ?>"</b></td>
					</tr>
				</table>
			</div>
		</div>
		<?php } ?>
		<?php 
		}
		?>
	</div>

	<!-- 4.- REPORTES -->
	<form name="f_reportes" class="caja_contForm none">
		<?php 
			if( strlen($ced_estH)>2 ){
				$cedulaRepSol = $ced_estH;
			}else{
				$cedulaRepSol = $cedEscH;
			}
			if( $estatus == '1' || $estatus == '2' ){
				if( $codInsc != '' ){
		?>
		<a href="../CONTROL/c_reportesEst.php?reporte=1&cedEsc=<?php echo $cedulaRepSol;?>&aesc=<?php echo $cod_AESC;?>" target="_blank" class="btn btn_icon_square lg">
			<i class="icon-file-pdf"></i>
			<p>Constancia de Inscripción</p>
		</a>
	
		<a href="../CONTROL/c_reportesEst.php?reporte=2&cedEsc=<?php echo $cedulaRepSol;?>&aesc=<?php echo $cod_AESC;?>" target="_blank" class="btn btn_icon_square lg">
			<i class="icon-file-pdf"></i>
			<p>Constancia de Estudio</p>
		</a>

		<a href="../CONTROL/c_reportesEst.php?reporte=5&cedEsc=<?php echo $cedulaRepSol;?>&aesc=<?php echo $cod_AESC;?>" target="_blank" class="btn btn_icon_square lg">
			<i class="icon-file-pdf"></i>
			<p>Constancia de Prosecución</p>
		</a>
			<?php }else{ ?>
				<div class="msj_lg">
					<i class="icon-attention rojo"></i><h3>No hay reportes disponibles.</h3>
				</div>
			<?php } ?>
		<?php }else if( $estatus == '3' ){?>
		<a href="../CONTROL/c_reportesEst.php?reporte=4&cedEsc=<?php echo $cedulaRepSol;?>&aesc=<?php echo $cod_AESC;?>" target="_blank" class="btn btn_icon_square lg">
			<i class="icon-file-pdf"></i>
			<p>Constancia de Retiro</p>
		</a>

		<a href="../CONTROL/c_reportesEst.php?reporte=3&cedEsc=<?php echo $cedulaRepSol;?>&aesc=<?php echo $cod_AESC;?>" target="_blank" class="btn btn_icon_square lg">
			<i class="icon-file-pdf"></i>
			<p>Constancia de Conducta</p>
		</a>
		<?php }else if( $estatus == '4' ){ ?>
		<a href="../CONTROL/c_reportesEst.php?reporte=5&cedEsc=<?php echo $cedulaRepSol;?>&aesc=<?php echo $cod_AESC;?>" target="_blank" class="btn btn_icon_square lg">
			<i class="icon-file-pdf"></i>
			<p>Constancia de Prosecución</p>
		</a>
		<a href="../CONTROL/c_reportesEst.php?reporte=6&cedEsc=<?php echo $cedulaRepSol;?>&aesc=<?php echo $cod_AESC;?>" target="_blank" class="btn btn_icon_square lg">
			<i class="icon-file-pdf"></i>
			<p>Certificado de Promoción</p>
		</a>
		<?php 
		}else{ # no hay reportes para mostrar?>
		<div class="msj_lg">
			<i class="icon-attention rojo"></i><h3>No hay reportes disponibles.</h3>
		</div>
		<?php } ?>
	</form>
</section>
</div>
<?php 
} # END de la primera condicion de este archivo <<<<<<<<<<<<<<
?>

<?php 
	function javascript($arg){
		echo '<script type="text/javascript">'.$arg.'</script>';
	}
?>


