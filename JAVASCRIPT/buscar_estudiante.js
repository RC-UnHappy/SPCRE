window.onload = function(){ // cargan los eventos una vez termine de cargar la pagina
	campo = document.getElementById('campo_ced');
	btnEnv = document.getElementById('btn_enviar');
	campo.onkeydown = pressEnteKey;
	campo.onkeypress = solo_numeros;
	btn_enviar.onclick = enviar;
}

function pressEnteKey(event){
	if( PressEnter(event) ){
		enviar(); // presiona enter: envia 
	}
}

function enviar(){ // envia
	window.location.href = 'index.php?estudiante=visualizar&ced='+campo.value.trim(); 
}