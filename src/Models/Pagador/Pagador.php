<?php
namespace CbCaio\Boletos\Models\Pagador\Base;

use CbCaio\Boletos\Models\Pagador\Contracts\PagadorInterface;

class Pagador implements PagadorInterface
{
    protected $nome;
    protected $endereco;
    protected $cidade;
    protected $estado;
    protected $cep;
    protected $cpf_cnpj;

    public function getNome()
    {
        return $this->nome;
    }

    public function getEndereco()
    {
        return $this->endereco;
    }

    public function getCidade()
    {
        return $this->cidade;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function getCep()
    {
        return $this->cep;
    }

    public function getCpfCnpj()
    {
        return $this->cpf_cnpj;
    }

    public function getCidadeEstadoCep()
    {
        return $this->getCidade() . ', ' . $this->getEstado() . ' - ' . $this->getCep();
    }
}