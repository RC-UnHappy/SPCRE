window.onload = function(){
	if ( f = document.repResumen ){
		f.aesc.onkeypress = escribirPeriodo;
		f.enviar.onclick = validar;
	}	

	url();
}

function validar(){
	div = document.getElementById('mensaje');
	div.classList.add('none');
	
	if( f.aesc.value.trim()=='' ){
		div.classList.remove('none');
	}
	else{
		f.submit();
	}
}

function escribirPeriodo( e ){
	if( f.aesc.value.trim().length == 4 ){
		aS = parseInt(f.aesc.value)+1;
		f.aesc.value +='-'+aS; // agrega un guion y le suma 1 al año
	}
	return solo_numeros(e); 
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
