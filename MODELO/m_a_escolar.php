<?php 
	require_once('MyDB.php');

	class cls_a_escolar extends MyDB{
		# Propiedades de la clase 
		private $codigo, $periodo, $f_ini, $f_fin;

		function __construct(){
			parent::__construct();
			$this->codigo = null;
			$this->periodo = null;
			$this->f_ini = null;
			$this->f_fin = null;
		}

		function set_codigo($cod){
			$this->codigo = $cod;
		}

		function set_periodo($periodo,$fi=null,$ff=null){
			$this->periodo = $periodo;
			$this->f_ini = $fi;
			$this->f_fin = $ff;
		}

		function incluir(){ # Permite incluir un nuevo año escolar
			# AGREGAR TRANSACCIONES 
			if( !$this->aesc_actual() ){ # No existe un año activo, procede.
				$sql = "INSERT INTO periodo_escolar (periodo,fecha_ini,fecha_fin) VALUES ('$this->periodo','$this->f_ini','$this->f_fin')";
				$this->query($sql);
				$cod_periodo =$this->ultimo_id();

				# Registra para las configuraciones de fechas
				if( $this->f_affectrows() ){
					$sql = "INSERT INTO conf_periodo (cod_periodo) VALUES ($cod_periodo)";
					$this->query($sql);
				}

				# AGREGA LOS LAPSOS
				if( $this->f_affectrows() ){
					$sql1 = "INSERT INTO lapso (cod_periodo,lapso,fecha_ini,fecha_fin,estatus) VALUES ('$cod_periodo','1','','','A')";
					$sql2 = "INSERT INTO lapso (cod_periodo,lapso,fecha_ini,fecha_fin,estatus) VALUES ('$cod_periodo','2','','','N')";
					$sql3 = "INSERT INTO lapso (cod_periodo,lapso,fecha_ini,fecha_fin,estatus) VALUES ('$cod_periodo','3','','','N')";
					$this->query($sql1);
					$this->query($sql2);
					$this->query($sql3);
					if( $this->f_affectrows() ){
						return true;
					}
				}
			}			
		}

		function modificar(){
			$sql = "UPDATE periodo_escolar SET fecha_ini='$this->f_ini', fecha_fin='$this->f_fin' WHERE periodo = '$this->periodo'";
			#echo $sql;
			$this->query($sql);
		}

		# Cambia el estatus del año escolar a cerrado ( En construcción )
		function cerrar_aesc(){	
			$this->empezar_op();
			$op = false;

			$fecha_cierre = date('Y-m-d');
			$sql = "UPDATE periodo_escolar SET estatus = 'C', cierre_sis = '$fecha_cierre' WHERE cod_periodo = '$this->codigo'";
			$this->query($sql);

			if( $this->f_affectrows() ){ 
				# cambia el estatus de las aulas a "D" (disponibles)
				$sql = "UPDATE aula set estatus = 'D'"; 
				$this->query($sql);
				$op = true;
			}
			
			if( $op == true ){
				$this->finalizar_op();
				return true;
			}
			else{
				$this->deshacer_op();
			}
		}	

		function promover(){
			$sql = "SELECT * FROM lapso WHERE estatus ='C' AND cod_periodo='$this->codigo'";
			$rs = $this->query($sql);
			#echo mysqli_num_rows($rs);
		
			# los tres lapsos están cerrados
			if( mysqli_num_rows($rs) >= 3 ){
				
				$sql2 = "SELECT estudiante.cod_per, nota_final.cod_insc, grado, promovido FROM nota_final
				INNER JOIN inscripcion ON nota_final.cod_insc = inscripcion.cod_insc
				INNER JOIN seccion ON inscripcion.seccion = seccion.cod_seccion
				INNER JOIN periodo_escolar ON seccion.periodo_esc = periodo_escolar.cod_periodo
				INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per
				WHERE nota_final.promovido = 'S' AND seccion.grado='6' AND periodo_escolar.cod_periodo = '$this->codigo'";
				#echo $sql2;
				$rsP = $this->query($sql2);

				$lista = array(); $cont = 0;
				while( $fila = $this->f_array($rsP) ){
					$lista[$cont]['cod_per'] = $fila['cod_per'];
					$lista[$cont]['codInsc'] = $fila['cod_insc'];
					$lista[$cont]['gdo'] = $fila['grado'];
					$lista[$cont]['prom'] = $fila['promovido'];
					$cont++;
				}
				for ($i=0; $i<count($lista); $i++) { 
					$codEst = $lista[$i]['cod_per'];
					$sql = "UPDATE estudiante SET estatus = '4' WHERE cod_per ='$codEst'";
					#echo $sql;
					$this->query($sql);
				}

				# modifica el estatus de promovido
				$sql = "UPDATE periodo_escolar SET prom = 'S' WHERE cod_periodo = '$this->codigo'";
				$this->query($sql);
				return true;	
			}
		}

		# convierte el record set en un arreglo y lo retorna
		function envia_array($sql){
			$lista = array(); $cont = 0;
			$rs = $this->query($sql); # ejecuta el query
			while( $fila = $this->f_array($rs) ){
				$lista[$cont]['cod'] = $fila['cod_periodo'];
				$lista[$cont]['periodo'] = $fila['periodo'];
				$lista[$cont]['f_ini'] = $fila['fecha_ini'];
				$lista[$cont]['f_fin'] = $fila['fecha_fin'];
				$lista[$cont]['fcsis'] = $fila['cierre_sis'];
				$lista[$cont]['sta'] = $fila['estatus'];
				$lista[$cont]['prom'] = $fila['prom'];
				$cont++;
			}
			return $lista;	
		}	

		# consulta el ultimo año escolar activo
		function aesc_actual(){ 
			$sql = "SELECT * FROM periodo_escolar WHERE estatus = 'A' ORDER BY cod_periodo DESC LIMIT 1";
			if( $rs = $this->query($sql) ){ # existen datos
				return $this->f_array($rs);
			}
		}

		# selecciona el ultimo año escolar
		function ultimo_aesc(){ 
			$sql = "SELECT * FROM periodo_escolar ORDER BY cod_periodo DESC LIMIT 1";
			return $this->f_array($this->query($sql));
		}

		# ultimos 6 años escolares
		function ultimos_6_aesc(){ 
			$sql = "SELECT * FROM periodo_escolar ORDER BY cod_periodo DESC LIMIT 6";
			return $this->envia_array($sql);
		}

		# consultar por codigo o periodo escolar (Ej: 2010-2011 )
		function consultar(){ 
			$sql = "SELECT * FROM periodo_escolar WHERE periodo = '$this->periodo' OR cod_periodo = '$this->codigo'";
			if( $rs = $this->query($sql) ){ # existen datos
				return $this->f_array($rs);
			}
		}

		// >> configuracion de fechas de año escolar
		function modificar_fecha_nuevo_ingreso($fd,$hd,$md,$td,$fh,$hh,$mh,$th){
			if( $fd == '' ){ $fd = '0000-00-00'; }
			if( $hd == '' ){ $hd = '00'; }
			if( $md == '' ){ $md = '00'; }
			if( $fh == '' ){ $fh = '0000-00-00'; }
			if( $hh == '' ){ $hh = '00'; }
			if( $mh == '' ){ $mh = '00'; }
			$hd = $this->ajustarHora($hd, $td);
			$hh = $this->ajustarHora($hh, $th);
			$a = $fd.' '.$hd.':'.$md.':00';
			$b = $fh.' '.$hh.':'.$mh.':00';
			$sql = "UPDATE conf_periodo SET apertura_insc_n='$a', cierre_insc_n='$b' WHERE cod_periodo = '$this->codigo'";
			$this->query($sql);
			#echo $sql;
		}

		function modificar_fecha_regular($fd,$hd,$md,$td,$fh,$hh,$mh,$th){
			if( $fd == '' ){ $fd = '0000-00-00'; }
			if( $hd == '' ){ $hd = '00'; }
			if( $md == '' ){ $md = '00'; }
			if( $fh == '' ){ $fh = '0000-00-00'; }
			if( $hh == '' ){ $hh = '00'; }
			if( $mh == '' ){ $mh = '00'; }
			$hd = $this->ajustarHora($hd, $td);
			$hh = $this->ajustarHora($hh, $th);
			$a = $fd.' '.$hd.':'.$md.':00';
			$b = $fh.' '.$hh.':'.$mh.':00';
			$sql = "UPDATE conf_periodo SET apertura_insc_r='$a', cierre_insc_r='$b' WHERE cod_periodo = '$this->codigo'";
			$this->query($sql);
		}

		function modificar_fecha_matricula_inicial($fd,$fh){
			if( $fd == ''){ $fd = '0000-00-00'; }
			if( $fh == ''){ $fd = '0000-00-00'; }
			$sql = "UPDATE conf_periodo SET fmi_desde='$fd', fmi_hasta='$fh' WHERE cod_periodo = '$this->codigo'";
			$this->query($sql);
		}		

		function consultar_configuracion(){
			$sql = "SELECT * FROM conf_periodo WHERE cod_periodo = '$this->codigo'";
			$rs = $this->query($sql);
			#echo $sql;
			if( $this->f_numrows($rs) ){
				return $this->f_array($rs);
			}
		}
	}
?>