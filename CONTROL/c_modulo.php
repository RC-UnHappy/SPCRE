<?php 
	include_once('../MODELO/m_seguridad.php');
	$obj = new cls_modulo();

	function listar_modulos(){ # al cargar la pagina se ejecuta esta función, ver: lista_enfermedades.php
		global $obj;
		$rs = $obj->listar();
		crea_lista($rs);
	}

	function crea_lista($rs,$pagina=false){
		# imprime la tabla con los resultados, recibe como parametro la consulta cargada en memoria.
		if( count($rs)>0 ){ 
			$fila = 'fila1';
			$num = 1;

			for( $i=0; $i<count($rs); $i++ ){
				$cod = $rs[$i]['cod'];
				$nom = $rs[$i]['nom'];
				$ico = $rs[$i]['ico'];
				$sta = $rs[$i]['sta'];
				$pos = $rs[$i]['pos'];
				$estatus = '';

				switch($sta){ # estatus
					case 'I':
						$estatus = '<div class="td_rojo">Inhabilitado</div>';
						break;

					case 'A':
						$estatus = '<div class="td_verde">Habilitado</div>';
						break;
				}

				$rnom = str_replace(" ","_s_",$nom);
				echo '<tr class="fila '.$fila.'">';
					echo '<td class="text_bold">'.$num.'</td>';
					echo '<td class="text_left text_bold">'.$nom.'</td>';
					echo '<td><i class="icon-'.$ico.'"></i></td>';
					echo '<td>'.$estatus.'</td>';
					echo '<td align="center"><div onclick=W_OpenMod("'.$cod.'","'.$rnom.'","'.$ico.'","'.$sta.'","'.$pos.'") class="acciones"><i class="icon-edit"></i><div class="info">Modificar</div></div>
					<div onclick=W_eliminar("'.$cod.'","'.$rnom.'","'.$ico.'","'.$sta.'","'.$pos.'") class="acciones"><i class="icon-trash-empty"></i><div class="info">Eliminar</div></div></td>';
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
			echo '<tr><td colspan="5">Sin resultados</td></tr>';
		}

		if( $pagina == true ){
			echo '%1';
		}
	}

	function paginas($pag_actual=1, $limite=15){ # Numeracion de páginas
		global $obj;
		$total = $obj->filas; 
		$paginas = ceil($total/$limite); # redondea hacia arriba
		$items = 5; # (botones)

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
		javascript("total_filas=parseInt(".$total.")"); # pasa valor a la variable de javascript
	}

	# >>>>>>>>>>>>>>>>>>>> POST
	if( isset($_POST['ope']) ){
		$HDR = 'location: ../VISTA/?ver=';
		$obj = new cls_modulo();
		$obj->set($_POST['cod'],$_POST['nom'],$_POST['icono'],$_POST['estatus'],$_POST['pos']);

		switch ($_POST['ope']) {
			case 'add':
				if( $obj->incluir() ){
					header($HDR.'modulo&ope=1');
				}
				else{
					header($HDR.'modulo&error=1'); # el nombre ya existe
				}
				break;

			case 'mod':
				if( $obj->modificar() ){
					header($HDR.'modulo&ope=2');
				}
				else{
					header($HDR.'modulo&error=2'); # el nombre ya existe
				}
				break;

			case 'elm':
				echo $_POST['cod'];
				$obj->eliminar();
				header($HDR.'modulo&ope=3');
				break;
		}
	}

	# solicitudes enviadas por método AJAX
	else if( isset($_POST['listar']) ){ 
		$rs = $obj->listar($_POST['desde'],$_POST['mostrar']);
		echo $obj->filas.'%'; 
		crea_lista($rs);
	}

	else if( isset($_POST['filtrar'] ) ){ 
		$obj->set_filtro($_POST['filtro']);
		$rs = $obj->filtrar($_POST['desde'],$_POST['mostrar']);
		echo $obj->filas.'%'; 

		if( $obj->filas > 0 && !$rs ){ 
			$rs = $obj->filtrar( 0, $_POST['mostrar']); # desde = 0;
			crea_lista($rs, true);
		}
		else{
			crea_lista($rs);
		}
	}

	function javascript($arg){
		echo '<script type="text/javascript">'.$arg.'</script>';
	}
?>