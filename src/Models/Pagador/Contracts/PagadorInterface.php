<?php
namespace CbCaio\Boletos\Models\Pagador\Contracts;

interface PagadorInterface
{
    public function getNome();

    public function getEndereco();

    public function getCidade();

    public function getEstado();

    public function getCep();

    public function getCpfCnpj();

    public function getCidadeEstadoCep();
}