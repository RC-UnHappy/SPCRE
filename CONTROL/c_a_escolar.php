<?php 
	# Seguridad >>>>>>>
	include_once('seguridadDinamica.php');
	$rsMetodo = consultarMetodos('Año Escolar');
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

	include_once('../MODELO/m_a_escolar.php'); # modelo año escolar
	$obj = new cls_a_escolar();
	
	function listar(){ 
		global $obj;
		$rs = $obj->ultimos_6_aesc(); # selecciona todos los registros de la tabla
		crea_lista( $rs );	
	}

	function crea_lista( $rs ){ # recibe la consulta cargada en memoria, crea las filas y columnas
		global $sI,$sM,$sC,$sE;
		global $obj;
		if( count($rs)>0 ){  # existen registros
			$fila = 'fila1';
			$num = 1; 
			$icon = '';
			for( $i=0; $i<count($rs); $i++ ){
				# variables 
				$cod = $rs[$i]['cod'];
				$periodo = $rs[$i]['periodo'];
				$f_ini = $obj->f_fecha($rs[$i]['f_ini']);
				$f_fin = $obj->f_fecha($rs[$i]['f_fin']);
				$fcsis = $obj->f_fecha($rs[$i]['fcsis']);

				# botones con eventos para cambiar el estatus del año escolar
				$editar = '<div onclick=modificar('.$cod.',"'.$f_ini.'","'.$f_fin.'","'.$periodo.'") class="acciones"><i class="icon-edit"></i><div class="info">Modificar</div></div>';
				$cerrar = '<div onclick=cambiar_estatus('.$cod.',"'.$periodo.'","C") class="acciones">Cerrar <i class="icon-lock"></i></div>';
				$promover = '<div onclick=promover('.$cod.',"'.$periodo.'") class="acciones">Promover <i class="icon-logout"></i></div>';	
				$configurar = '<div onclick=configurar('.$cod.',"'.$periodo.'") class="acciones"><i class="icon-cog"></i><div class="info">Configurar</div></div>';

				switch( $rs[$i]['sta'] ){ # estatus
					case 'C':
						$estatus = 'Cerrado';
						$cls_bg_sta = 'td_rojo';
						$acciones = '<td align="center">---</td>';# boton para abrir el año escolar
						break;

					case 'A':
						$estatus = 'Activo';
						$cls_bg_sta = 'td_verde';
						if( $sM == '1' ){
							if( $rs[$i]['prom'] == 'N' ){
								$acciones = '<td align="center">'.$editar.$configurar.$promover.'</td>';#boton para cerrar el año escolar
							}
							else if( $rs[$i]['prom'] == 'S' ){
								$acciones = '<td align="center">'.$editar.$configurar.$cerrar.'</td>';#boton para cerrar el año escolar
							}
						}
						else{
							$acciones = '<td align="center">---</td>';
						}
						break;
				}

				# filas y columnas
				echo '<tr class="'.$fila.'">';
					echo '<td class="text_bold">'.$num.'</td>';
					echo '<td>'.$periodo.'</td>';
					echo '<td>'.$f_ini.'</td>';
					echo '<td>'.$f_fin.'</td>';
					echo '<td>'.$fcsis.'</td>';
					echo '<td><div class="'.$cls_bg_sta.'">'.$estatus.'</div></td>';
					echo $acciones;
				echo '</tr>';

				if( $fila == 'fila1'){
					$fila = 'fila2';	
				}
				else{
					$fila = 'fila1';
				}
				$num++;
			}
		}
		else{ # tabla vacía
			echo '<tr><td colspan="7">Sin resultados</td></tr>';
		}
	}

	if( isset($_POST['ope']) ){ 
		$obj->set_periodo($_POST['periodo'],$_POST['f_ini'],$_POST['f_fin']);

		switch ($_POST['ope']) {
			case 'add': # nuevo año escolar
				if( $obj->incluir() ){
					header('location: ../VISTA/?ver=a_escolar&ope=add&periodo='.$_POST['periodo']);
				}else{
					header('location: ../VISTA/?ver=a_escolar&error=1'); # aun existe un año escolar activo
				}
				break;

			case 'mod':
				$obj->modificar();
				header('location: ../VISTA/?ver=a_escolar&ope=mod&periodo='.$_POST['periodo']);
				break;

			case 'prom':
				$obj->set_codigo($_POST['cod']);
				if( $obj->promover() ){
					header('location: ../VISTA/?ver=a_escolar&ope=prom');
				}
				else{
					header('location: ../VISTA/?ver=a_escolar&error=3');
				}
				break;

			case 'conf':
				#$fd,$hd,$md,$td,$fh,$hh,$mh,$th
				$obj->set_codigo($_POST['cod']);
				#echo $_POST['hnotasD'].$_POST['tiemponotasD'].'--'.$_POST['hnotasH'].$_POST['tiemponotasH'].'<br>';
				$obj->modificar_fecha_nuevo_ingreso($_POST['fnuevoD'],$_POST['hnuevoD'],$_POST['mnuevoD'],$_POST['tiemponuevoD'],$_POST['fnuevoH'],$_POST['hnuevoH'],$_POST['mnuevoH'],$_POST['tiemponuevoH']);
				$obj->modificar_fecha_regular($_POST['fregularD'],$_POST['hregularD'],$_POST['mregularD'],$_POST['tiemporegularD'],$_POST['fregularH'],$_POST['hregularH'],$_POST['mregularH'],$_POST['tiemporegularH']);
				$obj->modificar_fecha_matricula_inicial($_POST['fmatriculaD'],$_POST['fmatriculaH']);
				header('location: ../VISTA/?ver=a_escolar&ope=mod');
				break;
		}	
	}	

	# Para cerrar el año escolar
	else if( isset($_POST['passSeg']) ){ 
		include_once('../MODELO/m_usuario.php');
		$objUsu = new cls_usuario();
		$objUsu->set_usuario( $_POST['usu'] );
		$rs = $objUsu->consultar_usuario(); # consulta el usuario

		if( $rs['cod_nivel'] <= 2 && password_verify($_POST['passSeg'],$rs['clave']) ){ # la clave es correcta
			$obj->set_codigo($_POST['codAEscolar']);
			if( $obj->cerrar_aesc() ){ # cierra el año escolar
				header('location: ../VISTA/?ver=a_escolar&ope=mod_'.$_POST['sta'].'&periodo='.$_POST['h_periodo']);
			}
		}
		else{
			header('location: ../VISTA/?ver=a_escolar&error=2');
		} 	
	}

	else if( isset($_POST['ajax']) ){
		$obj->set_codigo($_POST['codAesc']);
		$rs = $obj->consultar_configuracion();
		$nulo = '0000-00-00 00:00:00';
		$nulo2 = '0000-00-00 00:00:00 am';

		if( $rs['apertura_insc_n'] == $nulo ){ 
			echo $nulo2.'%';
		}else{
			echo date('Y-m-d h:i:s a', strtotime($rs['apertura_insc_n'])).'%';
		}
		if( $rs['cierre_insc_n'] == $nulo ){ 
			echo $nulo2.'%';
		}else{
			echo date('Y-m-d h:i:s a', strtotime($rs['cierre_insc_n'])).'%';
		}
		if( $rs['apertura_insc_r'] == $nulo ){
			echo $nulo2.'%';
		}else{
			echo date('Y-m-d h:i:s a', strtotime($rs['apertura_insc_r'])).'%';
		}
		if( $rs['cierre_insc_r'] == $nulo ){
			echo $nulo2.'%';
		}else{
			echo date('Y-m-d h:i:s a', strtotime($rs['cierre_insc_r'])).'%';
		}
		echo $rs['fmi_desde'].'%';
		echo $rs['fmi_hasta'];
	}

	# imprime año escolar siguiente
	function op_aesc_siguiente(){ 
		global $obj;

		if( $rs = $obj->ultimo_aesc() ){
			$ep = explode('-', $rs['periodo']); # transforma en arreglo ej: arr=[2018][2019]
			$ep[0]++; $ep[1]++;
			echo $ep[0].'-'.$ep[1];
		}

		else{
			$actual = date('Y');
			echo $actual.'-'.($actual+1);
		}
	}
?>