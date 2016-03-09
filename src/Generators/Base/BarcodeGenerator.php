<?php
// Copyright (C) 2002-2015 Nicola Asuni - Tecnick.com LTD
//
// This file is part of TCPDF software library.
//
// TCPDF is free software: you can redistribute it and/or modify it
// under the terms of the GNU Lesser General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.
//
// TCPDF is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// See the GNU Lesser General Public License for more details.
//
// You should have received a copy of the License
// along with TCPDF. If not, see
// <http://www.tecnick.com/pagefiles/tcpdf/LICENSE.TXT>.
//
// See LICENSE.TXT file for more information.
namespace CbCaio\Boletos\Generators\Base;

abstract class BarcodeGenerator
{
    const TYPE_INTERLEAVED_2_5          = 'I25';
    const TYPE_INTERLEAVED_2_5_CHECKSUM = 'I25+';

    /**
     * Get the barcode data
     *
     * @param string $code code to print
     * @param string $type type of barcode
     * @return array barcode array
     * @public
     */
    protected function getBarcodeData($code, $type)
    {
        switch (strtoupper($type))
        {

            case self::TYPE_INTERLEAVED_2_5:
            { // Interleaved 2 of 5
                $arrcode = $this->barcode_i25($code, FALSE);
                break;
            }
            case self::TYPE_INTERLEAVED_2_5_CHECKSUM:
            { // Interleaved 2 of 5 + CHECKSUM
                $arrcode = $this->barcode_i25($code, TRUE);
                break;
            }
            default:
            {
                $arrcode = FALSE;
                break;
            }
        }

        $arrcode = $this->convertBarcodeArrayToNewStyle($arrcode);

        return $arrcode;
    }

    /**
     * Interleaved 2 of 5 barcodes.
     * Compact numeric code, widely used in industry, air cargo
     * Contains digits (0 to 9) and encodes the data in the width of both bars and spaces.
     *
     * @param string $code     (string) code to represent.
     * @param        $checksum (boolean) if true add a checksum to the code
     * @return array barcode representation.
     * @protected
     */
    protected function barcode_i25($code, $checksum = FALSE)
    {
        $chr['0'] = '11221';
        $chr['1'] = '21112';
        $chr['2'] = '12112';
        $chr['3'] = '22111';
        $chr['4'] = '11212';
        $chr['5'] = '21211';
        $chr['6'] = '12211';
        $chr['7'] = '11122';
        $chr['8'] = '21121';
        $chr['9'] = '12121';
        $chr['A'] = '11';
        $chr['Z'] = '21';
        if ($checksum)
        {
            // add checksum
            $code .= $this->checksum_s25($code);
        }
        if ((strlen($code) % 2) != 0)
        {
            // add leading zero if code-length is odd
            $code = '0' . $code;
        }
        // add start and stop codes
        $code = 'AA' . strtolower($code) . 'ZA';

        $bararray = ['code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => []];
        $k        = 0;
        $clen     = strlen($code);
        for ($i = 0; $i < $clen; $i = ($i + 2))
        {
            $char_bar   = $code{$i};
            $char_space = $code{$i + 1};
            if ((!isset($chr[ $char_bar ])) OR (!isset($chr[ $char_space ])))
            {
                // invalid character
                return FALSE;
            }
            // create a bar-space sequence
            $seq    = '';
            $chrlen = strlen($chr[ $char_bar ]);
            for ($s = 0; $s < $chrlen; $s++)
            {
                $seq .= $chr[ $char_bar ]{$s} . $chr[ $char_space ]{$s};
            }
            $seqlen = strlen($seq);
            for ($j = 0; $j < $seqlen; ++$j)
            {
                if (($j % 2) == 0)
                {
                    $t = TRUE; // bar
                } else
                {
                    $t = FALSE; // space
                }
                $w                       = $seq{$j};
                $bararray['bcode'][ $k ] = ['t' => $t, 'w' => $w, 'h' => 1, 'p' => 0];
                $bararray['maxw'] += $w;
                ++$k;
            }
        }

        return $bararray;
    }
    /**
     * Checksum for standard 2 of 5 barcodes.
     *
     * @param string $code (string) code to process.
     * @return int checksum.
     * @protected
     */
    protected function checksum_s25($code)
    {
        $len = strlen($code);
        $sum = 0;
        for ($i = 0; $i < $len; $i += 2)
        {
            $sum += $code{$i};
        }
        $sum *= 3;
        for ($i = 1; $i < $len; $i += 2)
        {
            $sum += ($code{$i});
        }
        $r = $sum % 10;
        if ($r > 0)
        {
            $r = (10 - $r);
        }
        return $r;
    }

    protected function convertBarcodeArrayToNewStyle($oldBarcodeArray)
    {
        $newBarcodeArray              = [];
        $newBarcodeArray['code']      = $oldBarcodeArray['code'];
        $newBarcodeArray['maxWidth']  = $oldBarcodeArray['maxw'];
        $newBarcodeArray['maxHeight'] = $oldBarcodeArray['maxh'];
        $newBarcodeArray['bars']      = [];
        foreach ($oldBarcodeArray['bcode'] as $oldbar)
        {
            $newBar                     = [];
            $newBar['width']            = $oldbar['w'];
            $newBar['height']           = $oldbar['h'];
            $newBar['positionVertical'] = $oldbar['p'];
            $newBar['drawBar']          = $oldbar['t'];
            $newBar['drawSpacing']      = !$oldbar['t'];

            $newBarcodeArray['bars'][] = $newBar;
        }

        return $newBarcodeArray;
    }
}
