<?php
namespace CbCaio\Boletos\Models\Beneficiario\Base;

use CbCaio\Boletos\Models\Beneficiario\Contracts\BeneficiarioInterface;

abstract class Beneficiario implements BeneficiarioInterface
{
    protected $razao_social;
    protected $conta;
    protected $agencia;
    protected $carteira;
    protected $cpf_cnpj;
    protected $endereco;
    protected $cidade_estado;

    public function getRazaoSocial()
    {
        return $this->razao_social;
    }

    public function getConta()
    {
        return $this->conta;
    }

    public function getAgencia()
    {
        return $this->agencia;
    }

    public function getCarteira()
    {
        return $this->carteira;
    }

    public function getCpfCnpj()
    {
        return $this->cpf_cnpj;
    }

    public function getEndereco()
    {
        return $this->endereco;
    }

    public function getCidadeEstado()
    {
        return $this->cidade_estado;
    }

}