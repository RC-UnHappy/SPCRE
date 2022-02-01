window.onload = function(){ // carga los eventos una vez la pagina  haya cargado
	controlador = '../CONTROL/c_vacuna.php';

	// VENTANAS
	if( document.getElementById('open-W-form') ){
		document.getElementById('open-W-form').onclick = W_open; // boton agregar
	}
	divForm = document.getElementById('form-vcna');
	document.getElementById('close-W-form').onclick = W_close; // boton cerrar ventana
	
	// BUSCAR
	if( document.getElementById('txt_buscar') ){
		txt_buscar = document.getElementById('txt_buscar');
		txt_buscar.onkeyup = searchVacuna;
		btn_buscar =  document.getElementById('btn_buscar');
		btn_buscar.onclick = searchVacuna; 
	}

	// TABLA 
	resultados = document.getElementById('resultados');
	thead = document.getElementById('thead').innerHTML;
	obj1 = ajax_newObj();

	// FORMULARIOS
	f = document.f_vacuna; // formulario
	f.enviar.onclick = enviar_formulario;	
	f.nom.onchange = validar_nom;
	f.ope.value = 'add';
	msjBox = document.getElementsByClassName('msjBox');

	urlVarGet();
	imprimir_resultados();
}

// TABLA Y AJAX
var m_desde = 0; // mostrar desde
var limite = 15; // cantidad de filas a mostrar
var total_filas; // total de resultados encontrados
var pag_actual = 1; // pagina seleccionada

function listar_tabla(){ // envia XMLHttpRequest 
	cargar_ajax(obj1,imprimir_tabla,'POST',controlador ,'listar&desde='+m_desde+'&mostrar='+limite);
}

function enviar_consulta(){ // envia XMLHttpRequest
	cargar_ajax(obj1,imprimir_tabla,'POST',controlador,'filtrar&filtro='+txt_buscar.value+'&desde='+m_desde+'&mostrar='+limite);
}

function imprimir_tabla(){ // recibe XMLHttpRequest
	if( obj1.readyState == loading ){
		cargando = '<tr><td colspan="3"> Cargando, por favor espere... </td></tr>';
		resultados.innerHTML = thead+cargando;
	}
	else if( obj1.readyState == complete && obj1.status == 200 ){
		arr = obj1.responseText.split('%');
		total_filas = parseInt(arr[0]); // total de registros encontrados en la BD
		resultados.innerHTML = thead+arr[1]; // agrega los registros a la tabla

		// se encuentra una consulta activa, el total de filas encontradas es mayor a 0, y pag
		if( busqueda == true && total_filas > 0 && arr[2] == 1 ){
			pag_actual = 1;
			m_desde = 0;
		}

		imprimir_paginas(); // imprime las paginas
		imprimir_resultados();
	}
}

busqueda = false;
function searchVacuna(e){ 
	evento = e || window.event;
	// se obtiene el codigo de la tecla
	ct = codigoTecla(evento);
	b = txt_buscar.value.trim().length;
	if( ct == 13 && b>0 ){ // presionó enter en la caja de texto
		busqueda = true; // se actuva la consulta
		enviar_consulta();
	}
	else if( evento == '[object MouseEvent]' && b>0 ){ // click en el icono buscar
		busqueda = true; // se actuva la consulta
		enviar_consulta();
	}
	else if( ct == 8 && b==0 && busqueda == true){ // presiona back 
		// restaura
		m_desde = 0;
		pag_actual = 1;
		busqueda = false;
		listar_tabla();
	}
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

// VENTANAS - ver archivo: ventanas.js
function W_open(){
	// muestra la ventana con el formulario
	OpenWindowForm(divForm); 
}

function W_close(){ // cierra una ventana
	W_default();
	CloseWindowForm(divForm);
}

function W_default(){
	// valores por defecto
	f.reset();
	ocultar_msj(msjBox);
	document.getElementById('W-nom').innerHTML = '<i class="icon-plus"></i>Agregar vacuna';
	document.getElementById('boton_enviar').innerHTML = '<i class="icon-plus"></i><p>Agregar</p>';
	document.getElementById('boton_enviar').classList.remove('btn_rojo');
	document.getElementById('boton_enviar').classList.add('btn_verde');
	document.getElementById('aviso').classList.add('none');
	f.ope.value = 'add';
	f.nom.disabled = false;
}

// FORMULARIOS
function enviar_formulario(){ // valida y envia
	if( validar_nom() ){
		f.nom.disabled = false;
		f.submit();
	}
}
// abre la ventana para modificar
function W_OpenMod(cod){
	W_open();
	f.cod.value = cod;
	f.ope.value = 'mod';
	f.nom.value = document.getElementById(cod+'-nomVacuna').innerText;
	document.getElementById('W-nom').innerHTML = '<i class="icon-edit"></i>Modificar vacuna: '+cod;
	document.getElementById('boton_enviar').innerHTML = '<i class="icon-edit"></i><p>Guardar cambios</p>';
}
// eliminar vacuna
function W_eliminar(cod){
	W_open();
	f.ope.value = 'elm';
	f.cod.value = cod;
	f.nom.value = document.getElementById(cod+'-nomVacuna').innerText;
	f.nom.disabled = true;
	document.getElementById('W-nom').innerHTML = '<i class="icon-trash-empty"></i>Eliminar vacuna';
	document.getElementById('boton_enviar').innerHTML = '<i class="icon-trash-empty"></i><p>Eliminar<p>';
	document.getElementById('boton_enviar').classList.remove('btn_verde');
	document.getElementById('boton_enviar').classList.add('btn_rojo');
	document.getElementById('aviso').classList.remove('none');
}

function validar_nom(){ // valida el campo nombre
	ocultar_msj(msjBox);
	if( validar_nombre(f.nom,0,'si') ){
		return true;
	}
}
function urlVarGet(){
	msj1 = '¡Los datos se han registrado correctamente! <i class="icon-ok-circled2"></i>';
	msj2 = '¡Los datos han sido modificados correctamente! <i class="icon-ok-circled2"></i>';
	msj3 = '¡Error, no se han podido registrar los datos! <i class="icon-cancel-circled2"></i>';
	msj4 = 'Vacuna eliminada! <i class="icon-ok-circled2"></i>';
	p = '<p class="msj_error"> El nombre de la vacuna ya se encuentra registrada.</p>';
	if( vGet = getVariable('add') ){ // existe variable get add
		OpenWindowNot(msj1); // muestra alerta
	}
	if( vGet = getVariable('mod') ){
		OpenWindowNot(msj2); // muestra alerta
	}
	if( vElm = getVariable('elm') ){
		OpenWindowNot(msj4);
	}
	if( vGet = getVariable('error') ){
		OpenWindowNot(msj3+p);
	}
}