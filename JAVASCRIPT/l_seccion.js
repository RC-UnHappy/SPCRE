// Se cargan los eventos una vez que la página haya cargado
window.onload = function(){
	// BUSCAR
	if( document.getElementById('txt_buscar') ){
		txt_buscar = document.getElementById('txt_buscar');
		txt_buscar.onkeypress = escribirPeriodo;
		btn_buscar = document.getElementById('btn_buscar');
		btn_buscar.onclick = buscarPeriodo;
	} 
	
	// TABLA 
	resultados = document.getElementById('resultados');
	thead = document.getElementById('thead').innerHTML;

	// VENTANAS
	if( document.getElementById('open-W-form') ){
		document.getElementById('open-W-form').onclick = W_open; // boton agregar
	}

	divForm = document.getElementById('form-seccion');
	document.getElementById('close-W-form').onclick = W_close // boton cerrar ventana

	// formulario 
	f = document.f_seccion;
	f.enviar.onclick = enviar;
	f.ope.value = 'add';
	f.cupos.value = 36;
	// eventos
	f.docente.onkeypress = solo_numeros;
	f.docente.onchange = vld_docente;
	f.grado.onchange = vld_grado;
	f.seccion.onchange = vld_seccion;
	f.aula.onchange = vld_aula;
	f.cupos.onkeypress = solo_numeros; f.cupos.onchange = vld_cupos;

	opAulas = f.aula.innerHTML;

	msjBox = document.getElementsByClassName('msjBox'); // cajas de dialogo que aparecen debajo de los inputs
	urlVariablesGet();
}

function urlVariablesGet(){
	if( vOpe = getVariable('ope') ){ // existe variable get add
		switch(vOpe){
			case 'add':
				msj1 = '¡Sección agregada! <i class="icon-ok-circled2"></i>';
				break;

			case 'mod':
				msj1 = '¡Sección modificada! <i class="icon-ok-circled2"></i>';
				break;
		}
		OpenWindowNot(msj1); // muestra alerta
	}
	if( vError = getVariable('error') ){ // mensajes de errores
		switch(vError){

			case '1': // el docente ya tiene seccion
				msj = '¡Error, la <b>sección</b> ya se encuentra registrada!<i class="icon-cancel-circled2"></i>';
				OpenWindowNot(msj);
				break;

			case '2': // ya existe el grado y la seccion
				msj = '¡Error, el grado y la sección ya se encuentran registrados!<i class="icon-cancel-circled2"></i>';
				OpenWindowNot(msj);
				break;

			case '3': // el docente no existe 
				msj = '¡Error, el docente no existe!<i class="icon-cancel-circled2"></i>';
				p = '<p class="msj_error">Por favor, asegúrese de escribir correctamente la <b>cédula</b> del docente e inténtelo nuevamente.</p>';
				OpenWindowNot(msj+p);
				break;

			case '4': // seccion no existente
				msj = '¡Error, la sección no existe!<i class="icon-cancel-circled2"></i>';
				p = '<p class="msj_error">Por favor, asegúrese de seleccionar una <b>sección</b> existente.</p>';
				OpenWindowNot(msj+p);
				break;
		}
	}
	if( vConsulta = getVariable('consultar') ){
		//alert(vConsulta);
		document.getElementById('tlt_seccion').innerHTML = 'Secciones ('+vConsulta+')';
	}
}

// VENTANAS - ver archivo: ventanas.js
function W_open(){
	// muestra la ventana con el formulario
	OpenWindowForm(divForm); 
}
function W_close(){
	// cierra la ventana
	W_default();
	CloseWindowForm(divForm);
}

function W_default(){
	document.getElementById('W-nom').innerHTML = '<i class="icon-plus"></i>Nueva Sección';
	document.getElementById('boton_enviar').innerHTML = '<i class="icon-plus"></i><p>Agregar</p>';

	ocultar_msj(msjBox);
	f.reset();
	activar_campos();
	f.aesc.disabled = true;
	f.aula.innerHTML = opAulas;
	f.ope.value = 'add';
	f.cupos.value = 36;
	if( temp = document.getElementById('tempOpDoc') ){
		f.docente.removeChild(temp);
	}	
}

function editar(cod,gdo,lta,codAula,nAula,codDoc,cup){ 
	W_open(); // muestra la ventana con el formulario
	
	// agrega valor a los campos
	f.ope.value = 'mod';
	f.codSec.value = cod; 
	f.docente.value = codDoc;
	f.grado.value = gdo;
	f.seccion.value = lta;
	f.codAula.value = codAula;
	f.codDoc.value = codDoc;
	f.cupos.value = cup;

	// desactiva campos para no modificar
	//f.grado.disabled = true;
	//f.seccion.disabled = true;
	aulasD = document.getElementById('DOM_aulasD').innerHTML;
	f.aula.innerHTML = '<option value="'+codAula+'">'+nAula.replace(/%/g,' ')+'</option>'+aulasD;
	hiddendoc = document.getElementById('docFila'+codDoc).innerHTML;
	opDocEdit = '<option value="'+codDoc+'" id="tempOpDoc" selected>'+hiddendoc+'</option>';
	f.docente.innerHTML = f.docente.innerHTML+opDocEdit;

	document.getElementById('W-nom').innerHTML = '<i class="icon-edit"></i>Modificar Sección';
	document.getElementById('boton_enviar').innerHTML = '<i class="icon-edit"></i><p>Guardar Cambios</p>';
}

function enviar(){ // valida el formulario antes de enviar
	submit = true;
	if( !vld_aesc() ){
		submit = false;
	}
	else if( !vld_docente() ){
		submit = false;
	}
	else if( !vld_grado() ){
		submit = false;
	}
	else if( !vld_seccion() ){
		submit = false;
	}
	else if( !vld_aula() ){
		submit = false;
	}
	else if( !vld_cupos() ){
		submit = false;
	}
	if( submit == true ){
		activar_campos(); // activa campos para evitar errores en el controlador
		f.submit(); // envia el formulario
	}
}
// validacion de combos selects (no esten vacíos)
function vld_aesc(){ 
	ocultar_msj(msjBox);
	if( validar_comboSelect(f.aesc,0) ){
		return true;
	}
}
function vld_docente(){ // valida el campo docente
	ocultar_msj(msjBox);
	if( validar_comboSelect(f.docente,1,'si') ){
		return true;
	}
}
function vld_grado(){ // valida el campo grado
	ocultar_msj(msjBox);
	if( validar_comboSelect(f.grado,2) ){
		return true;
	}
}
function vld_seccion(){ // valida el campo seccion
	ocultar_msj(msjBox);
	if( validar_comboSelect(f.seccion,3) ){
		return true;
	}
}
function vld_aula(){ // valida el campo aulas
	ocultar_msj(msjBox);
	if( validar_comboSelect(f.aula,4) ){
		return true;
	}
}
function vld_cupos(){ // valida el campo de cupos
	ocultar_msj(msjBox);
	if( f.cupos.value.trim() == '' ){
		msjBox[5].innerHTML = icon_attention+' El campo es requerido';
		mostrar_msj(msjBox[5]);
	}else{
		return true;
	}
}

function activar_campos(){
	f.aesc.disabled = false;
	f.aula.disabled = false;
	f.cupos.disabled = false;
	f.grado.disabled = false;
	f.seccion.disabled = false;
}

// funcion buscar secciones por año escolar
function escribirPeriodo( e ){
	if( txt_buscar.value.trim().length == 4 ){
		aS = parseInt(txt_buscar.value)+1;
		txt_buscar.value +='-'+aS; // agrega un guion y le suma 1 al año
	}
	if( PressEnter(e) ){ // presiona enter: envia
		buscarPeriodo();
	}
	return solo_numeros(e); 
}

// redirecciona
function buscarPeriodo(){
	if( txt_buscar.value.trim().length < 9 ){
		alert('Por favor introdúzca el año escolar');
	}
	else{
		window.location.href = '?ver=seccion&consultar='+txt_buscar.value;
	}
}
