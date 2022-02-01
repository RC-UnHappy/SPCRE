<?php 
# Seguridad >>>>>>>
include_once('seguridadDinamica.php');
# Servicio representante
$rsMetodo = consultarMetodos('Representante');
$sI = '1';
$sM = '1';
$sC = '1';
$sE = '1';

# Es diferente al administrador central
if( $_SESSION['vsn_nivel'] != 1 ){ 
	$sI = $rsMetodo['inc'];
	$sM = $rsMetodo['modf'];
	$sC = $rsMetodo['cons'];
	$sE = $rsMetodo['elm'];
}
# Seguridad <<<<<<

include_once('../MODELO/m_representante.php');
include_once('../CONTROL/c_lugares.php');
include_once('../CONTROL/c_grado_instruccion.php');

$objRep = new cls_representante();
# VARIABLES del Formulario Representante

$codPer = '';
$url_foto = '../IMG/avatar.jpg';
$tipoDoc = '';
$ciPer = '';
$ced = '';
$nom = '';
$ape = '';
$sex = '';
$fnac = ''; # fecha de nacimiento
$nac = ''; # nacionalidad
$obs = '';
# Direccion de dominicilio
$edoDom = ''; # Estado Portuguesa
$munDom = '0'; # municipio Araure
$parrDom = '';
$sector = '';
$calle = '';
$nroCasa = '';
# contacto
$tlfm = '';
$tlff = '';
$email = '';
# otros datos
$grdIns = '';
$ocup = '';
$trabaja = '';
$dirTrbjo = '';
$parrTrbjo = '';
$munTrbjo = '0';
$edoTrbjo = '0';
$tlft = '';

$personal = ''; # si la persona es un personal, personal = true

if( isset($_POST['opeRep']) ){ # Operador en el formulario Representante
	# datos personales
	$codPer = $_POST['codPer'];
	$tipoDoc = $_POST['tipo_doc'];
	$ciPer = $_POST['ciPer'];
	$ced = $_POST['ced'];
	$nom = $_POST['nom'];
	$ape = $_POST['ape'];
	$sex = $_POST['sex'];
	$fnac = $_POST['fnac'];
	$nac = $_POST['nac'];
	# Direccion de domicilio
	$domParr = $_POST['domParr'];
	$sector = $_POST['sector'];
	$calle = $_POST['calle'];
	$nroCasa = $_POST['nroCasa'];
	# Contacto
	$tlfm = $_POST['tlfm'];
	$tlff = $_POST['tlff'];
	$email = $_POST['email'];
	# Otros datos
	$grdIns = $_POST['grdIns'];
	$ocup = $_POST['ocup'];
	$obs = $_POST['obs'];
	$trabaja = $_POST['trabaja'];
	# datos del trabajo
	$tParr = $_POST['tParr']; # parroquia de trabajo
	$dirTrbjo = $_POST['dirTrbjo']; # direccion de trabajo
	$tlft = $_POST['tlft']; # telefono de trabajo
	
	# pasa valor a las propiedades de la clase
	$objRep->set_datosRep($tipoDoc,$ced,$nom,$ape,$sex,$fnac,$nac,$email,$grdIns,$ocup,$obs);
	$objRep->set_foto($_FILES['foto']);

	switch ( $_POST['opeRep'] ) {
		case 'reg': # registrar
			if( $rs1 = $objRep->consultar_persona() ){ # existe la persona
			
				if( $objRep->comprobar_representante() ){
					# Error de existencia en la tabla represenante
					header('location: ../VISTA/?Representante=registrar&error=cedula');
				}
				if( $objRep->comprobar_personal() ){
					# Existe la persona pero no en la tabla representante, entonces es un personal
					# Registra en la tabla representante y modifica en la tabla persona
					$objRep->set_codigoPersona($rs1['cod_per']);
					$objRep->registrar_modificar();
					$objRep->agregar_direccion($domParr,$sector,$calle,$nroCasa,'D'); # domicilio
					# modifica los telefonos
					$objRep->modificar_telefono($tlfm,'M');
					$objRep->modificar_telefono($tlff,'F');
					# registra direccion de trabajo
					if( $trabaja == 'si' ){
						$objRep->agregar_direccion($tParr,$dirTrbjo,'','','T'); # trabajo
						$objRep->agregar_telefono($tlft,'T'); # trabajo
					}
					header('location: ../VISTA/?Representante=visualizar&cedula='.$tipoDoc.'-'.$ced.'&reg=true');	
				}			
			}
			else{
				# Registra nueva persona y representante
				$objRep->registrar_representante(); # registra los datos de la persona
				$objRep->agregar_direccion($domParr,$sector,$calle,$nroCasa,'D');
				$objRep->agregar_telefono($tlfm,'M');
				$objRep->agregar_telefono($tlff,'F');

				if( $trabaja == 'si' ){
					$objRep->agregar_direccion($tParr,$dirTrbjo,'','','T');
					$objRep->agregar_telefono($tlft,'T');
				}
				header('location: ../VISTA/?Representante=visualizar&cedula='.$tipoDoc.'-'.$ced.'&reg=true');
			}
			break;
		
		case 'mod': # modificar
			$rs = $objRep->consultar_persona();
			if( $rs['cedula'] && $rs['cod_per'] != $codPer ){ # Ya existe
				header('location: ../VISTA/?Representante=visualizar&cedula='.$ciPer.'&error=cedula');
			}
			else if( $rs['cod_per'] == $codPer || !$rs['cedula'] ){ # es la misma persona, o no existe la cédula
				$objRep->set_codigoPersona($codPer);
				$objRep->modificar_representante();
				$objRep->modificar_direccion($domParr,$sector,$calle,$nroCasa,'D');
				$objRep->modificar_telefono($tlfm,'M');
				$objRep->modificar_telefono($tlff,'F');

				if( $trabaja == 'no' ){
					$objRep->eliminar_direccion('T');
					$objRep->eliminar_telefono('T');
				}

				else{
					$objRep->modificar_telefono($tlft,'T');
					$objRep->modificar_direccion($tParr,$dirTrbjo,'','','T');
				}

				$rsCed = $objRep->consultar_persona2();
				header('location: ../VISTA/?Representante=visualizar&cedula='.$rsCed['tipo_documento'].'-'.$rsCed['cedula'].'&mod=true');
			}
			break;
	}
}


else if(isset( $_POST['ajax']) ){
	$objRep->set_identidad($_POST['td'],$_POST['ced']);
	
	if( $rs1 = $objRep->consultar_persona() ){
		if($rs3 = $objRep->comprobar_representante() ){
			echo 'ya existe'; # ya existe
		}
		else if( $rs2 =  $objRep->comprobar_personal() ){
			echo $rs1['nom1'].'%'.$rs1['ape1'].'%'.$rs1['email'];
			$objRep->set_codigoPersona($rs1['cod_per']);
			$tlf = $objRep->consultar_telefono('M');
			echo '%'.$tlf; 
		}
	}
	else{
		echo 'no existe';
	}
}

if( isset($_GET['Representante'] )){
	$vget = $_GET['Representante'];
	# no posee ninguno de estos valores, vuelve atras.
	if($vget!='visualizar' && $vget!='registrar' && $vget!='consultar'){
		header('location: ?Representante=consultar'); 
	}

	if( $_GET['Representante'] == 'visualizar' ){
		if( isset($_GET['cedula']) ){
			$getCed = substr($_GET['cedula'],2); # tipo de documento
			$getTipoDoc = substr($_GET['cedula'], 0,1); # cedula
			$objRep->set_identidad($getTipoDoc,$getCed); 

			if( $rs = $objRep->consultar_representante() ){ # existen datos en la tabla representante
				include_once('../MODELO/m_personal.php'); 
				$ObjPersonal = new cls_personal();
				$ObjPersonal->set_identidad($getTipoDoc,$getCed); 
				
				if( $ObjPersonal->consultar_personal2() ){ # consulta en la tabla personal
					$personal = 'true'; # el representante es un personal
				}

				$codPer = $rs['cod_per'];
				if( $rs['foto'] != '' ){
					$url_foto = '../upload/'.$rs['foto']; 
				}

				$ciPer = $rs['cedula'];
				$ced = $ciPer;
				$nom = $rs['nom1'];
				$ape = $rs['ape1'];
				$sex = $rs['sexo'];
				$fnac = $rs['fecha_nac'];
				$nac = $rs['nacionalidad'];
				$email = $rs['email'];
				$grdIns = $rs['cod_ginst'];
				$ocup = $rs['cod_ocup'];
				$obs = $rs['observacion'];

				$objRep->set_codigoPersona( $rs['cod_per'] ); # pasa la cedula a la propiedad de la clase persona
				$arr_dirs = $objRep->consultarDirecciones(); # cunsulta las direcciones
				# Dirección de Domicilio, pasa valor a variables
				$edoDom = $arr_dirs[0]['codEdo'];
				$munDom = $arr_dirs[0]['codMun'];
				$parrDom = $arr_dirs[0]['codParr'];
				$sector = $arr_dirs[0]['sector'];
				$calle = $arr_dirs[0]['calle'];
				$nroCasa = $arr_dirs[0]['nro'];
				
				if( isset( $arr_dirs[1]['codParr'] ) ){ # existe la direccion de trabajo
					$trabaja = 'si';
					$dirTrbjo = $arr_dirs[1]['sector'];
					$parrTrbjo = $arr_dirs[1]['codParr'];
					$munTrbjo = $arr_dirs[1]['codMun'];
					$edoTrbjo = $arr_dirs[1]['codEdo'];
				}else{
					$trabaja = 'no';
				}
				$arr_tlfns = $objRep->consultarTelefonos(); # consulta los telefonos
				for ($i=0; $i<count($arr_tlfns); $i++) {
					switch ($arr_tlfns[$i]['tipo']) { # compara el tipo de telefono
						case 'T':
							$tlft = $arr_tlfns[$i]['num']; # pasa valor a las variables
							break;

						case 'F':
							$tlff = $arr_tlfns[$i]['num'];
							break;
						
						case 'M':
							$tlfm = $arr_tlfns[$i]['num'];
							break;
					}	
				} 
			}
			else{
				header('location: ?Representante=consultar&error=consulta'); # no existen datos, back.
			}
		}
		else{
			header('location: ?Representante=consultar'); # back
		}
	}
}

# Consulta los datos del Representante tomando la cédula de la URL
function consultar_representados(){
	# Consulta los representados de una persona en el año escolar activo.
	include_once('../MODELO/m_a_escolar.php');
	$objAesc = new cls_a_escolar();
	$rs = $objAesc->aesc_actual();
	global $codPer, $objRep;
	if ($rs = $objRep->representados($codPer,$rs['cod_periodo']) ){
		for ($i=0; $i<count($rs); $i++) { 
			$estatus = $rs[$i]['estatus'];
			switch ($estatus) {
				case '1':
					$estatus = '<div class="td_verde">Nuevo Ing.</div>';
					break;
				
				case '2':
					$estatus = '<div class="td_verde">Regular</div>';
					break;

				case '3':
					$estatus = '<div class="td_rojo">Retirado</div>';
					break;

				case '4':
					$estatus = '<div class="td_amarillo">Graduado</div>';
					break;
			}
			echo '<tr>';
			echo '<td>'.($i+1).'</td>';
			echo '<td>'.$rs[$i]['ced'].'</td>';
			echo '<td align="left">'.$rs[$i]['nom_ape'].'</td>';
			echo '<td><b>'.$rs[$i]['grado_sec'].'</b></td>';
			echo '<td><b>'.$estatus.'</b></td>';
			echo '<td align="center"><a target="__blank" href="?Estudiante=visualizar&cedEscolar='.$rs[$i]['ced'].'"><div class="acciones"><i class="icon-eye"></i></div></a></td>';
			echo '</tr>';
		}
	}
	else{
		echo '<td colspan="6">Sin resultados</td>';
	}
}

function listarOpOcup($valor){ # imprime elementos options con las ocupaciones
	include_once('../MODELO/m_ocupacion.php');
	$objOcup = new cls_ocupacion();
	$rs = $objOcup->listar();
	for($i=0;$i<count($rs);$i++){
		if( $valor ==  $rs[$i]['cod'] ){
			echo '<option value="'.$rs[$i]['cod'].'" selected>'.$rs[$i]['nom'].'</option>';
		}
		else{
			echo '<option value="'.$rs[$i]['cod'].'">'.$rs[$i]['nom'].'</option>';
		}
	}
}
?>