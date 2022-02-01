<?php  
include_once('../MODELO/m_a_escolar.php');
$objAesc = new cls_a_escolar();
$aesc_activo = false;

include_once('../MODELO/m_dias_habiles.php');
$objDias = new cls_dia_habil();

if( $rsAesc = $objAesc->aesc_actual() ) # Existe año escolar activo?
{
	$aesc_activo = true;
	$periodo = $rsAesc['periodo'];
	# Consulta los dias habiles del año escolar
	$objDias->set('',$rsAesc['cod_periodo']);
	$rsDias = $objDias->consultar_dias_activos();
	$arrDias = array();
	foreach ($rsDias as $key => $value) {
		array_push($arrDias, $value['cod_diahbl']); 
	}
	$DiasString = implode(',', $arrDias);
	
	function calendario() 
	{
		global $periodo, $arrDias;
		
		$meses = array(
			'mes' => array('Septiempre','Octubre','Noviembre','Diciembre','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto'),
			'numero' => array('09','10','11','12','01','02','03','04','05','06','07','08')
		);
		
		for ($mes=0; $mes < count($meses['mes']); $mes++) { 
			echo '<div class="calendario_mes_box">';
				echo '<div class="calendario_mes">';
					echo '<div class="nombre_mes">'.$meses['mes'][$mes].'</div>';	
					echo '<div class="dias_mes"><div class="cl_td th_dias">L</div><div class="cl_td th_dias">M</div><div class="cl_td th_dias">M</div><div class="cl_td th_dias">J</div><div class="cl_td th_dias">V</div><div class="cl_td th_dias">S</div><div class="cl_td th_dias">D</div>';	
						# Se calcula cuantos dias tiene el mes
						if( $mes < 4 ){ # los primeros cuatro meses del año (septiembre,octubre,noviembre,diciembre)
							$year = substr($periodo, 0, 4); 
							$totalDias = getMonthDays($meses['numero'][$mes],$year);
							$FirstDay = getFirstDay($meses['numero'][$mes],$year);
						}

						else{ 
							$year = substr($periodo, 5, 8); 
							$totalDias = getMonthDays($meses['numero'][$mes],$year);
							$FirstDay = getFirstDay($meses['numero'][$mes],$year);
						}

						$lock_bb = false; # bloquear bloques en blanco para que siga imprimiendo normal
						for ($i=1; $i <= $totalDias ; $i++){ # Ciclo en la cantidad de dias del mes
							$bb = 0; # cantidad de bloques en blanco antes del día "1" del mes # ej: si el dia 1 empieza un domingo "do" habran 6 bloques en blanco antes de que empiece a imprimir los dias				

							if( $lock_bb == false ){
								switch ($FirstDay) {
									case 'Mon':
										$bb = 0;
										break;

									case 'Tue':
										$bb = 1;
										break;

									case 'Wed':
										$bb = 2;
										break;

									case 'Thu':
										$bb = 3;
										break;

									case 'Fri':
										$bb = 4;
										break;

									case 'Sat':
										$bb = 5;
										break;

									case 'Sun':
										$bb = 6;
										break;
								}
								if( $bb > 0 ){
									for ($j=0; $j<$bb; $j++) { 
										echo '<div class="cl_td bb">&nbsp;</div>';
									}
								}
								$lock_bb = true;
							}

							$day = getDay($i,$meses['numero'][$mes],$year);

							# Atributos HTML
							$numDia = $i;
							if($i<10){
								$numDia = '0'.$i; # agrega un numero 0
							}
							$value = $year.'-'.$meses['numero'][$mes].'-'.$numDia;
							$class = 'cl_td dia_num';
							$event = 'onclick=operar(this)';

							if( in_array($value, $arrDias) ){
								$class = 'cl_td dia_num checked';
							}

							if($day == 'Sat' OR $day == 'Sun'){
								$class = 'cl_td sa_do';
								$event = '';
							}
							
							echo '<div id="'.$value.'" class="'.$class.'" '.$event.' >'.$i.'</div>';
							$bb = 0;
						} 
					echo '</div>';
				echo '</div>';
			echo '</div>';				
		}
	}

	function getMonthDays($Month, $Year)  # Devuelve el numero de días que tiene un mes
	{
		$fecha = $Year.$Month.'01';
		return date( 't', strtotime( $fecha ) );
	}

	function getFirstDay($Month, $Year) # Devuelve el día en el que empieza el día 1 del mes
	{
		setlocale(LC_TIME, 'C');
		$fecha = $Year.$Month.'01';
		return substr( date('D', strtotime($fecha)), 0,3 );
	}

	function getDay($Day, $Month, $Year){
		setlocale(LC_TIME, 'C');
		$fecha = $Year.'-'.$Month.'-'.$Day;
		return substr( date('D', strtotime($fecha)), 0,3 );
	}
}

# METHOD POST
if( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
	$arrDias = explode(',',$_POST['arrDias']);
	$objDias->set('',$rsAesc['cod_periodo']);
	$objDias->desactivar_dias();


	for ($i=0; $i<count($arrDias); $i++) { 
		$objDias->set($arrDias[$i],$rsAesc['cod_periodo']);
		if( $objDias->existe() ){
			# Modifica
			$objDias->activar_dia();
		}
		else{
			# Incluye
			$objDias->incluir();
		}
	}
	header('location: ../VISTA/?ver=dias_habiles&ope=true');	
}


?>
