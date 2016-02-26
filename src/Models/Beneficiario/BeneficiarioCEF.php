<?php
namespace Boletos\Models\Beneficiario;

use Boletos\Calculators\Calculator;
use Boletos\Models\Beneficiario\Base\Beneficiario;

class BeneficiarioCEF extends Beneficiario
{
    public function __construct()
    {
        $config = config('boleto');

        $this->razao_social  = $config['razao_social'];
        $this->endereco      = $config['endereco'];
        $this->cpf_cnpj      = $config['cpf_cnpj'];
        $this->cidade_estado = $config['cidade_estado'];

        $this->agencia  = $config['agencia'];
        $this->conta    = $config['conta'];
        $this->carteira = $config['carteira'];
    }

    public function getCodigoBeneficiario()
    {
        return Calculator::formataNumero($this->getConta(), 6, 0);
    }

}