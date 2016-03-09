<?php
namespace CbCaio\Boletos\Models\Beneficiario\Base;
use  CbCaio\Boletos\Models\Beneficiario\Contracts\BeneficiarioInterface;

abstract class Beneficiario implements BeneficiarioInterface
{
    /**
     * All of the user's attributes.
     *
     * @var array
     */
    protected $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function getRazaoSocial()
    {
        return $this->attributes['razao_social'];
    }

    public function getConta()
    {
        return $this->attributes['conta'];
    }

    public function getAgencia()
    {
        return $this->attributes['agencia'];
    }

    public function getCarteira()
    {
        return $this->attributes['carteira'];
    }

    public function getCpfCnpj()
    {
        return $this->attributes['cpf_cnpj'];
    }

    public function getEndereco()
    {
        return $this->attributes['endereco'];
    }

    public function getCidadeEstado()
    {
        return $this->attributes['cidade_estado'];
    }

}