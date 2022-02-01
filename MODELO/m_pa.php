<?php 
	require_once('MyDB.php');
		
	# Clase proyecto de aprendizaje
	class cls_PA extends MyDB{

		private $codigo_PA, $nombre_PA, $tiempo_PA, $seccion, $lapso, $estatus;

		function __construct(){
			parent::__construct();
			$this->codigo_PA = null; 
			$this->nombre_PA = null;
			$this->tiempo_PA = null;
			$this->seccion = null;
			$this->lapso = null;
			$this->estatus = null;
		}

		function setCodigo($cod){
			$this->codigo_PA = $cod; 
		}

		function set_lapso($lap){
			$this->lapso = $lap;
		}

		function setPA($nombre,$seccion,$lapso,$tiempo){
			$this->nombre_PA = $this->limpiarCadena($nombre);
			$this->seccion = $seccion;
			$this->lapso = $lapso;
			$this->tiempo_PA = mb_strtoupper($this->limpiarCadena($tiempo));
		}

		function incluirPA(){
			$sql = "INSERT INTO proyecto_ap (cod_seccion,cod_lapso,nom_pa,duracion) VALUES 
			('$this->seccion','$this->lapso','$this->nombre_PA','$this->tiempo_PA')";
			$this->query($sql);
		}

		function modificarPA(){
			$sql = "UPDATE proyecto_ap SET nom_pa='$this->nombre_PA',duracion='$this->tiempo_PA'
			WHERE cod_proyecto = '$this->codigo_PA'";
			$this->query($sql);
		}

		function CerrarInd($X){
			$sql = "UPDATE proyecto_ap SET estatus = '$X' WHERE cod_proyecto = '$this->codigo_PA'";
			$this->query($sql);
		}

		function consultarPA(){
			$sql = "SELECT cod_proyecto,cod_seccion,cod_lapso,nom_pa,duracion,estatus FROM proyecto_ap 
			WHERE cod_seccion = '$this->seccion' AND cod_lapso='$this->lapso'";
			$rs = $this->query($sql);
			#echo $sql;
			if( $this->f_numrows($rs) ){
				return $this->f_array($rs);
			}
		}

		function consultar_PA_abiertos(){
			$sql = "SELECT * FROM proyecto_ap WHERE cod_lapso = '$this->lapso'";
			$rs = $this->query($sql);

			if( $this->f_numrows($rs) ){ # se encontraron resultados
				$sql = "SELECT * FROM proyecto_ap WHERE cod_lapso = '$this->lapso' AND estatus = 'A'";
				$rs = $this->query($sql);

				if( mysqli_num_rows($rs) < 1 ){ # no existe ningun PA abierto
					return true;
				}
			}			
		}
	}
?>


