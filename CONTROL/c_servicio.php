<?php 
	include_once("../MODELO/m_seguridad.php");
	$objModulo = new cls_modulo();
	$objServicio = new cls_servicio();
	$modulo = "0";

	# >>> GET
	if( isset($_GET['modulo']) ){
		$modulo = $_GET['modulo'];
	}

	if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

		# solicitudes enviadas por método AJAX
		
		if( isset( $_POST['listar']) ){

			if($_POST['modulo'] != '0'){
				$objServicio->set_modulo($_POST['modulo']);	
				$rs = $objServicio->listar('si','no',$_POST['desde'],$_POST['mostrar']); 
			}
			else{
				$rs = $objServicio->listar('si','si',$_POST['desde'],$_POST['mostrar']); 
			}
			
			$filas = $objServicio->getFilas();
			echo $filas.'%'; 
			crea_lista($rs);
		}

		else if( isset($_POST['filtrar'] ) ){ 
			# Filtrar por nombre y por modulo
			$objServicio->set_modulo($_POST['modulo']);
			$objServicio->set_filtro($_POST['filtro']);

			if( $_POST['modulo'] != '0' ){ # Es diferente al valor "Todo"
				$rs = $objServicio->listar('si','no',$_POST['desde'],$_POST['mostrar'],'si');
			}
			else{
				$rs = $objServicio->listar('si','si',$_POST['desde'],$_POST['mostrar'],'si');
			}
			
			$filas = $objServicio->getFilas();
			echo $filas.'%'; 
			crea_lista($rs);
		}

		# Solicitudes normales

		else{ 	
			$objServicio->set($_POST['cod'],$_POST['nom'],$_POST['icono'],$_POST['modulo'],$_POST['estatus'],$_POST['link'],$_POST['mostrar_menu'],$_POST['pos']);

			switch ($_POST['ope']) {
				case 'add':
					if( $objServicio->incluir() ){
						header('location: ../VISTA/?ver=servicio&ope=1');
					}
					else{
						header('location: ../VISTA/?ver=servicio&error=1');
					}
					break;

				case 'mod':
					if( $objServicio->modificar() ){
						header('location: ../VISTA/?ver=servicio&ope=2');
					}
					else{
						header('location: ../VISTA/?ver=servicio&error=2');
					}
					break;

				case 'elm':
					if( $objServicio->eliminar() ){
						header('location: ../VISTA/?ver=servicio&ope=3');
					}
					else{
						header('location: ../VISTA/?ver=servicio&error=3');
					}
					break;
			}
		}	
	}

	function listar_servicios(){ # Consulta a la BD 
		global $objServicio;
		$rs = $objServicio->listar('si');
		crea_lista($rs);
	}
	
	function crea_lista($rs){
		# imprime la tabla con los resultados, recibe como parametro la consulta de la BD
		if( count($rs)>0 ){ 
			$fila = 'fila1';
			$num = 1;
			for( $i=0; $i<count($rs); $i++ ){

				switch($rs[$i]['sta']){ # estatus
					case 'I':
						$estatus = '<div class="td_rojo">Inhabilitado</div>';
						break;

					case 'A':
						$estatus = '<div class="td_verde">Habilitado</div>';
						break;
				}

				# VARIABLES
				$cod = $rs[$i]['cod'];
				$nom = $rs[$i]['nom'];
				$ico = $rs[$i]['ico'];
				$mdlo = $rs[$i]['mdlo'];
				$sta = $rs[$i]['sta'];
				$link = $rs[$i]['link'];
				$mostrar_menu = $rs[$i]['mostrar_menu'];
				$pos = $rs[$i]['pos'];

				$eveMod	= "W_OpenMod(".$cod.",'".$ico."',".$mdlo.",'".$sta."','".$link."','".$mostrar_menu."','".$pos."')";

				# CELDAS
				echo '<tr class="clsTr fila '.$fila.'">';
					echo '<td class="text_bold">'.$num.'</td>';
					echo '<td id="celSer'.$cod.'" class="text_left text_bold">'.$nom.'</td>';
					echo '<td><i class="icon-'.$ico.'"></i></td>';
					echo '<td class="text_left">'.$rs[$i]['nmdlo'].'</td>';
					echo '<td>'.$estatus.'</td>';
					echo '<td align="center"><div onclick="'.$eveMod.'" class="acciones"><i class="icon-edit"></i><div class="info">Modificar</div></div>
					<div onclick="W_eliminar('.$cod.')" class="acciones"><i class="icon-trash-empty"></i><div class="info">Eliminar</div></div></td>';
				echo '</tr>';
				
				# ESTILO DE FILAS
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
			echo '<tr><td colspan="6">Sin resultados</td></tr>';
		}
	}

	function paginas($pag_actual=1, $limite=15){ # Numeracion de páginas
		global $objServicio;
		$total = $objServicio->getFilas(); 
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

	function javascript($arg){
		echo '<script type="text/javascript">'.$arg.'</script>';
	}
?>