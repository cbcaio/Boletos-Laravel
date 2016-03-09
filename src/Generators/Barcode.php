<?php

namespace CbCaio\Boletos\Generators;

use CbCaio\Boletos\Generators\Base\BarcodeGenerator;

class Barcode extends BarcodeGenerator
{
    public function getBarcode($code, $type)
    {
        $barcodeData = $this->getBarcodeData($code, $type);
        return $barcodeData;
    }
}