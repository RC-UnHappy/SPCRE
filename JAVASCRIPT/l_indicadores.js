window.onload = function(){
	secciones = document.getElementsByClassName('secciones');

	fbuscar = document.fbuscar;
	fbuscar.aesc.onkeypress = escribirPeriodo;
	fbuscar.aesc.onchange = buscar_seccion;
	fbuscar.enviar.onclick = consultar;

	f = document.f_indicador;
	f.lapso.oninput = lapso;

	if( f.btnREG_PA ){
		f.btnREG_PA.onclick = gestionar_pa;
	}
	
	if( f.btnMOD_PA ){
		f.btnMOD_PA.onclick = gestionar_pa;
	}

	f.regIND.onclick = agregar_ind;
	f.modIND.onclick = modificar_ind;
		
	// ventana - indicadores
	if( document.getElementById('open-W-form') ){
		document.getElementById('open-W-form').onclick = W_open; // boton agregar indicador
	}
	ventana = document.getElementById('ventana_indicadores');
	document.getElementById('close-W-form').onclick = W_close // boton cerrar ventana

	if(f.cerrarIndBtn){
		f.cerrarIndBtn.onclick = wCerrarInd;
	}

	msjBox = document.getElementsByClassName('msjBox');
	url();
}

function agregar_ind(){
	x = document.getElementById('nomInd');
	if( validar_campo(x, 2) ){
		f.opeIND.value = 'reg';
		f.hNameInd.value = x.value;
		f.submit();
	} 
}

function modificar_ind(){
	x = document.getElementById('nomInd');
	if( validar_campo(x, 2) ){
		f.opeIND.value = 'mod';
		f.hNameInd.value = x.value;
		f.submit();
	} 
}

function seleccionar(cod,nom){
	f.codIND.value = cod;
	f.nomInd.value = nom.replace(/%/g,' ');
}

function gestionar_pa(){
	submit = true;
	if( !validar_campo(f.nomPA,0) ){
		submit = false;
	}
	else if( !validar_campo(f.tiempoPA,1)){
		submit = false;
	}
	
	if( submit == true ){
		if(this.name == 'btnREG_PA'){
			f.opePA.value = 'reg';
		}
		else if(this.name == 'btnMOD_PA'){
			f.opePA.value = 'mod';
		}
		f.submit();
	}
}

function lapso(){
	window.location.href = '?ver=indicadores&seccion='+f.seccion.value+'&lapso='+this.value;
}

function wCerrarInd(){
	h = '<b>¿Desea Continuar?</b>';
	p = '<p class="msj_aviso"> Por favor, asegúrese de haber agregado todos los <b>indicadores</b> del PA antes de continuar. Una vez finalice la carga de indicadores para el boletín de lapso no podrá <b>agregar</b> ni <b>modificar</b>.</p>';
	//document.getElementById('b1').onclick = confCerrarInd; // agrega evento al boton aceptar
	//document.getElementById('b2').style.display = 'block'; // muestra boton cancelar
	document.getElementById('b1').innerHTML = '<p>Cancelar</p>';
	document.getElementById('b2').innerHTML = '<p><i class="icon-edit"></i> Continuar</p>';
	document.getElementById('b2').style.display = 'block';
	document.getElementById('b2').onclick = confCerrarInd;
	OpenWindowNot(h+p);
}

// confirmar
function confCerrarInd(){
	f.cerrarIndH.value = 'C';
	f.submit();
}

function url(){
	msj1 = '¡Los datos se han registrado correctamente! <i class="icon-ok-circled2"></i>';
	msj2 = '¡Los datos han sido modificados correctamente! <i class="icon-ok-circled2"></i>';
	msj3 = '¡Error, no se han podido registrar los datos! <i class="icon-cancel-circled2"></i>';
	msj4 = '¡No se encontraron resultados! <i class="icon-cancel-circled2"></i>';
	msj5 = '¡Indicador eliminado! <i class="icon-ok-circled2"></i>';

	if( !getVariable('seccion') && !getVariable('lapso') ){
		secciones[0].classList.remove('none');
	}
	else{
		secciones[1].classList.remove('none');
	}

	if( v = getVariable('error') ){
		if( v == '1' ){
			OpenWindowNot(msj4);
		}
	}

	if( vGet = getVariable('opeInd') ){
		switch(vGet){
			case 'reg':
				OpenWindowNot(msj1); // muestra alerta
				break;
			case 'mod':
				OpenWindowNot(msj2); // muestra alerta
				break;
			case 'elm':
				OpenWindowNot(msj5); // muestra alerta
				break;
		}
	}
	if( vGet = getVariable('opePA') ){
		switch(vGet){
			case 'reg':
				OpenWindowNot(msj1); // muestra alerta
				break;
			case 'mod':
				OpenWindowNot(msj2); // muestra alerta
				break;
			case 'modEstatus':
				OpenWindowNot(msj2); // muestra alerta
				break;

		}
	}
}

// ventanas
function W_open(){
	// muestra la ventana con el formulario
	if( f.codPA.value == '' ){
		msj = '¡No es posible registrar indicadores! <i class="icon-cancel-circled2"></i>';
		p = '<p class="msj_error"> Por favor, registre el <b>proyecto de aprendizaje</b> del lapso.</p>';
		OpenWindowNot(msj+p);
	}
	else{
		OpenWindowForm(ventana); 
	}
}

function W_close(){
	// cierra la ventana
	W_default();
	CloseWindowForm(ventana);
}

function W_OpenMod(cod){  // abre la ventana para modificar
	W_open();
	f.opeIND.value = 'mod';
	f.codIND.value = cod;
	x = document.getElementById('nomInd'); // campo (input);
	td = document.getElementById('nomInd'+cod);
	x.value = td.innerText;
	document.getElementById('btnINDreg').classList.add('none');
	document.getElementById('btnINDmod').classList.remove('none');
}

function W_default(){
	// valores por defecto
	f.opeIND.value = '';
	ocultar_msj(msjBox);
	x = document.getElementById('nomInd');
	x.value = '';
	document.getElementById('btnINDreg').classList.remove('none');
	document.getElementById('btnINDmod').classList.add('none');
}
function W_eliminar(cod){
	f.opeIND.value = 'elm';
	f.codIND.value = cod;
	m = '¿Desea eliminar el <b>Indicador</b>?<br/>';
	document.getElementById('b1').innerHTML = '<p>Cancelar</p>';
	document.getElementById('b2').innerHTML = '<p><i class="icon-trash-empty"></i> Continuar</p>';
	document.getElementById('b2').style.display = 'block';
	document.getElementById('b2').onclick = Enviar_eliminar;
	OpenWindowNot(m);
}

function Enviar_eliminar(){
	f.submit();
}
///////////
function abrirBoletin(){
	f.cerrarIndH.value = 'A';
	f.submit();
}
// buscar seccion (consultar)
// AJAX
function buscar_seccion(){
	obj = ajax_newObj(); // crea el objeto Ajax
	control = '../CONTROL/c_indicadores.php';
	cargar_ajax(obj,imprimir_secciones,'POST',control,'listarSec&codPeriodo='+this.value); // envia peticion
}
function imprimir_secciones(){ // imprime las secciones en el combo select
	if( obj.readyState == complete && obj.status == 200 ){
		if( obj.responseText != '' ){
			fbuscar.seccion.innerHTML = obj.responseText;
		}
		else{
			fbuscar.seccion.innerHTML = '<option value="0">SELECCIONAR</option>';
		}
	}
}
function escribirPeriodo( e ){
	if( fbuscar.aesc.value.trim().length == 4 ){
		aS = parseInt(fbuscar.aesc.value)+1;
		fbuscar.aesc.value +='-'+aS; // agrega un guion y le suma 1 al año
	}
	return solo_numeros(e); 
}
function consultar(){
	if( fbuscar.aesc.value != '' && fbuscar.seccion.value != 0 ){
		window.location.href = '?ver=indicadores&lapso=1&seccion='+fbuscar.seccion.value; 	
	}
	else{
		document.getElementById('mensaje').classList.remove('none');
	}
}

