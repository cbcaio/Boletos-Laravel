<?php
namespace CbCaio\Boletos\Models\Pagador;

use CbCaio\Boletos\Models\Pagador\Contracts\PagadorInterface;

class Pagador implements PagadorInterface
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

    public function getNome()
    {
        return $this->attributes['nome'];
    }

    public function getEndereco()
    {
        return $this->attributes['endereco'];
    }

    public function getCidade()
    {
        return $this->attributes['cidade'];
    }

    public function getEstado()
    {
        return $this->attributes['estado'];
    }

    public function getCep()
    {
        return $this->attributes['cep'];
    }

    public function getCpfCnpj()
    {
        return $this->attributes['cpf_cnpj'];
    }

    public function getCidadeEstadoCep()
    {
        return $this->getCidade() . ', ' . $this->getEstado() . ' - ' . $this->getCep();
    }
}