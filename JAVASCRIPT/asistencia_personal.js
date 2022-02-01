function ready(callbackFunction){
  if(document.readyState != 'loading')
    callbackFunction(event)
  else
    document.addEventListener("DOMContentLoaded", callbackFunction)
}

ready(event => {
	// formulario
	f = document.formulario;

	controlador = '../CONTROL/c_asistencia_personal.php';

	if( document.getElementById('marcacion') ){
		marcacion = document.getElementById('marcacion');
		marcacion.onchange = buscar_marcacion;
	}

	if( document.getElementById('mes_buscar') ){
		mes = document.getElementById('mes_buscar');
		document.getElementById('mes_buscar').onchange = buscar_dias_habiles;
	}

	if( document.getElementById('diahbl_buscar') ){
		dia_habil = document.getElementById('diahbl_buscar');
		document.getElementById('diahbl_buscar').onchange = seleccionar_dia_habil;
	}
	
	if( document.getElementById('cargo_buscar') ){
		cargo = document.getElementById('cargo_buscar');
		document.getElementById('cargo_buscar').onchange = listar;
	}	
	
	// BUSCAR
	if( document.getElementById('txt_buscar') ){
		txt_buscar = document.getElementById('txt_buscar');
		txt_buscar.onkeyup = filtrarBusqueda;
		btn_buscar =  document.getElementById('btn_buscar');
		btn_buscar.onclick = filtrarBusqueda; 
	}

	// TABLA 
	resultados = document.getElementById('resultados');
	thead = document.getElementById('thead').innerHTML;
	imprimir_resultados();

	// variables URL
	if( vreg = getVariable('ope') ){ // existe variable get
		if( vreg == 'true' ){
			msj = '¡Los datos se han registrado correctamente!<i class="icon-ok-circled2"></i>';
			OpenWindowNot(msj); // muestra alerta
		}
	}

});

function registrar(cod_per){
	codPer = document.getElementById('fila-'+cod_per).id.substring(5);
	sta = document.getElementById('inputEstatus-'+cod_per);
	hora = document.getElementById('inputHora-'+cod_per);
	obs = document.getElementById('inputObs-'+cod_per);

	listo = true;

	if( sta.value == '0' && hora.value.trim()=='' ){
		msj = '¡Los campos <b>Asistencia</b> y <b>hora</b> son requeridos! <i class="icon-cancel-circled2"></i>';
		listo = false;
		OpenWindowNot(msj); // muestra alerta
	}

	if( sta.value == 'A' && hora.value.trim()=='' ){
		msj = '¡Los campos <b>Asistencia</b> y <b>hora</b> son requeridos! <i class="icon-cancel-circled2"></i>';
		listo = false;
		OpenWindowNot(msj); // muestra alerta
	}

	if( listo == true ){
		datos = {"codper":codPer,"sta":sta.value,"hora":hora.value,"obs":obs.value}
		f.mes.value = mes.value;
		f.dia_habil.value = dia_habil.value;
		f.datos.value = JSON.stringify(datos);
		f.marcacion.value = marcacion.value
		f.submit();
	}
}

function buscar_marcacion(){ // busca asistencias por marcacion entrada o salida
	if( busqueda ==  true ){
		filtrar();
	}
	else{
		listar();
	}
}

function buscar_dias_habiles(){
	objMes = ajax_newObj();
	cargar_ajax(objMes,imprimir_dias,'POST',controlador,'ajax&dias_habiles&mes='+this.value);

	function imprimir_dias(){
		if( objMes.readyState == complete && objMes.status == 200 ){
			rs = objMes.responseText;
			dia_habil.innerHTML = '<option value="0">SELECCIONAR</option>'+rs;
		}
	}
}

function seleccionar_dia_habil(){ // dia habil seleccionado
	if( mes.value != 0 && dia_habil.value != 0 ){	
		listar();
	}
}

// DATATABLE Y AJAX
var m_desde = 0; // mostrar desde
var limite = 15; // cantidad de filas a mostrar
var total_filas; // total de resultados encontrados
var pag_actual = 1; // pagina seleccionada

// envia XMLHttpRequest al servidor
function listar(){
	if( document.getElementById('diahbl_buscar').value != 0 ){
		obj = ajax_newObj();
		cargar_ajax(obj,imprimir_tabla,'POST',controlador,'ajax&listar&desde='+m_desde+'&mostrar='+limite+'&marcacion='+marcacion.value+'&dia_habil='+dia_habil.value+'&cargo='+cargo.value);
	}
}

function filtrar(){ 
	if( document.getElementById('diahbl_buscar').value != 0 ){
		obj = ajax_newObj();
		cargar_ajax(obj,imprimir_tabla,'POST',controlador,'ajax&filtrar&filtro='+txt_buscar.value+'&desde='+m_desde+'&mostrar='+limite+'&marcacion='+marcacion.value+'&dia_habil='+dia_habil.value+'&cargo='+cargo.value);
	}
}

function imprimir_tabla(){ // recibe XMLHttpRequest y actualiza la tabla
	if( obj.readyState == loading ){
		cargando = '<tr><td colspan="8"> Cargando, por favor espere... </td></tr>';
		resultados.innerHTML = thead+cargando;
	}
	else if( obj.readyState == complete && obj.status == 200 ){
		arr = obj.responseText.split('%');
		total_filas = parseInt(arr[0]); // total de registros encontrados en la BD

		resultados.innerHTML = thead+arr[1]; // agrega los registros a la tabla
		// se encuentra una consulta activa, el total de filas encontradas es mayor a 0, y pag
		if( busqueda == true && total_filas > 0 && arr[2] == 1 ){
			pag_actual = 1;
			m_desde = 0;
		}
		//alert(arr);
		imprimir_paginas(); // imprime las paginas
		imprimir_resultados();
	}
}

var busqueda = false;
function filtrarBusqueda(e){ 
	evento = e || window.event;
	// se obtiene el codigo de la tecla
	ct = codigoTecla(evento);
	b = txt_buscar.value.trim().length;
	if( ct == 13 && b>0 ){ // presionó enter en la caja de texto
		busqueda = true; // se esta ejecutando una busqueda
		filtrar();
	}
	else if( evento == '[object MouseEvent]' && b>0 ){ // click en el icono buscar
		busqueda = true;
		filtrar();
	}
	else if( ct == 8 && b==0 && busqueda == true){ // presiona back 
		// restaura
		m_desde = 0;
		pag_actual = 1;
		busqueda = false;
		listar();
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
	if ( div = document.getElementById('mostrando') ){
		filas = document.getElementsByClassName('fila');

		if( total_filas > 0 ){
			if( total_filas == 1 ){
				div.innerHTML = '<b>Mostrando</b>: '+filas.length+' fila de '+total_filas+' resultados.';
			}else{
				div.innerHTML = '<b>Mostrando</b>: '+filas.length+' filas de '+total_filas+' resultados.';
			}
		}

		else{
			div.innerHTML = '<b>Mostrando</b>: '+filas.length+' filas de '+total_filas+' resultados.';
		}	
	}
}
// limitar filas Ej: mostrar 15, mostrar 25, mostrar 40
function mostrar_filas(mostrar){
	// restart para evitar errores
	m_desde = 0; 
	pag_actual = 1;
	limite = mostrar;

	if( txt_buscar.value.trim() == '' ){
		listar();
	}
	else{
		filtrar();
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
		filtrar();
	}
	else{
		listar();
	}
}
// selecciona la pagina seguiente a la actual
function pag_siguiente(){ 
	pag_actual+=1;
	m_desde += limite;
	if( busqueda ==  true ){
		filtrar();
	}else{
		listar();
	}
}
// selecciona la pagina anterior a la actual
function pag_anterior(){ 
	pag_actual-=1;
	m_desde -= limite;
	if( busqueda ==  true ){
		filtrar();
	}else{
		listar();
	}
}

function setNivel(valor){
	nivel = valor;
	if( busqueda ==  true ){
		filtrar();
	}else{
		listar();
	}
}

function setEstatus(valor){
	estatus = valor;
	if( busqueda ==  true ){
		filtrar();
	}else{
		listar();
	}
}

function escribir_hora(o=this){
	//00:00:00
	evento = o.event || window.event;
	codigo = evento.charCode || evento.keyCode || evento.which;

	if( codigo != 8 ){
		if( o.value.length == 2 ){ 
			if( o.value > 12 ){
				o.value = 12;
			}
			o.value += ':';
		}
		if( o.value.length == 5 ){
			arr = o.value.split(':');
			if( arr[1] > 59 ){
				arr[1] = 59;
				o.value = arr[0]+':'+arr[1];
			}
			o.value += ':';
		}
		if( o.value.length == 7 ){
			arr = o.value.split(':');
			if( arr[2] > 5 ){
				arr[2] = 5;
				o.value = arr[0]+':'+arr[1]+':'+arr[2];
			}
		}
	}
}