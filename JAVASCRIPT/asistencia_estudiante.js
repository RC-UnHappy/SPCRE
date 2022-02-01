function ready(callbackFunction){
  if(document.readyState != 'loading')
    callbackFunction(event)
  else
    document.addEventListener("DOMContentLoaded", callbackFunction)
}

ready(event => {
	controlador = '../CONTROL/c_asistencia_estudiante.php';

	if( f = document.form_buscar ){
		f.enviar.onclick = function(){
			if( f.seccion.value != 0 ){
				window.location.href = '?ver=asistencia_estudiante&seccion='+f.seccion.value;
			}	
			else{
				document.getElementById('mensaje').classList.remove('none');
			}
		}
	}

	if( document.getElementById('mes_buscar') ){
		mes = document.getElementById('mes_buscar');
		document.getElementById('mes_buscar').onchange = buscar_dias_habiles;
	}

	if( document.getElementById('diahbl_buscar') ){
		dia_habil = document.getElementById('diahbl_buscar');
		document.getElementById('diahbl_buscar').oninput = seleccionar_dia_habil;
	}

	if( document.getElementById('codSec') ){
		codSec = document.getElementById('codSec');
	}

	if( f_asis = document.form_asistencia ){
	
		f_asis.onclick = function(){
			datos = {"codper":[],"sta":[]};
			fila_est = document.getElementsByClassName('fila_est');
			input_asis = document.getElementsByClassName('input_asis');

			if( mes.value != 0 && dia_habil.value != 0 ){
				enviar = true;
				
				// verifica que ningun campo select esté vacio
				for (var i = 0; i < input_asis.length; i++) {
					if( input_asis[i].value == 0 ){ enviar = false }
				}

				if( enviar == true ){
					for (var i = 0; i < fila_est.length; i++){
						codper = fila_est[i].id.split('-')[1];
						datos['codper'].push(codper);
						datos['sta'].push(input_asis[i].value);
					}
					f_asis.mes.value = mes.value
					f_asis.dia_habil.value = dia_habil.value;
					f_asis.datos.value = JSON.stringify(datos);
					f_asis.submit();
				}
				else{
					msj = '¡Los campos <b>Asistencia</b> son requeridos! <i class="icon-cancel-circled2"></i>';
					OpenWindowNot(msj); // muestra alerta
				}
			}
		}
	}

	// variables URL
	if( vreg = getVariable('ope') ){ // existe variable get
		if( vreg == 'true' ){
			msj = '¡Los datos se han registrado correctamente!<i class="icon-ok-circled2"></i>';
			OpenWindowNot(msj); // muestra alerta
		}
	}

});


function buscar_dias_habiles(){
	objMes = ajax_newObj();
	cargar_ajax(objMes,imprimir_dias,'POST',controlador,'ajax&dias_habiles&mes='+this.value);

	function imprimir_dias(){
		if( objMes.readyState == complete && objMes.status == 200 ){
			rs = objMes.responseText;
			dia_habil.innerHTML = '<option value="0">SELECCIONAR</option>'+rs;
		}
	}
}

function seleccionar_dia_habil(){ // dia habil seleccionado
	if( mes.value != 0 && dia_habil.value != 0 ){	
		listar();
	}
}

function listar(){
	obj = ajax_newObj();
	cargar_ajax(obj,listar_estudiantes,'POST',controlador,'ajax&listar&seccion='+codSec.value+'&dia='+dia_habil.value);

	function listar_estudiantes(){
		tabla = document.getElementById('resultados');
		trtotal = '<tr id="trtotal">'+document.getElementById('trtotal').innerHTML+'</tr>';
		thead = '<tr id="thead">'+document.getElementById('thead').innerHTML+'</tr>';

		if( obj.readyState == loading ){
			cargando = '<tr><td colspan="4"> Cargando, por favor espere... </td></tr>';
			tabla.innerHTML = trtotal+thead+cargando;
		}
		else if( obj.readyState == complete && obj.status == 200 ){
			rs = obj.responseText;
			//alert(rs);
			tabla.innerHTML = trtotal+thead+rs;
		}
	}
}

