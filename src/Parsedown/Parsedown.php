<?php

namespace kPDF\Parsedown;

use Parsedown as BASEParsedown;

class Parsedown extends BASEParsedown {

    protected $previousFontModifier = [];
    protected array $fontDefinition = [
        'h1' =>     ['size' => 24, 'pt' => true, 'bold' => true,  'italic' => false, 'tab' => 0,  'subtab' => 0, 'font' => 'Helvetica'],
        'h2' =>     ['size' => 20, 'pt' => true, 'bold' => true,  'italic' => false, 'tab' => 0,  'subtab' => 0, 'font' => 'Helvetica'],
        'h3' =>     ['size' => 16, 'pt' => true, 'bold' => true,  'italic' => false, 'tab' => 0,  'subtab' => 0, 'font' => 'Helvetica'],
        'h4' =>     ['size' => 16, 'pt' => true, 'bold' => false, 'italic' => false, 'tab' => 0,  'subtab' => 0, 'font' => 'Helvetica'],
        'h5' =>     ['size' => 12, 'pt' => true, 'bold' => false, 'italic' => false, 'tab' => 0,  'subtab' => 0, 'font' => 'Helvetica'],
        'h6' =>     ['size' => 10, 'pt' => true, 'bold' => false, 'italic' => false, 'tab' => 0,  'subtab' => 0, 'font' => 'Helvetica'],
        'p' =>      ['size' => 10, 'pt' => true, 'bold' => false, 'italic' => false, 'tab' => 0,  'subtab' => 0, 'font' => 'dejavu'],
        'li' =>     ['size' => 10, 'pt' => true, 'bold' => false, 'italic' => false, 'tab' => 4,  'subtab' => 4, 'font' => 'Helvetica'],
        'pre' =>    ['size' => 10, 'pt' => true, 'bold' => false, 'italic' => false, 'tab' => 10, 'subtab' => 0, 'font' => 'Courier'],
    ];

    protected array $fontModifier = [
        'link' => ['bold' => false, 'italic' => false, 'underline' => false],
        'strong' => ['bold' => true, 'italic' => false, 'underline' => false],
        'em' => ['bold' => false, 'italic' => true, 'underline' => false],
        'quote' => ['bold' => false, 'italic' => false, 'underline' => false]

    ];

    protected function setFontDefinition($fpdf, $name) {
        $font = $this->fontDefinition[$name];
        $style = '';
        if ($font['bold']) {
            $style .= 'B';
        }
        if ($font['italic']) {
            $style .= 'I';
        }
        $fpdf->SetFont($font['font'], $style);
        $fpdf->setFontSize($font['size'], $font['pt']);
        return [$font['tab'], $font['subtab']];
    }

    protected function setFontModifier ($fpdf, $name) {
        $this->previousFontModifier = [$fpdf->getFontFamily(), $fpdf->getFontStyle(), $fpdf->getFontSize()];
        $font = $this->fontModifier[$name];
        $fontFamily = $fpdf->getFontFamily();
        $style = '';
        if ($font['bold']) {
            $style .= 'B';
        }
        if ($font['italic']) {
            $style .= 'I';
        }
        $fpdf->SetFont($fontFamily, $style);
        if ($font['underline']) {
            $fpdf->startUnderline();
        }
    }

    function resetFontModifier ($fpdp) {
        $fpdp->SetFont($this->previousFontModifier[0], $this->previousFontModifier[1]);
        $fpdp->setFontSize($this->previousFontModifier[2]);
    }

    public function pdf ($fpdf, $text) {
            # make sure no definitions are set
            $this->DefinitionData = array();

            # standardize line breaks
            $text = str_replace(array("\r\n", "\r"), "\n", $text);
    
            # remove surrounding line breaks
            $text = trim($text, "\n");
    
            # split text into lines
            $lines = explode("\n", $text);
    
            $fpdf->start();
            $this->pdflines($fpdf, $lines);
            $references = $fpdf->getReferences();
            $fpdf->separator();
            for ($i = 0; $i < count($references); $i++) {
                $fpdf->echo($i + 1 . '. ' . $references[$i]);
                $fpdf->br();
            }
    }

    protected function pdflines($fdpd, array $lines)
    {
        $CurrentBlock = null;

        foreach ($lines as $line)
        {
            if (chop($line) === '')
            {
                if (isset($CurrentBlock))
                {
                    $CurrentBlock['interrupted'] = true;
                }

                continue;
            }

            if (strpos($line, "\t") !== false)
            {
                $parts = explode("\t", $line);

                $line = $parts[0];

                unset($parts[0]);

                foreach ($parts as $part)
                {
                    $shortage = 4 - mb_strlen($line, 'utf-8') % 4;

                    $line .= str_repeat(' ', $shortage);
                    $line .= $part;
                }
            }

            $indent = 0;

            while (isset($line[$indent]) and $line[$indent] === ' ')
            {
                $indent ++;
            }

            $text = $indent > 0 ? substr($line, $indent) : $line;

            # ~

            $Line = array('body' => $line, 'indent' => $indent, 'text' => $text);

            # ~

            if (isset($CurrentBlock['continuable']))
            {
                $Block = $this->{'block'.$CurrentBlock['type'].'Continue'}($Line, $CurrentBlock);

                if (isset($Block))
                {
                    $CurrentBlock = $Block;

                    continue;
                }
                else
                {
                    if ($this->isBlockCompletable($CurrentBlock['type']))
                    {
                        $CurrentBlock = $this->{'block'.$CurrentBlock['type'].'Complete'}($CurrentBlock);
                    }
                }
            }

            # ~

            $marker = $text[0];

            # ~

            $blockTypes = $this->unmarkedBlockTypes;

            if (isset($this->BlockTypes[$marker]))
            {
                foreach ($this->BlockTypes[$marker] as $blockType)
                {
                    $blockTypes []= $blockType;
                }
            }

            #
            # ~

            foreach ($blockTypes as $blockType)
            {
                $Block = $this->{'block'.$blockType}($Line, $CurrentBlock);

                if (isset($Block))
                {
                    $Block['type'] = $blockType;

                    if ( ! isset($Block['identified']))
                    {
                        $Blocks []= $CurrentBlock;

                        $Block['identified'] = true;
                    }

                    if ($this->isBlockContinuable($blockType))
                    {
                        $Block['continuable'] = true;
                    }

                    $CurrentBlock = $Block;

                    continue 2;
                }
            }

            # ~

            if (isset($CurrentBlock) and ! isset($CurrentBlock['type']) and ! isset($CurrentBlock['interrupted']))
            {
                $CurrentBlock['element']['text'] .= "\n".$text;
            }
            else
            {
                $Blocks []= $CurrentBlock;

                $CurrentBlock = $this->paragraph($Line);

                $CurrentBlock['identified'] = true;
            }
        }

        # ~

        if (isset($CurrentBlock['continuable']) and $this->isBlockCompletable($CurrentBlock['type']))
        {
            $CurrentBlock = $this->{'block'.$CurrentBlock['type'].'Complete'}($CurrentBlock);
        }

        # ~

        $Blocks []= $CurrentBlock;

        unset($Blocks[0]);

        foreach ($Blocks as $Block)
        {
            if (isset($Block['hidden']))
            {
                continue;
            }
            /* do not support rendering html as of yet */
            if (isset($Block['markup'])) {
                continue;
            }

            $this->pdfelement($fdpd, $Block['element']);
        }
    }


    public function _pdftext($fpdf, $text, $nonNestables=array())
    {
        $markup = '';

        # $excerpt is based on the first occurrence of a marker

        while ($excerpt = strpbrk($text, $this->inlineMarkerList))
        {
            $marker = $excerpt[0];

            $markerPosition = strpos($text, $marker);

            $Excerpt = array('text' => $excerpt, 'context' => $text);

            foreach ($this->InlineTypes[$marker] as $inlineType)
            {
                # check to see if the current inline type is nestable in the current context

                if ( ! empty($nonNestables) and in_array($inlineType, $nonNestables))
                {
                    continue;
                }

                $Inline = $this->{'inline'.$inlineType}($Excerpt);

                if ( ! isset($Inline))
                {
                    continue;
                }

                # makes sure that the inline belongs to "our" marker

                if (isset($Inline['position']) and $Inline['position'] > $markerPosition)
                {
                    continue;
                }

                # sets a default inline position

                if ( ! isset($Inline['position']))
                {
                    $Inline['position'] = $markerPosition;
                }

                # cause the new element to 'inherit' our non nestables

                foreach ($nonNestables as $non_nestable)
                {
                    $Inline['element']['nonNestables'][] = $non_nestable;
                }
                # the text that comes before the inline
                $unmarkedText = substr($text, 0, $Inline['position']);

                # compile the unmarked text
                $markup .= $this->_pdfUnmarkedText($fpdf, $unmarkedText);

                # compile the inline
                $markup .= isset($Inline['markup']) ? $Inline['markup'] : $this->_pdfinline($fpdf, $Inline['element']);

                # remove the examined text
                $text = substr($text, $Inline['position'] + $Inline['extent']);

                continue 2;
            }

            # the marker does not belong to an inline

            $unmarkedText = substr($text, 0, $markerPosition + 1);

            $markup .= $this->_pdfUnmarkedText($fpdf, $unmarkedText);

            $text = substr($text, $markerPosition + 1);
        }

        $markup .= $this->_pdfUnmarkedText($fpdf, $text);
        return $markup;
    }

    function _pdfUnmarkedText($fpdf, $text) {
        if (empty($text)) { return $fpdf->break(); }
        $fpdf->echo($text);

    }

    function pdfelement($fpdf, $Element)
    {        
        if (!isset($Element['handler']) || !method_exists($this, '_pdf' . $Element['handler'])) {
            return;
        }
        if (!isset($Element['text'])) { 
            return;
        }

        $this->{'_pdf' . $Element['handler']}($fpdf, $Element);

    }

    function _pdfinline ($fpdf, $element) {
        switch($element['name']) {
            case 'em':
            case 'strong':
                $this->setFontModifier($fpdf, $element['name']);
                $fpdf->echo($element['text']);
                $this->resetFontModifier($fpdf);
                break;
            case 'a':
                $x1 = $fpdf->GetX();
                $y1 = $fpdf->GetY();
                $w = $fpdf->GetStringWidth($element['text']);
                $h = $fpdf->getFontSize();
                $fpdf->Link($x1, $y1, $w, $h, $element['attributes']['href']);
                $fpdf->echo($element['text']);
                $fpdf->addReference($element['attributes']['href']);
                break;
            case 'img':
                $img = $fpdf->Image($element['attributes']['src']);
                break;
            default:
                break;
        }
    }

    function _pdflines ($fpdf, $lines) {
        $this->setFontModifier($fpdf, 'quote');
        foreach($lines['text'] as $line) {
            $fpdf->indent(20);
            $this->_pdftext($fpdf, $line);
            $fpdf->break();
        }
        $this->resetFontModifier($fpdf);
    }

    function _pdfline($fpdf, $line) {
        switch ($line['name']) {
            case 'p':
            case 'h1': 
            case 'h2': 
            case 'h3': 
            case 'h4': 
            case 'h5': 
            case 'h6':
            case 'li':
                $this->setFontDefinition($fpdf, $line['name']);
                $this->_pdftext($fpdf, $line['text']);
                $fpdf->break();
                break;
            default:
                break;
        }
    }

    function _pdfelement($fpdf, $line) {
        switch ($line['name']) {
            default: 
                break;
            case 'pre':
                $tab = $this->setFontDefinition($fpdf, $line['name']);
                $lines = explode("\n", $line['text']['text']);
                foreach($lines as $text) {
                    if ($tab[0] > 0) { $fpdf->indent($tab[0]); }
                    $fpdf->echo($text);
                    $fpdf->break();
                }
                $fpdf->break();
                break;
        }
    }

    function _pdfelements ($fpdf, $line) {
        switch($line['name']) {
            default: 
                break;
            case 'ul':
            case 'ol':
                $tab = $this->setFontDefinition($fpdf, 'li');
                $i = 0;
                foreach($line['text'] as $text) {
                    if ($tab[0] > 0) { $fpdf->indent($tab[0]); }
                    $x = $fpdf->GetX();
                    if ($line['name'] === 'ol') {
                        $fpdf->echo(++$i . '.');
                    } else {
                        $fpdf->echo('-');
                    }
                    if ($tab[1] > 0) {
                        $fpdf->SetX($x + $tab[1]);
                    }
                    $fpdf->echo($text['text'][0]);
                    $fpdf->break();
                }
                $fpdf->break();
                break;
        }
    }
}