<?php
<<<<<<< HEAD:src/Models/Bancos/Contracts/BancoInterface.php
namespace CbCaio\Boletos\Models\Bancos\Contracts;
=======
namespace  CbCaio\Boletos\Models\Bancos\Contracts;
>>>>>>> 1d091278335013eb13ca290b6a861c9c987fbfea:src/Models/Banco/Contracts/BancoInterface.php

interface BancoInterface
{
    /**
     * @param integer $modalidade
     * @param integer $emissao
     */
    public function __construct($modalidade, $emissao);

    public function getCodigoBanco();

    public function getCodigoMoeda();

    public function getCodigoCompensacao();

    public function getInicioNossoNumero();

}