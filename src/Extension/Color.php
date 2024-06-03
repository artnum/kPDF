<?php

namespace kPDF\Extension;

trait Color
{
    function getRGB($color)
    {
        switch ($color) {
                /* CSS Level 1 */
            case 'black':
                $color = '#000';
                break;
            case 'silver':
                $color = '#c0c0c0';
                break;
            case 'gray':
                $color = '#808080';
                break;
            case 'white':
                $color = '#fff';
                break;
            case 'maroon':
                $color = '#800000';
                break;
            case 'red':
                $color = '#ff0000';
                break;
            case 'purple':
                $color = '#800080';
                break;
            case 'fuchsia':
                $color = '#ff00ff';
                break;
            case 'green':
                $color = '#008000';
                break;
            case 'lime':
                $color = '#00ff00';
                break;
            case 'olive':
                $color = '#808000';
                break;
            case 'yellow':
                $color = '#ffff00';
                break;
            case 'navy':
                $color = '#000080';
                break;
            case 'blue':
                $color = '#0000ff';
                break;
            case 'teal':
                $color = '#008080';
                break;
            case 'aqua':
                $color = '#00ffff';
                break;
                /* css level 2, 3 and 4 */
            case 'orange':
                $color = '#ffa500';
                break;
            case 'aliceblue':
                $color = '#f0f8ff';
                break;
            case 'antiquewhite':
                $color = '#faebd7';
                break;
            case 'aquamarine':
                $color = '#7fffd4';
                break;
            case 'azure':
                $color = '#f0ffff';
                break;
            case 'beige':
                $color = '#f5f5dc';
                break;
            case 'bisque':
                $color = '#ffe4c4';
                break;
            case 'blanchedalmond':
                $color = '#ffebcd';
                break;
            case 'blueviolet':
                $color = '#8a2be2';
                break;
            case 'brown':
                $color = '#a52a2a';
                break;
            case 'burlywood':
                $color = '#deb887';
                break;
            case 'cadetblue':
                $color = '#5f9ea0';
                break;
            case 'chartreuse':
                $color = '#7fff00';
                break;
            case 'chocolate':
                $color = '#d2691e';
                break;
            case 'coral':
                $color = '#ff7f50';
                break;
            case 'cornflowerblue':
                $color = '#6495ed';
                break;
            case 'cornsilk':
                $color = '#fff8dc';
                break;
            case 'crimson':
                $color = '#dc143c';
                break;
            case 'cyan':
                $color = '#00ffff';
                break;
            case 'darkblue':
                $color = '#00008b';
                break;
            case 'darkcyan':
                $color = '#008b8b';
                break;
            case 'darkgoldenrod':
                $color = '#b8860b';
                break;
            case 'darkgray':
                $color = '#a9a9a9';
                break;
            case 'darkgreen':
                $color = '#006400';
                break;
            case 'darkgrey':
                $color = '#a9a9a9';
                break;
            case 'darkkhaki':
                $color = '#bdb76b';
                break;
            case 'darkmagenta':
                $color = '#8b008b';
                break;
            case 'darkolivegreen':
                $color = '#556b2f';
                break;
            case 'darkorange':
                $color = '#ff8c00';
                break;
            case 'darkorchid':
                $color = '#9932cc';
                break;
            case 'darkred':
                $color = '#8b0000';
                break;
            case 'darksalmon':
                $color = '#e9967a';
                break;
            case 'darkseagreen':
                $color = '#8fbc8f';
                break;
            case 'darkslateblue':
                $color = '#483d8b';
                break;
            case 'darkslategray':
                $color = '#2f4f4f';
                break;
            case 'darkslategrey':
                $color = '#2f4f4f';
                break;
            case 'darkturquoise':
                $color = '#00ced1';
                break;
            case 'darkviolet':
                $color = '#9400d3';
                break;
            case 'deeppink':
                $color = '#ff1493';
                break;
            case 'deepskyblue':
                $color = '#00bfff';
                break;
            case 'dimgray':
                $color = '#696969';
                break;
            case 'dimgrey':
                $color = '#696969';
                break;
            case 'dodgerblue':
                $color = '#1e90ff';
                break;
            case 'firebrick':
                $color = '#b22222';
                break;
            case 'floralwhite':
                $color = '#fffaf0';
                break;
            case 'forestgreen':
                $color = '#228b22';
                break;
            case 'gainsboro':
                $color = '#dcdcdc';
                break;
            case 'ghostwhite':
                $color = '#f8f8ff';
                break;
            case 'gold':
                $color = '#ffd700';
                break;
            case 'goldenrod':
                $color = '#daa520';
                break;
            case 'greenyellow':
                $color = '#adff2f';
                break;
            case 'grey':
                $color = '#808080';
                break;
            case 'honeydew':
                $color = '#f0fff0';
                break;
            case 'hotpink':
                $color = '#ff69b4';
                break;
            case 'indianred':
                $color = '#cd5c5c';
                break;
            case 'indigo':
                $color = '#4b0082';
                break;
            case 'ivory':
                $color = '#fffff0';
                break;
            case 'khaki':
                $color = '#f0e68c';
                break;
            case 'lavender':
                $color = '#e6e6fa';
                break;
            case 'lavenderblush':
                $color = '#fff0f5';
                break;
            case 'lawngreen':
                $color = '#7cfc00';
                break;
            case 'lemonchiffon':
                $color = '#fffacd';
                break;
            case 'lightblue':
                $color = '#add8e6';
                break;
            case 'lightcoral':
                $color = '#f08080';
                break;
            case 'lightcyan':
                $color = '#e0ffff';
                break;
            case 'lightgoldenrodyellow':
                $color = '#fafad2';
                break;
            case 'lightgray':
                $color = '#d3d3d3';
                break;
            case 'lightgreen':
                $color = '#90ee90';
                break;
            case 'lightgrey':
                $color = '#d3d3d3';
                break;
            case 'lightpink':
                $color = '#ffb6c1';
                break;
            case 'lightsalmon':
                $color = '#ffa07a';
                break;
            case 'lightseagreen':
                $color = '#20b2aa';
                break;
            case 'lightskyblue':
                $color = '#87cefa';
                break;
            case 'lightslategray':
                $color = '#778899';
                break;
            case 'lightslategrey':
                $color = '#778899';
                break;
            case 'lightsteelblue':
                $color = '#b0c4de';
                break;
            case 'lightyellow':
                $color = '#ffffe0';
                break;
            case 'limegreen':
                $color = '#32cd32';
                break;
            case 'linen':
                $color = '#faf0e6';
                break;
            case 'magenta':
                $color = '#ff00ff';
                break;
            case 'mediumaquamarine':
                $color = '#66cdaa';
                break;
            case 'mediumblue':
                $color = '#0000cd';
                break;
            case 'mediumorchid':
                $color = '#ba55d3';
                break;
            case 'mediumpurple':
                $color = '#9370db';
                break;
            case 'mediumseagreen':
                $color = '#3cb371';
                break;
            case 'mediumslateblue':
                $color = '#7b68ee';
                break;
            case 'mediumspringgreen':
                $color = '#00fa9a';
                break;
            case 'mediumturquoise':
                $color = '#48d1cc';
                break;
            case 'mediumvioletred':
                $color = '#c71585';
                break;
            case 'midnightblue':
                $color = '#191970';
                break;
            case 'mintcream':
                $color = '#f5fffa';
                break;
            case 'mistyrose':
                $color = '#ffe4e1';
                break;
            case 'moccasin':
                $color = '#ffe4b5';
                break;
            case 'navajowhite':
                $color = '#ffdead';
                break;
            case 'oldlace':
                $color = '#fdf5e6';
                break;
            case 'olivedrab':
                $color = '#6b8e23';
                break;
            case 'orangered':
                $color = '#ff4500';
                break;
            case 'orchid':
                $color = '#da70d6';
                break;
            case 'palegoldenrod':
                $color = '#eee8aa';
                break;
            case 'palegreen':
                $color = '#98fb98';
                break;
            case 'paleturquoise':
                $color = '#afeeee';
                break;
            case 'palevioletred':
                $color = '#db7093';
                break;
            case 'papayawhip':
                $color = '#ffefd5';
                break;
            case 'peachpuff':
                $color = '#ffdab9';
                break;
            case 'peru':
                $color = '#cd853f';
                break;
            case 'pink':
                $color = '#ffc0cb';
                break;
            case 'plum':
                $color = '#dda0dd';
                break;
            case 'powderblue':
                $color = '#b0e0e6';
                break;
            case 'rosybrown':
                $color = '#bc8f8f';
                break;
            case 'royalblue':
                $color = '#4169e1';
                break;
            case 'saddlebrown':
                $color = '#8b4513';
                break;
            case 'salmon':
                $color = '#fa8072';
                break;
            case 'sandybrown':
                $color = '#f4a460';
                break;
            case 'seagreen':
                $color = '#2e8b57';
                break;
            case 'seashell':
                $color = '#fff5ee';
                break;
            case 'sienna':
                $color = '#a0522d';
                break;
            case 'skyblue':
                $color = '#87ceeb';
                break;
            case 'slateblue':
                $color = '#6a5acd';
                break;
            case 'slategray':
                $color = '#708090';
                break;
            case 'slategrey':
                $color = '#708090';
                break;
            case 'snow':
                $color = '#fffafa';
                break;
            case 'springgreen':
                $color = '#00ff7f';
                break;
            case 'steelblue':
                $color = '#4682b4';
                break;
            case 'tan':
                $color = '#d2b48c';
                break;
            case 'thistle':
                $color = '#d8bfd8';
                break;
            case 'tomato':
                $color = '#ff6347';
                break;
            case 'turquoise':
                $color = '#40e0d0';
                break;
            case 'violet':
                $color = '#ee82ee';
                break;
            case 'wheat':
                $color = '#f5deb3';
                break;
            case 'whitesmoke':
                $color = '#f5f5f5';
                break;
            case 'yellowgreen':
                $color = '#9acd32';
                break;
            case 'rebeccapurple':
                $color = '#663399';
                break;
        }

        $r = 0;
        $g = 0;
        $b = 0;
        if ($color[0] === '#') {
            $color = substr($color, 1);
            $h1 = '';
            $h2 = '';
            $h3 = '';
            if (strlen($color) == 3) {
                $h1 = $color[0] . $color[0];
                $h2 = $color[1] . $color[1];
                $h3 = $color[2] . $color[2];
            } else {
                $h1 = substr($color, 0, 2) ? substr($color, 0, 2) : 'ff';
                $h2 = substr($color, 2, 2) ? substr($color, 2, 2) : 'ff';
                $h3 = substr($color, 4, 2) ? substr($color, 4, 2) : 'ff';
            }

            $r = hexdec($h1);
            $g = hexdec($h2);
            $b = hexdec($h3);
        }

        return [$r, $g, $b];
    }

    function getReverseColor($color)
    {
        list($r, $g, $b) = $this->getRGB($color);
        $r = 255 - $r;
        $g = 255 - $g;
        $b = 255 - $b;

        return '#' . str_pad(dechex($r), 2, '0', STR_PAD_LEFT) . str_pad(dechex($g), 2, '0', STR_PAD_LEFT) . str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
    }

    function getBWFromColor($color)
    {
        list($r, $g, $b) = $this->getRGB($color);
        $r = pow($r / 255, 2.2);
        $g = pow($g / 255, 2.2);
        $b = pow($b / 255, 2.2);

        if ((0.2126 * $r + 0.7151 * $g + 0.0721 * $b) < 0.5) {
            return 'white';
        }
        return 'black';
    }

    function setColor($color, $what = 'text')
    {
        list($r, $g, $b) = $this->getRGB($color);
        switch (strtolower($what)) {
            default:
            case 'text':
                $this->SetTextColor($r, $g, $b);
                break;
            case 'draw':
                $this->SetDrawColor($r, $g, $b);
                break;
            case 'fill':
                $this->SetFillColor($r, $g, $b);
                break;
        }
    }
}