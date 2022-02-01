<?php 
	require_once('MyDB.php');
	require_once('m_telefono.php');
	require_once('m_direccion.php');
	
	// CLASE usuario/personal
	class cls_persona extends MyDB{ # hija de la clase MyBD
		#Propiedades de la clase 
		protected $codPer,$tipo_documento,$cedula,$nom1,$nom2,$ape1,$ape2,$sexo,$nac,$fnac,$email,$foto;
		protected $objTlf, $objDir;
		protected $tlfm, $tlff, $tlft; # telefonos

		function __construct(){
			# inicializacion de propiedades
			parent::__construct(); # constructor del padre
			$this->codPer = null;
			$this->tipo_documento = null;
			$this->cedula = null;
			$this->nom1 = null;
			$this->nom2 = null;
			$this->ape1 = null;
			$this->ape2 = null;
			$this->sexo = null;
			$this->nac = null;
			$this->fnac = null;
			$this->email = null;
			$this->foto = null;
			$this->objDir = new cls_direccion();
			$this->objTlf = new cls_telefono();
		}
		# ------------------ TABLA PERSONA --------------------

		function set_codigoPersona($cod){
			$this->codPer = trim($cod);
		}

		function set_cedulaPersona($ced){
			$this->cedula = trim($ced);
		}

		function set_identidad($doc_id, $ced){
			$this->tipo_documento = $doc_id;
			$this->cedula = trim($ced);
		}

		function set_foto($foto){
			$this->foto = $foto;
		}

		function set_datosPersona($ced,$nom1,$nom2,$ape1,$ape2,$sex,$nac,$fnac,$ema){
			# pasa valor a las propiedades de la clase
			$this->cedula = trim($ced);
			$this->nom1 = mb_strtoupper($this->limpiarCadena($nom1)); # limpia los espacios en blanco sobrantes y todo mayuscula
			$this->nom2 = mb_strtoupper($this->limpiarCadena($nom2));
			$this->ape1 = mb_strtoupper($this->limpiarCadena($ape1));
			$this->ape2 = mb_strtoupper($this->limpiarCadena($ape2));
			$this->sexo = $sex;
			$this->nac = $nac;
			$this->fnac = $fnac;
			$this->email = strtolower(trim($ema));
			# importante para no incluir nombres o apellidos con comillas simples
			$this->nom1 = str_replace("'", '%', $this->nom1);
			$this->nom2 = str_replace("'", '%', $this->nom2);
			$this->ape1 = str_replace("'", '%', $this->ape1);
			$this->ape2 = str_replace("'", '%', $this->ape2);
		}

		function registrar_persona(){ # registra una persona
			$sql = "INSERT INTO persona (tipo_documento,cedula,nom1,nom2,ape1,ape2,sexo,nacionalidad,fecha_nac,email)
			VALUES ('$this->tipo_documento','$this->cedula','$this->nom1','$this->nom2','$this->ape1','$this->ape2','$this->sexo',
			'$this->nac','$this->fnac','$this->email')";
			$this->query($sql);
			if( $this->f_affectrows() ){
				# aqui no se cierra la conexion porque se necesita el ultimo ID
				return true;
			}
		}

		# modifica los datos de una persona
		function modificar_persona(){ 
			$sql = "UPDATE persona SET tipo_documento='$this->tipo_documento', cedula='$this->cedula',nom1='$this->nom1',nom2='$this->nom2',ape1='$this->ape1',ape2='$this->ape2',
			sexo='$this->sexo',nacionalidad='$this->nac',fecha_nac='$this->fnac',email='$this->email' 
			WHERE cod_per = '$this->codPer'";
			$this->query($sql);
			$this->subir_foto();
			$this->desconectar();
		}

		function subir_foto(){ 
			if( $this->foto['name'] != '' ){
				$type = substr($this->foto['type'], 6); # tipo de archivo
				$this->foto['name'] = $this->codPer.'.'.$type; # cambiamos el nombre del archivo por el codigo de la persona conservando su extensión
				$name = $this->foto['name'];
				$dir = '../upload/'.$this->foto['name']; # direccion en donde se subirá el archivo
				move_uploaded_file($this->foto['tmp_name'], $dir);
				$sql = "UPDATE persona SET foto = '$name' WHERE cod_per = '$this->codPer'";
				$this->query($sql);
			}
		}

		function consultar_persona(){
			# selecciona una de la tabla Persona
			$sql = "SELECT * FROM persona WHERE tipo_documento = '$this->tipo_documento' AND cedula = '$this->cedula'";
			$result = $this->query($sql);
			if( $result ){ # existen datos
				$this->desconectar();
				return $this->f_array($result); # devuelve un arreglo
			}
		}

		function consultar_persona2(){
			# consulta una persona por código
			$sql = "SELECT * FROM persona WHERE cod_per = '$this->codPer'";
			$arr = $this->f_array( $this->query($sql) );
			$this->desconectar();
			return $arr;
		}

		function consultarTelefonos(){
			# selecciona los telefonos de la persona
			$lista = array(); $cont=0;
			$sql = "SELECT numero, tipo FROM telefono_persona 
			INNER JOIN telefono ON telefono_persona.cod_tlf=telefono.cod_tlf WHERE cod_per = '$this->codPer'";
			$res = $this->query($sql);
			while($fila = $this->f_array($res)){
				$lista[$cont]['num'] = $fila['numero'];
				$lista[$cont]['tipo'] = $fila['tipo'];
				$cont++; 
			}
			$this->desconectar();
			return $lista; # devuelve un array
		}

		# consulta telefono por el tipo
		function consultar_telefono($t){
			$sql = "SELECT numero FROM telefono_persona 
			INNER JOIN telefono ON telefono_persona.cod_tlf=telefono.cod_tlf 
			WHERE cod_per = '$this->codPer' AND tipo = '$t'";
			$rs = $this->query($sql);
			$this->desconectar();
			if( $this->f_numrows($rs) ){
				return $this->f_array($rs)[0];
			}
		}

		function consultarDirecciones(){ # consulta las direcciones de una persona y devuelve un arreglo asociativo
			$lista = array(); $cont=0;
			$sql = "SELECT sector,calle,nro,tipo_dir,cod_parr,nom_parr,cod_mun,nom_mun,cod_edo,nom_edo FROM persona 
			INNER JOIN direccion_persona ON direccion_persona.cod_per = persona.cod_per
			INNER JOIN direccion ON direccion_persona.cod_dir = direccion.cod_dir
			INNER JOIN parroquia ON direccion.parroquia = parroquia.cod_parr
			INNER JOIN municipio ON parroquia.municipio = municipio.cod_mun
			INNER JOIN estado ON municipio.estado = estado.cod_edo
			WHERE persona.cod_per = '$this->codPer'";
			$res = $this->query($sql);
			$this->desconectar();
			while( $fila = $this->f_array($res) ){
				$lista[$cont]['tipo'] = $fila['tipo_dir'];
				$lista[$cont]['sector'] = $fila['sector'];
				$lista[$cont]['calle'] = $fila['calle'];
				$lista[$cont]['nro'] = $fila['nro'];
				$lista[$cont]['codParr'] = $fila['cod_parr'];
				$lista[$cont]['nomParr'] = $fila['nom_parr'];
				$lista[$cont]['codMun'] = $fila['cod_mun'];
				$lista[$cont]['nomMun'] = $fila['nom_mun'];
				$lista[$cont]['codEdo'] = $fila['cod_edo'];
				$lista[$cont]['nomEdo'] = $fila['nom_edo'];
				$cont++;
			}
			return $lista;
		}
		function consultar_direccion($codPer='$this->codPer', $t){
			$sql = "SELECT sector,calle,nro,tipo_dir,cod_parr,nom_parr,cod_mun,nom_mun,cod_edo,nom_edo FROM persona 
			INNER JOIN direccion_persona ON direccion_persona.cod_per = persona.cod_per
			INNER JOIN direccion ON direccion_persona.cod_dir = direccion.cod_dir
			INNER JOIN parroquia ON direccion.parroquia = parroquia.cod_parr
			INNER JOIN municipio ON parroquia.municipio = municipio.cod_mun
			INNER JOIN estado ON municipio.estado = estado.cod_edo
			WHERE persona.cod_per = '$codPer' AND tipo_dir = '$t'";
			$arr = $this->f_array($this->query($sql));
			$this->desconectar();
			return $arr;
		}

		#------------------ TABLA TELEFONO-PERSONA ----------------------
			
		# NOTA : lugar_tlf: M=movil, F=fijo, T=trabajo

		# Permite agregar un telefono de la persona
		function agregar_telefono($num, $tipo){
			if( trim($num) != '' ){
				$this->objTlf->set_telefono($num); # pasa el telefono a la propiedad de la clase cls_telefono
				$cod_tlf = $this->objTlf->get_codigoTlf(); # se obtiene el codigo del teléfono
				$sql = "INSERT INTO telefono_persona (cod_tlf,cod_per,tipo)VALUES('$cod_tlf','$this->codPer','$tipo')";
				$this->query($sql);
				$this->desconectar();
			}
		}

		# Permite modificar un telefono de la persona
		function modificar_telefono($num, $tipo){ 
			$num = trim($num);
			if( $this->buscar_telefono($tipo) ){ # existe el numero de telefono de la persona
				if( $num == '' ){
					$this->eliminar_telefono($tipo);
				}
				else{
					$this->objTlf->set_telefono($num); # pasa el telefono a la propiedad de la clase cls_telefono
					$cod_tlf = $this->objTlf->get_codigoTlf(); # se obtiene el codigo del teléfono
					$sql = "UPDATE telefono_persona SET cod_tlf='$cod_tlf' WHERE cod_per='$this->codPer' AND tipo='$tipo'";
					$this->query($sql);
					$this->desconectar();
				}
			}
			else if( $num != '' ){
				$this->agregar_telefono($num,$tipo); # si no existe el telefono y el campo no está vacio, agrega el telefono
			}
		}

		# Elimina un teléfono de la Persona
		function eliminar_telefono($tipo){
			$sql = "DELETE FROM telefono_persona WHERE cod_per='$this->codPer' AND tipo='$tipo'";
			$this->query($sql);
			$this->desconectar();
		}

		# Comprueba un numero de telefono de la persona
		function buscar_telefono($tipo){ 
			$sql = "SELECT * FROM telefono_persona WHERE cod_per='$this->codPer' AND tipo='$tipo'";
			$res = $this->query($sql);
			if( $this->f_numrows($res) ){
				$this->desconectar();
				return true;
			}
		}

		# ----------------- TABLA DIRECCION PERSONA -----------------
		# NOTA: tipo: D = domicilio, T = trabajo
		# registra una dirección en la tabla direccion_persona
		function agregar_direccion($parr,$sector,$calle,$nro,$tipo){ 
			$this->objDir->set_direccion($parr,$sector,$calle,$nro);
			$codDir = $this->objDir->get_codigo();
			$sql = "INSERT INTO direccion_persona(cod_dir,cod_per,tipo_dir) VALUES ('$codDir','$this->codPer','$tipo')";
			$this->query($sql);
			$this->desconectar();
		}
		# modifica una dirección de una persona
		function modificar_direccion($parr,$sector,$calle,$nro,$tipo){
			if( $this->buscar_direccion($tipo) ){ # existe la direccion de la persona
				$this->objDir->set_direccion($parr,$sector,$calle,$nro);
				$codDir = $this->objDir->get_codigo();
				$sql = "UPDATE direccion_persona SET cod_dir='$codDir' WHERE cod_per='$this->codPer' AND tipo_dir='$tipo'";
				$this->query($sql);
				$this->desconectar();
			}
			else if( $sector != '' ){
				$this->agregar_direccion($parr,$sector,$calle,$nro,$tipo);
			}
		}	
		# elimina una dirección de una persona
		function eliminar_direccion($tipo){
			$sql = "DELETE FROM direccion_persona WHERE cod_per = '$this->codPer' AND tipo_dir = '$tipo'";
			$this->query($sql);
			$this->desconectar();
		}
		# comprueba que exista una dirección
		function buscar_direccion($tipo){
			$sql = "SELECT * FROM direccion_persona WHERE cod_per='$this->codPer' AND tipo_dir='$tipo'";
			if( $this->f_numrows( $this->query($sql) ) ){
				$this->desconectar();
				return true;
			}
		}

		# Verifica si ya existe el personal
		function comprobar_personal(){ 
			$sql = "SELECT cedula, persona.cod_per FROM personal INNER JOIN persona ON personal.cod_per = persona.cod_per 
			WHERE tipo_documento = '$this->tipo_documento' AND cedula = '$this->cedula'";	
			if( $this->f_numrows($this->query($sql)) ){
				$this->desconectar();
				return true;
			}
		}
		
		function comprobar_representante(){ # verifica si el personal ya se encuentra en la tabla representante
			$sql = "SELECT * FROM representante INNER JOIN persona ON representante.cod_per = persona.cod_per
			WHERE tipo_documento = '$this->tipo_documento' AND cedula = '$this->cedula'";
			$rs = $this->query($sql);
			$this->desconectar();
			if( $rs ){ # existen datos
				return $this->f_array($rs); # devuelve arreglo
			}
		}
	}
?>