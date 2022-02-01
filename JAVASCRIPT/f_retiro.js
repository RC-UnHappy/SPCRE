window.onload = function(){ // carga los eventos una vez la pagina  haya cargado
	controlador = '../CONTROL/c_retiro.php';
	// VENTANAS
	divForm = document.getElementById('div_retiro');
	document.getElementById('close-W-form').onclick = W_close // boton cerrar ventana

	if( document.getElementById('open-W-form') ){
		document.getElementById('open-W-form').onclick = W_OpenAdd; // boton agregar
	}

	// BUSCAR
	if( document.getElementById('txt_buscar') ){
		txt_buscar = document.getElementById('txt_buscar');
		txt_buscar.onchange = searchPeriodo;
		txt_buscar.onkeypress = escribirPeriodo;
		btn_buscar =  document.getElementById('btn_buscar');
		btn_buscar.onclick = searchPeriodo;
	}

	if( document.getElementById('buscar_grado') ){
		buscar_grado = document.getElementById('buscar_grado');
		buscar_grado.onchange = searchGrado; // combo seccion
	}
	
	// TABLA 
	resultados = document.getElementById('resultados');
	thead = document.getElementById('thead').innerHTML;

	// FORMULARIO
	f = document.f_retiro; // formulario
	f.ced.onkeypress = solo_numeros;
	f.ced.onchange = buscar_estudiante;

	if(f.enviarReg){
		f.enviarReg.onclick = registrarRetiro;	
	}
	if(f.enviarMod){
		f.enviarMod.onclick = modificarRetiro;	
	}
	msjBox = document.getElementsByClassName('msjBox');
	url();
	//imprimir_resultados();
}

function url(){
	msj1 = '¡Los datos se han registrado correctamente! <i class="icon-ok-circled2"></i>';
	msj2 = '¡Los datos han sido modificados correctamente! <i class="icon-ok-circled2"></i>';
	msj3 = '¡Error, no se han podido registrar los datos! <i class="icon-cancel-circled2"></i>';
	msj4 = '¡Error, no se han podido modificar los datos! <i class="icon-cancel-circled2"></i>';
	msj5 = '¡Los datos se han eliminado correctamente! <i class="icon-ok-circled2"></i>';
	msj6 = '¡Error, no se han podido eliminar los datos! <i class="icon-cancel-circled2"></i>';
	p = '<p class="msj_error">La <b>cédula o CE</b> que introdújo no existe. Asegúrese de escribirla correctamente e inténtelo nuevamente.</p>';
	p2 = '<p class="msj_error">El <b>Estudiante</b> ya se encuentra <b>egresado</b>.</p>';
	p3 = '<p class="msj_error">El <b>Estudiante</b> no posee una inscripción <b>activa</b>.</p>';
	if( vGet = getVariable('ope') ){
		switch(vGet){
			case '1':
			OpenWindowNot(msj1);
			break;	

			case '2':
			OpenWindowNot(msj2);
			break;

			case '3':
			OpenWindowNot(msj5);
			break;		
		}		
	}

	if( vGet = getVariable('error') ){
		switch(vGet){
			case '1':
			OpenWindowNot(msj3+p);
			break;

			case '2':
			OpenWindowNot(msj3+p2);
			break;		

			case '3':
			OpenWindowNot(msj3+p3);
			break;	

			case '4':
			OpenWindowNot(msj6);
			break;	
		}
	}
}

// FORMULARIO
function registrarRetiro(){
	f.ope.value = 'reg';
	validar_formulario();
}

function modificarRetiro(){
	f.ope.value = 'mod';
	validar_formulario();
}

function validar_formulario(){ //
	submit = true;
	if( !validarCedula() ){
		submit = false;	
	}
	else if( !validarFecha() ){
		submit = false;
	}
	else if( !validarCausa() ){
		submit = false;
	}
	if(submit==true){
		enviar_formulario();			
	}
}

function enviar_formulario(){
	if(f.ope.value != '' ){
		f.tipo_doc.disabled = false;
		f.ced.disabled = false;
		f.submit();
	}	
}

function validarCedula(){
	if( validar_campo(f.ced,0,'si') ){
		return true;
	}
}
function validarFecha(){ // valida el campo nombre
	if( validar_campo(f.fecha,1,'si') ){
		return true;
	}
}
function validarCausa(){
	if( validar_comboSelect(f.causa,2,'si')	){
		return true;
	}
}

// VENTANAS - ver archivo: ventanas.js
function W_OpenAdd(){ // abrir ventana para agregar
	OpenWindowForm(divForm);
	ventanita('add');
}
// abre la ventana para modificar
function W_OpenMod(cod,ced,fecha,causa,obs){ 
	f.cod.value = cod;
	f.tipo_doc.value = ced.substr(0,1);
	f.ced.value = ced.substr(2);
	nom = document.getElementById('celNom'+cod).innerHTML; // contenido de la celda 
	ape = document.getElementById('celApe'+cod).innerHTML; // contenido de la celda
	gdo = document.getElementById('celGdo'+cod).innerHTML; // contenido de la celda
	f.nom.value = nom;
	f.ape.value = ape;
	f.gdo.value = gdo;
	f.fecha.value = fecha;
	f.causa.value = causa;
	f.obs.value = obs.replace(/_s_/g,' ');
	f.tipo_doc.disabled = true;
	f.ced.disabled = true;
	OpenWindowForm(divForm); 
	ventanita('mod');
}
function W_close(){
	// cierra la ventana
	W_default();
	CloseWindowForm(divForm);
	ventanita('cerrar');
}
function W_default(){
	//valores por defecto
	// f.cod.value = '';
	// f.ope.value = '';
	// f.tipo_doc.value = 'V';
	// f.ced.value = '';
	// f.fecha.value = '';
	// f.causa.value = '0';
	// f.obs.value = '';

	f.tipo_doc.disabled = false;
	f.ced.disabled = false;
	f.reset();
	ocultar_msj(msjBox);
}

function ventanita(ope){
	switch(ope){
		case 'add':
			titulo = '<i class="icon-plus"></i>Nuevo retiro';
			if( document.getElementById('btnReg') ){
				document.getElementById('btnReg').classList.remove('none');
				document.getElementById('btnMod').classList.add('none');
			}
			break;

		case 'mod':
			titulo = '<i class="icon-edit"></i>Modificar retiro';
			if( document.getElementById('btnMod') ){
				document.getElementById('btnMod').classList.remove('none');
				document.getElementById('btnReg').classList.add('none');
			}
			break;

		case 'cerrar':
			titulo = '<i class="icon-"></i>';
			break;
	}
	if( document.getElementById('W-nom') ){
		document.getElementById('W-nom').innerHTML = titulo;
	}
}

// eliminar servicio
function W_eliminar(cod,ced){
	f.cod.value = cod;
	nom = document.getElementById('celNom'+cod).innerHTML; // nombre del estudiante seleccionado
	ape = document.getElementById('celApe'+cod).innerHTML; // contenido de la celda
	m = '¿Desea eliminar el <b>retiro</b>?<br/>';
	p = '<p class="msj_error"><b>CI/CE: </b>'+ced+'<br/><b>Nombre y Apellido: </b>'+nom+' '+ape+'.</p>';
	adv = '<br><p class="msj_aviso"><b>Advertencia: </b> Si ústed no agregó éste retiro por <b>ERROR</b> y <b class="text_rosa">ELIMINA</b> éste registro, la inscripción del estudiante se activará nuevamente y la información puede verse alterada. <b>¿Desea continuar?</b></p>';
	document.getElementById('b1').innerHTML = '<p>Cancelar</p>';
	document.getElementById('b2').innerHTML = '<p><i class="icon-trash-empty"></i> Continuar</p>';
	document.getElementById('b2').style.display = 'block';
	document.getElementById('b2').onclick = Enviar_eliminar;
	OpenWindowNot(m+p+adv);
}
function Enviar_eliminar(){
	f.ope.value = 'elm';
	f.submit();
}

// TABLA Y AJAX
var m_desde = 0; // mostrar desde
var limite = 15; // cantidad de filas a mostrar
var total_filas; // total de resultados encontrados
var pag_actual = 1; // pagina seleccionada

function searchGrado(){ // AJAX -> retiros por grado
	obj1 = ajax_newObj();
	valor_grado = buscar_grado.value;
	cargar_ajax(obj1,imprimir_tabla,'POST',controlador ,'listar&desde='+m_desde+'&mostrar='+limite+'&grado='+valor_grado+'&periodo='+f.codAesc.value+'&eAesc='+f.eAesc.value+'&ajax=true');
}

function imprimir_tabla(){ // recibe XMLHttpRequest
	if( obj1.readyState == loading ){
		cargando = '<tr><td colspan="7"> Cargando, por favor espere... </td></tr>';
		resultados.innerHTML = thead+cargando;
	}
	else if( obj1.readyState == complete && obj1.status == 200 ){
		//alert(obj1.responseText);
		arr = obj1.responseText.split('%');
		//total_filas = parseInt(arr[0]); // total de registros encontrados en la BD
		//resultados.innerHTML = thead+arr[1]; // agrega los registros a la tabla
		resultados.innerHTML = thead+obj1.responseText;
		//se encuentra una consulta activa, el total de filas encontradas es mayor a 0, y pag
		// if( busqueda == true && total_filas > 0 && arr[2] == 1 ){
		// 	//alert('sipi');
		// 	pag_actual = 1;
		// 	m_desde = 0;
		// }
		//alert(pag_actual);
		//imprimir_paginas(); // imprime las paginas
		//imprimir_resultados();
	}
}

function buscar_estudiante(){
	ocultar_msj(msjBox);
	obj2 = ajax_newObj();
	if( f.ced.value.trim().length >= 8 ){
		cargar_ajax(obj2,imprimir_datos,'POST',controlador ,'ce='+f.tipo_doc.value+'-'+f.ced.value+'&ajax=true');
	}
}
function imprimir_datos(){ // recibe XMLHttpRequest
	if( obj2.readyState == loading ){
		f.nom.value = 'Cargando...'; 
		f.ape.value = 'Cargando...';
		f.gdo.value = 'Cargando...';
	}

	else if( obj2.readyState == complete && obj2.status == 200 ){
		
		switch( obj2.responseText ){
			case '1':
				msjBox[0].innerHTML = icon_attention+'El estudiante no se encuentra registrado.';
				mostrar_msj(msjBox[0]);
				limpiar();
				break;

			case '2':
				msjBox[0].innerHTML = icon_attention+'El estudiante ya se encuentra egresado.';
				mostrar_msj(msjBox[0]);
				limpiar();
				break;

			case '3':
				msjBox[0].innerHTML = icon_attention+'El estudiante no posee una inscripción';
				mostrar_msj(msjBox[0]);
				limpiar();
				break;

			default:
				arr = obj2.responseText.split('%');
				f.nom.value = arr[0]; 
				f.ape.value = arr[1];
				f.gdo.value = arr[2];
				break;
		}
	}


	function limpiar(){
		f.nom.value = ''; 
		f.ape.value = '';
		f.gdo.value = '';
	}
}

// BUSQUEDA POR AÑO
function escribirPeriodo( e ){
	searchPeriodo();
	if( txt_buscar.value.trim().length == 4 ){
		aS = parseInt(txt_buscar.value)+1;
		txt_buscar.value +='-'+aS; // agrega un guion y le suma 1 al año
	}
	return solo_numeros(e); 
}

function searchPeriodo(e){
	evento = e || window.event;
	ct = codigoTecla(evento);
	b = txt_buscar.value.trim().length;
	if( ct == 13 && b==9 ){ // presionó enter en la caja de texto
		window.location.href = '?ver=retiro&periodo='+txt_buscar.value;
	}
	else if( evento == '[object MouseEvent]' && b==9 ){ // click en el icono buscar
		window.location.href = '?ver=retiro&periodo='+txt_buscar.value;
	}
}

function reset_paginas(){
	pag_actual = 1;
	m_desde = 0; // mostrar desde
}

function imprimir_paginas(){
	div = document.getElementById('paginas'); // contenedor de los items_pag
	div.innerHTML = '';
	items = 5; // cantidad de items a mostrar
	total_paginas = Math.ceil(total_filas/limite); // redondea hacia arriba

	if( total_paginas > 1 ){ // existe mas de una página, 

		// el total de paginas no supera la cantidad de items a mostrar
		if( total_paginas < items ){ 
			for(i = 1; i <= total_paginas; i++){ // se recorre el total de paginas
				if( pag_actual == i ){
					div.innerHTML+='<div onclick="mostrar_pag(this,'+i+')" class="item_pag actual">'+i+'</div>';
				}
				else{
					div.innerHTML+='<div onclick="mostrar_pag(this,'+i+')" class="item_pag">'+i+'</div>';
				}
			}
		}

		// los 5 numeros siguientes de la pagina actual superan o es igual el total de paginas
		else if( pag_actual+items >= total_paginas ){ 
			// solo imprime hasta el total de paginas ej: total_pag = 9, total_pag-items = 4, imprime: [4,5,6,7,8,9] llegando al limite y evita imprimir mas items
			for(i=total_paginas-items; i<=total_paginas; i++){
				if( pag_actual == i ){
					div.innerHTML+='<div onclick="mostrar_pag(this,'+i+')" class="item_pag actual">'+i+'</div>';
				}
				else{
					div.innerHTML+='<div onclick="mostrar_pag(this,'+i+')" class="item_pag">'+i+'</div>';
				}
			}
		}

		// Imprime las 5 paginas siguientes de la pagina actual: ej: pag_actual = 2 imprime [2,3,4,5,6,7]
		else{ 
			for(i=pag_actual;i<=pag_actual+items;i++){
				if( pag_actual == i ){
					div.innerHTML+='<div onclick="mostrar_pag(this,'+i+')" class="item_pag actual">'+i+'</div>';
				}else{
					div.innerHTML+='<div onclick="mostrar_pag(this,'+i+')" class="item_pag">'+i+'</div>';
				}
			}
		}

		// boton anterior
		if( pag_actual > 1 ){ // Ej: pag_actual = 1; si pag_actual 
			div.innerHTML='<label class="pag_AS" onclick="pag_anterior()"><i class="icon-angle-left"></i>Anterior</label>'+div.innerHTML;
		}

		// Boton siguiente
		if( pag_actual != total_paginas ){
			div.innerHTML+='<label class="pag_AS" onclick="pag_siguiente()">Siguiente<i class="icon-angle-right"></i></label>';
		}
	}
}

// Imprime las filas que se estan mostrando y la cantidad de filas encontradas en la consulta
function imprimir_resultados(){ 
	div = document.getElementById('mostrando');
	filas = document.getElementsByClassName('fila');

	if( total_filas > 0 ){
		if( total_filas == 1 ){
			div.innerHTML = '<b>Mostrando</b>: '+filas.length+' fila de '+total_filas+' resultados.';
		}else{
			div.innerHTML = '<b>Mostrando</b>: '+filas.length+' filas de '+total_filas+' resultados.';
		}
	}

	else{
		div.innerHTML = '';
	}	
}

// limitar filas Ej: mostrar 15, mostrar 25, mostrar 40
function mostrar_filas(mostrar){
	// restart para evitar errores
	m_desde = 0; 
	pag_actual = 1;
	limite = mostrar;

	if( txt_buscar.value.trim() == '' ){
		listar_tabla();
	}
	else{
		enviar_consulta();
	}
}

// click al botón de numero de página
function mostrar_pag(elem,pag){ 
	pag_actual = pag; 
	if( pag_actual == 1 ){
		m_desde = 0;
	}
	else{
		m_desde = (pag_actual-1)*limite; // mostrar desde 
	}
	if( busqueda == true ){ // se esta ejecutando una consulta	
		enviar_consulta();
	}
	else{
		listar_tabla();
	}
}
// selecciona la pagina seguiente a la actual
function pag_siguiente(){ 
	pag_actual+=1;
	m_desde += limite;
	if( busqueda ==  true ){
		enviar_consulta();
	}else{
		listar_tabla();
	}
}
// selecciona la pagina anterior a la actual
function pag_anterior(){ 
	pag_actual-=1;
	m_desde -= limite;
	if( busqueda ==  true ){
		enviar_consulta();
	}else{
		listar_tabla();
	}
}