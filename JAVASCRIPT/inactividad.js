var objTiempo;
tiempo = 480000; // 8 min
//tiempo = 3000;

// Tip: 1000 ms = 1 second.
function objetoTiempo() {
  objTiempo = setInterval(descontar, 1000);
}

function descontar() {
  tiempo = tiempo - 1000;
  //document.getElementById('tiempo').innerHTML = tiempo;
   if( tiempo == 0 ){
  	clearInterval( objTiempo );
  	window.location.href = '../CONTROL/logout.php?tiempo="1"';
  }
}
// carga los eventos que permitan reactivar el tiempo
function iniciarEventosTiempo(){
	document.onmousemove = reestablecer_tiempo;
	document.onkeypress = reestablecer_tiempo;
	//document.onwheel = reestablecer_tiempo;
	document.onscroll = reestablecer_tiempo;
}

function reestablecer_tiempo(){
	tiempo = 480000; // 8 min
	//tiempo = 3000;
}

iniciarEventosTiempo();
objetoTiempo();
