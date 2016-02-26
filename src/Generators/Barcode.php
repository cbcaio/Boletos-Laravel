<?php

namespace  CbCaio\Boletos\Generators;

use Boletos\Generators\Base\BarcodeGenerator;

class Barcode extends BarcodeGenerator
{
    public function getBarcode($code, $type, $widthFactor = 1, $totalHeight = 50)
    {
        $barcodeData = $this->getBarcodeData($code, $type);
        return $barcodeData;
    }
}