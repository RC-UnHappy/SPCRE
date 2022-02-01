window.onload = function(){
	// FORMULARIO Y EVENTOS
	f = document.f_metodo;
	f.enviarR.onclick = enviar_rol;
	f.enviarM.onclick = enviar_metodos;
	msjBox = document.getElementsByClassName('msjBox');

	controlador = '../CONTROL/c_metodo.php';
	modulo_buscar = document.getElementById('modulo_buscar');
	modulo_buscar.onchange = buscar_servicio;
	servicio_buscar = document.getElementById('servicio_buscar');
	servicio_buscar.onchange = buscar_metodos;

	// VENTANAS
	if( document.getElementById('open-W-form') ){
		document.getElementById('open-W-form').onclick = W_OpenAdd; // boton agregar
		divForm = document.getElementById('div_servicio');
		document.getElementById('close-W-form').onclick = W_close // boton cerrar ventana
	} 
	url();
}

function enviar_rol(){
	selRol = document.getElementById('selRol');
	if( validar_comboSelect(selRol,0) ){
		f.ope.value = 'add';
		f.rol.value = selRol.value;
		f.submit();
	}
}

function enviar_metodos(){
	f.ope.value = 'metodos';
	f.submit();
}

// AJAX
function buscar_servicio(){ // envia XMLHttpRequest
	obj = ajax_newObj();
	cargar_ajax(obj,mostrar_servicios,'POST',controlador,'ajax=1&modulo='+modulo_buscar.value);
}

function mostrar_servicios(){
	if( obj.readyState == complete && obj.status == 200 ){
		//alert(obj.responseText);
		opNulo = '<option value="0">Seleccionar</option>';
		servicio_buscar.innerHTML = opNulo+obj.responseText;
	}
}
// GET
function buscar_metodos(){
	window.location.href = '?ver=metodo&modulo='+modulo_buscar.value+'&servicio='+servicio_buscar.value;
}

// VENTANAS - ver archivo: ventanas.js
function W_OpenAdd(){ // abrir ventana para agregar
	f.ope.value = 'add';
	OpenWindowForm(divForm);
}

function W_default(){
	// valores por defecto
	f.reset();
	ocultar_msj(msjBox);
}

function W_close(){
	// cierra la ventana
	W_default();
	CloseWindowForm(divForm);
}

// eliminar enfermedad
function W_eliminar(codN, codS){
	f.rol.value = codN;
	f.servicio.value = codS;
	rol = document.getElementById('tdRol'+codN).innerHTML;
	m = 'Eliminar acciones sobre el <b>servicio</b><br/>';
	p = '<p class="msj_error">¿Desea eliminar las <b>Acciones</b> que tiene el Rol <b>'+rol+'</b> sobre el <b>servicio</b>? Si está seguro presione <b class="text_rosa">Continuar</b></p>';
	document.getElementById('b1').innerHTML = '<p>Cancelar</p>';
	document.getElementById('b2').innerHTML = '<p><i class="icon-trash-empty"></i> Continuar</p>';
	document.getElementById('b2').style.display = 'block';
	document.getElementById('b2').onclick = enviar_eliminar;
	OpenWindowNot(m+p);
}

function enviar_eliminar(){
	f.ope.value = 'elm';
	f.submit();
}

function chekeo(codN,codS,metodo){
	checked = this.event.target.checked;
	arrRoles = f.arrRoles.value.split(',');
	arrInc = f.arrInc.value.split(',');
	arrMod = f.arrMod.value.split(',');
	arrElm = f.arrElm.value.split(',');
	arrCons = f.arrCons.value.split(',');
	pos = arrRoles.indexOf(codN.toString());

	function operar(arreglo, checked){
		if( checked == true ){
			arreglo[pos] = 1;
		}else{
			arreglo[pos] = 0;
		}
	}

	switch(metodo){
		// incluir
		case 1:
			operar(arrInc, checked);
			f.arrInc.value = arrInc.join(',');
		break;
		// modificar
		case 2:
			operar(arrMod, checked);
			f.arrMod.value = arrMod.join(',');
		break;
		// eliminar
		case 3:
			operar(arrElm, checked);
			f.arrElm.value = arrElm.join(',');
		break;
		// consultar
		case 4:
			operar(arrCons, checked);
			f.arrCons.value = arrCons.join(',');
		break;
	}
}

function url(){
	msj1 = '¡Los datos se han registrado correctamente! <i class="icon-ok-circled2"></i>';
	msj2 = '¡Los datos han sido modificados correctamente! <i class="icon-ok-circled2"></i>';
	msj3 = '¡Error, no se han podido registrar los datos! <i class="icon-cancel-circled2"></i>';
	msj4 = '¡Error, no se han podido modificar los datos! <i class="icon-cancel-circled2"></i>';
	msj5 = '¡Los datos se han eliminado correctamente! <i class="icon-ok-circled2"></i>';
	p = '<p class="msj_error"> El nombre del <b>módulo</b> ya se encuentra registrado.</p>';

	if( vGet = getVariable('ope') ){
		switch(vGet){
			case '1':
			OpenWindowNot(msj1);
			break;	
			case '2':
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
			OpenWindowNot(msj4+p);
			break;		
		}
	}
}