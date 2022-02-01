window.onload = function(){

	if( document.form_notas ){
		f = document.form_notas;
		f.enviar.onclick = enviar;
		selNotas = document.getElementsByClassName('selNotas');
		itemI = document.getElementsByClassName('itemI'); // columnas de indicadores
		f.lapso.oninput = seleccionar_lapso;
	}

	if(document.form_notaFinal){
		fnf = document.form_notaFinal;
		fnf.lapso.oninput = seleccionar_lapso;
		fnf.literal.oninput = radioProm;

		if( fnf.enviarReg ){
			fnf.enviarReg.onclick = validar_boletinFinal;
		}

		if( fnf.enviarMod ){
			fnf.enviarMod.onclick = validar_boletinFinal;
		}
	}	
	url();
}

function enviar(){
	if( validar_notas() ){
		arrNotas();
		if(f.promedio.disabled==true){
			f.pm.value = 'N';
		}
		else{
			f.pm.value = 'S';
		}
		f.promedio.disabled = false;
		
		f.submit();
	}
}

// Valida que ningún campo esté vacío
function validar_notas(){
	if(f.promedio.disabled == true ){
		estado = true;
		for(i=0;i<(selNotas.length);i++){
			if( selNotas[i].value == '---' ){
				estado = false;
			}
		}
		if( estado != true ){
			msj3 = '¡Vaya, ha ocurrido un problema! <i class="icon-cancel-circled2"></i>';
			p = '<p class="msj_error"> No ha seleccionado todas las <b>notas</b>. Por favor, verifique e inténtelo nuevamente.</p>';
			OpenWindowNot(msj3+p);
		}
		else{
			return true;
		}
	}else{
		return true;
	}
}

function arrNotas(){
	tempArr = [];
	for(i=0;i<(selNotas.length);i++){
		tempArr.push(selNotas[i].value);
	}
	f.arrNota.value = tempArr;
}

function seleccionar_lapso(){
	vSec = getVariable('seccion');
	//alert(vSec);
	if(document.form_notas){
		window.location.href = '../VISTA/index.php?ver=notas&est='+f.cedEst.value+'&lapso='+this.value+'&seccion='+vSec;
	}
	else if( document.form_notaFinal ){ 
		//alert(vSec);
		window.location.href = '../VISTA/index.php?ver=notas&est='+fnf.cedEst.value+'&lapso='+this.value+'&seccion='+vSec;
	}
}


// calcula la nota general en el lapso
function promediar(){
	if( f.promedio.disabled == true ){
		x = true;
		// valida que ninguna campo este vacio 
		for(i=0;i<(selNotas.length);i++){
			if( selNotas[i].value == '---' ){
				x = false;
			}
		}

		if( x == true){
			contador = 0; 
			for(i=0;i<selNotas.length;i++){
				switch(selNotas[i].value){
					case 'A':  
						contador+=5;
						break;
					case 'B':  
						contador+=4;
						break;
					case 'C':  
						contador+=3;
						break;
					case 'D':  
						contador+=2;
						break;
					case 'E':
						contador+=1;
						break;
				}
			}
			x = contador/selNotas.length;
			promedio = Math.round(x);
			//promedio = round(x);
			// alert(promedio);
			pLiteral = '';
			switch(promedio){
				case 5:  
					pLiteral = 'A';
					break;
				case 4:  
					pLiteral = 'B';
					break;
				case 3:  
					pLiteral = 'C';
					break;
				case 2:  
					pLiteral = 'D';
					break;
				case 1:
					pLiteral = 'E';
					break;
			}
			f.promedio.value = pLiteral;
		}
	}
}

function url(){
	msj0 = '¡Los datos se han registrado correctamente! <i class="icon-ok-circled2"></i>';
	msj1 = '¡Los cambios se han realizado correctamente! <i class="icon-ok-circled2"></i>';
	msj2 = '¡Los datos han sido modificados correctamente! <i class="icon-ok-circled2"></i>';
	msj3 = '¡Error, no se han podido registrar los datos! <i class="icon-cancel-circled2"></i>';
	p = '<p class="msj_error"> El nombre del Aula ya se encuentra registrada.</p>';
	
	if( vGet = getVariable('ope') ){
		switch(vGet){
			case 'true':
				OpenWindowNot(msj1); // muestra alerta
				break;
		}
	}

	if( vGet = getVariable('opeBF') ){
		switch(vGet){
			case 'reg':
				OpenWindowNot(msj0); // muestra alerta
				break;

			case 'mod':
				OpenWindowNot(msj1); // muestra alerta
				break;
		}
	}
}

function activarPM(){ // promedio a mano
	btn = document.getElementById('btnPM');
	if( f.promedio.disabled == true ){
		f.promedio.disabled = false;
		f.pm.value = 'S';
		btn.classList.replace('btn_gris1','btn_gris2');
	}
	else{
		f.promedio.disabled = true;
		f.pm.value = 'N';
		btn.classList.replace('btn_gris2','btn_gris1');
		promedio();
	}
}		

function validar_boletinFinal(){
	msj = document.getElementById('msjfnf');
	msj.classList.add('none');

	if( this.name == 'enviarReg' ){
		fnf.opeBF.value = 'reg';
	}
	else if(this.name == 'enviarMod'){
		fnf.opeBF.value = 'mod';
	}
	if( fnf.desc.value.trim().length == '' ){
		msj.classList.remove('none');
		window.scrollTo(0, 50);
	}
	else if(fnf.rec.value.trim().length == ''){
		msj.classList.remove('none');
		window.scrollTo(0, 50);
	}
	else if(fnf.literal.value == '0'){
		msj.classList.remove('none');
		window.scrollTo(0, 50);
	}
	else{
		fnf.prom[0].disabled = false;
		fnf.prom[1].disabled = false;
		fnf.submit();
	}
}

function radioProm(){
	if( fnf.literal.value == 'E' ){
		fnf.prom[1].checked = true;
	}
	else{
		fnf.prom[0].checked = true;
	}
}

