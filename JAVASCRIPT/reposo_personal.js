function ready(callbackFunction){
  if(document.readyState != 'loading')
    callbackFunction(event)
  else
    document.addEventListener("DOMContentLoaded", callbackFunction)
}

ready(event => {
	controlador = '../CONTROL/c_reposo_personal.php';

	// MODAL FORMULARIO
	if( document.getElementById('open-W-form') ){
		document.getElementById('open-W-form').onclick = W_open; // boton agregar
	}

	// Formulario
	f = document.formulario;
	f.enviar.onclick = enviar;
	f.cedula.onkeypress = solo_numeros;
	f.cedula.onchange = consultar_personal;
	f.tipo_doc.onchange = consultar_personal;
	f.fecha_desde.oninput = f.fecha_hasta.oninput = f.dias.onchange = calcular_dias;
	f.dias.onkeypress = solo_numeros;

	divForm = document.getElementById('ventana_formulario');
	document.getElementById('close-W-form').onclick = W_close // boton cerrar ventana

	// variables URL
	if( vreg = getVariable('ope') ){ // existe variable get
		if( vreg == 'true' ){
			msj = '¡Los datos se han registrado correctamente!<i class="icon-ok-circled2"></i>';
			OpenWindowNot(msj); // muestra alerta
		}
	}

	msjBox = document.getElementsByClassName('msjBox'); // cajas de dialogo que aparecen debajo de los inputs
	//urlVariablesGet();
});

function enviar(){
	enviar = true;
	ocultar_msj(msjBox);
	
	if( !validar_cedula(f.cedula,0,'si') ){
		enviar = false;
	}

	else if( f.dias.value.trim() == '' ){
		msjBox[2].innerHTML = icon_attention+'El campo es requerido';
		mostrar_msj(msjBox[2]);
		enviar = false;
	}	

	else if( f.fecha_desde.value == '' ){
		msjBox[3].innerHTML = icon_attention+'El campo es requerido';
		mostrar_msj(msjBox[3]);
		enviar = false;
	}

	else if( f.fecha_desde.value == '' ){
		msjBox[4].innerHTML = icon_attention+'El campo es requerido';
		mostrar_msj(msjBox[4]);
		enviar = false;
	}

	else if( f.descripcion.value == '' ){
		msjBox[5].innerHTML = icon_attention+'El campo es requerido';
		mostrar_msj(msjBox[5]);
		enviar = false;
	}

	if( enviar == true ){
		f.submit();
	}
}

function consultar_personal(){
	if( f.cedula.value.length >= 0 ){
		ocultar_msj(msjBox);

		obj = ajax_newObj();
		cargar_ajax(obj,mostrar_datos,'POST',controlador,'ajax_personal&tipo_doc='+f.tipo_doc.value+'&cedula='+f.cedula.value);
		
		function mostrar_datos(){
			if( obj.readyState == complete && obj.status == 200 ){
				rs = obj.responseText;
				if( rs == 'false' ){
					msjBox[0].innerHTML = icon_attention+'El personal no existe';	
					mostrar_msj(msjBox[0]);
					f.nom_ape.value = '';
				}
				else{
					//alert(rs);
					json = JSON.parse(rs);
					f.nom_ape.value = json.nom1+' '+json.ape1;
					f.cargo.value = json.nom_cargo;
				}	
			}
		}
	}
}

function calcular_dias(){
	if( this.name == 'fecha_desde' || this.name == 'dias' ){
		// calcula fecha_hasta
		if( f.dias.value.trim() != '' && f.fecha_desde.value != '' ){
			fecha_hasta = new Date( f.fecha_desde.value );
			fecha_hasta.setDate( fecha_hasta.getDate()+ parseInt(f.dias.value) );
			dia = fecha_hasta.getDate(); dia<10?dia='0'+dia:false;
			mes = fecha_hasta.getMonth()+1; mes<10?mes='0'+mes:false;
			anio = fecha_hasta.getFullYear();
			f.fecha_hasta.value = anio+'-'+mes+'-'+dia;

			// if( f.fecha_desde.value != '' && f.fecha_hasta.value != '' ){
			// 	f.dias.value = diferencia_dias(f.fecha_desde.value, f.fecha_hasta.value);
			// }
		}
	}

	else if( this.name == 'fecha_hasta' ){
		// calcula numero de dias
		if( f.fecha_desde.value != '' && f.fecha_hasta.value != '' ){
			f.dias.value = diferencia_dias(f.fecha_desde.value, f.fecha_hasta.value);
		}
	}
}

function diferencia_dias(desde,hasta){
	desde = new Date(desde); 
	hasta = new Date(hasta);
	resta = hasta.getTime()-desde.getTime();
	dias = resta/1000/60/60/24 //diferencia en dias
	if( dias >= 0 ){
		return dias+1;
	}
	else{
		return 0
	}
}

// VENTANAS - ver archivo: ventanas.js
function W_open(){
	// muestra la ventana con el formulario
	OpenWindowForm(divForm); 
}

function W_close(){
	// cierra la ventana
	W_default();
	CloseWindowForm(divForm);
}

function W_default(){
	document.getElementById('boton_enviar').innerHTML = '<i class="icon-plus"></i><p>Registrar</p>';
	ocultar_msj(msjBox);
	f.reset();
	f.ope.value = 'reg';
}

function modificar(codreposo){
	W_open();
	document.getElementById('boton_enviar').innerHTML = '<i class="icon-edit"></i><p>Guardar cambios</p>';
	f.ope.value = 'mod';
	fila = document.getElementById('reposo-'+codreposo); celdas = fila.childNodes;
	//alert(celdas[1].innerHTML.substring(2));
	f.codrep.value = codreposo;
	f.tipo_doc.value = celdas[1].innerText.substring(0, 1);
	f.cedula.value = celdas[1].innerText.substring(2);
	f.nom_ape.value = celdas[2].innerText;
	f.cargo.value = celdas[3].innerText;
	f.fecha_desde.value = formato_fecha(celdas[4].innerText);
	f.fecha_hasta.value = formato_fecha(celdas[5].innerText);
	f.descripcion.value = celdas[6].innerText;
	f.dias.value = diferencia_dias( f.fecha_desde.value, f.fecha_hasta.value);
}

function eliminar(codreposo){
	W_open();
	f.ope.value = 'elm';
}


