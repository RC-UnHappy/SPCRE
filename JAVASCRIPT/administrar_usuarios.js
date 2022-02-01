function ready(callbackFunction){
  if(document.readyState != 'loading')
    callbackFunction(event)
  else
    document.addEventListener("DOMContentLoaded", callbackFunction)
}

ready(event => {
	controlador = '../CONTROL/c_administrar_usuarios.php';
	obj1 = ajax_newObj();

	// MODAL FORMULARIO
	if( document.getElementById('open-W-form') ){
		document.getElementById('open-W-form').onclick = W_open; // boton agregar
	}

	// Formulario
	f = document.formulario;
	f.enviar.onclick = enviar;
	f.cedula.onkeypress = solo_numeros;
	f.cedula.onchange = consultar_personal;
	f.tipo_doc.onchange = consultar_personal;

	divForm = document.getElementById('ventana_formulario');
	document.getElementById('close-W-form').onclick = W_close // boton cerrar ventana
	msjBox = document.getElementsByClassName('msjBox'); // cajas de dialogo que aparecen debajo de los inputs

	// busqueda y filtros
	if( document.getElementById('nivel_buscar') ){
		nivel_buscar = document.getElementById('nivel_buscar');
		nivel_buscar.onchange = set_nivel;
	}
	if( document.getElementById('estatus_buscar') ){
		estatus_buscar = document.getElementById('estatus_buscar');
		estatus_buscar.onchange = set_estatus;
	}
	if( document.getElementById('txt_buscar') ){
		txt_buscar = document.getElementById('txt_buscar');
		btn_buscar = document.getElementById('btn_buscar');
		txt_buscar.onkeyup = search_usuario;
		btn_buscar.onclick = search_usuario;
	}

	// TABLA 
	resultados = document.getElementById('resultados');
	thead = document.getElementById('thead').innerHTML;
	obj1 = ajax_newObj();
	imprimir_resultados();

	// variables URL
	if( vreg = getVariable('ope') ){ // existe variable get
		if( vreg == 'true' ){
			msj = '¡Los datos se han registrado correctamente!<i class="icon-ok-circled2"></i>';
			OpenWindowNot(msj); // muestra alerta
		}
	}
});

function enviar(){
	enviar = true;
	ocultar_msj(msjBox);
	
	// if( !validar_cedula(f.cedula,0,'si') ){
	// 	enviar = false;
	// }

	// else if( f.dias.value.trim() == '' ){
	// 	msjBox[2].innerHTML = icon_attention+'El campo es requerido';
	// 	mostrar_msj(msjBox[2]);
	// 	enviar = false;
	// }	

	// else if( f.fecha_desde.value == '' ){
	// 	msjBox[3].innerHTML = icon_attention+'El campo es requerido';
	// 	mostrar_msj(msjBox[3]);
	// 	enviar = false;
	// }

	// else if( f.fecha_desde.value == '' ){
	// 	msjBox[4].innerHTML = icon_attention+'El campo es requerido';
	// 	mostrar_msj(msjBox[4]);
	// 	enviar = false;
	// }

	// else if( f.descripcion.value == '' ){
	// 	msjBox[5].innerHTML = icon_attention+'El campo es requerido';
	// 	mostrar_msj(msjBox[5]);
	// 	enviar = false;
	// }

	// if( enviar == true ){
	// 	f.submit();
	// }
}

function consultar_personal(){
	if( f.cedula.value.length >= 0 ){
		ocultar_msj(msjBox);

		obj = ajax_newObj();
		cargar_ajax(obj,mostrar_datos,'POST',controlador,'ajax_personal&tipo_doc='+f.tipo_doc.value+'&cedula='+f.cedula.value);
		
		function mostrar_datos(){
			if( obj.readyState == complete && obj.status == 200 ){
				rs = obj.responseText;
				
				if( rs == 'false' ){
					msjBox[0].innerHTML = icon_attention+'El personal no existe';	
					mostrar_msj(msjBox[0]);
					f.nom_ape.value = '';
					f.cargo.value = '';
					f.funcion.value = '';
				}
				else{
					//alert(rs);
					json = JSON.parse(rs);
					f.nom_ape.value = json.nom1+' '+json.ape1;
					f.cargo.value = json.nom_cargo;
					f.funcion.value = json.nom_funcion;
				}	
			}
		}
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
	document.getElementById('W-nom').innerHTML = '<i class="icon-plus"></i>Registrar Usuario</h3>';
	document.getElementById('boton_enviar').innerHTML = '<i class="icon-plus"></i><p>Registrar Usuario</p>';
	ocultar_msj(msjBox);
	f.reset();
	f.ope.value = 'reg';
	f.cedula.disabled = false;
	f.tipo_doc.disabled = false;
}

function modificar(cod_fila,nvl,sta){
	W_open();
	document.getElementById('boton_enviar').innerHTML = '<i class="icon-edit"></i><p>Guardar cambios</p>';
	document.getElementById('W-nom').innerHTML = '<i class="icon-plus"></i>Modificar Usuario</h3>';
	f.ope.value = 'mod';
	f.cedula.disabled = true;
	f.tipo_doc.disabled = true;
	fila = document.getElementById('fila-'+cod_fila); 
	celdas = fila.childNodes;
	//alert(celdas[1].innerHTML.substring(2));
	f.tipo_doc.value = celdas[1].innerText.substring(0, 1);
	f.cedula.value = celdas[1].innerText.substring(2);
	f.nom_ape.value = celdas[2].innerText;
	f.cargo.value = celdas[3].innerText;
	f.funcion.value = celdas[4].innerText;
	f.nivel.value = nvl;
	f.estatus.value = sta;
}

// TABLA Y AJAX
var nivel = 'All';
var estatus = 'A';
var m_desde = 0; // mostrar desde
var limite = 15; // cantidad de filas a mostrar
var total_filas; // total de resultados encontrados
var pag_actual = 1; // pagina seleccionada

function listar_tabla(){ // envia XMLHttpRequest 
	cargar_ajax(obj1,imprimir_tabla,'POST',controlador ,'listar&desde='+m_desde+'&mostrar='+limite+'&nivel='+nivel+'&estatus='+estatus);
}

function enviar_consulta(){ // envia XMLHttpRequest
	cargar_ajax(obj1,imprimir_tabla,'POST',controlador,'filtrar&filtro='+txt_buscar.value+'&desde='+m_desde+'&mostrar='+limite+'&nivel='+nivel+'&estatus='+estatus);
}

function imprimir_tabla(){ // recibe XMLHttpRequest

	if( obj1.readyState == loading ){
		cargando = '<tr><td colspan="8"> Cargando, por favor espere... </td></tr>';
		resultados.innerHTML = thead+cargando;
	}
	else if( obj1.readyState == complete && obj1.status == 200 ){
		arr = obj1.responseText.split('%');
		total_filas = parseInt(arr[0]); // total de registros encontrados en la BD
		resultados.innerHTML = thead+arr[1]; // agrega los registros a la tabla
		//document.getElementById('xd').innerHTML = arr;
		
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
function search_usuario(e){ 
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
			div.innerHTML = '<b>Mostrando</b>: '+filas.length+' fila de '+total_filas+' registros.';
		}else{
			div.innerHTML = '<b>Mostrando</b>: '+filas.length+' filas de '+total_filas+' registros.';
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
function set_nivel(){
	nivel = this.value;
	if( busqueda ==  true ){
		enviar_consulta();
	}else{
		listar_tabla();
	}
}
function set_estatus(){
	estatus = this.value;
	if( busqueda ==  true ){
		enviar_consulta();
	}else{
		listar_tabla();
	}
}