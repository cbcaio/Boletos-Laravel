<?php
namespace CbCaio\Boletos\Models\Beneficiario;

use CbCaio\Boletos\Calculators\Calculator;
use CbCaio\Boletos\Models\Beneficiario\Base\Beneficiario;

class BeneficiarioCEF extends Beneficiario
{
    public function __construct($load_from_config = true, array $attributes = null)
    {
        if ($load_from_config)
            parent::__construct(config('boletos'));
        else
            parent::__construct($attributes);
    }

    /*
     * Se refere a conta, no formato definido pelo banco
     */
    public function getCodigoBeneficiario()
    {
        return Calculator::formataNumero($this->getConta(), 6, 0);
    }

}