<?php 
include_once('MyDB.php');

class cls_asistencia extends MyDB
{
	# private $cod_asis;
	private $cod_per, $cod_diahbl, $estatus, $hora, $observacion, $marcacion;
	public $filtro, $filas; # para datatables
	# Estatus: A=asistente I=inasistente J=inasistente justificado

	function __construct(){
		parent::__construct(); 
		$this->filtro = null;
		$this->filas = 0;
		$this->marcacion = 'E';
	}

	// function set_codigo($codigo){
	// 	$this->cod_asis = $codigo;
	// }

	function set_dia_habil($dia){
		$this->cod_diahbl = date('Y-m-d', strtotime($dia));
	}

	function set_marcacion($m){
		$this->marcacion = $m;
	}

	function set($cod_per,$estatus,$hora,$observacion){
		$this->cod_per = $cod_per;
		$this->estatus = $estatus;
		$this->hora = $hora;
		$this->observacion = $observacion;
	}

	function set_filtro($filtro){
		$this->filtro = $this->limpiarCadena($filtro); 
	}

	function operar(){
		if( $this->consultar() ){
			$this->modificar();
		}
		else{
			$this->incluir();
		}
	}

	function incluir(){
		$sql = "INSERT INTO asistencia (cod_per,cod_diahbl,marcacion,estatus,hora,observacion)VALUES('$this->cod_per','$this->cod_diahbl','$this->marcacion','$this->estatus','$this->hora','$this->observacion')";
		$this->query($sql);
		$this->desconectar();
	}

	function modificar(){
		$sql = "UPDATE asistencia SET estatus='$this->estatus', hora='$this->hora', observacion='$this->observacion' WHERE cod_per = '$this->cod_per' AND cod_diahbl = '$this->cod_diahbl' AND marcacion='$this->marcacion'";
		$this->query($sql);
		$this->desconectar();
	}

	function consultar(){ # Existe la asistencia
		$sql = "SELECT * FROM asistencia WHERE cod_diahbl = '$this->cod_diahbl' AND cod_per = '$this->cod_per' AND marcacion = '$this->marcacion'";
		if( $this->f_numrows($this->query($sql))){
			$this->desconectar();
			return true;
		}
	}

	function listar_personal($desde=0,$limite=15,$cargo='All'){
		if( $cargo == 'All' ){
			$sqlF = "SELECT count(personal.cod_per) FROM personal 
			INNER JOIN persona ON personal.cod_per = persona.cod_per
			INNER JOIN cargo ON personal.cod_cargo = cargo.cod_cargo
			WHERE personal.estatus = 'A'";
			$this->filas = $this->total_filas($sqlF); # total de registros encontrados

			$sql = "SELECT personal.cod_per,tipo_documento,cedula, CONCAT(nom1,' ',ape1) AS nom_ape,personal.cod_cargo,nom_cargo,
			( SELECT asistencia.estatus FROM asistencia WHERE cod_diahbl = '$this->cod_diahbl' AND asistencia.cod_per = personal.cod_per AND marcacion='$this->marcacion') AS asistencia,
			( SELECT hora FROM asistencia WHERE cod_diahbl = '$this->cod_diahbl' AND asistencia.cod_per = personal.cod_per AND marcacion='$this->marcacion') AS hora,
			( SELECT asistencia.observacion FROM asistencia WHERE cod_diahbl = '$this->cod_diahbl' AND asistencia.cod_per = personal.cod_per AND marcacion='$this->marcacion') AS obs
			FROM personal 
			INNER JOIN persona ON personal.cod_per = persona.cod_per
			INNER JOIN cargo ON personal.cod_cargo = cargo.cod_cargo
			WHERE personal.estatus = 'A' ORDER BY cod_cargo ASC, cedula ASC LIMIT $desde,$limite";
			#echo $sql;
		}
		else{
			$sqlF = "SELECT count(personal.cod_per) FROM personal 
			INNER JOIN persona ON personal.cod_per = persona.cod_per
			INNER JOIN cargo ON personal.cod_cargo = cargo.cod_cargo
			WHERE personal.estatus = 'A' AND personal.cod_cargo = '$cargo'";
			$this->filas = $this->total_filas($sqlF); # total de registros encontrados

			$sql = "SELECT personal.cod_per,tipo_documento,cedula, CONCAT(nom1,' ',ape1) AS nom_ape,personal.cod_cargo,nom_cargo,
			( SELECT asistencia.estatus FROM asistencia WHERE cod_diahbl = '$this->cod_diahbl' AND asistencia.cod_per = personal.cod_per AND marcacion='$this->marcacion') AS asistencia,
			( SELECT hora FROM asistencia WHERE cod_diahbl = '$this->cod_diahbl' AND asistencia.cod_per = personal.cod_per AND marcacion='$this->marcacion') AS hora,
			( SELECT asistencia.observacion FROM asistencia WHERE cod_diahbl = '$this->cod_diahbl' AND asistencia.cod_per = personal.cod_per AND marcacion='$this->marcacion') AS obs
			FROM personal 
			INNER JOIN persona ON personal.cod_per = persona.cod_per
			INNER JOIN cargo ON personal.cod_cargo = cargo.cod_cargo
			WHERE personal.estatus = 'A' AND personal.cod_cargo = '$cargo' ORDER BY cod_cargo ASC, cedula ASC LIMIT $desde,$limite";	
		}
		$rs = $this->query($sql);
		$this->desconectar();
		return $this->envia_array_personal($rs);	
	}

	function filtrar_personal($desde=0,$limite=15,$cargo='All'){
		if( $cargo == 'All' ){
			$sqlF = "SELECT count(personal.cod_per) FROM personal 
			INNER JOIN persona ON personal.cod_per = persona.cod_per
			INNER JOIN cargo ON personal.cod_cargo = cargo.cod_cargo
			WHERE personal.estatus = 'A' AND cedula LIKE '$this->filtro%'
			OR personal.estatus = 'A' AND nom1 LIKE '$this->filtro%'
			OR personal.estatus = 'A' AND ape1 LIKE '$this->filtro%'
			OR personal.estatus = 'A' AND CONCAT(nom1,' ',ape1) LIKE '$this->filtro%'
			OR personal.estatus = 'A' AND CONCAT(ape1,' ',nom1) LIKE '$this->filtro%'";
			$this->filas = $this->total_filas($sqlF); # total de registros encontrados

			$sql = "SELECT personal.cod_per,tipo_documento,cedula, CONCAT(nom1,' ',ape1) AS nom_ape,personal.cod_cargo,nom_cargo,
			( SELECT asistencia.estatus FROM asistencia WHERE cod_diahbl = '$this->cod_diahbl' AND asistencia.cod_per = personal.cod_per AND marcacion='$this->marcacion') AS asistencia,
			( SELECT hora FROM asistencia WHERE cod_diahbl = '$this->cod_diahbl' AND asistencia.cod_per = personal.cod_per AND marcacion='$this->marcacion') AS hora,
			( SELECT asistencia.observacion FROM asistencia WHERE cod_diahbl = '$this->cod_diahbl' AND asistencia.cod_per = personal.cod_per AND marcacion='$this->marcacion') AS obs
			FROM personal 
			INNER JOIN persona ON personal.cod_per = persona.cod_per
			INNER JOIN cargo ON personal.cod_cargo = cargo.cod_cargo
			WHERE personal.estatus = 'A' AND cedula LIKE '$this->filtro%'
			OR personal.estatus = 'A' AND nom1 LIKE '$this->filtro%'
			OR personal.estatus = 'A' AND ape1 LIKE '$this->filtro%'
			OR personal.estatus = 'A' AND CONCAT(nom1,' ',ape1) LIKE '$this->filtro%'
			OR personal.estatus = 'A' AND CONCAT(ape1,' ',nom1) LIKE '$this->filtro%'
			ORDER BY cod_cargo ASC, cedula ASC LIMIT $desde,$limite";
			#echo $sql;
		}
		else{
			$sqlF = "SELECT count(personal.cod_per) FROM personal 
			INNER JOIN persona ON personal.cod_per = persona.cod_per
			INNER JOIN cargo ON personal.cod_cargo = cargo.cod_cargo
			WHERE personal.estatus = 'A' AND personal.cod_cargo='$cargo' AND cedula LIKE '$this->filtro%'
			OR personal.estatus = 'A' AND personal.cod_cargo='$cargo' AND nom1 LIKE '$this->filtro%'
			OR personal.estatus = 'A' AND personal.cod_cargo='$cargo' AND ape1 LIKE '$this->filtro%'
			OR personal.estatus = 'A' AND personal.cod_cargo='$cargo' AND CONCAT(nom1,' ',ape1) LIKE '$this->filtro%'
			OR personal.estatus = 'A' AND personal.cod_cargo='$cargo' AND CONCAT(ape1,' ',nom1) LIKE '$this->filtro%'";
			$this->filas = $this->total_filas($sqlF); # total de registros encontrados

			$sql = "SELECT personal.cod_per,tipo_documento,cedula, CONCAT(nom1,' ',ape1) AS nom_ape,personal.cod_cargo,nom_cargo,
			( SELECT asistencia.estatus FROM asistencia WHERE cod_diahbl = '$this->cod_diahbl' AND asistencia.cod_per = personal.cod_per AND marcacion='$this->marcacion') AS asistencia,
			( SELECT hora FROM asistencia WHERE cod_diahbl = '$this->cod_diahbl' AND asistencia.cod_per = personal.cod_per AND marcacion='$this->marcacion') AS hora,
			( SELECT asistencia.observacion FROM asistencia WHERE cod_diahbl = '$this->cod_diahbl' AND asistencia.cod_per = personal.cod_per AND marcacion='$this->marcacion') AS obs
			FROM personal 
			INNER JOIN persona ON personal.cod_per = persona.cod_per
			INNER JOIN cargo ON personal.cod_cargo = cargo.cod_cargo
			WHERE personal.estatus = 'A' AND personal.cod_cargo='$cargo' AND cedula LIKE '$this->filtro%'
			OR personal.estatus = 'A' AND personal.cod_cargo='$cargo' AND nom1 LIKE '$this->filtro%'
			OR personal.estatus = 'A' AND personal.cod_cargo='$cargo' AND ape1 LIKE '$this->filtro%'
			OR personal.estatus = 'A' AND personal.cod_cargo='$cargo' AND CONCAT(nom1,' ',ape1) LIKE '$this->filtro%'
			OR personal.estatus = 'A' AND personal.cod_cargo='$cargo' AND CONCAT(ape1,' ',nom1) LIKE '$this->filtro%'
			ORDER BY cod_cargo ASC, cedula ASC LIMIT $desde,$limite";	
		}
		$rs = $this->query($sql);
		$this->desconectar();
		return $this->envia_array_personal($rs);
	}

	function envia_array_personal($rs){
		$lista = array(); $cont = 0; # Array en donde se almacenará cada registro
		while ( $fila = $this->f_array($rs) ) {
			$lista[$cont]['cod_per'] = $fila['cod_per']; 
			$lista[$cont]['ced'] = $fila['tipo_documento'].'-'.$fila['cedula'];
			$lista[$cont]['nom_ape'] = $fila['nom_ape'];
			$lista[$cont]['car'] = $fila['nom_cargo'];
			$lista[$cont]['asis'] = $fila['asistencia'];
			$lista[$cont]['hora'] = $fila['hora'];
			$lista[$cont]['obs'] = $fila['obs'];
			$cont++;
		}
		return $lista; # devuelve el arreglo
	}

	function listar_estudiantes($seccion){
		$sql = "SELECT persona.cod_per, tipo_documento, cedula, ced_esc, CONCAT(nom1,' ',nom2,' ',ape1,' ',ape2,' ') as nom_ape,
		( SELECT asistencia.estatus FROM asistencia WHERE cod_diahbl = '$this->cod_diahbl' AND asistencia.cod_per = persona.cod_per ) AS asistencia 
		FROM inscripcion INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per INNER JOIN persona ON estudiante.cod_per = persona.cod_per 
		WHERE inscripcion.estatus = 'A' AND seccion='$seccion'";
		#echo $sql;
		$rs = $this->query($sql);
		$this->desconectar();

		$lista = array(); $cont = 0; 
		while ( $fila = $this->f_array($rs) ) {
			$lista[$cont]['cod_per'] = $fila['cod_per']; 
			$fila['cedula'] != '' ? $lista[$cont]['ced'] = $fila['tipo_documento'].'-'.$fila['cedula'] : $lista[$cont]['ced'] = $fila['ced_esc'];
			$lista[$cont]['nom_ape'] = $fila['nom_ape'];
			$lista[$cont]['asis'] = $fila['asistencia'];
			$cont++;
		}
		return $lista; 
	}
}

?>