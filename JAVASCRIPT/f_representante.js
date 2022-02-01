window.onload = function(){ // Se cargan los eventos una vez que la página haya cargado
	secciones = document.getElementsByTagName('section'); // sections

	// CONSULTAR REPRESENTANTE
	if( document.getElementById('btnBuscarRep') ){
		document.getElementById('btnBuscarRep').onclick = consultar;
		document.getElementById('buscar').onkeypress = teclaEnterBuscar;
	}
	
	btnsRep = document.getElementsByClassName('btnsRep');

	// FORMULARIO REPRESENTANTE
	fRep = document.representante;
	if( fRep.enviarFormReg ){
		fRep.enviarFormReg.onclick = registrar_representante;
	}

	if( fRep.enviarFormMod ){
		fRep.enviarFormMod.onclick = modificar_representante;
	}		

	// datos personales
	fRep.foto.onchange = validar_foto;
	fRep.ced.onblur = validar_ced; fRep.ced.onkeypress = solo_numeros;
	fRep.ced.onblur = existencia;
	fRep.tipo_doc.onchange = existencia;
	fRep.nom.onblur = validar_nom; fRep.nom.onkeypress = solo_letras;
	fRep.ape.onblur = validar_ape; fRep.ape.onkeypress = solo_letras;
	fRep.sex.onchange = validar_genero;
	fRep.email.onchange = validar_emailRep;
	fRep.tlfm.onchange = validar_tlfm;
	fRep.tlff.onchange = validar_tlff;
	fRep.grdIns.onchange = validar_grdIns;
	fRep.ocup.onchange = validar_ocupacion;
	fRep.tlft.onchange = validar_tlft;
	fRep.tlfm.onkeydown = agrega_guionTlf; fRep.tlfm.onkeypress = solo_numeros;
	fRep.tlff.onkeydown = agrega_guionTlf; fRep.tlff.onkeypress = solo_numeros;
	fRep.tlft.onkeydown = agrega_guionTlf; fRep.tlft.onkeypress = solo_numeros;
	F_existencia = false; 

	// direccion de domicilio
	fRep.domEdo.oninput = buscaMun;
	fRep.domMun.oninput = buscaParr;

	// direccion de trabajo
	fRep.tEdo.oninput = buscaMun;
	fRep.tMun.oninput = buscaParr;
	obj = ajax_newObj();
	cLugares = '../CONTROL/c_lugares.php';

	msjBox = document.getElementsByClassName('msjBox');
	urlVariables();
}

// Buscar variables en la URL
function urlVariables(){
	if( vRep = getVariable('Representante') ){ // existe la variable get Representante
		switch(vRep){
			case 'registrar': // formulario representante para registrar
				setTitulo('Registrar representante','user-plus');
				mostrar('p1');
				mostrar('btn_back');
				secciones[1].classList.remove('none');
				btnsRep[0].classList.remove('none');
				break;

			case 'visualizar': // formulario representante para modificar
				fRep.tipo_doc.disabled = true;
				fRep.ced.disabled = true;

				if( fRep.trabaja.value == 'no' ){
					ocultar('trabajo');
				}
				if( fRep.personal.value == 'true' ){
					bloquearPersonal();
				}
				secciones[1].classList.remove('none');
				secciones[0].classList.remove('none');
				btnsRep[1].classList.remove('none');
				break;

			default:
				secciones[0].classList.remove('none');
				break;
		}
	}
	// registro
	if( vReg = getVariable('reg') ){
		if( vReg == 'true' ){
			icon = '<i class="icon-ok-circled2"></i>';
			OpenWindowNot('¡Los datos se han registrado correctamente!'+icon);
		}
	}
	// modificacion
	if( vMod = getVariable('mod') ){
		if( vMod == 'true' ){
			icon = '<i class="icon-ok-circled2"></i>';
			OpenWindowNot('¡Los datos se han modificado correctamente!'+icon);
		}
	}
	// error
	if( vError = getVariable('error') ){
		if( vError == 'cedula'){
			icon = '<i class="icon-cancel-circled2"></i>';
			p = '<p class="msj_error"> La <b>cédula</b> que introdujo ya se encuentra registrada. Por favor, inténtelo nuevamente.</p>';
			OpenWindowNot('¡No se han podido registrar los datos!'+icon+p);
		}
		else if( vError == 'consulta' ){
			icon = '<i class="icon-cancel-circled2"></i>';
			p = '<p class="msj_error">Asegúrese de escribir correctamente el <b>documento Identidad</b> de la persona e inténtelo nuevamente</p>';
			OpenWindowNot('¡No se encontraron datos!'+icon+p);
		}
	} 
}

// FUNCIONES CONSULTAR REPRESENTANTE -----------------------
function teclaEnterBuscar(event){
	if( PressEnter(event) ){
		consultar();
	}
	return solo_numeros(event);
}
function consultar(){
	tipo_doc = document.getElementById('tipo_doc').value;
	cedula = document.getElementById('buscar').value.trim();
	if(cedula.length >  0){
		window.location.href = 'index.php?Representante=visualizar&cedula='+tipo_doc+'-'+cedula;
	}
}
//----------------------------------------------------------

// FORMULARIO BUSCAR REPRESENTANTE (antes de registrar) -------------------
function buscar_Rep(){ // valida que el campo no esté vacío y envia el formulario
	document.getElementById('msjCedularRep').classList.add('none');
	icon = '<i class="icon-attention tx_rojo"></i>';
	if( fbRep.buscar.value.trim() == '' ){
		document.getElementById('msjCedularRep').innerHTML = icon+'Introdúzca la <b>cédula</b> del representante';
		document.getElementById('msjCedularRep').classList.remove('none'); // muestra error
	}
	else if( fbRep.buscar.value.trim().length < 6 ){
		document.getElementById('msjCedularRep').innerHTML = icon+'La <b>cédula</b> debe tener al menos <b>6</b> caractéres';
		document.getElementById('msjCedularRep').classList.remove('none'); // muestra error
	}
	else{
		fbRep.submit();
	}
}
function teclado_buscar_Rep(evento){ // evento el teclado en el input cedula
	if( PressEnter(evento) ){
		buscar_Rep();
	}
	return solo_numeros(evento); // solo permite escribir numeros
}
// ---------------------------------------------------------------------------


// FUNCIONES FORMULARIO REPRESENTANTE ----------------------------
function registrar_representante(){
	fRep.opeRep.value = 'reg';
	validar_formulario();	
}

function modificar_representante(){
	fRep.opeRep.value = 'mod';
	validar_formulario();
}

function validar_formulario(){ // valida el formulario antes de enviar
	if( F_existencia == true ){
		icon = '<i class="icon-cancel-circled2"></i>';
		p = '<p class="msj_error"> La <b>cédula</b> que introdujo ya se encuentra registrada. Por favor, inténtelo nuevamente.</p>';
		OpenWindowNot('¡El representante ya existe!'+icon+p);
	}
	else{
		submit = true;
		if( !validar_foto() ){
			submit = false;
			window.scrollTo(0, 110);
		}
		else if( !validar_ced() ){
			submit = false;
			window.scrollTo(0, 150);
		}
		else if( !validar_nom() ){
			submit = false;
			window.scrollTo(0, 300);
		}
		else if( !validar_ape() ){
			submit = false;
			window.scrollTo(0, 300);
		}
		else if( !validar_genero() ){
			window.scrollTo(0, 400);
			submit = false;
		}
		else if( !validar_fechaNac() ){
			window.scrollTo(0, 400);
			submit = false;
		}
		else if( !validar_emailRep() ){
			submit = false;
			window.scrollTo(0, 400);
		}
		else if( !validar_tlfm() ){
			submit = false;
			window.scrollTo(0, 400);
		}
		else if( !validar_tlff() ){
			submit = false;
			window.scrollTo(0, 400);
		}
		else if( !validar_dirDom() ){
			submit = false;
			window.scrollTo(0, 700);
		}
		else if( !validar_grdIns() ){
			submit = false;
			window.scrollTo(0,900);
		}
		else if( !validar_ocupacion() ){
			submit = false;
			window.scrollTo(0,900);
		}
		else if( !validar_dirTrbjo() ){
			submit = false;
			window.scrollTo(0,1200);
		}
		else if( !validar_tlft() ){
			submit = false;
			window.scrollTo(0,900);
		}
		if( submit == true ){
			switch(fRep.opeRep.value){
				case 'mod':
					document.getElementById('b2').style = 'display:block'; 
					document.getElementById('b1').onclick = enviar;
					OpenWindowNot('¿Desea continuar?'); // muestra la ventana para confirmar
					break;

				case 'reg':
					enviar();
					break;
			}
		}
	}
}
function enviar(){
	// activa para evitar errores en el controlador
	activar_campos();
	fRep.submit();
}
// FUNCIONES VALIDACIONES
// Datos Personales
function validar_foto(){
	if( fRep.foto.value == '' ){
		return true;
	}
	else{
		if( validar_fotoImg(fRep.foto) ){
			preview = document.getElementById('foto_previa');
			previsualizar(fRep.foto, preview);
			return true;
		}else{
			fRep.foto.value = '';
			return false;
		}
	}
}
function validar_ced(){ // valida el camppo cedula
	ocultar_msj(msjBox);
	if( validar_cedula(fRep.ced,0,'si') ){
		return true;
	}
}
function validar_nom(){ // valida el campo nombre
	ocultar_msj(msjBox);
	if( validar_nombre(fRep.nom,1,'si') ){
		return true;
	}
}
function validar_ape(){ // valida el campo apellido
	ocultar_msj(msjBox);
	if( validar_apellido(fRep.ape,2,'si') ){
		return true;
	}
}
function validar_genero(event){ // valida el combo select genero
	ocultar_msj(msjBox);
	if( validar_comboSelect(fRep.sex,3) ){
		return true;
	}
}
function validar_fechaNac(){ // valida el campo fecha de nacimiento
	ocultar_msj(msjBox);
	if( fRep.fnac.value == '' ){
		msjBox[4].innerHTML = icon_attention+'El campo es requerido';
		mostrar_msj(msjBox[4]);
	}else{
		return true;
	}
}
function validar_emailRep(){ // valida el campo correo
	// campo no obligatorio, si está vacio retorna true
	ocultar_msj(msjBox);
	if( validar_email(fRep.email,5,'no') ){
		return true;
	}
}
function validar_tlfm(){ // validar el numero telefonico movil(evento del teclado)
	ocultar_msj(msjBox);
	if( validar_telefono(fRep.tlfm,6,'si') ){
		return true;
	}
}
function validar_tlff(){ // valida el campo telefono fijo o de casa
	// campo no obligatorio, si está vacio retorna true
	ocultar_msj(msjBox);
	if( validar_telefono(fRep.tlff,7,'no') ){
		return true;
	}
}
// Ubicacion domiciliaria
function validar_dirDom(){
	if(fRep.domEdo.value == 0||fRep.domMun.value==0||fRep.domParr.value==0||fRep.sector.value.trim()==''||fRep.calle.value.trim()==''||fRep.nroCasa.value.trim()==''){
		document.getElementById('msjErrorDir').classList.remove('none'); // muestra
	}else{
		document.getElementById('msjErrorDir').classList.add('none'); // oculta
		return true;
	}
}
// otros datos
function validar_grdIns(event){ // grado de instruccion
	ocultar_msj(msjBox);
	if( validar_comboSelect(fRep.grdIns,8) ){
		return true;
	}
}
function validar_ocupacion(event){
	ocultar_msj(msjBox);
	if( validar_comboSelect(fRep.ocup,9) ){
		return true;
	}
}
function validar_dirTrbjo(){
	document.getElementById('msjErrorDirTrabajo').classList.add('none');
	// valida que el campo direccion de trabajo no esté vacio mientras la opcion "si" está seleccionada
	if( fRep.trabaja.value == 'si' ){
		if(fRep.tEdo.value==0||fRep.tMun.value==0||fRep.tParr.value==0||fRep.dirTrbjo.value.trim() == ''){
			document.getElementById('msjErrorDirTrabajo').classList.remove('none');
		}
		else{
			return true;
		}
	}
	else{
		return true;
	}
}
function validar_tlft(){ // valida el campo numero de telefono del trabajo
	// campo no obligatorio, si está vacio retorna true
	ocultar_msj(msjBox);
	if( fRep.trabaja.value == 'si' ){
		if( validar_telefono(fRep.tlft,10,'no') ){
			return true;
		}
	}else{
		return true;
	}
}
// mostrar u ocultar elementos del formulario
function mostrar(id){
	document.getElementById(id).classList.remove('none');
}
function ocultar(id){
	document.getElementById(id).classList.add('none');
}

// > Estados,municipios,parroquias
var inputMun; 
var inputParr;
function addEventChilds(parent,eventName){ // agrega evento a los nodos hijos de un select
	longHijos = parent.childNodes.length;
	for(i=0; i<longHijos; i++){
		parent.childNodes[i].addEventListener('click', eventName );
	}
}
function buscaMun(){ // consulta los municipios de un estado y los agrega a un nodo padre
	inputEdo = this.name;
	cod_edo = this.value; // codigo del estado
	if( cod_edo != 0 ){ // ejecuta mientras el valor no se 0
		switch(inputEdo){
			case 'domEdo':
				inputMun = fRep.domMun; 
				fRep.domParr.innerHTML = '<option value="0">SELECCIONAR</option>';
				break;

			case 'tEdo':
				inputMun = fRep.tMun; 
				fRep.tParr.innerHTML = '<option value="0">SELECCIONAR</option>';
				break;
		}
		cargar_ajax(obj,printMun,'POST',cLugares,'listarMun&cod_edo='+cod_edo);
	}
}
function printMun(){ // imprime los municipios en un select
	if( obj.readyState == complete && obj.status == 200 ){
		inputMun.innerHTML = fRep.tParr.innerHTML = '<option value="0">SELECCIONAR</option>'+obj.responseText;
	}
}
function buscaParr(){ // consulta las parroquias de un municipio y las agrega a un nodo padre
	inputMun = this.name;
	cod_mun = this.value;
	if( cod_mun != 0 ){
		switch(inputMun){
			case 'domMun':
				inputParr = fRep.domParr;
				break;
			case 'tMun':
				inputParr = fRep.tParr;
				break;
		}
		cargar_ajax(obj,printParr,'POST',cLugares,'listarParr&cod_mun='+cod_mun);
	}
}
function printParr(){ // imprimie las parroquias en un select
	if( obj.readyState == complete && obj.status == 200 ){
		inputParr.innerHTML = '<option value="0">SELECCIONAR</option>'+obj.responseText;
	}
}
function bloquearPersonal(){ // desactiva campos
	fRep = document.representante;
	fRep.tipo_doc.disabled = true;
	fRep.ced.disabled = true;
	fRep.nom.disabled = true;
	fRep.ape.disabled = true;
	fRep.tlfm.disabled = true;
}
function bloquearCedula(){ // bloquea tipo de documento y cedula
	fRep = document.representante;
	fRep.tipo_doc.disabled = true;
	fRep.ced.disabled = true;
}
function activar_campos(){
	fRep.tipo_doc.disabled = false;
	fRep.ced.disabled = false;
	fRep.nom.disabled = false;
	fRep.ape.disabled = false;
	fRep.tlfm.disabled = false;
}
// FUNCIONES PARA CAMBIAR ALGUNOS ELEMENTOS DE LA PAGINA
function setTitulo(name,icon){ // cambia el titulo de la pagina
	h2 = '<h2>'+name+'</h2>';
	i = '<i class="icon-'+icon+'"></i>'; 
	document.getElementsByClassName('titulo_m')[0].innerHTML = h2+i;
}

function existencia(){ 
	if( fRep.ced.value.trim() != '' ){
		obj = ajax_newObj();
		controlador = '../CONTROL/c_representante.php';
		td = fRep.tipo_doc.value;
		cedula = fRep.ced.value;
		cargar_ajax(obj,respuesta_existencia,'POST',controlador,'ajax=1&R=1&td='+td+'&ced='+cedula);
	}
}

function respuesta_existencia(){ // envia XMLHttpRequest
	if( obj.readyState == complete && obj.status == 200 ){
		r = obj.responseText;
		switch (r){
			case 'ya existe':
				F_existencia = true;
				icon = '<i class="icon-cancel-circled2"></i>';
				p = '<p class="msj_error"> La <b>cédula</b> que introdujo ya se encuentra registrada. Por favor, inténtelo nuevamente.</p>';
				OpenWindowNot('¡El representante ya existe!'+icon+p);
				break;

			case 'no existe':
				F_existencia = false;
				break;

			default:
				F_existencia = false;
				arr = r.split('%');
				fRep.nom.value = arr[0];
				fRep.ape.value = arr[1];
				fRep.email.value = arr[2];
				fRep.tlfm.value = arr[3];
				break;
		}
	}
}
