// LIBRERIA:
// este archivo contiene variables y funciones que pueden ser reutilizados en cualquier documento
// Validar campo de texto dependiendo si es solo números o letras

numeros_perm = "1234567890"; 
letras_perm = "abcdefghijklmnñopqrstuvwxyzáéíóúäëïöüÁÉÍÓÚÄËÏÖÜABCDEFGHIJKLMNÑOPQRSTUVWXYZ' ";
simbolos_perm = "¡!#$%&/()=¿?[]*:;<>{}";
c_permitidos = numeros_perm+letras_perm+simbolos_perm;
teclas_especiales = [8, 9]; // back,  tab

// visible/oculto
visible = 'visibility:visible;opacity:1';
oculto = 'visibility:hidden;opacity:0';

// iconos:
icon_attention = '<i class="icon-attention"></i>';

// TECLADO
function solo_numeros(elEvento){ // Permite en un campo de texto escribir solo numeros
	evento = elEvento || window.event; // Se obtiene el tipo de evento
	codigo = evento.charCode || evento.keyCode || evento.which; // Se obtiene el codigo de la tecla presionada
	caracter = String.fromCharCode(codigo); // Se obtiene el caracter del codigo
	final = false;
	for( i in teclas_especiales ){ // bucle en el array de teclas especiales
		if( codigo == teclas_especiales[i] ){
			return true; // tecla permitida
			break;
		}
	}
	return numeros_perm.indexOf(caracter) != -1 || final; // Imprime en el campo caracter numérico
}

function solo_letras(elEvento){
	evento = elEvento || window.event;
	codigo = evento.charCode || evento.keyCode || evento.which;
	caracter = String.fromCharCode(codigo);
	final = false;
	for( i in teclas_especiales ){
		if( codigo == teclas_especiales[i] ){
			return true;
			break;
		}
	}
	return letras_perm.indexOf(caracter) != -1 || final;
}

function numeros_letras(elEvento){ // numeros y letras
	evento = elEvento || window.event;
	codigo = evento.charCode || evento.keyCode || evento.which;
	caracter = String.fromCharCode(codigo);
	final = false;
	for( i in teclas_especiales ){
		if( codigo == teclas_especiales[i] ){
			return true;
			break;
		}
	}
	patron = letras_perm+numeros_perm;
	return patron.indexOf(caracter) != -1 || final;
}

function permitidos(elEvento){
	evento = elEvento || window.event;
	codigo = evento.charCode || evento.keyCode || evento.which;
	caracter = String.fromCharCode(codigo);
	final = false;
	for( i in teclas_especiales ){
		if( codigo == teclas_especiales[i] ){
			return true;
			break;
		}
	}
	return c_permitidos.indexOf(caracter) != -1 || final;
}

function PressEnter(elEvento){ 
	codigo = elEvento.charCode || elEvento.keyCode || elEvento.which;
	if( codigo == 13 ){ // presionó la tecla Enter
		return true;
	}
}

function codigoTecla(e){ // obtener el codigo del teclado
	cod = e.charCode || e.keyCode || e.which;
	return cod;
}

function caracterTecla(e){ // obtener el caracter de una tecla
	codigo = e.charCode || e.keyCode || e.which;
	tecla = String.fromCharCode(codigo);
	return tecla;
}

function cerrar_sesion(){
	if( confirm('¿Desea cerrar la sesión?') ){
		// redirecciona al controlador c_logout
		window.location.href = '../CONTROL/logout.php';
	}
}

// verifica que la contraseña contanga al menos un caracter numerico y un simbolo
function verfSimbolNumberPass(password,arreglo){
	cont=0;
	for(i in password){
		for( j in arreglo ){
			if( password[i] == arreglo[j] ){
				cont++;
			}
		}
	}
	if( cont > 0 ){
		return true;
	}
}

// function solo_num2(e){
//     tecla = (document.all) ? e.keyCode : e.which;

//     //Tecla de retroceso para borrar, siempre la permite
//     if (tecla==8){
//         return true;
//     }
        
//     // Patron de entrada, en este caso solo acepta numeros
//     patron =/[0-9]/;
//     tecla_final = String.fromCharCode(tecla);
//     return patron.test(tecla_final);
// }

// AJAX
// Permite enviar solicitudes al servidor en segundo plano
// https://uniwebsidad.com/libros/ajax/capitulo-7/la-primera-aplicacion // ver guia
var uninitialized = 0;
var loading = 1;
var loaded = 2;
var interactive = 3;
var complete = 4;

function ajax_newObj() { 
	// inicializa el objeto
	if(window.XMLHttpRequest) {
		return new XMLHttpRequest();
	}
	else if(window.ActiveXObject) {
		return new ActiveXObject("Microsoft.XMLHTTP");
	}
}
// solicita información al servidor (sin recargar la pagina)
function cargar_ajax(objeto, funcion, metodo, url, variable){
	if( objeto ){
		objeto.onreadystatechange = funcion; // funcion que recibe el XMLHttpRequest
		objeto.open(metodo,url,true);
		objeto.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		objeto.send(variable); // envia las variables GET o POST
	}	
}

// obteber variables de la url
function getVariable(variable) {
	// split = Divide una cadena en subcadenas mediante el separador especificado y las devuelve como una matriz.
	var url = window.location.search.substring(1); // selecciona la url despues del simbolo "?"
	var vars = url.split("&"); // Elimina el simbolo "&"

	for ( i=0; i < vars.length; i++) { // bucle en el total de las variables encontradas "vars.length"
		var dato = vars[i].split("="); // se almacena la variable y el dato
		if( dato[0] == variable ) { // el valor de la variable es igual al valor que buscamos
			return dato[1]; // devuelve el valor de la varibable
		}
	}
}

function validar_cedula(campo,i,obl='no'){ // valida la cedula
	if( obl == 'si' ){
		if( campo.value.trim() == '' ){
			msjBox[i].innerHTML = icon_attention+'El campo es requerido';
			mostrar_msj(msjBox[i]);
		}
		else if( campo.value.trim().length < 7 ){
			msjBox[i].innerHTML = icon_attention+'La cédula debe tener al menos 7 números';
			mostrar_msj(msjBox[i]);
		}
		else{
			return true;
		}
	}
	else{
		if( campo.value.trim().length > 0 ){
			if( campo.value.trim().length < 7 ){
				msjBox[i].innerHTML = icon_attention+'La cédula debe tener al menos 7 números';
				mostrar_msj(msjBox[i]);
			}
			else{
				return true;
			}
		}
		else{
			return true;
		}
	}
	
}
function validar_nombre(campo,i,obl){ // valida los campos nombres
	ocultar_msj(msjBox);
	if( obl == 'si' ){ // obligatorio
		if( campo.value.trim() == '' ){
			msjBox[i].innerHTML = icon_attention+'El campo es requerido';
			mostrar_msj(msjBox[i]);
		}
		else if( campo.value.trim().length > 0 && campo.value.trim().length < 3 ){ 
			msjBox[i].innerHTML = icon_attention+'El nombre debe tener al menos 3 caractéres';
			mostrar_msj(msjBox[i]);
		}
		else{
			return true;
		}
	}
	else{
		if( campo.value.trim().length > 0 && campo.value.trim().length < 3 ){ 
			msjBox[i].innerHTML = icon_attention+'El nombre debe tener al menos 3 caractéres';
			mostrar_msj(msjBox[i]);
		}
		else{
			return true;
		}
	}
}

function validar_apellido(campo,i,obl){ // valida los campos apellidos
	ocultar_msj(msjBox);
	if( obl == 'si' ){ // obligatorio
		if( campo.value.trim() == '' ){
			msjBox[i].innerHTML = icon_attention+'El campo es requerido';
			mostrar_msj(msjBox[i]);
		}
		else if( campo.value.trim().length > 0 && campo.value.trim().length < 3 ){ 
			msjBox[i].innerHTML = icon_attention+'El apellido debe tener al menos 3 caractéres';
			mostrar_msj(msjBox[i]);
		}
		else{
			return true;
		}
	}
	else{
		if( campo.value.trim().length > 0 && campo.value.trim().length < 3 ){ 
			msjBox[i].innerHTML = icon_attention+'El apellido debe tener al menos 3 caractéres';
			mostrar_msj(msjBox[i]);
		}
		else{
			return true;
		}
	}	
}
function validar_comboSelect(campo,i){ // valida los combos select
	ocultar_msj(msjBox);
	if( campo.value == '0' ){	
		msjBox[i].innerHTML = icon_attention+'Seleccione un valor';
		mostrar_msj(msjBox[i]); // muestra el mensaje
	}else{
		return true;
	}	
}
function validar_campo(campo, i){ // valida que simplemente el campo no esté vacío
	ocultar_msj(msjBox);
	if( campo.value.trim().length == '' ){	
		msjBox[i].innerHTML = icon_attention+'El campo es requerido';
		mostrar_msj(msjBox[i]); // muestra el mensaje
	}else{
		return true;
	}	
}
function validar_email(campo,i,obl){ // valida email
	if( obl == 'si' ){ // obligatorio
		if( campo.value.trim() == '' ){
			msjBox[i].innerHTML = icon_attention+'El campo es requerido';
			mostrar_msj(msjBox[i]);
		}
		else if( comprueba_email(campo.value.trim()) == false ){
			campo.style.color = 'var(--rojo3)';
			msjBox[i].innerHTML = icon_attention+'Introduzca un email válido';
			mostrar_msj(msjBox[i]);
		}
		else{
			campo.style.color = 'var(--negro)';
			return true;
		} 
	}
	else{
		if( campo.value.trim().length > 0 ){
			if( comprueba_email(campo.value.trim() ) == false ){ // no es obligatorio
				campo.style.color = 'var(--rojo3)';
				msjBox[i].innerHTML = icon_attention+'Introduzca un email válido';
				mostrar_msj(msjBox[i]);
			}
			else{
				campo.style.color = 'var(--negro)';
				return true;
			}
		}else{
			campo.style.color = 'var(--negro)';
			return true;
		} 
	}
}
function comprueba_email(email){ // comprueba que el email sea valido
	var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z])+\.)+([a-zA-Z]{2,4})+$/;
    return regex.test(email) ? true : false;
}

function validar_telefono(campo,i,obl){ // valida campos con numeros de telefono
	arregla_telefono(campo);
	if( obl == 'si' ){ // obligatorio
		if( campo.value.trim() == '' ){
			msjBox[i].innerHTML = icon_attention+'El campo es requerido';
			mostrar_msj(msjBox[i]);
		}
		else if( !comprueba_numero(campo.value.trim()) ){
			msjBox[i].innerHTML = icon_attention+'Introduzca un número de teléfono válido';
			campo.style.color = 'var(--rojo3)';
			mostrar_msj(msjBox[i]);
		}
		else{
			campo.style.color = 'var(--negro)';
			return true;
		} 
	}
	else{ // no es obligatorio
		if( campo.value.trim().length > 0 ){
			if( !comprueba_numero(campo.value.trim()) ){ 
				campo.style.color = 'var(--rojo3)';
				msjBox[i].innerHTML = icon_attention+'Introduzca un número de teléfono válido';
				mostrar_msj(msjBox[i]);
			}
			else{
				campo.style.color = 'var(--negro)';
				return true;
			}
		}else{
			campo.style.color = 'var(--negro)';
			return true;
		} 
	}
}

function comprueba_numero(num){ // comprueba que el telefono sea valido
	reNumero = /^([0]{1})+([2,4]{1})+([0-9]{2})+(-){0,1}([0-9]{7})$/;
	if ( reNumero.exec(num) ) {
		return true;
	}
}

function arregla_telefono(campo){ // evento onchange
	tlf = campo.value.trim();
	if( tlf.length == 12 ){

		codArea = tlf.substr(0,5);
		numero = tlf.substr(5);

		if( codArea[4] != '-' ){ // no tiene guion
			arr = codArea.split(''); // descompone
			temp = arr[4];
			arr[4] = '-'; // reemplaza
			if( tlf.length == 11 ){	
				arr.push(temp);
			}
			codArea = arr.join(''); // compone
			new_tlf = codArea+numero;
			campo.value = new_tlf;
		}	
	}
}

function agrega_guionTlf(event){ // agrega un guion despues de los primeros cuatro caracteres (evento de teclado)
	campo = this;
	numero = campo.value.trim();
	//arregla_telefono(campo);
	if( numero.length == 4 ){
		this.value = numero+'-'; // agrega guion
	}
	if( numero.length == 4 && codigoTecla(event) == 8 ){ // presiona borrar
		this.value = numero.substr(0,4); // borra el guion
	}
}


function ocultar_msj(className){ // oculta los cuadros de dialogo 
	long = className.length;
	for(i=0; i<long; i++){
		className[i].classList.remove('show'); // elimina la clase show 
		className[i].innerHTML = '';
	}
}
// muestra caja de dialogo
function mostrar_msj(element){
	element.classList.add('show'); // añade la clase show a la lista de clases
}
function mostrar(element){ // muestra elemento
	element.classList.add('show');
}
function ocultar(className){ // oculta elemento
	long = className.length;
	for(i=0; i<long; i++){
		className[i].classList.remove('show'); // elimina la clase show 
		className[i].innerHTML = '';
	}
}

function dropdown_menu(elem){ // muestra submenu del elemento
	hide_dropdown_menu();
	child = elem.children; // hijos
	for(i=0;i<child.length;i++){
		if( child[i].classList.contains("dropdown_menu") ){ // si el elemento hijo contiene ésta clase
			dropMenu = child[i];
			dropMenu.classList.add('show'); // añade la clase show
		}
	}
}
function hide_dropdown_menu(){ // oculta los dropdown_menu
	dropMenus = document.getElementsByClassName("dropdown_menu");
	for(i=0;i<dropMenus.length;i++){
		if(dropMenus[i].classList.contains('show')){
			dropMenus[i].classList.remove('show'); // elimina la clase show
		}
	}
}

// validacion de archivo (FOTO)
function validar_fotoImg(input){
	icon = '<i class="icon-cancel-circled2"></i>';
	//p = '<p class="msj_error">Asegúrese de escribir bien el documento Identidad de la persona e inténtelo nuevamente</p>';
	op = true;
	if( !FileExtImage(input) ){
		OpenWindowNot('El archivo no tiene la extensión adecuada'+icon);
		op = false;
	}
	else if( MaxFileSize(input.files[0].size,500000 ) ){ // tamaño maximo 500kb
		OpenWindowNot('El tamaño maximo es de 500kb'+icon);
		op = false;
	}
	if( op == true ){
		return true;
	}
}
function MaxFileSize(file,size){ 
	if( file > size){ // El tamaño supera el limite (size)
		return true;
	}
}
function FileExtImage(file){ // valida la extencion del archivo
	ext = file.files[0].name.toLowerCase().split('.').pop(); // recuperamos la extension del archivo	
	switch(ext){
		case 'jpg': case 'png': case 'jpeg':
			return true;
			break;

		default:
			return false;
			break;
	}
} 
function previsualizar(fileInput,preview){ // muestra la imagen en elemento preview, antes de subirla
	// Creamos el objeto de la clase FileReader
	reader = new FileReader();
	reader.readAsDataURL(fileInput.files[0]);
	// Leemos el archivo subido y se lo pasamos a nuestro fileReader
 	reader.onload = function(){
 		preview.src = reader.result;
 	};
}

function agregar_eventos_grupo(grupo, evento, funcion){
	if( grupo ){
		for (var i = 0; i <= grupo.length; i++) {
			grupo[i].addEventListener(evento, funcion);	
		}
	}
}

function formato_fecha(fecha,norma='iso'){
	if( norma == 'iso' ){
		// dd-mm-YYYY => YYYY-mm-dd
		arr = fecha.split('-'); 
		fecha = arr[2]+'-'+arr[1]+'-'+arr[0];
		return fecha;
	}

	else if( norma == 'espn' ){
		// YYYY-mm-dd => dd-mm-YYYY 
		arr = fecha.split('-'); 
		fecha = arr[2]+'-'+arr[1]+'-'+arr[0];
		return fecha;
	}
}

// function escribir_fecha(){
// 	if(this.value.length == 2){
// 		this.value+='-';
// 	}
// 	if(this.value.length == 5){
// 		this.value+='-';
// 	}
// }