<?php 
include_once('MyDB.php');

class cls_dia_habil extends MyDB
{
	private $cod_diahbl, $cod_periodo;

	function __construct(){
		parent::__construct(); # constructor del padre
	}

	function set($cod_diahbl,$cod_periodo){
		$this->cod_diahbl = $cod_diahbl;
		$this->cod_periodo = $cod_periodo;
	}

	function incluir(){
		$sql = "INSERT INTO dia_habil (cod_diahbl,cod_periodo,estatus) VALUES ('$this->cod_diahbl','$this->cod_periodo','A')";
		$this->query($sql);
		$this->desconectar();
	}

	function activar_dia(){
		$sql = "UPDATE dia_habil SET estatus = 'A' WHERE cod_diahbl = '$this->cod_diahbl' AND cod_periodo = '$this->cod_periodo'";
		$this->query($sql);
		$this->desconectar();
	}

	function desactivar_dias(){
		$sql = "UPDATE dia_habil SET estatus = 'I' WHERE cod_periodo = '$this->cod_periodo'";
		$this->query($sql);
		$this->desconectar();
	}

	function existe(){ # consulta si existe un dia en especifico
		$sql = "SELECT * FROM dia_habil WHERE cod_diahbl = '$this->cod_diahbl'";
		if($this->f_numrows($this->query($sql))){
			$this->desconectar();
			return true;
		}
	}

	function consultar_dias_periodo(){ # consulta todos los dias habiles de un periodo escolar
		$sql = "SELECT * FROM dia_habil WHERE cod_periodo = '$this->cod_periodo' AND cod_diahbl != '0000-00-00'";
		$rs = $this->query($sql);
		$lista = array(); $cont = 0;
		while( $fila =  $this->array_assoc($rs) ){
			$lista[$cont]['cod_diahbl'] = $fila['cod_diahbl']; 
			$lista[$cont]['cod_periodo'] = $fila['cod_periodo'];
			$lista[$cont]['estatus'] = $fila['estatus'];
			$cont++;
		}
		$this->desconectar();
		return $lista;
	}

	function consultar_dias_activos(){ # consulta todos los dias de un periodo escolar con estatus activo
		$sql = "SELECT cod_diahbl,estatus FROM dia_habil WHERE cod_periodo = '$this->cod_periodo' AND estatus = 'A' AND cod_diahbl != '0000-00-00'";
		$rs = $this->query($sql);
		$lista = array(); $cont = 0;
		while( $fila =  $this->array_assoc($rs) ){
			$lista[$cont]['cod_diahbl'] = $fila['cod_diahbl']; 
			$lista[$cont]['estatus'] = $fila['estatus'];
			$cont++;
		}
		$this->desconectar();
		return $lista;
	}

	function consultar_dias_mes($num_mes){ # consulta todos los dias de un mes de un periodo escolar con estatus activo
		$sql = "SELECT cod_diahbl,estatus FROM dia_habil WHERE cod_periodo = '$this->cod_periodo' AND estatus = 'A' AND cod_diahbl != '0000-00-00' AND MONTH(cod_diahbl) = '$num_mes'";
		$rs = $this->query($sql);
		$lista = array(); $cont = 0;
		while( $fila =  $this->array_assoc($rs) ){
			$lista[$cont]['cod_diahbl'] = $fila['cod_diahbl']; 
			$lista[$cont]['estatus'] = $fila['estatus'];
			$cont++;
		}
		$this->desconectar();
		return $lista;
	}
}

?>