<?php

namespace kPDF\Extension;

trait Frame {
    function squaredFrame($height, $options = array())
    {
        $prevLineWidth = $this->LineWidth;
        $prevDrawColor = $this->DrawColor;

        $maxLength = isset($options['length']) ? $options['length'] : ceil($this->w - ($this->right + $this->left));
        $lineWidth = isset($options['line']) ? $options['line'] : 0.2;
        $squareSize = isset($options['square']) ? $options['square'] : 4;
        $lineType = isset($options['line-type']) ? $options['line-type'] : 'line';
        $upTo = isset($options['up-to']) ? $options['up-to'] : null;

        $vertical = true;
        if (isset($options['lined']) && $options['lined']) {
            $vertical = false;
        }

        if (isset($options['color'])) {
            $this->setColor($options['color'], 'draw');
        } else {
            $this->setColor('black', 'draw');
        }

        $this->SetLineWidth($lineWidth);

        $lineX = $startX = isset($options['x-origin']) ? $options['x-origin'] : $this->left;
        $lineY = $startY = isset($options['y-origin']) ? $options['y-origin'] : $this->GetY();
        $lenX = $stopX =  $maxLength;
        if ($lineX != $this->left && !isset($options['length'])) {
            $lenX = $this->w - ($lineX + $this->right);
        }

        if (is_null($upTo)) {
            $stopY = $startY + $height;
        } else {
            $stopY = $upTo;
        }

        $border = false;
        if ((isset($options['border']) && $options['border']) || (isset($options['skip']) && $options['skip'])) {
            $lineX += $squareSize;
            $lineY += $squareSize;
            $stopX -= $squareSize;
            $stopY -= $squareSize;
            if (isset($options['border']) && $options['border']) {
                $border = true;
            }
        }

        if ($vertical) {
            for ($i = $lineX; $i <= $stopX + $startX; $i += $squareSize) {
                $this->drawLine($i, $startY, $height, 270, $lineType);
            }
        }
        for ($i = $lineY; $i <= $stopY; $i += $squareSize) {
            $this->drawLine($startX, $i, $lenX, 0, $lineType);
        }

        if ($border) {
            if (isset($options['border-line']) && $options['border-line']) {
                $this->SetLineWidth($options['border-line']);
            }
            if (isset($options['border-color']) && $options['border-line']) {
                $this->setColor($options['border-color'], 'draw');
            }

            $this->drawLine($startX, $startY, $lenX);
            $this->drawLine($startX, $startY, $height, 270);
            $this->drawLine($lenX + $startX, $stopY + $squareSize, $height, 90);
            $this->drawLine($lenX + $startX, $stopY + $squareSize, $lenX, 180);
        }

        /* Reset to previous state */
        $this->SetLineWidth($prevLineWidth);
        $this->DrawColor = $prevDrawColor;
        $this->_out($this->DrawColor);
    }


    function frame($height, $options = array())
    {
        $prevLineWidth = $this->LineWidth;
        $prevDrawColor = $this->DrawColor;

        $maxLength = isset($options['length']) ? $options['length'] : ceil($this->w - ($this->right + $this->left));

        if (isset($options['color'])) {
            $this->setColor($options['color'], 'draw');
        } else {
            $this->setColor('black', 'draw');
        }

        $lineX = $startX = isset($options['x-origin']) ? $options['x-origin'] : $this->left;
        $lineY = $startY = isset($options['y-origin']) ? $options['y-origin'] : $this->GetY();
        $lenX = $maxLength;
        if ($lineX != $this->left && !isset($options['length'])) {
            $lenX = $this->w - ($lineX + $this->right);
        }

        $width = 0.2;
        if (isset($options['width'])) {
            $width = floatval($options['width']);
            $this->SetLineWidth($options['width']);
        }

        $width =  $width / 2;
        $this->drawLine($startX, $startY, $lenX);
        $this->drawLine($startX, $startY, $height - $width, -90);
        $this->drawLine($startX, $startY + $height,  $lenX);
        $this->drawLine($lenX + $startX, $startY, $height - $width, -90);


        /* Reset to previous state */
        $this->SetLineWidth($prevLineWidth);
        $this->DrawColor = $prevDrawColor;
        $this->_out($this->DrawColor);
    }
}