<?php
<<<<<<< HEAD:src/Models/Bancos/BancoCEF.php
namespace CbCaio\Boletos\Models\Bancos;

use CbCaio\Boletos\Calculators\Calculator;
use CbCaio\Boletos\Models\Bancos\Base\Banco;
=======
namespace  CbCaio\Boletos\Models\Bancos;

use  CbCaio\Boletos\Calculators\Calculator;
use  CbCaio\Boletos\Models\Bancos\Base\Banco;
>>>>>>> 1d091278335013eb13ca290b6a861c9c987fbfea:src/Models/Banco/CaixaEconomicaFederal.php

class BancoCEF extends Banco
{
    protected $codigo_banco = 104;
    protected $codigo_moeda = 9;
    protected $codigo_compensacao;

    /*
     * 2 Digitos ($modalidade_nosso_numero + $emissao_boleto)
     */
    protected $nosso_numero_inicio;

    /**
     * Modalidade/Carteira de Cobran�a (1-Registrada/2-Sem Registro)
     *
     * @param int $modalidade
     * Emiss�o do boleto (4-Benefici�rio)
     * @param int $emissao
     */
    public function __construct($modalidade = 2, $emissao = 4)
    {
        $this->nosso_numero_inicio = $modalidade . $emissao;
        $this->codigo_compensacao  = $this->getCodigoCompensacao();
    }


    public function getCodigoCompensacao()
    {
        return $this->geraCodigoCompensacao();
    }

    private function geraCodigoCompensacao()
    {
        $dv                 = $this->geraDVBanco();
        $codigo_compensacao = $this->getCodigoBanco() . '-' . $dv;

        return "$codigo_compensacao";

    }

    private function geraDVBanco()
    {
        $parte1 = substr($this->getCodigoBanco(), 0, 3);

        return Calculator::calculaModulo11($parte1);
    }

}