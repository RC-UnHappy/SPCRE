function ready(callbackFunction){
  if(document.readyState != 'loading')
    callbackFunction(event)
  else
    document.addEventListener("DOMContentLoaded", callbackFunction)
}

ready(event => {
	arrDias = []; 
	f = document.formulario;
	f.enviar.onclick = enviarForm;
	arrAlterno = f.arrDias.value.split(',');
	for (i in arrAlterno){
		arrDias.push(arrAlterno[i]);
	}

	variablesUrl();
});


function enviarForm(){
	f.arrDias.value = arrDias;
	f.submit();
}

function operar(obj){ // agrega o elimina al array el día habil
	if( obj.classList.contains('checked') ){
		obj.classList.remove('checked');
		// Elimina del arreglo
		index = arrDias.indexOf(obj.id);
		if ( index > -1 ) {
		   arrDias.splice(index, 1);
		}
	}	
	else{
		obj.classList.add('checked');
		arrDias.push(obj.id);
	}
}

function variablesUrl(){ // busca variables en la URL
	msj = '¡Los cambios se han realizado correctamente! <i class="icon-ok-circled2"></i>';
	if( variable = getVariable('ope') ){ // existe variable get ope
		OpenWindowNot(msj); // muestra alerta
	}
}



