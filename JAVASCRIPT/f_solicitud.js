window.onload = function(){ // se cargan los eventos
	f = document.solicitud;
	f.enviar.onclick = enviar;
	f.ced.onkeypress = solo_numeros;

	if( Vdatos = getVariable('datos') ){	
		if( Vdatos == 'false' ){
			OpenWindowNot('¡No se encontraron resultados! <i class="icon-cancel-circled2"></i>');
		}
	}
}

function enviar(){ 
	// validacion del formulario antes de enviar
	div = document.getElementById('mensaje');
	div.classList.add('none');
	if( f.ced.value.trim() == '' || f.reporte.value == 0 ){
		div.classList.remove('none');
	}
	else{
		aesc = f.aesc.value;
		ced = f.tipo_doc.value+'-'+f.ced.value.trim();
		tipo = f.reporte.value;
		window.location.href = '../CONTROL/c_reportesEst.php?reporte='+tipo+'&cedEsc='+ced+'&aesc='+aesc;
	}
}

