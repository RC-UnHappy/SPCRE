on = 0;
function header(){	
	// se cargan los eventos
	//document.getElementById('hdr_user').onclick = desplegarOp;
	document.getElementById('li-logout').onclick = cerrar_sesion;
}

function desplegarOp(){ // muestra las opciones Mi Perfil/cerrar sesión
	dropMenu = document.getElementById('hdr_user_child');
	dropMenu.classList.add('show');
	on = 1;
}

function cerrar_sesion(){
	// ventana cerrar sesion
	document.getElementById('background-black').style = 'visibility:visible;opacity:.6;';
	document.getElementById('cont-windows').style = visible;
	document.getElementById('window-logout').style = visible;
	// agrega evento a las botones
	document.getElementById('logout-si').onclick = aceptar;
	document.getElementById('logout-no').onclick = cancelar;

	// redirecciona al controlador php que se encarga de cerrar las sesiones
	function aceptar(){
		window.location.href = '../CONTROL/logout.php?cerrar';
	}
	// oculta las ventanas
	function cancelar(){
		document.getElementById('background-black').style = oculto;
		document.getElementById('cont-windows').style = oculto;
		document.getElementById('window-logout').style = oculto;
	}
}	
header();