<?php 
	# Seguridad >>>>>>>
	include_once('seguridadDinamica.php');
	$rsMetodo = consultarMetodos('Lapso');
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

	include_once('../MODELO/m_lapso.php');
	include_once('../MODELO/m_a_escolar.php');
	$objAesc = new cls_a_escolar();
	$objLapso = new cls_lapso();

	# año escolar vigente
	$codAesc = ''; # codigo
	$perAesc = ''; # periodo
	$estatusAesc = ''; #estatus del año escolar mostrando
	$resLapso = ''; # almacena arreglo cargado en memoria

	if( isset($_GET['consultar']) ){
		$objAesc->set_periodo($_GET['consultar']);
		if( $rs = $objAesc->consultar() ){
			
			$estatusAesc = $rs['estatus']; # estatus del año escolar mostrando
			$objLapso->set_codPeriodo($rs['cod_periodo']);
			$resLapso = $objLapso->listar();
			$perAesc = $_GET['consultar'];
			#crea_lista($rs); 
		}
	}
	else{
		if( $rs = $objAesc->ultimo_aesc() ){
			$codAesc = $rs['cod_periodo'];
			$perAesc = $rs['periodo'];
			$estatusAesc = $rs['estatus'];

			# consulta los lapsons del año escolar activo
			$objLapso->set_codPeriodo($codAesc); # codigo del año escolar activo
			$resLapso = $objLapso->listar(); # selecciona todos los registros de la tabla (lapso)
		}
	}


	function crea_lista(){ # recibe la consulta cargada en memoria, crea las filas y columnas
		global $sI,$sM,$sC,$sE;
		global $resLapso, $estatusAesc, $objLapso;
		$rs = $resLapso;
		if( !$rs ){ # no existe el año escolar
			echo '<tr><td colspan="5">No se encontraron resultados resultados</td></tr>';
		}
		else if( count($rs)>0 ){  # existen registros
			$fila = 'fila1';

			for( $i=0; $i<count($rs); $i++ ){
				$cod = $rs[$i]['cod']; 
				$lap = $rs[$i]['lap'];
				$fi = $objLapso->f_fecha($rs[$i]['fi']);
				$ff = $objLapso->f_fecha($rs[$i]['ff']);
				$sta = $rs[$i]['sta'];
				# configuracion de apertura de notas
				$apNotas = $rs[$i]['apertura_notas'];
				$crNotas = $rs[$i]['cierre_notas'];

				$nulo = '0000-00-00 00:00:00';
				$nulo2 = '0000-00-00 00:00:00 am';

				if( $apNotas == $nulo ){ 
					$apNotas = $nulo2;
				}else{
					$apNotas = date('Y-m-d h:i:s a', strtotime($apNotas)).'%';
				}
				if( $crNotas == $nulo ){ 
					$crNotas = $nulo2;
				}else{
					$crNotas = date('Y-m-d h:i:s a', strtotime($crNotas)).'%';
				}
				
				$siguiente = ''; # siguiente lapso a abrir
			
				switch ($rs[$i]['lap']) {
					case '1':
						$nLap = '1er LAPSO';
						break;
					case '2':
						$nLap = '2do LAPSO';
						break;
					case '3':
						$nLap = '3er LAPSO';
						break;
				}
				
				$eLap = str_replace(" ",'%', $nLap);
				$status = 'Abierto';
				$cls_bg_sta = 'td_verde';

				if( $rs[$i]['sta']=='N'){
					$status = 'No iniciado';
					$cls_bg_sta = 'td_amarillo';

					if( $rs[$i-1]['sta'] && $rs[$i-1]['sta'] == 'C' ){
						$siguiente = $cod;
					}
				}
				else if( $rs[$i]['sta']=='C' ){ # el lapso i está cerrado
					$status = 'Cerrado';
					$cls_bg_sta = 'td_rojo';
				}

				# filas y columnas
				echo '<tr class="'.$fila.'">';
					echo '<td>'.$nLap.'</td>';
					echo '<td>'.$fi.'</td>';
					echo '<td>'.$ff.'</td>';
					echo '<td width="1"><div class="'.$cls_bg_sta.'">'.$status.'</td>';
				
					if( $estatusAesc == 'A' ){ # año escolar actual activo, puede modificar
						# Esto que esta comentado es como originalmente debe ir
						// if( $sM == '1' ){ # seguridad/modificar
						// 	if( $sta == 'A' || $sta == 'N' ){
						// 		echo '<td align="center"><div class="acciones" onclick=editar('.$cod.',"'.$eLap.'","'.$fi.'","'.$ff.'")><i class="icon-edit"></i><div class="info">Modificar</div></div>';
						// 	}
						// 	else{
						// 		echo '<td>---</td>';
						// 	}

						// 	if( $sta == 'A' ){
						// 		$apNotasT = str_replace(' ', '%', $apNotas);
						// 		$crNotasT = str_replace(' ', '%', $crNotas); 
						// 		echo '<div onclick=configurar('.$cod.',"'.$apNotasT.'","'.$crNotasT.'") class="acciones"><i class="icon-cog"></i><div class="info">Configurar</div></div>';
						// 		echo '<div class="acciones" onclick=cerrar('.$cod.','.$lap.')>Cerrar <i class="icon-lock"></i></div>';
						// 	}

						// 	if($cod == $siguiente){
						// 		echo '<div class="acciones" onclick=abrir('.$cod.')>Abrir <i class="icon-lock-open"></i></div>';
						// 	}
						// 	echo '</td>';
						// }
						// else{
						// 	echo '<td align="center">---</td>';
						// }
						# esto sin comentar es para la prueba
						if( $sM == '1' ){ # seguridad/modificar
							echo '<td align="center"><div class="acciones" onclick=editar('.$cod.',"'.$eLap.'","'.$fi.'","'.$ff.'")><i class="icon-edit"></i><div class="info">Modificar</div></div>';
							$apNotasT = str_replace(' ', '%', $apNotas);
							$crNotasT = str_replace(' ', '%', $crNotas); 
							echo '<div onclick=configurar('.$cod.',"'.$apNotasT.'","'.$crNotasT.'") class="acciones"><i class="icon-cog"></i><div class="info">Configurar</div></div>';
							echo '<div class="acciones" onclick=cerrar('.$cod.','.$lap.')>Cerrar <i class="icon-lock"></i></div>';
							echo '<div class="acciones" onclick=abrir('.$cod.')>Abrir <i class="icon-lock-open"></i></div>';
							echo '</td>';
						}
						else{
							echo '<td align="center">---</td>';
						}
					}
					else{
						echo '<td align="center">---</td>';
					}
				echo '</tr>';

				if( $fila == 'fila1'){
					$fila = 'fila2';	
				}
				else{
					$fila = 'fila1';
				}
			}
		}
		else{ # tabla vacía
			echo '<tr><td colspan="5">Sin resultados</td></tr>';
		}
	}


	# OTRAS FUNCIONES 
	function ultimo_aesc(){ # consulta año activo
		global $objAesc;
		$rs = $objAesc->ultimo_aesc();
		return $rs; # devuelve arreglo
	}

	function codigo_aesc($periodo){ # devuelve el codigo de un año escolar
		global $objAesc;
		$objAesc->set_periodo($periodo);
		$rs = $objAesc->consultar();
		return $rs; # devuelve el codigo
	}

	function listar_aesc(){ # listar años escolares
		global $objAesc;
		$rs = $objAesc->listar();
		for( $i=0;$i<count($rs);$i++ ){
			echo '<option value="'.$rs[$i]['cod'].'" onclick="">'.$rs[$i]['periodo'].'</option>';
		}
	}

	if( isset($_POST['ope']) ){
		$cod = $_POST['cod'];

		switch ($_POST['ope']) {
			case 'mod':
				$fi = $_POST['f_ini'];
				$ff = $_POST['f_fin'];
				$objLapso->setFechas($cod,$fi,$ff);
				$objLapso->modificar();
				header('location: ../VISTA/?ver=lapsos&ope=mod');
				break;

			case 'abrir':
				$objLapso->set_estatus($cod,'A');
				$objLapso->abrir_lapso();
				header('location: ../VISTA/?ver=lapsos&ope=abrir');
				break;

			case 'conf':
				$objLapso->setCodigoLapso($cod);
				$objLapso->modificar_fecha_notas($_POST['fnotasD'],$_POST['hnotasD'],$_POST['mnotasD'],$_POST['tiemponotasD'],$_POST['fnotasH'],$_POST['hnotasH'],$_POST['mnotasH'],$_POST['tiemponotasH']);
				header('location: ../VISTA/?ver=lapsos&ope=mod');
				break;

			case 'cerrar':
				$objLapso->set_estatus($cod,'C');
				
				if( $objLapso->cerrar_lapso() ){
					header('location: ../VISTA/?ver=lapsos&ope=cerrar');
				}
				else{ # false
					header('location: ../VISTA/?ver=lapsos&error=cerrar');
				}
				break;
		}
	}
?>