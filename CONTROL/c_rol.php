<?php 
	include_once('../MODELO/m_rol.php');
	$obj = new cls_rol();

	$total = 0;

	function listar_roles(){
		global $obj, $total;	
		if( $rs = $obj->listar() ){
			$fila = 'fila1';
			$num = 1; 

			for ($i=0; $i<count($rs); $i++) {
				$cod = $rs[$i]['cod'];
				echo '<tr class="'.$fila.'">';
					echo '<td>'.$num.'</td>';
					echo '<td class="text_left text_bold" id="celNom'.$cod.'">'.$rs[$i]['nom'].'</td>';
					echo '<td class="text_left" id="celDesc'.$cod.'">'.$rs[$i]['desc'].'</td>';
					echo '<td align="center"><div onclick="modificar('.$cod.')" class="acciones"><i class="icon-edit"></i></div>';
				echo '<tr>';

				if( $fila == 'fila1'){
					$fila = 'fila2';	
				}
				else{
					$fila = 'fila1';
				}
				$total++;
				$num++;
			}
		}
		else{
			# sin resultados
			echo '<tr><td colspan="4">Sin resultados</td></tr>';
		}
	}

	if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

		$obj->set($_POST['cod'],$_POST['nom'],$_POST['desc']);
		switch ($_POST['ope']) {
			case 'add':
				if( $obj->incluir() ){
					header('location: ../VISTA/?ver=rol&ope=1');
				}
				else{
					header('location: ../VISTA/?ver=rol&error=1');
				}
				break;

			case 'mod':
				if( $obj->modificar() ){
					header('location: ../VISTA/?ver=rol&ope=2');
				}
				else{
					header('location: ../VISTA/?ver=rol&error=2');
				}
				break;
		}
	}
?>