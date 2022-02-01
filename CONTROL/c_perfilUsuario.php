<?php 
	include_once('../MODELO/m_personal.php');
	include_once('../MODELO/m_usuario.php');
	$objPersonal = new cls_personal();
	$objUsu = new cls_usuario();

	$codPer = ''; # hidden
	$ciPer = ''; # hidden
	$url_foto = '../IMG/avatar.jpg';
	$nom = '';
	$ape = '';
	$email = '';
	$tlfm = ''; 
	$tlff = '';
	# peguntas y respuestas de seguridad
	$p1 = ''; $p2 = '';
	$r1 = ''; $r2 = '';

	# consulta los datos de una persona y pasa valor a las variables
	function consultar($usuario){
		global $objPersonal, $objUsu;
		global $codPer,$ciPer,$url_foto,$nom,$ape,$email,$tlfm,$tlff,$p1,$r1,$p2,$r2; 
		
		$objUsu->set_usuario($usuario);
		$res = $objUsu->consultar_usuario();

		$objPersonal->set_codigoPersona($res['cod_per']);
		if( $res['foto'] != '' ){
			$url_foto = '../upload/'.$res['foto'];
		}
		$codPer = $res['cod_per'];
		$ciPer = $res['tipo_documento'].'-'.$res['cedula'];
		$nom = $res['nom1'];
		$ape = $res['ape1'];
		$email = $res['email'];
		$arrTlfns = $objPersonal->consultarTelefonos();
		# presguntas y respuestas de seguridad
		$p1 = $res['preg1']; $p2 = $res['preg2'];
		$r1 = $res['resp1']; $r2 = $res['resp2'];

		for ($i=0; $i<count($arrTlfns); $i++) {
			switch ($arrTlfns[$i]['tipo']) { # compara el tipo de telefono
				case 'F':
					$tlff = $arrTlfns[$i]['num'];
					break;
				case 'M':
					$tlfm = $arrTlfns[$i]['num'];
					break;
			}	
		} 
	}
	
	if( isset($_POST['ope']) ){
		switch ($_POST['ope']) {
			case 'modDatosPer': # El usuario modifica sus propios datos Personales
				# variables POST
				$codPer = $_POST['codPer'];
				$ciPer = $_POST['ciPer'];
				$nom = $_POST['nom'];
				$ape = $_POST['ape'];
				$email = $_POST['email'];
				$tlfm = $_POST['tlfm'];
				$tlff = $_POST['tlff'];
				$objPersonal->set_codigoPersona($codPer); # pasa el codigo de la persona a la clase pe
				$objPersonal->set_identidad( substr($ciPer,0,1), substr($ciPer, 2) ); # pasa el tipo de documento y la cédula de identidad
				$objPersonal->set_datosPersona( substr($ciPer, 2),$nom,'',$ape,'','','','',$email);
				$objPersonal->set_foto($_FILES['foto']);
				$objPersonal->modificar_miPerfil(); # modifica en la tabla personal
				$objPersonal->modificar_telefono($tlfm,'M');
				$objPersonal->modificar_telefono($tlff,'F');
				# actualiza variables sesiones
				session_start();
				$_SESSION['vsn_nombre'] = mb_strtoupper($nom);
				$_SESSION['vsn_apellido'] = mb_strtoupper($ape);
				$_SESSION['vsn_foto'] = $codPer.'.'.substr($_FILES['foto']['type'],6);;
				header('Location: ../VISTA/?Perfil&form=1&mod=true'); # regresa al perfil del usuario
				break;

			case 'changePass': # El usuario modifica la contraseña desde su perfil
				$x=substr($_POST['ciPer'],0,1).substr($_POST['ciPer'], 2);
				$objUsu->set_usuario($x);
				$objUsu->set_Pass($_POST['codPer'],$_POST['npass'] );
				$res = $objUsu->consultar_usuario();
			
				if( PASSWORD_VERIFY( $_POST['pass'], $res['clave'] ) ){ # verifica que la contraseña sea correcta
					$objUsu->cambiar_clave(); # cambia la clave
					header('Location: ../VISTA/?Perfil&form=2&modPass=true'); # modifica la clave
				}
				else{
					header('Location: ../VISTA/?Perfil&form=2&modPass=false'); # clave incorrecta
				}
				break;

			case 'pregSeg':
				# pasa valor a propiedades
				$x=substr($_POST['ciPer'],0,1).substr($_POST['ciPer'], 2);
				$objUsu->set_usuario($x);
				$objUsu->set_Pass( $_POST['codPer'], $_POST['pass'] );
				$res = $objUsu->consultar_usuario();


				if( PASSWORD_VERIFY( $_POST['pass'], $res['clave'] ) ){ # verifica que la contraseña sea correcta
					$objUsu->set_pregSeg($_POST['preg1'],$_POST['resp1'],$_POST['preg2'],$_POST['resp2']); 
					$objUsu->modificar_preguntasSeg(); # modifica las preguntas de seguridad
					echo 'si';
					header('Location: ../VISTA/?Perfil&form=3&modPregSeg=true'); # modifica la clave
				}
				else{
					echo 'no';
					header('Location: ../VISTA/?Perfil&form=3&modPregSeg=false'); # modifica la clave
				}				
				break;
		}
	}
?>