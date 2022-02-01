<?php 
	# CLASES: MODULO, SERVICIO, METODO
	include_once('MyDB.php');
	# clase modulo
	class cls_modulo extends MyDB{
		private $codigo, $nombre, $icono, $estatus, $pos, $filtro;
		public $filas;
		function __construct(){
			parent::__construct(); 
			$this->codigo = null;
			$this->nombre = null;
			$this->icono = null;
			$this->estatus = null;
			$this->filtro = null;
			$this->filas = null;
		}

		function set($cod,$nom,$ico,$sta,$pos){
			$this->codigo = $cod;
			#$this->nombre = $this->limpiarCadena(ucwords($nom));
			$this->nombre = $this->limpiarCadena($this->solo_letras(ucwords($nom)));
			$this->icono = $this->limpiarCadena($this->solo_letras(strtolower($ico)));
			$this->estatus = $sta;
			$this->pos = $pos;
		}

		function set_filtro($f){
			$this->filtro = $f;
		}

		function incluir(){
			$sql = "INSERT INTO modulo (nom_modulo,icono,estatus,pos) VALUES ('$this->nombre','$this->icono','$this->estatus','$this->pos')";
			$this->query($sql);
			if( $this->f_affectrows() ){
				return true;
			}
		}

		function modificar(){
			$sql = "UPDATE modulo SET nom_modulo='$this->nombre', icono='$this->icono', estatus='$this->estatus', pos='$this->pos' WHERE cod_modulo='$this->codigo'";
			
			if( $rs = $this->consultar_por_nombre() ){
				# Es el mismo
				if( $rs['cod_modulo'] == $this->codigo ){
					$this->query($sql);
					return true;
				}
			}
			else{
				# No existe el nombre, modifica
				$this->query($sql);
				return true;
			}		
		}

		function eliminar(){
			$sql = "DELETE FROM modulo WHERE cod_modulo = '$this->codigo'";
			$this->query($sql);
		}

		function consultar(){ # consulta por codigo
			$sql = "SELECT * FROM modulo WHERE cod_modulo='$this->codigo'";
			$rs = $this->query($sql);
			if( $this->f_numrows($rs) ){
				return $this->array_assoc($rs);
			}
		}

		function consultar_por_nombre(){ # consulta por nombre
			$sql = "SELECT * FROM modulo WHERE nom_modulo='$this->nombre'";
			$rs = $this->query($sql);
			if( $this->f_numrows($rs) ){
				return $this->array_assoc($rs);
			}
		}

		function listar($desde=0,$mostrar=15){
			$sql = "SELECT * FROM modulo ORDER BY pos ASC LIMIT $desde,$mostrar";
			$sqlF = "SELECT count(cod_modulo) FROM modulo";
			$this->filas = $this->total_filas($sqlF); # total de registros encontrados
			$res = $this->query($sql);
			return $this->retornar_arreglo($res);
		}

		function filtrar($desde=0,$mostrar=15){ #
			$sqlF = "SELECT count(cod_modulo) FROM modulo WHERE nom_modulo LIKE '$this->filtro%'";
			$this->filas = $this->total_filas($sqlF); # total de registros encontrados
			$sql = "SELECT * FROM modulo WHERE nom_modulo LIKE '$this->filtro%' ORDER BY pos ASC LIMIT $desde,$mostrar";
			#echo $sql;
			$res = $this->query($sql);
			return $this->retornar_arreglo($res);
		}

		function listar_todo(){ # selcciona todas las tuplas
			$sql = "SELECT * FROM modulo ORDER BY pos ASC";
			$res = $this->query($sql);
			return $this->retornar_arreglo($res);
		}

		function listar_menu($nivel){ # Lista el menu con restricciones de nivel de usuario
			$sql = "SELECT DISTINCT modulo.cod_modulo, nom_modulo, modulo.icono, modulo.estatus, modulo.pos 
			FROM modulo INNER JOIN servicio ON servicio.cod_modulo = modulo.cod_modulo INNER JOIN nivel_metodo ON nivel_metodo.cod_servicio = servicio.cod_servicio WHERE nivel_metodo.cod_nivel = '$nivel' ORDER BY modulo.pos ASC";
			$res = $this->query($sql);
			return $this->retornar_arreglo($res);
		}

		function retornar_arreglo($res){
			$lista = array(); $cont = 0;
			while( $fila = $this->f_array($res) ){
				$lista[$cont]['cod'] = $fila['cod_modulo']; 
				$lista[$cont]['nom'] = $fila['nom_modulo']; 
				$lista[$cont]['ico'] = $fila['icono'];
				$lista[$cont]['sta'] = $fila['estatus'];
				$lista[$cont]['pos'] = $fila['pos'];
				$cont++;
			}
			return $lista;
		}

		# IMPRIMIR HTML
		function combo_option($selected="0"){
			$rs = $this->listar_todo();
		
			for ($i=0; $i<count($rs); $i++) {
				$s = '';
				if($selected == $rs[$i]['cod']){
					$s = 'selected';
				}
				echo '<option value="'.$rs[$i]['cod'].'" '.$s.'>'.$rs[$i]['nom'].'</option>';
			}
		}	
	}

	# clase servicio
	class cls_servicio extends MyDB{
		private $modulo, $codigo, $nombre, $icono, $link, $mostrar_menu, $pos, $filtro, $filas;
		private $nivel; 
		function __construct(){
			parent::__construct(); 
			$this->modulo = null;
			$this->codigo = null;
			$this->nombre = null;
			$this->icono = null;
			$this->filtro = null;
			$this->filas = null;
			$this->nivel = null;
		}

		function set($cod,$nom,$ico,$mdlo,$sta,$link,$mostrar_menu,$pos){
			$this->modulo = $mdlo;
			$this->codigo = $cod;
			$this->nombre = $this->limpiarCadena($this->solo_letras(ucwords($nom)));
			$this->icono = $this->limpiarCadena($this->solo_letras(strtolower($ico)));
			$this->estatus = $sta;
			$this->link = $this->limpiarCadena($link);
			$this->mostrar_menu = $mostrar_menu;
			$this->pos = $pos;
		}

		function set_codigo($cod){
			$this->codigo = $cod;
		}

		function set_modulo($mdlo){
			$this->modulo = $mdlo;
		}

		function set_filtro($f){
			$this->filtro = $f;
		}

		function set_nivel($n){
			$this->nivel = $n;
		}

		function getFilas(){
			return $this->filas;
		}

		function incluir(){
			$sql = "INSERT INTO servicio (nom_servicio,cod_modulo,icono,link,mostrar_menu,pos) VALUES ('$this->nombre','$this->modulo','$this->icono','$this->link','$this->mostrar_menu','$this->pos')";
			# no existe el servicio en un modulo
			if( !$this->consultar_por_nombre() ){
				$this->query($sql);
				return true;
			}	
		}

		function modificar(){
			$sqlM = "UPDATE servicio SET nom_servicio='$this->nombre', cod_modulo='$this->modulo', icono='$this->icono', estatus='$this->estatus', link='$this->link', mostrar_menu='$this->mostrar_menu', pos='$this->pos' 
			WHERE cod_servicio = '$this->codigo'";

			$sql = "SELECT * FROM servicio WHERE nom_servicio = '$this->nombre' AND cod_modulo = '$this->modulo'";

			$rs = $this->query($sql);
			if( $this->f_numrows($rs) ){
				$arr = $this->f_array($rs);
				if( $arr['cod_servicio'] == $this->codigo ){
					$this->query($sqlM);
					return true;
				}
			}
			else{
				$this->query($sqlM);
				return true;
			}
		}

		function eliminar(){
			$sql = "DELETE FROM servicio WHERE cod_servicio = '$this->codigo'";
			$this->query($sql);
			if( $this->f_affectrows() ){
				return true;
			}
		}

		function consultar(){
			$sql = "SELECT * FROM servicio WHERE cod_servicio = '$this->codigo' OR nom_servicio = '$this->nombre'";
			$rs = $this->query($sql);
			if( $this->f_numrows($rs) ){
				return $this->f_array($rs);
			}
		}

		function consultar_por_nombre(){ # consulta por nombre de servicio y modulo
			$sql = "SELECT * FROM servicio WHERE nom_servicio = '$this->nombre' AND cod_modulo = '$this->modulo'";
			return $this->array_assoc( $this->query($sql) );
		}

		# Permite listar sin limites, y con limites para paginación
		function listar($limite='no',$todo='si',$desde=0,$mostrar=15,$filtrar='no'){
			$campos = "cod_servicio, nom_servicio, servicio.cod_modulo, nom_modulo, servicio.icono, servicio.estatus, link, mostrar_menu, servicio.pos";
			$join = "modulo ON modulo.cod_modulo = servicio.cod_modulo";

			# condiciones de filtros
			$condFtodo = "nom_servicio LIKE '$this->filtro%'";
			$condFMdlo = "nom_servicio LIKE '$this->filtro%' AND servicio.cod_modulo = '$this->modulo'";

			switch ($limite) {
				# Resultados con límites (Para las datatables con paginación)
				case 'si':
					# Todos los servicios
					if($todo == 'si'){

						if( $filtrar == 'no' ){
							$sqlF = "SELECT count(cod_servicio) FROM servicio";
							$this->filas = $this->total_filas($sqlF); # total de registros encontrados
							$sql = "SELECT $campos FROM servicio INNER JOIN $join ORDER BY servicio.cod_modulo ASC LIMIT $desde, $mostrar";
						}

						else{
							$sqlF = "SELECT count(cod_servicio) FROM servicio WHERE $condFtodo";
							$this->filas = $this->total_filas($sqlF); # total de registros encontrados
							$sql = "SELECT $campos FROM servicio INNER JOIN $join WHERE $condFtodo ORDER BY servicio.cod_modulo ASC LIMIT $desde, $mostrar";
						}
					}

					# servicios por módulos
					else{ 
						if( $filtrar == 'no' ){
							$sqlF = "SELECT count(cod_servicio) FROM servicio WHERE cod_modulo = '$this->modulo'";
							$this->filas = $this->total_filas($sqlF); # total de registros encontrados
							$sql = "SELECT $campos FROM servicio INNER JOIN $join WHERE servicio.cod_modulo = '$this->modulo' ORDER BY servicio.cod_modulo ASC LIMIT $desde, $mostrar";
						}
						else{
							$sqlF = "SELECT count(cod_servicio) FROM servicio WHERE $condFMdlo";
							$this->filas = $this->total_filas($sqlF); # total de registros encontrados
							$sql = "SELECT $campos FROM servicio INNER JOIN $join WHERE $condFMdlo ORDER BY servicio.cod_modulo ASC LIMIT $desde, $mostrar";
						}	
					}
					break;

				# Resultados sin límites para otros usos
				default:
					# Todos los servicios
					if($todo == 'si'){
						$sql = "SELECT $campos FROM servicio INNER JOIN $join ORDER BY pos ASC";
					}
					else{
						$sql = "SELECT $campos FROM servicio INNER JOIN $join WHERE servicio.cod_modulo = '$this->modulo' ORDER BY pos ASC";
					}
					break;
			}
			#echo $sql;
			$rs = $this->query($sql);
			return $this->retorna_arreglo($rs);
		}

		function listar_menu($nivel, $modulo){ # Lista los servicios con restricciones de nivel de usuario
			$sql = "SELECT DISTINCT servicio.cod_servicio, nom_servicio, icono, estatus, link, mostrar_menu, pos FROM servicio
			INNER JOIN nivel_metodo ON nivel_metodo.cod_servicio = servicio.cod_servicio 
			WHERE cod_nivel = '$nivel' AND cod_modulo='$modulo' ORDER BY servicio.pos ASC";
			#echo $sql;
			$rs = $this->query($sql);
			$lista = array(); $cont = 0;
			while( $fila = $this->f_array($rs) ){
				$lista[$cont]['cod'] = $fila['cod_servicio']; 
				$lista[$cont]['nom'] = $fila['nom_servicio']; 
				$lista[$cont]['ico'] = $fila['icono'];
				$lista[$cont]['sta'] = $fila['estatus'];
				$lista[$cont]['link'] = $fila['link'];
				$lista[$cont]['mostrar_menu'] = $fila['mostrar_menu'];
				$lista[$cont]['pos'] = $fila['pos'];
				$cont++;
			}
			return $lista;
		}

		function retorna_arreglo($rs){
			$lista = array(); $cont = 0;
			while( $fila = $this->f_array($rs) ){
				$lista[$cont]['cod'] = $fila['cod_servicio']; 
				$lista[$cont]['nom'] = $fila['nom_servicio']; 
				$lista[$cont]['mdlo'] = $fila['cod_modulo'];
				$lista[$cont]['nmdlo'] = $fila['nom_modulo']; 
				$lista[$cont]['ico'] = $fila['icono'];
				$lista[$cont]['sta'] = $fila['estatus'];
				$lista[$cont]['link'] = $fila['link'];
				$lista[$cont]['mostrar_menu'] = $fila['mostrar_menu'];
				$lista[$cont]['pos'] = $fila['pos'];
				$cont++;
			}
			return $lista;
		}

		function servicios_nivel(){ # consulta los servicios por nivelde usuario
			$campos = "cod_servicio, nom_servicio, servicio.cod_modulo, nom_modulo, servicio.icono, servicio.estatus";
			$join = "modulo ON modulo.cod_modulo = servicio.cod_modulo";
			$sql = "SELECT $campos INNER JOIN $join WHERE";
		}

		# IMPRIMIR HTML
		function combo_option($modulo=false, $selected="0"){
			# parametro modulo: si se quiere filtrar por modulo
			if( $modulo == false){
				$rs = $this->listar();
			}
			else{
				#listar($limite='no',$todo='si',$desde=0,$mostrar=15,$filtrar='no'){
				$rs = $this->listar('no','no');
			}

			for ($i=0; $i<count($rs); $i++) {
				$s = '';	
				if($selected == $rs[$i]['cod']){
					$s = 'selected';
				} 
				echo '<option value="'.$rs[$i]['cod'].'" '.$s.'>'.$rs[$i]['nom'].'</option>';
			}
		}
	}

	# clase nivel-metodo
	class cls_metodo extends MyDB{
		private $codigo, $codNivel, $codServicio, $inc, $modf, $elm, $cons;
		function __construct(){
			parent::__construct(); 
			$this->codigo = null;
			$this->codNivel = null; 
			$this->codServicio = null; 
			$this->inc = null; 
			$this->modf = null; 
			$this->elm = null; 
			$this->cons = null;
		}

		function set($codN, $codS, $i=null, $m=null, $e=null, $c=null){
			$this->codNivel = $codN;
			$this->codServicio = $codS;
			$this->inc = $i;
			$this->modf = $m;
			$this->elm = $e;
			$this->cons = $c;
		}

		function setNivel($nivel){
			$this->codNivel=$nivel;
		}

		function incluir(){
			$codigo = $this->codNivel.'-'.$this->codServicio; # concatenación de codigo de nivel y codigo de servicio
			$sql = "INSERT INTO nivel_metodo (codigo, cod_nivel,cod_servicio) VALUES ('$codigo','$this->codNivel','$this->codServicio')";
			$this->query($sql);
			if( $this->f_affectrows() ){
				return true;
			}
		}

		function modificar(){
			$sql = "UPDATE nivel_metodo SET inc='$this->inc', modf='$this->modf', elm='$this->elm', cons='$this->cons'
			WHERE cod_nivel='$this->codNivel' AND cod_servicio='$this->codServicio'";
			$this->query($sql);
		}

		function eliminar(){
			$sql = "DELETE FROM nivel_metodo WHERE cod_nivel='$this->codNivel' AND cod_servicio='$this->codServicio'";
			$this->query($sql);
			if( $this->f_affectrows() ){
				return true;
			}
		}

		function consultar(){
			$sql = "SELECT * FROM nivel_metodo WHERE cod_nivel='$this->codNivel' AND cod_servicio='$this->codServicio'";
			$rs = $this->query($sql);
			if( $this->f_numrows($rs) ){
				return $this->f_array($rs);
			}
		}

		# consulta por codigo de usuario y nombre del servicio
		function consultar2($nivel, $nomSer){
			$sql = "SELECT cod_nivel, nivel_metodo.cod_servicio, inc, modf, elm, cons FROM nivel_metodo INNER JOIN servicio ON servicio.cod_servicio = nivel_metodo.cod_servicio WHERE cod_nivel='$nivel' AND servicio.nom_servicio='$nomSer'";
			if( $rs = $this->query($sql) ){
				return $this->f_array($rs);
			}
		}

		function listar(){
			$sql = "SELECT cod_servicio,nivel_metodo.cod_nivel,nom_nivel,inc,modf,elm,cons FROM nivel_metodo INNER JOIN nivel ON nivel.cod_nivel = nivel_metodo.cod_nivel WHERE cod_servicio='$this->codServicio'";
			$rs = $this->query($sql);
			if( $this->f_numrows($rs) ){
				$lista = array(); $cont=0;
				while( $fila = $this->f_array($rs) ){
					$lista[$cont]['codS'] = $fila['cod_servicio'];
					$lista[$cont]['codN'] = $fila['cod_nivel'];
					$lista[$cont]['nomN'] = $fila['nom_nivel'];
					$lista[$cont]['inc'] = $fila['inc'];
					$lista[$cont]['mod'] = $fila['modf'];
					$lista[$cont]['elm'] = $fila['elm'];
					$lista[$cont]['con'] = $fila['cons'];
					$cont++;
				}
				return $lista;
			}
		}

		function listar_2(){ # para el menu del sistema
			$sql = "SELECT nivel_metodo.codigo, 
			nivel_metodo.cod_nivel, 
	        nivel_metodo.cod_servicio,
	        servicio.cod_modulo, inc, modf, elm, cons 
	        FROM nivel_metodo INNER JOIN servicio ON nivel_metodo.cod_servicio = servicio.cod_servicio WHERE nivel_metodo.cod_nivel = '$this->codNivel'";
		
			$rs = $this->query($sql);
			if( $this->f_numrows($rs) ){
				$lista = array(); $cont=0;
				while( $fila = $this->f_array($rs) ){
					$lista[$cont]['cod'] = $fila['cod_servicio'];
					$lista[$cont]['codN'] = $fila['cod_nivel'];
					$lista[$cont]['inc'] = $fila['inc'];
					$lista[$cont]['mod'] = $fila['modf'];
					$lista[$cont]['elm'] = $fila['elm'];
					$lista[$cont]['con'] = $fila['cons'];
					$lista[$cont]['codM'] = $fila['cod_modulo'];
					$cont++;
				}
				return $lista;
			}
		}

		# IMPRIMIR HTML >>>>>>><
		# imprime los roles que no están asociados al servicio para agregarlos
		function combo_rol_servicio(){ 
			include_once('m_rol.php');
			$obj = new cls_rol();
			$rs = $obj->listar(); # lista todos los roles
			for ($i=0; $i<count($rs); $i++) { 
				$this->set( $rs[$i]['cod'], $this->codServicio );
				if( !$this->consultar() ){
					echo '<option value="'.$rs[$i]['cod'].'">'.$rs[$i]['nom'].'</option>';	
				}
			}
		}
	}
?>