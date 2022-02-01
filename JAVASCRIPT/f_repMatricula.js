window.onload = function(){
	if ( f = document.repMatricula ){
		f.tipo.onchange = activar_mes;
		f.aesc.onkeypress = escribirPeriodo;
		f.aesc.onchange = buscar_seccion;
		f.enviar.onclick = validar;
	}	
}

function validar(){
	div = document.getElementById('mensaje');
	div.classList.add('none');


	if( f.mes.disabled == true ){
		if( f.tipo.value == '0' || f.aesc.value.trim()=='' || f.cod_seccion.value=="0"){
			div.classList.remove('none');
		}
		else{
			f.mes.disabled = false;
			f.submit();
		}
	}
	else{
		if( f.tipo.value == '0' || f.mes.value == '0' || f.aesc.value.trim()=='' || f.cod_seccion.value=="0"){
			div.classList.remove('none');
		}	
		else{
			f.mes.disabled = false;
			f.submit();
		}
	}
}

function escribirPeriodo( e ){
	if( f.aesc.value.trim().length == 4 ){
		aS = parseInt(f.aesc.value)+1;
		f.aesc.value +='-'+aS; // agrega un guion y le suma 1 al año
	}
	return solo_numeros(e); 
}

// AJAX
function buscar_seccion(){
	if( f.aesc.value.trim().length == 9 ){
		obj = ajax_newObj(); // crea el objeto Ajax
		control = '../CONTROL/c_matricula.php';
		cargar_ajax(obj,imprimir_secciones,'POST',control,'listarSec&periodo='+this.value); // envia peticion
	}
}

function imprimir_secciones(){ // imprime las secciones en el combo select
	x = '<option value="0">SELECCIONAR</option>';
	if( obj.readyState == complete && obj.status == 200 ){
		if( obj.responseText != '' ){
			f.cod_seccion.innerHTML = x+obj.responseText;
		}
		else{
			f.cod_seccion.innerHTML = x;
		}
	}
}

function activar_mes(){
	if( f.tipo.value == 'M' ){
		f.mes.disabled = false;
	}
	else{
		f.mes.disabled = true;
	}
}