window.onload = function(){

	if( f1 = document.resumenEstadistico ){
		f1.aesc.onkeypress = escribirPeriodo;
		f1.tipoResumen1.onchange = desbloquear_mes;
		f1.enviar.onclick = validarf1;
	}

	if( f2 = document.resumenMatricula ){
		f2.aesc.onkeypress = escribirPeriodo;
		f2.tipoResumen2.onchange = desbloquear_mes;
		f2.enviar.onclick = validarf2;
	}

	if ( f3 = document.repResumen ){
		f3.aesc.onkeypress = escribirPeriodo;
		f3.aesc.onchange = buscar_seccion;
		f3.enviar.onclick = validarf3;
	}	

	msj_error=document.getElementsByClassName('msj_error');
}

function validarf1(){
	msj_error[0].classList.add('none');

	if( f1.mes.disabled == true ){
		if(f1.tipoResumen1.value == '0' || f1.aesc.value.trim().length == 0){
			msj_error[0].classList.remove('none');
		}
		else{
			f1.submit();
		}
	}
	else{
		if(f1.tipoResumen1.value == '0' || f1.mes.value == '0' || f1.aesc.value.trim().length == 0){
			msj_error[0].classList.remove('none');
		}
		else{
			f1.submit();
		}
	}
}

function validarf2(){
	msj_error[1].classList.add('none');

	if( f2.mes.disabled == true ){
		if(f2.tipoResumen2.value == '0' || f2.aesc.value.trim().length == 0){
			msj_error[1].classList.remove('none');
		}
		else{
			f2.submit();
		}
	}
	else{
		if(f2.tipoResumen2.value == '0' || f2.mes.value == '0' || f2.aesc.value.trim().length == 0){
			msj_error[1].classList.remove('none');
		}
		else{
			f2.submit();
		}
	}
}

function validarf3(){
	msj_error[1].classList.add('none');
	if( f3.aesc.value.trim()=='' || f3.seccion.value=="0"){
		msj_error[1].classList.remove('none');
	}
	else{
		f3.submit();
	}
}

function escribirPeriodo( e ){
	if( this.value.trim().length == 4 ){
		aS = parseInt(this.value)+1;
		this.value +='-'+aS; // agrega un guion y le suma 1 al año
	}
	return solo_numeros(e); 
}

// AJAX
function buscar_seccion(){
	if( f3.aesc.value.trim().length == 9 ){
		obj = ajax_newObj(); // crea el objeto Ajax
		control = '../CONTROL/c_repResumen.php';
		cargar_ajax(obj,imprimir_secciones,'POST',control,'listarSec&periodo='+this.value); // envia peticion
	}
}

function imprimir_secciones(){ // imprime las secciones en el combo select
	x = '<option vallue="0">SELECCIONAR</option>';
	if( obj.readyState == complete && obj.status == 200 ){
		if( obj.responseText != '' ){
			f3.seccion.innerHTML = x+obj.responseText;
		}
		else{
			f3.seccion.innerHTML = x;
		}
	}
}

function desbloquear_mes(){
	tipoResSel = '';
	mesSel = '';

	switch(this.name){
		case 'tipoResumen1':
			tipoResSel = f1.tipoResumen1;
			mesSel = f1.mes;
			break;

		case 'tipoResumen2':
			tipoResSel = f2.tipoResumen2;
			mesSel = f2.mes;
			break;
	}
	if( tipoResSel.value == 'M' ){
		mesSel.disabled = false;
	}
	else{
		mesSel.disabled = true;
	}
}