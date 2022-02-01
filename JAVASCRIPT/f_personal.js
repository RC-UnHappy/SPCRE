window.onload = function(){ // Se cargan los eventos una vez que la página haya cargado
	// FORMULARIO PERSONAL
	f = document.f_personal;
	btnP = document.getElementsByClassName('btnP');

	if( f.btnEnvReg ){
		f.btnEnvReg.onclick = registrar_personal;
	}
	if( f.btnEnvMod ){
		f.btnEnvMod.onclick = modificar_personal;
	}
	
	// eventos de teclado
	f.ced.onkeypress = solo_numeros;
	f.nom.onkeypress = f.nom2.onkeypress = solo_letras;
	f.ape.onkeypress = f.ape2.onkeypress = solo_letras;
	f.tlfm.onkeypress = solo_numeros; f.tlfm.onkeydown = agrega_guionTlf;

	// eventos onchange
	f.ced.onblur = vld_ced;
	f.nom.onblur = vld_nom; f.nom2.onblur = vld_nom2;
	f.ape.onblur = vld_ape; f.ape2.onblur = vld_ape2;
	f.fnac.onchange = calcularEdad;
	f.email.onchange = vld_email;
	f.tlfm.onchange = vld_tlfm;

	// eventos click
	f.crear_usuario[0].onclick = f.crear_usuario[1].onclick = crear_usuario;
	

	//formulario consultar personal:
	fBus = document.fbuscarper;
	fBus.onkeypress = solo_numeros;
	if( fBus.cedula ){
		fBus.cedula.onkeyup = pressEnter;
		fBus.btnCons.onclick = consultarPersonal; 
	}
		
	msjBox = document.getElementsByClassName('msjBox'); 
	secciones = document.getElementsByClassName('secciones');
	urlVariables();
}

function registrar_personal(){
	f.ope.value = 'reg';
	validar_formulario();
}

function modificar_personal(){
	f.ope.value = 'mod';
	validar_formulario();
}

function validar_formulario(){ // valida antes de enviar
	submit = true;
	if( !vld_ced() ){
		submit = false;
		window.scrollTo(0, 100);
	}
	else if( !vld_nom() ){
		submit = false;
		window.scrollTo(0, 100);
	}
	else if( !vld_ape() ){
		submit = false;
		window.scrollTo(0, 100);
	}
	else if( !vld_sexo() ){
		submit = false;
		window.scrollTo(0, 100);
	}
	else if( !vld_nom2() ){
		submit = false;
		window.scrollTo(0, 100);
	}
	else if( !vld_ape2() ){
		submit = false;
		window.scrollTo(0, 100);
	}
	else if( !vld_nivel() ){
		submit = false;
		window.scrollTo(0, 100);
	}
	else if( !vld_cargo() ){
		submit = false;
		window.scrollTo(0, 100);
	}
	else if( !vld_funcion() ){
		submit = false;
		window.scrollTo(0, 100);
	}
	else if( !vld_tlfm() ){
		submit = false;
		window.scrollTo(0, 100);
	}
	else if( !vld_email() ){
		submit = false;
		window.scrollTo(0, 100);
	}
	else if( !vld_nivel_usuario() ){
		submit = false;
		window.scrollTo(0, 100);
	}
	if( submit == true ){
		if( f.ope.value == 'reg' ){
			enviar();
		}
		else if( f.ope.value == 'mod' ){
			confirm_modificar();
		}
	}
}

function enviar(){ // envia el formulario 
	// activa los campos para evitar errores en el controlador
	f.tdoc.disabled = false;
	f.ced.disabled = false;
	f.submit();
}

function confirm_modificar(){ // muestra la ventana para confirmar antes de enviar el formulario
	document.getElementById('tlt').innerHTML = 'Modificar: '+f.ciPer.value;
	document.getElementById('b1').onclick = enviar;
	document.getElementById('b1').innerHTML = '<p><i class="icon-edit"></i> Continuar</p>';
	document.getElementById('b2').style.display = 'block';
	OpenWindowNot('¿Desea continuar?');
}

// Valida el campo cedula
function vld_ced(){
	ocultar_msj(msjBox); // limpia los msjBox
	if( validar_cedula(f.ced,0,'si') ){
		return true;
	}
}
// Valida el campo nombre
function vld_nom(){
	ocultar_msj(msjBox);
	if( validar_nombre(f.nom,1,'si') ){
		return true;
	}
}
// valida el campo apellido
function vld_ape(){
	ocultar_msj(msjBox);
	if( validar_apellido(f.ape,2,'si') ){
		return true;
	}
}
function vld_sexo(){
	ocultar_msj(msjBox); // limpia los msjBox
	if( validar_comboSelect(f.sexo,3)){
		return true;
	}
}
function vld_nom2(){
	ocultar_msj(msjBox);
	if( validar_nombre(f.nom2,4,'no') ){
		return true;
	}
}
function vld_ape2(){
	ocultar_msj(msjBox);
	if( validar_apellido(f.ape2,5,'no') ){
		return true;
	}
}
// valida nivel
function vld_nivel(){
	ocultar_msj(msjBox); // limpia los msjBox
	if( validar_comboSelect(f.nivel,6)){
		return true;
	}
}
// valida el cargo
function vld_cargo(){
	ocultar_msj(msjBox); // limpia los msjBox
	if( validar_comboSelect(f.cargo,7)){
		return true;
	}
}
// valida funcion
function vld_funcion(){
	ocultar_msj(msjBox); // limpia los msjBox
	if( validar_comboSelect(f.funcion,8)){
		return true;
	}
}
// validación de teléfonos
function vld_tlfm(){
	ocultar_msj(msjBox);
	if( validar_telefono(f.tlfm,9,'si') ){
		return true;
	}
}
// valida el campo email
function vld_email(){
	ocultar_msj(msjBox); 
	if( validar_email(f.email,10,'no') ){
		return true;
	}
}
function vld_nivel_usuario(){
	if( f.crear_usuario[0].checked ==  true ){
		if( validar_comboSelect(f.nivel_usuario,11) ){
			return true;
		}
	}
	else{
		return true;
	}
}
function crear_usuario(){
	if( this.value == 'si' ){
		document.getElementById('nivel_usuario').classList.remove('none');
	}
	else if( this.value == 'no' ){
		document.getElementById('nivel_usuario').classList.add('none');
	}
}


// FORMULARIO CONSULTAR PERSONAL
function consultarPersonal(){
	if( fBus.cedula.value.trim() == 0 ){
		alert('Debe escribir la cédula');
	}
	else{
		cedPer = fBus.tipo_doc.value+'-'+fBus.cedula.value;
		window.location.href = '?Personal=visualizar&cedula='+cedPer;
	}
}
function pressEnter(event){
	if( PressEnter(event) ){
		consultarPersonal(); // presiona enter: envia 
	}
}
// FORMULARIO REESTARBLECER CONTRASEÑA
function resetPass(){
	fPass.ope
	fPass.submit();
}

function confirm_rsPass(){
	// muestra la ventana para confirmar 
	document.getElementById('b1').onclick = resetPass;
	document.getElementById('b1').innerHTML = '<p>Continuar</p>';
	document.getElementById('b2').style.display = 'block';
	OpenWindowNot('¿Desea reestablecer la contraseña?');
}


function urlVariables(){ // busca variables en la URL
	msjReg = '¡Los datos se han registrado correctamente! <i class="icon-ok-circled2"></i>';
	msjMod = '¡Los cambios se han guardado correctamente! <i class="icon-ok-circled2"></i>';
	msjModPass = '¡La contraseña se ha reestablecido correctamente! <i class="icon-ok-circled2"></i>';
	msjError = '¡Error, no se ha podido realizar la operación! <i class="icon-cancel-circled2"></i>';
	pError = '<p class="msj_error">La <b>Cédula</b> ya se encuentra registrada.</p>';
	msjError2 = '¡No se encontraron resultados! <i class="icon-cancel-circled2"></i>';

	if( vmod = getVariable('mod') ){ 
		if( vmod == "true" ){
			OpenWindowNot(msjMod);
		}
	}  
	if( vmodPass = getVariable('resetPass') ){
		if( vmodPass == "true" ){
			OpenWindowNot(msjModPass);
		}
	}
	if( vError = getVariable('error') ){
		if( vError == 'cedula' ){ 
			OpenWindowNot(msjError+pError);
		}
	} 

	vP = getVariable('Personal');
	if( vP == 'registrar'){
		setTitulo('Registrar Personal','user-plus');
		secciones[1].classList.remove('none');
		btnP[0].classList.remove('none');
		document.getElementById('crear_usuario').classList.remove('none');
	}
	else if( vP == 'visualizar' ){
		setTitulo('Modificar Personal','edit');
		secciones[1].classList.remove('none');
		btnP[1].classList.remove('none');
	}

	else if( vP == 'consultar' ){
		setTitulo('Datos Del Personal','user');
		secciones[0].classList.remove('none');	
	}

	if(vError = getVariable('error') ){
		if(vError == '1'){
			OpenWindowNot(msjError2);
		}
	}
}
function setTitulo(name,icon){ // cambia el titulo de la página
	p = '<h2>'+name+'</h2>';
	i = '<i class="icon-'+icon+'"></i>';
	document.getElementById('tt').innerHTML = p+i;
}

function calcularEdad(){ 
	//calcula la edad del estudiante dependiendo de la fecha actual del servidor y la fecha de nacimiento
	fechaActual = f.fechaActual.value;
	fechaNacimiento = f.fnac.value;
	if( fechaActual != '' && fechaNacimiento != '' ){
		edad = fechaActual.substr(0,4)-fechaNacimiento.substr(0,4); // edad en años
		mes = fechaActual.substr(5,2)-fechaNacimiento.substr(5,2); // diferencia de mes
		diaActual = fechaActual.substr(8,2);
		diaNacimiento = fechaNacimiento.substr(8,2);
		if (mes < 0 || mes === 0 && diaActual < diaNacimiento){
	        edad--;
	    }
	  	f.edad.value = edad;
	}
	if( edad <= 0 ){
		f.edad.value = 0;
	}
}

