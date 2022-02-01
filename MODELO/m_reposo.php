<?php 
include_once('MyDB.php');

class cls_reposo extends MyDB{
	private $cod_reposo, $cod_per, $desde, $hasta, $descripcion, $dia_habil;

	function __construct(){
		parent::__construct();
	}

	function set_codigo( $cod ){ # codigo de reposo
		$this->cod_reposo = $cod;
	}

	function set_cod_per($cod){
		$this->cod_per = $cod;
	}

	function set_dia_habil($dia){
		$this->dia_habil = date('Y-m-d', strtotime($dia));
	}

	function set($cod_per,$desde,$hasta,$descripcion){
		$this->cod_per = $cod_per;
		$this->desde = $desde;
		$this->hasta = $hasta;
		$this->descripcion = $this->limpiarCadena(htmlentities($descripcion));
	}

	function incluir(){
		$hoy = date('Y-m-d');
		$sql = "INSERT INTO reposo (cod_per,fecha,desde,hasta,descripcion) VALUES ('$this->cod_per','$hoy','$this->desde','$this->hasta','$this->descripcion')";
		$this->query($sql);
		if( $this->f_affectrows() ){
			$this->desconectar();
			return true;
		}
	}

	function modificar(){
		$sql = "UPDATE reposo SET desde='$this->desde',hasta='$this->hasta',descripcion='$this->descripcion' WHERE cod_reposo='$this->cod_reposo'";
		$this->query($sql);
		$this->desconectar();
		return true;
	}

	function consultar_reposo(){
		$sql = "SELECT * FROM reposo WHERE cod_per = '$this->cod_per' AND desde <= '$this->dia_habil' AND hasta >= '$this->dia_habil'";
		$rs = $this->query($sql);
		$this->desconectar();
		if( $this->f_numrows($rs) ){
			return $this->array_assoc($rs);
		}
	}

	// function elimiar(){

	// }

	function listar(){
		$sql = "SELECT persona.cod_per, CONCAT(tipo_documento,'-',cedula) AS cedula, CONCAT(nom1,' ',ape1) AS nom_ape, nom_cargo, cod_reposo, fecha, desde, hasta, descripcion FROM reposo
		INNER JOIN personal ON reposo.cod_per = personal.cod_per
		INNER JOIN persona ON personal.cod_per = persona.cod_per
		INNER JOIN cargo ON personal.cod_cargo = cargo.cod_cargo";
		$rs = $this->query($sql);
		$this->desconectar();
		$lista = array(); $cont = 0;
		while( $fila = $this->array_assoc($rs) ){
			$lista[$cont]['cod_per'] = $fila['cod_per']; 
			$lista[$cont]['cedula'] = $fila['cedula'];
			$lista[$cont]['nom_ape'] = $fila['nom_ape'];
			$lista[$cont]['nom_cargo'] = strtoupper($fila['nom_cargo']);
			$lista[$cont]['cod_reposo'] = $fila['cod_reposo']; 
			$lista[$cont]['fecha'] = $fila['fecha'];
			$lista[$cont]['desde'] = date('d-m-Y', strtotime($fila['desde']));
			$lista[$cont]['hasta'] = date('d-m-Y', strtotime($fila['hasta']));
			$lista[$cont]['descripcion'] = $fila['descripcion'];
			$cont++;
		}
		return $lista;
	}
}
?>