window.onload = function(){ // se cargan los eventos al cargar la página
	item_menu = document.getElementsByClassName('item_menu');
	contForm = document.getElementsByClassName('contForm');
	msjBox = document.getElementsByClassName('msjBox');

	// Formulario Datos Personales
	fPersona = document.persona;
	fPersona.enviar.onclick = enviarDatosPersona;
	fPersona.foto.onchange = validar_foto;
	fPersona.nom.onkeypress = fPersona.ape.onkeypress = solo_letras;
	fPersona.nom.onblur = vld_nom;
	fPersona.ape.onblur = vld_ape;
	fPersona.email.onchange = vld_email;
	fPersona.tlfm.onkeypress = fPersona.tlff.onkeypress = solo_numeros;
	fPersona.tlfm.onkeydown = fPersona.tlff.onkeydown = agrega_guionTlf;
	fPersona.tlfm.onblur = vld_tlfm; fPersona.tlff.onblur = vld_tlff;

	// Formulario Cambiar contraseña
	fPass = document.cambiarPass;
	fPass.enviar.onclick = enviarCambiarPass;
	fPass.pass.onchange = vld_pass;
	fPass.npass.onchange = vld_npass;
	fPass.rnpass.onchange = vld_rnpass;

	// Formulario preguntas de seguridad
	fSeg = document.preguntas;
	fSeg.enviar.onclick = enviarSeguridad;
	fSeg.preg1.onchange = vld_p1; fSeg.preg2.onchange = vld_p2;

	urlVariablesGet();

	//Administrador Central
	if( fPersona.ciPer.value == 'V-AdminC' ){
		// solo muestra el formulario para cambiar la contraseña
		h2 = document.getElementsByTagName('h2');
		h2[1].innerHTML = 'Cambiar Contraseña';
		document.getElementById('menu-x').style.display = 'none';
		contForm[0].classList.add('none');
		contForm[1].classList.remove('none');
	}
}

function urlVariablesGet(){ // captura las variables de la URL
	if( vGet = getVariable('form') ){
		ocultar_formulario();

		switch( vGet ){
			case "1":
			mostrar_formulario(0);
			break;

			case "2":
			mostrar_formulario(1);
			break;

			case "3":
			mostrar_formulario(2);
			break;
		}
	}

	if( vGet = getVariable('mod') ){ // existe variable get
		if( vGet == 'true' ){
			OpenWindowNot('¡Los datos han sido modificados correctamente!<i class="icon-ok-circled2"></i>'); // muestra ventana
		}
	}
	if( vGet = getVariable('modPass') ){ // existe variable get
		if( vGet == 'true' ){
			OpenWindowNot('¡La contraseña ha sido cambiada con éxito!<i class="icon-ok-circled2"></i>'); // muestra alerta
		}
		else if( vGet == 'false' ){
			msj = '¡Error, no se ha podido cambiar la contraseña! <i class="icon-cancel-circled2"></i>';
			p = '<p class="msj_error">La contraseña es incorrecta. Inténtelo nuevamente.</p>';
			OpenWindowNot(msj+p); // muestra alerta
		}
	}
	if( vGet = getVariable('modPregSeg') ){
		if( vGet == 'true' ){
			OpenWindowNot('¡Los datos han sido modificados correctamente!<i class="icon-ok-circled2"></i>'); //
		}
		else if( vGet == 'false' ){
			msj = '¡Error, no se han podido guardar los cambios! <i class="icon-cancel-circled2"></i>';
			p = '<p class="msj_error">La contraseña es incorrecta. Inténtelo nuevamente.</p>';
			OpenWindowNot(msj+p); // muestra alerta
		}
	}
}

function mostrar_formulario(i){ // muestra formulario oculto
	ocultar_formulario(); // oculta el resto
	contForm[i].classList.remove('none'); // muestra
	item_menu[i].classList.add('selected');
}

function ocultar_formulario(){ // deselecciona el item, cambia la propiedad clase
	for(i=0; i<item_menu.length; i++){
		item_menu[i].classList.remove('selected');
		contForm[i].classList.add('none');
	}
}

// Formulario Datos Personales
function enviarDatosPersona(){
	submit = true;
	if( !vld_nom() ){
		submit = false;
	}
	else if( !vld_ape() ){
		submit = false;
	}
	else if( !vld_email() ){
		submit = false;
	}
	else if( !vld_tlfm() ){
		submit = false;
	}
	else if( !vld_tlff() ){
		submit = false;
	}
	else if( !validar_foto() ){
		submit = false;
	}
	if( submit == true ){ // todo bien
		fPersona.ope.value = 'modDatosPer';
		fPersona.submit(); // envia el formulario
	}
}

function validar_foto(){
	if( fPersona.foto.value == '' ){
		return true;
	}
	else{
		if( validar_fotoImg(fPersona.foto) ){
			preview = document.getElementById('foto_previa');
			previsualizar(fPersona.foto, preview);
			return true;
		}else{
			fPersona.foto.value = '';
			return false;
		}
	}
}
function vld_email(){ // valida el campo email
	ocultar_msj(msjBox);
	if( validar_email(fPersona.email,0,'no') ){
		return true;
	}
}
function vld_nom(){ // Valida el campo nombre
	ocultar_msj(msjBox); // limpia los msjbox
	if( validar_nombre(fPersona.nom,1,'si') ){
		return true;
	}
}
function vld_ape(){ // valida el campo apellido
	ocultar_msj(msjBox); 
	if( validar_apellido(fPersona.ape,2,'si') ){
		return true;
	}
}
// validación de teléfonos
function vld_tlfm(){
	ocultar_msj(msjBox);
	if( validar_telefono(fPersona.tlfm,3,'si') ){
		return true;
	}
}
function vld_tlff(){
	ocultar_msj(msjBox);
	if( validar_telefono(fPersona.tlff,4,'no') ){
		return true;
	}
}

// Formulario Cambiar contraseña
function enviarCambiarPass(){
	submit = true;
	if( !vld_pass() ){
		submit = false;
	}
	else if( !vld_npass() ){
		submit = false;
	}
	else if( !vld_rnpass() ){
		submit = false;
	}
	if( submit == true ){
		fPass.ope.value = 'changePass';
		fPass.submit();
	}
}
// valida campo contraseña actual
function vld_pass(){
	ocultar_msj(msjBox); // limpia los msjBox
	if( fPass.pass.value.trim() == '' ){
		msjBox[5].classList.add('show'); 
		msjBox[5].innerHTML='<i class="icon-attention"></i> Escriba la contraseña';
	}
	else{
		return true;
	}
}
// valida campo nueva contraseña
function vld_npass(){
	ocultar_msj(msjBox); // limpia los msjBox
	campo = fPass.npass;
	if( campo.value.trim() == '' ){
		msjBox[6].classList.add('show'); 
		msjBox[6].innerHTML='<i class="icon-attention"></i> Escriba la nueva contraseña';
	}
	else if( campo.value.trim().length < 8 ){
		msjBox[6].classList.add('show'); 
		msjBox[6].innerHTML='<i class="icon-attention"></i> La contraseña debe tener mínimo 8 caractéres';
	}
	else if( !verfSimbolNumberPass(campo.value.trim(),numeros_perm) ){
		msjBox[6].classList.add('show');
		msjBox[6].innerHTML = '<i class="icon-attention"></i> La contraseña debe tener al menos un número';
	}
	else if( !verfSimbolNumberPass(campo.value.trim(),simbolos_perm) ){
		msjBox[6].classList.add('show');
		msjBox[6].innerHTML = '<i class="icon-attention"></i> La contraseña debe tener al menos un símbolo '+simbolos_perm;
	}
	else{
		return true;
	}
}
// valida campo repetir nueva contraseña
function vld_rnpass(){
	ocultar_msj(msjBox); // limpia los msjBox
	campo = fPass.rnpass;
	if( campo.value.trim() == '' ){
		msjBox[7].classList.add('show'); 
		msjBox[7].innerHTML='<i class="icon-attention"></i> Repita la contraseña';
	}
	else if( campo.value.trim() != fPass.npass.value.trim() ){
		msjBox[7].classList.add('show'); 
		msjBox[7].innerHTML='<i class="icon-attention"></i> Las contraseñas no coinciden';
	}
	else{
		return true;
	}
}

// funciones preguntas de seguridad
function enviarSeguridad(){
	submit = true;
	if( !vld_p1() ){
		submit = false;
	}
	else if( !vld_r1() ){
		submit = false;
	}
	else if( !vld_p2() ){
		submit = false;
	}
	else if( !vld_r2() ){
		submit = false;
	}
	else if( fSeg.pass.value.trim() == '' ){ // contraseña
		ocultar_msj(msjBox);
		msjBox[12].innerHTML = icon_attention+' Escriba la contraseña';
		mostrar_msj(msjBox[12]);
		submit = false;
	}
	if( submit == true ){
		fSeg.ope.value = 'pregSeg';
		fSeg.submit();
	}
}

function vld_p1(){
	if( validar_comboSelect(fSeg.preg1,8) ){
		return true;
	}
}
function vld_r1(){
	if( vld_respuesta(fSeg.resp1,9) ){
		return true;
	}
}
function vld_p2(){
	if( vld_pregunta(fSeg.preg2,10) ){
		return true;
	}
}
function vld_r2(){
	if( vld_respuesta(fSeg.resp2,11) ){
		return true;
	}
}
// valida las preguntas
function vld_pregunta(campo,i){
	ocultar_msj(msjBox);
	if( campo.value.trim() == '' ){
		mostrar_msj(msjBox[i]);
		msjBox[i].innerHTML= icon_attention+'El campo es requerido';
	}
	else if( campo.value.trim().length < 5 ){
		mostrar_msj(msjBox[i]);
		msjBox[i].innerHTML= icon_attention+'Mínimo 5 caractéres';
	}
	else{
		return true;
	}
}
// valida que las respuetas no estén vacias
function vld_respuesta(campo, i){
	ocultar_msj(msjBox);
	if( campo.value.trim() == '' ){
		mostrar_msj(msjBox[i]);
		msjBox[i].innerHTML= icon_attention+'El campo es requerido';
	}
	else if( campo.value.trim().length < 3 ){
		mostrar_msj(msjBox[i]);
		msjBox[i].innerHTML= icon_attention+'Mínimo 3 caractéres';
	}
	else{
		return true;
	}
}
