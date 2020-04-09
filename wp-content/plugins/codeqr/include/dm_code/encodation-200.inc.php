<?php
class Encodation_200
{
    var $iError = 0;
    var $i144NonStandard = false;
    var $iSelectSchema = ENCODING_ASCII, $iShift = 0;
    var $iCurrentEncoding = ENCODING_ASCII;
    var $iDataIdx = 0, $iDataLen = 0, $iData = array();
    var $iSymbolIdx = 0, $iSymbols = array(), $iSymbolMaxDataLen = 0;
    var $iSymbolShapeIdx = -1;
    var $iRSLen = 0;
    var $iFillBASE256 = true;
    var $iSymbolSizes = array(array(3, 5), array(5, 7), array(8, 10), array(12, 12), array(18, 14), array(22, 18), array(30, 20), array(36, 24), array(44, 28), array(62, 36), array(86, 42), array(114, 48), array(144, 56), array(174, 68), array(204, 84), array(280, 112), array(368, 144), array(456, 192), array(576, 224), array(696, 272), array(816, 336), array(1050, 408), array(1304, 496), array(1558, 620), array(5, 7), array(10, 11), array(16, 14), array(22, 18), array(32, 24), array(49, 28)), $iShape = array("10x10", "12x12", "14x14", "16x16", "18x18", "20x20", "22x22", "24x24", "26x26", "32x32", "36x36", "40x40", "44x44", "48x48", "52x52", "64x64", "72x72", "80x80", "88x88", "96x96", "104x104", "120x120", "132x132", "144x144", "155x62", "8x18", "8x32", "12x26", "12x36", "16x36", "16x48"), $iMappingShape = array("8x8", "10x10", "12x12", "14x14", "16x16", "18x18", "20x20", "22x22", "24x24", "28x28", "32x32", "36x36", "40x40", "44x44", "48x48", "56x56", "64x64", "72x72", "80x80", "88x88", "96x96", "108x108", "120x120", "132x132", "6x16", "6x28", "10x24", "10x32", "14x32", "14x44"), $iDataRegion = array("8x8", "10x10", "12x12", "14x14", "16x16", "18x18", "20x20", "22x22", "24x24", "14x14", "16x16", "18x18", "20x20", "22x22", "24x24", "14x14", "16x16", "18x18", "20x20", "22x22", "24x24", "18x18", "20x20", "22x22", "6x16", "6x14", "10x24", "10x16", "14x16", "14x22"), $iNDataRegions = array(1, 1, 1, 1, 1, 1, 1, 1, 1, 4, 4, 4, 4, 4, 4, 16, 16, 16, 16, 16, 16, 36, 36, 36, 1, 2, 1, 2, 2, 2), $iEncodingName = array('ENCODING_C40', 'ENCODING_TEXT', 'ENCODING_X12', 'ENCODING_EDIFACT', 'ENCODING_ASCII', 'ENCODING_BASE256', 'ENCODING_AUTO'), $iInterleaving = array(array(3, 5, 1), array(5, 7, 1), array(8, 10, 1), array(12, 12, 1), array(18, 14, 1), array(22, 18, 1), array(30, 20, 1), array(36, 24, 1), array(44, 28, 1), array(62, 36, 1), array(86, 42, 1), array(114, 48, 1), array(144, 56, 1), array(174, 68, 1), array(102, 42, 2), array(140, 56, 2), array(92, 36, 4), array(114, 48, 4), array(144, 56, 4), array(174, 68, 4), array(136, 56, 6), array(175, 68, 6), array(163, 62, 8), array(156, 62, 10), array(5, 7, 1), array(10, 11, 1), array(16, 14, 1), array(22, 18, 1), array(32, 24, 1), array(49, 28, 1));
    function __construct($aSchema = ENCODING_ASCII)
    {
        $this->iSelectSchema = $aSchema;
    }
    function SetSchema($aSchema)
    {
        $this->iSelectSchema = $aSchema;
    }
    function GetTextValues(&$aValues, $aSmallestChunk = false)
    {
        $idx = 0;
        while ($this->iDataIdx < $this->iDataLen) {
            $v = ord($this->iData[$this->iDataIdx++]);
            if ($v > 127) {
                $v -= 128;
                $aValues[$idx++] = 1;
                $aValues[$idx++] = 30;
            }
            if ($v == 32 || ($v >= 48 && $v <= 57) || ($v >= 97 && $v <= 122)) {
                $shift = 0;
            } elseif ($v <= 31) {
                $shift           = 1;
                $aValues[$idx++] = 0;
            } elseif (($v >= 33 && $v <= 47) || ($v >= 58 && $v <= 64) || ($v >= 91 && $v <= 95)) {
                $shift           = 2;
                $aValues[$idx++] = 1;
            } elseif ($v == 96 || ($v >= 65 && $v <= 90) || ($v >= 123 && $v <= 127)) {
                $shift           = 3;
                $aValues[$idx++] = 2;
            }
            switch ($shift) {
                case 0:
                    if ($v == 32)
                        $v = 3;
                    elseif ($v >= 48 && $v <= 57)
                        $v -= 44;
                    elseif ($v >= 97 && $v <= 122)
                        $v -= 83;
                    break;
                case 1:
                    break;
                case 2:
                    if ($v >= 33 && $v <= 47)
                        $v -= 33;
                    elseif ($v >= 58 && $v <= 64)
                        $v -= 43;
                    elseif ($v >= 91 && $v <= 95)
                        $v -= 69;
                    break;
                case 3:
                    if ($v == 96)
                        $v = 0;
                    elseif ($v >= 65 && $v <= 90)
                        $v -= 64;
                    elseif ($v >= 123 && $v <= 127)
                        $v -= 96;
                    break;
            }
            $aValues[$idx++] = $v;
            if ($aSmallestChunk && ($idx % 3 == 0))
                return $idx;
        }
    }
    function GetC40Values(&$aValues, $aSmallestChunk = false)
    {
        $idx = 0;
        while ($this->iDataIdx < $this->iDataLen) {
            $v = ord($this->iData[$this->iDataIdx++]);
            if ($v > 127) {
                $v -= 128;
                $aValues[$idx++] = 1;
                $aValues[$idx++] = 30;
            }
            if ($v == 32 || ($v >= 48 && $v <= 57) || ($v >= 65 && $v <= 90)) {
                $shift = 0;
            } elseif ($v <= 31) {
                $shift           = 1;
                $aValues[$idx++] = 0;
            } elseif (($v >= 33 && $v <= 47) || ($v >= 58 && $v <= 64) || ($v >= 91 && $v <= 95)) {
                $shift           = 2;
                $aValues[$idx++] = 1;
            } elseif ($v >= 96 && $v <= 127) {
                $shift           = 3;
                $aValues[$idx++] = 2;
            }
            switch ($shift) {
                case 0:
                    if ($v == 32)
                        $v = 3;
                    elseif ($v >= 48 && $v <= 57)
                        $v -= 44;
                    elseif ($v >= 65 && $v <= 90)
                        $v -= 51;
                    break;
                case 1:
                    break;
                case 2:
                    if ($v >= 33 && $v <= 47)
                        $v -= 33;
                    elseif ($v >= 58 && $v <= 64)
                        $v -= 43;
                    elseif ($v >= 91 && $v <= 95)
                        $v -= 69;
                    break;
                case 3:
                    if ($v >= 96 && $v <= 127)
                        $v -= 96;
                    break;
            }
            $aValues[$idx++] = $v;
            if ($aSmallestChunk && ($idx % 3 == 0))
                return $idx;
        }
    }
    function Encode_TEXT_C40($aEncoding = ENCODING_TEXT, $aSmallestChunk = false)
    {
        $values = array();
        if ($aEncoding == ENCODING_TEXT)
            $this->GetTextValues($values, $aSmallestChunk);
        else
            $this->GetC40Values($values, $aSmallestChunk);
        $n       = count($values);
        $nchunks = floor(count($values) / 3);
        $rest    = $n % 3;
        $shift   = false;
        if ($nchunks > 0) {
            for ($i = 0; $i < $nchunks - 1; ++$i) {
                $v1    = $values[$i * 3];
                $shift = $v1 <= 2 && !$shift;
                $v2    = $values[$i * 3 + 1];
                $shift = $v2 <= 2 && !$shift;
                $v3    = $values[$i * 3 + 2];
                $shift = $v3 <= 2 && !$shift;
                $val   = 1600 * $v1 + 40 * $v2 + $v3 + 1;
                $this->_Put(floor($val / 256));
                $this->_Put($val % 256);
            }
            $i     = $nchunks - 1;
            $v1    = $values[$i * 3];
            $shift = $v1 <= 2 && !$shift;
            $v2    = $values[$i * 3 + 1];
            $shift = $v2 <= 2 && !$shift;
            $v3    = $values[$i * 3 + 2];
            $shift = $v3 <= 2 && !$shift;
            if ($rest == 1 && $shift) {
                $v3 = 0;
            }
            $val = 1600 * $v1 + 40 * $v2 + $v3 + 1;
            $this->_Put(floor($val / 256));
            $this->_Put($val % 256);
        }
        if ($this->iError < 0)
            return false;
        $remainingSymbols = $this->iSymbolMaxDataLen - $this->iSymbolIdx;
        if ($rest == 2 && $remainingSymbols >= 2) {
            $val = 1600 * $values[$nchunks * 3] + 40 * $values[$nchunks * 3 + 1] + 0 + 1;
            $v1  = floor($val / 256);
            $v2  = $val % 256;
            $this->_Put($v1);
            $this->_Put($v2);
        } elseif ($rest == 1 && $remainingSymbols >= 2) {
            $this->_Put(254);
            $this->iCurrentEncoding = ENCODING_ASCII;
            $this->_Put(ord($this->iData[$this->iDataLen - 1]) + 1);
        } elseif ($rest == 1 && $remainingSymbols == 1) {
            $this->iCurrentEncoding = ENCODING_ASCII;
            $this->_Put(ord($this->iData[$this->iDataLen - 1]) + 1);
        } elseif ($rest >= 1) {
            $this->iError = -1;
        } elseif ($rest == 0 && $remainingSymbols >= 0) {
        } else {
            $this->iError = -7;
        }
        return $this->iError >= 0;
    }
    function Encode_ASCII($aCnt = -1)
    {
        if ($aCnt == -1)
            $aCnt = $this->iDataLen - $this->iDataIdx;
        $i = 0;
        while ($i < $aCnt) {
            $c1 = $this->iData[$this->iDataIdx++];
            $c2 = false;
            if ($this->iDataIdx < $this->iDataLen) {
                $c2 = $this->iData[$this->iDataIdx];
            }
            if (ctype_digit($c1) && ctype_digit($c2)) {
                $this->_Put(intval($c1 . $c2) + 130);
                ++$this->iDataIdx;
                ++$i;
            } else {
                $v = ord($c1);
                if ($v <= 127) {
                    $this->_Put($v + 1);
                } else {
                    $this->_Put(235);
                    $this->_Put($v - 128);
                }
            }
            ++$i;
        }
        return $this->iError >= 0;
    }
    function _Put($aCW)
    {
        if ($this->iSymbolIdx >= $this->iSymbolMaxDataLen) {
            $this->iError = -1;
            return;
        }
        $this->iSymbols[$this->iSymbolIdx++] = $aCW;
    }
    function _Get()
    {
        if ($this->iDataIdx >= $this->iDataLen) {
            $this->iError = -5;
            return -1;
        } else
            return $this->iData[$this->iDataIdx++];
    }
    function _Peek($aLookAhead = 0)
    {
        if ($this->iDataIdx + $aLookAhead >= $this->iDataLen) {
            $this->iError = -6;
            return -1;
        } else
            return $this->iData[$this->iDataIdx + $aLookAhead];
    }
    function GetX12Value($v)
    {
        if ($v == 32)
            return 3;
        elseif ($v >= 48 && $v <= 57)
            return $v - 44;
        elseif ($v >= 65 && $v <= 90)
            return $v - 51;
        elseif ($v == 13)
            return 0;
        elseif ($v == 42)
            return 1;
        elseif ($v == 62)
            return 2;
        else {
            $this->iError = -8;
            return false;
        }
    }
    function Encode_X12($aSmallestChunk = false)
    {
        $remaining = $this->iDataLen - $this->iDataIdx;
        $n         = floor($remaining / 3);
        $idx       = 0;
        for ($i = 0; $i < $n; ++$i) {
            $v1 = $this->GetX12Value(ord($this->iData[$this->iDataIdx++]));
            $v2 = $this->GetX12Value(ord($this->iData[$this->iDataIdx++]));
            $v3 = $this->GetX12Value(ord($this->iData[$this->iDataIdx++]));
            if ($v1 === false || $v2 === false || $v3 === false) {
                return false;
            }
            $remaining -= 3;
            $val = $v1 * 1600 + $v2 * 40 + $v3 + 1;
            $this->_Put(floor($val / 256));
            $this->_Put($val % 256);
            if ($aSmallestChunk)
                return $this->iError >= 0;
        }
        if ($remaining > 0) {
            $available = $this->iSymbolMaxDataLen - $this->iSymbolIdx;
            if ($remaining > $available) {
                $this->iError = -1;
                return false;
            }
            if ($available > 1) {
                $this->_Put(254);
                $this->iCurrentEncoding = ENCODING_ASCII;
            }
            $this->_Put(ord($this->iData[$this->iDataIdx++]) + 1);
            if ($remaining > 1) {
                $this->_Put(ord($this->iData[$this->iDataIdx++]) + 1);
            }
        }
        return $this->iError >= 0;
    }
    function Encode_EDIFACT()
    {
        $this->iError = -99;
        return false;
        $remaining = $this->iDataLen - $this->iDataIdx;
        $n         = floor($remaining / 4);
        $idx       = $this->iDataIdx;
        for ($i = 0; $i < $n; ++$i) {
            $c1 = $this->GetEDIFACTValue(ord($this->iData[$idx++]));
            $c2 = $this->GetEDIFACTValue(ord($this->iData[$idx++]));
            $c3 = $this->GetEDIFACTValue(ord($this->iData[$idx++]));
            $c4 = $this->GetEDIFACTValue(ord($this->iData[$idx++]));
            $remaining -= 4;
            $v1 = ((0x3f & $c1) << 2) | ((0x30 & $c1) >> 4);
            $v2 = ((0x0F & $c2) << 4) | ((0x3F & $c3) >> 2);
            $v3 = ((0x03 & $c3) << 6) | (0x3F & $c4);
        }
        return $this->iError >= 0;
    }
    function Encode_BASE256($aCnt = -1, $aStoreLengthCnt = true)
    {
        if ($aCnt == -1)
            $aCnt = $this->iDataLen - $this->iDataIdx;
        if ($aCnt > ($this->iSymbolMaxDataLen - $this->iSymbolIdx)) {
            $this->iError = -2;
            return false;
        }
        if ($aStoreLengthCnt) {
            if ($aCnt >= 1 && $aCnt <= 249) {
                $v    = $aCnt;
                $rand = ((149 * ($this->iSymbolIdx + 1)) % 255) + 1;
                $v += $rand;
                $v = $v <= 255 ? $v : $v - 256;
                $this->_Put($v);
            } else {
                $v    = floor($aCnt / 250) + 249;
                $rand = ((149 * ($this->iSymbolIdx + 1)) % 255) + 1;
                $v += $rand;
                $v = $v <= 255 ? $v : $v - 256;
                $this->_Put($v);
                $v    = $aCnt % 250;
                $rand = ((149 * ($this->iSymbolIdx + 1)) % 255) + 1;
                $v += $rand;
                $v = $v <= 255 ? $v : $v - 256;
                $this->_Put($v);
            }
        }
        if ($this->iError < 0) {
            return false;
        }
        $i = 0;
        while ($i < $aCnt && $this->iError >= 0) {
            $v    = ord($this->iData[$this->iDataIdx++]);
            $rand = ((149 * ($this->iSymbolIdx + 1)) % 255) + 1;
            $v += $rand;
            $v = $v <= 255 ? $v : $v - 256;
            $this->_Put($v);
            ++$i;
        }
        return $this->iError >= 0;
    }
    function AutoSize(&$aData)
    {
        $this->iRSLen = 0;
        ;
        $i = 0;
        $n = count($this->iSymbolSizes);
        $m = floor(count($aData) / 2);
        while ($i < $n && $this->iSymbolSizes[$i][0] < $m)
            ++$i;
        if ($i >= $n) {
            $this->iError = -1;
            return false;
        }
        do {
            $symbols               = array();
            $this->iSymbolShapeIdx = $i;
            $this->_Encode($aData, $symbols);
            ++$i;
        } while ($i < $n && $this->iError < 0);
        if ($this->iError < 0) {
            $this->iError = -1;
            return false;
        } else {
            return $this->iSymbolShapeIdx;
        }
    }
    function GetError($aAsString = false)
    {
        return $this->iError;
    }
    function AddErrorCoding()
    {
        $spec         = $this->iInterleaving[$this->iSymbolShapeIdx];
        $nDataSymbols = $this->iSymbolSizes[$this->iSymbolShapeIdx][0];
        $nBlocks      = $spec[2];
        $nd           = $spec[0];
        $ne           = $spec[1];
        $block        = array();
        for ($i = 0; $i < $nDataSymbols; ++$i) {
            $bidx                               = $i % $nBlocks;
            $block[$bidx][floor($i / $nBlocks)] = $this->iSymbols[$i];
        }
        $rs = new ReedSolomon(8, $ne);
        if ($rs === false) {
            $this->iError = -16;
            return false;
        }
        $offset = 0;
        if ($this->iSymbolShapeIdx == 23 && $this->i144NonStandard)
            $offset = 2;
        for ($i = 0; $i < $nBlocks; ++$i) {
            $rs->AppendCode($block[$i]);
            if ($this->iSymbolShapeIdx == 23 && $i == 8) {
                --$nd;
            }
            for ($j = 0; $j < $ne; ++$j) {
                $this->iSymbols[$nDataSymbols + $offset + $j * $nBlocks] = $block[$i][$nd + $j];
            }
            ++$offset;
            $offset %= $nBlocks;
        }
    }
    function NextAutoMode($aCurrentMode)
    {
        if ($aCurrentMode == ENCODING_ASCII) {
            $cntASCII = 0;
            $cntC40   = 1;
            $cntTEXT  = 1;
            $cntX12   = 1;
            $cntEDF   = 1;
            $cntB256  = 1.25;
        } else {
            $cntASCII = 1;
            $cntC40   = 2;
            $cntTEXT  = 2;
            $cntX12   = 2;
            $cntEDF   = 2;
            $cntB256  = 2.25;
        }
        switch ($aCurrentMode) {
            case ENCODING_C40:
                $cntC40 = 0;
                break;
            case ENCODING_TEXT:
                $cntTEXT = 0;
                break;
            case ENCODING_X12:
                $cntX12 = 0;
                break;
            case ENCODING_EDIFACT:
                $cntEDF = 0;
                break;
            case ENCODING_BASE256:
                $cntB256 = 0;
                break;
        }
        $idx = $this->iDataIdx;
        while ($idx < $this->iDataLen) {
            $c1         = $this->iData[$idx++];
            $o          = ord($c1);
            $isExtASCII = $o > 127;
            $isDigit    = ctype_digit($c1);
            if ($isDigit)
                $cntASCII += 0.5;
            elseif ($isExtASCII)
                $cntASCII = ceil($cntASCII) + 2;
            else
                $cntASCII = ceil($cntASCII) + 1;
            if ($o == 32 || $isDigit || ($o >= 65 && $o <= 90))
                $cntC40 += 2 / 3;
            elseif ($isExtASCII)
                $cntC40 += 8 / 3;
            else
                $cntC40 += 4 / 3;
            if ($o == 32 || $isDigit || ($o >= 97 && $o <= 122))
                $cntTEXT += 2 / 3;
            elseif ($isExtASCII)
                $cntTEXT += 8 / 3;
            else
                $cntTEXT += 4 / 3;
            if ($o == 32 || $o == 13 || $o == 42 || $o == 62 || $isDigit || ($o >= 65 && $o <= 90))
                $cntX12 += 2 / 3;
            elseif ($isExtASCII)
                $cntX12 += 13 / 3;
            else
                $cntX12 += 10 / 3;
            if ($o == 232 || $o == 233 || $o == 234 || $o == 241)
                $cntB256 += 4;
            else
                ++$cntB256;
            $n = $idx - $this->iDataIdx;
            if ($n >= 4) {
                $ret = -1;
                if ($cntASCII + 1 <= $cntC40 && $cntASCII + 1 <= $cntTEXT && $cntASCII + 1 <= $cntX12 && $cntASCII + 1 <= $cntB256) {
                    $ret = ENCODING_ASCII;
                } elseif (($cntB256 + 1 <= $cntASCII) || ($cntB256 < $cntC40 && $cntB256 < $cntX12 && $cntB256 < $cntTEXT)) {
                    $ret = ENCODING_BASE256;
                } elseif ($cntTEXT + 1 < $cntC40 && $cntTEXT + 1 < $cntB256 && $cntTEXT + 1 < $cntX12 && $cntTEXT + 1 < $cntASCII) {
                    $ret = ENCODING_TEXT;
                } elseif ($cntX12 + 1 < $cntC40 && $cntX12 + 1 < $cntTEXT && $cntX12 + 1 < $cntB256 && $cntX12 + 1 < $cntASCII) {
                    $ret = ENCODING_X12;
                } elseif ($cntC40 + 1 < $cntTEXT && $cntC40 + 1 < $cntB256 && $cntC40 + 1 < $cntASCII) {
                    if ($cntC40 < $cntX12)
                        $ret = ENCODING_C40;
                    elseif ($cntC40 == $cntX12) {
                        $ret = ENCODING_C40;
                        if ($idx < $this->iDataLen) {
                            $c2 = $this->iData[$idx];
                            if (ord($c2) == 13 || ord($c2) == 42 || ord($c2) == 62)
                                $ret = ENCODING_X12;
                        }
                    }
                }
                if ($ret >= 0) {
                    return $ret;
                }
            }
        }
        $cntASCII = round($cntASCII);
        $cntC40   = round($cntC40);
        $cntTEXT  = round($cntTEXT);
        $cntX12   = round($cntX12);
        $cntB256  = round($cntB256);
        if ($cntASCII <= $cntC40 && $cntASCII <= $cntTEXT && $cntASCII <= $cntX12 && $cntASCII <= $cntB256) {
            $ret = ENCODING_ASCII;
        } elseif ($cntB256 < $cntC40 && $cntB256 < $cntTEXT && $cntB256 < $cntX12 && $cntB256 < $cntASCII) {
            $ret = ENCODING_BASE256;
        } elseif ($cntTEXT < $cntC40 && $cntTEXT < $cntB256 && $cntTEXT < $cntX12 && $cntTEXT < $cntASCII) {
            $ret = ENCODING_TEXT;
        } elseif ($cntX12 < $cntC40 && $cntX12 < $cntTEXT && $cntX12 < $cntB256 && $cntX12 < $cntASCII) {
            $ret = ENCODING_X12;
        } else
            $ret = ENCODING_C40;
        return $ret;
    }
    function Encode_Auto()
    {
        $latchTo                = array(
            ENCODING_BASE256 => 231,
            ENCODING_C40 => 230,
            ENCODING_TEXT => 239,
            ENCODING_X12 => 238,
            ENCODING_EDIFACT => 240
        );
        $this->iCurrentEncoding = ENCODING_ASCII;
        $startBASE256           = true;
        $cntBASE256             = 0;
        while ($this->iDataIdx < $this->iDataLen) {
            if ($this->iError < 0) {
                return false;
            }
            $charsLeft = $this->iDataLen - $this->iDataIdx;
            $c1        = $this->iData[$this->iDataIdx];
            $c2        = false;
            $c3        = false;
            if ($charsLeft >= 2)
                $c2 = $this->iData[$this->iDataIdx + 1];
            if ($charsLeft >= 3)
                $c3 = $this->iData[$this->iDataIdx + 2];
            switch ($this->iCurrentEncoding) {
                case ENCODING_ASCII:
                    if (ctype_digit($c1) && ctype_digit($c2)) {
                        $this->Encode_ASCII(2);
                    }
                    $this->iCurrentEncoding = $this->NextAutoMode(ENCODING_ASCII);
                    if ($this->iCurrentEncoding != ENCODING_ASCII) {
                        $this->_Put($latchTo[$this->iCurrentEncoding]);
                    } elseif (!ctype_digit($c1) || !ctype_digit($c2)) {
                        $this->Encode_ASCII(1);
                    }
                    break;
                case ENCODING_C40:
                    $this->Encode_TEXT_C40(ENCODING_C40, true);
                    if ($this->iDataIdx < $this->iDataLen) {
                        $this->iCurrentEncoding = $this->NextAutoMode(ENCODING_C40);
                        if ($this->iCurrentEncoding != ENCODING_C40) {
                            $this->_Put(254);
                            if ($this->iCurrentEncoding != ENCODING_ASCII) {
                                $this->_Put($latchTo[$this->iCurrentEncoding]);
                            }
                        }
                    }
                    break;
                case ENCODING_TEXT:
                    $this->Encode_TEXT_C40(ENCODING_TEXT, true);
                    if ($this->iDataIdx < $this->iDataLen) {
                        $this->iCurrentEncoding = $this->NextAutoMode(ENCODING_TEXT);
                        if ($this->iCurrentEncoding != ENCODING_TEXT) {
                            $this->_Put(254);
                            if ($this->iCurrentEncoding != ENCODING_ASCII) {
                                $this->_Put($latchTo[$this->iCurrentEncoding]);
                            }
                        }
                    }
                    break;
                case ENCODING_BASE256:
                    $this->iCurrentEncoding = $this->NextAutoMode(ENCODING_BASE256);
                    if ($this->iCurrentEncoding == ENCODING_BASE256) {
                        if ($startBASE256) {
                            $base256CounterIdx = $this->iSymbolIdx;
                            $startBASE256      = false;
                        }
                        $this->Encode_BASE256(1, $startBASE256);
                        ++$cntBASE256;
                    } else {
                        $startBASE256 = true;
                        if ($$cntBASE256 >= 1 && $cntBASE256 <= 249) {
                            $v    = $cntBASE256;
                            $rand = ((149 * ($base256CounterIdx + 1)) % 255) + 1;
                            $v += $rand;
                            $v                                  = $v <= 255 ? $v : $v - 256;
                            $this->iSymbols[$base256CounterIdx] = $v;
                        } else {
                            $n = oount($this->iSymbols);
                            for ($i = $n; $i > $base256CounterIdx; --$i) {
                                $this->iSymbols[$i] = $this->iSymbols[$i - 1];
                            }
                            $v    = floor($cntBASE256 / 250) + 249;
                            $rand = ((149 * ($base256CounterIdx + 1)) % 255) + 1;
                            $v += $rand;
                            $v                                  = $v <= 255 ? $v : $v - 256;
                            $this->iSymbols[$base256CounterIdx] = $v;
                            $v                                  = $cntBASE256 % 250;
                            $rand                               = ((149 * ($base256CounterIdx + 2)) % 255) + 1;
                            $v += $rand;
                            $v                                      = $v <= 255 ? $v : $v - 256;
                            $this->iSymbols[$base256CounterIdx + 1] = $v;
                        }
                        $cntBASE256 = 0;
                        $this->_Put(254);
                        if ($this->iCurrentEncoding != ENCODING_ASCII) {
                            $this->_Put($latchTo[$this->iCurrentEncoding]);
                        }
                    }
                    break;
                case ENCODING_X12:
                    $this->Encode_X12(true);
                    if ($this->iDataIdx < $this->iDataLen) {
                        $this->iCurrentEncoding = $this->NextAutoMode(ENCODING_X12);
                        if ($this->iCurrentEncoding != ENCODING_X12) {
                            $this->_Put(254);
                            if ($this->iCurrentEncoding != ENCODING_ASCII) {
                                $this->_Put($latchTo[$this->iCurrentEncoding]);
                            }
                        }
                    }
                    break;
                case ENCODING_EDIFACT:
                    $this->iError = -99;
                    return false;
            }
        }
    }
    function Encode($aData, &$aSymbols, $aSymbolShapeIdx = -1)
    {
        $this->iSymbolShapeIdx = $aSymbolShapeIdx;
        if ($aSymbolShapeIdx >= 0 && $aSymbolShapeIdx < count($this->iSymbolSizes)) {
            $symbolDataSize = $this->iSymbolSizes[$aSymbolShapeIdx][0];
            $this->_Encode($aData, $aSymbols);
        } elseif ($this->iSelectSchema != ENCODING_AUTO) {
            $this->AutoSize($aData);
            $aSymbols = $this->iSymbols;
        } else {
            $aSymbols = array();
            $this->_Encode($aData, $aSymbols);
        }
        if ($this->iError < 0) {
            $aSymbols = array();
            return false;
        } else
            return true;
    }
    function _Encode($aData, &$aSymbols)
    {
        if ($this->iSelectSchema != ENCODING_AUTO) {
            $this->iSymbolMaxDataLen = $this->iSymbolSizes[$this->iSymbolShapeIdx][0];
        }
        $this->iDataLen   = count($aData);
        $this->iData      = $aData;
        $this->iDataIdx   = 0;
        $this->iSymbolIdx = 0;
        $this->iSymbols   = array();
        $this->iError     = 0;
        while ($this->iDataIdx < $this->iDataLen && $this->iError >= 0) {
            switch ($this->iSelectSchema) {
                case ENCODING_AUTO:
                    $idx                     = $this->iSymbolShapeIdx < 0 ? 23 : $this->iSymbolShapeIdx;
                    $this->iSymbolMaxDataLen = $this->iSymbolSizes[$idx][0];
                    $this->Encode_Auto();
                    if ($this->iSymbolShapeIdx < 0) {
                        $i = 0;
                        while ($i < 23 && ($this->iSymbolSizes[$i][0] < $this->iSymbolIdx))
                            ++$i;
                        if ($i >= 23) {
                            $this->iError = -1;
                            return false;
                        }
                        $this->iSymbolShapeIdx   = $i;
                        $this->iSymbolMaxDataLen = $this->iSymbolSizes[$i][0];
                    }
                    break;
                case ENCODING_ASCII:
                    $this->iCurrentEncoding = ENCODING_ASCII;
                    $this->Encode_ASCII();
                    break;
                case ENCODING_C40:
                    if ($this->iDataLen < 3) {
                        $this->iError = -3;
                        return false;
                    }
                    $this->_Put(230);
                    $this->iCurrentEncoding = ENCODING_C40;
                    $this->Encode_TEXT_C40(ENCODING_C40);
                    break;
                case ENCODING_TEXT:
                    if ($this->iDataLen < 3) {
                        $this->iError = -4;
                        return false;
                    }
                    $this->_Put(239);
                    $this->iCurrentEncoding = ENCODING_TEXT;
                    $this->Encode_TEXT_C40(ENCODING_TEXT);
                    break;
                case ENCODING_X12:
                    if ($this->iDataLen < 3) {
                        $this->iError = -10;
                        return false;
                    }
                    $this->iCurrentEncoding = ENCODING_X12;
                    $this->_Put(238);
                    $this->Encode_X12();
                    break;
                case ENCODING_EDIFACT:
                    $this->_Put(240);
                    $this->iCurrentEncoding = ENCODING_EDIFACT;
                    $this->Encode_EDIFACT();
                    break;
                case ENCODING_BASE256:
                    $this->_Put(231);
                    $this->iCurrentEncoding = ENCODING_BASE256;
                    $this->Encode_BASE256();
                    break;
            }
        }
        if ($this->iError < 0) {
            return false;
        }
        $n           = $this->iSymbolMaxDataLen - $this->iSymbolIdx;
        $firstPadPos = $this->iSymbolIdx;
        if ($n > 1 && $this->iSelectSchema != ENCODING_ASCII && $this->iSelectSchema != ENCODING_BASE256) {
            if ($this->iCurrentEncoding != ENCODING_ASCII) {
                $this->_Put(254);
                --$n;
            }
        }
        if ($n > 0) {
            $this->_Put(129);
            while ($n > 1) {
                $pad  = 129;
                $rand = (149 * ($this->iSymbolIdx + 1)) % 253 + 1;
                $pad += $rand;
                $pad = $pad <= 254 ? $pad : $pad - 254;
                $this->_Put($pad);
                --$n;
            }
        }
        $aSymbols = $this->iSymbols;
        return $this->iError >= 0;
    }
}