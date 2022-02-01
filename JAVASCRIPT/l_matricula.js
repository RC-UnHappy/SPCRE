window.onload = function(){
	f = document.matricula;
	f.aesc.onkeypress = escribirPeriodo;
	f.aesc.onblur = buscar_seccion;
	f.enviar.onclick = validar;

	url();
}

function validar(){
	div = document.getElementById('mensaje');
	div.classList.add('none');
	
	if( f.aesc.value.trim()=='' || f.seccion.value=="0"){
		div.classList.remove('none');
	}
	else{
		window.location.href = '?ver=lista_matricula&seccion='+f.seccion.value;
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
		control = '../CONTROL/c_repResumen.php';
		cargar_ajax(obj,imprimir_secciones,'POST',control,'listarSec&periodo='+this.value); // envia peticion
	}
}

function imprimir_secciones(){ // imprime las secciones en el combo select
	x = '<option vallue="0">SELECCIONAR</option>';
	if( obj.readyState == complete && obj.status == 200 ){
		if( obj.responseText != '' ){
			f.seccion.innerHTML = x+obj.responseText;
		}
		else{
			f.seccion.innerHTML = x;
		}
	}
}

function url(){
	if( v = getVariable('error') ){
		switch(v){
			case '1':
			OpenWindowNot('¡No se encontraron datos! <i class="icon-cancel-circled2"></i>');
			break;
		}
	}
}
