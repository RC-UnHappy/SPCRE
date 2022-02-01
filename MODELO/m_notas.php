<?php 
	include_once('MyDB.php');

	class cls_notas extends MyDB{
		# Estudiante - Notas Indicador
		private $insc, $CE; # estudiante
		private $ind; # codigo indicador
		private $nota;
		# Estudiante - Promedio Lapso
		private $notaLapso;
		# Estudiante - Nota Final
		private $descripcion, $recomendacion, $notaF, $promovido; 
		# promedios
		private $lapso, $promedio;
		# periodo escolar
		private $periodo;
		private $codPA;

		function __construct(){
			parent::__construct(); # constructor del padre
			$this->insc = null;
			$this->CE = null;
			$this->ind = null;
			$this->nota = null;
			$this->lapso = null;
			$this->promedio = null;
			$this->notaLapso = null;
			$this->aesc = null;
			$this->descripcion = null;
			$this->recomendacion = null;
			$this->notaF = null;
			$this->promovido = null; 
			$this->periodo = null;
			$this->codPA = null;
		}

		# set codigo de inscripción
		function setInsc($insc){
			$this->insc = $insc;
		}

		# set cedula escolar o cedula
		function setCE($ce){
			$this->CE = $ce;
		}

		function set($insc,$ind,$nota){
			$this->insc = $insc;
			$this->ind = $ind;
			$this->nota = $nota;
		}

		function setPeriodo($p){
			$this->periodo = $p;
		}

		function setPA($a){
			$this->codPA = $a;
		}

		# NOTAS POR INDICADOR
		function incluir(){
			$sql = "INSERT INTO nota_indicador (cod_insc,cod_ind,nota) VALUES ('$this->insc','$this->ind','$this->nota')";
			$this->query($sql);
		}

		function modificar(){
			$sql = "UPDATE nota_indicador SET nota = '$this->nota' WHERE cod_insc = '$this->insc' AND cod_ind = '$this->ind'";
			$this->query($sql);
		}

		# consulta nota de un indicador por codigo de inscripcion
		function consultar(){
			$sql = "SELECT nota FROM nota_indicador 
			WHERE cod_insc = '$this->insc' AND cod_ind = '$this->ind'";
			$rs = $this->f_array($this->query($sql));
			return $rs['nota'];
		}

		# consulta todas los indicadores con sus respectiva nota por codigo de inscripcion
		function consultar_notas_indicadores(){
			$sql = "SELECT nom_ind, nota FROM nota_indicador 
			INNER JOIN indicador ON nota_indicador.cod_ind = indicador.cod_ind
			WHERE cod_insc = '$this->insc' AND cod_proyecto = '$this->codPA'";
			$rs = $this->query($sql);
			$lista = array(); $cont=0;
			while( $fila = $this->f_array($rs) ){
				$lista[$cont]['nom_ind'] = $fila['nom_ind'];
				$lista[$cont]['nota'] = $fila['nota'];
				$cont++;
			}
			return $lista;
		}

		function opeNotas(){
			$rs1 = $this->consultar();
			if( !$rs1 ){
				$this->incluir();
			}
			else{
				$this->modificar();
			}
		}

		# NOTAS POR LAPSO
		function setPromedio($insc, $prom, $lapso){
			$this->insc = $insc;
			$this->promedio = $prom;
			$this->lapso = $lapso;
		}

		function opePromedio($x){
			# Nota, el sistema automáticamente calcula un promedio de las notas por indicador entre el total de indicadores del lapso
			# pm = si la nota fué promediada manualmente. # el parametro X= S,N (si o no)
			# la nota promediada manualmente es en caso de que un estudiante no se le puedan evaluar algunos indicadores, o sea un estudiante que venga de otra escuela
			$pm = '';
			if($x == 'S'){
				$pm = 'S';
			}else{
				$pm = '';
			}

			$sql = "SELECT * FROM nota_lapso WHERE cod_insc = '$this->insc' AND cod_lapso = '$this->lapso'";
			$rs = $this->query($sql);

			# Si existe datos modifica
			if( $this->f_numrows($rs) ){
				$sql = "UPDATE nota_lapso SET nota = '$this->promedio', pm = '$pm' WHERE cod_insc = '$this->insc' AND cod_lapso = '$this->lapso'";
				$this->query($sql);
			}
			# Sino registra
			else{
				$sql = "INSERT INTO nota_lapso (cod_insc,cod_lapso,nota,pm) VALUES ('$this->insc','$this->lapso','$this->promedio','$pm')";
				$this->query($sql);
			}
		}

		function consultar_promedio(){ # por lapso
			$sql = "SELECT nota, pm FROM nota_lapso WHERE cod_insc = '$this->insc' AND cod_lapso = '$this->lapso'";
			$rs = $this->query($sql);
			if( $this->f_numrows($rs) ){
				return $this->f_array($rs);
			}
		}

		# BOLETIN FINAL
		function setBoletinFinal($insc,$desc,$rec,$nota,$prom){
			$this->insc = $insc;
			$this->descripcion = $this->limpiarCadena($desc);
			$this->recomendacion = $this->limpiarCadena($rec);
			$this->notaF = $nota;
			$this->promovido = $prom;
		}

		function incluirBF(){
			$sql = "INSERT INTO nota_final (cod_insc,descripcion,recomendacion,literal,promovido) VALUES 
			('$this->insc','$this->descripcion','$this->recomendacion','$this->notaF','$this->promovido')";
			$this->query($sql);
		}

		function modificarBF(){
			$sql = "UPDATE nota_final SET descripcion='$this->descripcion', recomendacion='$this->recomendacion', literal='$this->notaF', promovido='$this->promovido' 
			WHERE cod_insc = '$this->insc'";
			$this->query($sql);
		}

		function consultarBF(){ # por codigo de inscripción
			$sql = "SELECT cod_insc,descripcion,recomendacion,literal,promovido FROM nota_final WHERE cod_insc = '$this->insc'";
			$rs = $this->query($sql);
			#echo $sql;
			if( $this->f_numrows($rs) ){
				return $this->f_array($rs);
			}
		}

		function consultar_notaFinal(){ # por codigo de inscripcion
			$sql = "SELECT cod_insc, literal, promovido FROM nota_final WHERE cod_insc = '$this->insc'";
			$rs = $this->query($sql);
			#echo $sql;
			if( $this->f_numrows($rs) ){
				return $this->f_array($rs);
			}
		}	

		function consultar_notaFinal_2(){ # por cedula y periodo
			$sql = "SELECT inscripcion.cod_insc, literal, promovido, grado, letra, periodo FROM nota_final 
			INNER JOIN inscripcion ON nota_final.cod_insc = inscripcion.cod_insc
			INNER JOIN seccion ON inscripcion.seccion = seccion.cod_seccion
			INNER JOIN periodo_escolar ON seccion.periodo_esc = periodo_escolar.cod_periodo
			INNER JOIN estudiante ON inscripcion.cod_est = estudiante.cod_per
			INNER JOIN persona ON estudiante.cod_per = persona.cod_per
			WHERE CONCAT(tipo_documento,'-',cedula)='$this->CE' AND periodo = '$this->periodo'
			OR ced_esc='$this->CE' AND periodo = '$this->periodo'";
			$rs = $this->query($sql);
			#echo $sql;
			if( $this->f_numrows($rs) ){
				return $this->f_array($rs);
			}
		}

		# consultar todo tipo de notas en el año escolar
		function consultar_notasAESC(){
			$sql = "SELECT inscripcion.cod_insc,
			(SELECT nota_lapso.nota FROM nota_lapso WHERE inscripcion.cod_insc = '$this->insc' AND lapso = '1') AS lapso1, 
			(SELECT nota_lapso.nota FROM nota_lapso WHERE inscripcion.cod_insc = '$this->insc' AND lapso = '2') AS lapso2,
			(SELECT nota_lapso.nota FROM nota_lapso WHERE inscripcion.cod_insc = '$this->insc' AND lapso = '3') AS lapso3,
			(SELECT literal FROM nota_final WHERE inscripcion.cod_insc = '$this->insc') AS final
			FROM inscripcion 
			INNER JOIN nota_lapso ON nota_lapso.cod_insc = inscripcion.cod_insc
			INNER JOIN lapso ON nota_lapso.cod_lapso = lapso.cod_lapso
			WHERE inscripcion.cod_insc = '$this->insc'";
			#echo $sql;
			$rs = $this->query($sql);
			if( $this->f_numrows($rs) ){
				return $this->f_array($rs);
			}
		}
	}
?>