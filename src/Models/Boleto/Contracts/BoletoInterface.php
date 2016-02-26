<?php
namespace Boletos\Models\Boletos\Contracts;

interface BoletoInterface
{
    public function getLinhaDigitavelFormatada();
    public function getNossoNumeroFormatado();
    public function getCodigoBarras();
    public function adicionaDemonstrativo($string);
    public function adicionaInstrucao($string);
    public function processaDadosBoleto();
}