window.onload = function(){ // se cargan los eventos al cargar la página
	// SECCIONES DE LA PAGINA
	section = document.getElementsByClassName('secciones'); 

	if( document.getElementById('principal_estudiante') ){
		menu_x = document.getElementById('menu-x');

		// FORMULARIO CONSULTAR ESTUDIANTE
		document.getElementById('btnBuscarEst').onclick = consultar;
		document.getElementById('buscar').onkeypress = teclaEnterBuscar;

		// FORMULARIO ESTUDIANTE
		fEst = document.estudiante;
		btnsEst = document.getElementsByClassName('btnsEst'); // botones registrar y modificar
		
		if( fEst.enviarRegistrar ){
			fEst.enviarRegistrar.onclick = enviarRegistrar;
		}	
		if( fEst.enviarModificar ){
			fEst.enviarModificar.onclick = enviarModificar;
		}
		// Representantes del Estudiante
		fEst.ced_madre.onkeypress = fEst.ced_padre.onkeypress = solo_numeros;
		fEst.tdoc_madre.onchange = fEst.tdoc_padre.onchange =  buscar_representante;
		fEst.ced_madre.onchange = fEst.ced_padre.onchange =  buscar_representante;
		fEst.nom_madre.disabled = fEst.nom_padre.disabled = true;

		// EVENTOS: 
		// identidad del estudiante
		fEst.ced_est.onchange = existencia_cedula; fEst.ced_est.onkeypress = solo_numeros;
		fEst.cedEsc.onchange = existencia_cedula;
		fEst.nom1.onkeypress = fEst.nom2.onkeypress = solo_letras;
		fEst.nom1.onblur = validar_nom1; 
		fEst.nom2.onblur = validar_nom2; 
		fEst.ape1.onblur = validar_ape1;
		fEst.ape2.onblur = validar_ape2;
		fEst.sex.onblur = validar_genero;
		fEst.fnac.onblur = calcularEdad_y_validar;
		fEst.tlfn.onchange = validar_tlfn;
		fEst.tlfn.onkeypress = solo_numeros; 
		fEst.tlfn.onkeydown = agrega_guionTlf;
		fEst.ordenNac.onchange = agregar_ordenNacimiento;
		fEst.foto.onchange = validar_foto; 

		// Lugar de nacimiento
		fEst.paisNac.onchange = buscaEdo;
		fEst.edoNac.onchange = buscaMun; 
		fEst.munNac.onchange = buscaParr;

		// Direccion de Domicilio
		fEst.edoDom.onchange = buscaMun;
		fEst.munDom.onchange = buscaParr; 

		// datos antropométricos
		fEst.estatura.onkeydown = agrega_punto; fEst.estatura.onkeypress = solo_numeros;
		fEst.peso.onkeydown = agrega_punto; fEst.peso.onkeypress = solo_numeros;
		fEst.camisa.onkeypress = numeros_letras;
		fEst.pantalon.onkeypress = numeros_letras;
		fEst.calzado.onkeypress = solo_numeros; 

		// Datos socieconómicos
		fEst.num_habitaciones.onchange = validar_numHabitaciones; fEst.num_habitaciones.onkeypress = solo_numeros; 
		fEst.num_personas.onchange = validar_numPersonas; fEst.num_personas.onkeypress = solo_numeros; 
		fEst.num_personasT.onchange = validar_numPersonasT; fEst.num_personasT.onkeypress = solo_numeros; 
		fEst.num_hermanos.onkeypress = solo_numeros; 
		fEst.num_hermanosEsc.onkeypress = solo_numeros;
		fEst.ing_familiar = validar_numIngFamiliar; fEst.ing_familiar.onkeypress = solo_numeros;

		cLugares = '../CONTROL/c_lugares.php';
		msjBox = document.getElementsByClassName('msjBox');
	}
	msjModo = document.getElementsByClassName('msjModo');
	urlVariables(); 
}

function setTitulo(name){ // cambia el titulo de la página
	document.getElementById('tt').innerHTML = name;
}

function urlVariables(){ // captura las variables que vienen por metodo GET
	if( vEst = getVariable('Estudiante') ){
		// Sección consultar
		if( vEst == 'consultar'){
			if( section[0] ){
				section[0].classList.remove('none'); // muestra solo la capa de consulta  
			}
			if( !getVariable('modo') ){
				if( msjModo[0] ){
					msjModo[0].classList.remove('none');
				}	
			}
		}
		// Sección Formulario Estudiante / Registrar
		else if( vEst == 'registrar' ){
			if( section[2] ){
				section[2].classList.remove('none'); // muestra la capa que contiene los formularios
				btnsEst[0].classList.remove('none'); // muestra boton registrar
				fEst.classList.remove('none'); // muestra el formulario estudiante
			}
		}
		// Sección Formulario Estudiante / Modificar
		else if( vEst == 'visualizar' ){ 
			// ESTUDIANTE
			menu_x.classList.remove('none');
			section[2].classList.remove('none'); // muestra capa que contienen los formularios
			btnsEst[1].classList.remove('none'); // muestra botón guardar cambios
		
			calcularEdad();
			fEst.classList.remove('none');
			// INSCRIPCION - Carga los eventos para la inscripción
			cargarEventosIns();
		}	
	} 

	if( vmodo = getVariable('modo') ){
		switch(vmodo){
			case 'regular':
				if( msjModo[1] ){
					msjModo[1].classList.remove('none');
				}
				setTitulo('Inscripción Regular');
				break;

			case 'nuevo':
				setTitulo('Inscripción Nuevo Ingreso');
				break;
		}
	}

	msj1 = '¡Los datos se han modificado correctamente! <i class="icon-ok-circled2"></i>';
	msj2 = '¡Los datos se han registrado correctamente! <i class="icon-ok-circled2"></i>';
	msj3 = '¡No se han podido registrar los datos! <i class="icon-cancel-circled2"></i>';
	msj4 = '¡No se encontraron datos! <i class="icon-cancel-circled2"></i>';
	msj5 = '¡Inscripción cancelada! <i class="icon-ok-circled2"></i>';
	p1 = '<p class="msj_error">La <b>cédula</b> que introdújo ya se encuentra registrada.</p>';
	p2 = '<p class="msj_error">La <b>cédula</b> escolar ya se encuentra registrada.</p>';

	if( vReg = getVariable('reg') ){
		if( vReg == 'true' ){
			OpenWindowNot(msj2);
		}
	}
	if( vMod = getVariable('mod') ){
		if( vMod == 'true' ){
			OpenWindowNot(msj1);
		}
	}  
	if( vCons = getVariable('consulta') ){
		// para consulta antes de una inscripción regular, si no existe se permite registrar un estudiante nuevo en el sistema en modalidad regular
		if(vModo = getVariable('modo') ){
			if(vModo=='regular'){
				p1 = '<p class="text_size1">El o la estudiante que intenta buscar no se encuentra <b>registrado(a)</b>. Por favor, asegúrese de haber escrito correctamente la cédula escolar o documento de identidad. Si desea registrar un nuevo estudiante haga <a href="?Estudiante=registrar&modo=regular" class="text_rosa text_bold"><u>click aquí.</u></a></p>';
				div = '<div class="msj_info">'+p1+'</div>';
				OpenWindowNot(msj4+div);
			}
		}
		else{
			// para consulta simple
			if( vCons == 'false' ){
				p1 = '<p class="text_size1">El o la estudiante que intenta buscar no se encuentra <b>registrado(a)</b>. Por favor, asegúrese de haber escrito correctamente la cédula escolar o documento de identidad.</p>';
				div = '<div class="msj_info">'+p1+'</div>';
				OpenWindowNot(msj4+div);
			}
		}
	}
	if( vError = getVariable('error') ){
		if( vError == 'cedula' ){ // cedula ya existe
			OpenWindowNot(msj3+p1); 
		}
		else if( vError == 'cedulaEsc'){ 
			OpenWindowNot(msj3+p2); // cedula escolar ya existe
		}
		else if( vError =='cedRep' ){
			p1 = '<p> Asegúrese de escribir correctamente la cédula del representante.</p>';
			p2 = '<p> Si desea registrar el representante haga <a href="?Representante=registrar" class="text_bold text_azul" target="_blank">click aquí</a> e inténtelo nuevamente.</p>';
			div = '<div class="msj_error">'+p1+p2+'</div>';
			OpenWindowNot(msj4+div);
		}
	}
	// Inscripción
	if( vInsc = getVariable('insc') ){
		if(vInsc == 'false'){
			p = '<p class="msj_error">No hay cupos disponibles</p>';
			OpenWindowNot(msj3+p);
		}else{
			OpenWindowNot(msj2);
		}
		if( vInsc == 'cancelada'){
			OpenWindowNot(msj5);
		}
		mostrar_formulario(1,document.getElementById('item-menu2'));
	}
	// vistas
	if( vista =  getVariable('vista') ){
		switch(vista){
			case '2':
				mostrar_formulario(1,document.getElementById('item-menu2'));	
				break;
			case '3':
				mostrar_formulario(2,document.getElementById('item-menu3'));	
				break;
			case '4':
				mostrar_formulario(4,document.getElementById('item-menu4'));	
				break;
		}
	}
}
// FORMULARIO CONSULTAR ESTUDIANTE
function teclaEnterBuscar(event){
	if( PressEnter(event) ){
		if( document.getElementById('buscar').value.trim() != '' ) {
			consultar();
		}
	}
	return solo_numeros(event);
}
function consultar(){ // pasa cedula a la url
	tipo_doc = document.getElementById('tipo_doc').value;
	cedula = document.getElementById('buscar').value.trim();
	if( cedula != '' ){
		if( vmodo =  getVariable('modo') ){
			switch(vmodo){
				case 'nuevo':
					window.location.href = 'index.php?Estudiante=visualizar&cedEscolar='+tipo_doc+'-'+cedula+'&modo=nuevo';
					break;

				case 'regular':
					window.location.href = 'index.php?Estudiante=visualizar&cedEscolar='+tipo_doc+'-'+cedula+'&modo=regular';
					break;

				default:
					window.location.href = 'index.php?Estudiante=visualizar&cedEscolar='+tipo_doc+'-'+cedula;
					break;
			}
		}
		else{
			window.location.href = 'index.php?Estudiante=visualizar&cedEscolar='+tipo_doc+'-'+cedula;
		}
	}
}

// >>>>>>>>>> FORMULARIO ESTUDIANTE

// FUNCIONES EN LOS BOTONES DEL FORMULARIO ESTUDIANTE
 function enviarRegistrar(){
 	fEst.opeEst.value = 'reg';
 	validar_formulario()
 }
 function enviarModificar(){
 	fEst.opeEst.value = 'mod';
 	validar_formulario();
 }
function validar_formulario(){ // valida el formulario antes de enviar
 	submit = true;

 	if( !validar_cedMadre() && fEst.cod_madre.value == '' ){
 		submit = false;
 		window.scrollTo(0, 150);
 	}
 	else if( !validar_cedPadre() && fEst.cod_padre.value == '' ){
 		submit = false;
 		window.scrollTo(0, 150);
 	}
 	else if( !validar_cedEst() ){
 		submit = false;
 		window.scrollTo(0, 350);
 	}
 	else if( !validar_nom1() ){
 		submit = false;
 		window.scrollTo(0, 450);
 	}
 	else if( !validar_nom2() ){
 		submit = false;
 		window.scrollTo(0, 450);
 	}
 	else if( !validar_ape1() ){
 		submit = false;
 		window.scrollTo(0, 550);
 	}
 	else if( !validar_ape2() ){
 		submit = false;
 		window.scrollTo(0, 550);
 	}
 	else if( !validar_genero() ){
 		submit = false;
 		window.scrollTo(0, 650);
 	}
 	else if( !validar_fechaNac() ){
 		submit = false;
 		window.scrollTo(0, 650);
 	}
 	else if( !validar_tlfn() ){
 		submit = false;
 		window.scrollTo(0, 850);
 	}
 	else if( !validar_cedEsc() ){
 		submit = false;
 		window.scrollTo(0, 850);
 	}
 	else if( !validar_lugarNac() ){
 		submit = false;
 		window.scrollTo(0, 1200);
 	}
 	else if( !validar_dirDomicilio() ){
 		submit = false;
 		window.scrollTo(0, 1450);
 	}
 	else if( !validar_datos_Soc() ){
 		submit = false;
 	}
 	if( submit == true ){
 		enviar();
 	}
}
function enviar(){ // envia el formulario
	//activa para evitar errores en el controlador
	fEst.cedEsc.disabled = false;
	fEst.submit();
}
// AJAX 
function existencia_cedula(){
	control = '../CONTROL/c_estudiante.php';
	objCed = ajax_newObj();
	codPer = fEst.codPer.value; // codigo de persona del estudiante
	cedula = ''; // cedula / cedula escolar
	tipo = 0; // cedula normal o escolar
	modo = fEst.modoINSC.value;
	buscar = false;

	if( fEst.ced_est.value.trim().length >= 8 ){
		// cedula toma valor de la cedula escolar, si no posee cedula
		cedula = fEst.tdoc_est.value+'-'+fEst.ced_est.value.trim();
		tipo = 1;
		buscar = true;
	}
	else if( fEst.cedEsc.value.length >= 12){
		// cedula toma valor de la cedula del estudiante si posee
		cedula = fEst.cedEsc.value;
		tipo = 2;
		buscar = true;
	}
	if( buscar == true && cedula.length > 0 ){
		cargar_ajax(objCed,respuesta_existencia_cedula,'POST',control,'existencia_cedula&codPer='+codPer+'&cedula='+cedula+'&modo='+modo+'&tipo='+tipo);
	}
}
function respuesta_existencia_cedula(){
	if( objCed.readyState == complete && objCed.status == 200 ){
		resp = objCed.responseText;
		//alert(resp);
		switch(resp){
			case '1':
				msj = '¡La <b>cédula o cédula escolar</b> ya existe! <i class="icon-cancel-circled2"></i>';
				p1 = '<p> Asegúrese de escribir correctamente la <b>cédula</b> e inténtelo nuevamente.</p>';
				div = '<div class="msj_error">'+p1+'</div>';
				OpenWindowNot(msj+div);
				break;
		}
	}
}	

//>>> Representantes:
function buscar_representante(){
	ocultar_msj(msjBox);
	objR = ajax_newObj();
	control = '../CONTROL/c_estudiante.php';
	tipo_doc = ''; // tipo documento a buscar
	ced_b = ''; // cedula a buscar
	ecodH = ''; // codigo de la persona (hidden)
	etdoc_sel = ''; // elementos a seleccionar
	eced_sel = ''; // elementos a seleccionar
	enom_sel = '';
	ejecutar = false;

	if( this.name == 'ced_madre' || this.name == 'tdoc_madre' ){
		if( fEst.ced_madre.value.trim() == '' ){
			fEst.nom_madre.value = '';
			fEst.cod_madre.value = '';
			fEst.cedEsc.value = fEst.tdoc_madre.value+'-'+fEst.ordenNac.value;
		}
		if( validar_cedula(fEst.ced_madre,0) ){
			tipo_doc = fEst.tdoc_madre.value;
			ced_b = fEst.ced_madre.value;
			ecodH = fEst.cod_madre;
			etdoc_sel = fEst.tdoc_madre;
			eced_sel = fEst.ced_madre;
			enom_sel = fEst.nom_madre;
			if( fEst.ced_madre.value.trim() != '' ){
				ejecutar = true;
			}
		}
	}
	else if( this.name == 'ced_padre' || this.name == 'tdoc_padre' ){
		if( fEst.ced_padre.value.trim() == '' ){
			fEst.nom_padre.value = '';
			fEst.cod_padre.value = '';
		}
		if( validar_cedula(fEst.ced_padre,1) ){
			tipo_doc = fEst.tdoc_padre.value;
			ced_b = fEst.ced_padre.value;
			ecodH = fEst.cod_padre;
			etdoc_sel = fEst.tdoc_padre;
			eced_sel = fEst.ced_padre;
			enom_sel = fEst.nom_padre;
			if( fEst.ced_padre.value.trim() != '' ){
				ejecutar = true;
			}	
		}
	}
	else if( this.name == 'ced_rep' || this.name == 'tdoc_rep' ){
		if( fInsc.ced_rep.value.trim() == '' ){
			fInsc.nom_rep.value = '';
			fInsc.cod_rep.value = '';
		}
		if( validar_cedula(fInsc.ced_rep,18) ){
			tipo_doc = fInsc.tdoc_rep.value;
			ced_b = fInsc.ced_rep.value;
			ecodH = fInsc.Rep;
			etdoc_sel = fInsc.tdoc_rep;
			eced_sel = fInsc.ced_rep;
			enom_sel = fInsc.nom_rep;
			if( fInsc.ced_rep.value.trim() != '' ){
				ejecutar = true;
			}	
		}
	}

	if( ejecutar == true && this.value.trim().length > 0 ){
		cargar_ajax(objR,imp_datos,'POST',control,'buscar_representante&tipo_doc='+tipo_doc+'&ced_rep='+ced_b); // envia XMLHttpRequest al servidor		
	}

	function imp_datos(){
		if( objR.readyState == complete && objR.status == 200 ){
			if( objR.responseText != 'false' ){ // existen dato
				arr = objR.responseText.split('%');
				ecodH.value = arr[0];
				etdoc_sel.value = arr[1];
				eced_sel.value = arr[2];
				enom_sel.value = arr[3];

				if( eced_sel.name != 'ced_rep' ){ 
					// evento ha sido ejecuta por el formulario estudiante y no inscripcion
					// para evitar recrear la cedula escolar desde el formulario inscripcion
					crearCE();
				}
			}
			else{
				if( getVariable('modo') == 'nuevo' ){
					msj = '¡No se encontraron resultados! <i class="icon-cancel-circled2"></i>';
					p1 = '<p> Asegúrese de escribir correctamente la <b>cédula de identidad</b>.</p>';
					p2 = '<p> Si desea registrar el representante, proceda a registrar los datos de la persona en el formulario, regrese aquí e inténtelo nuevamente.</p>';
					div = '<div class="msj_error">'+p1+p2+'</div>';
					OpenWindowNot(msj+div);
					window.open('?Representante=registrar','_blank');
				}
				else{
					msj = '¡No se encontraron resultados! <i class="icon-cancel-circled2"></i>';
					p1 = '<p> Asegúrese de escribir correctamente la <b>cédula de identidad</b>.</p>';
					p2 = '<p> Si desea registrar el representante haga <a href="?Representante=registrar" class="text_bold text_azul" target="_blank">click aquí</a>, proceda a registrar los datos de la persona e inténtelo nuevamente.</p>';
					div = '<div class="msj_error">'+p1+p2+'</div>';
					OpenWindowNot(msj+div);
				}
				
				if( eced_sel.name == 'ced_madre' ){
					// evento ha sido ejecuta por el formulario estudiante y no inscripcion
					// para evitar borrar la cedula escolar en el formulario estudiante
					fEst.cedEsc.value = fEst.tdoc_madre.value+'-'+fEst.ordenNac.value;
				}

				enom_sel.value = '';
				eced_sel.value = '';
				ecodH.value = '';
			}
		}
	}
}

// FUNCIONES DE VALIDACION DE LOS CAMPOS
// Identidad del estudiante	
function agregar_ordenNacimiento(){
	string = fEst.cedEsc.value.trim().split("");
	string[2] = this.value;
	string = string.join("");
	fEst.cedEsc.value = string;
	existencia_cedula();
}

function crearCE(){ 
	// REALIZAR CON AJAX CONSULTA PARA VERIFICAR EXISTENCIA
	if( fEst.ced_madre.value.trim() != '' && fEst.fnac.value != '' ){
		fEst.cedEsc.value = fEst.tdoc_madre.value+'-'+fEst.ordenNac.value+fEst.fnac.value.substr(2,2)+fEst.ced_madre.value;
	}
	else{
		fEst.cedEsc.value = fEst.tdoc_madre.value+'-'+fEst.ordenNac.value;
	}
	existencia_cedula();
}

function calcularEdad(){ 
	//calcula la edad del estudiante dependiendo de la fecha actual del servidor y la fecha de nacimiento
	fechaActual = fEst.fechaActual.value;
	fechaNacimiento = fEst.fnac.value;
	if( fechaActual != '' && fechaNacimiento != '' ){
		edad = fechaActual.substr(0,4)-fechaNacimiento.substr(0,4); // edad en años
		mes = fechaActual.substr(5,2)-fechaNacimiento.substr(5,2); // diferencia de mes
		diaActual = fechaActual.substr(8,2);
		diaNacimiento = fechaNacimiento.substr(8,2);
		if (mes < 0 || mes === 0 && diaActual < diaNacimiento){
	        edad--;
	    }
	  	fEst.edad.value = edad;
	}
	if( edad <= 0 ){
		fEst.edad.value = 0;
	}
}
function validar_edad(){ // muestra mensaje cuando la edad del estudiante es menor a 5 años
	edad = parseInt(fEst.edad.value);
	if( edad < 6 ){
		p = '<p class="msj_aviso text_center">El estudiante debe ser mayor a 5 años<br/><b>¿Desea continuar con el registro?</b></p>';
		OpenWindowNot('<b>¡Atención!</b>'+p);
	}
}
function calcularEdad_y_validar(){
	ocultar_msj(msjBox);
	calcularEdad();
	validar_edad();
	crearCE();
}
function validar_foto(){
	if( fEst.foto.value == '' ){
		return true;
	}
	else{
		if( validar_fotoImg(fEst.foto) ){
			preview = document.getElementById('foto_previa');
			previsualizar(fEst.foto, preview);
			return true;
		}else{
			fEst.foto.value = '';
			return false;
		}
	}
}
function validar_cedMadre(){ 
	ocultar_msj(msjBox);
	if( validar_cedula(fEst.ced_madre,0,'si') ){
		return true;
	}
}
function validar_cedPadre(){ 
	ocultar_msj(msjBox);
	if( validar_cedula(fEst.ced_padre,1) ){
		return true;
	}
}
function validar_cedRep(){ 
	ocultar_msj(msjBox);
	if( validar_cedula(fEst.ced_rep,2,'si') ){
		return true;
	}
}
function validar_parentesco(){ // Parentesco
	ocultar_msj(msjBox);
	if( validar_comboSelect(fEst.parent_r,3,'si') ){
		return true;
	}
}
function validar_cedEst(){ // cedula del estudiante
	// campo no es obligatorio
	ocultar_msj(msjBox);
	if( fEst.ced_est.value.trim() != '' ){
		if(fEst.ced_est.value.trim().length < 8){
			msjBox[2].innerHTML = icon_attention+'La cédula debe tener al menos 8 caractéres';
			mostrar_msj(msjBox[2]);
		}
		else{
			return true;
		}
	}
	else{
		return true;
	}
}
function validar_cedEsc(){ // cedula escolar
	ocultar_msj(msjBox);
	if(fEst.cedEsc.value.trim().length > 3 ){
		return true;
	}else{
		msjBox[12].innerHTML = icon_attention+'El campo es requerido';
		mostrar_msj(msjBox[12]);
	}
}
function validar_nom1(){ // valida el campo nombre1
	if( validar_nombre(fEst.nom1,3,'si') ){
		return true;
	}
}
function validar_nom2(){ // valida el campo nombre2
	if( validar_nombre(fEst.nom2,4,'no')){
		return true;
	}
}
function validar_ape1(){ // valida el campo appellido 1
	if( validar_apellido(fEst.ape1,5,'si') ){
		return true;
	}
}
function validar_ape2(){ // valida el campo appellido 2
	if( validar_apellido(fEst.ape2,6,'no') ){
		return true;
	}
}
function validar_genero(){ // valida el campo genero
	if( validar_comboSelect(fEst.sex,7,'si') ){
		return true;
	}
}
function validar_fechaNac(){ // valida el campo fecha de nacimiento
	ocultar_msj(msjBox);
	if( fEst.fnac.value == '' ){
		msjBox[8].innerHTML = icon_attention+'El campo es requerido';
		mostrar_msj(msjBox[8]);
	}else{
		return true;
	}
}
function validar_tlfn(){ 
	ocultar_msj(msjBox);
	if( validar_telefono(fEst.tlfn,9,'no') ){
		return true;
	}
}
// lugar de nacimiento
function validar_lugarNac(){ // valida lugar de nacimiento
	document.getElementById('msjErrorLugarNac').classList.add('none');
	if( fEst.edoNac.value == "0" || fEst.munNac.value == "0" || fEst.parrNac.value == "0" || fEst.lugarNac.value.trim() == '' ){ // pais direrente a venezuela
		document.getElementById('msjErrorLugarNac').classList.remove('none');
	}
	else{
		return true;
	}
}
function validar_dirDomicilio(){ // valida direccion de domicilio
	document.getElementById('msjErrorDir').classList.add('none');
	if( fEst.munDom.value=="0"||fEst.parrDom.value=="0"||fEst.sector.value.trim()==''||fEst.calle.value.trim()==''||fEst.nroCasa.value.trim()==''){
		document.getElementById('msjErrorDir').classList.remove('none');	
	}
	else{
		return true;
	} 
}
function validar_datos_Soc(){
	submit = true;
	if( !validar_tipoVnda() ){
		submit = false;
		window.scrollTo(0, 2400);
	}
	else if( !validar_condVnda() ){
		submit = false;
		window.scrollTo(0, 2400);
	}
	else if( !validar_condInfraVnda() ){
		submit = false;
		window.scrollTo(0, 2400);
	}
	else if( !validar_numHabitaciones() ){
		submit = false;
		window.scrollTo(0, 2550);
	}
	else if( !validar_numPersonas() ){
		submit = false;
		window.scrollTo(0, 2550);
	}
	else if( !validar_numPersonasT() ){
		submit = false;
		window.scrollTo(0, 2550);
	}
	else if( !validar_numIngFamiliar() ){
		submit = false;
		window.scrollTo(0, 2650);
	}
	if( submit == true ){
		return true;
	}
}
// validacion de campos de datos socio-economicos
function validar_tipoVnda(){
	if( validar_comboSelect(fEst.tipoVnda,11,'si') ){
		return true;
	}
}
function validar_condVnda(){
	if( validar_comboSelect(fEst.condVnda,12,'si') ){
		return true;
	}
}
function validar_condInfraVnda(){
	if( validar_comboSelect(fEst.condInfraVnda,13,'si') ){
		return true;
	}
}
function validar_numHabitaciones(){
	ocultar_msj(msjBox);
	if( fEst.num_habitaciones.value.trim() == '' ){
		msjBox[14].innerHTML = icon_attention+'El campo es requerido';
		mostrar_msj(msjBox[14]);
	}else{
		return true;
	}
}
function validar_numPersonas(){
	if( fEst.num_personas.value.trim() == '' ){
		msjBox[15].innerHTML = icon_attention+'El campo es requerido';
		mostrar_msj(msjBox[15]);
	}else{
		return true;
	}
}
function validar_numPersonasT(){
	if( fEst.num_personasT.value.trim() == '' ){
		msjBox[16].innerHTML = icon_attention+'El campo es requerido';
		mostrar_msj(msjBox[16]);
	}else{
		return true;
	}
}
function validar_numIngFamiliar(){
	if( fEst.ing_familiar.value.trim() == '' ){
		msjBox[17].innerHTML = icon_attention+'El campo es requerido';
		mostrar_msj(msjBox[17]);
	}else{
		return true;
	}
}

// >>>>>>> ESTADOS MUNICIPIOS Y PARROQUIAS CON AJAX
var parentRecibe; // elemento padre que recibe

function buscaEdo(){ // Pais > Estado
	inputName = this.name;

	if( inputName == 'paisNac' ){
		parentRecibe = fEst.edoNac; // estado de lugar de nacimineto
		resetLugarNac();
	}

	if( this.value != '0' ){
		obj = ajax_newObj(); // crea el objeto Ajax
		cargar_ajax(obj,printEdo,'POST',cLugares,'listarEst&cod_pais='+this.value); // envia XMLHttpRequest al servidor	
	}
}
function buscaMun(){ // consulta los municipios de un estado y los agrega a un nodo padre
	inputName = this.name;
	if( inputName == 'edoNac' ){ // lugar de nacimiento
		parentRecibe = fEst.munNac;
		resetLugarNac('edo'); 
	}
	else{ // domicilio
		parentRecibe = fEst.munDom;
		resetDirDom();
	}
	obj = ajax_newObj(); // crea el objeto Ajax
	cargar_ajax(obj,printMun,'POST',cLugares,'listarMun&cod_edo='+this.value);
}
function buscaParr(){ // consulta las parroquias de un municipio y las agrega a un nodo padre
	inputName = this.name;

	if( inputName == 'munNac' ){ // domicilio 
		parentRecibe = fEst.parrNac; 
		resetLugarNac('mun');
	}
	else{
		parentRecibe = fEst.parrDom; 
		resetDirDom('mun');
	}
	obj = ajax_newObj(); // crea el objeto Ajax
	cargar_ajax(obj,printParr,'POST',cLugares,'listarParr&cod_mun='+this.value); // envia peticion
}
function printEdo(){
	if( obj.readyState == complete && obj.status == 200 ){
		parentRecibe.innerHTML = '<option value="0">SELECCIONAR</option>'+obj.responseText;
	}	
}
function printMun(){ // imprime los municipios en un select
	if( obj.readyState == complete && obj.status == 200 ){
		parentRecibe.innerHTML = '<option value="0">SELECCIONAR</option>'+obj.responseText;
	}
}
function printParr(){ // imprimie las parroquias en un select
	if( obj.readyState == complete && obj.status == 200 ){
		parentRecibe.innerHTML = '<option value="0">SELECCIONAR</option>'+obj.responseText;
	}
}
// otras funciones
// >>>>> LUGAR DE NACIMIENTO

function resetLugarNac(x='all'){ // resetea el los selects del lugar de nacimiento 
	s = '<option value="0">SELECCIONAR</option>';
	if(x=='all'){
		fEst.edoNac.innerHTML = s;
		fEst.munNac.innerHTML = s;
		fEst.parrNac.innerHTML = s;
	}
	else if(x=='edo'){
		fEst.munNac.innerHTML = s;
		fEst.parrNac.innerHTML = s;
	}
	else if(x=='mun'){
		fEst.parrNac.innerHTML = s;
	}
	
}
function resetDirDom(x='all'){
	s = '<option value="0">SELECCIONAR</option>';
	if(x=='all'){
		fEst.munDom.innerHTML = s;
		fEst.parrDom.innerHTML = s;
	}
	else if('mun'){
		fEst.parrDom.innerHTML = s;
	}
}

function agrega_punto(event){ // agrega un punto en un campo de texto
	valor = this.value.trim();
	if( this.name == 'estatura' ){
		pos = 1;
	}
	else if( this.name == 'peso' ){
		pos = 2;
	}
	if( valor.length == pos ){
		this.value = valor+'.'; // agrega el punto despues de la posicion
	}
	if( valor.length == pos && codigoTecla(event) == 8 ){ // presiona tecla borrar
		this.value = valor.substr(0,pos);
	}
}

function mostrar_formulario(i,item){ // muestra formulario oculto
	ocultar_formulario(); // oculta el resto
	// contenedor de formularios
	cajasForm = document.getElementsByClassName('caja_contForm');
	cajasForm[i].classList.remove('none'); // muestra
	item.setAttribute('class','selected'); // agrega estilo al item menu
}

function ocultar_formulario(){ // deselecciona el item, cambia la propiedad class
	document.getElementById('item-menu1').removeAttribute('class');
	document.getElementById('item-menu2').removeAttribute('class');
	document.getElementById('item-menu3').removeAttribute('class');
	document.getElementById('item-menu4').removeAttribute('class');
	cajasForm = document.getElementsByClassName('caja_contForm');
	//formulario = document.getElementsByTagName('form');
	for(i=0; i<cajasForm.length; i++){
		//alert(cajasForm[i].innerHTML);
		cajasForm[i].classList.add('none'); // cambia la clase (oculta)
	}
}

// DATOS DE SALUD
function agregar_enf(){
	inputH = fEst.enf_pd;
	enf_sel = fEst.enf_sel.value; // input select
	arrEnf = inputH.value;
	div = document.getElementById('div_enf');
	if( enf_sel != 0 ){
		separar = enf_sel.split('%');
		cod = separar[0]; // codigo de la enfermedad
		nom = separar[1];
		if( noRepeatItem(cod,inputH) ){
			inputH.value = inputH.value+','+cod; // una coma y el codigo
			div.innerHTML = div.innerHTML+'<div class="itemBox_inline" onclick="eliminar_enf('+cod+',this)">'+nom+'<i class="icon-cancel-circled2"></i></div>';
		}
	}
}
function eliminar_enf(cod,item){
	cod = cod.toString(); // importante!, sino... no funciona :v
	enf_pd = fEst.enf_pd.value;
	arrEnf = enf_pd.split(',');
	i = arrEnf.indexOf(cod);
	arrEnf.splice(i,1); // elimina el codigo del arreglo
	fEst.enf_pd.value = arrEnf;
	// remueve el item box
	div = document.getElementById('div_enf'); 
	div.removeChild(item);
}
function agregar_vacuna(){
	inputH = fEst.vacunas;
	vcna_sel = fEst.vcna_sel.value; // input select
	arrVcna = inputH.value;
	div = document.getElementById('div_vcna');
	if( vcna_sel != 0 ){
		separar = vcna_sel.split('%');
		cod = separar[0]; // codigo de la vacuna
		nom = separar[1]; // nombre de la vacuna
		if( noRepeatItem(cod,inputH) ){
			inputH.value = inputH.value+','+cod; // una coma y el codigo
			div.innerHTML = div.innerHTML+'<div class="itemBox_inline" onclick="eliminar_vacuna('+cod+',this)">'+nom+'<i class="icon-cancel-circled2"></i></div>';
		}
	}
}
function eliminar_vacuna(cod,item){
	cod = cod.toString(); // importante!, sino... no funciona :v
	vcna = fEst.vacunas.value;
	arrVcna = vcna.split(',');
	i = arrVcna.indexOf(cod);
	arrVcna.splice(i,1); // elimina el codigo del arreglo
	fEst.vacunas.value = arrVcna;
	// remueve el item box
	div = document.getElementById('div_vcna'); 
	div.removeChild(item);
}
function noRepeatItem(cod,inputH){ // verifica que no se agregue un item ya seleccionado
	inputH = inputH.value.substr(1);
	arrValue = inputH.split(','); // array con los valores
	i = arrValue.indexOf(cod);
	if( i == -1 ){ // no existe
		return true;
	}
}

//-------------------- Inscripcion Escolar ---------------------------------------------------
function cargarEventosIns(){
	if( fInsc = document.f_inscripcion ){
		fInsc.ced_rep.onkeypress = solo_numeros;
		fInsc.tdoc_rep.onchange = buscar_representante;
		fInsc.ced_rep.onchange = buscar_representante;
		fInsc.nom_rep.disabled = true;
		fInsc.parent_r.onchange = validar_parentesco;
		fInsc.traslado[0].onclick = desb_motivo; fInsc.traslado[1].onclick = desb_motivo;
		if( fInsc.btnRegInsc ){
			fInsc.btnRegInsc.onclick = registrar_inscripcion;	
		}
		if( fInsc.btnModInsc ){
			fInsc.btnModInsc.onclick = modificar_inscripcion;
		}
		if( fInsc.motivo.value.trim().length>0 ){
			fInsc.motivo.disabled = false;
			fInsc.traslado[0].checked = true;
		}
	} 
}
function registrar_inscripcion(){
	fInsc.ope_insc.value = 'reg';
	enviar_form_inscripcion();
}

function modificar_inscripcion(){
	fInsc.ope_insc.value = 'mod';
	confirmar_modificar_inscripcion();
}
function validar_form_inscripcion(){ // valida el formulario antes inscribir al estudiante
	error = document.getElementById('msjErrorInsc');
	error.classList.add('none');
	submit = false;
	if( fInsc.ced_rep.value.trim()==''||fInsc.parent_r.value=='0'||fInsc.seccion.value=="0"||fInsc.modo.value=="0"||fInsc.condicion.value=="0"||fInsc.fecha.value==''||fInsc.procedencia.value==''){
		error.classList.remove('none');
	}
	else if(fInsc.traslado[0].checked == true && fInsc.motivo.value ==''){
		error.classList.remove('none');
	}
	else{
		submit = true;
	}
	if( submit == true ){
		return true;
	}
}
function enviar_form_inscripcion(){
	if( validar_form_inscripcion() ){
		fInsc.cedEst.disabled = false;
		fInsc.seccion.disabled = false;
		fInsc.modo.disabled = false;
		fInsc.condicion.disabled = false;
		fInsc.fecha.disabled = false;
		fInsc.modo.disabled = false;
		fInsc.motivo.disabled = false;
		fInsc.submit();
	}
}
function confirmar_modificar_inscripcion(){
	p = '¿Desea modificar la inscripción?</p>';
	OpenWindowNot(p);
	document.getElementById('b1').onclick = enviar_form_inscripcion;
	document.getElementById('b1').innerHTML = '<p>Confirmar</p>';
	document.getElementById('b2').style.display = 'block';
	document.getElementById('b2').innerHTML = '<p>Cerrar</p>'
} 
function desb_motivo(){ // desbloquear motivo, si es incripción de cambio de escuela
	if(this.value == 'si' ){
		fInsc.motivo.disabled = false;
		document.getElementById('icon_motivo').classList.remove('none');
	}
	else{
		document.getElementById('icon_motivo').classList.add('none');
		fInsc.motivo.disabled = true;
		fInsc.motivo.value = '';
	}
}
function marcaProcedencia(elem){
	if(elem.checked == true){
		fInsc.procedencia.value = 'U.E.N.B "SAMUEL ROBINSON"';
	}else{
		fInsc.procedencia.value = '';
	}	
}


function desplegar_rendimiento(elem){
	padre = elem.parentNode;
	for(i=0;i<padre.childNodes.length;i++){
		if( padre.childNodes[i].className == 'dsp_hijo' ){
			hijo = padre.childNodes[i];
			
			if( hijo.style == 'max-height:1000px' ){
				hijo.classList.remove('v');
				hijo.style = 'max-height:0';	
			}

			else{
				hijo.style = 'max-height:1000px';
				hijo.classList.add('v');
			}
		}
	}
}
