<?php 
require_once('../TCPDF/tcpdf.php');

class MYPDF extends TCPDF{
	public $ci_director,
    $nom_director, 
    $sexo_dir,
    $nom_escuela, 
    $edo,
    $mun,
    $zonaeduc,
    $codplantel,
    $coddea,
    $codestco,
    $direccion, 
    $correo, 
    $telefono, 
    $sinPag;
	 //Page header

    public function Header() {
        // Logo
        $image_file = '../IMG/cintillo.jpg';
        $this->SetDrawColor(225,22,22);
        $this->SetLineWidth(0.5);
        $this->Image($image_file, 10, 5, 190, 18, 'JPG', '', 'T', false, 300, 'C', false, false, 'B', false, false, false);
       	$this->SetDrawColor(0,0,0);
        $this->SetLineWidth(0.2);
    }

    // Page footer
    public function Footer(){
        // Position at 15 mm from bottom
        $this->SetDrawColor(225,22,22);
        $this->SetLineWidth(0.5);
        // Pie de página
        $this->SetFont('helvetica', 'I', 8);
        $this->getPlantel();
       	$txt = $this->nom_escuela.". ".$this->direccion.". Correo: ".$this->correo.". Teléfono: ".$this->telefono;
        $this->SetY(-19);
        $this->MultiCell(0, 15,$txt, 'T', 'C', false, 1, '', '', true, 0, false, true, 15, 'M');
        // Numero de pagina
        if($this->sinPag != true ){
            $this->SetY(-5); $this->SetX(-20);
            $this->Cell(8, 4, 'Pag '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'L', 0, '', 0, false, 'T', 'M');
        }
    }

    public function Titulo($txt,$size=18){
    	 $this->SetFont('helvetica', 'BI', $size);
    	 #($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
    	 $this->Ln(25);
    	 $this->Cell(0,10,$txt,0,1,'C');
    	 $this->Ln(15);
    }

    # Consulta datos del Plantel
    public function getPlantel(){ 
    	require_once('m_plantel.php');
    	$plantel = new cls_plantel();
    	$rsplantel = $plantel->consultar();

		$this->ci_director = htmlspecialchars($rsplantel['cedula']);
		$this->nom_director = htmlspecialchars($rsplantel['director']);
        $this->sexo_dir = $rsplantel['sexo'];
        $this->nom_escuela = htmlspecialchars($rsplantel['nom_escuela']);
        $this->edo = htmlspecialchars($rsplantel['edo']);
        $this->mun = htmlspecialchars($rsplantel['mun']);
        $this->zonaeduc = htmlspecialchars($rsplantel['zonaeduc']);
        $this->codplantel = htmlspecialchars($rsplantel['codplantel']);
        $this->coddea = htmlspecialchars($rsplantel['coddea']);
        $this->codestco = htmlspecialchars($rsplantel['codestco']);
        $this->direccion = htmlspecialchars($rsplantel['direccion']);
		$this->correo = htmlspecialchars($rsplantel['correo']);
		$this->telefono = htmlspecialchars($rsplantel['telefono']);
    }

    public function firmaSello(){
        $sexo_dir = 'Director(E): ';
        if( $this->sexo_dir == 'F' ){
            $sexo_dir = 'Directora(E): ';
        }
    	$this->Ln(30);
    	$ln = '______________________';
    	$this->Cell(90,8,$ln,0,0,'C'); $this->Cell(35,8,'',0,0,'');$this->Cell(70,8,'',0,1,'C');
    	$this->Cell(90,8,$sexo_dir.ucwords(strtolower($this->nom_director)),0,0,'C'); $this->Cell(15,8,'',0,0,''); $this->Cell(70,8,'Sello',0,1,'C');
    	$this->Cell(90,0,'C.I.N°: '.$this->ci_director,0,1,'C');
    }

    public function sinPag(){
        $this->sinPag = true;
    }
}

?>