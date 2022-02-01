function ready(callbackFunction){
  if(document.readyState != 'loading')
    callbackFunction(event)
  else
    document.addEventListener("DOMContentLoaded", callbackFunction)
}

ready(event => {
	// se cargan los eventos al cargar la página
	 // formulario login
	f = document.form_login;
	f.enviar.onclick = login;
	f.user.oninput = limpiar;
	f.pass.oninput = limpiar;
	//f.user.onkeyup = pressEnter;
	f.pass.onkeydown = pressEnter;
	f.pass.onkeyup = verOjo;
	f.user.onfocus = f.pass.onfocus = enfoca; // al enfocar, cambia la opacidad del fondo negro a un poco mas oscura
	f.user.onblur = f.pass.onblur = desenfoca; 

	// formulario recuperar contraseña
	fr = document.recuperar_pass;
	fr.enviar.onclick = enviarRecPass;
	fr.user.onkeypress = solo_numeros;

	// formulario preguntas de seguridad
	fp = document.preguntasSeg;
	fp.enviar.onclick = enviarPregSeg;

	// formulario cambiar contraseña
	fcc = document.nuevaPass;
	fcc.enviar.onclick = enviarChangePass;

	msjBox = document.getElementsByClassName('msjBox');
	cont_w = document.getElementsByClassName('cont_window')[0]; 	

	// Variables GET
	getVariables();
	// fondo dinamico en el banner
	fondoDinamico();
});


function login(){ // valida que los campos no esten vacios
	ocultar_msj(msjBox);
	if( f.user.value.trim() == ''){
		mostrar_msj(msjBox[0]);
		agrega_texto(0,'Ingrése el usuario');
	}
	else if( f.pass.value.trim() == ''){
		mostrar_msj(msjBox[1]);
		agrega_texto(1,'Ingrese la contraseña');
	}
	else{
		f.pass.type = 'password';
		f.submit(); // envia el formulario
	}
}

function agrega_texto(i,texto){ // agrega texto a los msjBox
	msjBox[i].innerHTML = icon_attention+texto;
}
function limpiar(){ // oculta los msjBox
	ocultar_msj(msjBox);
}

function pressEnter(e){
	evento = e || window.event;
	tecla = codigoTecla(evento);
	if( tecla == 13 ){ // presiona enter
		login(); // envia el formulario
	}
}

// cambia la opacidad de la capa "id="fondo" cuando se enfoca un input
function enfoca(){
	document.getElementById('fondo').style.opacity = '.6';	
}
function desenfoca(){
	document.getElementById('fondo').style.opacity = '.4';
}

// escuela
mostrar = false;
function mostrar_escuela(elem){ // muestra mision,vision...
	if( mostrar == false ){
		// muestra
		document.getElementById('textos_escuela').style = 'max-height:400px;';
		elem.style.transform = 'rotate(180deg)';
		mostrar = true;
	}
	else{
		// oculta
		document.getElementById('textos_escuela').style = 'max-height: 0;';
		elem.style.transform = 'rotate(0)';
		mostrar = false;
	}
}

function verPass(p){
	if( f.pass.type == 'password' ){
		f.pass.type = 'text';
		p.classList.remove('icon-eye');
		p.classList.add('icon-eye-off');
	}
	else{
		f.pass.type = 'password';
		p.classList.remove('icon-eye-off');
		p.classList.add('icon-eye');
	}
}

function verOjo(){
	ojo = document.getElementById('ojo');
	if(f.pass.value.trim().length > 0){
		ojo.classList.remove('none');
	}
	else{
		ojo.classList.add('none');
	}
}

// recuperar contraseña
// VENTANA
function abrir_ventana(){ 
	// cambia el nombre de la clase y muestra el formulario
	cont_w.classList.remove('hidden');
}
function cerrar_ventana(){
	cont_w.classList.add('hidden');
}
function mostrar_formulario(i){
	form = document.getElementsByTagName('form');
	form[i].classList.remove('none'); // muestra
}
function ocultar_formularios(){
	forms = document.getElementsByTagName('form');
	forms[1].classList.add('none'); // oculta
}

// enviar para consultar preguntas secretas del usuraio
function enviarRecPass(){
	if( fr.user.value.trim() == '' ){
		mostrar_msj(msjBox[2]);
		agrega_texto(2,'Ingrese el usuario');
	}
	else{
		fr.ope.value = 'buscarUsu';
		fr.submit();
	}
}

// preguntas de seguridad
function enviarPregSeg(){
	ocultar_msj(msjBox);
	// valida los campos
	if( fp.r1.value.trim() == '' ){
		mostrar_msj(msjBox[3]);
		agrega_texto(3,'Escriba su respuesta');
	}
	else if(fp.r2.value.trim() == '' ){
		mostrar_msj(msjBox[4]);
		agrega_texto(4,'Escriba su respuesta');
	}
	else{
		fp.ope.value = 'pregSeg';
		fp.submit(); // envia el formulario
	}
}

// cambiar la contraseña
function enviarChangePass(){
	ocultar_msj(msjBox);
	if( fcc.pass.value.trim() == '' ){
		mostrar_msj(msjBox[5]);
		agrega_texto(5,'Escriba la contraseña');
	}
	else if( fcc.pass.value.trim().length < 8 ){ // minimo 8
		mostrar_msj(msjBox[5]); 
		agrega_texto(5,'La contraseña debe tener mínimo 8 caractéres');
	}
	else if( !verfSimbolNumberPass(fcc.pass.value.trim(),numeros_perm) ){ // un numero
		mostrar_msj(msjBox[5]);
		agrega_texto(5,'La contraseña debe tener al menos un número');
	}
	else if( !verfSimbolNumberPass(fcc.pass.value.trim(),simbolos_perm) ){ // un simbolo
		mostrar_msj(msjBox[5]);
		agrega_texto(5,'La contraseña debe tener al menos un símbolo: '+simbolos_perm);
	}
	else if( fcc.rpass.value.trim().length == '' ){
		mostrar_msj(msjBox[6]);
		agrega_texto(6,'Repita la contraseña');
	}
	else if( fcc.rpass.value.trim() != fcc.pass.value.trim() ){ // las contraseñas no coinciden
		mostrar_msj(msjBox[6]);
		agrega_texto(6,'Las contraseñas no coincíden');
	}
	else{
		fcc.ope.value = 'newPass';
		fcc.submit();
	}
}

// Banner: imagenes dinamicas
function fondoDinamico(){
	imgs = document.getElementsByClassName('imgBanner');
	i = imgs.length;
	cont = 0;
	objImg = setInterval( function(){
		hidden_imgs();
		imgs[cont].classList.remove('hidden');
		imgs[cont].classList.add('zoom');
		cont++;
		if( cont == i ){
			cont = 0;
		}
	},6000);
}

function hidden_imgs(){ // oculta las imagenes en el banner
	imgs = document.getElementsByClassName('imgBanner');
	for( i=0; i<imgs.length; i++ ){
		imgs[i].classList.add('hidden');
		imgs[i].classList.remove('zoom');
	}
}
// // intervalos de tiempo
// function intervaloTiempo( funcion, tiempo ){
// 	objeto = setInterval(sumar, tiempo);
// }
// function changeBackground(){
// 	cont++;
// 	if( cont == 5 ){
// 		stopIntervaloTiempo(objeto);
// 	}
// }
// function detener(objImg){
// 	clearInterval(objImg);
// }

function getVariables(){
	// existe variable Get error
	if( varGetError = getVariable('error') ){
		
		if(varUserGet = getVariable('user')){
			doc_id = varUserGet.substr(0,1); // tipo de documento
			ced = varUserGet.substr(1); // cedula
		}
		
		switch( varGetError ){
			// inicio de sesión
			case 'user':
				mostrar_msj(msjBox[0]);
				agrega_texto(0,'Usuario incorrecto');
				f.user.value = ced; f.d_ID.value = doc_id;
				break;

			case 'password':
				mostrar_msj(msjBox[1]);
				agrega_texto(1,'Contraseña incorrecta');
				f.user.value = ced; f.d_ID.value = doc_id;
				break;

			case '0':
				mostrar_msj(msjBox[0]);
				agrega_texto(0,'Debe iniciar sesión');
				break;

			case '1':
				alert('El usuario no ha configurado las preguntas de seguridad, no es posible recuperar la contraseña');
				break;

			// recuperar contraseña
			case 'user_rcp': 
				mostrar_msj(msjBox[2]);
				agrega_texto(2,'Usuario incorrecto');
				fr.doc_id.value = doc_id;
				fr.user.value = ced;
				abrir_ventana();
				break;

			case 'preg_rcp':
				mostrar_msj(msjBox[3]);
				agrega_texto(3,'Las respuestas son incorrectas');
				mostrar_msj(msjBox[4]);
				agrega_texto(4,'Las respuestas son incorrectas');
				ocultar_formularios();
				mostrar_formulario(2);
				abrir_ventana();
				break;

			case 'password_adv':
				alert('Advertencia: ¡Usted lleva 4 intentos, si falla una vez más el usuario será bloqueado!');
				break;

			case 'bloq':
				alert('¡Disculpe el usuario está bloqueado. Comuníquese con el administrador de su organización!');
				break;
		}
	}

	// recuperando la contraseña
	if( vRcp = getVariable('rcp_pass') ){
		ocultar_formularios();
		switch(vRcp){
			case '2':
				// muestra preguntas de seguridad
				if( fp.varRecPass.value != 'false'){
					mostrar_formulario(2);
					abrir_ventana();
				}
				break;
			case '3':
				// muestra formulario para cambiar la contraseña
				if( fcc.varRecPass.value != 'false'){
					mostrar_formulario(3);
					abrir_ventana();
				}
				break;
			case 'true':
				alert('La contraseña ha sido cambiada con éxito, inicie sesión');
				break;
		}
	}

	if( vTiempo = getVariable('tiempo') ){
		alert('¡Usted ha permanecido mucho tiempo inactivo y ha sido desconectado!');
	}
}
	
