<?php
	# Seguridad >>>>>>>
	include_once('seguridadDinamica.php');
	$rsMetodo = consultarMetodos('Sección');
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

	include_once('../MODELO/m_seccion.php'); 
	include_once('../MODELO/m_personal.php');
	$objPersonal = new cls_personal();
	$obj = new cls_seccion();

	# año escolar vigente
	$codAesc = ''; # codigo
	$perAesc = ''; # periodo
	$estatusAesc = ''; #estatus del año escolar mostrando
	# -----------------------
	# resultados
	$resSec = ''; # almacena arreglo cargado en memoria

	if( isset($_GET['consultar']) ){
		include_once('../MODELO/m_a_escolar.php');
		# instancia a la clase año escolar
		$objAesc = new cls_a_escolar();
		$objAesc->set_periodo($_GET['consultar']);
		if( $rs = $objAesc->consultar() ){
			$codPeriodo = $rs['cod_periodo'];
			$estatusAesc = $rs['estatus']; # estatus del año escolar mostrando
			# instancia a la clase seccion
			$obj->set_periodo($codPeriodo);
			$resSec = $obj->listar();
			$perAesc = $_GET['consultar'];
			#crea_lista($rs); # imprime la lista de la secciones del año escolar en busqueda.
		}
	}
	else{
		if( $rsAesc = ultimo_aesc() ){
			$codAesc = $rsAesc['cod_periodo'];
			$perAesc = $rsAesc['periodo'];
			$estatusAesc = $rsAesc['estatus'];
		}
		# consulta las secciones del año escolar activo
		$obj->set_periodo($codAesc); # codigo del año escolar activo
		$resSec = $obj->listar(); # selecciona todos los registros de la tabla (Seccion)
	}

	if($_SESSION['vsn_nivel'] == 4){ # nivel docente
		header('location: ?ver=lista_matricula');
	}

	function crea_lista(){ # recibe la consulta cargada en memoria, crea las filas y columnas
		global $sI,$sM,$sC,$sE;
		global $resSec, $estatusAesc;
		$rs = $resSec;
		if( !$rs ){ # no existe el año escolar
			echo '<tr><td colspan="8">No se encontraron resultados</td></tr>';
		}
		else if( count($rs)>0 ){  # existen registros
			$fila = 'fila1';
			$num = 1; 

			for( $i=0; $i<count($rs); $i++ ){
				# variables 
				$cod = $rs[$i]['cod']; # codigo de la seccion
				$aesc = $rs[$i]['aesc']; 
				$gdo = $rs[$i]['gdo'];
				$lta = $rs[$i]['lta'];
				$aula = $rs[$i]['aula']; # codigo del aula
				$nAula = $rs[$i]['nom_aula'];
				$pnAula = str_replace(" ","%",$nAula);
				$doc = $rs[$i]['codDoc']; # codigo del docente
				$codDoc = $rs[$i]['codDoc'];
				$nomDoc = $rs[$i]['doc'];
				$cup = $rs[$i]['cup'];
				$cedDoc = $rs[$i]['cedDoc2'];

				$est = cantidad_estudiantes($cod); # cantidad de estudiantes inscritos, pasa de parametro el codigo de la seccion

				# filas y columnas
				echo '<tr class="'.$fila.'">';
					echo '<td class="text_bold">'.$num.'</td>';
					echo '<td>'.$gdo.'°</td>';
					echo '<td>'.$lta.'</td>';
					echo '<td>'.$est.'</td>';
					echo '<td><label id="docFila'.$codDoc.'" class="none">'.$cedDoc.' - '.$nomDoc.'</label>'.$nomDoc.'</td>';
					echo '<td>'.$nAula.'</td>';
					echo '<td>'.$cup.'</td>';
					if( $estatusAesc == 'A' ){ # año escolar actual activo, puede modificar
						echo '<td align="center">';
						if( $sC == '1' ){
							echo '<a href="?ver=lista_matricula&seccion='.$cod.'"><div class="acciones"><i class="icon-eye"></i><div class="info">Matrícula</div></div></a>';
						}
						if( $sM == '1' ){
							echo '<div class="acciones" onclick=editar('.$cod.','.$gdo.',"'.$lta.'",'.$aula.',"'.$pnAula.'",'.$codDoc.','.$cup.')><i class="icon-edit"></i><div class="info">Modificar</div></div>';
						}
						if( $sC == '0' && $sM == '0' ){
							echo '---';
						}
						echo '</td>';
					}
					else{
						echo '<td align="center">
						<a href="?ver=lista_matricula&seccion='.$cod.'"><div class="acciones"><i class="icon-eye"></i><div class="info">Matrícula</div></div></a>
						</td>';
					}
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
			echo '<tr><td colspan="8">No se encontraron resultados</td></tr>';
		}
	}

	# ----------  POST
	if( isset($_POST['ope']) ){ 
		$aesc = $_POST['codAesc']; # codigo del año escolar 
		$codDocOcup = $_POST['codDoc']; # codigo del docente que ocupa la seccion al modificar
		
		$codDoc = $_POST['docente']; 
		$codSec = $_POST['codSec']; 
		$gdo = $_POST['grado']; 
		$sec = $_POST['seccion'];	
		$codAula = $_POST['codAula']; # codigo del aula que ocupa la sección a modificar
		$aula = $_POST['aula']; 
		$cup = $_POST['cupos'];

		
		$obj->set_datos($codSec,$aesc,$gdo,$sec,$codAula,$aula,$codDoc,$cup);

		switch ($_POST['ope']){
			case 'add':
				if( $obj->buscar_docente_seccion() ){
					# el docente ya tiene seccion
					header('location: ../VISTA/?ver=seccion&error=1'); 
				}
				else if( $obj->buscar_seccion() ){
					# Ya existe el grado y la seccion
					header('location: ../VISTA/?ver=seccion&error=2');
				}
				else{
					if( $obj->incluir() ){ # incluye
						header('location: ../VISTA/?ver=seccion&ope=add');
					}
				}
				break;

			case 'mod':
				if( $rs = $obj->consultar2() ){
					if( $rs['cod_seccion'] == $codSec ){
						$obj->modificar(); 
						header('location: ../VISTA/?ver=seccion&ope=mod');
					}
					else{
						# error
						header('location: ../VISTA/?ver=seccion&error=1'); 
					}
				}
				else{
					$obj->modificar(); 
					header('location: ../VISTA/?ver=seccion&ope=mod');
				}
				break;			
		}
	}

	if( isset($_POST['Ajax']) ){ # peticion al servidor con Ajax
		include_once('../MODELO/m_a_escolar.php');
		# instancia a la clase año escolar
		$objAesc = new cls_a_escolar();
		$objAesc->set_periodo($_POST['periodoEsc']);
		if( $rs = $objAesc->consultar() ){
			$codPeriodo = $rs['cod_periodo'];
			$estatusAesc = $rs['estatus']; # estatus del año escolar mostrando
			# instancia a la clase seccion
			$obj->set_periodo($codPeriodo);
			$rs = $obj->listar();
			crea_lista($rs); # imprime la lista de la secciones del año escolar en busqueda.
		}
		else{
			echo 'false'; # no existe el año escolar
		}
	}

	# OTRAS FUNCIONES 
	function ultimo_aesc(){ # consulta año activo
		include_once('../MODELO/m_a_escolar.php');
		$obj = new cls_a_escolar();
		$rs = $obj->ultimo_aesc();
		return $rs; # devuelve arreglo
	}

	function codigo_aesc($periodo){ # devuelve el codigo de un año escolar
		include_once('../MODELO/m_a_escolar.php');
		$obj = new cls_a_escolar();
		$obj->set_periodo($periodo);
		$rs = $obj->consultar();
		return $rs; # devuelve el codigo
	}

	function listar_aesc(){ # listar años escolares
		include_once('../MODELO/m_a_escolar.php');
		$obj = new cls_a_escolar();
		$rs = $obj->listar();
		for( $i=0;$i<count($rs);$i++ ){
			echo '<option value="'.$rs[$i]['cod'].'" onclick="">'.$rs[$i]['periodo'].'</option>';
		}
	}

	function aulas_op(){ # consulta las aulas  disponibles e imprime combo select
		include_once('../MODELO/m_aula.php');
		$obj = new cls_aula();
		$rs = $obj->listar_disponibles();
		for ($i=0; $i<count($rs) ; $i++) { 
			echo '<option value="'.$rs[$i]['cod'].'">'.$rs[$i]['nom'].'</option>';
		}
	}

	// function buscar_docente($cedula){ # consulta docentes
	// 	include_once('../MODELO/m_personal.php');
	// 	$obj = new cls_personal();
	// 	$obj->set_usuario($cedula);
	// 	if ( $rs = $obj->buscar_docente() ){ # existen datos
	// 		return $rs['cod_per']; # devuelve el codigo de la persona
	// 	}
	// }

	function solo_seccionesOp($grado,$aesc){ # selecciona las secciones de un grado y del año escolar actual
		global $obj;
		$obj->set_periodo($aesc);
		$obj->set_grado($grado);
		$rs = $obj->listar_por_grado();
		for ($i=0; $i<count($rs);$i++) { 
			echo '<option value="'.$rs[$i]['cod'].'">'.$rs[$i]['lta'].'</option>';
		}
	}

	function cantidad_estudiantes( $seccion ){ # consulta la cantidad de estudiantes en la seccion
		include_once('../MODELO/m_inscripcion.php');
		$obj = new cls_inscripcion();
		$cantidad = $obj->cons_estudiantes_seccion($seccion);
		return $cantidad;
	}

	# consulta datos especificos de una seccion de la seccion por codigo
	function consultar_seccion($codigo){
		global $obj;
		$obj->set_codigo($codigo);
		$rs = $obj->consultar();
		return $rs;
	}

	function listar_docentesDisp(){ # docentes disponibles
		global $codAesc, $perAesc, $resSec;
		global $objPersonal;
		$rs = $objPersonal->listar_docentes();

		if( count($resSec) == 0){
			for ($i=0; $i<count($rs); $i++){
				echo '<option value="'.$rs[$i]['cod'].'">'.$rs[$i]['docente'].'</option>';
			}
		}
		else{
			for ($i=0; $i<count($rs); $i++){
				# El docente no esta ocupado
				if ( !$objPersonal->docente_ocupado($rs[$i]['cod'],$codAesc) ){
					echo '<option value="'.$rs[$i]['cod'].'">'.$rs[$i]['docente'].'</option>';
				}
			}
		}
	}
?>