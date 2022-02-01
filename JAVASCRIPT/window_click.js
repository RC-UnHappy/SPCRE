window.onclick = function(event){
	// eventos que ocurren fuera el elemento a ocultar
	// MENU-Y: oculta los sub-menus
	if( !event.target.matches('.parent_menu') ){
		cerrarSubmenus(); // ver menu_y.js
	}

	// DROP_MENU
	if( event.target.matches('.parent_dropdown_menu') ){
		parent = event.target; // nodo padre
		child = parent.children; // hijos
		for(i=0;i<child.length;i++){
			if( child[i].classList.contains("dropdown_menu") ){ // si el elemento hijo contiene ésta clase
				dropMenu = child[i];
				dropMenu.classList.add('show'); // añade la clase show
			}
		}
	}

	else if( !event.target.matches('.parent_dropdown_menu') ){
		hide_dropdown_menu(); // en: funciones.js
	}
}	