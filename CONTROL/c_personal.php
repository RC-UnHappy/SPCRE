<?php 
	# Seguridad >>>>>>>
	include_once('seguridadDinamica.php');

	# Servicio Datos del Personal
	if( isset($_GET['Personal']) ){
		$rsMetodo = consultarMetodos('Datos Del Personal');

		if( $_GET['Personal'] == 'registrar' ){
			if( $rsMetodo['inc'] == '0'){
				header('location: ../VISTA/?ver=error404');
			}
		}
	}

	# Servicio lista del personal
	else if( isset($_GET['ver']) && $_GET['ver'] == 'personal' || isset($_POST['listar']) || isset($_POST['filtrar'])){
		$rsMetodo = consultarMetodos('Lista Del Personal');
	} 

	$sI = '1';
	$sM = '1';
	$sC = '1';
	$sE = '1';

	# Es diferente al administrador central
	if( $_SESSION['vsn_nivel'] != 1 ){ 
		$sI = $rsMetodo['inc'];
		$sM = $rsMetodo['modf'];
		$sC = $rsMetodo['cons'];
		$sE = $rsMetodo['elm'];
	}
	# Seguridad <<<<<<

	include_once('../MODELO/m_personal.php'); 
	$objPersonal = new cls_personal();

	if( !isset($_SESSION['vsn_nivel'])){
		session_start();
	}

	# FUNCIONES ---------
	function listar_personal(){
		global $objPersonal;
		$rs = $objPersonal->listar_personal(); # se obtienen los registros
		crea_lista($rs);
	}

	function crea_lista($rs,$pagina=false){ # lista la tabla personal
		global $sI,$sM,$sC,$sE;
		if( count($rs) > 0){ # filas mayor a 0
			$fila = 'fila1';
			$num = 1;
			for($i=0; $i<count($rs); $i++){
				$ced = $rs[$i]['ced'];
				$nom_ape = str_replace('%', "'", $rs[$i]['nom_ape']);
				$nvl = $rs[$i]['nvl'];
				$car = $rs[$i]['car'];
				$fun = $rs[$i]['fun'];
				$sta = $rs[$i]['sta']; 
				
				$status = 'Activo';
				$cls_bg_sta = 'td_verde';
				if($sta=='I'){
					$status = 'Inactivo';
					$cls_bg_sta = 'td_rojo';
				}

				$nivel = 'PRIMARIA'; # Por ahora
						
				echo '<tr class="fila '.$fila.'">';
					echo '<td>'.$num.'</td>';
					echo '<td class="text_bold">'.$ced.'</td>';
					echo '<td>'.$nom_ape.'</td>';
					echo '<td>'.$nivel.'</td>';
					echo '<td>'.$car.'</td>';
					echo '<td>'.$fun.'</td>';
					echo '<td width="1"><div class="'.$cls_bg_sta.'">'.$status.'</td>';
					echo '<td align="center"><a href="?Personal=visualizar&cedula='.$ced.'"><div class="acciones"><i class="icon-edit"></i><div class="info">Modificar</div></div></a></td>';
				echo '</tr>';

				if( $fila == 'fila1'){
					$fila = 'fila2';	
				}
				else{
					$fila = 'fila1';
				}
				$num++;
			}
		}else{
			echo'<tr><td colspan="8">Sin resultados</td></tr>';
		}
		if( $pagina == true ){
			echo '%1';
		}
	}

	# VARIABLES
	$codPer = ''; # hidden
	$ciPer = ''; # hidden
	$tdoc = ''; 
	$ced = '';
	$nom = '';
	$nom2 = '';
	$ape = '';
	$ape2 = '';
	$sexo = '';
	$fnac = '';
	$edad = '0';
	$cargo = '';
	$funcion = '';
	$nivel = '';
	$email = '';
	$tlfm = '';
	$sta = '';
	#$tlff = '';

	# VALIDAR VARIABLES GET

	# REGISTRAR O MODIFICAR 
	if( isset($_POST['ope']) ){
		$codPer = $_POST['codPer']; # hidden
		$ciPer = $_POST['ciPer']; # hidden
		$tdoc = $_POST['tdoc'];
		$ced = $_POST['ced'];
		$nom = $_POST['nom'];
		$nom2 = $_POST['nom2'];
		$ape = $_POST['ape'];
		$ape2 = $_POST['ape2'];
		$sexo = $_POST['sexo'];
		$fnac = $_POST['fnac'];
		$cargo = $_POST['cargo'];
		$funcion = $_POST['funcion'];
		$nivel = $_POST['nivel'];
		$email = $_POST['email'];
		$tlfm = $_POST['tlfm'];
		$sta = $_POST['estatus'];
		# usuario
		$crear_usuario = $_POST['crear_usuario']; # si/no
		$nivel_usuario = $_POST['nivel_usuario'];

		switch ($_POST['ope']) {
			case 'reg':	
				# set_datosPersona($ced,$nom1,$nom2,$ape1,$ape2,$sex,$nac,$fnac,$ema)
				$objPersonal->set_datosPersonal($tdoc,$ced,$nom,$nom2,$ape,$ape2,$sexo,$fnac,$cargo,$funcion,$nivel,$email,$tlfm,$sta);

				
				if( $objPersonal->comprobar_personal() ){ # existe ya el personal?
					header('location: ../VISTA/?Personal=registrar&error=cedula'); # error
				}
				else{ # no existe
					if( $rs = $objPersonal->comprobar_representante() ){ # es representante
						$objPersonal->set_codigoPersona($rs['cod_per']);
						$objPersonal->set_datosPersona($rs['cedula'],$nom,$nom2,$ape,$ape2,$rs['sexo'],$rs['nacionalidad'],$fnac,$email);
						$objPersonal->modificar_persona(); # modifica en la tabla persona
						$objPersonal->modificar_telefono($tlfm,'M');
						# $objPersonal->modificar_telefono($tlff,'F');
						$objPersonal->registrar_personal(); # registra en la tabla personal
						if( $crear_usuario == 'si' ){	
							registrar_usuario($rs['cod_per'],$rs['tipo_documento'],$rs['cedula'],$nivel_usuario);
						}
					}
					else{ # no es representante (registra nueva persona)
						$objPersonal->registrar_personal();
						if( $crear_usuario == 'si' ){	
							$objPersonal->set_identidad($tdoc, $ced);
							if( $rs = $objPersonal->consultar_persona() ){
								registrar_usuario($rs['cod_per'],$rs['tipo_documento'],$rs['cedula'],$nivel_usuario);
							}
						}
					}
					header('location: ../VISTA/?ver=personal&reg=true');
				}
				break;
			
			case 'mod':
					$objPersonal->set_datosPersonal($tdoc,$ced,$nom,$nom2,$ape,$ape2,$sexo,$fnac,$cargo,$funcion,$nivel,$email,$tlfm,$sta);
					$objPersonal->set_codigoPersona($codPer); # pasa el codigo de la persona
					if( $objPersonal->modificar_personal() ){ # modificó?
						$objPersonal->modificar_telefono($tlfm,'M');
						header('location: ../VISTA/?Personal=visualizar&cedula='.$tdoc.'-'.$ced.'&mod=true');
					}
					else{
						header('location: ../VISTA/?Personal=visualizar&cedula='.substr($ciPer,0,1).'-'.substr($ciPer,2).'&error=cedula'); # error en la cedula, ya existe
					}
				break;
		}
	}

	# solicitudes enviadas por método AJAX
	if( isset($_POST['listar']) ){
		$rs = $objPersonal->listar_personal($_POST['desde'],$_POST['mostrar'],$_POST['cargo'],$_POST['estatus']);
		echo $objPersonal->filas.'%'; 
		crea_lista($rs);
	}

	else if( isset($_POST['filtrar']) ){
		$objPersonal->set_filtro($_POST['filtro']);
		$rs = $objPersonal->filtrar($_POST['desde'],$_POST['mostrar'],$_POST['cargo'],$_POST['estatus']);
		echo $objPersonal->filas.'%'; 

		if( $objPersonal->filas > 0 && !$rs ){ 
			$rs = $objPersonal->filtrar(0, $_POST['mostrar'],$_POST['estatus'],$_POST['cargo']); # desde = 0;
			crea_lista($rs, true);
		}
		else{
			crea_lista($rs);
		}
	}

	# existe variable GET
	if( isset($_GET['Personal']) ){
		if($_GET['Personal']=='visualizar'){
			if( isset($_GET['cedula']) ){
				$objPersonal->set_identidad( substr($_GET['cedula'],0,1) , substr($_GET['cedula'],2) );	
				if( $rs = $objPersonal->consultar_datosPersonales() ){ # existen datos
					$codPer = $rs['codPer']; # hidden
					$ciPer = $rs['tipo_documento'].'-'.$rs['cedula']; # hidden 
					$tdoc = $rs['tipo_documento'];
					$ced = $rs['cedula'];
					$nom = str_replace('%', "'", $rs['nom1']);
					$nom2 = str_replace('%', "'", $rs['nom2']);
					$ape = str_replace('%', "'", $rs['ape1']);
					$ape2 = str_replace('%', "'", $rs['ape2']);
					$sexo = $rs['sexo'];
					$fnac = '';
					$edad = '0';
					if( $rs['fecha_nac'] != '0000-00-00' ){
						$fnac = $rs['fecha_nac'];
						$edad = $objPersonal->calcular_edad($fnac);
					}
					$nivel = $rs['nivel'];
					$cargo = $rs['cargo'];
					$funcion = $rs['funcion'];
					$sta = $rs['estatus'];
					$email = $rs['email'];
					
					$tlfns = explode(",", $rs['tlfns']); # descompone el string en un array, separando los numeros telefonicos
					for( $i=0;$i<count($tlfns);$i++ ){
						$tipo = str_split($tlfns[$i]); # recoge el primer caracter del string, este caracter indica el tipo de telefono
						switch ($tipo[0]) {
							case 'M': # movil
								$tlfm = substr($tlfns[$i],1);
								break;		
							// case 'F': # fijo
							// 	$tlff = substr($tlfns[$i],1);
							// 	break;
						}
					}
				}
				else{
					header('location: ?Personal=consultar&error=1'); # no existen datos
				}	
			}else{
				header('location: ?Personal=consultar'); # no existe la cedula
			}
		}
	}

	
	function paginas($pag_actual=1, $limite=15){ # Numeracion de páginas
		global $objPersonal;
		$total = $objPersonal->filas; # Ej: 30 filas
		$paginas = ceil($total/$limite); # redondea hacia arriba
		$items = 5; # cantidad de items_pag a mostrar

		if( $paginas > 1 ){ # existe mas de una página
			# El total de paginas no supera la cantidad de items a mostrar
			if( $paginas < $items ){ 
				for($i=1; $i<=$paginas; $i++){ // se recorre el total de paginas
					if( $pag_actual == $i ){
						echo '<div onclick="mostrar_pag(this,'.$i.')" class="item_pag actual">'.$i.'</div>';
					}
					else{
						echo '<div onclick="mostrar_pag(this,'.$i.')" class="item_pag">'.$i.'</div>';
					}
				}
			}
			# los 5 numeros siguientes de la pagina actual superan o es igual el total de paginas
			else if( $pag_actual+$items >= $paginas ){ 
				# solo imprime hasta el total de paginas ej: total_pag = 9, total_pag-items = 4, imprime: [4,5,6,7,8,9] llegando al limite y evita imprimir mas items
				for($i=$paginas-$items; $i<=$paginas; $i++){
					if( $pag_actual == $i ){
						echo '<div onclick="mostrar_pag(this,'.$i.')" class="item_pag actual">'.$i.'</div>';
					}
					else{
						echo '<div onclick="mostrar_pag(this,'.$i.')" class="item_pag">'.$i.'</div>';
					}
				}
			}
			# Imprime las 5 paginas siguientes de la pagina actual: ej: pag_actual = 2 imprime [2,3,4,5,6,7]
			else{ 
				for($i=$pag_actual; $i<=$pag_actual+$items; $i++){
					if( $pag_actual == $i ){
						echo '<div onclick="mostrar_pag(this,'.$i.')" class="item_pag actual">'.$i.'</div>';
					}else{
						echo '<div onclick="mostrar_pag(this,'.$i.')" class="item_pag">'.$i.'</div>';
					}
				}
			}
			# Botón Siguiente
			if( $pag_actual != $paginas ){
				echo '<label class="pag_AS" onclick="pag_siguiente()">Siguiente<i class="icon-angle-right"></i></label>';
			}
		}
		javascript("total_filas=parseInt(".$total.")");
	}

	function javascript($arg){
		echo '<script type="text/javascript">'.$arg.'</script>';
	}

	function error404(){
		#header('location: ../VISTA/index.php?ver=error404');
	}
	
	function listar_cargos($sel=null){
		include_once('../MODELO/m_cargo.php');		
		$obj = new cls_cargo();
		$rs = $obj->consultar();
		for ($i=0; $i<count($rs); $i++){
			if($sel == $rs[$i]['cod']){
				echo '<option value="'.$rs[$i]['cod'].'" selected>'.$rs[$i]['nom'].'</option>';
			} 
			else{
				echo '<option value="'.$rs[$i]['cod'].'">'.$rs[$i]['nom'].'</option>';
			}
		}
	}

	function listar_funciones($sel){
		include_once('../MODELO/m_funcion.php');		
		$obj = new cls_funcion();
		$rs = $obj->listar();
		for ($i=0; $i<count($rs); $i++){
			if($sel == $rs[$i]['cod']){
				echo '<option value="'.$rs[$i]['cod'].'" selected>'.$rs[$i]['nom'].'</option>';
			} 
			else{
				echo '<option value="'.$rs[$i]['cod'].'">'.$rs[$i]['nom'].'</option>';
			}
		}
	}

	function listar_niveles_usuario(){
		include_once('../MODELO/m_rol.php');		
		$obj = new cls_rol();
		$obj->comboRoles();
	}

	function registrar_usuario($codper,$td,$ced,$nvl){
		include_once('../MODELO/m_usuario.php');		
		$obj = new cls_usuario();
		$usu = $td.$ced;
		$obj->set_datos_usuario($codper,$usu,$nvl,'A');
		$obj->registrar_usuario();
	}
?>