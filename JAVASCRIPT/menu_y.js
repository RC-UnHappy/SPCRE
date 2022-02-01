// carga eventos click a los nodos padres
function cargar_eventosMenu(){
	parent = document.getElementsByClassName('parent_menu');
	for(i=0;i<parent.length;i++){
		parent[i].onclick = mostrar_subMenu;
	}
}
// muestra los nodos hijos
function mostrar_subMenu(){
	
	menu = document.getElementById('menu').clientWidth;
	if( menu > 100 ){ // anchura es mayor a 100
		cerrarSubmenus();
		for(i=0;i<this.childNodes.length;i++){
			clase = this.childNodes[i].className;
			if( clase == 'icon-right-open' ){
				this.childNodes[i].style.transform = 'rotate(90deg)'; // icono ">" rota 90 grados
			}
			if( clase == "child_menu"){
				this.childNodes[i].style = 'max-height:1000px;padding:10px;'; // sub-menu
			}
		}	
	}
	
	//this.style.color='#FFF';
}
// oculta los submenus
function cerrarSubmenus(){
	menu = document.getElementById('menu').clientWidth;
	if( menu > 100 ){ // anchure es mayor a 100
		parent = document.getElementsByClassName('parent_menu');
		child = document.getElementsByClassName('child_menu');

		for(i=0;i<parent.length;i++){
			for(j=0;j<parent[i].childNodes.length; j++){
				if( parent[i].childNodes[j].classList == 'icon-right-open' ){
					parent[i].childNodes[j].style.transform = 'rotate(0deg)';
				}
			}
		}
		for(i=0;i<child.length;i++){
			child[i].style = 'max-height:0;padding:0px 10px 0px 10px;';
		}
	}	
}
cargar_eventosMenu();
