window.onload = function(){
	// se cargan los eventos
	f = document.plantel;
	f.tlfn.onkeydown = agrega_guionTlf; f.tlfn.onkeypress = solo_numeros;
	f.image.onchange = img_encabezado;
	f.enviar.onclick = enviarDatos;

	if( url = getVariable('mod')){
		OpenWindowNot('¡Los datos se han modificado correctamente! <i class="icon-ok-circled2"></i>');
	}
}

function enviarDatos(){ 
	// valida el formulario antes de enviar
	p = document.getElementsByClassName('msj_error')[0];
	p.classList.add('none');

	submit = true;
	if( f.director.value == 0 ){
		submit = false;
	}
	else if(f.codplantel.value.trim() == ''){
		submit = false;
	}
	else if(f.codestco.value.trim()==''){
		submit = false;
	}
	else if(f.coddea.value.trim() == ''){
		submit = false;
	}
	else if(f.zonaeduc.value.trim() == ''){
		submit = false;
	}
	else if(f.edo.value.trim() == ''){
		submit = false;
	}
	else if(f.mun.value.trim() == ''){
		submit = false;
	}
	else if(f.nombre.value.trim()==''){
		submit = false;
	}
	else if(f.direccion.value.trim()==''){
		submit = false;
	}
	else if(f.tlfn.value.trim()==''){
		submit = false;		
	}
	else if(f.correo.value.trim()==''){
		submit = false;
	}

	if( submit == false ){
		p.classList.remove('none');
		p.innerHTML = '<i class="icon-attention tx_rojo"></i>Todos los campos son <b>requeridos</b>.';
	}
	else{
		f.submit();
	}
}

function img_encabezado(){
	p = document.getElementsByClassName('msj_error')[0];
	p.classList.add('none');

	file = f.image; // input file
	ext = file.files[0].name.toLowerCase().split('.').pop(); // recuperamos la extension del archivo	
	ope = true;

	if( ext != 'jpg' ){
		p.innerHTML = '<i class="icon-attention tx_rojo"></i>La imagen no cumple con la extensión adecuada. debe ser: <b>"JPG"</b>.';
		p.classList.remove('none');
		ope = false;
	}
	else if( MaxFileSize(file.files[0].size, 500000) ){
		p.innerHTML = '<i class="icon-attention tx_rojo"></i>El tamaño máximo permitido es de <b>500Kb.</b>.';
		p.classList.remove('none');
		ope = false;
	}	
	if( ope == true ){
		prev = document.getElementById('imgPrev');
		previsualizar(file, prev);
	}
	else{
		file.value = '';
	}
}
