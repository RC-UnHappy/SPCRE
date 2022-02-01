<?php 
	include_once('MyDB.php');
	class cls_vivienda extends MyDB{

		function __construct(){
			parent::__construct(); # constructor del padre
		}

		function listar_tipoVDA(){ # lista los tipos de vivienda
			$lista = array(); $cont=0;
			$sql = "SELECT * FROM tipo_vivienda";
			$rs = $this->query($sql);
			while( $fila = $this->f_array( $rs ) ){
				$lista[$cont]['cod'] = $fila['cod_tipo_vda'];
				$lista[$cont]['nom'] = $fila['nom_tipo_vda'];
				$cont++;
			}
			return $lista;
		}

		function listar_condVDA(){ # condicion de vivienda
			$lista = array(); $cont=0;
			$sql = "SELECT * FROM cond_vivienda";
			$rs = $this->query($sql);
			while( $fila = $this->f_array( $rs ) ){
				$lista[$cont]['cod'] = $fila['cod_cond_vnda'];
				$lista[$cont]['nom'] = $fila['nom_cond_vnda'];
				$cont++;
			}
			return $lista;
		}

		function listar_condInfraVDA(){ # condicion de infraestructura
			$lista = array(); $cont=0;
			$sql = "SELECT * FROM cond_infraestructura";
			$rs = $this->query($sql);
			while( $fila = $this->f_array( $rs ) ){
				$lista[$cont]['cod'] = $fila['cod_cond_inf'];
				$lista[$cont]['nom'] = $fila['nom_cond_inf'];
				$cont++;
			}
			return $lista;
		}
	}
?>