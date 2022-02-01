window.onload = function(){
	f = document.repBoletin;
	f.enviar.onclick = enviar;
	f.ced.onkeypress = solo_numeros;
	f.aesc.onkeypress = escribirPeriodo;
	url();
}

function enviar(){
	if( validar() ){ 
		f.submit();
	}
}

function validar(){
	submit = true;
	if( f.ced.value.trim() == '' ) {
		submit = false;
	}
	else if( f.lapso.value == '0' ){
		submit = false;
	}
	if( submit == false ){
		document.getElementById('mensaje').classList.remove('none');
	}
	else{
		document.getElementById('mensaje').classList.add('none');
		return true;
	}
}

function periodo_escolar(){
	if( f.aesc.value.length == 4 ){
		alert('x');
	}
	return solo_numeros();
}

function escribirPeriodo( e ){
	if( f.aesc.value.trim().length == 4 ){
		aS = parseInt(f.aesc.value)+1;
		f.aesc.value +='-'+aS; // agrega un guion y le suma 1 al año
	}
	return solo_numeros(e); 
}

function url(){
	msj = '¡No se encontraron resultados! <i class="icon-cancel-circled2"></i>';
	p = '<p class="msj_error">Por favor, asegúrese de escribir correctamente la <b>Cédula Escolar</b> y el <b>Año Escolar</b> e inténtelo nuevamente.</p>';
	if( v = getVariable('error') ){
		if( v = '1'){
			OpenWindowNot(msj+p);
		}
	}
}