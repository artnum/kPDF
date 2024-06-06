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
    
    function addReference ($url) {
        $this->references[$this->referenceCounter] = $url;

        $this->_out(sprintf('BT /F%d %.2f Tf %.2f %.2f Td 5.8 Ts (%d) Tj ET', 
            $this->CurrentFont['i'],
            $this->FontSizePt * 0.6,
            ($this->GetX() + 1)* $this->k,
            ($this->h - $this->GetY() - $this->getFontSize()) * $this->k,
            $this->referenceCounter + 1)
        );

        return $this->referenceCounter++;
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

    function setColumn($left, $right) {
        $this->currentColumn = [$left, $right];
    }

}