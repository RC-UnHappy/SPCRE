<?php 
	# Seguridad >>>>>>>
	include_once('seguridadDinamica.php');
	$rsMetodo = consultarMetodos('Aulas De Clase');
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

	include_once('../MODELO/m_aula.php');
	$objAula = new cls_aula();

	function listar(){
		global $objAula;
		crea_lista( $objAula->listar_limit() );
	}

	function crea_lista($rs, $pagina=false){
		global $sI,$sM,$sC,$sE;

		if( count($rs)>0 ){ 
			$fila = 'fila1';
			$num = 1;
			
			for( $i=0; $i<count($rs); $i++ ){
				$cod = $rs[$i]['cod'];
				$nom = $rs[$i]['nom'];
				$rnom = str_replace(" ","_s_", $nom);
				$sta = '';

				switch($rs[$i]['sta']){ # estatus
					case 'O':
						$sta = '<div class="td_amarillo">Ocupada</div>';
						break;

					case 'D':
						$sta = '<div class="td_verde">Disponible</div>';
						break;
				}

				echo '<tr class="fila '.$fila.'">';
					echo '<td class="text_bold">'.$num.'</td>';
					echo '<td id="fila'.$cod.'">'.$nom.'</td>';
					echo '<td>'.$sta.'</td>';
					echo '<td align="center">';
					if( $sM == '1' ){
						echo '<div onclick=W_OpenMod("'.$cod.'") class="acciones"><i class="icon-edit"></i><div class="info">Modificar</div></div>';
					}
					if( $sE == '1' ){
						echo '<div onclick=W_eliminar("'.$cod.'") class="acciones"><i class="icon-trash-empty"></i><div class="info">Eliminar</div></div>';
					}
					if( $sM == '0' && $sE == '0' ){
						echo '---';
					}
					echo '</td>';
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
			echo '<tr><td colspan="4">Sin resultados</td></tr>';
		}
		if( $pagina == true ){
			echo '%1'; # imprime y se recoleta este dato con ajax
		}
	}

	if( isset($_POST['ope']) ){
		$cod = $_POST['cod'];
		$nom = $_POST['nom'];
		$objAula->set_datos($cod,$nom);
		switch ($_POST['ope']) {
			case 'mod': # modificar
				if( $objAula->modificar() ){
					header('location: ../VISTA/?ver=aulas&mod=true');
				}
				else{
					header('location: ../VISTA/?ver=aulas&error=nombre');
				}
				break;
			
			case 'add': # agregar
				if ( $objAula->agregar() ){ # registró?
					header('location: ../VISTA/?ver=aulas&add=true');	
				}else{
					header('location: ../VISTA/?ver=aulas&error=nombre');	
				}
				break;

			case 'elm':
				if( $objAula->eliminar() ){
					header('location: ../VISTA/?ver=aulas&elm=true');
				}else{
					header('location: ../VISTA/?ver=aulas&elm=false');
				}
		}
	}
	
	else if( isset($_POST['listar']) ){ # listar aulas
		$rs = $objAula->listar_limit($_POST['desde'],$_POST['mostrar']);
		echo $objAula->filas.'%'; // separador de cadenas de texto
		# Ej:  texto1%texto2%texto3
		crea_lista($rs);
	}

	else if( isset($_POST['filtrar'] ) ){ # filtrar aulas
		$objAula->set_filtro($_POST['filtro']);
		$rs = $objAula->filtrar($_POST['desde'],$_POST['mostrar']);
		echo $objAula->filas.'%'; 

		if( $objAula->filas > 0 && !$rs ){ 
			$rs = $objAula->filtrar(0, $_POST['mostrar']); # desde = 0;
			crea_lista($rs, true);
		}
		else{
			crea_lista($rs);
		}
	}

	function paginas($pag_actual=1, $limite=15){ # Numeracion de páginas
		global $objAula;
		$total = $objAula->filas; # Ej: 30 filas
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