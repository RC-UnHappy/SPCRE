<?php 
require_once('MyDB.php');

	class cls_lapso extends MyDB{
		private $codigo, $lapso, $f_ini, $f_fin, $cod_periodo, $estatus;
		
		function __construct(){
			parent::__construct();
			$this->codigo = null;
			$this->aesc = null; # año escolar
			$this->lapso = null;
			$this->f_ini = null;
			$this->f_fin = null;
			$this->cod_periodo = null;
			$this->estatus = null;
		}

		function set_codPeriodo($cod){
			$this->cod_periodo = $cod;
		}

		function setCodigoLapso($cod){
			$this->codigo = $cod;
		}

		function setLapso($lapso,$aesc){
			$this->lapso = $lapso;
			$this->aesc = $aesc;
		}

		function set_estatus($codLapso,$estatus){
			$this->codigo = $codLapso;
			$this->estatus = $estatus;
		}

		function setFechas($cod,$fi,$ff){
			$this->codigo = $cod;
			$this->f_ini = $fi;
			$this->f_fin = $ff;
		}

		function modificar(){
			$sql = "UPDATE lapso SET fecha_ini = '$this->f_ini', fecha_fin = '$this->f_fin' WHERE cod_lapso = '$this->codigo'";
			$this->query($sql);
		}

		function abrir_lapso(){
			$sql = "UPDATE lapso SET estatus = '$this->estatus' WHERE cod_lapso = '$this->codigo'";
			$this->query($sql);
			return true;	
		}

		function cerrar_lapso(){
			include_once('m_pa.php');
			$objPA = new cls_PA();
			$objPA->set_lapso($this->codigo);
			
			if( $objPA->consultar_PA_abiertos() ){
				$sql = "UPDATE lapso SET estatus = '$this->estatus' WHERE cod_lapso = '$this->codigo'";
				$this->query($sql);
				return true;
			}
		}

		function consultar(){
			$sql = "SELECT cod_lapso,lapso,lapso.fecha_ini,lapso.fecha_fin,lapso.estatus,lapso.cod_periodo,apertura_notas,cierre_notas FROM lapso 
			INNER JOIN periodo_escolar ON lapso.cod_periodo = periodo_escolar.cod_periodo
			WHERE cod_lapso = '$this->codigo'
			OR lapso = '$this->lapso' AND periodo_escolar.cod_periodo = '$this->aesc'
			OR lapso = '$this->lapso' AND periodo = '$this->aesc'";
			#echo $sql;
			$rs = $this->query($sql);
			if( $this->f_numrows($rs) ){
				return $this->f_array($rs);
			}
		}

		function listar(){
			$sql = "SELECT * FROM lapso WHERE cod_periodo = '$this->cod_periodo'";
			$lista = array(); $cont = 0;
			$rs = $this->query($sql);
			#echo $sql;
			while( $fila = $this->f_array($rs) ){
				$lista[$cont]['cod'] = $fila['cod_lapso'];
				$lista[$cont]['lap'] = $fila['lapso'];
				$lista[$cont]['fi'] = $fila['fecha_ini'];
				$lista[$cont]['ff'] = $fila['fecha_fin'];
				$lista[$cont]['sta'] = $fila['estatus'];
				$lista[$cont]['apertura_notas'] = $fila['apertura_notas'];
				$lista[$cont]['cierre_notas'] = $fila['cierre_notas'];
				$cont++;
			}
			return $lista;
		}

		# Lapsos abiertos
		function listar_activos(){
			$sql = "SELECT * FROM lapso WHERE cod_periodo = '$this->cod_periodo' AND lapso.estatus = 'A'";
			$lista = array(); $cont = 0;
			$rs = $this->query($sql);
			while( $fila = $this->f_array($rs) ){
				$lista[$cont]['cod'] = $fila['cod_lapso'];
				$lista[$cont]['lap'] = $fila['lapso'];
				$cont++;
			}
			return $lista;
		}

		function modificar_fecha_notas($fd,$hd,$md,$td,$fh,$hh,$mh,$th){
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
			$sql = "UPDATE lapso SET apertura_notas='$a', cierre_notas='$b' WHERE cod_lapso = '$this->codigo'";
			$this->query($sql);
		}
	}

?>