window.onload = function(){
	f = document.f_rol;
	f.enviar.onclick = enviar;

	document.getElementById('open-W-form').onclick = agregar; // boton agregar
	divForm = document.getElementById('div_rol');
	document.getElementById('close-W-form').onclick = cerrar // boton cerrar ventana
	msjBox = document.getElementsByClassName('msjBox');

	url();
}

function url(){
	msj1 = '¡Los datos se han registrado correctamente! <i class="icon-ok-circled2"></i>';
	msj2 = '¡Los datos han sido modificados correctamente! <i class="icon-ok-circled2"></i>';
	msj3 = '¡Error, no se han podido registrar los datos! <i class="icon-cancel-circled2"></i>';
	msj4 = '¡Error, no se han podido modificar los datos! <i class="icon-cancel-circled2"></i>';
	msj5 = '¡Módulo eliminado! <i class="icon-ok-circled2"></i>';
	p = '<p class="msj_error"> El nombre del <b>Rol</b> ya se encuentra registrado.</p>';

	if( vGet = getVariable('ope') ){
		switch(vGet){
			case '1':
			OpenWindowNot(msj1);
			break;	
			case '2':
			OpenWindowNot(msj2);
			break;	
			case '3':
			OpenWindowNot(msj5);
			break;		
		}		
	}
	if( vGet = getVariable('error') ){
		switch(vGet){
			case '1':
			OpenWindowNot(msj3+p);
			break;
			case '2':
			OpenWindowNot(msj4+p);
			break;		
		}
	}
}


// function url(){

// }

function enviar(){
	submit = true;
	if( !validar_campo(f.nom,0) ){
		submit = false;
	}

	else if( !validar_campo(f.desc,1) ){
		submit = false;
	}

	if( submit == true ){
		//alert(f.ope.value);
		f.submit();
	}
}

// VENTANAS
function agregar(){
	f.ope.value = 'add';
	OpenWindowForm(divForm); 
	ventanita('add');
}

function modificar(cod){
	f.ope.value = 'mod';
	nom = document.getElementById('celNom'+cod).innerHTML;
	desc = document.getElementById('celDesc'+cod).innerHTML;
	f.cod.value = cod;
	f.nom.value = nom;
	f.desc.value = desc;
	OpenWindowForm(divForm);
	ventanita('mod');
}

function wDefault(){
	// valores por defecto
	f.reset();
	ocultar_msj(msjBox);
}

function ventanita(ope=null){
	switch(ope){

		case 'add':
		titulo = '<i class="icon-plus"></i>Agregar Rol';
		boton = '<i class="icon-plus"></i><p>Agregar</p>';
		break;

		case 'mod':
		titulo = '<i class="icon-edit"></i>Modificar Rol';
		boton = '<i class="icon-edit"></i><p>Guardar Cambios</p>';
		break;

		default:
		titulo = ''; boton = '';
		break;

	}
	document.getElementById('W-nom').innerHTML = titulo;
	document.getElementById('boton_enviar').innerHTML = boton;
}

function cerrar(){
	// cierra la ventana
	CloseWindowForm(divForm);
	wDefault();
}

