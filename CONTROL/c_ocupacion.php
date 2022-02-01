<?php
	# Seguridad >>>>>>>
	include_once('seguridadDinamica.php');
	$rsMetodo = consultarMetodos('Ocupación');
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
		
	include_once('../MODELO/m_ocupacion.php');
	$objOcup = new cls_ocupacion();

	function listar_ocup(){ # al cargar la pagina se ejecuta esta función, ver: lista_ocupaciones.php
		global $objOcup;
		$rs = $objOcup->listar_limit();
		crea_lista($rs); # muestra la tabla con las tuplas
	}

	function crea_lista($rs,$pagina=false){
		global $sI, $sM, $sC, $sE;
		# imprime la tabla con los resultados, recibe como parametro la consulta cargada en memoria.
		if( count($rs)>0 ){ 
			$fila = 'fila1';
			$num = 1;
			for( $i=0; $i<count($rs); $i++ ){
				$cod = $rs[$i]['cod'];
				$nom = $rs[$i]['nom'];
				$rnom = str_replace(" ","_s_", $nom);

				echo '<tr class="fila '.$fila.'">';
					echo '<td class="text_bold">'.$num.'</td>';
					echo '<td>'.$nom.'</td>';
					if( $sM == '1' ){
						echo '<td align="center"><div onclick=W_OpenMod("'.$cod.'","'.$rnom.'") class="acciones"><i class="icon-edit"></i><div class="info">Modificar</div></div></td>';
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
				$num++;
			}
		}
		else{
			echo '<tr><td colspan="3">Sin resultados</td></tr>';
		}
		if( $pagina == true ){
			echo '%1';
		}
	}

	function listarOpOcup($valor){ # imprime elementos options con las ocupaciones
		global $objOcup;
		$rs = $objOcup->listar();
		for($i=0;$i<count($rs);$i++){
			if( $valor ==  $rs[$i]['cod'] ){
				echo '<option value="'.$rs[$i]['cod'].'" selected>'.$rs[$i]['nom'].'</option>';
			}
			else{
				echo '<option value="'.$rs[$i]['cod'].'">'.$rs[$i]['nom'].'</option>';
			}
		}
	}

	# existe varible POST ope
	if( isset($_POST['ope']) ){
		$objOcup->set_datos( $_POST['cod'],$_POST['nom'] );
		switch ($_POST['ope']) {
			case 'mod':
				if( $objOcup->modificar() ){
					header('location: ../VISTA/?ver=ocupacion&mod=true');
				}else{
					header('location: ../VISTA/?ver=ocupacion&error=nombre');
				}
				break;
			
			case 'add': 
				if( $objOcup->agregar() ){ # agrega
					header('location: ../VISTA/?ver=ocupacion&add=true');
				}else{
					header('location: ../VISTA/?ver=ocupacion&error=nombre');
				} 
				break;
		}
	}
	
	# solicitudes enviadas por método AJAX
	else if( isset($_POST['listar']) ){ # listar ocupaciones
		$rs = $objOcup->listar_limit($_POST['desde'],$_POST['mostrar']);
		echo $objOcup->filas.'%'; 
		crea_lista($rs);
	}

	else if( isset($_POST['filtrar'] ) ){ # listar ocupaciones por filtro
		$objOcup->set_filtro($_POST['filtro']);
		$rs = $objOcup->filtrar($_POST['desde'],$_POST['mostrar']);
		echo $objOcup->filas.'%'; 

		if( $objOcup->filas > 0 && !$rs ){ 
			$rs = $objOcup->filtrar( 0, $_POST['mostrar']); # desde = 0;
			crea_lista($rs, true);
		}
		else{
			crea_lista($rs);
		}
	}

	function paginas($pag_actual=1, $limite=15){ # Numeracion de páginas
		global $objOcup;
		$total = $objOcup->filas; # Ej: 30 filas
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
?>