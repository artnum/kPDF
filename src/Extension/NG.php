<?php

namespace kPDF\Extension;

/**
 * Trait Next Generation.
 * Rewrite function to have better text handling. Use NG function to echo text.
 */
trait NG {
    protected float $currentWidth = 0;
    protected array $currentColumn = [-1, -1];
    protected float $currentUnderline = -1;
    protected int $referenceCounter = 0;
    protected array $references = [];
    protected array $beforeBreakPosition = [];
    protected bool $textStarted = false;
    protected array $columns = [];
    
    function separator() {
        $this->br();
        $this->Line($this->left, $this->GetY(), $this->left + 20, $this->GetY());
        $this->br();
    }

    function addReference ($url) {
        $reference = array_search($url, $this->references);

        if ($reference === false) {
            $reference = $this->referenceCounter++;
            $this->references[$reference] = $url;
        }
        $x = $this->GetX();
        $y = $this->GetY();
        $this->_out(
            sprintf('BT /F%d %.2f Tf %.2f %.2f Td 5 Ts', 
                $this->CurrentFont['i'],
                $this->FontSizePt * 0.8,
                ($x + ($this->GetStringWidth(' '))) * $this->k,
                ($this->h - $y - $this->getFontSize()) * $this->k
            )
        );

        if ($this->unifontSubset)
        {
            $txt = strval($reference + 1);
            $this->_out(' ('.$this->_escape($this->UTF8ToUTF16BE($txt, false)).')');
            foreach($this->UTF8StringToArray($txt) as $uni) {
                $this->CurrentFont['subset'][$uni] = $uni;
            }
        } else {
            $this->_out(' ('. $reference + 1 .')');
        }
        $this->_out(' Tj 0 Ts ET');
        return $reference;
    }

    function printReferences () {
        for ($i = 0; $i < count($this->references); $i++) {
            $this->echo($i + 1 . '. ' . $this->references[$i]);
            $this->br();
        } 
    }

    function getReferences () {
        return $this->references;
    }

    function startUnderline() {
        $this->currentUnderline = $this->GetX();
    }

    function endUnderline() {
        if ($this->currentUnderline < 0) { return; }
        $end = $this->GetX();
        $pos = $this->GetY() + $this->getFontSize() - 0.6;
        $this->Line($this->currentUnderline + 1, $pos, $end + 1, $pos);
        $this->currentUnderline = -1;
    }

    function echo ($txt) {
        if (empty($txt)) { return; }
        $left = $this->left;
        $right = $this->right;
        if ($this->currentColumn[0] > -1) {
            $left = $this->currentColumn[0];
        }
        if ($this->currentColumn[1] > -1) {
            $right = $this->currentColumn[1];
        }
        $parts = explode("\n", $txt);
        foreach ($parts as $part) {
            do {
                $next = [];
                do {
                    $width = $this->GetStringWidth($part);
                    if ($width > $this->w - ($right + $left + $this->currentWidth)) {
                        $words = explode(' ',$part);
                        if (count($words) === 1) { break; }
                        array_unshift($next, array_pop($words));
                        $part = implode(' ', $words);
                    }
                } while ($width > $this->w - ($right + $left + $this->currentWidth));
                $this->currentWidth += $width;
                $this->Cell($width, $this->getFontSize(), $part);
                if (!empty($next)) { $this->break(); }
                $part = implode(' ', $next);
            } while (!empty($next));
        }
    }

    function indent($mm)
    {
        $this->SetX($this->GetX() + $mm);
    }

    function break() {
        $this->beforeBreakPosition = [$this->GetX(), $this->GetY()];
        $this->endUnderline();
        $this->br();
        if ($this->currentColumn[0] > -1) {
            $this->SetX($this->currentColumn[0]);
        }
        $this->currentWidth = 0;
    }

    function start() {
        if ($this->currentColumn[0] > -1) {
            $this->SetX($this->currentColumn[0]);
        }
    }

    function addColumn ($name, $left, $width) {
        $this->columns[$name] = [$left, $left + $width];
    }

    function setColumn($name) {
        if (isset($this->columns[$name])) {
            $this->_setColumn($this->columns[$name][0], $this->columns[$name][1]);
        }
    }

    function _setColumn($left, $right) {
        $this->currentColumn = [$left, $right];
    }

    /* Rewrite of the original function just to return info about image */
    protected function _image($file, $x=null, $y=null, $w=0, $h=0, $type='', $link='')
    {
        // Put an image on the page
        if($file=='') {
            $this->Error('Image file name is empty');
        }
        if(!isset($this->images[$file]))
        {
            // First use of this image, get info
            if($type=='')
            {
                $pos = strrpos($file,'.');
                if(!$pos) {
                    $this->Error('Image file has no extension and no type was specified: '.$file);
                }
                $type = substr($file,$pos+1);
            }
            $type = strtolower($type);
            if($type=='jpeg')
                $type = 'jpg';
            $mtd = '_parse'.$type;
            if(!method_exists($this,$mtd)) {
                $this->Error('Unsupported image type: '.$type);
            }
            $info = $this->$mtd($file);
            $info['i'] = count($this->images)+1;
            $this->images[$file] = $info;
        }
        else {
            $info = $this->images[$file];
        }

        // Automatic width and height calculation if needed
        if($w==0 && $h==0)
        {
            // Put image at 96 dpi
            $w = -96;
            $h = -96;
        }
        if($w<0) {
            $w = -$info['w']*72/$w/$this->k;
        }
        if($h<0) {
            $h = -$info['h']*72/$h/$this->k;
        }
        if($w==0) {
            $w = $h*$info['w']/$info['h'];
        }
        if($h==0) {
            $h = $w*$info['h']/$info['w'];
        }

        // Flowing mode
        if($y===null)
        {
            if($this->y+$h>$this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak())
            {
                // Automatic page break
                $x2 = $this->x;
                $this->AddPage($this->CurOrientation,$this->CurPageSize,$this->CurRotation);
                $this->x = $x2;
            }
            $y = $this->y;
            $this->y += $h;
        }
    
        if($x===null) {
            $x = $this->x;
        }
        $this->_out(sprintf('q %.2F 0 0 %.2F %.2F %.2F cm /I%d Do Q',$w*$this->k,$h*$this->k,$x*$this->k,($this->h-($y+$h))*$this->k,$info['i']));
        if($link) {
            $this->Link($x,$y,$w,$h,$link);
        }
        return $info;
    }
}