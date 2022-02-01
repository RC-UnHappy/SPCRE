// Se cargan los eventos una vez que la página haya cargado
window.onload = function(){ 
	// VENTANAS
	if( document.getElementById('open-W-form') ){
		document.getElementById('open-W-form').onclick = agregar; // boton agregar
	}

	// formulario año escolar 
	f = document.f_aesc;
	f.enviar.onclick = enviar;
	f.periodo.disabled = true;
	divForm = document.getElementById('form-aesc');
	document.getElementById('close-W-form').onclick = W_close // boton cerrar ventana	
	sections = document.getElementsByClassName('formularios');
	// configuracion del año escolar
	// apertura para inscripcion nuevo ingreso
	f.hnuevoD.onkeyup = f.hnuevoH.onkeyup = arreglar_hora;
	f.mnuevoD.onkeyup = f.mnuevoH.onkeyup = arreglar_minuto;
	f.hregularD.onkeyup = f.hregularH.onkeyup = arreglar_hora;
	f.mregularD.onkeyup =  f.mregularH.onkeyup = arreglar_minuto;
	f.hnuevoD.onkeypress = f.hnuevoH.onkeypress = solo_numeros; 
	f.mnuevoD.onkeypress = f.mnuevoH.onkeypress = solo_numeros; 
	f.hregularD.onkeypress = f.hregularH.onkeypress = solo_numeros; 
	f.mregularD.onkeypress =  f.mregularH.onkeypress = solo_numeros; 
	
	// formulario clave de seguridad
	fSeg = document.f_claveSeg;

	mjB = document.getElementsByClassName('mjB');
	urlVariables();
}

function urlVariables(){ // busca variables en la URL
	if( vOpe = getVariable('ope') ){ // existe variable GET ope
		vPeriodo = getVariable('periodo');
		switch(vOpe){
			
			case 'add': 
				msj = '¡Año escolar '+vPeriodo+' Iniciado! <i class="icon-ok-circled2"></i>';
				OpenWindowNot(msj);
				break;

			case 'mod':
				msj = '¡Los cambios se han realizado correctamente! <i class="icon-ok-circled2"></i>';
				OpenWindowNot(msj);
				break;

			case 'mod_C':
				msj = '¡Año escolar '+vPeriodo+' Cerrado! <i class="icon-ok-circled2"></i>';
				OpenWindowNot(msj);
				break;

			case 'prom':
				msj = '¡Año escolar promovido! <i class="icon-ok-circled2"></i>';
				OpenWindowNot(msj);
				break;
		}
	}
	if( vError = getVariable('error') ){
		if( vError == 1 ){
			msj = '¡No se pudo registrar el nuevo año escolar! <i class="icon-cancel-circled2"></i>';
			p = '<p class="msj_error">Aún se encuentra un año escolar <b>activo</b>. Por favor, antes de registrar un nuevo año escolar debe cerrar el año escolar activo e inténtelo nuevamente.</p>';
			OpenWindowNot(msj+p);
		}
		else if( vError == 2 ){
			msj = '¡No es posible cerrar el año escolar! <i class="icon-cancel-circled2"></i>';
			p = '<p class="msj_error">La <b>clave de seguridad</b> es incorrecta.</p>';
			OpenWindowNot(msj+p);
		} 
		else if(vError == 3 ){
			msj = '¡No es posible <b>promover</b> el año escolar! <i class="icon-cancel-circled2"></i>';
			p = '<p class="msj_error">Asegúrese de que no hayan <b>Lapsos</b> abiertos.</p>';
			OpenWindowNot(msj+p);
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
	reset();
	CloseWindowForm(divForm);
}

function setTituloWindow(nombre,icono){
	document.getElementById('W-nom').innerHTML = '<i class="icon-'+icono+'"></i>'+nombre;
}

function setBotonWindow(nombre,icono){
	document.getElementById('boton_enviar').innerHTML = '<i class="icon-'+icono+'"></i><p>'+nombre+'</p>';
}

function enviar(){
	if( f.ope.value != 'conf'){
		submit = true;
		ocultar(mjB);
		if( f.periodo.value.trim() == '' ){
			submit = false;
		}
		if(f.f_ini.value == ''){
			mostrar(mjB[1]);
			mjB[1].innerHTML = icon_attention+'El campo es requerido.';
			submit = false;
		} 
		if(f.f_fin.value == ''){
			mostrar(mjB[2]);
			mjB[2].innerHTML = icon_attention+'El campo es requerido.';
			submit = false;
		}
		else if(f.f_ini.value >= f.f_fin.value){ // iguales
			mostrar(mjB[1]);
			mjB[1].innerHTML = icon_attention+'La fecha de inicio debe ser menor a la de cierre.';
			submit = false;
		}
		if( submit == true ){
			f.periodo.disabled = false;
			f.submit();
		}
	}
	else{
		f.submit();	
	}
}

function agregar(){
	W_open();
	f.periodo.value = f.p_sig.value;
	f.ope.value = 'add'; // cambia el operador
	sections[0].classList.remove('none');
	setTituloWindow('Nuevo año escolar','plus');
	setBotonWindow('Registrar','plus');
}

function modificar(cod,fi,ff,p){
	W_open();
	document.getElementById('periodo').innerHTML = 'Año escolar';
	f.ope.value = 'mod'; // cambia el operador
	f.cod.value = cod;
	f.periodo.value = p;
	f.f_ini.value = fi;
	f.f_fin.value = ff;
	sections[0].classList.remove('none');
	setTituloWindow('Modificar año escolar','edit');
	setBotonWindow('Guardar Cambios','edit')
}

function configurar( cod, periodo ){
	W_open();
	f.ope.value = 'conf';
	f.cod.value = cod;
	sections[1].classList.remove('none');
	titulo = '<b>Configuración del año escolar</b> <b class="text_rosa">('+periodo+')</b>';
	setTituloWindow(titulo,'cog');
	setBotonWindow('Guadar configuración','cog');
	consultar_configuracion(cod);
}

function reset(){
	ocultar(mjB);
	f.ope.value = 'add';
	document.getElementById('periodo').innerHTML = 'Año escolar siguiente';
	f.periodo.value = f.p_sig.value;
	f.f_ini.value = '';
	f.f_fin.value = '';
	sections[0].classList.add('none');
	sections[1].classList.add('none');
	setTituloWindow('','');
	setBotonWindow('','');
}

function consultar_configuracion(codAesc){
	control = '../CONTROL/c_a_escolar.php';
	obj = ajax_newObj();
	cargar_ajax(obj,respuesta_consulta,'POST',control,'ajax&codAesc='+codAesc);
}
function respuesta_consulta(){
	if( obj.readyState == complete && obj.status == 200 ){
		arr = obj.responseText.split('%');
		//alert(arr[6]);
		// 0000-00-00 00:00:00 xx
		f.fnuevoD.value = arr[0].substr(0,10); 
		f.hnuevoD.value = arr[0].substr(11,2);
		f.mnuevoD.value = arr[0].substr(14,2);
		f.tiemponuevoD.value = arr[0].substr(20,2);
		f.fnuevoH.value = arr[1].substr(0,10); 
		f.hnuevoH.value = arr[1].substr(11,2);
		f.mnuevoH.value = arr[1].substr(14,2);
		f.tiemponuevoH.value = arr[1].substr(20,2);

		f.fregularD.value = arr[2].substr(0,10); 
		f.hregularD.value = arr[2].substr(11,2);
		f.mregularD.value = arr[2].substr(14,2);
		f.tiemporegularD.value = arr[2].substr(20,2);
		f.fregularH.value = arr[3].substr(0,10); 
		f.hregularH.value = arr[3].substr(11,2);
		f.mregularH.value = arr[3].substr(14,2);
		f.tiemporegularH.value = arr[3].substr(20,2);

		f.fmatriculaD.value = arr[4];
		f.fmatriculaH.value = arr[5];
	}
}

// variables
var v_cod;
var v_periodo;
var v_estatus; 
function cambiar_estatus(cod,periodo,estatus){ // muestra ventana de dialogo antes de confirmar
	switch(estatus){
		case 'A':
			msj = '¿Desea abrir el año escolar '+periodo+'?<i class="icon-attention-circled"></i>';
			break;

		case 'C':
			msj = '¿Desea cerrar el año escolar '+periodo+'?<i class="icon-attention-circled"></i>';
			break;
	}

	// pasa valor a variables
	v_cod = cod;
	v_periodo = periodo;
	v_estatus = estatus; 
	document.getElementById('b1').onclick = mostrarVentanaClaveSeg; // agrega evento al boton aceptar
	document.getElementById('b2').style.display = 'block'; // muestra boton cancelar
	OpenWindowNot(msj);
}
// clave se seguridad 
function mostrarVentanaClaveSeg(){
	// agrega valor a los campos ocultos
	fSeg.codAEscolar.value = v_cod; 
	fSeg.h_periodo.value = v_periodo;
	fSeg.sta.value = v_estatus;
	// agrega evento a botones
	fSeg.enviar.onclick = enviarClaveSeg;
	fSeg.cancelar.onclick = cerrarWClaveSeg;
	ventana = document.getElementById('w-claveSeguridad');
	ventana.style = visible;
}
function enviarClaveSeg(){
	fSeg.passSeg.style.border = 'solid 1px transparent';
	if( fSeg.passSeg.value.trim() == '' ){
		fSeg.passSeg.style.border = 'solid 1px var(--rojo1)';
	}
	else{
		fSeg.submit();
	}
}
function cerrarWClaveSeg(){
	ventana = document.getElementById('w-claveSeguridad');
	ventana.style = oculto;
}

// promover
function promover(cod, periodo){
	f.cod.value = cod;
	m = '¿Desea promover los estudiantes del <b>Año escolar</b>?: <b class="text_rosa">'+periodo+'</b>?<br/>';
	document.getElementById('b1').innerHTML = '<p>Cancelar</p>';
	document.getElementById('b2').innerHTML = '<p><i class="icon-trash-logout"></i> Continuar</p>';
	document.getElementById('b2').style.display = 'block';
	document.getElementById('b2').onclick = confirmar_promover;
	OpenWindowNot(m);
}

function confirmar_promover(){
	f.ope.value = 'prom';
	f.submit();
	//alert('xd');
}

function arreglar_hora(){
	if( this.value > 12 ){
		this.value = 12;
	}
}
function arreglar_minuto(){
	if( this.value > 59 ){
		this.value = 59;
	}
}