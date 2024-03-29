<?php
session_start();
require('fpdf.php');
include('includes/mysqlconn.php');

//function hex2dec
//returns an associative array (keys: R,G,B) from
//a hex html code (e.g. #3FE5AA)
function hex2dec($couleur = "#000000"){
	$R = substr($couleur, 1, 2);
	$rouge = hexdec($R);
	$V = substr($couleur, 3, 2);
	$vert = hexdec($V);
	$B = substr($couleur, 5, 2);
	$bleu = hexdec($B);
	$tbl_couleur = array();
	$tbl_couleur['R']=$rouge;
	$tbl_couleur['V']=$vert;
	$tbl_couleur['B']=$bleu;
	return $tbl_couleur;
}

//conversion pixel -> millimeter at 72 dpi
function px2mm($px){
	return $px*25.4/72;
}

function txtentities($html){
	$trans = get_html_translation_table(HTML_ENTITIES);
	$trans = array_flip($trans);
	return strtr($html, $trans);
}
////////////////////////////////////

class PDF_MC_Table extends FPDF
{
	
//variables of html parser
var $B;
var $I;
var $U;
var $HREF;
var $fontList;
var $issetfont;
var $issetcolor;

function PDF_HTML($orientation='P', $unit='mm', $format='A4')
{
	//Call parent constructor
	$this->FPDF($orientation,$unit,$format);
	//Initialization
	$this->B=0;
	$this->I=0;
	$this->U=0;
	$this->HREF='';
	$this->fontlist=array('arial', 'times', 'courier', 'helvetica', 'symbol');
	$this->issetfont=false;
	$this->issetcolor=false;
}

function WriteHTML($html)
{
	//HTML parser
	$html=strip_tags($html,"<b><u><i><a><img><p><br><strong><em><font><pre><tr><blockquote>"); //supprime tous les tags sauf ceux reconnus
	$html=str_replace("\n",' ',$html); //remplace retour à la ligne par un espace
	$a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE); //éclate la chaîne avec les balises
	foreach($a as $i=>$e)
	{
		if($i%2==0)
		{
			//Text
			if($this->HREF)
				$this->PutLink($this->HREF,$e);
			else
				$this->Write(5,stripslashes(txtentities($e)));
		}
		else
		{
			//Tag
			if($e[0]=='/')
				$this->CloseTag(strtoupper(substr($e,1)));
			else
			{
				//Extract attributes
				$a2=explode(' ',$e);
				$tag=strtoupper(array_shift($a2));
				$attr=array();
				foreach($a2 as $v)
				{
					if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
						$attr[strtoupper($a3[1])]=$a3[2];
				}
				$this->OpenTag($tag,$attr);
			}
		}
	}
}

function OpenTag($tag, $attr)
{
	//Opening tag
	switch($tag){
		case 'STRONG':
			$this->SetStyle('B',true);
			break;
		case 'EM':
			$this->SetStyle('I',true);
			break;
		case 'B':
		case 'I':
		case 'U':
			$this->SetStyle($tag,true);
			break;
		case 'A':
			$this->HREF=$attr['HREF'];
			break;
		case 'IMG':
			if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
				if(!isset($attr['WIDTH']))
					$attr['WIDTH'] = 0;
				if(!isset($attr['HEIGHT']))
					$attr['HEIGHT'] = 0;
				$this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
			}
			break;
		case 'PRE':
                $this->SetFont('Courier','',11);
                $this->SetFontSize(11);
                $this->SetStyle('B',false);
                $this->SetStyle('I',false);
                $this->PRE=true;
                break;
		case 'TR':
		case 'BLOCKQUOTE':
		case 'BR':
			$this->Ln(5);
			break;
		case 'P':
			$this->Ln(10);
			break;
		case 'FONT':
			if (isset($attr['COLOR']) && $attr['COLOR']!='') {
				$coul=hex2dec($attr['COLOR']);
				$this->SetTextColor($coul['R'],$coul['V'],$coul['B']);
				$this->issetcolor=true;
			}
			if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
				$this->SetFont(strtolower($attr['FACE']));
				$this->issetfont=true;
			}
			break;
	}
}

function CloseTag($tag)
{
	//Closing tag
	if($tag=='STRONG')
		$tag='B';
	if($tag=='EM')
		$tag='I';
	if($tag=='B' || $tag=='I' || $tag=='U')
		$this->SetStyle($tag,false);
	if($tag=='A')
		$this->HREF='';
	if($tag=='FONT'){
		if ($this->issetcolor==true) {
			$this->SetTextColor(0);
		}
		if ($this->issetfont) {
			$this->SetFont('arial');
			$this->issetfont=false;
		}
	}
}

function SetStyle($tag, $enable)
{
	//Modify style and select corresponding font
	$this->$tag+=($enable ? 1 : -1);
	$style='';
	foreach(array('B','I','U') as $s)
	{
		if($this->$s>0)
			$style.=$s;
	}
	$this->SetFont('',$style);
}

function PutLink($URL, $txt)
{
	//Put a hyperlink
	$this->SetTextColor(0,0,255);
	$this->SetStyle('U',true);
	$this->Write(5,$txt,$URL);
	$this->SetStyle('U',false);
	$this->SetTextColor(0);
}




var $widths;
var $aligns;

function Header()
{
    require('includes/mysqlconn.php');
	
	$query = "SELECT T1.ID, T1.code, T2.ID dsStoreID, T2.name dsStore, CONCAT(T3.first, ' ', T3.last) emp, DATE_FORMAT(T1.created_at, '%d-%m-%Y %T') created_at, T1.remarks FROM inflows T1 INNER JOIN stores T2 ON T1.storeID = T2.ID INNER JOIN crew T3 ON T1.empID = T3.ID"/* WHERE T1.ID = '$infID'"*/;
	$result = $db->query($query);
	$row = $result->fetch();
	
	$folio = $row["code"];
	$dsStoreID = $row["dsStoreID"];
	$dsStore = $row["dsStore"];
	$created_at = $row["created_at"];
	$remarks = $row["remarks"];
		
	$queryTo = "SELECT T1.first, T1.last, T1.email, T2.phone, T2.address FROM crew T1 JOIN stores T2 ON T1.storeID = T2.ID WHERE T2.ID = '$dsStoreID' LIMIT 1";
	$resultTo = $db->query($queryTo);
	$rowTo = $resultTo->fetch();
	$nameTo = $rowTo["first"]." ".$rowTo["last"];
	$emailTo = $rowTo["email"];
	$phoneTo = $rowTo["phone"];
	$addressTo = $rowTo["address"];
	
    // Logo
    $this->Image("images/logo.png",10,5,65);
    // Arial bold 20
    $this->SetFont('Arial','B',18);
    // Move to the right
    //$this->Cell(190,1,' ',0,1);
	//$this->Cell(110);
    // Title
	$this->Cell(98,8,'',0,0,'');
	$this->Cell(98,8,"Lista de Precios",0,1,'R');
	$this->SetFont('Arial','B',12);
	$this->Cell(98,5,'',0,0,'');
	$this->Cell(98,5,date("d-m-Y H:i:s"),0,1,'R');
	$this->Cell(196,5,'',0,1,'L');
	$this->SetFont('Arial','B',8);
	$this->Cell(49,5,'Fecha de documento',0,0,'L');
	$this->Cell(49,5,'Impreso por',0,0,'L');
	$this->Cell(73.5,5,'E-mail',0,0,'L');
	$this->Cell(24.5,5,utf8_decode('Página'),0,1,'L');
	$this->SetFont('Arial','',8);
	$this->Cell(49,5,date("d-m-Y H:i:s"),0,0,'L');
	$this->Cell(49,5,$_SESSION["name"],0,0,'L');
	$this->Cell(73.5,5,$_SESSION["email"],0,0,'L');
	$this->Cell(24.5,5,$this->PageNo()." de {nb}",0,1,'L');
	$this->Ln(3);
	$this->SetFont('Arial','B',8);
	//Table with 20 rows and 4 columns
	$this->SetDrawColor(0,0,0);
	$this->SetWidths(array(30,86,30,35,15));
	if(isset($_REQUEST["pcompra"])&&isset($_REQUEST["pventa"]))
	{
		$this->Row(array(utf8_decode("Referencia"),utf8_decode("Nombre"),utf8_decode("Código"),"Precio Compra","Precio Venta"));
	}
	else if(isset($_REQUEST["pcompra"]))
	{
		$this->Row(array(utf8_decode("Referencia"),utf8_decode("Nombre"),utf8_decode("Código"),"Precio Compra"));	
	}
	else if(isset($_REQUEST["pventa"]))
	{
		$this->Row(array(utf8_decode("Referencia"),utf8_decode("Nombre"),utf8_decode("Código"),"Precio Venta"));
	}
	else{
		$this->Row(array(utf8_decode("Referencia"),utf8_decode("Nombre"),utf8_decode("Código")));		
	}
	$this->SetFont('Arial','',8);
	$this->SetDrawColor(255,255,255);
}

// Page footer
function Footer() {
	// Position from bottom
    $this->SetY(-5);
	$this->SetX(-215.9);
	$this->SetFillColor(17,52,171);
	$this->SetTextColor(255,255,255);
    $this->Cell(215.9,5,'www.unassalonymas.com',0,0,'C',1);
} 
function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,5,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}
}
?>