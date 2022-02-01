<?php 
	if( isset($_GET['cerrar']) ){ # desde la vista el usuario
		session_start();
		if(isset( $_SESSION['vsn_user'])){
			#cierra las sesiones
			unset($_SESSION['vsn_codPer']);
			unset($_SESSION['vsn_user']);
			unset($_SESSION['vsn_nivel']);
			unset($_SESSION['vsn_nombre']);
			unset($_SESSION['vsn_apellido']);
			unset($_SESSION['vsn_foto']);
			unset($_SESSION['vsn_ultconex']);
			# redirecciona a la homepage
			header('Location: ../index.php');
		}
	}

	# desconexión por tiempo agotado
	if( isset($_GET['tiempo']) ){
		session_start();
		if(isset( $_SESSION['vsn_user'])){
			#cierra las sesiones
			unset($_SESSION['vsn_codPer']);
			unset($_SESSION['vsn_user']);
			unset($_SESSION['vsn_nivel']);
			unset($_SESSION['vsn_nombre']);
			unset($_SESSION['vsn_apellido']);
			unset($_SESSION['vsn_foto']);
			unset($_SESSION['vsn_ultconex']);
			# redirecciona a la homepage
			header('Location: ../index.php?tiempo=1');
		}
	}

	function destruirSesion(){ # desde la homePage
		include_once('MODELO/m_usuario.php');	
		$obj = new cls_usuario();
		$obj->ult_conexion($_SESSION['vsn_user'], date('Y-m-d H:i:s') ); # se guarda la ultima conexion
		# cierra las sesiones
		unset($_SESSION['vsn_codPer']);
		unset($_SESSION['vsn_user']);
		unset($_SESSION['vsn_nivel']);
		unset($_SESSION['vsn_nombre']);
		unset($_SESSION['vsn_apellido']);
		unset($_SESSION['vsn_foto']);
		unset($_SESSION['vsn_ultconex']);
		# redirecciona a la homepage
		header('Location: index.php');
	}
?>