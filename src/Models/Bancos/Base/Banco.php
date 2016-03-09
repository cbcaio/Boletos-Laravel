<?php
<<<<<<< HEAD:src/Models/Bancos/Base/Banco.php
namespace CbCaio\Boletos\Models\Bancos\Base;

use CbCaio\Boletos\Models\Bancos\Contracts\BancoInterface;
=======
namespace  CbCaio\Boletos\Models\Bancos\Base;

use  CbCaio\Boletos\Models\Bancos\Contracts\BancoInterface;
>>>>>>> 1d091278335013eb13ca290b6a861c9c987fbfea:src/Models/Banco/Base/Banco.php

abstract class Banco implements BancoInterface
{
    protected $codigo_banco;
    protected $codigo_compensacao;
    protected $codigo_moeda;
    protected $nosso_numero_inicio;

    public function getCodigoBanco()
    {
        return $this->codigo_banco;
    }

    public function getCodigoMoeda()
    {
        return $this->codigo_moeda;
    }

    public function getCodigoCompensacao()
    {
        $this->codigo_compensacao;
    }

    public function getInicioNossoNumero()
    {
        return $this->nosso_numero_inicio;
    }
}