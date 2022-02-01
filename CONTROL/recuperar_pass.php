<?php 
	session_start();
	if( isset($_POST['ope']) ){ # el campo no viene vacío
		include_once('../MODELO/m_usuario.php');
		$obj = new cls_usuario();

		switch ( $_POST['ope'] ) {
			# buscar el usuario 
			case 'buscarUsu': 
				$user = $_POST['doc_id'].$_POST['user']; # usuario (documento_id,cedula)
				$obj->set_usuario( $user );
				if( $rs = $obj->consultar_usuario() ){ # existen datos
					
					if( $rs['estatus'] == 'I' ){
						header('Location: ../?error=user&user='.$user);
					}

					else if( $rs['estatus'] == 'B' ){
						header('Location: ../?error=bloq&user='.$user);
					}

					else{
						if( $rs['preg1'] != '' ){
							# crea las sesiones
							switch ($rs['preg1']) {
								case '1':
									$_SESSION['p1'] = 'Segundo nombre de mi madre'; 
									break;
								case '2':
									$_SESSION['p1'] = 'Segundo nombre de mi padre';
									break;
								case '3':
									$_SESSION['p1'] = 'Color favorito';
									break;
								case '4':
									$_SESSION['p1'] = 'Lugar de Nacimiento';
									break;
								case '5':
									$_SESSION['p1'] = 'Nombre de mi primera mascota';
									break;
								case '6':
									$_SESSION['p1'] = 'Lugar de último viaje';
									break;
							}
							$_SESSION['recPass'] = true;
							$_SESSION['codPer'] = $rs['cod_per'];
							$_SESSION['r1'] = $rs['resp1']; 
							$_SESSION['p2'] = $rs['preg2']; 
							$_SESSION['r2'] = $rs['resp2'];
							header('location: ../?rcp_pass=2');
						}
						else{
							header('location: ../?error=1');
						}
					}
				}
				else{
					header('location: ../?error=user_rcp&user='.$user);
				}
				break;

			case 'pregSeg':
				if( $_SESSION['codPer'] ){
					$obj->set_codigoPersona( $_POST['codPer'] );
					if( $_POST['r1'] == $_SESSION['r1'] && $_POST['r2'] == $_SESSION['r2']){
						# las respuestas coinciden
						header('location: ../?rcp_pass=3');
					}
					else{
						header('location: ../?error=preg_rcp');
					}
				}else{
					header('location: ../');
				}
				break;

			case 'newPass':
				if( isset($_SESSION['codPer']) ){
					$obj->set_Pass($_SESSION['codPer'],$_POST['pass']);
					$obj->cambiar_clave(); # cambia la contraseña
					cerrar_sesiones();
					header('location: ../?rcp_pass=true');
				}else{
					header('location: ../');
				}
				break;
		}	
	}

	else{
		header('location: ../');
	}

	# permite cerrar sesionar al finalizar todas la operaciones
	function cerrar_sesiones(){
		unset($_SESSION['recPass']);
		unset($_SESSION['codPer']);
		unset($_SESSION['p1']);
		unset($_SESSION['r1']);
		unset($_SESSION['p2']);
		unset($_SESSION['r2']);
	}
?>