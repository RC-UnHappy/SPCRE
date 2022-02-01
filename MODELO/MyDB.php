<?php 

class MyDB{
	private $server_name;
	private $server_user;
	private $server_pass;
	private $dbname;
	private $conex;

	protected function __construct(){
		$this->server_name = 'localhost';   # nombre de mi servidor
		$this->server_user = 'root'; 		# usuario
		$this->server_pass = ''; 			# contraseña
		$this->dbname = 'db_sm';
		// $this->server_name = 'sql209.epizy.com';   # nombre de mi servidor
		// $this->server_user = 'epiz_27785359'; 		# usuario
		// $this->server_pass = 'DR4DuhfXDcKd7'; 		# contraseña
		// $this->dbname = 'epiz_27785359_db_sm';
		// $this->conectar(); 
	}

	protected function conectar(){
		$this->conex = mysqli_connect($this->server_name, $this->server_user, $this->server_pass, $this->dbname);
		if( $this->conex ){ # la conexión es correcta:
			# echo mysqli_thread_id($this->conex).'/';
			//echo mysqli_get_client_info($this->conex).'/';
			// echo '<pre>';
			// print_r($this->conex);
			// echo '</pre>';
			// echo '<br>';
			return true;
		}

		else{
			die('Error al conectar: '.mysqli_connect_error() );
		}
	}
	

	protected function query($sql){# Permite enviar sentencias a la base de datos activa
		$this->conectar();
		mysqli_set_charset($this->conex,"utf8");
		$query = mysqli_query($this->conex, $sql); # Se ejecuta el query
		# $this->desconectar();
		return $query;
	}

	protected function ultimo_id(){ # devuelve el id autogenerado que se utilizo en la ultima consulta
		return mysqli_insert_id($this->conex);
	}

	protected function f_array($resource){ # Devuelve una matriz que corresponde a la sentencia extraida.
		return mysqli_fetch_array($resource);
	}

	protected function array_assoc($resource){
		return mysqli_fetch_assoc($resource);
	}

	protected function f_numrows($resource){ # Permite recuperar el número de filas de un conjunto de resultados.
		if( mysqli_num_rows($resource) > 0 ){
			return true;
		}	
	}

	protected function f_affectrows(){ # Obtiene el número de filas afectadas en la última operación 
		if( mysqli_affected_rows($this->conex) > 0){
			return true;
		}
	}

	public function f_free_result($resource){ # Permite liberar toda la memoria asociada con el identificador del resultado
		mysqli_free_result($resource);
	}

	protected function total_filas($sql){ # devuelve el numero de registros en la tabla
		$res = $this->query($sql);
		$this->desconectar();
		$num = $this->f_array($res);
		return $num[0];
	}

	protected function limpiarCadena($cadena){ # elimina espacios en blanco sobrantes
		$cadena = explode(" ",$cadena);
		$i=0;
		$cadenas = '';
		while($i<count($cadena)){
			if($cadena[$i]!=""){
				$cadenas = $cadenas.trim($cadena[$i])." ";
			}
			$i++;
		}
		return trim($cadenas);
	}

	public function solo_letras($cadena){
    	return preg_replace('([^ A-Za-z_-ñÑ])', '', $cadena);
    	#return (ereg_replace('[^ A-Za-z0-9_-ñÑ]', '', $cadena));
	}

	public function solo_numeros($numero){
		return preg_replace('([^ 0-9])', '', $cadena);
	}

	// public function eliminar_especiales($cadena){
	// 	return preg_replace('([^ A-Za-z_-ñÑ])', '', $cadena);
	// }

	protected function desconectar(){ # Cierra la conexión 
		mysqli_close($this->conex);
	}	

	# TRANSACCIONES
	protected function empezar_op(){
		mysqli_begin_transaction($this->conex);
	}

	protected function finalizar_op(){
		mysqli_commit($this->conex);
	}

	protected function deshacer_op(){
		mysqli_rollback($this->conex);
	}

	public function f_fecha($fecha){ // dar formato a la fecha
		# año mes dia

		$arr = explode("-", $fecha);
		// $arr = str_split("-", $fecha);
		$new = $arr[2].'-'.$arr[1].'-'.$arr[0];
		return $new;
	}
	# calcula la edad de un estudiante a partir de la fecha de nacimiento y la fecha actual
	public function calcular_edad($fechaNac){
		$fechaA = date('Y-m-d'); # fecha actual del servidor
		$edad = substr($fechaA, 0,4)- substr($fechaNac, 0,4); # edad en años
		$difM = substr($fechaA, 5,2)- substr($fechaNac, 5,2); # diferencia en mes
		$diaA = substr($fechaA, 8,2); # dia actual
		$diaN = substr($fechaNac, 8,2); # dia de nacimiento
		if( $difM < 0 || $difM === 0 && $diaA < $diaN){
      	  $edad--;
    	}
    	return $edad;
	}
	
	// ajusta una hora usual a militar
	function ajustarHora($hora,$mer){
		# hora y meridiano
		$nHora = '';

		if( $mer == 'am' ){
			if( $hora >= 12 ){ # hora 24
				$nHora = 00;
			}
			else if( $hora < 10 ){
				$nHora = $hora;
				if( strlen($hora) == 1 ){
					$nHora = '0'.$hora; # concatena un 0
				}
			}
			else if( $hora >= 10 && $hora <= 11 ){
				$nHora = $hora; # es la misma
			}
		}

		else if( $mer == 'pm' ){
			if( $hora == 12 ){
				$nHora = 12;
			}
			else{
				$nHora = $hora+12;
			}
		}
		return $nHora;
	}

	function pre($obj){
		echo '<pre>';
		print_r($obj);
		echo '</pre>';
	}
}

?>