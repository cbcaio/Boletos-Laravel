<?php
namespace  CbCaio\Boletos\Models\Beneficiario\Contracts;

interface BeneficiarioInterface
{
    public function getAgencia();

    public function getConta();

    public function getRazaoSocial();

    public function getCpfCnpj();

    public function getEndereco();

    public function getCidadeEstado();

    public function getCarteira();

    public function getCodigoBeneficiario();

}