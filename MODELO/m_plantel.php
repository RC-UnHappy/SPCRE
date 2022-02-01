<?php 
require_once('MyDB.php');

class cls_plantel extends MyDB{
	# propiedades
	private $director, 
	$nom_escuela, 
	$edo,
	$mun,
	$zonaeduc,
	$codplantel,
	$codestco,
	$coddea,
	$correo, 
	$telefono, 
	$direccion; 

	function __construct(){
		parent::__construct(); # constructor del padre
		$this->director = null; 
		$this->nom_escuela = null;
		$this->edo = null;
		$this->mun = null;
		$this->zonaeduc = null;
		$this->codplantel = null;
		$this->codestco = null;
		$this->coddea = null;
		$this->correo = null;
		$this->telefono = null;
		$this->direccion = null;
	}

	# pasa valor a las propiedades
	function setDatos($a,$b,$c,$d,$e,$f,$g,$h,$i,$j,$k){
		$this->director = $a;
		$this->nom_escuela = $this->limpiarCadena($b);
		$this->edo = $this->limpiarCadena($c);
		$this->mun = $this->limpiarCadena($d);
		$this->zonaeduc = $e;
		$this->codplantel = $f;
		$this->codestco = $g;
		$this->coddea = $h;
		$this->correo = trim($i);
		$this->telefono = $j;
		$this->direccion = $this->limpiarCadena($k);
	}

	# modifica los datos del plantel
	function modificar(){
		$sql = "UPDATE plantel SET 
		cod_director='$this->director', 
		nom_escuela='$this->nom_escuela',
		edo = '$this->edo',
		mun = '$this->mun',
		zonaeduc = '$this->zonaeduc',
		codplantel = '$this->codplantel',
		codestco = '$this->codestco',
		coddea = '$this->coddea',
		direccion='$this->direccion',
		correo='$this->correo', 
		telefono='$this->telefono'";
		$this->query($sql);
		#echo $sql;
	}
	
	# consulta datos del plantel
	function consultar(){
		$sql = "SELECT cod_director,nom_escuela,edo,mun,zonaeduc,codplantel,codestco,coddea,direccion,correo,telefono, CONCAT(tipo_documento,'-',cedula) AS cedula, CONCAT(nom1,' ',ape1) AS director, sexo FROM plantel
		INNER JOIN personal ON plantel.cod_director = personal.cod_per
		INNER JOIN persona ON personal.cod_per = persona.cod_per"; 
		return $this->f_array($this->query($sql));
	}
}
?>