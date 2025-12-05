<?php
class FPDF {
    protected $page = 0;
    protected $n = 2;
    protected $buffer = '';
    protected $pages = array();
    protected $state = 0;
    protected $compress = false;
    protected $k;
    protected $DefOrientation;
    protected $CurOrientation;
    protected $StdPageSizes;
    protected $DefPageSize;
    protected $CurPageSize;
    protected $CurRotation;
    protected $PageInfo;
    protected $wPt, $hPt;
    protected $w, $h;
    protected $lMargin;
    protected $tMargin;
    protected $rMargin;
    protected $bMargin;
    protected $cMargin;
    protected $x, $y;
    protected $lasth;
    protected $LineWidth;
    protected $fontpath;
    protected $FontSizePt;
    protected $FontSize;
    protected $DrawColor;
    protected $FillColor;
    protected $TextColor;
    protected $ColorFlag;
    protected $WithAlpha;
    protected $ws;
    protected $images;
    protected $PageLinks;
    protected $links;
    protected $AutoPageBreak;
    protected $PageBreakTrigger;
    protected $InHeader;
    protected $InFooter;
    protected $AliasNbPages;
    protected $ZoomMode;
    protected $LayoutMode;
    protected $metadata;
    protected $PDFVersion;

    function __construct($orientation='P', $unit='mm', $size='A4') {
        $this->StdPageSizes = array('a3'=>array(841.89,1190.55), 'a4'=>array(595.28,841.89), 'a5'=>array(420.94,595.28),
            'letter'=>array(612,792), 'legal'=>array(612,1008));
        
        if($unit=='pt')
            $this->k = 1;
        elseif($unit=='mm')
            $this->k = 72/25.4;
        elseif($unit=='cm')
            $this->k = 72/2.54;
        elseif($unit=='in')
            $this->k = 72;
        else
            $this->Error('Incorrect unit: '.$unit);

        if(is_string($size)) {
            $size = strtolower($size);
            if(!isset($this->StdPageSizes[$size]))
                $this->Error('Unknown page size: '.$size);
            $a = $this->StdPageSizes[$size];
            $this->DefPageSize = array($a[0]/$this->k, $a[1]/$this->k);
        } else {
            $this->DefPageSize = array($size[0]*$this->k, $size[1]*$this->k);
        }

        $this->DefOrientation = $orientation=='L' ? 'L' : 'P';
        $this->CurOrientation = $this->DefOrientation;
        $this->wPt = $this->DefPageSize[0]*$this->k;
        $this->hPt = $this->DefPageSize[1]*$this->k;
        $this->w = $this->wPt/$this->k;
        $this->h = $this->hPt/$this->k;

        $margin = 28.35/$this->k;
        $this->SetMargins($margin,$margin);
        $this->SetAutoPageBreak(true,2*$margin);
        $this->SetDisplayMode('default');
        $this->SetCompression(true);
    }

    function SetMargins($left, $top, $right=null) {
        $this->lMargin = $left;
        $this->tMargin = $top;
        if($right===null)
            $right = $left;
        $this->rMargin = $right;
    }

    function SetLeftMargin($margin) {
        $this->lMargin = $margin;
        if($this->page>0 && $this->x<$margin)
            $this->x = $margin;
    }

    function SetTopMargin($margin) {
        $this->tMargin = $margin;
    }

    function SetRightMargin($margin) {
        $this->rMargin = $margin;
    }

    function SetAutoPageBreak($auto, $margin=0) {
        $this->AutoPageBreak = $auto;
        $this->bMargin = $margin;
        $this->PageBreakTrigger = $this->h-$margin;
    }

    function SetDisplayMode($zoom, $layout='default') {
        $this->ZoomMode = $zoom;
        $this->LayoutMode = $layout;
    }

    function SetCompression($compress) {
        $this->compress = $compress;
    }

    function AddPage($orientation='', $size='', $rotation=0) {
        if($this->state==0)
            $this->Open();
        $family = $this->FontFamily ?? 'Arial';
        $style = $this->FontStyle ?? '';
        $fontsize = $this->FontSizePt ?? 12;
        $lw = $this->LineWidth ?? 0.2;
        $dc = $this->DrawColor ?? '0 G';
        $fc = $this->FillColor ?? '0 g';
        $tc = $this->TextColor ?? '0 g';
        $cf = $this->ColorFlag ?? false;
        
        if($this->page>0) {
            $this->_endpage();
        }
        $this->_beginpage($orientation,$size,$rotation);
        $this->_out('2 J');
        $this->LineWidth = $lw;
        $this->_out(sprintf('%.2F w',$lw*$this->k));
        if(!empty($family))
            $this->SetFont($family,$style,$fontsize);
        $this->DrawColor = $dc;
        if($dc!='0 G')
            $this->_out($dc);
        $this->FillColor = $fc;
        if($fc!='0 g')
            $this->_out($fc);
        $this->TextColor = $tc;
        $this->ColorFlag = $cf;
    }

    function Header() {
        // Override in your class
    }

    function Footer() {
        // Override in your class
    }

    function PageNo() {
        return $this->page;
    }

    function SetDrawColor($r, $g=null, $b=null) {
        if(($r==0 && $g==0 && $b==0) || $g===null)
            $this->DrawColor = sprintf('%.3F G',$r/255);
        else
            $this->DrawColor = sprintf('%.3F %.3F %.3F RG',$r/255,$g/255,$b/255);
        if($this->page>0)
            $this->_out($this->DrawColor);
    }

    function SetFillColor($r, $g=null, $b=null) {
        if(($r==0 && $g==0 && $b==0) || $g===null)
            $this->FillColor = sprintf('%.3F g',$r/255);
        else
            $this->FillColor = sprintf('%.3F %.3F %.3F rg',$r/255,$g/255,$b/255);
        $this->ColorFlag = ($this->FillColor!='0 g');
        if($this->page>0)
            $this->_out($this->FillColor);
    }

    function SetTextColor($r, $g=null, $b=null) {
        if(($r==0 && $g==0 && $b==0) || $g===null)
            $this->TextColor = sprintf('%.3F g',$r/255);
        else
            $this->TextColor = sprintf('%.3F %.3F %.3F rg',$r/255,$g/255,$b/255);
    }

    function GetStringWidth($s) {
        $s = (string)$s;
        $cw = $this->CurrentFont['cw'] ?? array();
        $w = 0;
        $l = strlen($s);
        for($i=0;$i<$l;$i++)
            $w += $cw[ord($s[$i])] ?? 600;
        return $w*$this->FontSize/1000;
    }

    function SetLineWidth($width) {
        $this->LineWidth = $width;
        if($this->page>0)
            $this->_out(sprintf('%.2F w',$width*$this->k));
    }

    function Line($x1, $y1, $x2, $y2) {
        $this->_out(sprintf('%.2F %.2F m %.2F %.2F l S',$x1*$this->k,($this->h-$y1)*$this->k,$x2*$this->k,($this->h-$y2)*$this->k));
    }

    function Rect($x, $y, $w, $h, $style='') {
        if($style=='F')
            $op = 'f';
        elseif($style=='FD' || $style=='DF')
            $op = 'B';
        else
            $op = 'S';
        $this->_out(sprintf('%.2F %.2F %.2F %.2F re %s',$x*$this->k,($this->h-$y)*$this->k,$w*$this->k,-$h*$this->k,$op));
    }

    function SetFont($family, $style='', $size=0) {
        $family = strtolower($family);
        if($family=='')
            $family = $this->FontFamily ?? 'arial';
        if($family=='arial')
            $family = 'helvetica';
        $style = strtoupper($style);
        if(strpos($style,'U')!==false) {
            $this->underline = true;
            $style = str_replace('U','',$style);
        } else {
            $this->underline = false;
        }
        if($size==0)
            $size = $this->FontSizePt ?? 12;

        $this->FontFamily = $family;
        $this->FontStyle = $style;
        $this->FontSizePt = $size;
        $this->FontSize = $size/$this->k;
        
        // Basic font widths for Arial/Helvetica
        $cw = array(
            chr(0)=>278,chr(1)=>278,chr(2)=>278,chr(3)=>278,chr(4)=>278,chr(5)=>278,chr(6)=>278,chr(7)=>278,chr(8)=>278,chr(9)=>278,chr(10)=>278,chr(11)=>278,chr(12)=>278,chr(13)=>278,chr(14)=>278,chr(15)=>278,chr(16)=>278,chr(17)=>278,chr(18)=>278,chr(19)=>278,chr(20)=>278,chr(21)=>278,
            chr(22)=>278,chr(23)=>278,chr(24)=>278,chr(25)=>278,chr(26)=>278,chr(27)=>278,chr(28)=>278,chr(29)=>278,chr(30)=>278,chr(31)=>278,' '=>278,'!'=>278,'"'=>355,'#'=>556,'$'=>556,'%'=>889,'&'=>667,'\''=>191,'('=>333,')'=>333,'*'=>389,'+'=>584,
            ','=>278,'-'=>333,'.'=>278,'/'=>278,'0'=>556,'1'=>556,'2'=>556,'3'=>556,'4'=>556,'5'=>556,'6'=>556,'7'=>556,'8'=>556,'9'=>556,':'=>278,';'=>278,'<'=>584,'='=>584,'>'=>584,'?'=>556,'@'=>1015,'A'=>667,
            'B'=>667,'C'=>722,'D'=>722,'E'=>667,'F'=>611,'G'=>778,'H'=>722,'I'=>278,'J'=>500,'K'=>667,'L'=>556,'M'=>833,'N'=>722,'O'=>778,'P'=>667,'Q'=>778,'R'=>722,'S'=>667,'T'=>611,'U'=>722,'V'=>667,'W'=>944,
            'X'=>667,'Y'=>667,'Z'=>611,'['=>278,'\\'=>278,']'=>278,'^'=>469,'_'=>556,'`'=>333,'a'=>556,'b'=>556,'c'=>500,'d'=>556,'e'=>556,'f'=>278,'g'=>556,'h'=>556,'i'=>222,'j'=>222,'k'=>500,'l'=>222,'m'=>833,
            'n'=>556,'o'=>556,'p'=>556,'q'=>556,'r'=>333,'s'=>500,'t'=>278,'u'=>556,'v'=>500,'w'=>722,'x'=>500,'y'=>500,'z'=>500,'{'=>334,'|'=>260,'}'=>334,'~'=>584,chr(127)=>350,chr(128)=>556,chr(129)=>350,chr(130)=>222,chr(131)=>556,
            chr(132)=>333,chr(133)=>1000,chr(134)=>556,chr(135)=>556,chr(136)=>333,chr(137)=>1000,chr(138)=>667,chr(139)=>333,chr(140)=>1000,chr(141)=>350,chr(142)=>611,chr(143)=>350,chr(144)=>350,chr(145)=>222,chr(146)=>222,chr(147)=>333,chr(148)=>333,chr(149)=>350,chr(150)=>556,chr(151)=>1000,chr(152)=>333,chr(153)=>1000,
            chr(154)=>500,chr(155)=>333,chr(156)=>944,chr(157)=>350,chr(158)=>500,chr(159)=>667,chr(160)=>278,chr(161)=>333,chr(162)=>556,chr(163)=>556,chr(164)=>556,chr(165)=>556,chr(166)=>260,chr(167)=>556,chr(168)=>333,chr(169)=>737,chr(170)=>370,chr(171)=>556,chr(172)=>584,chr(173)=>333,chr(174)=>737,chr(175)=>333,
            chr(176)=>400,chr(177)=>584,chr(178)=>333,chr(179)=>333,chr(180)=>333,chr(181)=>556,chr(182)=>537,chr(183)=>278,chr(184)=>333,chr(185)=>333,chr(186)=>365,chr(187)=>556,chr(188)=>834,chr(189)=>834,chr(190)=>834,chr(191)=>611,chr(192)=>667,chr(193)=>667,chr(194)=>667,chr(195)=>667,chr(196)=>667,chr(197)=>667,
            chr(198)=>1000,chr(199)=>722,chr(200)=>667,chr(201)=>667,chr(202)=>667,chr(203)=>667,chr(204)=>278,chr(205)=>278,chr(206)=>278,chr(207)=>278,chr(208)=>722,chr(209)=>722,chr(210)=>778,chr(211)=>778,chr(212)=>778,chr(213)=>778,chr(214)=>778,chr(215)=>584,chr(216)=>778,chr(217)=>722,chr(218)=>722,chr(219)=>722,
            chr(220)=>722,chr(221)=>667,chr(222)=>667,chr(223)=>611,chr(224)=>556,chr(225)=>556,chr(226)=>556,chr(227)=>556,chr(228)=>556,chr(229)=>556,chr(230)=>889,chr(231)=>500,chr(232)=>556,chr(233)=>556,chr(234)=>556,chr(235)=>556,chr(236)=>278,chr(237)=>278,chr(238)=>278,chr(239)=>278,chr(240)=>556,chr(241)=>556,
            chr(242)=>556,chr(243)=>556,chr(244)=>556,chr(245)=>556,chr(246)=>556,chr(247)=>584,chr(248)=>611,chr(249)=>556,chr(250)=>556,chr(251)=>556,chr(252)=>556,chr(253)=>500,chr(254)=>556,chr(255)=>500);
        
        $this->CurrentFont = array('cw'=>$cw, 'name'=>$family.$style);
        if($this->page>0)
            $this->_out(sprintf('BT /F1 %.2F Tf ET',$this->FontSizePt));
    }

    function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
        $k = $this->k;
        if($this->y+$h>$this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak()) {
            $x = $this->x;
            $ws = $this->ws;
            if($ws>0) {
                $this->ws = 0;
                $this->_out('0 Tw');
            }
            $this->AddPage($this->CurOrientation,$this->CurPageSize,$this->CurRotation);
            $this->x = $x;
            if($ws>0) {
                $this->ws = $ws;
                $this->_out(sprintf('%.3F Tw',$ws*$k));
            }
        }
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;
        $s = '';
        if($fill || $border==1) {
            if($fill)
                $op = ($border==1) ? 'B' : 'f';
            else
                $op = 'S';
            $s = sprintf('%.2F %.2F %.2F %.2F re %s ',$this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);
        }
        if(is_string($border)) {
            $x = $this->x;
            $y = $this->y;
            if(strpos($border,'L')!==false)
                $s .= sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
            if(strpos($border,'T')!==false)
                $s .= sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
            if(strpos($border,'R')!==false)
                $s .= sprintf('%.2F %.2F m %.2F %.2F l S ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
            if(strpos($border,'B')!==false)
                $s .= sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
        }
        if($txt!=='') {
            if(!isset($this->CurrentFont))
                $this->Error('No font has been set');
            if($align=='R')
                $dx = $w-$this->cMargin-$this->GetStringWidth($txt);
            elseif($align=='C')
                $dx = ($w-$this->GetStringWidth($txt))/2;
            else
                $dx = $this->cMargin;
            if($this->ColorFlag)
                $s .= 'q '.$this->TextColor.' ';
            $s .= sprintf('BT %.2F %.2F Td (%s) Tj ET',($this->x+$dx)*$k,($this->h-($this->y+.5*$h+.3*$this->FontSize))*$k,$this->_escape($txt));
            if($this->underline ?? false)
                $s .= ' '.$this->_dounderline($this->x+$dx,$this->y+.5*$h+.3*$this->FontSize,$txt);
            if($this->ColorFlag)
                $s .= ' Q';
            if($link)
                $this->Link($this->x+$dx,$this->y+.5*$h-.5*$this->FontSize,$this->GetStringWidth($txt),$this->FontSize,$link);
        }
        if($s)
            $this->_out($s);
        $this->lasth = $h;
        if($ln>0) {
            $this->y += $h;
            if($ln==1)
                $this->x = $this->lMargin;
        } else {
            $this->x += $w;
        }
    }

    function Ln($h=null) {
        $this->x = $this->lMargin;
        if($h===null)
            $this->y += $this->lasth;
        else
            $this->y += $h;
    }

    function SetY($y) {
        $this->x = $this->lMargin;
        $this->y = $y;
    }

    function Output($dest='', $name='', $isUTF8=false) {
        if($this->state<3)
            $this->Close();
        
        if($dest=='') {
            $dest = 'I';
            $name = 'doc.pdf';
        }
        
        switch(strtoupper($dest)) {
            case 'I':
                $this->_checkoutput();
                if(PHP_SAPI!='cli') {
                    if(headers_sent())
                        $this->Error('Some data has already been output, can\'t send PDF file');
                    header('Content-Type: application/pdf');
                    if(headers_sent())
                        $this->Error('Some data has already been output, can\'t send PDF file (2)');
                    header('Content-Disposition: inline; filename="'.$name.'"');
                    header('Cache-Control: private, max-age=0, must-revalidate');
                    header('Pragma: public');
                }
                echo $this->buffer;
                break;
            case 'D':
                $this->_checkoutput();
                if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'],'MSIE'))
                    header('Content-Type: application/force-download');
                else
                    header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.$name.'"');
                header('Cache-Control: private, max-age=0, must-revalidate');
                header('Pragma: public');
                echo $this->buffer;
                break;
            case 'F':
                if(!$isUTF8)
                    $name = utf8_decode($name);
                if(file_put_contents($name,$this->buffer)===false)
                    $this->Error('Unable to create output file: '.$name);
                break;
            case 'S':
                return $this->buffer;
            default:
                $this->Error('Incorrect output destination: '.$dest);
        }
        return '';
    }

    function Close() {
        if($this->state==3)
            return;
        if($this->page==0)
            $this->AddPage();
        $this->InFooter = true;
        $this->Footer();
        $this->InFooter = false;
        $this->_endpage();
        $this->_enddoc();
    }

    function AcceptPageBreak() {
        return $this->AutoPageBreak;
    }

    function Error($msg) {
        throw new Exception('FPDF error: '.$msg);
    }

    protected function _checkoutput() {
        if(PHP_SAPI!='cli') {
            if(headers_sent($file,$line))
                $this->Error("Some data has already been output to browser, can't send PDF file (output started at $file:$line)");
        }
        if(ob_get_length()) {
            if(preg_match('/^(\xEF\xBB\xBF)?\s*$/',ob_get_contents())) {
                ob_end_clean();
            } else {
                $this->Error("Some data has already been output to browser, can't send PDF file");
            }
        }
    }

    protected function _beginpage($orientation,$size,$rotation) {
        $this->page++;
        $this->pages[$this->page] = '';
        $this->state = 2;
        $this->x = $this->lMargin;
        $this->y = $this->tMargin;
        $this->FontFamily = '';
        
        if($orientation!='') {
            $this->CurOrientation = $orientation;
            if($orientation!=$this->DefOrientation) {
                $this->wPt = $this->DefPageSize[1]*$this->k;
                $this->hPt = $this->DefPageSize[0]*$this->k;
            } else {
                $this->wPt = $this->DefPageSize[0]*$this->k;
                $this->hPt = $this->DefPageSize[1]*$this->k;
            }
            $this->w = $this->wPt/$this->k;
            $this->h = $this->hPt/$this->k;
            $this->PageBreakTrigger = $this->h-$this->bMargin;
        }
        
        $this->CurRotation = $rotation;
        if($orientation!=$this->DefOrientation || $size!=$this->DefPageSize || $rotation!=0)
            $this->PageInfo[$this->page] = array('w'=>$this->wPt,'h'=>$this->hPt,'r'=>$rotation);
    }

    protected function _endpage() {
        $this->state = 1;
    }

    protected function _enddoc() {
        $this->_putheader();
        $this->_putpages();
        $this->_putresources();
        $info = $this->_putinfo();
        $catalog = $this->_putcatalog();
        $o = strlen($this->buffer);
        $this->_put('xref');
        $this->_put('0 '.($this->n+1));
        $this->_put('0000000000 65535 f ');
        for($i=1;$i<=$this->n;$i++)
            $this->_put(sprintf('%010d 00000 n ',isset($this->offsets[$i]) ? $this->offsets[$i] : 0));
        $this->_put('trailer');
        $this->_put('<<');
        $this->_putdict(array('Size'=>$this->n+1,'Root'=>$catalog,'Info'=>$info));
        $this->_put('>>');
        $this->_put('startxref');
        $this->_put($o);
        $this->_put('%%EOF');
        $this->state = 3;
    }

    protected function _putheader() {
        $this->_put('%PDF-1.3');
    }

    protected function _putpages() {
        $nb = $this->page;
        for($n=1;$n<=$nb;$n++)
            $this->PageInfo[$n] = array('w'=>$this->wPt,'h'=>$this->hPt);
        for($n=1;$n<=$nb;$n++)
            $this->_putpage($n);
    }

    protected function _putpage($n) {
        $this->_newobj();
        $this->_put('<</Type /Page');
        $this->_put('/Parent 1 0 R');
        if(isset($this->PageInfo[$n]))
            $this->_put(sprintf('/MediaBox [0 0 %.2F %.2F]',$this->PageInfo[$n]['w'],$this->PageInfo[$n]['h']));
        $this->_put('/Resources 2 0 R');
        $this->_put('/Contents '.($this->n+1).' 0 R>>');
        $this->_put('endobj');
        
        $p = $this->pages[$n];
        $this->_newobj();
        $this->_put('<</Length '.strlen($p).'>>');
        $this->_put('stream');
        $this->_put($p);
        $this->_put('endstream');
        $this->_put('endobj');
    }

    protected function _putresources() {
        $this->_put('2 0 obj');
        $this->_put('<</ProcSet [/PDF /Text /ImageB /ImageC /ImageI]');
        $this->_put('/Font <<');
        $this->_put('/F1 3 0 R');
        $this->_put('>>');
        $this->_put('>>');
        $this->_put('endobj');
        
        $this->_put('3 0 obj');
        $this->_put('<</Type /Font');
        $this->_put('/BaseFont /Helvetica');
        $this->_put('/Subtype /Type1');
        $this->_put('/Encoding /WinAnsiEncoding');
        $this->_put('>>');
        $this->_put('endobj');
    }

    protected function _putinfo() {
        $this->_put('1 0 obj');
        $this->_put('<<');
        $this->_put('/Type /Catalog');
        $this->_put('/Pages 4 0 R');
        $this->_put('>>');
        $this->_put('endobj');
        return 1;
    }

    protected function _putcatalog() {
        $n = $this->_newobj();
        $this->_put('<<');
        $this->_put('/Type /Pages');
        $kids = '/Kids [';
        for($i=1;$i<=$this->page;$i++)
            $kids .= (3+2*$i).' 0 R ';
        $kids .= ']';
        $this->_put($kids);
        $this->_put('/Count '.$this->page);
        $this->_put('>>');
        $this->_put('endobj');
        return $n;
    }

    protected function _newobj($n=null) {
        if($n===null)
            $n = ++$this->n;
        $this->offsets[$n] = strlen($this->buffer);
        $this->_put($n.' 0 obj');
        return $n;
    }

    protected function _put($s) {
        $this->buffer .= $s."\n";
    }

    protected function _putdict($d) {
        $s = '';
        foreach($d as $k=>$v)
            $s .= '/'.$k.' '.$v.' ';
        $this->_put($s);
    }

    protected function _out($s) {
        if($this->state==2)
            $this->pages[$this->page] .= $s."\n";
        else
            $this->_put($s);
    }

    protected function _escape($s) {
        return '('.strtr($s,array(')'=>'\\)','\('=>'\\(','\\'=>'\\\\')).')';
    }

    function Open() {
        $this->state = 1;
        $this->page = 0;
        $this->n = 2;
        $this->buffer = '';
        $this->pages = array();
        $this->offsets = array();
        $this->images = array();
        $this->links = array();
        $this->InHeader = false;
        $this->InFooter = false;
        $this->lasth = 0;
        $this->FontFamily = '';
        $this->FontStyle = '';
        $this->FontSizePt = 12;
        $this->underline = false;
        $this->DrawColor = '0 G';
        $this->FillColor = '0 g';
        $this->TextColor = '0 g';
        $this->ColorFlag = false;
        $this->ws = 0;
        $this->cMargin = 2/$this->k;
    }
}
?>