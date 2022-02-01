window.onload = function(){
	Vform = document.getElementById('vf_lapso'); // ventana que contiene el form
	document.getElementById('close-W-form').onclick = W_close;

	f = document.f_lapso;
	f.enviar.onclick = modificar;
	f.hnotasD.onkeyup = f.hnotasH.onkeyup = arreglar_hora;
	f.mnotasD.onkeyup = f.mnotasH.onkeyup = arreglar_minuto;
	f.hnotasD.onkeypress = f.hnotasH.onkeypress = solo_numeros; 
	f.mnotasD.onkeypress = f.mnotasH.onkeypress = solo_numeros; 
	msjBox = document.getElementsByClassName('mjB');
	sections = document.getElementsByClassName('formularios');

	// BUSCAR 
	txt_buscar = document.getElementById('txt_buscar');
	txt_buscar.onkeypress = escribirPeriodo;
	btn_buscar = document.getElementById('btn_buscar');
	btn_buscar.onclick = buscarPeriodo;
	urlVariables();
}

function editar(cod,eLap,fi,ff){
	W_open();
	f.ope.value = 'mod';
	f.cod.value = cod;
	f.lapso.value = eLap.replace(/%/g,' ');
	f.f_ini.value = fi;
	f.f_fin.value = fi; 
	sections[0].classList.remove('none');
	setBotonWindow('Guardar cambios','edit');
}

function abrir(cod){
	f.cod.value = cod;
	f.ope.value = 'abrir';
	f.submit();
}

var codCerrar; 
function cerrar(cod, lap){
	codCerrar = cod;
	h = '<b>¿Desea cerrar el Lapso '+lap+'?</b>';
	p = '<p class="msj_aviso"> <b>Nota:</b> Una vez cerrado el <b>Lapso</b> no podrá ser abierto nuevamente.</p>';
	document.getElementById('b1').innerHTML = '<p>Cancelar</p>';
	document.getElementById('b2').innerHTML = '<p><i class="icon-edit"></i> Continuar</p>';
	document.getElementById('b2').style.display = 'block';
	document.getElementById('b2').onclick = confirmar_cerrar;
	OpenWindowNot(h+p);
}

function confirmar_cerrar(){
	f.cod.value = codCerrar;
	f.ope.value = 'cerrar';
	f.submit();
}

function configurar(cod, apNotas, crNotas){
	W_open();
	f.ope.value = 'conf';
	f.cod.value = cod;
	sections[1].classList.remove('none');
	apNotas = apNotas.replace(/%/g,' ');
	crNotas = crNotas.replace(/%/g,' ');
	
	f.fnotasD.value = apNotas.substr(0,10); 
	f.hnotasD.value = apNotas.substr(11,2);
	f.mnotasD.value = apNotas.substr(14,2);
	f.tiemponotasD.value = apNotas.substr(20,2);
	f.fnotasH.value = crNotas.substr(0,10); 
	f.hnotasH.value = crNotas.substr(11,2);
	f.mnotasH.value = crNotas.substr(14,2);
	f.tiemponotasH.value = crNotas.substr(20,2);

	setBotonWindow('Guadar configuración','cog');
}


function modificar(){
	if( f.ope.value == 'mod' ){
		x = true;
		if(f.f_ini.value == ''){
			msjBox[0].innerHTML = icon_attention+'El campo es requerido.';
			mostrar(msjBox[0]);
			x = false;
		}
		if(f.f_fin.value == ''){
			msjBox[1].innerHTML = icon_attention+'El campo es requerido.';
			mostrar(msjBox[1]);
			x = false;
		}
		if(x == true){
			f.submit();
		}
	}
	else if( f.ope.value == 'conf' ){
		f.submit();
	}
}

// VENTANAS - ver archivo: ventanas.js
function W_open(){
	// muestra la ventana con el formulario
	OpenWindowForm(Vform); 
}

function W_close(){
	// cierra la ventana
	sections[0].classList.add('none');
	sections[1].classList.add('none');
	CloseWindowForm(Vform);
	W_default();
}

function W_default(){
	// valores por defecto
	f.reset();
	ocultar_msj(msjBox);
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
		window.location.href = '?ver=lapsos&consultar='+txt_buscar.value;
	}
}

function urlVariables(){ // busca variables en la URL
	if( vOpe = getVariable('ope') ){ // existe variable GET ope
		msj = '¡Los cambios se han realizado correctamente! <i class="icon-ok-circled2"></i>';
		if(vOpe!=''){
			OpenWindowNot(msj);
		}
	}
	if( vOpe = getVariable('error') ){ // existe variable GET ope
		msj = '¡No es posible cerrar el <b>Lapso</b>! <i class="icon-cancel-circled2"></i>';
		p = '<p class="msj_error">Por favor, asegúrese de que no hayan <b>Proyectos de aprendizajes</b> abiertos.</p>';
		if(vOpe!=''){
			OpenWindowNot(msj+p);
		}
	}
}

function setTituloWindow(nombre,icono){
	document.getElementById('W-nom').innerHTML = '<i class="icon-'+icono+'"></i>'+nombre;
}

function setBotonWindow(nombre,icono){
	document.getElementById('boton_enviar').innerHTML = '<i class="icon-'+icono+'"></i><p>'+nombre+'</p>';
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