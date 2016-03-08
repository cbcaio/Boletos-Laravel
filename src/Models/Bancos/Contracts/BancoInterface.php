<?php
namespace CbCaio\Boletos\Models\Bancos\Contracts;

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