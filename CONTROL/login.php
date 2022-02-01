<?php 
	session_start();

	if( isset($_SESSION['recPass']) ){
		# Si las sesiones de recuperacion de clave quedan abiertas
		unset($_SESSION['recPass']);
		unset($_SESSION['p1']);
		unset($_SESSION['r1']);
		unset($_SESSION['p2']);
		unset($_SESSION['r2']);
	}	 

	# INICIAR SESIÓN
	if( isset($_POST['user']) ){ 
		include_once('../MODELO/m_usuario.php');
		$objUsu = new cls_usuario();  
		$user = $_POST['d_ID'].$_POST['user']; 
		$pass = $_POST['pass'];

		$objUsu->set_usuario($user);
	
		# consulta el usuario
		if( $res = $objUsu->consultar_usuario() ){ 

			switch ($res['estatus']) {
				case 'A': # usuario activo
					if( PASSWORD_VERIFY( $pass, $res['clave'] ) ){ # verifica si la contraseña es correcta
						# se crean las variables de sesiones
						$_SESSION['vsn_codPer'] = $res['cod_per'];
						$_SESSION['vsn_user'] = $res['cod_usu']; 
						$_SESSION['vsn_nivel'] = $res['cod_nivel'];
						$_SESSION['vsn_nombre'] = $res['nom1'];
						$_SESSION['vsn_apellido'] = $res['ape1'];
						$_SESSION['vsn_foto'] = $res['foto'];
						$_SESSION['vsn_ultconex'] = $res['ult_conex'];
						$objUsu->ult_conexion(); # se guarda la ultima conexion
						$objUsu->eliminar_intentos();
						#print_r($_SESSION);
						
						if( $res['cod_nivel'] == 1 ){ # administrador central
							header('Location: ../VISTA/?inicio');
						}

						# diferentes usuarios: verifica si no han configurado su seguridad
						else if( substr($user, 1) == $pass || $res['preg1'] == '' || $res['preg2'] == '' ){ 
							header('Location: ../VISTA/?inicio&seg=1');
						}

						else{
							header('Location: ../VISTA/?inicio');
						}
					}
					else{
						# contraseña incorrecta, regresa a la vista
						if( $res['cod_nivel'] != 1 ){ # usuarios normales
							$objUsu->inscluir_intento_clv();

							$intentos = $objUsu->consultar_intentos_clv();


							if( $intentos == 4 ){
								# advierte
								header('Location: ../?error=password_adv&user='.$user);
							}
							else if( $intentos == 5 ){
								# bloquea al usuario
								$objUsu->bloquear();
								header('Location: ../?error=bloq&user='.$user);
							}
							else{
								# clave incorrecta
								header('Location: ../?error=password&user='.$user);
							}
						}
						else{
							# clave incorrecta
							header('Location: ../?error=password&user='.$user);
						}
					}
					break;
				
				case 'I': # usuario inactivo
					header('Location: ../?error=user&user='.$user);
					break;

				case 'B':
					header('Location: ../?error=bloq&user='.$user);
					break;
			}
		}
		
		else{
			# usuario incorreto, regresa a la vista
			header('Location: ../?error=user&user='.$user);
		}
	}
?>