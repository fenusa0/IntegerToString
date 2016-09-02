<?php

namespace Fenusa0;

class IntegerToString
{
    private $number;

    private $negative;
    private $billions;
    private $millions;
    private $thousands;
    private $hundreds;

    /**
     *
     */
    public function __construct($number = 0)
    {
        $this->setNumber($number);
    }

    /**
     *
     */
    public function setNumber($number)
    {
        $this->number = (int) $number;

        if ($this->number < 0) {
            $this->negative = true;
            $this->number *= -1;
        } else {
            $this->negative = false;
        }

        if ($this->number > 999999999) {
            $this->billions = floor($this->number / 1000000000);
        } else {
            $this->billions = null;
        }

        if ($this->number > 999999) {
            $this->millions = $this->number % 1000000000;
            $this->millions = floor($this->millions / 1000000);
        } else {
            $this->millions = null;
        }

        if ($this->number > 999) {
            $this->thousands = $this->number % 1000000;
            $this->thousands = floor($this->thousands / 1000);
        } else {
            $this->thousands = null;
        }

        if ($this->number > 0) {
            $this->hundreds = $this->number % 1000;
        } else {
            $this->hundreds = null;
        }
    }

    /**
     *
     */
    public function getNumber()
    {
        return $this->negative ? $this->number*-1 : $this->number;
    }

    /**
     *
     */
    public function toText()
    {
        if ($this->number == 0) {
            return "cero";
        }

        $text = "";

        if ($this->negative) {
            $text .= " menos ";
        }

        if ($this->billions) {
            if ($this->billions == 1) {
                $text .= " mil ";
            } else {
                $text .= " ".$this->partToText($this->billions, true)." mil ";
            }
        }

        if ($this->millions) {
            if ($this->millions == 1) {
                $text .= " un millón ";
            } else {
                $text .= $this->partToText($this->millions, true)." millones ";
            }
        } else if ($this->billions) {
            $text .= " millones ";
        }

        if ($this->thousands) {
            if ($this->thousands == 1) {
                $text .= " mil ";
            } else {
                $text .= " ".$this->partToText($this->thousands, true)." mil ";
            }
        }

        if ($this->hundreds) {
            $text .= " ".$this->partToText($this->hundreds);
        }

        return preg_replace('/\s+/', ' ', $text);
    }

    /**
     *
     */
    public function __toString()
    {
        return $this->toText();
    }

    /**
     *
     */
    protected function partToText($part, $thousands = false)
    {
        if ($part > 999) {
            throw new \Exception("Parameter must be <= 999");
        }

        if ($part > 0) {
            $tens = $part % 100;
        } else {
            $tens = null;
        }

        $text = "";

        $text .= $this->hundredToText(floor($part / 100), $tens % 100);

        $text .= $this->tensToText(floor($tens / 10), $tens % 10);

        if ($tens < 10 || $tens > 15) {
            $text .= $this->unitsToText($tens % 10, $thousands);
        }

        return $text;
    }

    /**
     *
     */
    protected function hundredToText($digit, $tens = null, $thousands = false)
    {
        if ($digit > 10) {
            throw new \Exception("Parameter must be <= 9");
        }

        switch ($digit) {
            case 0: return ""; break;
            case 1: return $tens ? "ciento" : "cien"; break;
            case 5: return "quinientos "; break;
            case 7: return "setecientos "; break;
            case 9: return "novecientos "; break;
            default: return $this->unitsToText($digit, $thousands)."cientos "; break;
        }
    }

    /**
     *
     */
    protected function tensToText($digit, $unit = null)
    {
        if ($digit > 10) {
            throw new \Exception("Parameter must be <= 9");
        }

        switch ($digit) {
            case 0: return; break;
            case 1:
                if ($unit == 0) {
                    return "diez";
                } elseif ($unit >= 6) {
                    return "dieci";
                } else {
                    switch ($unit) {
                        case 1: return "once"; break;
                        case 2: return "doce"; break;
                        case 3: return "trece"; break;
                        case 4: return "catorce"; break;
                        case 5: return "quince"; break;
                    }
                }
                break;
            case 2: return $unit ? "veinti" : "veinte"; break;
            case 3: $text = "treinta"; break;
            case 4: $text = "cuarenta"; break;
            case 5: $text = "cincuenta"; break;
            case 6: $text = "sesenta"; break;
            case 7: $text = "setenta"; break;
            case 8: $text = "ochenta"; break;
            case 9: $text = "noventa"; break;
        }

        return $unit ? $text." y " : $text;
    }

    /**
     *
     */
    protected function unitsToText($digit, $noO = false)
    {
        if ($digit > 10) {
            throw new \Exception("Parameter must be <= 9");
        }

        switch ($digit) {
            case 1: return $noO ? "un" : "uno"; break;
            case 2: return "dos"; break;
            case 3: return "tres"; break;
            case 4: return "cuatro"; break;
            case 5: return "cinco"; break;
            case 6: return "seis"; break;
            case 7: return "siete"; break;
            case 8: return "ocho"; break;
            case 9: return "nueve"; break;
        }

        return "";
    }
}
