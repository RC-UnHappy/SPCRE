<?php 
# el objetivo de éste archivo es evitar los agujeros del sistema através de la url.
# valida si un usuario de nivel x tiene acceso a un servicio y. De lo contrario manda error, a su vez extrae los metodos de la base datos las acciones (métodos) que tienen los usuarios en sobre el servicio
function consultarMetodos( $servicio ){
	if( !isset($_SESSION['vsn_nivel'])){
		session_start();
	}
	if( $_SESSION['vsn_nivel'] != 1 ){ # es diferente al administrador central
		include_once('../MODELO/m_seguridad.php');
		$objMetodo = new cls_metodo();
		
		if ( $rsMetodos = $objMetodo->consultar2($_SESSION['vsn_nivel'], $servicio) ){
			return $rsMetodos;
		}
		# No tiene autorización. 
		else{
			header('location: ../VISTA/?error=404');
		}
	}
}
?>