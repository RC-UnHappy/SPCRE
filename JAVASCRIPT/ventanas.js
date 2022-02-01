// muestra las ventanas
function OpenWindowForm(idFormDiv){ 
	// muestra las capas
	document.getElementById('background-black').style = 'visibility:visible;opacity:.6';
	document.getElementById('cont-windows').style = visible;
	document.getElementById('window-cont-formulario').style = visible;
	// agrega un formulario a la ventana contenedor id="window-cont-formulario"
	document.getElementById('window-cont-formulario').appendChild(idFormDiv);
	idFormDiv.style = 'display:block';
}
// oculta las ventanas
function CloseWindowForm(idFormDiv){
	document.getElementById('background-black').style = oculto;
	document.getElementById('cont-windows').style = oculto;
	document.getElementById('window-cont-formulario').style = oculto;
	idFormDiv.style = 'display:none';
	document.getElementById('window-cont-formulario').removeChild(idFormDiv);
}

// muestra ventana de notificaciones
function OpenWindowNot(msjP){ // recibe como parametro el mensaje que se va a añadir
	document.getElementById('background-black').style = 'visibility:visible;opacity:.6';
	document.getElementById('cont-windows').style = visible;
	document.getElementById('window-not').style = visible;
	document.getElementById('ok').onclick = CloseWindowNot;
	document.getElementById('msj').innerHTML = msjP;
}
// cierra la ventana de notificacion
function CloseWindowNot(){
	document.getElementById('tlt').innerHTML = 'Mensaje: ';
	document.getElementById('background-black').style = oculto;
	document.getElementById('cont-windows').style = oculto;
	document.getElementById('window-not').style = oculto;
}



